<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('usuario_ruta', function (Blueprint $table) {
            $table->id();

            // Relación con usuario
            $table->foreignId('user_id')
                ->constrained() // referencia automática a 'users.id'
                ->cascadeOnDelete();

            // Relación con ruta - CORREGIDO para usar id_ruta consistentemente
            $table->unsignedBigInteger('id_ruta'); // Cambiado de 'ruta_id' a 'id_ruta'
            $table->foreign('id_ruta')
                ->references('id_ruta')
                ->on('ruta')
                ->onDelete('cascade');

            $table->boolean('es_principal')
                ->default(true)
                ->comment('Indica si es la ruta principal del usuario');

            $table->timestamps();

            // Índice único compuesto
            $table->unique(['user_id', 'id_ruta'], 'usuario_ruta_unico'); // Actualizado a id_ruta
        });
    }

    public function down()
    {
        Schema::dropIfExists('usuario_ruta');
    }
};