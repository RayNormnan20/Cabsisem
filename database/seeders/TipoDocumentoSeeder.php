<?php

namespace Database\Seeders;

use App\Models\TipoDocumento;
use Illuminate\Database\Seeder;

class TipoDocumentoSeeder extends Seeder
{
    private array $data = [
        [
            'nombre' => 'Cédula de Ciudadanía',
            'descripcion' => 'Documento nacional de identidad',
            'valido_para_credito' => true
        ],
        [
            'nombre' => 'CPF',
            'descripcion' => 'Cadastro de Pessoas Físicas (Brasil)',
            'valido_para_credito' => true
        ],
        [
            'nombre' => 'INE',
            'descripcion' => 'Credencial para votar (México)',
            'valido_para_credito' => true
        ],
        [
            'nombre' => 'DNI',
            'descripcion' => 'Documento Nacional de Identidad (Argentina/Perú)',
            'valido_para_credito' => true
        ],
        [
            'nombre' => 'RG',
            'descripcion' => 'Registro Geral (Brasil)',
            'valido_para_credito' => true
        ],
        [
            'nombre' => 'RUC',
            'descripcion' => 'Registro Único de Contribuyentes (Perú/Ecuador)',
            'valido_para_credito' => true
        ],
        [
            'nombre' => 'DPI',
            'descripcion' => 'Documento Personal de Identificación (Guatemala)',
            'valido_para_credito' => true
        ],
        [
            'nombre' => 'Tarjeta de Identidad',
            'descripcion' => 'Documento de identidad para menores de edad',
            'valido_para_credito' => false
        ],
        [
            'nombre' => 'Pasaporte',
            'descripcion' => 'Documento de identificación para viajes internacionales',
            'valido_para_credito' => true
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
            TipoDocumento::firstOrCreate(['nombre' => $item['nombre']], $item);
        }
    }
}
