<?php

namespace Database\Seeders;

use App\Models\TipoCobro;
use Illuminate\Database\Seeder;

class TipoCobroSeeder extends Seeder
{
    private array $data = [
        [
            'nombre' => 'Diario',
            'descripcion' => 'Pago que se realiza cada día',
            'dias_frecuencia' => 1
        ],
        [
            'nombre' => 'Semanal',
            'descripcion' => 'Pago que se realiza cada semana',
            'dias_frecuencia' => 7
        ],
        [
            'nombre' => 'Quincenal',
            'descripcion' => 'Pago que se realiza cada 15 días',
            'dias_frecuencia' => 15
        ],
        [
            'nombre' => 'Mensual',
            'descripcion' => 'Pago que se realiza cada mes',
            'dias_frecuencia' => 30
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->data as $item) {
            TipoCobro::firstOrCreate(['nombre' => $item['nombre']], $item);
        }
    }
}
