<?php

namespace App\Http\Controllers\Soporte;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pago;
use App\Models\Paciente;
use Illuminate\Support\Facades\Auth;

class PagoController extends Controller
{
    /** 🧾 Mostrar formulario de registro de pago */
    public function create()
    {
        // Traemos los pacientes y armamos etiqueta "Apellidos, Nombres"
        $raw = Paciente::orderBy('id', 'asc')->get();

        $pacientes = $raw->map(function ($p) {
            $ape = $p->apellido ?? '';
            $nom = $p->nombre ?? '';

            $label = trim(implode(', ', array_filter([$ape ?: null, $nom ?: null])));

            if ($label === '') {
                $label = 'Paciente #' . $p->id;
            }

            return (object)[
                'id'    => $p->id,
                'label' => $label,
            ];
        });

        return view('soporte.finanzas.pagos.create', compact('pacientes'));
    }

    /** 💾 Guardar pago en BD */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'paciente_id'       => ['required', 'exists:pacientes,id'],
            'documento_tipo'    => ['required', 'string', 'max:12'],
            'documento_numero'  => ['nullable', 'string', 'max:30'],
            'concepto'          => ['required', 'string', 'max:120'],
            'monto'             => ['required', 'numeric', 'min:0'],
            'moneda'            => ['required', 'string', 'size:3'],
            'medio_pago'        => ['required', 'string', 'max:20'],
            'numero_operacion'  => ['nullable', 'string', 'max:40'],
            'fecha'             => ['required', 'date'],
            'observacion'       => ['nullable', 'string', 'max:255'],
        ]);

        $validated['registrado_por'] = Auth::id(); // usuario logueado

        Pago::create($validated);

        // ✅ Redirigir al historial de pagos
        return redirect()
            ->route('soporte.finanzas.pagos.index')
            ->with('ok', '✅ Pago registrado correctamente.');
    }

    /** 📋 Listar pagos registrados */
    public function index(Request $request)
    {
        $query = Pago::with('paciente', 'usuario')
            ->orderBy('fecha', 'desc');

        // 🧍 Filtrar por nombre o apellido del paciente
        if ($request->filled('paciente')) {
            $query->whereHas('paciente', function ($q) use ($request) {
                $q->where('nombre', 'like', "%{$request->paciente}%")
                    ->orWhere('apellido', 'like', "%{$request->paciente}%");
            });
        }

        // 📅 Filtrar por rango de fechas
        if ($request->filled('desde')) {
            $query->whereDate('fecha', '>=', $request->desde);
        }
        if ($request->filled('hasta')) {
            $query->whereDate('fecha', '<=', $request->hasta);
        }

        $pagos = $query->paginate(10)->appends($request->query());

        return view('soporte.finanzas.pagos.index', compact('pagos'));
    }
}
