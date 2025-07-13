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
        Schema::create('empresas', function (Blueprint $table) {
                $table->id();
                $table->string('nombre');
                $table->string('ruc', 20)->unique();
                $table->text('direccion')->nullable();
                $table->string('telefono', 20)->nullable();
                $table->string('email', 100)->nullable();
                $table->string('representante_legal')->nullable();
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
        Schema::dropIfExists('empresas');
    }
};
