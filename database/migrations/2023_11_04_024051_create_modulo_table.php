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
        Schema::create('modulo', function (Blueprint $table) {
            $table->id('id_modulo');
            $table->string('nombre');
            $table->dateTime('fecha_creacion')->default(getFechaActual());
            $table->dateTime('fecha_actualizacion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modulo');
    }
};
