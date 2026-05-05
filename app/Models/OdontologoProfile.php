<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OdontologoProfile extends Model
{
    use HasFactory;

    // 👇 Muy importante: tu tabla real
    // Tabla real donde se guardan los datos profesionales del odontologo.
    protected $table = 'odontologo_profiles';

    // Informacion profesional editable desde configuracion/RRHH.
    protected $fillable = [
        'user_id',
        'colegiatura',
        'especialidad',
        'turno_preferido',
    ];

    public function user()
    {
        // Perfil asociado a un usuario del sistema.
        return $this->belongsTo(User::class);
    }

    public function disponibilidades()
    {
        // Bloques semanales en los que el odontologo atiende.
        return $this->hasMany(OdontologoDisponibilidad::class, 'odontologo_id');
    }

    public function ausencias()
    {
        // Fechas en las que el odontologo no esta disponible.
        return $this->hasMany(OdontologoAusencia::class, 'odontologo_id');
    }

    public function asistentes()
    {
        // Asistentes asignados al odontologo.
        return $this->hasMany(OdontologoAsistente::class, 'odontologo_id');
    }

    // Relación con horarios fijos (si la usas)
    public function horarios()
    {
        return $this->hasMany(Horario::class, 'odontologo_id');
    }

    // Nombre para mostrar (toma del usuario si existe)
    public function getNombreMostrarAttribute(): string
    {
        // Prioriza el nombre del usuario y usa un texto alternativo si no existe.
        $this->loadMissing('user');
        if ($this->user?->name) return $this->user->name;

        $ap = trim($this->user->apellidos ?? '');
        $no = trim($this->user->nombres ?? '');
        $nom = trim($ap . ($ap && $no ? ', ' : '') . $no);
        return $nom !== '' ? $nom : 'Odontólogo #' . $this->id;
    }
}
