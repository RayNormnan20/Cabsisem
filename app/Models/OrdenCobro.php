<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenCobro extends Model
{
    use HasFactory;

    protected $table = 'orden_cobro';
    protected $primaryKey = 'id_orden_cobro';

    protected $fillable = [
        'nombre', 
        'descripcion',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    /**
     * Scope para órdenes de cobro activas
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Relación con los créditos que usan este orden de cobro
     */
    public function creditos()
    {
        return $this->hasMany(Creditos::class, 'orden_cobro', 'id_orden_cobro');
    }
}