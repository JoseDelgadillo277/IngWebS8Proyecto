<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();

            // Relaciones
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->foreignId('odontologo_id')->constrained('users')->cascadeOnDelete();

            // Datos de agenda
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');

            // Otros campos
            $table->string('estado')->default('programada'); // programada|atendida|cancelada
            $table->string('motivo')->nullable();
            $table->text('notas')->nullable();

            $table->timestamps();

            // Índice útil para búsquedas/validaciones
            $table->index(['odontologo_id', 'fecha']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
