<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('prospectos', function (Blueprint $table) {
            $table->string('numero_documento', 50)->nullable()->change();
            $table->string('nombres', 255)->nullable()->change();
            $table->string('ape_paterno', 255)->nullable()->change();
            $table->string('ape_materno', 255)->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('prospectos', function (Blueprint $table) {
            $table->string('numero_documento', 50)->nullable(false)->change();
            $table->string('nombres', 255)->nullable(false)->change();
            $table->string('ape_paterno', 255)->nullable(false)->change();
            $table->string('ape_materno', 255)->nullable(false)->change();
        });
    }
};
