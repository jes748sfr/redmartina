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
        Schema::create('actividades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usu')->constrained('users')->cascadeOnDelete();
            $table->string('titulo');
            $table->text('cuerpo')->nullable(true);
            $table->boolean('noticia')->default(false);
            $table->date('fecha');
            $table->timestamps();
            $table->softDeletes();
            
            $table->fullText('titulo', 'titulo_actividades');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('actividades', function (Blueprint $table) {
            // Eliminar índice FULLTEXT antes de borrar la tabla
            $table->dropFullText('titulo_actividades');
        });

        Schema::dropIfExists('actividades');
    }
};
