<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoFinanciamiento extends Model
{
    // Definir el nombre de la tabla
    protected $table = 'tipos_financiamiento';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre',
        'descripcion',
        'color',
        'is_default',
    ];

    // Deshabilitar timestamps si no los usas
    public $timestamps = false;

    public function departamentos()
    {
        return $this->hasMany(Departamento::class, 'tipo_financiamiento_id');
    }

    // Scope opcional para obtener el tipo de financiamiento por defecto
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    // Mutador para asegurar el formato correcto del color
    public function setColorAttribute($value)
    {
        $this->attributes['color'] = str_starts_with($value, '#') ? $value : '#'.$value;
    }
}

