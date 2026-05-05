<?php

namespace App\Http\Controllers;

use App\Models\Cita;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy = now()->toDateString(); // YYYY-MM-DD

        // DEVUELVE UNA COLLECTION (NO uses ->count() aquí)
        $citasHoy = Cita::with(['paciente', 'odontologo'])
            ->whereDate('fecha', $hoy)
            // ->whereIn('estado', ['programada','checkin','atendida']) // opcional
            ->orderBy('hora_inicio')
            ->get();

        return view('dashboard', [
            'hoy'      => $hoy,
            'citasHoy' => $citasHoy, // <-- Collection
        ]);
    }
}
