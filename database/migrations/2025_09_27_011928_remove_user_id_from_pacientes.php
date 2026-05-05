<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Quitar la FK si existe (nombre por convención)
        if (Schema::hasColumn('pacientes', 'user_id')) {
            // Algunas versiones de MySQL requieren ejecutar DROP FOREIGN KEY con el nombre exacto
            try {
                Schema::table('pacientes', function (Blueprint $table) {
                    $table->dropForeign(['user_id']);
                });
            } catch (\Throwable $e) {
                // SQLite can drop the column by rebuilding the table without a named FK.
            }

            // 2) Ahora sí, quitamos la columna
            Schema::table('pacientes', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('pacientes', 'user_id')) {
            Schema::table('pacientes', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
                // Si quieres restaurar la FK (opcional):
                // $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            });
        }
    }
};
