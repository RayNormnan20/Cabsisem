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
        'codigo',
        'nombre',
        'dias_frecuencia',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'dias_frecuencia' => 'integer'
    ];

    /**
     * Obtener los créditos con esta forma de pago
     */
    public function creditos()
    {
        return $this->hasMany(Creditos::class, 'id_forma_pago');
    }

    /**
     * Scope para formas de pago activas
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Obtener el nombre completo con los días
     */
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} (cada {$this->dias_frecuencia} días)";
    }
}