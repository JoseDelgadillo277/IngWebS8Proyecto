@extends('layouts.app')

@section('hide_default_nav', true)
@section('title', 'Tratamiento Odontológico')

@section('content')
    <style>
        :root {
            --glass: rgba(17, 24, 39, .55);
            --glass-border: rgba(255, 255, 255, .12);
            --input-bg: rgba(255, 255, 255, .08);
            --input-bg-focus: rgba(255, 255, 255, .12);
            --input-border: rgba(255, 255, 255, .22);
            --text: #eaf2ff;
            --muted: #cbd5e1;
            --brand1: #2563eb;
            --brand2: #1d4ed8;
            --r: 16px;
        }

        .bg {
            position: fixed;
            inset: 0;
            z-index: -2;
            background: linear-gradient(180deg, rgba(6, 11, 28, .70), rgba(6, 11, 28, .55)),
                url('/images/bg-dashboard.jpg') center/cover no-repeat;
        }

        .wrap {
            max-width: 1100px;
            margin: 26px auto 80px;
            padding: 0 14px
        }

        .glass {
            backdrop-filter: blur(12px) saturate(120%);
            background: var(--glass);
            border: 1px solid var(--glass-border);
            border-radius: var(--r);
            color: var(--text)
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
            color: #fff
        }

        .card {
            padding: 18px
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 12px
        }

        .col-3 {
            grid-column: span 3
        }

        .col-4 {
            grid-column: span 4
        }

        .col-2 {
            grid-column: span 2
        }

        .col-12 {
            grid-column: span 12
        }

        @media(max-width:980px) {

            .col-3,
            .col-4,
            .col-2 {
                grid-column: span 12
            }
        }

        label {
            font-weight: 700;
            font-size: .9rem
        }

        .btn {
            display: inline-flex;
            gap: 8px;
            align-items: center;
            border: 1px solid rgba(255, 255, 255, .18);
            padding: 10px 14px;
            border-radius: 10px;
            color: var(--text);
            background: rgba(255, 255, 255, .10);
            text-decoration: none;
            font-weight: 800;
            transition: .25s;
        }

        .btn:hover {
            transform: translateY(-2px);
            background: rgba(255, 255, 255, .16)
        }

        .btn.primary {
            background: linear-gradient(180deg, var(--brand1), var(--brand2));
            border-color: transparent;
            color: #fff
        }

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 8px
        }

        .tr {
            background: rgba(255, 255, 255, .05);
            border: 1px solid rgba(255, 255, 255, .12);
            border-radius: 10px
        }

        .th,
        .td {
            padding: 12px 14px;
            vertical-align: top;
            text-align: left
        }

        .hint {
            color: var(--muted)
        }

        .badge {
            background: rgba(255, 255, 255, .1);
            border: 1px solid rgba(255, 255, 255, .2);
            padding: .25rem .55rem;
            border-radius: 999px;
            font-size: .75rem
        }

        /* ================= CRISTALINO FORZADO ================== */

        /* base */
        .glass input,
        .glass select,
        .filters input,
        .filters select,
        .form-control,
        .form-select,
        input.form-control,
        select.form-select {
            color-scheme: dark;
            -webkit-appearance: none;
            appearance: none;
            background-color: var(--input-bg) !important;
            border: 1px solid var(--input-border) !important;
            color: var(--text) !important;
            -webkit-text-fill-color: var(--text) !important;
            border-radius: 10px !important;
            padding: 10px 12px !important;
            box-shadow: none !important;
            outline: none !important;
            background-clip: padding-box !important;
        }

        /* focus */
        .glass input:focus,
        .glass select:focus,
        .form-control:focus,
        .form-select:focus {
            background-color: var(--input-bg-focus) !important;
            border-color: #60a5fa !important;
            box-shadow: 0 0 14px rgba(59, 130, 246, .35) !important;
        }

        /* opciones del select (dropdown oscuro) */
        select option {
            background: #0f1a33;
            color: #eaf2ff
        }

        /* Autofill Chrome/Edge */
        .glass input:-webkit-autofill,
        .glass input:-webkit-autofill:hover,
        .glass input:-webkit-autofill:focus,
        .form-control:-webkit-autofill,
        .form-control:-webkit-autofill:hover,
        .form-control:-webkit-autofill:focus {
            -webkit-text-fill-color: #eaf2ff !important;
            -webkit-box-shadow: 0 0 0 1000px var(--input-bg) inset !important;
            transition: background-color 9999s ease-out 0s !important;
        }

        /* Date picker en modo oscuro */
        input[type="date"],
        input[type="date"].form-control {
            background-color: var(--input-bg) !important;
        }

        input[type="date"]::-webkit-datetime-edit,
        input[type="date"].form-control::-webkit-datetime-edit {
            color: #eaf2ff
        }

        input[type="date"]::-webkit-datetime-edit-fields-wrapper,
        input[type="date"].form-control::-webkit-datetime-edit-fields-wrapper {
            background: transparent
        }

        input[type="date"]::-webkit-datetime-edit-text,
        input[type="date"]::-webkit-datetime-edit-month-field,
        input[type="date"]::-webkit-datetime-edit-day-field,
        input[type="date"]::-webkit-datetime-edit-year-field,
        input[type="date"].form-control::-webkit-datetime-edit-text,
        input[type="date"].form-control::-webkit-datetime-edit-month-field,
        input[type="date"].form-control::-webkit-datetime-edit-day-field,
        input[type="date"].form-control::-webkit-datetime-edit-year-field {
            color: #eaf2ff
        }

        input[type="date"]::-webkit-calendar-picker-indicator,
        input[type="date"].form-control::-webkit-calendar-picker-indicator {
            filter: invert(1) opacity(.85);
            cursor: pointer;
        }

        /* deshabilitados */
        .glass input[readonly],
        .glass input:disabled,
        .glass select:disabled,
        .form-control[readonly],
        .form-control:disabled,
        .form-select:disabled {
            background-color: rgba(255, 255, 255, .06) !important;
            color: #cbd5e1 !important;
            opacity: 1 !important;
        }
    </style>

    <div class="bg"></div>
    <div class="wrap">
        <div class="glass head">
            <h1 class="title">Tratamiento Odontológico</h1>
            <a class="btn" href="{{ route('dashboard') }}">← Volver al Panel</a>
        </div>

        {{-- Filtros --}}
        <div class="glass card">
            <form class="grid" method="GET">
                <div class="col-4">
                    <label>Buscar paciente (nombre / apellido / DNI)</label>
                    <input type="text" name="buscar" value="{{ $filters['buscar'] ?? '' }}"
                        placeholder="Ej. Juan, Pérez, 7123…">
                </div>
                <div class="col-3">
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
                        <option value="">(activos)</option>
                        <option value="abierta" @selected(($filters['estado'] ?? '') === 'abierta')>Abierta</option>
                        <option value="en_tratamiento" @selected(($filters['estado'] ?? '') === 'en_tratamiento')>En tratamiento</option>
                        <option value="cerrada" @selected(($filters['estado'] ?? '') === 'cerrada')>Cerrada</option>
                    </select>
                </div>
                <div class="col-3"></div>
                <div class="col-3">
                    <label>Desde</label>
                    <input type="date" name="desde" value="{{ $filters['desde'] ?? '' }}">
                </div>
                <div class="col-3">
                    <label>Hasta</label>
                    <input type="date" name="hasta" value="{{ $filters['hasta'] ?? '' }}">
                </div>
                <div class="col-2" style="display:flex;gap:8px;align-items:flex-end">
                    <button class="btn primary" type="submit">Filtrar</button>
                    <a class="btn" href="{{ route('operativos.tratamiento') }}">Limpiar</a>
                </div>
            </form>
        </div>

        {{-- Tabla --}}
        <div class="glass card" style="margin-top:12px">
            @if ($historias->count() === 0)
                <div class="hint">No hay tratamientos con los filtros aplicados.</div>
            @else
                <table class="table">
                    <thead>
                        <tr class="tr">
                            <th class="th">Paciente</th>
                            <th class="th">Odontólogo</th>
                            <th class="th">Apertura</th>
                            <th class="th">Diagnóstico</th>
                            <th class="th">Plan</th>
                            <th class="th">Última nota</th>
                            <th class="th">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($historias as $h)
                            @php $nota = $h->notas->first(); @endphp
                            <tr class="tr">
                                <td class="td">
                                    <div style="font-weight:800">{{ $h->paciente?->apellido }} {{ $h->paciente?->nombre }}
                                    </div>
                                    <div class="hint">DNI: {{ $h->paciente?->dni ?? '—' }}</div>
                                </td>
                                <td class="td">{{ $h->odontologo?->name ?? '—' }}</td>
                                <td class="td">{{ optional($h->fecha_apertura)->format('d/m/Y') ?? '—' }}</td>
                                <td class="td">{{ \Illuminate\Support\Str::limit($h->diagnostico ?? '—', 60) }}</td>
                                <td class="td">{{ \Illuminate\Support\Str::limit($h->plan_tratamiento ?? '—', 60) }}
                                </td>
                                <td class="td">
                                    @if ($nota)
                                        <div><span class="badge">{{ optional($nota->fecha)->format('d/m/Y H:i') }}</span>
                                        </div>
                                        @if ($nota->procedimiento)
                                            <div class="hint">
                                                {{ \Illuminate\Support\Str::limit($nota->procedimiento, 40) }}</div>
                                        @endif
                                    @else
                                        <span class="hint">—</span>
                                    @endif
                                </td>
                                <td class="td" style="display:flex;gap:8px;flex-wrap:wrap">
                                    <a class="btn" href="{{ route('historias.show', $h->paciente) }}">Ver historia</a>
                                    <a class="btn primary" href="{{ route('notas.create', ['historia' => $h->id]) }}">+
                                        Nota</a>
                                    <a class="btn" href="{{ route('citas.create') }}">Programar sesión</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="margin-top:10px">
                    {{ $historias->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
