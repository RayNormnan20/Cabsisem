<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
        {
        Schema::create('yape_clientes', function (Blueprint $table) {
        $table->id();

        $table->foreignId('id_cliente')->constrained('clientes', 'id_cliente')->onDelete('cascade');
        $table->string('nombre');
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Cobrador seleccionado manualmente
        $table->decimal('monto', 10, 2);
        $table->decimal('entregar', 10, 2)->nullable();


        $table->timestamps();
    });
        Schema::table('yape_clientes', function (Blueprint $table) {
            $table->softDeletes(); // Agregar soporte para soft deletes
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('yape_clientes');
    }
};
