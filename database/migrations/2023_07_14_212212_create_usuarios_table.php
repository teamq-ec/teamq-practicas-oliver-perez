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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('lastname');
            $table->date('birthdate');
            $table->string('address');
            $table->string('country');
            $table->unsignedBigInteger('zip');
            $table->unsignedBigInteger('telephone');
            $table->string('position');
            $table->string('department');
            $table->date('companyage');
            $table->boolean('state');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->unsignedBigInteger('rol_id')->default(null);
            $table->foreign('rol_id')->references('id')->on('roles');
            $table->string('ruta_imagen')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
