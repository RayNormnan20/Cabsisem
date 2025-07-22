<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    protected $table = 'vista_movimientos';

    public $timestamps = false;

    protected $primaryKey = 'id'; // Ojo: puede haber IDs duplicados por el union
    public $incrementing = false;

    protected $guarded = [];
}