<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notas_clinicas', function (Blueprint $table) {
            $table->id();

            // FK a historias_clinicas
            $table->foreignId('historia_clinica_id')
                ->constrained('historias_clinicas')
                ->cascadeOnDelete();

            // (Opcional) vincular a la cita
            $table->foreignId('cita_id')
                ->nullable()
                ->constrained('citas')
                ->nullOnDelete();

            // Datos de la nota
            $table->dateTime('fecha'); // la enviarás desde el controller
            $table->string('procedimiento')->nullable();
            $table->text('evolucion')->nullable();
            $table->text('indicaciones')->nullable();
            $table->json('adjuntos')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notas_clinicas');
    }
};
