<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            // Paso 1: registro (se guarda en cuanto lo completan)
            $table->string('nombre', 120);
            $table->string('telefono', 20);
            $table->string('email', 120)->index();
            $table->boolean('terminos')->default(false);
            $table->boolean('marketing')->default(false);
            // Pasos 2 y 3
            $table->string('apodo', 60)->nullable();
            $table->json('facetas')->nullable();
            $table->string('foto')->nullable();
            $table->timestamp('completado_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
