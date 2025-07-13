<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proyectos', function (Blueprint $table) {
                $table->id();
                $table->string('nombre');
                $table->text('descripcion')->nullable();
                $table->text('ubicacion')->nullable();
                $table->date('fecha_inicio')->nullable();
                $table->date('fecha_entrega')->nullable();
                $table->foreignId('estado_proyecto_id')->constrained('estados_proyecto');
                $table->foreignId('empresa_constructora_id')->constrained('empresas');
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proyectos');
    }
};
