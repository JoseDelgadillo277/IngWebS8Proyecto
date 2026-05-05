@extends('layouts.app')

@section('hide_default_nav', true)
@section('title', 'Usuarios')

@section('content')
    <style>
        :root {
            --glass: rgba(17, 24, 39, .55);
            --glass-border: rgba(255, 255, 255, .10);
            --txt: #e5e7eb;
            --muted: #c7d2fe;
            --brand1: #2563eb;
            --brand2: #1d4ed8;
            --ok: #22c55e;
            --warn: #f59e0b;
            --danger: #ef4444;
            --r: 16px;
            --shadow: 0 12px 30px rgba(0, 0, 0, .35);
            --glow-soft: 0 0 10px rgba(56, 189, 248, .28);
        }

        .dash-bg {
            position: fixed;
            inset: 0;
            z-index: -2;
            background: linear-gradient(180deg, rgba(6, 11, 28, .70), rgba(6, 11, 28, .55)),
                url('/images/bg-dashboard.jpg') center/cover no-repeat;
        }

        .wrap {
            max-width: 1100px;
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
            text-shadow: 0 0 15px rgba(59, 130, 246, .45)
        }

        .crumb {
            color: #cbd5e1;
            font-size: .9rem
        }

        .logo {
            height: 56px;
            width: auto;
            filter: drop-shadow(0 0 10px rgba(59, 130, 246, .6))
        }

        .card {
            padding: 18px 20px
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
                radial-gradient(120% 120% at 100% 0%, rgba(56, 189, 248, .18), transparent 60%),
                rgba(255, 255, 255, .10);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 16px rgba(59, 130, 246, .35)
        }

        .btn.primary {
            background: linear-gradient(180deg, var(--brand1), var(--brand2));
            color: #fff
        }

        .btn.ghost {
            background: rgba(255, 255, 255, .06);
            border-color: rgba(255, 255, 255, .18)
        }

        .btn.warn {
            background: linear-gradient(180deg, #f59e0b, #b45309);
            color: #111
        }

        .btn.danger {
            background: linear-gradient(180deg, #ef4444, #b91c1c)
        }

        table {
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
            border-radius: 14px;
            color: #e5e7eb
        }

        thead {
            background: rgba(37, 99, 235, .25)
        }

        th,
        td {
            padding: 12px 14px;
            text-align: left
        }

        th {
            font-size: .82rem;
            color: #dbeafe;
            text-transform: uppercase;
            letter-spacing: .02em
        }

        tbody tr:nth-child(even) {
            background: rgba(255, 255, 255, .05)
        }

        tbody tr:hover {
            background: rgba(37, 99, 235, .15)
        }

        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap
        }

        .foot {
            text-align: center;
            color: #9ca3af;
            font-size: .85rem;
            margin-top: 28px
        }
    </style>

    <div class="dash-bg"></div>

    <div class="wrap">
        {{-- Header --}}
        <div class="glass head">
            <div>
                <div class="crumb">Administración</div>
                <h1 class="title">Usuarios</h1>
            </div>
            <div style="display:flex;align-items:center;gap:10px">
                <a class="btn ghost" href="{{ route('dashboard') }}">← Volver al Panel</a>
                <img class="logo" src="/images/logo.png" alt="ArteDental">
            </div>
        </div>

        {{-- Caja principal --}}
        <div class="glass card">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px">
                <div style="color:#cbd5e1">Gestiona cuentas, correos y roles del personal.</div>
                <a href="{{ route('users.create') }}" class="btn primary">＋ Nuevo usuario</a>
            </div>

            <div class="glass" style="padding:0; overflow:hidden">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th style="width:220px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $u)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $u->name }}</td>
                                <td>{{ $u->email }}</td>
                                <td>
                                    @php
                                        $role = method_exists($u, 'getRoleNames')
                                            ? $u->getRoleNames()->first() ?? '—'
                                            : $u->role ?? '—';
                                    @endphp
                                    <span class="btn ghost"
                                        style="padding:4px 10px; font-size:.8rem">{{ $role }}</span>
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="{{ route('users.edit', $u) }}" class="btn warn">✏️ Editar</a>
                                        <form action="{{ route('users.destroy', $u) }}" method="POST"
                                            onsubmit="return confirm('¿Eliminar usuario?')">
                                            @csrf @method('DELETE')
                                            <button class="btn danger" type="submit">🗑️ Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center; color:#9ca3af; padding:18px">
                                    No hay usuarios registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Único footer --}}

    </div>
@endsection
