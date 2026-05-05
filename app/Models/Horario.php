<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $table = 'horarios';

    protected $fillable = ['odontologo_id', 'dia', 'hora_inicio', 'hora_fin'];

    protected $casts = [
        'hora_inicio' => 'datetime:H:i',
        'hora_fin'    => 'datetime:H:i',
    ];

    public function odontologo()
    {
        return $this->belongsTo(OdontologoProfile::class, 'odontologo_id');
    }
}
