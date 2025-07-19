<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Abonos extends Model
{
    use HasFactory;

    protected $table = 'abonos';
    protected $primaryKey = 'id_abono';

    protected $fillable = [
        'id_credito',
        'id_cliente',
        'id_ruta',
        'id_usuario',
        'id_concepto', // Añadido para relación con conceptos
        'fecha_pago',
        'monto_abono',
        'saldo_anterior',
        'saldo_posterior',
        'coordenadas_gps',
        'observaciones',
        'estado'
    ];

    protected $casts = [
        'fecha_pago' => 'datetime',
        'monto_abono' => 'decimal:2',
        'saldo_anterior' => 'decimal:2',
        'saldo_posterior' => 'decimal:2',
        'coordenadas_gps' => 'array',
        'estado' => 'boolean'
    ];


    public function credito()
    {
        return $this->belongsTo(Creditos::class, 'id_credito');
    }

    public function cliente()
    {
        return $this->belongsTo(Clientes::class, 'id_cliente');
    }

    public function ruta()
    {
        return $this->belongsTo(Ruta::class, 'id_ruta');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function concepto()
    {
        return $this->belongsTo(Concepto::class, 'id_concepto');
    }

    // Relación con conceptos de crédito (métodos de pago)
    public function conceptos()
    {
        return $this->hasMany(ConceptoCredito::class, 'id_abono');
    }

    // Relación con conceptos de abono (alternativa)
    public function conceptosabonos()
    {
        return $this->hasMany(ConceptoAbono::class, 'id_abono');
    }

    public function getTotalAbonadoAttribute()
    {
        return $this->conceptos->sum('monto');
    }

    public function getEstaCompletoAttribute()
    {
        // Ahora podemos usar directamente el campo estado booleano
        // o mantener esta lógica si es diferente
        return $this->estado && (abs($this->total_abonado - $this->monto_abono)) < 0.01;
    }

    public function getMetodosPagoAttribute()
    {
        return $this->conceptos->pluck('tipo_concepto')->unique()->implode(', ');
    }

    public static function registrarConConceptos(array $datosAbono, array $conceptos)
    {
        return DB::transaction(function () use ($datosAbono, $conceptos) {
            if (empty($datosAbono['id_credito'])) {
                throw new \Exception('Debe especificar un crédito para el abono');
            }

            // Asegurar que el concepto "Abono" esté asignado
            if (empty($datosAbono['id_concepto'])) {
                $conceptoAbono = Concepto::where('nombre', 'Abono')->first();
                if (!$conceptoAbono) {
                    throw new \Exception('El concepto "Abono" no existe en la base de datos');
                }
                $datosAbono['id_concepto'] = $conceptoAbono->id;
            }

            // Estado por defecto true (completo)
            $datosAbono['estado'] = true;

            $abono = self::create($datosAbono);

            foreach ($conceptos as $concepto) {
                $abono->conceptos()->create($concepto);
            }

            $abono->actualizarSaldos();

            return $abono;
        });
    }

    public function actualizarSaldos()
    {
        $this->saldo_posterior = $this->saldo_anterior - $this->total_abonado;
        
        // Marcar como completo si el saldo posterior es coherente
        $this->estado = abs($this->saldo_posterior - ($this->saldo_anterior - $this->total_abonado)) < 0.01;
        
        $this->save();

        $this->credito->actualizarSaldo();
    }

    public static function obtenerHistorialCompleto($creditoId)
    {
        return self::with(['conceptos', 'usuario', 'concepto'])
            ->where('id_credito', $creditoId)
            ->orderBy('fecha_pago', 'desc')
            ->get()
            ->map(function ($abono) {
                return [
                    'fecha' => $abono->fecha_pago->format('d/m/Y'),
                    'hora' => $abono->fecha_pago->format('H:i'),
                    'concepto_principal' => $abono->concepto->nombre ?? 'Abono',
                    'conceptos' => $abono->conceptos->map(function ($concepto) {
                        return [
                            'tipo' => $concepto->tipo_concepto,
                            'monto' => 'S/ ' . number_format($concepto->monto, 2),
                            'comprobante' => $concepto->foto_comprobante
                        ];
                    }),
                    'total' => 'S/ ' . number_format($abono->total_abonado, 2),
                    'saldo' => 'S/ ' . number_format($abono->saldo_posterior, 2),
                    'usuario' => $abono->usuario->name,
                    'completo' => $abono->estado ? 'Sí' : 'No',
                    'gps' => $abono->coordenadas_gps ? '✔' : ''
                ];
            });
    }

    public static function validarMonto($creditoId, $monto)
    {
        $credito = Creditos::findOrFail($creditoId);
        return $monto <= $credito->saldo_actual;
    }
}