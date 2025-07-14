<?php

namespace Database\Seeders;

use App\Models\Moneda;
use Illuminate\Database\Seeder;

class MonedaSeeder extends Seeder
{
    private array $data = [
        [
            'codigo' => 'USD',
            'nombre' => 'Dólar Estadounidense',
            'simbolo' => '$',
            'activa' => true
        ],
        [
            'codigo' => 'CRC',
            'nombre' => 'Colón Costarricense',
            'simbolo' => '₡',
            'activa' => true
        ],
        [
            'codigo' => 'EUR',
            'nombre' => 'Euro',
            'simbolo' => '€',
            'activa' => true
        ],
        [
            'codigo' => 'PYG',
            'nombre' => 'Guaraní Paraguayo',
            'simbolo' => 'Gs',
            'activa' => true
        ],
        [
            'codigo' => 'ARS',
            'nombre' => 'Peso Argentino',
            'simbolo' => '$a',
            'activa' => true
        ],
        [
            'codigo' => 'GTQ',
            'nombre' => 'Quetzal Guatemalteco',
            'simbolo' => 'Q',
            'activa' => true
        ],
        [
            'codigo' => 'BRL',
            'nombre' => 'Real Brasileño',
            'simbolo' => 'R$',
            'activa' => true
        ],
        [
            'codigo' => 'PEN',
            'nombre' => 'Sol Peruano',
            'simbolo' => 'S/.',
            'activa' => true
        ],
    ];

    public function run()
    {
        foreach ($this->data as $item) {
            Moneda::firstOrCreate(['codigo' => $item['codigo']], $item);
        }
    }
}
