<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OdontologoAsistente extends Model
{
    protected $table = 'odontologo_asistente';
    protected $fillable = ['odontologo_id', 'asistente_id', 'turno'];

    public function odontologo()
    {
        return $this->belongsTo(OdontologoProfile::class, 'odontologo_id');
    }

    public function asistente()
    {
        return $this->belongsTo(User::class, 'asistente_id');
    }
}
