@extends('layouts.app')
@section('hide_default_nav', true)
@section('title', 'Configurar odontólogos')

@section('content')
    <style>
        :root {
            --bg1: rgba(6, 11, 28, .86);
            --bg2: rgba(6, 11, 28, .60);
            --glass: rgba(13, 20, 38, .62);
            --glass-2: rgba(17, 24, 39, .55);
            --b: rgba(255, 255, 255, .12);
            --txt: #eaf2ff;
            --mut: #cbd5e1;
            --gold: #fbbf24;
            --warning: #ef4444;
            --ok: #22c55e;
            --glow: 0 0 22px rgba(251, 191, 36, .35);
        }

        /* Fondo y branding */
        .bg {
            position: fixed;
            inset: 0;
            z-index: -2;
            background:
                linear-gradient(180deg, var(--bg1), var(--bg2)),
                url('/images/bg-dashboard.jpg') center/cover no-repeat;
        }

        .brand {
            position: fixed;
            top: 86px;
            left: 22px;
            z-index: 900
        }

        .brand img {
            height: 56px;
            filter: drop-shadow(0 0 14px rgba(251, 191, 36, .55))
        }

        .btn-back {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 12px;
            font-weight: 800;
            color: #fff;
            text-decoration: none;
            background: linear-gradient(180deg, rgba(255, 255, 255, .14), rgba(255, 255, 255, .08));
            border: 1px solid var(--b);
            backdrop-filter: blur(8px)
        }

        .btn-back:hover {
            background: linear-gradient(180deg, #fbbf24, #f59e0b);
            box-shadow: var(--glow);
            transform: translateY(-1px)
        }

        /* Contenedor */
        .wrap {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            padding: 120px 18px 80px
        }

        .panel {
            width: min(1100px, 96vw);
            border: 1px solid var(--b);
            border-radius: 20px;
            background: var(--glass);
            backdrop-filter: blur(12px) saturate(120%);
            box-shadow: 0 18px 44px rgba(0, 0, 0, .36);
            overflow: hidden
        }

        .hero {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 22px;
            border-bottom: 1px solid var(--b);
            background: linear-gradient(180deg, rgba(251, 191, 36, .16), rgba(251, 191, 36, .08))
        }

        .hero h1 {
            margin: 0;
            color: #fff;
            font-weight: 900;
            text-shadow: var(--glow);
            font-size: clamp(20px, 3.2vw, 30px)
        }

        .badge {
            padding: .38rem .65rem;
            border-radius: 9999px;
            border: 1px solid var(--b);
            color: #fff3cf;
            background: rgba(255, 255, 255, .10);
            font-weight: 800
        }

        /* Tarjetas */
        .content {
            padding: 20px 22px;
            display: grid;
            gap: 16px;
            color: var(--txt)
        }

        .card {
            border: 1px solid var(--b);
            border-radius: 16px;
            padding: 16px 18px;
            background: var(--glass-2)
        }

        .card h2 {
            margin: 0 0 10px;
            color: #fff;
            font-weight: 900
        }

        /* Formularios cristalinos */
        label {
            display: block;
            color: var(--mut);
            margin-bottom: 6px;
            font-size: 13px
        }

        .row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 12px
        }

        select,
        input[type="time"],
        input[type="date"],
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border-radius: 12px;
            border: 1px solid var(--b);
            background: rgba(255, 255, 255, .08);
            color: #fff;
            outline: none;
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            appearance: none
        }

        select option {
            background: #0b1224;
            color: #eaf2ff
        }

        input[type="date"]::-webkit-calendar-picker-indicator,
        input[type="time"]::-webkit-calendar-picker-indicator {
            filter: invert(1) opacity(.8)
        }

        select:focus,
        input:focus {
            box-shadow: 0 0 0 3px rgba(251, 191, 36, .18)
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 14px;
            border-radius: 12px;
            border: 1px solid var(--b);
            color: #111;
            font-weight: 800;
            text-decoration: none;
            background: linear-gradient(180deg, #fbbf24, #f59e0b)
        }

        .btn.secondary {
            background: rgba(255, 255, 255, .10);
            color: #fff
        }

        .btn.danger {
            background: #ef4444;
            color: #fff;
            border-color: #ef4444
        }

        .btn:hover {
            filter: brightness(1.05);
            transform: translateY(-1px)
        }

        /* Mensajes */
        .pill {
            display: inline-block;
            border: 1px solid var(--b);
            background: rgba(255, 255, 255, .08);
            padding: 8px 12px;
            border-radius: 9999px;
            color: #fff;
            margin: 2px 0
        }

        .ok {
            border-color: rgba(34, 197, 94, .35);
            background: rgba(34, 197, 94, .18)
        }

        .warn {
            border-color: rgba(239, 68, 68, .35);
            background: rgba(239, 68, 68, .18)
        }

        /* Tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px
        }

        th,
        td {
            padding: 10px;
            border-bottom: 1px solid var(--b);
            text-align: left;
            color: var(--txt)
        }

        th {
            color: #cbd5e1
        }

        td.actions {
            white-space: nowrap
        }

        /* Chips de días */
        .chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px
        }

        .chip {
            padding: 6px 10px;
            border-radius: 999px;
            border: 1px solid var(--b);
            background: rgba(255, 255, 255, .08);
            color: #fff;
            font-size: 12px
        }

        footer {
            position: fixed;
            bottom: 10px;
            width: 100%;
            text-align: center;
            color: #94a3b8
        }
    </style>

    <div class="bg"></div>
    <a href="{{ route('dashboard') }}" class="btn-back">← Volver al panel</a>
    <div class="brand"><img src="/images/logo.png" alt="ArteDental"></div>

    <div class="wrap">
        <div class="panel">
            <div class="hero">
                <span class="badge">🦷 RRHH</span>
                <h1>Configurar odontólogos</h1>
            </div>

            <div class="content">
                {{-- Mensajes --}}
                @if (session('ok'))
                    <div class="pill ok">✔ {{ session('ok') }}</div>
                @endif
                @if ($errors->any())
                    <div class="pill warn">⚠ @foreach ($errors->all() as $e)
                            {{ $e }}
                        @endforeach
                    </div>
                @endif

                {{-- Selector --}}
                <div class="card">
                    <form method="GET" class="row">
                        <div>
                            <label>Profesional</label>
                            <select name="odontologo_id" onchange="this.form.submit()">
                                <option value="">— Seleccionar —</option>
                                @foreach ($odontologos as $o)
                                    <option value="{{ $o->id }}" @selected($sel == $o->id)>
                                        {{ $o->name }}
                                        @if (method_exists($o, 'hasRole'))
                                            @if ($o->hasRole('odontologo'))
                                                (Odontólogo)
                                            @elseif($o->hasRole('asistente'))
                                                (Asistente)
                                            @endif
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div style="align-self:end">
                            <a class="btn secondary" href="{{ route('dashboard') }}">← Volver al panel</a>
                        </div>
                    </form>
                </div>

                @if ($sel)
                    {{-- ======================= --}}
                    {{--   Disponibilidad semanal --}}
                    {{-- ======================= --}}
                    <div class="card">
                        <h2>Disponibilidad semanal</h2>

                        @php
                            $dias = [
                                1 => 'Lunes',
                                2 => 'Martes',
                                3 => 'Miércoles',
                                4 => 'Jueves',
                                5 => 'Viernes',
                                6 => 'Sábado',
                                7 => 'Domingo',
                            ];
                            $ocupados = $disponibilidades->pluck('dia_semana')->unique()->sort()->values()->all();
                        @endphp

                        {{-- Chips y contador --}}
                        <div class="chips">
                            @forelse($ocupados as $d)
                                <span class="chip">{{ $dias[$d] }}</span>
                            @empty
                                <span style="color:#94a3b8">Aún no hay días registrados.</span>
                            @endforelse
                        </div>
                        <p style="color:#cbd5e1;margin:8px 0 0">Días activos: <b>{{ count($ocupados) }}</b> / 7</p>

                        {{-- Formulario agregar bloque --}}
                        <form method="POST" action="{{ route('odontologos.disponibilidad.add') }}" class="row"
                            style="margin-top:12px">
                            @csrf
                            <input type="hidden" name="odontologo_id" value="{{ $sel }}">
                            <div>
                                <label>Día</label>
                                <select name="dia_semana" required>
                                    <option value="">—</option>
                                    @foreach ($dias as $k => $v)
                                        <option value="{{ $k }}" @disabled(in_array($k, $ocupados))>
                                            {{ $v }} @if (in_array($k, $ocupados))
                                                (ya agregado)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label>Inicio</label>
                                <input type="time" name="hora_inicio" required>
                            </div>
                            <div>
                                <label>Fin</label>
                                <input type="time" name="hora_fin" required>
                            </div>
                            <div style="align-self:end">
                                <button class="btn" type="submit">Agregar bloque</button>
                            </div>
                        </form>

                        {{-- Tabla --}}
                        <table>
                            <thead>
                                <tr>
                                    <th>Día</th>
                                    <th>Inicio</th>
                                    <th>Fin</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($disponibilidades as $d)
                                    <tr>
                                        <td>{{ $dias[$d->dia_semana] ?? $d->dia_semana }}</td>
                                        <td>{{ \Carbon\Carbon::parse($d->hora_inicio)->format('H:i') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($d->hora_fin)->format('H:i') }}</td>
                                        <td class="actions">
                                            <form method="POST" action="{{ route('odontologos.disponibilidad.del', $d) }}"
                                                onsubmit="return confirm('¿Eliminar bloque?')">
                                                @csrf @method('DELETE')
                                                <button class="btn danger" type="submit">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" style="color:#94a3b8">Sin bloques aún.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Ausencias / Vacaciones --}}
                    <div class="card">
                        <h2>Ausencias / Vacaciones</h2>
                        <form method="POST" action="{{ route('odontologos.ausencia.add') }}" class="row">
                            @csrf
                            <input type="hidden" name="odontologo_id" value="{{ $sel }}">
                            <div>
                                <label>Fecha</label>
                                <input type="date" name="fecha" required>
                            </div>
                            <div>
                                <label>Hora inicio (opcional)</label>
                                <input type="time" name="hora_inicio">
                            </div>
                            <div>
                                <label>Hora fin (opcional)</label>
                                <input type="time" name="hora_fin">
                            </div>
                            <div>
                                <label>Motivo</label>
                                <input type="text" name="motivo" placeholder="Vacaciones / cirugía / etc.">
                            </div>
                            <div style="align-self:end">
                                <button class="btn" type="submit">Registrar ausencia</button>
                            </div>
                        </form>

                        <table>
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Motivo</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ausencias as $a)
                                    <tr>
                                        <td>{{ $a->fecha }}</td>
                                        <td>
                                            @if ($a->hora_inicio && $a->hora_fin)
                                                {{ \Carbon\Carbon::parse($a->hora_inicio)->format('H:i') }}–{{ \Carbon\Carbon::parse($a->hora_fin)->format('H:i') }}
                                            @else
                                                Día completo
                                            @endif
                                        </td>
                                        <td>{{ $a->motivo }}</td>
                                        <td class="actions">
                                            <form method="POST" action="{{ route('odontologos.ausencia.del', $a) }}"
                                                onsubmit="return confirm('¿Eliminar ausencia?')">
                                                @csrf @method('DELETE')
                                                <button class="btn danger" type="submit">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" style="color:#94a3b8">Sin ausencias registradas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <footer>© 2025 Clínica Arte Dental — Todos los derechos reservados.</footer>
@endsection
