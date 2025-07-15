<?php

namespace Database\Seeders;

use App\Models\OrdenCobro;
use Illuminate\Database\Seeder;

class OrdenCobroSeeder extends Seeder
{
    public function run()
    {
        $ordenesCobro = [
            [
                'nombre' => 'Primero', 
                'descripcion' => 'Cobrar primero este crédito',
                'activo' => true
            ],
            [
                'nombre' => 'Último', 
                'descripcion' => 'Cobrar al final este crédito',
                'activo' => true
            ],
        
        ];

        foreach ($ordenesCobro as $orden) {
            OrdenCobro::firstOrCreate(
                ['nombre' => $orden['nombre']],
                $orden
            );
        }
    }
}