<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogActividad extends Model
{
    protected $table = 'log_actividades';
    protected $fillable = ['user_id', 'tipo', 'mensaje', 'metadata'];

    // ¡Añade esta línea!
    // Esto le dice a Laravel que el campo 'metadata' debe ser tratado como un array
    protected $casts = [
        'metadata' => 'array',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Método para registrar una nueva actividad
    public static function registrar($tipo, $mensaje, $metadata = null)
    {
        return self::create([
            'user_id' => auth()->id(),
            'tipo' => $tipo,
            'mensaje' => $mensaje,
            'metadata' => $metadata // Laravel se encargará de convertir el array a JSON aquí
        ]);
    }
}