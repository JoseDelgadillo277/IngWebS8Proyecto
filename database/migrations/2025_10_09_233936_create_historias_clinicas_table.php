<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('historias_clinicas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->foreignId('odontologo_id')->nullable()->constrained('users')->nullOnDelete();;
            $table->date('fecha_apertura')->default(now());
            $table->enum('estado', ['abierta', 'cerrada'])->default('abierta');

            // Anamnesis
            $table->string('motivo_consulta')->nullable();
            $table->text('alergias')->nullable();
            $table->text('medicamentos')->nullable();
            $table->text('antecedentes_personales')->nullable();
            $table->text('antecedentes_familiares')->nullable();
            $table->text('habitos')->nullable();

            // Signos vitales
            $table->string('pa')->nullable();
            $table->unsignedSmallInteger('fc')->nullable();
            $table->unsignedSmallInteger('fr')->nullable();
            $table->decimal('temp', 4, 1)->nullable();
            $table->unsignedTinyInteger('sato2')->nullable();

            // Examen / diagnóstico / plan
            $table->text('examen_extraoral')->nullable();
            $table->text('examen_intraoral')->nullable();
            $table->text('diagnostico')->nullable();
            $table->text('plan_tratamiento')->nullable();

            // Archivos
            $table->json('imagenes')->nullable();
            $table->json('consentimientos')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historias_clinicas');
    }
};
