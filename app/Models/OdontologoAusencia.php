<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OdontologoAusencia extends Model
{
    // Tabla que guarda dias o rangos en los que el odontologo no atiende.
    protected $table = 'odontologo_ausencias';
    // Campos permitidos para registrar una ausencia desde RRHH.
    protected $fillable = [
        'odontologo_id',
        'fecha_inicio',
        'fecha_fin',
        'motivo'
    ];

    public function odontologo()
    {
        // La ausencia pertenece a un usuario odontologo.
        return $this->belongsTo(User::class, 'odontologo_id');
    }
}
