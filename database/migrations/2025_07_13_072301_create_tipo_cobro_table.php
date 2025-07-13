<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tipo_cobro', function (Blueprint $table) {
            $table->id('id_tipo_cobro');
            $table->string('nombre', 50);
            $table->string('descripcion', 255)->nullable();
            $table->integer('dias_frecuencia')->comment('Número de días entre cobros');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tipo_cobro');
    }
};
