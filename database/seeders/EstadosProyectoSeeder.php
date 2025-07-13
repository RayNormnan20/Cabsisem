<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadosProyectoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insertar los estados de proyecto
        DB::table('estados_proyecto')->insert([
            [
                'nombre' => 'Planificado',
                'descripcion' => 'El proyecto está en la fase de planificación, aún no se ha iniciado la construcción.',
            ],
            [
                'nombre' => 'Construcción',
                'descripcion' => 'El proyecto está en proceso de construcción.',
            ],
            [
                'nombre' => 'Terminado',
                'descripcion' => 'El proyecto ha sido completado y está finalizado.',
            ]
        ]);
    }
}
