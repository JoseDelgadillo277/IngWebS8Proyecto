<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Nombre real de la tabla de historias (por si en tu proyecto es 'historias_clinicas' o 'historia_clinicas')
        $histTable = Schema::hasTable('historias_clinicas') ? 'historias_clinicas' : 'historia_clinicas';

        Schema::create('nota_clinicas', function (Blueprint $table) use ($histTable) {
            $table->id();

            // Historia clínica (obligatoria)
            $table->foreignId('historia_clinica_id')
                ->constrained($histTable)
                ->cascadeOnDelete();

            // (Opcional) Cita desde la que nace la nota
            $table->foreignId('cita_id')
                ->nullable()
                ->constrained('citas')
                ->nullOnDelete();

            // (Opcional) Odontólogo que registró la nota
            $table->foreignId('odontologo_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Datos de la nota
            $table->date('fecha')->index();            // Usamos DATE; si prefieres DATETIME, cámbialo a dateTime()
            $table->string('procedimiento')->nullable();

            // Contenido principal (usa este campo en vistas/controladores)
            $table->text('detalle')->nullable();

            // Compatibilidad con tu versión previa (puedes usarlos si los necesitas)
            $table->text('evolucion')->nullable();
            $table->text('indicaciones')->nullable();

            // Adjuntos (JSON con rutas/ids, opcional)
            $table->json('adjuntos')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nota_clinicas');
    }
};
