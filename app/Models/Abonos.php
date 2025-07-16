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
        'fecha_pago',
        'monto_abono',
        'saldo_anterior',
        'saldo_posterior',
        'numero_cuota',
        'coordenadas_gps',
        'observaciones',
        'estado',
        'es_cuota_completa'
    ];

    protected $casts = [
        'fecha_pago' => 'datetime',
        'monto_abono' => 'decimal:2',
        'saldo_anterior' => 'decimal:2',
        'saldo_posterior' => 'decimal:2',
        'es_cuota_completa' => 'boolean',
        'coordenadas_gps' => 'array'
    ];

    const ESTADO_REGISTRADO = 'registrado';
    const ESTADO_APROBADO = 'aprobado';
    const ESTADO_ANULADO = 'anulado';

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

    public function conceptos()
    {
        return $this->hasMany(ConceptoCredito::class, 'id_abono');
    }

    public function getTotalAbonadoAttribute()
    {
        return $this->conceptos->sum('monto');
    }

    public function getEstaCompletoAttribute()
    {
        return abs($this->total_abonado - $this->monto_abono) < 0.01; // Considera decimales
    }

    public function getMetodosPagoAttribute()
    {
        return $this->conceptos->pluck('tipo_concepto')->unique()->implode(', ');
    }

    public static function registrarConConceptos(array $datosAbono, array $conceptos)
    {
        return DB::transaction(function () use ($datosAbono, $conceptos) {
            // Validar crédito
            if (empty($datosAbono['id_credito'])) {
                throw new \Exception('Debe especificar un crédito para el abono');
            }

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
        $this->save();
        
        $this->credito->actualizarSaldo();
    }

    public static function obtenerHistorialCompleto($creditoId)
    {
        return self::with(['conceptos', 'usuario'])
            ->where('id_credito', $creditoId)
            ->orderBy('fecha_pago', 'desc')
            ->get()
            ->map(function ($abono) {
                return [
                    'fecha' => $abono->fecha_pago->format('d/m/Y'),
                    'hora' => $abono->fecha_pago->format('H:i'),
                    'conceptos' => $abono->conceptos->map(function ($concepto) {
                        return [
                            'tipo' => $concepto->tipo_concepto,
                            'monto' => 'S/ '.number_format($concepto->monto, 2),
                            'comprobante' => $concepto->foto_comprobante
                        ];
                    }),
                    'total' => 'S/ '.number_format($abono->total_abonado, 2),
                    'saldo' => 'S/ '.number_format($abono->saldo_posterior, 2),
                    'usuario' => $abono->usuario->name,
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