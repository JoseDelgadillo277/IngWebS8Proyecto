{{-- Listado de citas: filtros, estados y acciones rapidas de atencion. --}}
@extends('layouts.app')

@section('hide_default_nav', true)
@section('title', 'Citas')

@section('content')
    <style>
        :root {
            --glass: rgba(17, 24, 39, .55);
            --glass-border: rgba(255, 255, 255, .10);
            --txt: #e5e7eb;
            --mut: #9aa4bf;
            --brand1: #2563eb;
            --brand2: #1d4ed8;
            --ok: #22c55e;
            --warn: #eab308;
            --danger: #ef4444;
            --cyan: #06b6d4;
            --shadow: 0 12px 30px rgba(0, 0, 0, .35);
            --glow: 0 0 18px rgba(59, 130, 246, .35);
            --glow-soft: 0 0 10px rgba(56, 189, 248, .28);
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
            max-width: 1200px;
            margin: 26px auto 110px;
            padding: 0 14px;
        }

        .glass {
            backdrop-filter: blur(10px) saturate(120%);
            background: var(--glass);
            border: 1px solid var(--glass-border);
            border-radius: var(--r);
            box-shadow: var(--shadow);
        }

        .head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 18px 22px;
            margin-bottom: 14px;
            box-shadow: var(--glow-soft);
        }

        .title {
            margin: 0;
            color: #f8fafc;
            font-weight: 900;
            font-size: clamp(22px, 3.2vw, 32px);
            text-shadow: var(--glow);
        }

        .crumb {
            color: #cbd5e1;
            font-size: .9rem
        }

        .logo {
            height: 48px;
            width: auto;
            filter: drop-shadow(0 0 10px rgba(59, 130, 246, .6))
        }

        .toolbar {
            display: flex;
            align-items: end;
            gap: 10px;
            justify-content: space-between;
            padding: 16px 18px;
            flex-wrap: wrap
        }

        label {
            display: block;
            margin: 0 0 6px;
            color: #e5e7eb;
            font-weight: 700;
            font-size: .9rem
        }

        input,
        select {
            width: 100%;
            padding: 10px 12px;
            border-radius: 12px;
            color: #eaf2ff !important;
            background-color: rgba(255, 255, 255, .08) !important;
            border: 1px solid rgba(255, 255, 255, .16) !important;
            -webkit-text-fill-color: #eaf2ff !important;
            outline: none;
            transition: .18s;
        }

        input:focus,
        select:focus {
            background-color: rgba(255, 255, 255, .12) !important;
            border-color: rgba(59, 130, 246, .5) !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .25) !important;
        }

        .f-row {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 10px;
            width: 100%
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

        .col-6 {
            grid-column: span 6
        }

        .col-auto {
            grid-column: auto
        }

        @media(max-width:900px) {

            .col-3,
            .col-2,
            .col-4,
            .col-6 {
                grid-column: span 12
            }
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            border: 1px solid transparent;
            padding: 10px 14px;
            border-radius: 12px;
            font-weight: 800;
            text-decoration: none;
            color: #eaf3ff;
            transition: .2s;
            background: radial-gradient(120% 120% at 0% 0%, rgba(99, 102, 241, .22), transparent 60%),
                radial-gradient(120% 120% at 100% 0%, rgba(56, 189, 248, .18), transparent 60%), rgba(255, 255, 255, .10);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--glow)
        }

        .btn.primary {
            background: linear-gradient(180deg, var(--brand1), var(--brand2))
        }

        .btn.warn {
            background: linear-gradient(180deg, #fde047, #f59e0b);
            color: #1f2937
        }

        .btn.ok {
            background: linear-gradient(180deg, #34d399, #10b981)
        }

        .btn.info {
            background: linear-gradient(180deg, #0ea5e9, #38bdf8)
        }

        .btn.danger {
            background: linear-gradient(180deg, var(--danger), #dc2626)
        }

        .table-wrap {
            padding: 12px
        }

        table {
            width: 100%;
            border-collapse: collapse
        }

        th,
        td {
            padding: 12px 14px;
            border-bottom: 1px solid rgba(255, 255, 255, .12);
            text-align: left;
            vertical-align: middle
        }

        thead th {
            background: linear-gradient(90deg, var(--brand1), #1d4ed8);
            color: #fff;
            position: sticky;
            top: 0;
            z-index: 1;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: .8px
        }

        tbody tr:hover td {
            background: rgba(59, 130, 246, .08)
        }

        .badge {
            display: inline-block;
            padding: .25rem .6rem;
            border-radius: 9999px;
            font-size: .75rem;
            font-weight: 800
        }

        .b-ok {
            background: rgba(34, 197, 94, .16);
            border: 1px solid rgba(34, 197, 94, .5);
            color: #d8ffea
        }

        .b-warn {
            background: rgba(250, 204, 21, .16);
            border: 1px solid rgba(250, 204, 21, .5);
            color: #fff2cc
        }

        .b-danger {
            background: rgba(239, 68, 68, .18);
            border: 1px solid rgba(239, 68, 68, .5);
            color: #ffe2e2
        }

        .b-info {
            background: rgba(56, 189, 248, .15);
            border: 1px solid rgba(56, 189, 248, .45);
            color: #d5f2ff
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px
        }

        .alert {
            position: fixed;
            top: 26px;
            right: 26px;
            background: linear-gradient(90deg, var(--ok), #16a34a);
            color: #fff;
            font-weight: 800;
            padding: 12px 16px;
            border-radius: 12px;
            box-shadow: 0 12px 30px rgba(16, 185, 129, .35);
            z-index: 50;
            animation: slide .6s ease
        }

        @keyframes slide {
            from {
                opacity: 0;
                transform: translateX(120%)
            }

            to {
                opacity: 1;
                transform: translateX(0)
            }
        }

        footer {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 12px;
            text-align: center;
            font-size: .85rem;
            color: #cbd5e1;
            opacity: .85;
            pointer-events: none
        }

        /* ====== FIX cristalino para SELECTs ====== */
        .toolbar select,
        .table-wrap select,
        select {
            color-scheme: dark;
            background-color: rgba(255, 255, 255, .08) !important;
            color: #eaf2ff !important;
            border: 1px solid rgba(255, 255, 255, .16) !important;
            -webkit-text-fill-color: #eaf2ff !important;
            outline: none;
            -webkit-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%23cbd5e1' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><polyline points='6 9 12 15 18 9'/></svg>");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 40px;
            border-radius: 12px;
        }

        select:focus {
            background-color: rgba(255, 255, 255, .12) !important;
            border-color: rgba(59, 130, 246, .50) !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .25) !important;
        }

        select option {
            background: #0f1a33 !important;
            color: #eaf2ff !important;
        }

        select::-ms-expand {
            display: none;
        }
    </style>

    <div class="dash-bg"></div>

    @if (session('ok'))
        <div class="alert">✅ {{ session('ok') }}</div>
    @endif

    <div class="wrap">
        {{-- HEADER --}}
        <div class="glass head">
            <div>
                <div class="crumb">Operativos / Gestión de citas</div>
                <h1 class="title">Listado de Citas</h1>
            </div>
            <div style="display:flex;align-items:center;gap:10px">
                <a class="btn" href="{{ route('dashboard') }}">← Volver al Panel</a>
                <img class="logo" src="/images/logo.png" alt="ArteDental">
            </div>
        </div>

        {{-- FILTROS --}}
        <div class="glass toolbar">
            <form method="GET" class="f-row" style="flex:1">
                <div class="col-3">
                    <label for="fecha">Fecha</label>
                    <input id="fecha" type="date" name="fecha" value="{{ request('fecha') }}">
                </div>
                <div class="col-4">
                    <label for="odontologo_id">Odontólogo</label>
                    <select id="odontologo_id" name="odontologo_id">
                        <option value="">(todos)</option>
                        @foreach ($odontologos as $doc)
                            <option value="{{ $doc->id }}" @selected(request('odontologo_id') == $doc->id)>
                                {{ $doc->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3">
                    <label for="estado">Estado</label>
                    <select id="estado" name="estado">
                        <option value="">(todos)</option>
                        <option value="programada" @selected(request('estado') === 'programada')>Programada</option>
                        <option value="atendida" @selected(request('estado') === 'atendida')>Atendida</option>
                        <option value="cancelada" @selected(request('estado') === 'cancelada')>Cancelada</option>
                    </select>
                </div>
                <div class="col-2" style="display:flex;gap:8px;align-items:flex-end">
                    <button class="btn info" type="submit">Filtrar</button>
                    <a class="btn" href="{{ route('citas.index') }}">Limpiar</a>
                </div>
            </form>

            <a href="{{ route('citas.create') }}" class="btn primary">➕ Programar cita</a>
        </div>

        {{-- TABLA --}}
        <div class="glass table-wrap">
            @if ($citas->count())
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Paciente</th>
                            <th>Odontólogo</th>
                            <th>Fecha</th>
                            <th>Hora inicio</th>
                            <th>Hora fin</th>
                            <th>Estado</th>
                            <th style="width:360px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($citas as $c)
                            @php
                                $badgeClass =
                                    $c->estado === 'atendida'
                                        ? 'b-ok'
                                        : ($c->estado === 'cancelada'
                                            ? 'b-danger'
                                            : 'b-info');
                            @endphp
                            <tr>
                                <td>{{ $c->id }}</td>
                                <td>{{ $c->paciente?->apellido }}, {{ $c->paciente?->nombre }}</td>
                                <td>{{ $c->odontologo?->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($c->fecha)->format('d/m/Y') }}</td>
                                <td>{{ $c->hora_inicio }}</td>
                                <td>{{ $c->hora_fin }}</td>
                                <td><span class="badge {{ $badgeClass }}">{{ ucfirst($c->estado) }}</span></td>
                                <td>
                                    <div class="actions">
                                        @if ($c->estado === 'programada')
                                            <form action="{{ route('citas.checkin', $c) }}" method="POST"
                                                onsubmit="return confirm('¿Registrar check-in?')">
                                                @csrf @method('PATCH')
                                                <button class="btn ok" type="submit">✔ Check-in</button>
                                            </form>
                                        @endif
                                        <a class="btn info" href="{{ route('citas.atender', $c) }}">🩺 Atender</a>
                                        <a class="btn warn" href="{{ route('citas.edit', $c) }}">✏️ Editar</a>
                                        @if ($c->estado !== 'cancelada')
                                            <form action="{{ route('citas.cancel', $c) }}" method="POST"
                                                onsubmit="return confirm('¿Cancelar esta cita?')">
                                                @csrf @method('PATCH')
                                                <button class="btn danger" type="submit">⛔ Cancelar</button>
                                            </form>
                                        @endif
                                        <form action="{{ route('citas.destroy', $c) }}" method="POST"
                                            onsubmit="return confirm('¿Eliminar esta cita definitivamente?')">
                                            @csrf @method('DELETE')
                                            <button class="btn danger" type="submit">🗑️ Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="margin-top:14px">
                    {{ $citas->links() }}
                </div>
            @else
                <div style="padding:12px;color:#cbd5e1">No hay citas registradas.</div>
            @endif
        </div>
    </div>

    <footer>© 2025 Clínica Arte Dental — Todos los derechos reservados.</footer>
@endsection
