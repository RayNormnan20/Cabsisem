<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogActividad extends Model
{
    protected $table = 'log_actividades';
    protected $fillable = ['user_id', 'tipo', 'mensaje', 'metadata'];

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
            'metadata' => $metadata
        ]);
    }
}
