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
        Schema::create('curso_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('curso_id')->constrained()->onDelete('cascade');
            $table->timestamp('inscrito_at')->useCurrent();
            $table->integer('progreso')->default(0); // Porcentaje de 0 a 100
            $table->timestamp('completado_at')->nullable();
            $table->enum('estado', ['activo', 'completado', 'abandonado'])->default('activo');
            $table->timestamps();

            // Evitar inscripciones duplicadas
            $table->unique(['user_id', 'curso_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curso_user');
    }
};
