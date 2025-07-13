<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoInmueble extends Model
{
    protected $table = 'tipo_inmueble';

    protected $fillable = [
        'nombre',
    ];

    public $timestamps = false;
}

