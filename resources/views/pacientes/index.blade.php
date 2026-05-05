@extends('layouts.app')

@section('hide_default_nav', true)
@section('title', 'Registro de pacientes')

@section('content')
    <style>
        :root {
            --glass: rgba(17, 24, 39, .55);
            --glass-border: rgba(255, 255, 255, .10);
            --txt: #e5e7eb;
            --brand1: #2563eb;
            --brand2: #1d4ed8;
            --shadow: 0 12px 30px rgba(0, 0, 0, .35);
            --glow-soft: 0 0 10px rgba(56, 189, 248, .28);
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
            margin: 26px auto 60px;
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
            font-weight: 900;
            color: #f8fafc;
            font-size: clamp(22px, 3.2vw, 32px);
            text-shadow: 0 0 15px rgba(59, 130, 246, .5);
        }

        .crumb {
            color: #cbd5e1;
            font-size: .9rem;
        }

        .logo {
            height: 56px;
            width: auto;
            filter: drop-shadow(0 0 10px rgba(59, 130, 246, .6));
        }

        .search-bar {
            display: flex;
            gap: 12px;
            align-items: center;
            margin-bottom: 18px;
        }

        .search-bar input {
            flex: 1;
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, .15);
            background-color: rgba(255, 255, 255, .08);
            color: #e5e7eb;
            outline: none;
        }

        .search-bar input::placeholder {
            color: #9ca3af;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            border-radius: 12px;
            padding: 11px 14px;
            font-weight: 700;
            color: #fff;
            cursor: pointer;
            text-decoration: none;
            transition: .2s;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 15px rgba(59, 130, 246, .35);
        }

        .btn.primary {
            background: linear-gradient(180deg, var(--brand1), var(--brand2));
        }

        .btn.danger {
            background: linear-gradient(180deg, #ef4444, #b91c1c);
        }

        .btn.warn {
            background: linear-gradient(180deg, #facc15, #ca8a04);
            color: #111;
        }

        .btn.blue {
            background: linear-gradient(180deg, #3b82f6, #1d4ed8);
        }

        .btn.pay {
            background: linear-gradient(180deg, #16a34a, #15803d);
            color: #fff;
            font-weight: 800;
            box-shadow: 0 0 14px rgba(22, 163, 74, .45);
        }

        .btn.pay:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 20px rgba(22, 163, 74, .6);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
            border-radius: var(--r);
            color: #e5e7eb;
            font-size: .94rem;
        }

        thead {
            background: rgba(37, 99, 235, .25);
        }

        th,
        td {
            padding: 12px 16px;
            text-align: left;
        }

        th {
            font-weight: 700;
            color: #dbeafe;
            text-transform: uppercase;
            font-size: .8rem;
        }

        tbody tr:nth-child(even) {
            background: rgba(255, 255, 255, .05);
        }

        tbody tr:hover {
            background: rgba(37, 99, 235, .15);
        }

        .acciones-wrap {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .paciente-info {
            display: flex;
            flex-direction: column;
        }

        .avatar {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(37, 99, 235, .5);
            border-radius: 50%;
            width: 36px;
            height: 36px;
            color: #fff;
            font-weight: 700;
            font-size: .9rem;
        }

        .foot {
            text-align: center;
            color: #9ca3af;
            font-size: .8rem;
            margin-top: 40px;
        }
    </style>

    <div class="dash-bg"></div>

    <div class="wrap">
        {{-- ENCABEZADO --}}
        <div class="glass head">
            <div>
                <div class="crumb">Pacientes</div>
                <h1 class="title">Registro de pacientes</h1>
            </div>
            <div style="display:flex;align-items:center;gap:10px">
                <a class="btn ghost" href="{{ route('dashboard') }}">← Volver al Panel</a>
                <img class="logo" src="/images/logo.png" alt="ArteDental">
            </div>
        </div>

        {{-- BUSCADOR + BOTÓN NUEVO PACIENTE --}}
        <div class="glass search-bar">
            <input type="text" placeholder="Buscar por DNI, nombre, teléfono, email, provincia o distrito…">
            <a href="{{ route('pacientes.create') }}" class="btn primary">＋ Nuevo paciente</a>
        </div>

        {{-- TABLA DE PACIENTES --}}
        <div class="glass">
            <table>
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Paciente</th>
                        <th>DNI</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Ubicación</th>
                        <th>Dirección</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pacientes as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="paciente-info">
                                    <div class="avatar">
                                        {{ strtoupper(substr($p->nombre, 0, 1)) }}{{ strtoupper(substr($p->apellido, 0, 1)) }}
                                    </div>
                                    <strong>{{ $p->apellido }}, {{ $p->nombre }}</strong>
                                    <small>Nac.: {{ $p->fecha_nacimiento?->format('d/m/Y') }}</small>
                                </div>
                            </td>
                            <td>{{ $p->dni }}</td>
                            <td>{{ $p->telefono }}</td>
                            <td>{{ $p->email }}</td>
                            <td>
                                <span class="btn ghost"
                                    style="padding:4px 10px;font-size:.8rem;">{{ $p->provincia }}</span>
                                <span class="btn ghost"
                                    style="padding:4px 10px;font-size:.8rem;">{{ $p->distrito }}</span>
                            </td>
                            <td>{{ $p->direccion }}</td>
                            <td>
                                <div class="acciones-wrap">
                                    {{-- 🩺 Historia clínica --}}
                                    <a href="{{ route('historias.show', $p) }}" class="btn blue">
                                        🩺 Historia clínica
                                    </a>

                                    {{-- 💰 Ver pagos --}}
                                    <a href="{{ route('soporte.finanzas.pagos.index', ['paciente' => $p->nombre]) }}"
                                        class="btn blue">
                                        💰 Ver pagos
                                    </a>

                                    {{-- 💳 Registrar pago --}}
                                    <a href="{{ route('soporte.finanzas.pagos.create', ['paciente_id' => $p->id]) }}"
                                        class="btn pay">
                                        💳 Registrar pago
                                    </a>

                                    {{-- ✏️ Editar --}}
                                    <a href="{{ route('pacientes.edit', $p) }}" class="btn warn">
                                        ✏️ Editar
                                    </a>

                                    {{-- 🗑️ Eliminar --}}
                                    <form method="POST" action="{{ route('pacientes.destroy', $p) }}"
                                        onsubmit="return confirm('¿Eliminar paciente?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn danger">🗑️ Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;color:#9ca3af;padding:20px;">
                                No hay pacientes registrados actualmente.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ✅ ÚNICO FOOTER --}}
        <div class="foot">
            © 2025 Clínica Arte Dental — Todos los derechos reservados.
        </div>
    </div>
@endsection
