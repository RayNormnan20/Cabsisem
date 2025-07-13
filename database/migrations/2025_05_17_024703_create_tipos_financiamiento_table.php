<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tipos_financiamiento', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique(); // El campo 'nombre' será único
            $table->text('descripcion')->nullable(); // 'descripcion' puede ser nula
            $table->string('color')->default('#6b7280'); // 'color' tiene un valor predeterminado
            $table->boolean('is_default')->default(false); // 'is_default' con valor por defecto de 'false'
            $table->timestamps(); // Laravel manejará 'created_at' y 'updated_at'
        });
    }

    public function down()
    {
        Schema::dropIfExists('tipos_financiamiento');
    }
};
