<?php

namespace Database\Seeders;

use App\Models\FormaContacto;
use App\Models\NivelInteres;
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
        $this->call(ActivitySeeder::class);
        $this->call(Estados_DepartamentoSeeder::class);
        $this->call(VistaSeeder::class);
        $this->call(TipoInmuebleSeeder::class);
        $this->call(MonedaSeeder::class);
        $this->call(TiposDepartamentoSeeder::class);
        $this->call(EstadosProyectoSeeder::class);
        $this->call(EstadoSeeder::class);
        $this->call(TiposFinanciamientoSeeder::class);

        $this->call(FormaContactoSeeder::class);
        $this->call(TipoGestionSeeder::class);
        $this->call(ComoSeEnteroSeeder::class);
        $this->call(NivelInteresSeeder::class);
        $this->call(TipoDocumentoSeeder::class);
        
        
              
        
        
    }
}
