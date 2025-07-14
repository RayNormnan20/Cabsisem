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
            
            // Cambiar esta línea:
            // $table->foreignId('id_cliente')->constrained('clientes');
            // Por esta:
            $table->unsignedBigInteger('id_cliente');
            $table->foreign('id_cliente')->references('id_cliente')->on('clientes');
            
            $table->date('fecha_credito')->comment('Fecha del Crédito');
            $table->decimal('valor_credito', 12, 2)->comment('Valor del Crédito (ej: 1,000.00)');
            $table->decimal('porcentaje_interes', 5, 2)->comment('Porcentaje de interés (ej: 20)');
            
            $table->enum('forma_pago', ['Diario', 'Semanal', 'Quincenal', 'Mensual', 'Bisemanal'])
                  ->comment('Forma de Pago');
            $table->integer('dias_plazo')->comment('Días del plazo (ej: 30)');
            $table->enum('orden_cobro', ['Primero', 'Último', 'Aleatorio'])
                  ->default('Último')
                  ->comment('Orden de Cobro');
            
            $table->decimal('saldo_actual', 12, 2)->comment('Saldo actual (ej: 1,200.00)');
            $table->decimal('valor_cuota', 10, 2)->comment('Valor de cada cuota (ej: 40.00)');
            $table->integer('numero_cuotas')->comment('Número de cuotas (ej: 30)');
            
            $table->date('fecha_vencimiento')->comment('Fecha de Vencimiento total');
            $table->date('fecha_proximo_pago')->comment('Fecha del próximo pago');
            
            $table->timestamps();
            
            $table->index('id_cliente');
            $table->index('fecha_proximo_pago');
            $table->index('saldo_actual');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creditos');
    }
};