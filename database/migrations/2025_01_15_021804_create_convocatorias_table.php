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
        Schema::create('convocatorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usu')->constrained('users')->cascadeOnDelete();
            $table->string('titulo');
            $table->text('cuerpo')->nullable(true);
            $table->date('fecha');
            $table->timestamps();
            $table->softDeletes();

            $table->fullText('titulo', 'titulo_convocatorias');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('convocatorias', function (Blueprint $table) {
            // Eliminar índice FULLTEXT antes de borrar la tabla
            $table->dropFullText('titulo_convocatorias');
        });

        Schema::dropIfExists('convocatorias');
    }
};
