<?php

namespace App\Http\Controllers\Soporte;

use App\Http\Controllers\Controller;

class AdministracionController extends Controller
{
    public function index()
    {
        return view('soporte.administracion');
    }
}
