<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

use App\Models\Cita;
use App\Models\Paciente;
use App\Models\User;

// Disponibilidad / ausencias / asistentes
use App\Models\OdontologoDisponibilidad;
use App\Models\OdontologoAusencia;
use App\Models\OdontologoProfile;
use App\Models\OdontologoAsistente;

class CitaController extends Controller
{
    /**
     * Muestra el listado de citas con filtros por fecha, odontologo y estado.
     */
    public function index(Request $request)
    {
        // with() trae las relaciones necesarias para mostrar nombres sin consultas extra.
        $q = Cita::with(['paciente', 'odontologo'])
            ->when($request->filled('fecha'), fn($qq) => $qq->where('fecha', $request->fecha))
            ->when($request->filled('odontologo_id'), fn($qq) => $qq->where('odontologo_id', $request->odontologo_id))
            ->when($request->filled('estado'), fn($qq) => $qq->where('estado', $request->estado))
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_inicio', 'desc');

        $citas = $q->paginate(10)->appends($request->query());

        $odontologos = class_exists(\Spatie\Permission\Models\Role::class)
            ? User::role('odontologo')->orderBy('name')->get()
            : User::orderBy('name')->get();

        return view('citas.index', compact('citas', 'odontologos'));
    }

    public function create()
    {
        // Datos necesarios para llenar los select del formulario de nueva cita.
        $pacientes   = Paciente::orderBy('apellido')->orderBy('nombre')->get();
        $odontologos = class_exists(\Spatie\Permission\Models\Role::class)
            ? User::role('odontologo')->orderBy('name')->get()
            : User::orderBy('name')->get();

        return view('citas.create', compact('pacientes', 'odontologos'));
    }

    public function store(Request $request)
    {
        // Validacion base de datos obligatorios y formatos de hora.
        $data = $request->validate([
            'paciente_id'   => ['required', 'exists:pacientes,id'],
            'odontologo_id' => ['required', 'exists:users,id'],
            'fecha'         => ['required', 'date'],
            'hora_inicio'   => ['required', 'date_format:H:i'],
            'hora_fin'      => ['required', 'date_format:H:i', 'after:hora_inicio'],
            'estado'        => ['nullable', 'in:programada,atendida,cancelada'],
            'motivo'        => ['nullable', 'string', 'max:150'],
            'notas'         => ['nullable', 'string'],
        ]);

        $fecha  = $data['fecha'];
        $inicio = $data['hora_inicio'];
        $fin    = $data['hora_fin'];
        $docId  = $data['odontologo_id'];

        // Carbon usa domingo=0; el sistema guarda los dias como 1..7.
        $dow0   = Carbon::parse($fecha)->dayOfWeek; // 0..6 (0=domingo)
        $dia1a7 = $dow0 === 0 ? 7 : $dow0;

        // Verifica que la cita caiga dentro del horario fijo del odontologo.
        $bloque = OdontologoDisponibilidad::where('odontologo_id', $docId)
            ->where('dia_semana', $dia1a7)
            ->first();

        if (!$bloque || !($inicio >= substr($bloque->hora_inicio, 0, 5) && $fin <= substr($bloque->hora_fin, 0, 5))) {
            throw ValidationException::withMessages([
                'odontologo_id' => 'El odontólogo está fuera de su horario fijo para ese día.'
            ]);
        }

        // Impide reservar si el odontologo tiene una ausencia registrada.
        $ausente = OdontologoAusencia::where('odontologo_id', $docId)
            ->whereDate('fecha_inicio', '<=', $fecha)
            ->whereDate('fecha_fin',    '>=', $fecha)
            ->exists();

        if ($ausente) {
            throw ValidationException::withMessages([
                'odontologo_id' => 'El odontólogo está ausente ese día.'
            ]);
        }

        // Detecta cruce de horarios con otras citas del mismo odontologo.
        $ocupado = Cita::where('odontologo_id', $docId)
            ->whereDate('fecha', $fecha)
            ->where(function ($q) use ($inicio, $fin) {
                $q->where('hora_inicio', '<', $fin)
                    ->where('hora_fin',    '>', $inicio);
            })
            ->exists();

        if ($ocupado) {
            throw ValidationException::withMessages([
                'hora_inicio' => 'El odontólogo ya tiene una cita en ese rango horario.'
            ]);
        }

        // Compatibilidad con un posible scope/helper seSolapa definido en el modelo.
        if (method_exists(Cita::class, 'seSolapa')) {
            $choque = Cita::seSolapa($docId, $fecha, $inicio, $fin)->exists();
            if ($choque) {
                throw ValidationException::withMessages([
                    'hora_inicio' => 'El odontólogo ya tiene una cita en ese rango (seSolapa).'
                ]);
            }
        }

        $data['estado'] = $data['estado'] ?? 'programada';
        Cita::create($data);

        return redirect()->route('citas.index')->with('ok', '✅ Cita registrada exitosamente.');
    }

    public function edit(Cita $cita)
    {
        // Carga listas auxiliares para editar la cita seleccionada.
        $pacientes   = Paciente::orderBy('apellido')->orderBy('nombre')->get();
        $odontologos = class_exists(\Spatie\Permission\Models\Role::class)
            ? User::role('odontologo')->orderBy('name')->get()
            : User::orderBy('name')->get();

        return view('citas.edit', compact('cita', 'pacientes', 'odontologos'));
    }

    public function update(Request $request, Cita $cita)
    {
        // Usa reglas similares al registro, pero excluye la cita actual al revisar cruces.
        $data = $request->validate([
            'paciente_id'   => ['required', 'exists:pacientes,id'],
            'odontologo_id' => ['required', 'exists:users,id'],
            'fecha'         => ['required', 'date'],
            'hora_inicio'   => ['required', 'date_format:H:i'],
            'hora_fin'      => ['required', 'date_format:H:i', 'after:hora_inicio'],
            'estado'        => ['nullable', 'in:programada,atendida,cancelada'],
            'motivo'        => ['nullable', 'string', 'max:150'],
            'notas'         => ['nullable', 'string'],
        ]);

        $fecha  = $data['fecha'];
        $inicio = $data['hora_inicio'];
        $fin    = $data['hora_fin'];
        $docId  = $data['odontologo_id'];

        $dow0   = Carbon::parse($fecha)->dayOfWeek;
        $dia1a7 = $dow0 === 0 ? 7 : $dow0;

        $bloque = OdontologoDisponibilidad::where('odontologo_id', $docId)
            ->where('dia_semana', $dia1a7)
            ->first();

        if (!$bloque || !($inicio >= substr($bloque->hora_inicio, 0, 5) && $fin <= substr($bloque->hora_fin, 0, 5))) {
            return back()->withErrors(['odontologo_id' => 'El odontólogo está fuera de su horario fijo.'])->withInput();
        }

        $ausente = OdontologoAusencia::where('odontologo_id', $docId)
            ->whereDate('fecha_inicio', '<=', $fecha)
            ->whereDate('fecha_fin',    '>=', $fecha)
            ->exists();

        if ($ausente) {
            return back()->withErrors(['odontologo_id' => 'El odontólogo está ausente ese día.'])->withInput();
        }

        $ocupado = Cita::where('odontologo_id', $docId)
            ->whereDate('fecha', $fecha)
            ->where('id', '<>', $cita->id)
            ->where(function ($q) use ($inicio, $fin) {
                $q->where('hora_inicio', '<', $fin)
                    ->where('hora_fin',    '>', $inicio);
            })
            ->exists();

        if ($ocupado) {
            return back()->withErrors(['hora_inicio' => 'El odontólogo ya tiene una cita en ese rango.'])->withInput();
        }

        if (method_exists(Cita::class, 'seSolapa')) {
            $choque = Cita::seSolapa($docId, $fecha, $inicio, $fin, $cita->id)->exists();
            if ($choque) {
                return back()->withErrors(['hora_inicio' => 'El odontólogo ya tiene una cita en ese rango (seSolapa).'])->withInput();
            }
        }

        $data['estado'] = $data['estado'] ?? 'programada';
        $cita->update($data);

        return redirect()->route('citas.index')->with('ok', '🩺 Cita actualizada correctamente.');
    }

    public function destroy(Cita $cita)
    {
        // Borra la cita indicada y vuelve al listado anterior.
        $cita->delete();
        return back()->with('ok', '🗑️ Cita eliminada correctamente.');
    }

    public function reprogram(Request $request, Cita $cita)
    {
        // Solo cambia fecha y horas, manteniendo paciente y odontologo.
        $data = $request->validate([
            'fecha'       => ['required', 'date'],
            'hora_inicio' => ['required', 'date_format:H:i'],
            'hora_fin'    => ['required', 'date_format:H:i', 'after:hora_inicio'],
        ]);

        $fecha  = $data['fecha'];
        $inicio = $data['hora_inicio'];
        $fin    = $data['hora_fin'];

        // Evita que la nueva hora se cruce con otra cita del mismo odontologo.
        $ocupado = Cita::where('odontologo_id', $cita->odontologo_id)
            ->whereDate('fecha', $fecha)
            ->where('id', '<>', $cita->id)
            ->where(function ($q) use ($inicio, $fin) {
                $q->where('hora_inicio', '<', $fin)
                    ->where('hora_fin',    '>', $inicio);
            })
            ->exists();

        if ($ocupado) {
            return back()->withErrors([
                'hora_inicio' => 'El odontólogo ya tiene una cita en ese rango horario.'
            ]);
        }

        $cita->update($data);

        return back()->with('ok', '📅 Cita reprogramada correctamente.');
    }

    public function cancel(Cita $cita)
    {
        // Cancelar no elimina la cita; conserva el historial cambiando el estado.
        $cita->update(['estado' => 'cancelada']);
        return back()->with('ok', '⚠️ Cita cancelada correctamente.');
    }

    public function checkin(Cita $cita)
    {
        // Marca que el paciente llego a la cita.
        $cita->update(['estado' => \App\Models\Cita::EST_CHECKIN ?? 'checkin']);
        return back()->with('ok', '✅ Check-in registrado.');
    }

    // ✅ Atender: crear historia si no existe; si existe, ir a Nota clínica
    public function atender(Cita $cita)
    {
        // La atencion parte desde el paciente asociado a la cita.
        $paciente = $cita->paciente;

        if (!$paciente) {
            return back()->withErrors(['cita' => 'Paciente no encontrado para esta cita.']);
        }

        if (!$paciente->historiaClinica) {
            return redirect()
                ->route('historias.create', $paciente)
                ->with('ok', 'Crea la historia clínica para iniciar la atención.');
        }

        $historia = $paciente->historiaClinica;
        return redirect()->route('notas.create', [
            'historia' => $historia->id,
            'cita'     => $cita->id,
        ]);
    }

    /**
     * GET /citas/odontologos-disponibles?fecha=YYYY-MM-DD&inicio=HH:MM&fin=HH:MM
     * Devuelve JSON con { id, name, disponible, motivo, asistentes[] }
     */
    public function disponibles(Request $request)
    {
        try {
            // Acepta inicio/fin o hora_inicio/hora_fin
            // Acepta inicio/fin o hora_inicio/hora_fin para facilitar llamadas desde JS.
            $inicio = $request->input('inicio', $request->input('hora_inicio'));
            $fin    = $request->input('fin',    $request->input('hora_fin'));
            $request->merge(['inicio' => $inicio, 'fin' => $fin]);

            $request->validate([
                'fecha'  => ['required', 'date'],
                'inicio' => ['required', 'date_format:H:i'],
                'fin'    => ['required', 'date_format:H:i', 'after:inicio'],
            ]);

            $fecha  = $request->fecha;

            // Lista base de odontólogos (rol o perfil)
            // Lista base de odontologos: por rol o por perfil creado.
            $odontologos = User::where(function ($q) {
                if (class_exists(\Spatie\Permission\Models\Role::class)) {
                    $q->whereHas('roles', fn($r) => $r->where('name', 'odontologo'));
                }
            })
                ->orWhereHas('odontologoProfile')
                ->orderBy('name')
                ->get();

            $out = [];

            $dow0   = Carbon::parse($fecha)->dayOfWeek; // 0..6
            $dia1a7 = $dow0 === 0 ? 7 : $dow0;

            foreach ($odontologos as $doc) {
                $motivosNo = [];

                // 1) Horario fijo
                // 1) Horario fijo: debe trabajar ese dia y cubrir el rango solicitado.
                $bloque = OdontologoDisponibilidad::where('odontologo_id', $doc->id)
                    ->where('dia_semana', $dia1a7)
                    ->first();

                if (!$bloque) {
                    $motivosNo[] = 'No trabaja ese día';
                } elseif (!($inicio >= substr($bloque->hora_inicio, 0, 5) && $fin <= substr($bloque->hora_fin, 0, 5))) {
                    $motivosNo[] = 'Fuera de su horario fijo';
                }

                // 2) Ausencia (rango)
                // 2) Ausencia: bloquea al odontologo si la fecha cae en el rango.
                $ausencia = OdontologoAusencia::where('odontologo_id', $doc->id)
                    ->whereDate('fecha_inicio', '<=', $fecha)
                    ->whereDate('fecha_fin',    '>=',  $fecha)
                    ->exists();

                if ($ausencia) {
                    $motivosNo[] = 'Ausente (vacaciones/procedimiento)';
                }

                // 3) Choque con otra cita
                // 3) Choque con otra cita en el mismo intervalo.
                $ocupado = Cita::where('odontologo_id', $doc->id)
                    ->whereDate('fecha', $fecha)
                    ->where(function ($q) use ($inicio, $fin) {
                        $q->where('hora_inicio', '<', $fin)
                            ->where('hora_fin',    '>', $inicio);
                    })
                    ->exists();

                if ($ocupado) {
                    $motivosNo[] = 'Choque con otra cita';
                }

                // 4) Asistentes (vía perfil)
                // 4) Asistentes asignados al odontologo mediante su perfil.
                $asistentes = [];
                $profile = $doc->odontologoProfile()->first();
                if ($profile) {
                    $asig = OdontologoAsistente::with('asistente')
                        ->where('odontologo_id', $profile->id)
                        ->get();
                    foreach ($asig as $row) {
                        if ($row->asistente) $asistentes[] = $row->asistente->name;
                    }
                }

                $out[] = [
                    'id'         => $doc->id,
                    'name'       => $doc->name,
                    'disponible' => empty($motivosNo),
                    'motivo'     => empty($motivosNo) ? null : implode(' · ', $motivosNo),
                    'asistentes' => $asistentes,
                ];
            }

            return response()->json($out, 200);
        } catch (\Throwable $e) {
            return response()->json([
                'error'   => true,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
