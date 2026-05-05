<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

use App\Models\HistoriaClinica;
use App\Models\NotaClinica;
use App\Models\Cita;

class NotaClinicaController extends Controller
{
    /**
     * Formulario para crear una nota clínica.
     * GET /historias/{historia}/notas/crear?cita=ID
     */
    public function create(HistoriaClinica $historia, Request $request)
    {
        // Evita añadir notas si la historia está cerrada
        if ($historia->estado === 'cerrada') {
            return redirect()
                ->route('historias.show', $historia->paciente)
                ->withErrors(['historia' => 'La historia clínica está cerrada. Ábrela para añadir notas.']);
        }

        $cita = null;
        if ($request->filled('cita')) {
            $cita = Cita::find($request->query('cita'));
            // Si existe, valida que pertenezca al mismo paciente
            if ($cita && $cita->paciente_id !== $historia->paciente_id) {
                $cita = null;
            }
        }

        return view('notas.create', [
            'historia' => $historia,
            'paciente' => $historia->paciente,
            'cita'     => $cita,
        ]);
    }

    /**
     * Guarda una nueva nota clínica.
     * POST /historias/{historia}/notas
     */
    public function store(Request $request, HistoriaClinica $historia)
    {
        if ($historia->estado === 'cerrada') {
            throw ValidationException::withMessages([
                'historia' => 'La historia clínica está cerrada. Ábrela antes de registrar notas.',
            ]);
        }

        $data = $request->validate([
            'cita_id'       => ['nullable', 'exists:citas,id'],
            // Acepta datetime-local (HTML) "Y-m-d\TH:i" o "Y-m-d H:i"; si viene vacío usa ahora()
            'fecha'         => ['nullable', 'string'],
            'procedimiento' => ['nullable', 'string', 'max:255'],
            'evolucion'     => ['nullable', 'string'],
            'indicaciones'  => ['nullable', 'string'],
        ]);

        // Normaliza fecha
        $fechaTxt = $data['fecha'] ?? '';
        if (trim($fechaTxt) === '') {
            $fecha = now();
        } else {
            $norm = str_replace('T', ' ', $fechaTxt);
            try {
                $fecha = Carbon::parse($norm);
            } catch (\Throwable $e) {
                throw ValidationException::withMessages([
                    'fecha' => 'Formato de fecha/hora inválido.',
                ]);
            }
        }

        // Si hay cita, valida que sea del mismo paciente de la historia
        $citaId = $data['cita_id'] ?? null;
        $cita   = null;
        if ($citaId) {
            $cita = Cita::find($citaId);
            if (!$cita || $cita->paciente_id !== $historia->paciente_id) {
                throw ValidationException::withMessages([
                    'cita_id' => 'La cita seleccionada no pertenece a este paciente.',
                ]);
            }
        }

        // Crear nota
        $nota = new NotaClinica();
        $nota->historia_clinica_id = $historia->id;
        $nota->cita_id       = $citaId;
        $nota->fecha         = $fecha;
        $nota->procedimiento = $data['procedimiento'] ?? null;
        $nota->evolucion     = $data['evolucion'] ?? null;
        $nota->indicaciones  = $data['indicaciones'] ?? null;
        $nota->save();

        // (Opcional) marcar la cita como atendida si existe y no está cancelada
        if ($cita && $cita->estado !== 'cancelada') {
            $cita->estado = 'atendida';
            $cita->save();
        }

        return redirect()
            ->route('historias.show', $historia->paciente)
            ->with('ok', 'Nota clínica registrada.');
    }

    /**
     * Elimina una nota clínica.
     * DELETE /notas/{nota}
     */
    public function destroy(NotaClinica $nota)
    {
        $paciente = $nota->historiaClinica->paciente;
        $nota->delete();

        return redirect()
            ->route('historias.show', $paciente)
            ->with('ok', 'Nota clínica eliminada.');
    }
}
