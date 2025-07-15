<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nombres')->nullable();
            $table->string('apellidos')->nullable();
            $table->string('celular')->nullable()->unique();
            $table->string('password')->nullable(true)->change();
            $table->boolean('is_active')->default(true);
            $table->string('type')->default('db');
            $table->string('oidc_username')->nullable();
            $table->string('oidc_sub')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nombres')->nullable(false);
            $table->string('apellidos')->nullable(false);
            $table->string('celular')->nullable(false)->unique();
            $table->string('password')->nullable(false)->change();
            $table->dropColumn('type');
            $table->dropColumn('oidc_username');
            $table->dropColumn('oidc_sub');
        });
    }
};
