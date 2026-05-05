<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Horario;
use App\Models\OdontologoProfile;

class HorarioController extends Controller
{
    public function index()
    {
        $odontologos = OdontologoProfile::with('user')->orderBy('id')->get();
        $horarios = Horario::with(['odontologo.user'])
            ->orderBy('odontologo_id')->orderBy('dia')->orderBy('hora_inicio')->get();

        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

        return view('odontologos.horarios.index', compact('odontologos', 'horarios', 'dias'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'odontologo_id' => ['required', 'exists:odontologo_profiles,id'], // 👈 aquí
            'dia'           => ['required', 'in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado,Domingo'],
            'hora_inicio'   => ['required', 'date_format:H:i'],
            'hora_fin'      => ['required', 'date_format:H:i', 'after:hora_inicio'],
        ]);

        $traslape = Horario::where('odontologo_id', $data['odontologo_id'])
            ->where('dia', $data['dia'])
            ->where(fn($q) => $q->where('hora_inicio', '<', $data['hora_fin'])
                ->where('hora_fin', '>', $data['hora_inicio']))
            ->exists();

        if ($traslape) {
            return back()->withInput()->withErrors([
                'hora_inicio' => 'Este horario se traslapa con uno existente para este día.'
            ]);
        }

        Horario::create($data);
        return redirect()->route('odontologos.horarios.index')->with('ok', 'Horario registrado ✅');
    }

    public function destroy(Horario $horario)
    {
        $horario->delete();
        return redirect()->route('odontologos.horarios.index')->with('ok', 'Horario eliminado 🗑️');
    }
}
