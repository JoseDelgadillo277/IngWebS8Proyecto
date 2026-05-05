<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained()->cascadeOnDelete();
            $table->string('documento_tipo', 12);      // boleta | factura | recibo
            $table->string('documento_numero', 30)->nullable();
            $table->string('concepto', 120);
            $table->decimal('monto', 10, 2);
            $table->string('moneda', 3)->default('PEN'); // PEN | USD
            $table->string('medio_pago', 20);           // efectivo | tarjeta | transferencia | yape | plin
            $table->string('numero_operacion', 40)->nullable()->index();
            $table->date('fecha');
            $table->string('observacion', 255)->nullable();
            $table->foreignId('registrado_por')->constrained('users'); // cajero
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
