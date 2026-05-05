<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    // Tabla que almacena rangos de atencion fijos por odontologo.
    protected $table = 'horarios';

    // Campos permitidos para crear horarios desde el formulario de RRHH.
    protected $fillable = ['odontologo_id', 'dia', 'hora_inicio', 'hora_fin'];

    protected $casts = [
        // Formatea las horas como HH:mm cuando Laravel las serializa.
        'hora_inicio' => 'datetime:H:i',
        'hora_fin'    => 'datetime:H:i',
    ];

    public function odontologo()
    {
        // Cada horario pertenece a un perfil de odontologo.
        return $this->belongsTo(OdontologoProfile::class, 'odontologo_id');
    }
}
