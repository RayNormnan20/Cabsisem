<?php

namespace Database\Seeders;

use App\Models\TipoPago;
use Illuminate\Database\Seeder;

class FormaPagoSeeder extends Seeder
{
    public function run()
    {
        $formasPago = [
            ['codigo' => 'DIARIO', 'nombre' => 'Diario', 'dias_frecuencia' => 1, 'activo' => true],
            ['codigo' => 'SEMANAL', 'nombre' => 'Semanal', 'dias_frecuencia' => 7, 'activo' => true],
            ['codigo' => 'BISEMANAL', 'nombre' => 'Bisemanal', 'dias_frecuencia' => 3, 'activo' => true],
            ['codigo' => 'QUINCENAL', 'nombre' => 'Quincenal', 'dias_frecuencia' => 15, 'activo' => true],
            ['codigo' => 'MENSUAL', 'nombre' => 'Mensual', 'dias_frecuencia' => 30, 'activo' => true],
        ];

        foreach ($formasPago as $forma) {
            TipoPago::create($forma);
        }
    }
}