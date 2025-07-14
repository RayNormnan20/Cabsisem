<?php

namespace Database\Seeders;

use App\Models\FormaContacto;
use App\Models\Moneda;
use App\Models\NivelInteres;
use App\Models\TipoCobro;
use App\Models\TipoDocumento;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call(DefaultUserSeeder::class);
        $this->call(PermissionsSeeder::class);
        $this->call(TicketTypeSeeder::class);
        $this->call(TicketPrioritySeeder::class);
        $this->call(TicketStatusSeeder::class);
        $this->call(TipoDocumentoSeeder::class);
        $this->call(TipoCobroSeeder::class);
        $this->call(MonedaSeeder::class);

        $this->call(FormaPagoSeeder::class);
        $this->call(OrdenCobroSeeder::class);




    }
}