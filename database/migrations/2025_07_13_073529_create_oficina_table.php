<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('oficina', function (Blueprint $table) {
            // Estructura básica
            $table->id('id_oficina');
            $table->string('nombre', 100);
            $table->unsignedBigInteger('id_moneda');
            $table->string('pais', 50);
            $table->string('codigo', 20)->nullable();
            $table->string('porcentajes_credito', 255)->comment('Porcentajes separados por comas');

            // Campos booleanos con sus valores por defecto
            $table->boolean('ver_caja_anterior')->default(false);
            $table->boolean('ver_entradas_salidas')->default(false);
            $table->boolean('consultar_cuadre_pasado')->default(false);
            $table->boolean('cobrador_edita_clientes')->default(false);
            $table->boolean('cobrador_ingresos_gastos')->default(true);
            $table->boolean('pedir_base_al_ingresar')->default(false);
            $table->boolean('liquidar_rutas')->default(false);
            $table->boolean('foto_documento_obligatoria')->default(false);
            $table->boolean('cambiar_claves_usuarios')->default(false);
            $table->boolean('creditos_requieren_autorizacion')->default(false);

            $table->timestamps();

            $table->foreign('id_moneda')
                ->references('id_moneda')
                ->on('moneda')
                ->onDelete('restrict'); // O 'set null'/'cascade' según necesites


            $table->index('id_moneda');
            $table->index('codigo');
        });
    }

    public function down()
    {
        Schema::table('oficina', function (Blueprint $table) {
            $table->dropForeign(['id_moneda']);
        });

        Schema::dropIfExists('oficina');
    }
};