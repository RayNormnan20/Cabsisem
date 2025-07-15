<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('forma_pago', function (Blueprint $table) {
            $table->id('id_forma_pago');
            $table->string('nombre', 50);
            $table->string('descripcion', 100);
            $table->integer('dias_frecuencia');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('forma_pago');
    }
};