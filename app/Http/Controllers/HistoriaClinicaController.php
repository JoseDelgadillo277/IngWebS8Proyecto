<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paciente;
use App\Models\HistoriaClinica;
use App\Models\User;

class HistoriaClinicaController extends Controller
{
    /**
     * Mostrar la historia clínica de un paciente (si existe).
     * Incluye las notas clínicas ordenadas por fecha (desc).
     */
    public function showByPaciente(Paciente $paciente)
    {
        $historia = $paciente->historiaClinica()
            ->with(['notas' => fn($q) => $q->latest('fecha')])
            ->first();

        // Listado de odontólogos para selects en la vista
        $odontologos = User::role('odontologo')->orderBy('name')->get();

        return view('historias.show', compact('paciente', 'historia', 'odontologos'));
    }

    /**
     * Formulario para crear historia clínica (si el paciente aún no tiene).
     */
    public function create(Paciente $paciente)
    {
        abort_if($paciente->historiaClinica, 403, 'Ya existe historia clínica para este paciente.');

        $odontologos = User::role('odontologo')->orderBy('name')->get();

        return view('historias.create', compact('paciente', 'odontologos'));
    }

    /**
     * Guardar una nueva historia clínica para el paciente.
     */
    public function store(Request $request, Paciente $paciente)
    {
        $data = $request->validate([
            'odontologo_id'           => 'nullable|exists:users,id',
            'fecha_apertura'          => 'nullable|date',
            'motivo_consulta'         => 'nullable|string|max:255',
            'alergias'                => 'nullable|string',
            'medicamentos'            => 'nullable|string',
            'antecedentes_personales' => 'nullable|string',
            'antecedentes_familiares' => 'nullable|string',
            'habitos'                 => 'nullable|string',
            'pa'                      => 'nullable|string|max:10',
            'fc'                      => 'nullable|integer',
            'fr'                      => 'nullable|integer',
            'temp'                    => 'nullable|numeric',
            'sato2'                   => 'nullable|integer',
            'examen_extraoral'        => 'nullable|string',
            'examen_intraoral'        => 'nullable|string',
            'diagnostico'             => 'nullable|string',
            'plan_tratamiento'        => 'nullable|string',
        ]);

        // Si viene odontologo_id, asegurar que realmente tenga rol odontólogo
        if (!empty($data['odontologo_id'])) {
            $u = User::find($data['odontologo_id']);
            abort_unless($u && $u->hasRole('odontologo'), 422, 'El usuario seleccionado no es odontólogo.');
        }

        $data['fecha_apertura'] = $data['fecha_apertura'] ?? now()->toDateString();
        $data['estado'] = 'abierta';

        $paciente->historiaClinica()->create($data);

        return redirect()
            ->route('historias.show', $paciente)
            ->with('ok', 'Historia clínica creada.');
    }

    /**
     * Actualizar historia clínica (solo campos permitidos).
     */
    public function update(Request $request, HistoriaClinica $historia)
    {
        $data = $request->validate([
            'odontologo_id'           => 'nullable|exists:users,id',
            'fecha_apertura'          => 'nullable|date',
            'estado'                  => 'nullable|in:abierta,cerrada',
            'motivo_consulta'         => 'nullable|string|max:255',
            'alergias'                => 'nullable|string',
            'medicamentos'            => 'nullable|string',
            'antecedentes_personales' => 'nullable|string',
            'antecedentes_familiares' => 'nullable|string',
            'habitos'                 => 'nullable|string',
            'pa'                      => 'nullable|string|max:10',
            'fc'                      => 'nullable|integer',
            'fr'                      => 'nullable|integer',
            'temp'                    => 'nullable|numeric',
            'sato2'                   => 'nullable|integer',
            'examen_extraoral'        => 'nullable|string',
            'examen_intraoral'        => 'nullable|string',
            'diagnostico'             => 'nullable|string',
            'plan_tratamiento'        => 'nullable|string',
        ]);

        if (!empty($data['odontologo_id'])) {
            $u = User::find($data['odontologo_id']);
            abort_unless($u && $u->hasRole('odontologo'), 422, 'El usuario seleccionado no es odontólogo.');
        }

        $historia->update($data);

        return back()->with('ok', 'Historia clínica actualizada.');
    }

    /**
     * Cerrar historia clínica.
     */
    public function cerrar(HistoriaClinica $historia)
    {
        $historia->update(['estado' => 'cerrada']);

        return back()->with('ok', 'Historia clínica cerrada.');
    }
}
