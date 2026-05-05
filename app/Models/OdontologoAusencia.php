<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OdontologoAusencia extends Model
{
    protected $table = 'odontologo_ausencias';
    protected $fillable = [
        'odontologo_id',
        'fecha_inicio',
        'fecha_fin',
        'motivo'
    ];

    public function odontologo()
    {
        return $this->belongsTo(User::class, 'odontologo_id');
    }
}
