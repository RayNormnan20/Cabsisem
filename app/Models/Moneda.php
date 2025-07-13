<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Moneda extends Model
{
    use HasFactory;

    protected $table = 'moneda';
    protected $primaryKey = 'id_moneda';

    protected $fillable = [
        'codigo',
        'nombre',
        'simbolo',
        'activa'
    ];

    protected $casts = [
        'activa' => 'boolean'
    ];

    protected $attributes = [
        'activa' => true
    ];
}
