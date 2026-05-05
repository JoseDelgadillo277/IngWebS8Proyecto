<?php

namespace App\Http\Controllers\Soporte;

use App\Http\Controllers\Controller;

class AdministracionController extends Controller
{
    /**
     * Muestra la pagina inicial del modulo de administracion.
     */
    public function index()
    {
        return view('soporte.administracion');
    }
}
