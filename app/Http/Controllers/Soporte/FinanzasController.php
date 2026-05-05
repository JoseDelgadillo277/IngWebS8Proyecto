<?php

namespace App\Http\Controllers\Soporte;

use App\Http\Controllers\Controller;

class FinanzasController extends Controller
{
    public function index()
    {
        return view('soporte.finanzas');
    }
}
