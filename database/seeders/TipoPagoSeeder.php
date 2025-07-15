<?php

namespace Database\Seeders;

use App\Models\TipoPago;
use Illuminate\Database\Seeder;

class TipoPagoSeeder extends Seeder
{
    private array $data = [
        [
            'nombre' => 'Diario',
            'descripcion' => 'Pago que se realiza cada día',
            'dias_frecuencia' => 1,
            'activo' => true
        ],
        [
            'nombre' => 'Semanal',
            'descripcion' => 'Pago que se realiza cada semana',
            'dias_frecuencia' => 7,
            'activo' => true
        ],
        [
            'nombre' => 'Quincenal',
            'descripcion' => 'Pago que se realiza cada 15 días',
            'dias_frecuencia' => 15,
            'activo' => true
        ],
        [
            'nombre' => 'Mensual',
            'descripcion' => 'Pago que se realiza cada mes',
            'dias_frecuencia' => 30,
            'activo' => true
        ],
    ];

    public function run()
    {
        foreach ($this->data as $item) {
            TipoPago::firstOrCreate(
                ['nombre' => $item['nombre']], // Buscar por nombre para evitar duplicados
                $item
            );
        }
    }
}