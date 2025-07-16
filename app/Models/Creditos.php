<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Creditos extends Model
{
    use HasFactory;

    protected $table = 'creditos';
    protected $primaryKey = 'id_credito';

    protected $fillable = [
        'id_cliente',
        'id_ruta',
        'fecha_credito',
        'valor_credito',
        'porcentaje_interes',
        'forma_pago',
        'dias_plazo',
        'orden_cobro',
        'saldo_actual',
        'valor_cuota',
        'numero_cuotas',
        'fecha_vencimiento',
        'fecha_proximo_pago'
    ];

    protected $casts = [
        'fecha_credito' => 'date',
        'valor_credito' => 'decimal:2',
        'porcentaje_interes' => 'decimal:2',
        'saldo_actual' => 'decimal:2',
        'valor_cuota' => 'decimal:2',
        'fecha_vencimiento' => 'date',
        'fecha_proximo_pago' => 'date'
    ];

    public function cliente()
    {
        return $this->belongsTo(Clientes::class, 'id_cliente');
    }

    public function tipoPago()
    {
        return $this->belongsTo(TipoPago::class, 'forma_pago');
    }

    public function ordenCobro()
    {
        return $this->belongsTo(OrdenCobro::class, 'orden_cobro');
    }

    public function abonos()
    {
        return $this->hasMany(Abonos::class, 'id_credito');
    }

    public function conceptosCredito()
    {
        return $this->hasMany(ConceptoCredito::class, 'id_credito');
    }

    public function ruta()
    {
        return $this->belongsTo(Ruta::class, 'id_ruta');
    }

    public function scopeActivos($query)
    {
        return $query->where('saldo_actual', '>', 0);
    }

    public function scopePagados($query)
    {
        return $query->where('saldo_actual', '<=', 0);
    }

    public function getInteresTotalAttribute()
    {
        return $this->valor_credito * ($this->porcentaje_interes / 100);
    }

    public function getMontoTotalAttribute()
    {
        return $this->valor_credito + $this->interes_total;
    }

    public function getCuotasPagadasAttribute()
    {
        return $this->abonos()->count();
    }

    public function getCuotasPendientesAttribute()
    {
        return max(0, $this->numero_cuotas - $this->cuotas_pagadas);
    }

    public function scopeDeRuta($query, $rutaId)
    {
        return $query->where('id_ruta', $rutaId);
    }

    public function actualizarSaldo()
    {
        $this->saldo_actual = $this->valor_credito - $this->abonos()->sum('monto_abono');
        $this->save();
    }
    
}