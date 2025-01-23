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
        Schema::create('galerias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usu')->constrained('users')->cascadeOnDelete();
            $table->string('titulo');
            $table->timestamps();
            $table->softDeletes();

            $table->fullText('titulo', 'titulo_galerias');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('galerias', function (Blueprint $table) {
            // Eliminar índice FULLTEXT antes de borrar la tabla
            $table->dropFullText('titulo_galerias');
        });

        Schema::dropIfExists('galerias');
    }
};
