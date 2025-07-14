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
        'codigo',
        'nombre', 
        'descripcion',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    /**
     * Obtener los créditos con este orden de cobro
     */
    public function creditos()
    {
        return $this->hasMany(Creditos::class, 'id_orden_cobro');
    }

    /**
     * Scope para órdenes de cobro activas
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Obtener el nombre con descripción abreviada
     */
    public function getNombreDescriptivoAttribute()
    {
        return "{$this->nombre} ({$this->descripcion})";
    }
}