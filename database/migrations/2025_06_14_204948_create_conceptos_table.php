<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('conceptos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
            $table->enum('tipo', ['Ingresos', 'Gastos']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('conceptos');
    }
};