<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;

    /**
     * Campos permitidos para asignación masiva.
     * Añadimos ubicación/dirección y sexo.
     * (Si no usarás alguno, puedes quitarlo sin problema.)
     */
    protected $fillable = [
        'nombre',
        'apellido',
        'dni',
        'telefono',
        'email',
        'fecha_nacimiento',

        // Ubicación / dirección
        'sexo',
        'provincia',
        'distrito',
        'calle',
        'numero',
        'direccion',      // opcional si quieres seguir guardando una cadena completa

        // Consecutivo propio (opcional, ver controlador)
        'codigo',
    ];

    protected $casts = [
        // Permite trabajar fecha_nacimiento como fecha de Laravel.
        'fecha_nacimiento' => 'date',
    ];

    // ==============================
    // 🔗 RELACIONES
    // ==============================

    public function citas()
    {
        // Un paciente puede tener varias citas.
        return $this->hasMany(\App\Models\Cita::class);
    }

    // Una historia clínica por paciente
    public function historiaClinica()
    {
        return $this->hasOne(\App\Models\HistoriaClinica::class);
    }

    // ==============================
    // 🔧 ACCESSORS / HELPERS
    // ==============================

    public function getNombreCompletoAttribute(): string
    {
        // Atributo calculado para mostrar nombre y apellido juntos.
        return trim(($this->nombre ?? '') . ' ' . ($this->apellido ?? ''));
    }

    /**
     * Dirección compuesta: "Calle N° Número" o cae a 'direccion' si existe.
     */
    public function getDireccionCompletaAttribute(): ?string
    {
        $calle  = trim((string) $this->calle);
        $numero = trim((string) $this->numero);
        $armada = trim($calle . ($numero ? ' N° ' . $numero : ''));

        return $armada !== '' ? $armada : ($this->direccion ?: null);
    }

    /**
     * Ubicación breve: "Distrito, Provincia"
     */
    public function getUbicacionAttribute(): ?string
    {
        $d = trim((string) $this->distrito);
        $p = trim((string) $this->provincia);
        if ($d === '' && $p === '') return null;
        if ($d !== '' && $p !== '') return $d . ', ' . $p;
        return $d !== '' ? $d : $p;
    }
}
