<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoPago extends Model
{
    use HasFactory;

    protected $table = 'forma_pago';
    protected $primaryKey = 'id_forma_pago';

    protected $fillable = [
        'nombre',
        'descripcion',
        'dias_frecuencia',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'dias_frecuencia' => 'integer'
    ];

    /**
     * Scope para formas de pago activas
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Relación con los créditos que usan esta forma de pago
     */
    public function creditos()
    {
        return $this->hasMany(Creditos::class, 'forma_pago', 'id_forma_pago');
    }

    /**
     * Obtener el nombre descriptivo con días de frecuencia
     */
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} (cada {$this->dias_frecuencia} días)";
    }
}