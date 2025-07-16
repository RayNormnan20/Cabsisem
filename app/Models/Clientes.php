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
        'activo',
        'id_ruta',
        'id_usuario_creador'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // Relación con TipoDocumento
    public function tipoDocumento()
    {
        return $this->belongsTo(TipoDocumento::class, 'id_tipo_documento');
    }

    // Relación con Ruta (¡NUEVA RELACIÓN!)
    // Un cliente pertenece a una ruta
    public function ruta()
    {
        return $this->belongsTo(Ruta::class, 'id_ruta');
    }

    // Relación con Créditos
    public function creditos()
    {
        return $this->hasMany(Creditos::class, 'id_cliente');
    }

    // Relación directa con abonos (si la tabla abonos tiene id_cliente)
    // Esto es útil si un abono puede existir sin un crédito directo o si necesitas un acceso rápido
    public function abonosDirectos()
    {
        return $this->hasMany(Abonos::class, 'id_cliente');
    }

    // Relación a través de créditos para obtener todos los abonos de un cliente
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

    // Accessor para obtener el nombre completo del cliente
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->apellido}";
    }


    public function scopeDeRuta($query, $rutaId)
    {
        return $query->where('id_ruta', $rutaId);
    }

    public function creador()
{
    return $this->belongsTo(User::class, 'id_usuario_creador');
}
}