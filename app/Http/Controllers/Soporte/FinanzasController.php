<?php

namespace App\Http\Controllers\Soporte;

use App\Http\Controllers\Controller;

class FinanzasController extends Controller
{
    /**
     * Muestra la pagina inicial del modulo de finanzas.
     */
    public function index()
    {
        return view('soporte.finanzas');
    }
}
