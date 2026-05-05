<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EstrategicoController extends Controller
{
    public function index()
    {
        return view('estrategicos.index');
    }

    public function planeamiento()
    {
        return view('estrategicos.planeamiento');
    }

    public function calidad()
    {
        return view('estrategicos.calidad');
    }

    public function innovacion()
    {
        return view('estrategicos.innovacion');
    }
}
