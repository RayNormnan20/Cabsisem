<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('abonos', function (Blueprint $table) {
            $table->id('id_abono');

            // Relaciones
            $table->unsignedBigInteger('id_credito');
            $table->unsignedBigInteger('id_cliente');
            $table->unsignedBigInteger('id_ruta');
            $table->unsignedBigInteger('id_usuario');

            // Datos del pago
            $table->dateTime('fecha_pago')->useCurrent();
            $table->decimal('monto_abono', 12, 2);
            $table->decimal('saldo_anterior', 12, 2);
            $table->decimal('saldo_posterior', 12, 2);

            // Información adicional
            $table->json('coordenadas_gps')->nullable();
            $table->text('observaciones')->nullable();

            // Estado
            $table->enum('estado', [
                'Abonado',
                'anulado'
            ])->default('Abonado');

            $table->timestamps();
            $table->softDeletes();

            // Claves foráneas
            $table->foreign('id_credito', 'fk_abonos_creditos')
                  ->references('id_credito')
                  ->on('creditos')
                  ->onDelete('restrict');

            $table->foreign('id_cliente', 'fk_abonos_clientes')
                  ->references('id_cliente')
                  ->on('clientes')
                  ->onDelete('restrict');

            $table->foreign('id_ruta', 'fk_abonos_rutas')
                  ->references('id_ruta')
                  ->on('ruta')
                  ->onDelete('restrict');

            $table->foreign('id_usuario', 'fk_abonos_usuarios')
                  ->references('id')
                  ->on('users')
                  ->onDelete('restrict');
        });

        // Modificación de conceptos_credito
        Schema::table('conceptos_credito', function (Blueprint $table) {
            $table->unsignedBigInteger('id_abono')
                  ->nullable()
                  ->after('id_credito');

            $table->foreign('id_abono', 'fk_conceptos_abonos')
                  ->references('id_abono')
                  ->on('abonos')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        // Eliminar la relación primero
        Schema::table('conceptos_credito', function (Blueprint $table) {
            $table->dropForeign(['id_abono']);
            $table->dropColumn('id_abono');
        });

        // Eliminar la tabla abonos
        Schema::dropIfExists('abonos');
    }
};
