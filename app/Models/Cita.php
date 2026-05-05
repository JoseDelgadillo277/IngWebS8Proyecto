<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $fillable = [
        'paciente_id',
        'odontologo_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'estado',
        'motivo'
    ];

    // Estados sugeridos
    public const EST_PROGRAMADA = 'programada';
    public const EST_CHECKIN    = 'checkin';
    public const EST_ATENDIDA   = 'atendida';
    public const EST_CANCELADA  = 'cancelada';
    public const EST_NO_SHOW    = 'no_show';

    protected $casts = [
        'fecha' => 'date',
    ];

    // Relaciones
    public function paciente()
    {
        return $this->belongsTo(\App\Models\Paciente::class);
    }
    public function odontologo()
    {
        return $this->belongsTo(\App\Models\User::class, 'odontologo_id');
    }

    // Scopes de filtro
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

    // Helpers
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
