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
                'codigo' => 'PRIMERO', 
                'nombre' => 'Primero', 
                'descripcion' => 'Cobrar primero este crédito',
                'activo' => true
            ],
            [
                'codigo' => 'ULTIMO', 
                'nombre' => 'Último', 
                'descripcion' => 'Cobrar al final este crédito',
                'activo' => true
            ],
            [
                'codigo' => 'ALEATORIO', 
                'nombre' => 'Aleatorio', 
                'descripcion' => 'Cobrar en orden aleatorio',
                'activo' => true
            ],
        ];

        foreach ($ordenesCobro as $orden) {
            OrdenCobro::create($orden);
        }
    }
}