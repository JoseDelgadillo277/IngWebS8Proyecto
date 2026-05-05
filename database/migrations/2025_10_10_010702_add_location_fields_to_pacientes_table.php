<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            if (!Schema::hasColumn('pacientes', 'sexo'))      $table->string('sexo', 1)->nullable()->after('fecha_nacimiento');
            if (!Schema::hasColumn('pacientes', 'provincia')) $table->string('provincia', 80)->nullable()->after('sexo');
            if (!Schema::hasColumn('pacientes', 'distrito'))  $table->string('distrito', 80)->nullable()->after('provincia');
            if (!Schema::hasColumn('pacientes', 'calle'))     $table->string('calle', 120)->nullable()->after('distrito');
            if (!Schema::hasColumn('pacientes', 'numero'))    $table->string('numero', 15)->nullable()->after('calle');
        });
    }

    public function down(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            if (Schema::hasColumn('pacientes', 'numero'))   $table->dropColumn('numero');
            if (Schema::hasColumn('pacientes', 'calle'))    $table->dropColumn('calle');
            if (Schema::hasColumn('pacientes', 'distrito')) $table->dropColumn('distrito');
            if (Schema::hasColumn('pacientes', 'provincia')) $table->dropColumn('provincia');
            if (Schema::hasColumn('pacientes', 'sexo'))     $table->dropColumn('sexo');
        });
    }
};
