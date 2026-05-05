{{-- Vista principal: muestra las citas del dia y accesos del sistema. --}}
@extends('layouts.app')

@section('hide_default_nav', true)
@section('title', 'Panel principal')

@section('content')
    <style>
        :root {
            --glass: rgba(17, 24, 39, .55);
            --glass-border: rgba(255, 255, 255, .10);
            --text: #e5eefc;
            --muted: #c7d2fe;
            --glow: 0 0 18px rgba(59, 130, 246, .45);
            --glow-soft: 0 0 10px rgba(56, 189, 248, .35);
        }

        .dash-bg {
            position: fixed;
            inset: 0;
            z-index: -2;
            background:
                linear-gradient(180deg, rgba(6, 11, 28, .70), rgba(6, 11, 28, .55)),
                url('/images/bg-dashboard.jpg') center/cover no-repeat
        }

        .viewport {
            display: flex;
            min-height: 100vh;
            gap: 18px
        }

        .content-col {
            display: flex;
            flex: 1
        }

        .inner {
            width: 100%;
            max-width: 1200px;
            margin: auto;
            padding: 0 12px 48px;
            display: flex;
            flex-direction: column;
            gap: 8px
        }

        .sidebar {
            position: sticky;
            top: 10px;
            height: calc(100vh - 20px);
            width: 64px;
            transition: width .2s ease;
            background: rgba(8, 12, 22, .85);
            border: 1px solid var(--glass-border);
            border-radius: 14px;
            box-shadow: 0 10px 26px rgba(0, 0, 0, .35);
            overflow: auto
        }

        .sidebar.open {
            width: 240px
        }

        .burger {
            width: 40px;
            height: 40px;
            display: grid;
            place-items: center;
            border-radius: 10px;
            cursor: pointer;
            background: rgba(255, 255, 255, .06);
            color: var(--text);
            border: 1px solid var(--glass-border);
            transition: .2s
        }

        .burger:hover {
            background: rgba(59, 130, 246, .25);
            box-shadow: var(--glow-soft);
            transform: scale(1.05)
        }

        .sb-accordions {
            padding: 10px 8px
        }

        details.sb-acc {
            margin: 6px 0;
            border: 1px solid transparent;
            border-radius: 12px;
            transition: .25s
        }

        details.sb-acc:hover {
            background: rgba(59, 130, 246, .1);
            box-shadow: var(--glow-soft);
            border-color: rgba(59, 130, 246, .25)
        }

        details.sb-acc[open] {
            background: rgba(59, 130, 246, .12);
            box-shadow: var(--glow);
            border-color: rgba(59, 130, 246, .35)
        }

        .sb-summary {
            list-style: none;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            cursor: pointer;
            color: var(--text);
            font-weight: 600
        }

        .sb-summary i {
            font-size: 18px;
            width: 22px;
            text-align: center;
            opacity: .95
        }

        .sb-sub {
            padding: 6px 8px 10px 14px
        }

        .sb-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 11px;
            border-radius: 10px;
            color: var(--text);
            text-decoration: none;
            transition: .2s
        }

        .sb-link:hover {
            background: rgba(59, 130, 246, .18);
            box-shadow: var(--glow-soft);
            border-color: rgba(59, 130, 246, .3);
            transform: translateX(4px)
        }

        .sidebar:not(.open) .sb-summary span,
        .sidebar:not(.open) .sb-link span {
            display: none
        }

        .glass {
            backdrop-filter: blur(10px) saturate(120%);
            background: var(--glass);
            border: 1px solid var(--glass-border);
            border-radius: 18px;
            box-shadow: 0 14px 34px rgba(0, 0, 0, .35)
        }

        .hero {
            padding: 18px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--glow-soft)
        }

        .hero img {
            height: 70px;
            width: auto;
            filter: drop-shadow(0 0 12px rgba(59, 130, 246, .6))
        }

        .hero-center {
            text-align: center;
            flex: 1
        }

        .panel-title {
            font-weight: 900;
            color: #f8fafc;
            font-size: clamp(28px, 3.6vw, 40px);
            text-shadow: var(--glow)
        }

        .panel-date {
            color: var(--muted);
            font-weight: 700;
            margin-top: 4px
        }

        .hero-actions {
            display: flex;
            gap: 10px
        }

        .hero-btn {
            padding: .55rem 1rem;
            border-radius: 10px;
            background: rgba(255, 255, 255, .10);
            color: #f1f5f9;
            border: 1px solid var(--glass-border);
            font-weight: 600;
            transition: .2s
        }

        .hero-btn:hover {
            background: #60a5fa;
            color: white;
            box-shadow: var(--glow);
            transform: translateY(-2px)
        }

        .section {
            padding: 18px 22px;
            border-radius: 18px;
            margin-top: 10px;
            box-shadow: var(--glow-soft)
        }

        .section-title {
            color: #e2e8f0;
            font-weight: 800;
            margin-bottom: 14px;
            text-transform: uppercase
        }

        .qa-row {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px
        }

        @media (max-width:1060px) {
            .qa-row {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }
        }

        @media (max-width:560px) {
            .qa-row {
                grid-template-columns: 1fr
            }
        }

        .qa-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .6rem;
            padding: 12px 16px;
            border-radius: 16px;
            font-weight: 700;
            color: #e9f1ff;
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, .16);
            background:
                radial-gradient(150% 150% at 0% 0%, rgba(99, 102, 241, .22), transparent 60%),
                radial-gradient(150% 150% at 100% 0%, rgba(56, 189, 248, .18), transparent 60%),
                rgba(255, 255, 255, .10);
            transition: .22s
        }

        .qa-btn:hover {
            transform: translateY(-4px);
            background:
                radial-gradient(150% 150% at 0% 0%, rgba(99, 102, 241, .35), transparent 60%),
                radial-gradient(150% 150% at 100% 0%, rgba(56, 189, 248, .3), transparent 60%),
                rgba(255, 255, 255, .16);
            box-shadow: var(--glow)
        }

        .table {
            width: 100%;
            border-collapse: collapse
        }

        th,
        td {
            padding: 10px 12px;
            border-bottom: 1px solid rgba(255, 255, 255, .12);
            text-align: left
        }

        thead th {
            background: linear-gradient(90deg, #2563eb, #1d4ed8);
            color: #fff;
            font-size: 12px;
            letter-spacing: .8px;
            text-transform: uppercase
        }

        .badge {
            display: inline-block;
            padding: .25rem .6rem;
            border-radius: 9999px;
            font-size: .75rem;
            font-weight: 700;
            background: rgba(255, 255, 255, .10);
            border: 1px solid rgba(255, 255, 255, .2)
        }

        .badge.ok {
            background: rgba(34, 197, 94, .18);
            border-color: rgba(34, 197, 94, .45);
            color: #dbffe6
        }

        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap
        }

        .btn {
            display: inline-flex;
            gap: 8px;
            align-items: center;
            border: 1px solid rgba(255, 255, 255, .18);
            padding: 8px 12px;
            border-radius: 10px;
            color: #eaf2ff;
            background: rgba(255, 255, 255, .10);
            text-decoration: none;
            font-weight: 800
        }

        .btn.primary {
            background: linear-gradient(180deg, #2563eb, #1d4ed8);
            border-color: transparent
        }
    </style>

    <div class="dash-bg"></div>

    <div class="viewport">
        {{-- ===== SIDEBAR ===== --}}
        <aside id="sidebar" class="sidebar">
            <div class="sb-head" style="display:flex;justify-content:center;padding:10px;">
                <button class="burger" id="burgerBtn" title="Menú">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M3 6h18M3 12h18M3 18h18" stroke="#e7eefc" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </button>
            </div>

            <div class="sb-accordions">
                <details class="sb-acc">
                    <summary class="sb-summary"><i>📊</i><span>Estratégicos</span></summary>
                    <div class="sb-sub">
                        <a class="sb-link" href="{{ route('estrategicos.planeamiento') }}"><i>📈</i><span>Planeamiento
                                estratégico</span></a>
                        <a class="sb-link" href="{{ route('estrategicos.calidad') }}"><i>✅</i><span>Gestión de
                                calidad</span></a>
                        <a class="sb-link" href="{{ route('estrategicos.innovacion') }}"><i>🧬</i><span>Innovación y
                                mejora</span></a>
                    </div>
                </details>

                <details class="sb-acc" open>
                    <summary class="sb-summary"><i>⚙️</i><span>Operativos</span></summary>
                    <div class="sb-sub">
                        <a class="sb-link" href="{{ route('pacientes.index') }}"><i>🧾</i><span>Recepción y
                                registro</span></a>
                        <a class="sb-link" href="{{ route('citas.index') }}"><i>📅</i><span>Gestión de citas</span></a>
                        <a class="sb-link" href="{{ route('operativos.diagnostico') }}"><i>🫀</i><span>Diagnóstico y
                                plan</span></a>
                        <a class="sb-link" href="{{ route('operativos.tratamiento') }}"><i>🦷</i><span>Tratamiento
                                odontológico</span></a>
                        <a class="sb-link" href="{{ route('operativos.seguimiento') }}"><i>🔎</i><span>Seguimiento del
                                paciente</span></a>
                    </div>
                </details>

                <details class="sb-acc">
                    <summary class="sb-summary"><i>🧩</i><span>Soporte</span></summary>
                    <div class="sb-sub">
                        <a class="sb-link" href="{{ route('rrhh.index') }}"><i>👥</i><span>Gestión de RRHH</span></a>
                        <a class="sb-link" href="{{ route('soporte.finanzas.index') }}"><i>💰</i><span>Finanzas</span></a>
                        <a class="sb-link"
                            href="{{ route('soporte.administracion.index') }}"><i>🏛️</i><span>Administración</span></a>
                    </div>
                </details>
            </div>
        </aside>

        {{-- ===== CONTENIDO ===== --}}
        <div class="content-col">
            <div class="inner">
                <div class="glass hero">
                    <img src="/images/logo.png" alt="ArteDental">
                    <div class="hero-center">
                        <div class="panel-title">Panel Principal</div>
                        <div class="panel-date">{{ \Illuminate\Support\Carbon::parse($hoy)->format('Y-m-d') }}</div>
                    </div>
                    <div class="hero-actions">
                        <a href="{{ route('profile.edit') }}" class="hero-btn">Mi perfil</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="hero-btn" type="submit">Salir</button>
                        </form>
                    </div>
                </div>

                {{-- Acciones rápidas --}}
                <section class="glass section">
                    <div class="section-title">ACCIONES RÁPIDAS</div>
                    <div class="qa-row">
                        <a href="{{ route('pacientes.create') }}" class="qa-btn">➕ Nuevo paciente</a>
                        <a href="{{ route('citas.create') }}" class="qa-btn">🗓️ Programar cita</a>
                        <a href="{{ route('pacientes.index') }}" class="qa-btn">📄 Historia clínica</a>
                        <a href="{{ route('soporte.finanzas.pagos.create') }}" class="qa-btn">💳 Registrar pago</a>
                    </div>
                </section>

                {{-- Citas de hoy --}}
                <section class="glass section">
                    <div class="section-title">CITAS DE HOY</div>

                    @if ($citasHoy->isEmpty())
                        <div class="glass"
                            style="padding:14px;border-radius:12px;background:rgba(255,255,255,.06);color:#d8e7ff">
                            Sin citas registradas para hoy.
                        </div>
                    @else
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Hora</th>
                                    <th>Paciente</th>
                                    <th>Odontólogo</th>
                                    <th>Estado</th>
                                    <th style="width:260px">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($citasHoy as $c)
                                    @php
                                        $map = [
                                            'programada' => ['txt' => 'Programada', 'cls' => 'ok'],
                                            'checkin' => ['txt' => 'Check-in', 'cls' => 'ok'],
                                            'atendida' => ['txt' => 'Atendida', 'cls' => 'ok'],
                                            'cancelada' => ['txt' => 'Cancelada', 'cls' => ''],
                                        ];
                                        $m = $map[$c->estado] ?? ['txt' => $c->estado, 'cls' => ''];
                                    @endphp
                                    <tr>
                                        <td>{{ $c->hora_inicio }} – {{ $c->hora_fin }}</td>
                                        <td>{{ $c->paciente?->apellido }}, {{ $c->paciente?->nombre }}</td>
                                        <td>{{ $c->odontologo?->name }}</td>
                                        <td><span class="badge {{ $m['cls'] }}">{{ $m['txt'] }}</span></td>
                                        <td class="actions">
                                            @if ($c->estado === 'programada')
                                                <form action="{{ route('citas.checkin', $c) }}" method="POST"
                                                    onsubmit="return confirm('¿Registrar check-in?')">
                                                    @csrf @method('PATCH')
                                                    <button class="btn">✅ Check-in</button>
                                                </form>
                                            @endif
                                            <a class="btn primary" href="{{ route('citas.atender', $c) }}">🩺 Atender</a>
                                            <a class="btn" href="{{ route('citas.edit', $c) }}">✏️ Editar</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </section>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('burgerBtn').addEventListener('click', () => {
            document.getElementById('sidebar').classList.toggle('open');
        });
    </script>
@endsection
