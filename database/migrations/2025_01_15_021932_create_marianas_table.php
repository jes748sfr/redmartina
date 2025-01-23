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
        Schema::create('marianas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usu')->constrained('users')->cascadeOnDelete();
            $table->string('titulo');
            $table->text('cuerpo')->nullable(true);
            $table->timestamps();
            $table->softDeletes();

            $table->fullText('titulo', 'titulo_marianas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marianas', function (Blueprint $table) {
            // Eliminar índice FULLTEXT antes de borrar la tabla
            $table->dropFullText('titulo_marianas');
        });

        Schema::dropIfExists('marianas');
    }
};
