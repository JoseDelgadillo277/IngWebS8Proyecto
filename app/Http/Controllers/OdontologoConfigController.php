<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\User;
use App\Models\OdontologoProfile;
use App\Models\OdontologoDisponibilidad;
use App\Models\OdontologoAusencia;
use App\Models\OdontologoAsistente;

class OdontologoConfigController extends Controller
{
    /**
     * Vista principal de configuración (selector + disponibilidad + ausencias)
     */
    public function index()
    {
        // Busca profesionales por rol o por perfil para llenar el selector principal.
        // Mostrar usuarios que tengan rol odontologo/asistente o que tengan perfil de odontólogo
        $odontologos = User::where(function ($q) {
            if (class_exists(\Spatie\Permission\Models\Role::class)) {
                $q->whereHas('roles', function ($r) {
                    $r->whereIn('name', ['odontologo', 'asistente']);
                });
            }
        })
            ->orWhereHas('odontologoProfile')   // usuarios con perfil, aunque no tengan rol
            ->orderBy('name')
            ->get();

        $sel = request('odontologo_id'); // users.id del profesional seleccionado

        $disponibilidades = collect();
        $ausencias        = collect();
        $asignaciones     = collect(); // si más adelante usas asistentes por odontólogo

        if ($sel) {
            // ===== DISPONIBILIDAD (usa users.id) =====
            $disponibilidades = OdontologoDisponibilidad::where('odontologo_id', $sel)
                ->orderBy('dia_semana')
                ->orderBy('hora_inicio')
                ->get();

            // ===== AUSENCIAS (usa users.id) =====
            // Tu tabla usa fecha_inicio / fecha_fin (NO "fecha")
            $ausencias = OdontologoAusencia::where('odontologo_id', $sel)
                ->orderBy('fecha_inicio', 'desc')
                ->orderBy('fecha_fin', 'desc')
                ->get();

            // ===== ASIGNACIONES (si usas tabla intermedia con odontologo_profiles.id) =====
            if ($profile = OdontologoProfile::where('user_id', $sel)->first()) {
                $asignaciones = OdontologoAsistente::with('asistente')
                    ->where('odontologo_id', $profile->id) // id del perfil
                    ->orderBy('id', 'desc')
                    ->get();
            }
        }

        return view('odontologos.config', compact(
            'odontologos',
            'sel',
            'disponibilidades',
            'ausencias',
            'asignaciones'
        ));
    }

    /**
     * POST /odontologos/disponibilidad  (name: odontologos.disponibilidad.add)
     * Crea o actualiza un bloque de disponibilidad para un día.
     */
    public function addDisponibilidad(Request $request)
    {
        // Valida dia y horas antes de guardar el bloque fijo.
        $data = $request->validate([
            'odontologo_id' => ['required', 'exists:users,id'],
            'dia_semana'    => ['required', 'integer', Rule::in([1, 2, 3, 4, 5, 6, 7])],
            'hora_inicio'   => ['required', 'date_format:H:i'],
            'hora_fin'      => ['required', 'date_format:H:i', 'after:hora_inicio'],
        ]);

        // Evita duplicados por día: sobreescribe si ya existe
        OdontologoDisponibilidad::updateOrCreate(
            ['odontologo_id' => $data['odontologo_id'], 'dia_semana' => $data['dia_semana']],
            ['hora_inicio' => $data['hora_inicio'], 'hora_fin' => $data['hora_fin']]
        );

        return redirect()
            ->route('odontologos.config', ['odontologo_id' => $data['odontologo_id']])
            ->with('ok', 'Disponibilidad guardada.');
    }

    /**
     * DELETE /odontologos/disponibilidad/{bloque}  (name: odontologos.disponibilidad.del)
     */
    public function delDisponibilidad(OdontologoDisponibilidad $bloque)
    {
        // Guarda el usuario antes de borrar para volver al mismo filtro.
        $userId = $bloque->odontologo_id;
        $bloque->delete();

        return redirect()
            ->route('odontologos.config', ['odontologo_id' => $userId])
            ->with('ok', 'Bloque eliminado.');
    }

    /**
     * POST /odontologos/ausencia  (name: odontologos.ausencia.add)
     * Crea una ausencia para un día completo (fecha_inicio = fecha_fin = fecha).
     * Si más adelante agregas horas en la tabla, aquí se puede ampliar.
     */
    public function addAusencia(Request $request)
    {
        // La ausencia se registra como dia completo usando fecha_inicio y fecha_fin iguales.
        $data = $request->validate([
            'odontologo_id' => ['required', 'exists:users,id'],
            'fecha'         => ['required', 'date'],
            'motivo'        => ['nullable', 'string', 'max:120'],
        ]);

        OdontologoAusencia::create([
            'odontologo_id' => $data['odontologo_id'],
            'fecha_inicio'  => $data['fecha'],
            'fecha_fin'     => $data['fecha'],
            'motivo'        => $data['motivo'] ?? null,
        ]);

        return redirect()
            ->route('odontologos.config', ['odontologo_id' => $data['odontologo_id']])
            ->with('ok', 'Ausencia registrada.');
    }

    /**
     * DELETE /odontologos/ausencia/{ausencia}  (name: odontologos.ausencia.del)
     */
    public function delAusencia(OdontologoAusencia $ausencia)
    {
        // Guarda el usuario antes de borrar para regresar a su configuracion.
        $userId = $ausencia->odontologo_id;
        $ausencia->delete();

        return redirect()
            ->route('odontologos.config', ['odontologo_id' => $userId])
            ->with('ok', 'Ausencia eliminada.');
    }
}
