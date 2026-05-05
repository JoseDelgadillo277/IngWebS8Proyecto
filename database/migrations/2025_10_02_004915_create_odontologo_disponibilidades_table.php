<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('odontologo_disponibilidades', function (Blueprint $table) {
            $table->id();
            // odontólogo = users.id
            $table->foreignId('odontologo_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // 1 = Lunes, ... 7 = Domingo
            $table->unsignedTinyInteger('dia_semana');        // 1..7
            $table->time('hora_inicio');
            $table->time('hora_fin');

            $table->timestamps();

            // Evitar duplicados exactos
            $table->unique(['odontologo_id', 'dia_semana', 'hora_inicio', 'hora_fin'], 'uniq_dispo_bloque');

            // Índices útiles
            $table->index(['odontologo_id', 'dia_semana']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('odontologo_disponibilidades');
    }
};
