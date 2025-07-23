<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class YapeCliente extends Model
{
    use HasFactory;

    protected $table = 'yape_clientes';

    protected $fillable = [
        'id_cliente',
        'nombre',
        'yape',
        'user_id',
        'monto',
        'entregar',
        'total',
        'devolucion',
    ];

    // Relaciones
    public function cliente()
    {
        return $this->belongsTo(Clientes::class, 'id_cliente');
    }



    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
