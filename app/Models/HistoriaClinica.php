<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use App\Models\Paciente;
use App\Models\User;
use App\Models\NotaClinica;

class HistoriaClinica extends Model
{
    // ✅ Nombre real de la tabla
    protected $table = 'historias_clinicas';

    // ==============================
    // 🔒 CAMPOS ASIGNABLES
    // ==============================
    protected $fillable = [
        'paciente_id',
        'odontologo_id',
        'fecha_apertura',
        'estado',
        'motivo_consulta',
        'alergias',
        'medicamentos',
        'antecedentes_personales',
        'antecedentes_familiares',
        'habitos',
        'pa',
        'fc',
        'fr',
        'temp',
        'sato2',
        'examen_extraoral',
        'examen_intraoral',
        'diagnostico',
        'plan_tratamiento',
        'imagenes',
        'consentimientos',
    ];

    // ==============================
    // ⚙️ CONVERSIONES AUTOMÁTICAS
    // ==============================
    protected $casts = [
        'imagenes' => AsArrayObject::class,
        'consentimientos' => AsArrayObject::class,
        'fecha_apertura' => 'date',
    ];

    // ==============================
    // 🔗 RELACIONES
    // ==============================

    /**
     * Una historia clínica pertenece a un paciente.
     */
    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    /**
     * Una historia clínica pertenece a un odontólogo (usuario con rol odontólogo).
     */
    public function odontologo()
    {
        return $this->belongsTo(User::class, 'odontologo_id');
    }

    /**
     * Una historia clínica puede tener muchas notas clínicas.
     * Se ordenan de la más reciente a la más antigua por la columna 'fecha'.
     */
    public function notas()
    {
        return $this->hasMany(\App\Models\NotaClinica::class, 'historia_clinica_id')
            ->latest('fecha');
    }
}
