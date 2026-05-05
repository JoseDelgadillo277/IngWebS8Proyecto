@extends('layouts.app')
@section('hide_default_nav', true)
@section('title', 'Configurar odontólogos')

@section('content')
    <style>
        :root {
            --glass: rgba(17, 24, 39, .55);
            --glass-b: rgba(255, 255, 255, .10);
        }

        html,
        body {
            height: 100%;
            margin: 0
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
            min-height: calc(100vh - 60px);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 30px 14px 80px
        }

        .glass {
            backdrop-filter: blur(10px) saturate(120%);
            background: var(--glass);
            border: 1px solid var(--glass-b);
            border-radius: 16px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, .35)
        }

        .hero {
            width: 100%;
            max-width: 1100px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 24px;
            margin-bottom: 20px
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px
        }

        .brand img {
            height: 60px;
            filter: drop-shadow(0 0 10px rgba(59, 130, 246, .55))
        }

        .brand .name {
            color: #eaf2ff;
            font-weight: 800;
            letter-spacing: .3px
        }

        .note {
            color: #cbd5e1;
            font-size: .92rem;
            margin: 2px 0 0
        }

        .btn {
            display: inline-flex;
            gap: 8px;
            align-items: center;
            padding: 10px 18px;
            border-radius: 12px;
            font-weight: 800;
            border: none;
            cursor: pointer;
            transition: all .25s ease
        }

        .btn-ghost {
            background: rgba(255, 255, 255, .08);
            color: #eaf2ff;
            border: 1px solid rgba(255, 255, 255, .18);
            box-shadow: 0 0 10px rgba(255, 255, 255, .08)
        }

        .btn-ghost:hover {
            background: linear-gradient(180deg, #60a5fa, #3b82f6);
            box-shadow: 0 0 15px rgba(59, 130, 246, .5);
            transform: translateY(-2px);
            color: #fff
        }

        .btn-blue {
            background: linear-gradient(180deg, #60a5fa, #3b82f6);
            color: #fff
        }

        .btn-blue:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 15px rgba(59, 130, 246, .4)
        }

        .card {
            width: 100%;
            max-width: 900px;
            padding: 20px 24px;
            margin-bottom: 14px
        }

        label {
            display: block;
            color: #eaf2ff;
            font-weight: 700;
            margin: 6px 0 8px
        }

        select,
        input {
            width: 100%;
            padding: 12px 14px;
            border-radius: 12px;
            background: rgba(255, 255, 255, .08);
            border: 1px solid rgba(255, 255, 255, .16);
            color: #eaf2ff;
            outline: none
        }

        select:focus,
        input:focus {
            border-color: rgba(59, 130, 246, .45);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .25)
        }

        /* cristal para selects */
        select,
        select option {
            background-color: #0f1a33 !important;
            color: #eaf2ff !important
        }

        select option:checked,
        select option:hover {
            background-color: #1b2a4d !important;
            color: #eaf2ff !important
        }

        .row-2 {
            display: grid;
            grid-template-columns: 1fr 200px;
            gap: 12px
        }

        @media(max-width:700px) {
            .row-2 {
                grid-template-columns: 1fr
            }
        }

        footer {
            position: fixed;
            bottom: 8px;
            left: 0;
            width: 100%;
            text-align: center;
            color: #94a3b8;
            font-size: .9rem
        }

        table th,
        table td {
            color: #eaf2ff
        }
    </style>

    <div class="dash-bg"></div>

    <div class="wrap">

        {{-- HERO --}}
        <div class="glass hero">
            <div class="brand">
                <img src="/images/logo.png" alt="ArteDental">
                <div>
                    <div class="name">ArteDental</div>
                    <div class="note">Módulo de RRHH · Configuración de odontólogos (solo administrador)</div>
                </div>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-ghost">← Volver al panel</a>
        </div>

        {{-- Selector de odontólogo --}}
        <div class="glass card">
            <h2 style="margin:0 0 10px; color:#fff; font-weight:800;">🦷 Configurar odontólogos</h2>
            <form method="GET" action="{{ route('odontologos.config') }}">
                <label>Odontólogo</label>
                <div class="row-2" style="align-items:end">
                    <select name="odontologo_id" required>
                        <option value="">— Selecciona —</option>
                        @foreach ($odontologos as $o)
                            <option value="{{ $o->id }}" {{ (string) $sel === (string) $o->id ? 'selected' : '' }}>
                                {{ $o->name }}
                            </option>
                        @endforeach
                    </select>
                    <button class="btn btn-blue" type="submit">Cargar</button>
                </div>
            </form>
            @if (!$sel)
                <p class="note" style="margin-top:10px">Elige un odontólogo y pulsa <b>Cargar</b>.</p>
            @endif
        </div>

        {{-- Secciones solo si hay odontólogo seleccionado --}}
        @if ($sel)
            {{-- ===== Disponibilidad semanal ===== --}}
            <div class="glass card">
                <h3 style="margin:0 0 12px; color:#fff; font-weight:800;">Disponibilidad semanal</h3>

                <form method="POST" action="{{ route('odontologos.disponibilidad.add') }}" class="row-2"
                    style="align-items:end">
                    @csrf
                    <input type="hidden" name="odontologo_id" value="{{ $sel }}">
                    <div>
                        <label>Día</label>
                        <select name="dia_semana" required>
                            <option value="">— Selecciona —</option>
                            <option value="1">Lunes</option>
                            <option value="2">Martes</option>
                            <option value="3">Miércoles</option>
                            <option value="4">Jueves</option>
                            <option value="5">Viernes</option>
                            <option value="6">Sábado</option>
                            <option value="7">Domingo</option>
                        </select>
                    </div>
                    <div class="row-2" style="grid-template-columns:1fr 1fr; gap:12px">
                        <div><label>Hora inicio</label><input type="time" name="hora_inicio" required></div>
                        <div><label>Hora fin</label><input type="time" name="hora_fin" required></div>
                    </div>
                    <button class="btn btn-blue" type="submit">Agregar bloque</button>
                </form>

                <div style="margin-top:14px">
                    @if ($disponibilidades->isEmpty())
                        <p class="note">Sin bloques aún.</p>
                    @else
                        <div style="overflow:auto">
                            <table style="width:100%; border-collapse:separate; border-spacing:0 8px">
                                <thead>
                                    <tr style="color:#cbd5e1">
                                        <th style="text-align:left; padding:8px">Día</th>
                                        <th style="text-align:left; padding:8px">Inicio</th>
                                        <th style="text-align:left; padding:8px">Fin</th>
                                        <th style="width:1%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $dias=[1=>'Lunes',2=>'Martes',3=>'Miércoles',4=>'Jueves',5=>'Viernes',6=>'Sábado',7=>'Domingo']; @endphp
                                    @foreach ($disponibilidades as $b)
                                        <tr class="glass" style="background:rgba(255,255,255,.06)">
                                            <td style="padding:10px 12px">{{ $dias[$b->dia_semana] ?? $b->dia_semana }}</td>
                                            <td style="padding:10px 12px">
                                                {{ \Illuminate\Support\Str::substr($b->hora_inicio, 0, 5) }}</td>
                                            <td style="padding:10px 12px">
                                                {{ \Illuminate\Support\Str::substr($b->hora_fin, 0, 5) }}</td>
                                            <td style="padding:10px 12px">
                                                <form method="POST"
                                                    action="{{ route('odontologos.disponibilidad.del', $b) }}">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-ghost" type="submit">Eliminar</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ===== Ausencias / Vacaciones ===== --}}
            <div class="glass card">
                <h3 style="margin:0 0 12px; color:#fff; font-weight:800;">Ausencias / Vacaciones</h3>

                <form method="POST" action="{{ route('odontologos.ausencia.add') }}" class="row-2"
                    style="align-items:end">
                    @csrf
                    <input type="hidden" name="odontologo_id" value="{{ $sel }}">
                    <div class="row-2" style="grid-template-columns:1fr 2fr; gap:12px">
                        <div><label>Fecha</label><input type="date" name="fecha" required></div>
                        <div><label>Motivo (opcional)</label><input type="text" name="motivo"
                                placeholder="Vacaciones / cirugía / etc."></div>
                    </div>
                    <button class="btn btn-blue" type="submit">Registrar ausencia</button>
                </form>

                <div style="margin-top:14px">
                    @if ($ausencias->isEmpty())
                        <p class="note">Sin ausencias registradas.</p>
                    @else
                        <div style="overflow:auto">
                            <table style="width:100%; border-collapse:separate; border-spacing:0 8px">
                                <thead>
                                    <tr style="color:#cbd5e1">
                                        <th style="text-align:left; padding:8px">Fecha(s)</th>
                                        <th style="text-align:left; padding:8px">Jornada</th>
                                        <th style="text-align:left; padding:8px">Motivo</th>
                                        <th style="width:1%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($ausencias as $a)
                                        @php
                                            $fi = \Carbon\Carbon::parse($a->fecha_inicio)->format('Y-m-d');
                                            $ff = \Carbon\Carbon::parse($a->fecha_fin)->format('Y-m-d');
                                        @endphp
                                        <tr class="glass" style="background:rgba(255,255,255,.06)">
                                            <td style="padding:10px 12px">
                                                {{ $fi }} @if ($ff !== $fi)
                                                    → {{ $ff }}
                                                @endif
                                            </td>
                                            <td style="padding:10px 12px">Día completo</td>
                                            <td style="padding:10px 12px">{{ $a->motivo ?? '—' }}</td>
                                            <td style="padding:10px 12px">
                                                <form method="POST"
                                                    action="{{ route('odontologos.ausencia.del', $a) }}">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-ghost" type="submit">Eliminar</button>
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
        @endif
    </div>

    <footer>© 2025 Clínica Arte Dental — Todos los derechos reservados.</footer>
@endsection
