<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    // Datos del comprobante, monto y usuario que registra el pago.
    protected $fillable = [
        'paciente_id',
        'documento_tipo',
        'documento_numero',
        'concepto',
        'monto',
        'moneda',
        'medio_pago',
        'numero_operacion',
        'fecha',
        'observacion',
        'registrado_por',
    ];

    protected $casts = [
        // Laravel convierte fecha y monto a formatos consistentes.
        'fecha' => 'date',
        'monto' => 'decimal:2',
    ];

    /** 🧍 Relación con paciente */
    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    /** 👤 Relación con usuario que registró el pago */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }
}
