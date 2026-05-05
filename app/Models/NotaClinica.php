<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotaClinica extends Model
{
    use HasFactory;

    protected $table = 'notas_clinicas';

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
        'fecha'    => 'datetime',
        'adjuntos' => 'array',
    ];

    public function historiaClinica()
    {
        return $this->belongsTo(HistoriaClinica::class, 'historia_clinica_id');
    }

    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }
}
