<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id('id_cliente');

            // Document information
            $table->foreignId('id_tipo_documento')->constrained('tipo_documento', 'id_tipo_documento');
            $table->string('numero_documento', 20);

            // Personal information
            $table->string('nombre', 100);
            $table->string('apellido', 100);
            $table->string('celular', 20)->nullable();
            $table->string('telefono', 20)->nullable();

            // Address information
            $table->string('direccion', 255);
            $table->string('direccion2', 255)->nullable();
            $table->string('ciudad', 100)->nullable();

            // Business information
            $table->string('nombre_negocio', 100)->nullable();

            // Status flags
            $table->boolean('activo')->default(true);

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('numero_documento');
            $table->index(['nombre', 'apellido']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('clientes');
    }
};
