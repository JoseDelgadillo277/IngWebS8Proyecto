{{-- Detalle de historia clinica: datos del paciente, diagnostico y notas. --}}
@extends('layouts.app')

@section('hide_default_nav', true)
@section('title', 'Historia clínica')

@section('content')
    <style>
        :root {
            --glass: rgba(17, 24, 39, .55);
            --glass-border: rgba(255, 255, 255, .10);
            --brand1: #2563eb;
            --brand2: #1d4ed8;
            --danger: #ef4444;
            --ok: #22c55e;
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
            max-width: 1100px;
            margin: 26px auto 110px;
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
            padding: 22px
        }

        h2 {
            margin: 0 0 10px;
            font-weight: 800;
            color: #dbeafe
        }

        .btn {
            display: inline-flex;
            gap: 8px;
            align-items: center;
            padding: 11px 16px;
            border-radius: 12px;
            font-weight: 800;
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, .18);
            color: #eaf3ff;
            background: rgba(255, 255, 255, .10)
        }

        .btn.primary {
            background: linear-gradient(180deg, var(--brand1), var(--brand2));
            border-color: transparent
        }

        .btn.ghost {
            background: rgba(255, 255, 255, .06)
        }

        .btn.danger {
            background: rgba(239, 68, 68, .15);
            border-color: rgba(239, 68, 68, .35)
        }

        .grid {
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

        .col-4 {
            grid-column: span 4
        }

        @media(max-width:980px) {

            .col-6,
            .col-4 {
                grid-column: span 12
            }
        }

        .info {
            background: rgba(255, 255, 255, .06);
            border: 1px solid rgba(255, 255, 255, .15);
            border-radius: 12px;
            padding: 14px 16px
        }

        .label {
            font-weight: 700;
            color: #9db9ff;
            font-size: .85rem
        }

        .value {
            margin-top: 3px;
            white-space: pre-line
        }

        .hint {
            color: #cbd5e1
        }
    </style>

    <div class="dash-bg"></div>

    <div class="wrap">
        {{-- CABECERA --}}
        <div class="glass head">
            <div>
                <div class="hint">Pacientes / Historia clínica</div>
                <h1 class="title">Historia clínica – {{ $paciente->apellido }} {{ $paciente->nombre }}</h1>
            </div>
            <div style="display:flex;align-items:center;gap:10px">
                <a href="{{ route('pacientes.index') }}" class="btn ghost">← Volver a Pacientes</a>
                <img class="logo" src="/images/logo.png" alt="Arte Dental"
                    style="height:56px;filter:drop-shadow(0 0 10px rgba(59,130,246,.6))">
            </div>
        </div>

        {{-- DATOS DEL PACIENTE --}}
        <div class="glass card">
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <h2>Datos del paciente</h2>
                @if (!$historia)
                    <a href="{{ route('historias.create', $paciente) }}" class="btn primary">🩺 Crear historia clínica</a>
                @endif
            </div>

            <div class="grid">
                <div class="col-4">
                    <div class="info">
                        <div class="label">DNI</div>
                        <div class="value">{{ $paciente->dni ?? '—' }}</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="info">
                        <div class="label">Teléfono</div>
                        <div class="value">{{ $paciente->telefono ?? '—' }}</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="info">
                        <div class="label">Email</div>
                        <div class="value">{{ $paciente->email ?? '—' }}</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="info">
                        <div class="label">Fecha de nacimiento</div>
                        <div class="value">{{ optional($paciente->fecha_nacimiento)->format('d/m/Y') ?? '—' }}</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="info">
                        <div class="label">Provincia</div>
                        <div class="value">{{ $paciente->provincia ?? '—' }}</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="info">
                        <div class="label">Distrito</div>
                        <div class="value">{{ $paciente->distrito ?? '—' }}</div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="info">
                        <div class="label">Dirección</div>
                        <div class="value">{{ $paciente->direccion ?? '—' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- HISTORIA CLÍNICA --}}
        <div class="glass card" style="margin-top:16px">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
                <h2>Historia clínica</h2>
                @if ($historia)
                    <a href="{{ route('notas.create', ['historia' => $historia->id]) }}" class="btn primary">➕ Nueva nota
                        clínica</a>
                @endif
            </div>

            @if ($historia)
                <div class="grid">
                    <div class="col-4">
                        <div class="info">
                            <div class="label">Odontólogo</div>
                            <div class="value">{{ optional($historia->odontologo)->name ?? '—' }}</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="info">
                            <div class="label">Fecha de apertura</div>
                            <div class="value">{{ optional($historia->fecha_apertura)->format('d/m/Y') ?? '—' }}</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="info">
                            <div class="label">Estado</div>
                            <div class="value">{{ $historia->estado ?? '—' }}</div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="info">
                            <div class="label">Motivo de consulta</div>
                            <div class="value">{{ $historia->motivo_consulta ?? '—' }}</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="info">
                            <div class="label">Diagnóstico</div>
                            <div class="value">{{ $historia->diagnostico ?? '—' }}</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="info">
                            <div class="label">Plan de tratamiento</div>
                            <div class="value">{{ $historia->plan_tratamiento ?? '—' }}</div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="info">
                            <div class="label">Alergias</div>
                            <div class="value">{{ $historia->alergias ?? '—' }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info">
                            <div class="label">Medicamentos actuales</div>
                            <div class="value">{{ $historia->medicamentos ?? '—' }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info">
                            <div class="label">Antecedentes personales</div>
                            <div class="value">{{ $historia->antecedentes_personales ?? '—' }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info">
                            <div class="label">Antecedentes familiares</div>
                            <div class="value">{{ $historia->antecedentes_familiares ?? '—' }}</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="info">
                            <div class="label">Hábitos</div>
                            <div class="value">{{ $historia->habitos ?? '—' }}</div>
                        </div>
                    </div>

                    {{-- Signos vitales (si los usas) --}}
                    <div class="col-2">
                        <div class="info">
                            <div class="label">PA</div>
                            <div class="value">{{ $historia->pa ?? '—' }}</div>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="info">
                            <div class="label">FC</div>
                            <div class="value">{{ $historia->fc ?? '—' }}</div>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="info">
                            <div class="label">FR</div>
                            <div class="value">{{ $historia->fr ?? '—' }}</div>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="info">
                            <div class="label">Temp</div>
                            <div class="value">{{ $historia->temp ?? '—' }}</div>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="info">
                            <div class="label">SatO₂</div>
                            <div class="value">{{ $historia->sato2 ?? '—' }}</div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="info">
                            <div class="label">Examen extraoral</div>
                            <div class="value">{{ $historia->examen_extraoral ?? '—' }}</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="info">
                            <div class="label">Examen intraoral</div>
                            <div class="value">{{ $historia->examen_intraoral ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            @else
                <p class="hint">Aún no se ha registrado una historia clínica para este paciente.</p>
            @endif
        </div>

        {{-- NOTAS CLÍNICAS --}}
        <div class="glass card" style="margin-top:16px">
            <h2>Notas clínicas</h2>

            @if ($historia && $historia->notas->count())
                @foreach ($historia->notas as $n)
                    <div class="glass"
                        style="padding:14px;margin-bottom:10px;border-radius:12px;border:1px solid rgba(255,255,255,.14);background:rgba(255,255,255,.05)">
                        <div style="display:flex;justify-content:space-between;align-items:center;gap:10px">
                            <div>
                                <strong>{{ optional($n->fecha)->format('d/m/Y H:i') }}</strong>
                                @if ($n->cita_id)
                                    <span class="hint"> · cita #{{ $n->cita_id }}</span>
                                @endif
                            </div>
                            <form method="POST" action="{{ route('notas.destroy', $n) }}"
                                onsubmit="return confirm('¿Eliminar esta nota?')">
                                @csrf @method('DELETE')
                                <button class="btn danger" type="submit">Eliminar</button>
                            </form>
                        </div>

                        @if ($n->procedimiento)
                            <div style="margin-top:6px"><span class="label">Procedimiento</span>
                                <div class="value">{{ $n->procedimiento }}</div>
                            </div>
                        @endif
                        @if ($n->evolucion)
                            <div style="margin-top:6px"><span class="label">Evolución</span>
                                <div class="value">{{ $n->evolucion }}</div>
                            </div>
                        @endif
                        @if ($n->indicaciones)
                            <div style="margin-top:6px"><span class="label">Indicaciones</span>
                                <div class="value">{{ $n->indicaciones }}</div>
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <p class="hint">Aún no hay notas clínicas.</p>
            @endif
        </div>
    </div>
@endsection
