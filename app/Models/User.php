<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Paciente;
use App\Models\Cita;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relación: un usuario odontólogo tiene muchas citas
     */
    public function citas()
    {
        return $this->hasMany(Cita::class, 'odontologo_id');
    }

    /**
     * Relación: un usuario puede estar vinculado a un paciente
     * (para cuando el usuario tiene el rol 'paciente')
     */
    public function paciente()
    {
        return $this->hasOne(Paciente::class);
    }

    public function disponibilidades()
    {
        return $this->hasMany(\App\Models\OdontologoDisponibilidad::class, 'odontologo_id');
    }

    public function ausencias()
    {
        return $this->hasMany(\App\Models\OdontologoAusencia::class, 'odontologo_id');
    }

    public function odontologosComoAsistente()
    {
        return $this->hasMany(\App\Models\OdontologoAsistente::class, 'asistente_id');
    }

    /**
     * 👇 NUEVO: Relación 1:1 con el perfil de odontólogo
     * Permite detectar usuarios que tienen perfil (aunque no tengan rol).
     */
    public function odontologoProfile()
    {
        return $this->hasOne(\App\Models\OdontologoProfile::class, 'user_id');
    }
}
