<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('log_actividades', function (Blueprint $table) {
            $table->id();

            // Relación con el usuario que realizó la acción
            $table->foreignId('user_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            // Tipo de actividad (Rutas, Créditos, Clientes, etc.)
            $table->string('tipo', 50);

            // Mensaje descriptivo de la actividad
            $table->text('mensaje');

            // Datos adicionales en formato JSON
            $table->json('metadata')->nullable();

            // Timestamps automáticos
            $table->timestamps();

            // Índices para mejorar el rendimiento de búsquedas
            $table->index('user_id');
            $table->index('tipo');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_actividades');
    }
};
