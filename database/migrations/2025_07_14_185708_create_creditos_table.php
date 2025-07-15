<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creditos', function (Blueprint $table) {
            $table->id('id_credito');
            
            $table->unsignedBigInteger('id_cliente');
            $table->foreign('id_cliente')->references('id_cliente')->on('clientes');
            
            $table->date('fecha_credito');
            $table->decimal('valor_credito', 12, 2);
            $table->decimal('porcentaje_interes', 5, 2);
            
            $table->unsignedBigInteger('forma_pago');
            $table->foreign('forma_pago')->references('id_forma_pago')->on('forma_pago');
            
            $table->integer('dias_plazo');
            
            $table->unsignedBigInteger('orden_cobro');
            $table->foreign('orden_cobro')->references('id_orden_cobro')->on('orden_cobro');
            
            $table->decimal('saldo_actual', 12, 2);
            $table->decimal('valor_cuota', 10, 2);
            $table->integer('numero_cuotas');
            $table->date('fecha_vencimiento');
            $table->date('fecha_proximo_pago');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creditos');
    }
};