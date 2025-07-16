<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clientes extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'clientes';
    protected $primaryKey = 'id_cliente';

    protected $fillable = [
        'id_tipo_documento',
        'numero_documento',
        'nombre',
        'apellido',
        'celular',
        'telefono',
        'direccion',
        'direccion2',
        'ciudad',
        'nombre_negocio',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function tipoDocumento()
    {
        return $this->belongsTo(TipoDocumento::class, 'id_tipo_documento');
    }

    public function creditos()
    {
        return $this->hasMany(Creditos::class, 'id_cliente');
    }

    // Relación directa con abonos (si la tabla abonos tiene id_cliente)
    public function abonosDirectos()
    {
        return $this->hasMany(Abonos::class, 'id_cliente');
    }

    // Relación a través de créditos
    public function abonos()
    {
        return $this->hasManyThrough(
            Abonos::class,
            Creditos::class,
            'id_cliente', // FK en créditos
            'id_credito', // FK en abonos
            'id_cliente', // PK en clientes
            'id_credito'  // PK en créditos
        );
    }

    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->apellido}";
    }

    public function scopeDeRuta($query, $rutaId)
    {
        return $query->whereHas('creditos', function($q) use ($rutaId) {
            $q->where('id_ruta', $rutaId);
        });
    }
}