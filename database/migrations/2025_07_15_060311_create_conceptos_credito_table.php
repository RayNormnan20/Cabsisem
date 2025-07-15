<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConceptosCreditoTable extends Migration
{
    public function up()
    {
        Schema::create('conceptos_credito', function (Blueprint $table) {
            $table->id('id_concepto_credito');

            $table->unsignedBigInteger('id_credito');
            $table->string('tipo_concepto');
            $table->decimal('monto', 10, 2);
            $table->string('foto_comprobante')->nullable(); // Solo para Yape
            $table->unsignedBigInteger('id_credito_anterior')->nullable(); // Para renovación
            $table->unsignedBigInteger('id_caja')->nullable(); // Para caja

            $table->timestamps();

            $table->foreign('id_credito')->references('id_credito')->on('creditos')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('conceptos_credito');
    }
}