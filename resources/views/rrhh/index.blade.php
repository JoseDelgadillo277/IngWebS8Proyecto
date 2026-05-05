{{-- Modulo de RRHH para acceder a configuraciones de personal. --}}
@extends('layouts.app')

@section('hide_default_nav', true)
@section('title', 'Gestión de RRHH')

@section('content')
    <style>
        :root {
            --glass: rgba(17, 24, 39, .55);
            --glass-border: rgba(255, 255, 255, .10);
            --glow: 0 0 18px rgba(59, 130, 246, .35);
            --glow-soft: 0 0 10px rgba(56, 189, 248, .28);
            --text: #eaf2ff;
            --muted: #9ca3af;
        }

        .dash-bg {
            position: fixed;
            inset: 0;
            z-index: -2;
            background:
                linear-gradient(180deg, rgba(6, 11, 28, .75), rgba(6, 11, 28, .6)),
                url('/images/bg-dashboard.jpg') center/cover no-repeat;
        }

        .wrap {
            max-width: 900px;
            margin: 60px auto;
            padding: 0 18px;
        }

        .glass {
            backdrop-filter: blur(10px) saturate(120%);
            background: var(--glass);
            border: 1px solid var(--glass-border);
            border-radius: 18px;
            box-shadow: var(--glow-soft);
            padding: 32px;
        }

        h1 {
            font-weight: 900;
            color: #f8fafc;
            font-size: clamp(28px, 4vw, 36px);
            text-align: center;
            margin-bottom: 10px;
            text-shadow: var(--glow);
        }

        p.subtitle {
            text-align: center;
            color: var(--muted);
            margin-bottom: 30px;
            font-size: 1rem;
        }

        .actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            justify-items: center;
        }

        .btn-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            gap: 10px;
            width: 100%;
            height: 140px;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, .16);
            color: #eaf3ff;
            font-weight: 700;
            font-size: 1.1rem;
            text-decoration: none;
            background:
                radial-gradient(150% 150% at 0% 0%, rgba(99, 102, 241, .22), transparent 60%),
                radial-gradient(150% 150% at 100% 0%, rgba(56, 189, 248, .18), transparent 60%),
                rgba(255, 255, 255, .10);
            transition: all .25s ease;
            box-shadow: var(--glow-soft);
        }

        .btn-card:hover {
            transform: translateY(-4px);
            background:
                radial-gradient(150% 150% at 0% 0%, rgba(99, 102, 241, .35), transparent 60%),
                radial-gradient(150% 150% at 100% 0%, rgba(56, 189, 248, .3), transparent 60%),
                rgba(255, 255, 255, .16);
            box-shadow: var(--glow);
        }

        .btn-card i {
            font-size: 36px;
            text-shadow: var(--glow);
        }

        .footer {
            text-align: center;
            margin-top: 40px;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: 12px;
            background: rgba(255, 255, 255, .08);
            border: 1px solid rgba(255, 255, 255, .15);
            color: #dbeafe;
            font-weight: 700;
            text-decoration: none;
            transition: .2s;
        }

        .btn-back:hover {
            background: rgba(59, 130, 246, .25);
            box-shadow: var(--glow-soft);
            transform: translateY(-2px);
        }

        .logo {
            display: block;
            height: 64px;
            margin: 0 auto 30px;
            filter: drop-shadow(0 0 12px rgba(59, 130, 246, .6));
        }
    </style>

    <div class="dash-bg"></div>

    <div class="wrap">
        <div class="glass">
            <img class="logo" src="/images/logo.png" alt="Arte Dental">

            <h1>Gestión de Recursos Humanos</h1>
            <p class="subtitle">Selecciona una de las opciones para administrar el personal de la clínica.</p>

            <div class="actions">
                <a href="{{ route('users.index') }}" class="btn-card">
                    <i>👤</i>
                    <span>Gestión de usuarios</span>
                </a>

                <a href="{{ route('odontologos.config') }}" class="btn-card">
                    <i>🦷</i>
                    <span>Configurar odontólogos</span>
                </a>
            </div>

            <div class="footer">
                <a href="{{ route('dashboard') }}" class="btn-back">← Volver al panel principal</a>
            </div>
        </div>
    </div>
@endsection
