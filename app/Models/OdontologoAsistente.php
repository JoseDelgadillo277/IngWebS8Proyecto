<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OdontologoAsistente extends Model
{
    // Tabla pivote que relaciona perfiles de odontologo con asistentes.
    protected $table = 'odontologo_asistente';
    // Incluye el turno para indicar cuando apoya el asistente.
    protected $fillable = ['odontologo_id', 'asistente_id', 'turno'];

    public function odontologo()
    {
        // El odontologo se referencia por su perfil.
        return $this->belongsTo(OdontologoProfile::class, 'odontologo_id');
    }

    public function asistente()
    {
        // El asistente es un usuario del sistema.
        return $this->belongsTo(User::class, 'asistente_id');
    }
}
