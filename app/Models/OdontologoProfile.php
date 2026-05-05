<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OdontologoProfile extends Model
{
    use HasFactory;

    // 👇 Muy importante: tu tabla real
    protected $table = 'odontologo_profiles';

    protected $fillable = [
        'user_id',
        'colegiatura',
        'especialidad',
        'turno_preferido',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function disponibilidades()
    {
        return $this->hasMany(OdontologoDisponibilidad::class, 'odontologo_id');
    }

    public function ausencias()
    {
        return $this->hasMany(OdontologoAusencia::class, 'odontologo_id');
    }

    public function asistentes()
    {
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
        $this->loadMissing('user');
        if ($this->user?->name) return $this->user->name;

        $ap = trim($this->user->apellidos ?? '');
        $no = trim($this->user->nombres ?? '');
        $nom = trim($ap . ($ap && $no ? ', ' : '') . $no);
        return $nom !== '' ? $nom : 'Odontólogo #' . $this->id;
    }
}
