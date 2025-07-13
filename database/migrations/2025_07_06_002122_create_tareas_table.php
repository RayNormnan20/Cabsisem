<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prospecto_id')->constrained('prospectos');
            $table->foreignId('forma_contacto_id')->constrained('formas_contacto');
            $table->foreignId('nivel_interes_id')->constrained('niveles_interes');
            $table->foreignId('usuario_asignado_id')->constrained('users');
            $table->date('fecha_realizar');
            $table->time('hora');
            $table->text('nota')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tareas');
    }
};