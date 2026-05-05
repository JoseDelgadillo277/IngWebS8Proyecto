<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    // Campos que se pueden crear o actualizar masivamente desde controladores.
    protected $fillable = [
        'paciente_id',
        'odontologo_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'estado',
        'motivo'
    ];

    // Estados usados para seguir el ciclo de vida de una cita.
    public const EST_PROGRAMADA = 'programada';
    public const EST_CHECKIN    = 'checkin';
    public const EST_ATENDIDA   = 'atendida';
    public const EST_CANCELADA  = 'cancelada';
    public const EST_NO_SHOW    = 'no_show';

    protected $casts = [
        // Convierte fecha a objeto de fecha de Laravel al leerla.
        'fecha' => 'date',
    ];

    // Relacion: cada cita pertenece a un paciente.
    public function paciente()
    {
        return $this->belongsTo(\App\Models\Paciente::class);
    }
    // Relacion: cada cita pertenece a un usuario odontologo.
    public function odontologo()
    {
        return $this->belongsTo(\App\Models\User::class, 'odontologo_id');
    }

    // Scopes reutilizables para aplicar filtros en consultas.
    public function scopeFecha($q, $fecha)
    {
        return $fecha ? $q->whereDate('fecha', $fecha) : $q;
    }
    public function scopeOdontologo($q, $id)
    {
        return $id ? $q->where('odontologo_id', $id) : $q;
    }
    public function scopeEstado($q, $estado)
    {
        return $estado ? $q->where('estado', $estado) : $q;
    }

    // Helper visual para escoger color segun el estado de la cita.
    public function getBadgeClaseAttribute(): string
    {
        return match ($this->estado) {
            self::EST_PROGRAMADA => 'bg-blue-600',
            self::EST_CHECKIN    => 'bg-cyan-600',
            self::EST_ATENDIDA   => 'bg-emerald-600',
            self::EST_CANCELADA  => 'bg-rose-600',
            self::EST_NO_SHOW    => 'bg-amber-600',
            default              => 'bg-slate-500'
        };
    }
}
