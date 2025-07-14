<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    use HasFactory;

    protected $table = 'ruta';
    protected $primaryKey = 'id_ruta';

    protected $fillable = [
        'nombre',
        'codigo',
        'id_oficina',
        'id_usuario', // Asegúrate que existe en tu migración
        'creada_en',
        'activa',
        'id_tipo_documento',
        'id_tipo_cobro',
        'agregar_ceros_cantidades',
        'editar_interes_credito',
        'considerar_domingos_pago',
        'enrutamiento_automatico',
        'porcentajes_credito',
        'cobradores_agregan_gastos'
    ];


    protected $casts = [
        'activa' => 'boolean',
        'agregar_ceros_cantidades' => 'boolean',
        'editar_interes_credito' => 'boolean',
        'considerar_domingos_pago' => 'boolean',
        'enrutamiento_automatico' => 'boolean',
        'cobradores_agregan_gastos' => 'boolean',
        'creada_en' => 'date'
    ];

    // Relación con Oficina
    public function oficina()
    {
        return $this->belongsTo(Oficina::class, 'id_oficina', 'id_oficina');
    }

    // Relación con Usuario (vendedor)
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }

    // Relación con TipoDocumento
    public function tipoDocumento()
    {
        return $this->belongsTo(TipoDocumento::class, 'id_tipo_documento', 'id_tipo_documento');
    }

    // Relación con TipoCobro
    public function tipoCobro()
    {
        return $this->belongsTo(TipoCobro::class, 'id_tipo_cobro', 'id_tipo_cobro');
    }
}