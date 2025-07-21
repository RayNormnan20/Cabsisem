<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Movimiento extends Model
{
    protected $table = null;
    public $incrementing = false;

    protected $fillable = [
        'id', 'tipo', 'fecha', 'concepto_id', 'concepto_nombre', 
        'concepto_tipo', 'cliente_id', 'cliente_nombre', 
        'usuario_id', 'usuario_nombre', 'monto', 'observaciones',
        'source_type', 'source_id'
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'monto' => 'decimal:2'
    ];

    public static function obtenerTodos(): Collection
    {
        $movimientos = collect();
        
        // Obtener créditos como movimientos
        $creditos = Creditos::with(['concepto', 'cliente'])->get();
        foreach ($creditos as $credito) {
            $movimientos->push(new static([
                'id' => 'c-' . $credito->id_credito,
                'tipo' => 'credito',
                'fecha' => $credito->fecha_credito,
                'concepto_id' => $credito->id_concepto,
                'concepto_nombre' => $credito->concepto->nombre ?? '',
                'concepto_tipo' => $credito->concepto->tipo ?? '',
                'cliente_id' => $credito->id_cliente,
                'cliente_nombre' => $credito->cliente->nombre ?? '',
                'monto' => $credito->valor_credito,
                'observaciones' => 'Crédito ' . $credito->id_credito,
                'source_type' => Creditos::class,
                'source_id' => $credito->id_credito
            ]));
        }
        
        // Obtener abonos como movimientos
        $abonos = Abonos::with(['concepto', 'cliente', 'usuario'])->get();
        foreach ($abonos as $abono) {
            $movimientos->push(new static([
                'id' => 'a-' . $abono->id_abono,
                'tipo' => 'abono',
                'fecha' => $abono->fecha_pago,
                'concepto_id' => $abono->id_concepto,
                'concepto_nombre' => $abono->concepto->nombre ?? '',
                'concepto_tipo' => $abono->concepto->tipo ?? '',
                'cliente_id' => $abono->id_cliente,
                'cliente_nombre' => $abono->cliente->nombre ?? '',
                'usuario_id' => $abono->id_usuario,
                'usuario_nombre' => $abono->usuario->name ?? '',
                'monto' => $abono->monto_abono,
                'observaciones' => $abono->observaciones,
                'source_type' => Abonos::class,
                'source_id' => $abono->id_abono
            ]));
        }
        
        return $movimientos->sortByDesc('fecha');
    }

    // Relación polimórfica al modelo original
    public function source()
    {
        return $this->morphTo('source', 'source_type', 'source_id');
    }

    // Método para evitar guardado accidental
    public function save(array $options = [])
    {
        throw new \Exception('Este modelo es de solo lectura');
    }
}