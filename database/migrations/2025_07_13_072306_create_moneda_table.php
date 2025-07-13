<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('moneda', function (Blueprint $table) {
            $table->id('id_moneda'); // Esto es crítico
            $table->string('codigo', 10)->unique();
            $table->string('nombre', 50);
            $table->string('simbolo', 5);
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('moneda');
    }
};
