<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    use HasFactory;

    protected $table = 'tipo_documento';
    protected $primaryKey = 'id_tipo_documento';

    protected $fillable = [
        'nombre',
        'descripcion',
        'valido_para_credito'
    ];

    protected $casts = [
        'valido_para_credito' => 'boolean'
    ];

    protected $attributes = [
        'valido_para_credito' => true
    ];
}
