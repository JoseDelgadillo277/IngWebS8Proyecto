@extends('layouts.app')

@section('hide_default_nav', true)
@section('title', 'Nuevo usuario')

@section('content')
    <style>
        :root {
            --glass: rgba(17, 24, 39, .55);
            --glass-border: rgba(255, 255, 255, .10);
            --txt: #e5e7eb;
            --muted: #c7d2fe;
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
            max-width: 900px;
            margin: 26px auto 60px;
            padding: 0 14px;
        }

        .glass {
            backdrop-filter: blur(12px) saturate(140%);
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
            text-shadow: 0 0 15px rgba(59, 130, 246, .45);
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

        label {
            display: block;
            margin: 6px 0 6px;
            color: #e5e7eb;
            font-weight: 700;
            font-size: .92rem;
        }

        input,
        select {
            width: 100%;
            padding: 12px 14px;
            border-radius: 12px;
            color: #eaf2ff !important;
            -webkit-text-fill-color: #eaf2ff !important;
            background-color: rgba(255, 255, 255, .05) !important;
            border: 1px solid rgba(255, 255, 255, .18) !important;
            outline: none;
            transition: .18s;
            box-shadow: inset 0 0 8px rgba(255, 255, 255, .05);
        }

        input:focus,
        select:focus {
            background-color: rgba(255, 255, 255, .08) !important;
            border-color: rgba(59, 130, 246, .50) !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .25) !important;
        }

        /* 🔹 Elimina autofill blanco en Chrome/Edge */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        textarea:-webkit-autofill,
        select:-webkit-autofill {
            -webkit-box-shadow: 0 0 0px 1000px rgba(255, 255, 255, 0.05) inset !important;
            -webkit-text-fill-color: #eaf2ff !important;
            transition: background-color 5000s ease-in-out 0s;
        }

        ::placeholder {
            color: #cbd5e1 !important;
            opacity: .85 !important;
        }

        select option {
            background: #0f1a33 !important;
            color: #eaf2ff !important;
        }

        .actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            border: 1px solid transparent;
            padding: 11px 14px;
            border-radius: 12px;
            font-weight: 800;
            text-decoration: none;
            color: #eaf3ff;
            transition: .2s;
            background: radial-gradient(120% 120% at 0% 0%, rgba(99, 102, 241, .22), transparent 60%),
                radial-gradient(120% 120% at 100% 0%, rgba(56, 189, 248, .18), transparent 60%),
                rgba(255, 255, 255, .10);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 16px rgba(59, 130, 246, .35);
        }

        .btn.primary {
            background: linear-gradient(180deg, var(--brand1), var(--brand2));
            color: #fff;
        }

        .btn.ghost {
            background: rgba(255, 255, 255, .06);
            border-color: rgba(255, 255, 255, .18);
        }

        .foot {
            text-align: center;
            color: #9ca3af;
            font-size: .85rem;
            margin-top: 28px;
        }

        .card {
            padding: 24px 30px;
            box-shadow: var(--glow-soft);
        }
    </style>

    <div class="dash-bg"></div>

    <div class="wrap">
        {{-- Header --}}
        <div class="glass head">
            <div>
                <div class="crumb">Administración / Usuarios</div>
                <h1 class="title">Nuevo usuario</h1>
            </div>
            <div style="display:flex;align-items:center;gap:10px">
                <a class="btn ghost" href="{{ route('dashboard') }}">← Volver al Panel</a>
                <img class="logo" src="/images/logo.png" alt="ArteDental">
            </div>
        </div>

        {{-- Formulario --}}
        <form class="glass card" method="POST" action="{{ route('users.store') }}" autocomplete="off">
            @csrf

            <div style="display:grid; grid-template-columns:1fr; gap:14px; max-width:600px; margin:auto">
                <div>
                    <label for="name">Nombre</label>
                    <input id="name" name="name" value="{{ old('name') }}" placeholder="Nombre y apellido"
                        required>
                </div>

                <div>
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                        placeholder="correo@dominio.com" required>
                </div>

                <div>
                    <label for="password">Contraseña</label>
                    <input id="password" type="password" name="password" placeholder="Mínimo 8 caracteres" required>
                </div>

                <div>
                    <label for="role">Rol</label>
                    <select id="role" name="role" required>
                        <option value="">— Seleccionar —</option>
                        <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                        <option value="recepcionista" @selected(old('role') === 'recepcionista')>Recepcionista</option>
                        <option value="odontologo" @selected(old('role') === 'odontologo')>Odontólogo</option>
                        <option value="asistente" @selected(old('role') === 'asistente')>Asistente</option>
                    </select>
                </div>
            </div>

            <div class="actions">
                <a class="btn ghost" href="{{ route('users.index') }}">Cancelar</a>
                <button class="btn primary" type="submit">💾 Guardar</button>
            </div>
        </form>


    </div>
@endsection
