<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('creditos', function (Blueprint $table) {
            $table->unsignedBigInteger('id_ruta')->after('id_cliente')->nullable();

            // Si tienes una tabla rutas, puedes agregar la clave foránea (opcional)
            // $table->foreign('id_ruta')->references('id_ruta')->on('ruta')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('creditos', function (Blueprint $table) {
            $table->dropColumn('id_ruta');
        });
    }
};