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
        Schema::create('usuario', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->unsignedBigInteger('id_usuario_detalle');
            $table->unsignedBigInteger('id_rol');
            $table->string('usuario', 225);
            $table->string('contrasena', 225);
            $table->string('estado', 1)->default('A');
            $table->dateTime('fecha_creacion')->default(now()->format('Y-m-d H:i:s'));
            $table->dateTime('fecha_actualizacion')->nullable();

            $table->foreign('id_usuario_detalle')->references('id_usuario_detalle')->on('usuario_detalle');
            $table->foreign('id_rol')->references('id_rol')->on('rol');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};
