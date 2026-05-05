<?php

namespace App\Http\Controllers;

use App\Models\Cita;

class DashboardController extends Controller
{
    /**
     * Carga el panel principal con las citas programadas para el dia actual.
     */
    public function index()
    {
        $hoy = now()->toDateString(); // YYYY-MM-DD

        // DEVUELVE UNA COLLECTION (NO uses ->count() aquí)
        // Se cargan paciente y odontologo para evitar consultas repetidas en la vista.
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
