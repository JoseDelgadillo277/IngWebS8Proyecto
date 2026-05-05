<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            // crea índice único en dni si no existe
            if (! $this->indexExists('pacientes', 'pacientes_dni_unique')) {
                $table->unique('dni', 'pacientes_dni_unique');
            }

            // crea índice único en email si no existe
            if (! $this->indexExists('pacientes', 'pacientes_email_unique')) {
                $table->unique('email', 'pacientes_email_unique');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            if ($this->indexExists('pacientes', 'pacientes_dni_unique')) {
                $table->dropUnique('pacientes_dni_unique');
            }
            if ($this->indexExists('pacientes', 'pacientes_email_unique')) {
                $table->dropUnique('pacientes_email_unique');
            }
        });
    }

    /** Comprueba si existe un índice por nombre usando INFORMATION_SCHEMA */
    private function indexExists(string $table, string $indexName): bool
    {
        if (DB::getDriverName() === 'sqlite') {
            return collect(DB::select("PRAGMA index_list('$table')"))
                ->contains(fn ($index) => ($index->name ?? null) === $indexName);
        }

        $db = DB::getDatabaseName();

        $rows = DB::select(
            "SELECT 1
             FROM INFORMATION_SCHEMA.STATISTICS
             WHERE TABLE_SCHEMA = ?
               AND TABLE_NAME   = ?
               AND INDEX_NAME   = ?
             LIMIT 1",
            [$db, $table, $indexName]
        );

        return !empty($rows);
    }
};
