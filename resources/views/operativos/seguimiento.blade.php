{{-- Proceso operativo de seguimiento: control de citas posteriores. --}}
@extends('layouts.app')

@section('hide_default_nav', true)
@section('title', 'Seguimiento del Paciente')

@section('content')
    <style>
        :root {
            --glass: rgba(17, 24, 39, .55);
            --glass-border: rgba(255, 255, 255, .10);
            --text: #e5e7eb;
            --muted: #cbd5e1;
            --accent: #3b82f6;
            --glow: 0 0 18px rgba(59, 130, 246, .45);
            --r: 16px;
        }

        .dash-bg {
            position: fixed;
            inset: 0;
            z-index: -2;
            background: linear-gradient(180deg, rgba(6, 11, 28, .70), rgba(6, 11, 28, .55)),
                url('/images/bg-dashboard.jpg') center/cover no-repeat;
        }

        .wrap {
            max-width: 1200px;
            margin: 30px auto 100px;
            padding: 0 14px;
        }

        .glass {
            backdrop-filter: blur(10px) saturate(120%);
            background: var(--glass);
            border: 1px solid var(--glass-border);
            border-radius: var(--r);
            box-shadow: 0 12px 30px rgba(0, 0, 0, .35);
        }

        .head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 24px;
            margin-bottom: 16px;
            box-shadow: var(--glow);
        }

        .title {
            color: #f8fafc;
            font-weight: 900;
            font-size: clamp(24px, 3.5vw, 36px);
            text-shadow: var(--glow);
        }

        .crumb {
            color: var(--muted);
            font-size: .9rem;
            margin-bottom: 4px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 10px 16px;
            border-radius: 12px;
            font-weight: 700;
            color: #fff;
            text-decoration: none;
            background: linear-gradient(90deg, #2563eb, #1e3a8a);
            border: 1px solid rgba(255, 255, 255, .15);
            transition: .2s;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--glow);
        }

        .btn.ghost {
            background: rgba(255, 255, 255, .08);
            color: #dbeafe;
        }

        .filters {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding: 18px;
        }

        /* Inputs cristalinos */
        input[type="date"],
        select {
            padding: 10px 14px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, .18);
            background: rgba(255, 255, 255, .12);
            color: #e2e8f0;
            outline: none;
            transition: all .2s ease;
        }

        input[type="date"]:focus,
        select:focus {
            border-color: rgba(59, 130, 246, .6);
            box-shadow: 0 0 6px rgba(59, 130, 246, .4);
            background: rgba(255, 255, 255, .18);
        }

        select option {
            background: #111827;
            color: #f1f5f9;
        }

        .table-box {
            padding: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px 14px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, .1);
        }

        th {
            background: linear-gradient(90deg, #2563eb, #1e3a8a);
            color: white;
            text-transform: uppercase;
            letter-spacing: .6px;
            font-size: 13px;
        }

        tr:hover td {
            background: rgba(59, 130, 246, .1);
        }

        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 9999px;
            font-size: .8rem;
            font-weight: 700;
            border: 1px solid rgba(255, 255, 255, .2);
        }

        .status.ok {
            background: rgba(34, 197, 94, .18);
            color: #bbf7d0;
        }

        .status.warn {
            background: rgba(234, 179, 8, .18);
            color: #fef9c3;
        }

        .empty {
            padding: 14px;
            border-radius: 12px;
            background: rgba(255, 255, 255, .06);
            border: 1px dashed rgba(255, 255, 255, .15);
            color: #d8e7ff;
            text-align: center;
        }
    </style>

    <div class="dash-bg"></div>

    <div class="wrap">
        {{-- HEADER --}}
        <div class="glass head">
            <div>
                <div class="crumb">Operativos / Seguimiento</div>
                <h1 class="title">Seguimiento del Paciente</h1>
            </div>
            <div style="display:flex;align-items:center;gap:10px">
                <a href="{{ route('dashboard') }}" class="btn ghost">← Volver al Panel</a>
                <img src="/images/logo.png" alt="Arte Dental"
                    style="height:58px;filter:drop-shadow(0 0 10px rgba(59,130,246,.6))">
            </div>
        </div>

        {{-- FILTROS --}}
        <div class="glass filters">
            <form method="GET" action="{{ route('operativos.seguimiento') }}"
                style="display:flex;flex-wrap:wrap;gap:10px;width:100%;">
                <input type="date" name="fecha" value="{{ request('fecha') }}">
                <select name="estado">
                    <option value="">Estado (todos)</option>
                    <option value="atendida" @selected(request('estado') === 'atendida')>Atendida</option>
                    <option value="seguimiento" @selected(request('estado') === 'seguimiento')>En seguimiento</option>
                    <option value="programada" @selected(request('estado') === 'programada')>Programada</option>
                    <option value="cancelada" @selected(request('estado') === 'cancelada')>Cancelada</option>
                </select>
                <button class="btn" type="submit">🔎 Filtrar</button>
                <a href="{{ route('operativos.seguimiento') }}" class="btn ghost">Limpiar</a>
            </form>
        </div>

        {{-- TABLA --}}
        <div class="glass table-box">
            @if (($citas ?? collect())->count() === 0)
                <div class="empty">No hay pacientes en seguimiento actualmente.</div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Paciente</th>
                            <th>Odontólogo</th>
                            <th>Motivo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($citas as $c)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($c->fecha)->format('d/m/Y') }} {{ $c->hora_inicio }}</td>
                                <td>{{ $c->paciente->apellido }}, {{ $c->paciente->nombre }}</td>
                                <td>{{ $c->odontologo->name ?? '—' }}</td>
                                <td>{{ $c->motivo ?? '—' }}</td>
                                <td>
                                    @php
                                        $map = [
                                            'atendida' => ['txt' => 'Atendida', 'cls' => 'ok'],
                                            'seguimiento' => ['txt' => 'Seguimiento', 'cls' => 'warn'],
                                        ];
                                        $m = $map[$c->estado] ?? ['txt' => ucfirst($c->estado), 'cls' => ''];
                                    @endphp
                                    <span class="status {{ $m['cls'] }}">{{ $m['txt'] }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('historias.show', $c->paciente) }}" class="btn">🩺 Ver
                                        historia</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
