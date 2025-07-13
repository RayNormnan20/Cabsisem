<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCobro extends Model
{
    use HasFactory;

    protected $table = 'tipo_cobro';
    protected $primaryKey = 'id_tipo_cobro';

    protected $fillable = [
        'nombre',
        'descripcion',
        'dias_frecuencia'
    ];
}
