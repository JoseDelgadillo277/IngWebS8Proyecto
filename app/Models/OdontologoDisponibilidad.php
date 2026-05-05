<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OdontologoDisponibilidad extends Model
{
    protected $table = 'odontologo_disponibilidades';

    protected $fillable = [
        'odontologo_id',
        'dia_semana',
        'hora_inicio',
        'hora_fin'
    ];

    /**
     * Relación con el odontólogo (usuario)
     */
    public function odontologo()
    {
        return $this->belongsTo(User::class, 'odontologo_id');
    }

    /**
     * Accesor para mostrar el nombre del día (1–7 -> Lunes...Domingo)
     */
    public function getDiaNombreAttribute(): string
    {
        $map = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            7 => 'Domingo'
        ];
        return $map[$this->dia_semana] ?? '—';
    }

    /**
     * Accesores para formatear las horas HH:mm
     */
    public function getHoraInicioFmtAttribute(): string
    {
        return Carbon::parse($this->hora_inicio)->format('H:i');
    }

    public function getHoraFinFmtAttribute(): string
    {
        return Carbon::parse($this->hora_fin)->format('H:i');
    }
}
