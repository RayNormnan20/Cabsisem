<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orden_cobro', function (Blueprint $table) {
            $table->id('id_orden_cobro');
            $table->string('codigo', 20)->unique();
            $table->string('nombre', 50);
            $table->string('descripcion', 100)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orden_cobro');
    }
};