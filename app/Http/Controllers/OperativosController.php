<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HistoriaClinica;
use App\Models\User;
use App\Models\Cita;

class OperativosController extends Controller
{
    /** Listado de odontólogos (si hay Spatie, solo los de rol odontólogo). */
    private function odontologos()
    {
        if (class_exists(\Spatie\Permission\Models\Role::class)) {
            return User::role('odontologo')->orderBy('name')->get();
        }
        return User::orderBy('name')->get();
    }

    /**
     * ============ Diagnóstico y Plan ============
     * Lista Historias clínicas con filtros:
     * - fecha (fecha_apertura)
     * - odontologo_id
     * - estado (abierta/cerrada)
     * Incluye la última nota clínica.
     */
    public function diagnostico(Request $request)
    {
        // Consulta historias con relaciones para mostrar paciente, odontologo y ultima nota.
        $q = HistoriaClinica::with([
            'paciente',
            'odontologo',
            'notas' => fn($qq) => $qq->latest('fecha')->limit(1),
        ])
            ->when(
                $request->filled('fecha'),
                fn($qq) =>
                $qq->whereDate('fecha_apertura', $request->fecha)
            )
            ->when(
                $request->filled('odontologo_id'),
                fn($qq) =>
                $qq->where('odontologo_id', $request->odontologo_id)
            )
            ->when(
                $request->filled('estado'),
                fn($qq) =>
                $qq->where('estado', $request->estado)
            )
            ->orderByDesc('fecha_apertura');

        $historias   = $q->paginate(10)->appends($request->query());
        $odontologos = $this->odontologos();
        $filters     = $request->only(['fecha', 'odontologo_id', 'estado']);

        return view('operativos.diagnostico', compact('historias', 'odontologos', 'filters'));
    }

    /**
     * ============ Tratamiento ============
     * Historias activas (o filtradas) con última nota.
     */
    public function tratamiento(Request $request)
    {
        // Reune filtros del formulario para mantenerlos al paginar.
        $filters = $request->only(['buscar', 'odontologo_id', 'estado', 'desde', 'hasta']);

        // Lista historias en tratamiento o abiertas, con busqueda por paciente.
        $q = HistoriaClinica::with([
            'paciente',
            'odontologo',
            'notas' => fn($qq) => $qq->latest('fecha')->limit(1),
        ])
            ->when(
                $filters['odontologo_id'] ?? null,
                fn($qq, $doc) =>
                $qq->where('odontologo_id', $doc)
            )
            ->when(
                $filters['estado'] ?? null,
                fn($qq, $est) =>
                $qq->where('estado', $est),
                fn($qq) => $qq->whereIn('estado', ['abierta', 'en_tratamiento'])
            )
            ->when(
                $filters['desde'] ?? null,
                fn($qq, $d) =>
                $qq->whereDate('fecha_apertura', '>=', $d)
            )
            ->when(
                $filters['hasta'] ?? null,
                fn($qq, $h) =>
                $qq->whereDate('fecha_apertura', '<=', $h)
            )
            ->when($filters['buscar'] ?? null, function ($qq, $term) {
                $qq->whereHas('paciente', function ($qp) use ($term) {
                    $qp->where('nombre',   'like', "%{$term}%")
                        ->orWhere('apellido', 'like', "%{$term}%")
                        ->orWhere('dni',     'like', "%{$term}%");
                });
            })
            ->orderByDesc('fecha_apertura');

        $historias   = $q->paginate(10)->appends($filters);
        $odontologos = $this->odontologos();

        return view('operativos.tratamiento', compact('historias', 'odontologos', 'filters'));
    }

    /**
     * ============ Seguimiento ============
     * Citas filtradas para seguimiento.
     */
    public function seguimiento(Request $request)
    {
        // Filtros opcionales para revisar citas atendidas o en seguimiento.
        $fecha        = $request->input('fecha');
        $estado       = $request->input('estado');
        $odontologoId = $request->input('odontologo_id');

        // Carga citas y sus relaciones para el tablero de seguimiento.
        $citas = Cita::with(['paciente', 'odontologo'])
            ->when($fecha,        fn($q) => $q->whereDate('fecha', $fecha))
            ->when($odontologoId, fn($q) => $q->where('odontologo_id', $odontologoId))
            ->when(
                $estado,
                fn($q) => $q->where('estado', $estado),
                fn($q) => $q->whereIn('estado', ['atendida', 'seguimiento'])
            )
            ->orderByDesc('fecha')
            ->orderBy('hora_inicio', 'asc')
            ->paginate(10)
            ->appends($request->query());

        $odontologos = $this->odontologos();

        return view('operativos.seguimiento', compact('citas', 'odontologos'));
    }
}
