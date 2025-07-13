<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('vendedores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('tipo_documento_id')->constrained('tipo_documento');
            $table->string('numero_documento', 50);
            $table->string('nombre', 255);
            $table->string('telefono', 20);
            $table->string('email', 255);
            $table->foreignId('estado_id')->constrained('estado');
            $table->date('fecha_ingreso');
            $table->date('fecha_egreso')->nullable();
            $table->foreignId('proyecto_id')->nullable()->constrained('proyectos');
            $table->decimal('comision', 10, 2)->nullable();
            $table->text('perfil')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendedores');
    }
};