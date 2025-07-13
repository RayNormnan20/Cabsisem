<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('prospectos', function (Blueprint $table) {
            $table->id();
            $table->timestamp('fecha_registro')->useCurrent();
            $table->foreignId('tipo_documento_id')->constrained('tipo_documento');
            $table->string('numero_documento', 50);
            $table->string('nombres', 255);
            $table->string('ape_paterno', 255);
            $table->string('ape_materno', 255);
            $table->string('razon_social', 255)->nullable();
            $table->string('celular', 20);
            $table->string('correo_electronico', 255)->nullable();
            $table->foreignId('proyecto_id')->constrained('proyectos');
            $table->foreignId('tipo_inmueble_id')->constrained('tipo_inmueble');
            $table->foreignId('forma_contacto_id')->constrained('formas_contacto');
            $table->foreignId('como_se_entero_id')->constrained('como_se_entero');
            $table->foreignId('tipo_gestion_id')->constrained('tipos_gestion');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('prospectos');
    }
};