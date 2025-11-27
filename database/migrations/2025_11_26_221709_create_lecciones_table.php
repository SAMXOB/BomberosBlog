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
        Schema::create('lecciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modulo_id')->constrained()->onDelete('cascade');
            $table->string('titulo');
            $table->text('contenido');
            $table->enum('tipo', ['texto', 'video', 'archivo', 'quiz'])->default('texto');
            $table->string('url_recurso')->nullable(); // URL de video o archivo
            $table->integer('duracion_minutos')->default(0);
            $table->integer('orden')->default(0);
            $table->boolean('es_gratis')->default(false); // Vista previa gratuita
            $table->boolean('publicado')->default(true);
            $table->timestamps();

            $table->index(['modulo_id', 'orden']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecciones');
    }
};
