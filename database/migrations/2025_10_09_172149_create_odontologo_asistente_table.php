<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('odontologo_asistente', function (Blueprint $table) {
            $table->id();

            // Relación con el perfil del odontólogo
            $table->foreignId('odontologo_id')
                ->constrained('odontologo_profiles')
                ->cascadeOnDelete();

            // Relación con el usuario ayudante
            $table->foreignId('asistente_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Turno asignado (opcional)
            $table->enum('turno', ['mañana', 'tarde', 'noche'])->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('odontologo_asistente');
    }
};
