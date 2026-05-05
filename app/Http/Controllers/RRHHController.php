<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RRHHController extends Controller
{
    /**
     * Muestra la página de gestión de RRHH.
     */
    public function index()
    {
        return view('rrhh.index');
    }
}
