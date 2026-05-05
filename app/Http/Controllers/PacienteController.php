<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PacienteController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q', ''));

        $pacientes = Paciente::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('dni', 'like', "%{$q}%")
                        ->orWhere('telefono', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('provincia', 'like', "%{$q}%")
                        ->orWhere('distrito', 'like', "%{$q}%")
                        ->orWhere('nombre', 'like', "%{$q}%")
                        ->orWhere('apellido', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->appends(['q' => $q]);

        return view('pacientes.index', compact('pacientes', 'q'));
    }

    public function create()
    {
        return view('pacientes.create');
    }

    public function store(Request $request)
    {
        $messages = [
            'dni.unique'       => 'Ya existe un paciente con este DNI.',
            'dni.digits'       => 'El DNI debe tener 8 dígitos.',
            'telefono.digits'  => 'El teléfono debe tener 9 dígitos.',
            'email.email'      => 'Ingresa un correo válido.',
        ];

        $data = $request->validate([
            'nombre'           => ['required', 'string', 'max:120'],
            'apellido'         => ['required', 'string', 'max:120'],
            'dni'              => ['required', 'digits:8', 'unique:pacientes,dni'],
            'telefono'         => ['nullable', 'digits:9'],
            'email'            => ['nullable', 'email', 'max:150'],
            'fecha_nacimiento' => ['nullable', 'date'],

            'sexo'             => ['nullable', Rule::in(['M', 'F', 'O'])],
            'provincia'        => ['nullable', 'string', 'max:80'],
            'distrito'         => ['nullable', 'string', 'max:80'],
            'calle'            => ['nullable', 'string', 'max:120'],
            'numero'           => ['nullable', 'string', 'max:15'],
            'direccion'        => ['nullable', 'string', 'max:160'],
        ], $messages);

        if (empty($data['direccion'])) {
            $calle  = trim((string)($data['calle'] ?? ''));
            $numero = trim((string)($data['numero'] ?? ''));
            $armada = trim($calle . ($numero ? ' N° ' . $numero : ''));
            if ($armada !== '') {
                $data['direccion'] = $armada;
            }
        }

        Paciente::create($data);

        return redirect()
            ->route('pacientes.index')
            ->with('ok', '✅ Paciente registrado exitosamente.');
    }

    public function edit(Paciente $paciente)
    {
        return view('pacientes.edit', compact('paciente'));
    }

    public function update(Request $request, Paciente $paciente)
    {
        $messages = [
            'dni.unique'       => 'Ya existe otro paciente con este DNI.',
            'dni.digits'       => 'El DNI debe tener 8 dígitos.',
            'telefono.digits'  => 'El teléfono debe tener 9 dígitos.',
            'email.email'      => 'Ingresa un correo válido.',
        ];

        $data = $request->validate([
            'nombre'           => ['required', 'string', 'max:120'],
            'apellido'         => ['required', 'string', 'max:120'],
            'dni'              => ['required', 'digits:8', Rule::unique('pacientes', 'dni')->ignore($paciente->id)],
            'telefono'         => ['nullable', 'digits:9'],
            'email'            => ['nullable', 'email', 'max:150'],
            'fecha_nacimiento' => ['nullable', 'date'],

            'sexo'             => ['nullable', Rule::in(['M', 'F', 'O'])],
            'provincia'        => ['nullable', 'string', 'max:80'],
            'distrito'         => ['nullable', 'string', 'max:80'],
            'calle'            => ['nullable', 'string', 'max:120'],
            'numero'           => ['nullable', 'string', 'max:15'],
            'direccion'        => ['nullable', 'string', 'max:160'],
        ], $messages);

        if (empty($data['direccion'])) {
            $calle  = trim((string)($data['calle'] ?? $paciente->calle));
            $numero = trim((string)($data['numero'] ?? $paciente->numero));
            $armada = trim($calle . ($numero ? ' N° ' . $numero : ''));
            if ($armada !== '') {
                $data['direccion'] = $armada;
            }
        }

        $paciente->update($data);

        return redirect()
            ->route('pacientes.index')
            ->with('ok', '🩺 Datos del paciente actualizados correctamente.');
    }

    public function destroy(Paciente $paciente)
    {
        $paciente->delete();

        return back()->with('ok', '⚠️ Paciente eliminado del registro.');
    }
}
