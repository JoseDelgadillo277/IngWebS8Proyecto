<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePagoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasAnyRole(['admin', 'recepcionista']) ?? false;
    }

    public function rules(): array
    {
        return [
            'paciente_id'      => ['required', 'exists:pacientes,id'],
            'documento_tipo'   => ['required', 'in:boleta,factura,recibo'],
            'documento_numero' => ['nullable', 'string', 'max:30'],
            'concepto'         => ['required', 'string', 'max:120'],
            'monto'            => ['required', 'numeric', 'min:0.10'],
            'moneda'           => ['required', 'in:PEN,USD'],
            'medio_pago'       => ['required', 'in:efectivo,tarjeta,transferencia,yape,plin'],
            'numero_operacion' => ['nullable', 'string', 'max:40'],
            'fecha'            => ['required', 'date'],
            'observacion'      => ['nullable', 'string', 'max:255'],
        ];
    }
}
