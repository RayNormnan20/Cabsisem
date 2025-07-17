<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConceptosAbonoTable extends Migration
{
    public function up()
    {
        Schema::create('conceptos_abono', function (Blueprint $table) {
            $table->id('id_concepto_abono');
            $table->unsignedBigInteger('id_abono');

            $table->string('tipo_concepto');
            $table->decimal('monto', 10, 2);
            $table->string('foto_comprobante')->nullable();
            $table->string('referencia')->nullable();

            // Solo agregamos la columna, sin foreign key
            $table->unsignedBigInteger('id_caja')->nullable()->comment('Relación con cajas se agregará posteriormente');

            $table->timestamps();

            $table->foreign('id_abono')
                  ->references('id_abono')
                  ->on('abonos')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('conceptos_abono');
    }
}
