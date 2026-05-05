<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('horarios')) {
            Schema::create('horarios', function (Blueprint $table) {
                $table->id();
                $table->foreignId('odontologo_id')
                    ->constrained('odontologo_profiles')
                    ->cascadeOnDelete();
                $table->string('dia', 20);
                $table->time('hora_inicio');
                $table->time('hora_fin');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};
