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
        Schema::create('token_otp', function (Blueprint $table) {
            $table->id('id_token_otp');
            $table->unsignedBigInteger('id_usuario');
            $table->string('codigo', 225);
            $table->string('estado', 1)->default('A');
            $table->dateTime('fecha_creacion')->default(getFechaActual());

            $table->foreign('id_usuario')->references('id_usuario')->on('usuario');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('token_otp');
    }
};
