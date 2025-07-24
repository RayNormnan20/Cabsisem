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
        Schema::create('clientes', function (Blueprint $table) {
            $table->bigIncrements('id_cliente');

            // Relación con usuario creador
            $table->foreignId('id_usuario_creador')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            // Información del documento
            $table->foreignId('id_tipo_documento')
                ->constrained('tipo_documento', 'id_tipo_documento');
            $table->string('numero_documento', 20);

            // Información personal
            $table->string('nombre', 100);
            $table->string('apellido', 100);
            $table->string('celular', 20)->nullable();
            $table->string('telefono', 20)->nullable();

            // Información de dirección
            $table->string('direccion', 255);
            $table->string('direccion2', 255)->nullable();
            $table->string('ciudad', 100)->nullable();

            // Información de negocio
            $table->string('nombre_negocio', 100)->nullable();

            // Estado
            $table->boolean('activo')->default(true);

            // Relación con ruta
            $table->foreignId('id_ruta')
                ->constrained('ruta', 'id_ruta')
                ->onDelete('restrict');

            // Campos para fotos del cliente (nuevos)
            $table->string('foto1_path')->nullable()->comment('Ruta de la primera foto del cliente');
            $table->string('foto2_path')->nullable()->comment('Ruta de la segunda foto del cliente');

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('numero_documento');
            $table->index(['nombre', 'apellido']);
            $table->index('id_ruta');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientes');
    }
};
