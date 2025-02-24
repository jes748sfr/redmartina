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
        Schema::create('directorios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usu')->constrained('users')->cascadeOnDelete();
            $table->string('area');
            $table->integer('nivel');
            $table->string('pais');
            $table->string('imagen')->nullable(true);
            $table->string('nombre');
            $table->string('correo')->unique()->nullable(true);
            $table->string('descripcion')->nullable(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('directorios');
    }
};
