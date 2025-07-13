<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vista extends Model
{
    protected $table = 'vistas';

    protected $fillable = [
        'nombre',
    ];

    public $timestamps = false;
}
