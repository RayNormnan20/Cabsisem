<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfiguracionAbonos extends Model
{
    use HasFactory;

    protected $table = 'configuracion_abonos';
    protected $primaryKey = 'id_configuracion';

    protected $fillable = [
        'id_oficina',
        'max_abonos_dia',
        'sin_limite',
        'aplica_desde',
        'aplica_hasta'
    ];

    protected $casts = [
        'sin_limite' => 'boolean',
        'aplica_desde' => 'datetime:H:i',
        'aplica_hasta' => 'datetime:H:i'
    ];

    public function oficina()
    {
        return $this->belongsTo(Oficina::class, 'id_oficina');
    }
}
