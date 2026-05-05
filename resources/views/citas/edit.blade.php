<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Cita</title>
    <style>
        body {
            font-family: system-ui, Arial, sans-serif;
            margin: 24px;
        }

        h1 {
            margin-bottom: 12px;
        }

        label {
            display: inline-block;
            width: 140px;
            margin-right: 8px;
        }

        input,
        select,
        textarea,
        button {
            padding: 6px;
            font-size: 14px;
        }

        .errors {
            color: #b30000;
            margin: 8px 0;
        }
    </style>
</head>

<body>
    <h1>Editar Cita</h1>

    @if ($errors->any())
        <div class="errors">
            <ul>
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('citas.update', $cita) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Paciente:</label>
        <select name="paciente_id" required>
            @foreach ($pacientes as $p)
                <option value="{{ $p->id }}"
                    {{ old('paciente_id', $cita->paciente_id) == $p->id ? 'selected' : '' }}>
                    {{ $p->nombre }} {{ $p->apellido }}
                </option>
            @endforeach
        </select><br><br>

        <label>Fecha:</label>
        <input type="date" name="fecha" value="{{ old('fecha', $cita->fecha) }}" required><br><br>

        <label>Hora Inicio:</label>
        <input type="time" name="hora_inicio" value="{{ old('hora_inicio', $cita->hora_inicio) }}" required><br><br>

        <label>Hora Fin:</label>
        <input type="time" name="hora_fin" value="{{ old('hora_fin', $cita->hora_fin) }}" required><br><br>

        <label>Odontólogo:</label>
        <select name="odontologo_id" id="odontologo_id" required>
            @foreach ($odontologos as $o)
                <option value="{{ $o->id }}"
                    {{ old('odontologo_id', $cita->odontologo_id) == $o->id ? 'selected' : '' }}>
                    {{ $o->name }}
                </option>
            @endforeach
        </select><br><br>

        <label>Estado:</label>
        <select name="estado">
            <option value="programada" {{ old('estado', $cita->estado) == 'programada' ? 'selected' : '' }}>Programada
            </option>
            <option value="atendida" {{ old('estado', $cita->estado) == 'atendida' ? 'selected' : '' }}>Atendida
            </option>
            <option value="cancelada" {{ old('estado', $cita->estado) == 'cancelada' ? 'selected' : '' }}>Cancelada
            </option>
        </select><br><br>

        <label>Motivo:</label>
        <input type="text" name="motivo" value="{{ old('motivo', $cita->motivo) }}"><br><br>

        <label>Notas:</label>
        <textarea name="notas">{{ old('notas', $cita->notas) }}</textarea><br><br>

        <button type="submit">Actualizar</button>
    </form>

    <br>
    <a href="{{ route('citas.index') }}">← Volver</a>

    <script>
        async function cargarOdontologosEdit() {
            const fecha = document.querySelector('[name="fecha"]').value;
            const ini = document.querySelector('[name="hora_inicio"]').value;
            const fin = document.querySelector('[name="hora_fin"]').value;
            const sel = document.getElementById('odontologo_id');
            const except_id = '{{ $cita->id }}';

            if (!fecha || !ini || !fin) return;

            try {
                const params = new URLSearchParams({
                    fecha,
                    hora_inicio: ini,
                    hora_fin: fin,
                    except_id
                });
                const url = '{{ route('citas.odontologos.disponibles') }}?' + params.toString();

                const res = await fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                if (!res.ok) throw new Error('HTTP ' + res.status);
                const data = await res.json();

                const actual = String('{{ old('odontologo_id', $cita->odontologo_id) }}');
                sel.innerHTML = '';

                if (Array.isArray(data) && data.length) {
                    for (const o of data) {
                        const opt = document.createElement('option');
                        opt.value = o.id;
                        opt.textContent = o.name;
                        if (String(o.id) === actual) opt.selected = true;
                        sel.appendChild(opt);
                    }
                } else {
                    const opt = document.createElement('option');
                    opt.value = '';
                    opt.textContent = 'No hay odontólogos disponibles';
                    sel.appendChild(opt);
                }
            } catch (e) {
                console.error(e);
            }
        }

        // Cuando cambien fecha/horas, recalcula disponibles
        document.querySelector('[name="fecha"]').addEventListener('change', cargarOdontologosEdit);
        document.querySelector('[name="hora_inicio"]').addEventListener('change', cargarOdontologosEdit);
        document.querySelector('[name="hora_fin"]').addEventListener('change', cargarOdontologosEdit);

        // Al cargar, intenta refrescar (útil tras un error de validación)
        window.addEventListener('DOMContentLoaded', () => {
            const f = document.querySelector('[name="fecha"]').value;
            const i = document.querySelector('[name="hora_inicio"]').value;
            const x = document.querySelector('[name="hora_fin"]').value;
            if (f && i && x) cargarOdontologosEdit();
        });
    </script>
</body>

</html>
