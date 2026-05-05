<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EstrategicoController extends Controller
{
    /**
     * Pagina principal del modulo de procesos estrategicos.
     */
    public function index()
    {
        return view('estrategicos.index');
    }

    public function planeamiento()
    {
        // Vista informativa sobre planeamiento institucional.
        return view('estrategicos.planeamiento');
    }

    public function calidad()
    {
        // Vista informativa sobre gestion de calidad.
        return view('estrategicos.calidad');
    }

    public function innovacion()
    {
        // Vista informativa sobre innovacion y mejora continua.
        return view('estrategicos.innovacion');
    }
}
