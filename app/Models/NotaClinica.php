<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotaClinica extends Model
{
    use HasFactory;

    // Tabla donde se guardan las evoluciones o atenciones de una historia.
    protected $table = 'notas_clinicas';

    // Campos clinicos que pueden guardarse desde el formulario de notas.
    protected $fillable = [
        'historia_clinica_id',
        'cita_id',
        'fecha',
        'procedimiento',
        'evolucion',
        'indicaciones',
        'adjuntos',
    ];

    protected $casts = [
        // fecha se maneja como fecha y hora; adjuntos como arreglo.
        'fecha'    => 'datetime',
        'adjuntos' => 'array',
    ];

    public function historiaClinica()
    {
        // Cada nota pertenece a una historia clinica.
        return $this->belongsTo(HistoriaClinica::class, 'historia_clinica_id');
    }

    public function cita()
    {
        // Una nota puede estar vinculada a la cita atendida.
        return $this->belongsTo(Cita::class);
    }
}
