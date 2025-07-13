<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('configuracion_abonos', function (Blueprint $table) {
            $table->id('id_configuracion');

            $table->unsignedBigInteger('id_oficina');
            $table->foreign('id_oficina')
                  ->references('id_oficina')
                  ->on('oficina')
                  ->onDelete('cascade');

            $table->integer('max_abonos_dia')->default(1)->comment('Valores posibles: 1-6 (como muestra la interfaz)');
            $table->boolean('sin_limite')->default(false)->comment('Cuando es TRUE, muestra "Sin límite" como en la interfaz');
            $table->time('aplica_desde')->default('00:00:00')->comment('Hora de inicio de la restricción');
            $table->time('aplica_hasta')->default('23:59:59')->comment('Hora de fin de la restricción');
            $table->timestamps();

            $table->unique('id_oficina');
        });
    }

    public function down()
    {
        Schema::table('configuracion_abonos', function (Blueprint $table) {
            $table->dropForeign(['id_oficina']);
        });

        Schema::dropIfExists('configuracion_abonos');
    }
};