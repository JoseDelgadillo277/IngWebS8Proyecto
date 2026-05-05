{{-- resources/views/notas/create.blade.php --}}
{{-- Registro de nota clinica asociada a una historia y opcionalmente a una cita. --}}
@extends('layouts.app')

@section('hide_default_nav', true)
@section('title', 'Nueva nota clínica')

@section('content')
    <style>
        :root {
            --glass: rgba(17, 24, 39, .55);
            --glass-border: rgba(255, 255, 255, .10);
            --brand1: #2563eb;
            --brand2: #1d4ed8;
            --shadow: 0 12px 30px rgba(0, 0, 0, .35);
            --r: 16px;
        }

        .dash-bg {
            position: fixed;
            inset: 0;
            z-index: -2;
            background:
                linear-gradient(180deg, rgba(6, 11, 28, .70), rgba(6, 11, 28, .55)),
                url('/images/bg-dashboard.jpg') center/cover no-repeat;
        }

        .wrap {
            max-width: 1000px;
            margin: 26px auto 80px;
            padding: 0 14px;
        }

        .glass {
            backdrop-filter: blur(10px) saturate(120%);
            background: var(--glass);
            border: 1px solid var(--glass-border);
            border-radius: var(--r);
            box-shadow: var(--shadow);
            color: #eaf2ff;
        }

        .head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 18px 22px;
            margin-bottom: 14px;
        }

        .title {
            margin: 0;
            font-weight: 900;
            color: #f8fafc;
            font-size: clamp(22px, 3.2vw, 32px);
        }

        .card {
            padding: 20px;
        }

        label {
            display: block;
            margin: 8px 0 6px;
            color: #e5e7eb;
            font-weight: 700
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 12px 14px;
            border-radius: 12px;
            color: #eaf2ff;
            background: rgba(255, 255, 255, .08);
            border: 1px solid rgba(255, 255, 255, .16);
            outline: none
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: rgba(59, 130, 246, .50);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .25)
        }

        .row {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 14px
        }

        .col-12 {
            grid-column: span 12
        }

        .col-6 {
            grid-column: span 6
        }

        @media(max-width:900px) {
            .col-6 {
                grid-column: span 12
            }
        }

        .btn {
            display: inline-flex;
            gap: 8px;
            align-items: center;
            padding: 11px 14px;
            border-radius: 12px;
            font-weight: 800;
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, .18);
            color: #eaf3ff;
            background: rgba(255, 255, 255, .08);
            cursor: pointer
        }

        .btn.primary {
            background: linear-gradient(180deg, var(--brand1), var(--brand2));
            border-color: transparent
        }

        .row-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 16px
        }

        .hint {
            color: #cbd5e1;
            font-size: .9rem
        }

        .badge {
            display: inline-block;
            padding: .35rem .6rem;
            border: 1px solid rgba(255, 255, 255, .18);
            border-radius: 999px;
            background: rgba(255, 255, 255, .06);
            margin-right: .5rem
        }
    </style>

    <div class="dash-bg"></div>

    <div class="wrap">
        {{-- CABECERA --}}
        <div class="glass head">
            <div>
                <div class="hint">Notas clínicas / Nueva</div>
                <h1 class="title">Nueva nota clínica</h1>
            </div>
            <div>
                <a class="btn" href="{{ route('historias.show', $paciente) }}">← Volver a la historia</a>
            </div>
        </div>

        {{-- INFO PACIENTE / CITA --}}
        <div class="glass card" style="margin-bottom:14px">
            <div class="hint" style="margin-bottom:8px">Paciente</div>
            <div>
                <span class="badge">{{ $paciente->dni ?? 'DNI —' }}</span>
                <strong>{{ $paciente->apellido }} {{ $paciente->nombre }}</strong>
                @if ($cita)
                    <span class="badge">Cita: {{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}
                        {{ substr($cita->hora_inicio, 0, 5) }}-{{ substr($cita->hora_fin, 0, 5) }}</span>
                    <span class="badge">Estado: {{ $cita->estado }}</span>
                @endif
            </div>
        </div>

        {{-- ERRORES --}}
        @if ($errors->any())
            <div class="glass card" style="border-left:4px solid #ef4444;margin-bottom:14px">
                <strong>Corrige lo siguiente:</strong>
                <ul style="margin:8px 0 0 18px">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORMULARIO --}}
        <form class="glass card" method="POST" action="{{ route('notas.store', $historia) }}">
            @csrf

            {{-- Si vino desde "Atender", amarra la nota a la cita --}}
            @if ($cita)
                <input type="hidden" name="cita_id" value="{{ $cita->id }}">
            @endif

            <div class="row">
                <div class="col-6">
                    <label>Fecha y hora</label>
                    <input type="datetime-local" name="fecha" value="{{ old('fecha') }}" id="fechaInput" required>
                    <div class="hint">Puedes ajustar la hora de atención.</div>
                </div>

                <div class="col-6">
                    <label>Procedimiento</label>
                    <input type="text" name="procedimiento" value="{{ old('procedimiento') }}"
                        placeholder="p. ej. Limpieza, obturación, etc.">
                </div>

                <div class="col-12">
                    <label>Evolución</label>
                    <textarea name="evolucion" rows="4" placeholder="Hallazgos, evolución, técnicas, anestesia...">{{ old('evolucion') }}</textarea>
                </div>

                <div class="col-12">
                    <label>Indicaciones</label>
                    <textarea name="indicaciones" rows="3" placeholder="Indicaciones al alta, medicamentos, controles...">{{ old('indicaciones') }}</textarea>
                </div>
            </div>

            <div class="row-actions">
                <a class="btn" href="{{ route('historias.show', $paciente) }}">Cancelar</a>
                <button class="btn primary" type="submit">💾 Guardar nota</button>
            </div>
        </form>
    </div>

    <script>
        // Si no viene old('fecha'), precarga ahora() en formato datetime-local
        (function() {
            const inp = document.getElementById('fechaInput');
            if (!inp.value) {
                const d = new Date();
                const pad = n => String(n).padStart(2, '0');
                const val = d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate()) +
                    'T' + pad(d.getHours()) + ':' + pad(d.getMinutes());
                inp.value = val;
            }
        })();
    </script>
@endsection
