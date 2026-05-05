<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('odontologo_ausencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('odontologo_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable(); // mismo día o rango
            $table->string('motivo', 120)->nullable();

            $table->timestamps();

            $table->index(['odontologo_id', 'fecha_inicio', 'fecha_fin']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('odontologo_ausencias');
    }
};
