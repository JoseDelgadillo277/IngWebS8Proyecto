{{-- Proceso operativo de diagnostico: historias clinicas filtradas. --}}
@extends('layouts.app')

@section('hide_default_nav', true)
@section('title', 'Diagnóstico y Plan')

@section('content')
    <style>
        :root {
            --glass: rgba(17, 24, 39, .55);
            --glass-b: rgba(255, 255, 255, .10);
            --brand1: #2563eb;
            --brand2: #1d4ed8;
            --r: 16px;
        }

        .bg {
            position: fixed;
            inset: 0;
            z-index: -2;
            background:
                linear-gradient(180deg, rgba(6, 11, 28, .70), rgba(6, 11, 28, .55)),
                url('/images/bg-dashboard.jpg') center/cover no-repeat
        }

        .wrap {
            max-width: 1200px;
            margin: 26px auto 90px;
            padding: 0 14px
        }

        .glass {
            backdrop-filter: blur(10px) saturate(120%);
            background: var(--glass);
            border: 1px solid var(--glass-b);
            border-radius: var(--r);
            color: #eaf2ff;
            box-shadow: 0 12px 30px rgba(0, 0, 0, .35)
        }

        .head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 22px;
            margin-bottom: 14px
        }

        .title {
            margin: 0;
            font-weight: 900;
            font-size: clamp(22px, 3.2vw, 32px);
            color: #fff;
            text-shadow: 0 0 18px rgba(59, 130, 246, .35)
        }

        .btn {
            display: inline-flex;
            gap: 8px;
            align-items: center;
            border: 1px solid rgba(255, 255, 255, .18);
            padding: 10px 14px;
            border-radius: 12px;
            color: #eaf2ff;
            background: rgba(255, 255, 255, .10);
            text-decoration: none;
            font-weight: 800;
            transition: .2s
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 18px rgba(59, 130, 246, .35)
        }

        .btn.primary {
            background: linear-gradient(180deg, var(--brand1), var(--brand2));
            border-color: transparent
        }

        .card {
            padding: 18px
        }

        label {
            font-weight: 700;
            font-size: .9rem
        }

        input,
        select {
            background: rgba(255, 255, 255, .08);
            border: 1px solid rgba(255, 255, 255, .18);
            color: #eaf2ff;
            border-radius: 12px;
            padding: 10px 12px;
            width: 100%;
            outline: none
        }

        input:focus,
        select:focus {
            border-color: rgba(59, 130, 246, .50);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .25)
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 12px
        }

        .col-3 {
            grid-column: span 3
        }

        .col-2 {
            grid-column: span 2
        }

        .col-4 {
            grid-column: span 4
        }

        .col-12 {
            grid-column: span 12
        }

        @media(max-width:980px) {

            .col-3,
            .col-2,
            .col-4 {
                grid-column: span 12
            }
        }

        .hint {
            color: #cbd5e1
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px
        }

        thead th {
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .8px;
            text-align: left;
            padding: 10px 12px;
            color: #fff;
            background: linear-gradient(90deg, var(--brand1), #1d4ed8);
            border-top-left-radius: 10px;
            border-top-right-radius: 10px
        }

        tbody tr {
            background: rgba(255, 255, 255, .06);
            border: 1px solid rgba(255, 255, 255, .14)
        }

        tbody td {
            padding: 12px 14px;
            vertical-align: top
        }

        .badge {
            background: rgba(255, 255, 255, .1);
            border: 1px solid rgba(255, 255, 255, .2);
            padding: .25rem .55rem;
            border-radius: 999px;
            font-size: .75rem
        }
    </style>

    <div class="bg"></div>

    <div class="wrap">
        {{-- Encabezado --}}
        <div class="glass head">
            <div>
                <div style="color:#cbd5e1;font-size:.9rem">Operativos / Gestión clínica</div>
                <h1 class="title">Diagnóstico y Plan</h1>
            </div>
            <a class="btn" href="{{ route('dashboard') }}">← Volver al Panel</a>
        </div>

        {{-- Filtros --}}
        <div class="glass card">
            <form class="grid" method="GET" action="{{ route('operativos.diagnostico') }}">
                <div class="col-3">
                    <label>Fecha (apertura)</label>
                    <input type="date" name="fecha" value="{{ $filters['fecha'] ?? '' }}">
                </div>
                <div class="col-4">
                    <label>Odontólogo</label>
                    <select name="odontologo_id">
                        <option value="">(todos)</option>
                        @foreach ($odontologos as $o)
                            <option value="{{ $o->id }}" @selected(($filters['odontologo_id'] ?? '') == $o->id)>{{ $o->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3">
                    <label>Estado</label>
                    <select name="estado">
                        <option value="">(todos)</option>
                        <option value="abierta" @selected(($filters['estado'] ?? '') === 'abierta')>Abierta</option>
                        <option value="cerrada" @selected(($filters['estado'] ?? '') === 'cerrada')>Cerrada</option>
                    </select>
                </div>
                <div class="col-2" style="display:flex;gap:8px;align-items:flex-end">
                    <button class="btn primary" type="submit">Filtrar</button>
                    <a class="btn" href="{{ route('operativos.diagnostico') }}">Limpiar</a>
                </div>
            </form>
        </div>

        {{-- Tabla --}}
        <div class="glass card" style="margin-top:12px">
            @if ($historias->count() === 0)
                <div class="hint">No hay registros con los filtros aplicados.</div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Paciente</th>
                            <th>Odontólogo</th>
                            <th>Fecha apertura</th>
                            <th>Diagnóstico</th>
                            <th>Plan</th>
                            <th>Última nota</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($historias as $h)
                            @php $nota = $h->notas->first(); @endphp
                            <tr>
                                <td>
                                    <div style="font-weight:800">{{ $h->paciente?->apellido }} {{ $h->paciente?->nombre }}
                                    </div>
                                    <div class="hint">DNI: {{ $h->paciente?->dni ?? '—' }}</div>
                                </td>
                                <td>{{ $h->odontologo?->name ?? '—' }}</td>
                                <td>{{ optional($h->fecha_apertura)->format('d/m/Y') ?? '—' }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($h->diagnostico ?? '—', 70) }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($h->plan_tratamiento ?? '—', 70) }}</td>
                                <td>
                                    @if ($nota)
                                        <div><span class="badge">{{ optional($nota->fecha)->format('d/m/Y H:i') }}</span>
                                        </div>
                                        @if ($nota->procedimiento)
                                            <div class="hint">
                                                {{ \Illuminate\Support\Str::limit($nota->procedimiento, 50) }}</div>
                                        @endif
                                    @else
                                        <span class="hint">—</span>
                                    @endif
                                </td>
                                <td style="display:flex;gap:8px;flex-wrap:wrap">
                                    <a class="btn" href="{{ route('historias.show', $h->paciente) }}">Ver historia</a>
                                    <a class="btn primary" href="{{ route('notas.create', ['historia' => $h->id]) }}">+
                                        Nota</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="margin-top:12px">
                    {{ $historias->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
