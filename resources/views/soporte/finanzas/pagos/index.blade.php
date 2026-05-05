@extends('layouts.app')
@section('hide_default_nav', true)
@section('title', 'Pagos registrados')

@section('content')
    <style>
        :root {
            --glass: rgba(17, 24, 39, .55);
            --b: rgba(255, 255, 255, .12);
            --txt: #eaf2ff;
            --mut: #94a3b8;
        }

        .bg {
            position: fixed;
            inset: 0;
            z-index: -3;
            background: linear-gradient(180deg, rgba(6, 11, 28, .8), rgba(6, 11, 28, .6)),
                url('/images/bg-dashboard.jpg') center/cover no-repeat;
        }

        .panel {
            max-width: 1200px;
            margin: 90px auto;
            background: var(--glass);
            backdrop-filter: blur(12px) saturate(120%);
            border: 1px solid var(--b);
            border-radius: 20px;
            overflow: hidden;
            color: var(--txt);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid var(--b);
        }

        .header h1 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 800;
        }

        .table {
            width: 100%;
            border-collapse: collapse
        }

        th,
        td {
            padding: 12px 14px;
            border-bottom: 1px solid var(--b);
            text-align: left
        }

        thead th {
            background: rgba(255, 255, 255, .08);
            font-weight: 800
        }

        tbody tr:hover {
            background: rgba(255, 255, 255, .05)
        }

        .btn-top {
            padding: 10px 14px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 800;
            background: linear-gradient(180deg, #2563eb, #1d4ed8);
            color: #fff;
            border: none;
            box-shadow: 0 4px 10px rgba(37, 99, 235, .4)
        }

        .btn-top:hover {
            filter: brightness(1.1)
        }

        .mut {
            color: var(--mut);
            font-size: .9rem
        }
    </style>

    <div class="bg"></div>

    <div class="panel">
        <div class="header">
            <h1>💰 Pagos Registrados</h1>
            <a href="{{ route('dashboard') }}" class="btn-top" style="background:linear-gradient(180deg,#6b7280,#4b5563)">
                ← Volver al Panel
            </a>
        </div>

        {{-- FILTRO BÁSICO --}}
        <div style="padding: 15px 20px;">
            <form method="GET" action="{{ route('soporte.finanzas.pagos.index') }}"
                style="display:flex;gap:10px;flex-wrap:wrap">
                <input type="text" name="paciente" placeholder="Buscar paciente..." value="{{ request('paciente') }}"
                    style="padding:10px;border-radius:10px;border:1px solid var(--b);background:rgba(255,255,255,.08);color:#fff;">
                <input type="date" name="desde" value="{{ request('desde') }}"
                    style="padding:10px;border-radius:10px;border:1px solid var(--b);background:rgba(255,255,255,.08);color:#fff;">
                <input type="date" name="hasta" value="{{ request('hasta') }}"
                    style="padding:10px;border-radius:10px;border:1px solid var(--b);background:rgba(255,255,255,.08);color:#fff;">
                <button type="submit" class="btn-top">🔍 Filtrar</button>
                <a href="{{ route('soporte.finanzas.pagos.index') }}" class="btn-top"
                    style="background:linear-gradient(180deg,#ef4444,#dc2626)">Limpiar</a>
            </form>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Paciente</th>
                    <th>DNI</th>
                    <th>Concepto</th>
                    <th>Monto</th>
                    <th>Medio</th>
                    <th>Fecha</th>
                    <th>Registrado por</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pagos as $pago)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ $pago->paciente?->apellidos ?? ($pago->paciente?->apellido ?? '') }},
                                {{ $pago->paciente?->nombres ?? ($pago->paciente?->nombre ?? '') }}</strong><br>
                            <span class="mut">ID: {{ $pago->paciente?->id ?? '—' }}</span>
                        </td>
                        <td>{{ $pago->paciente?->dni ?? '—' }}</td>
                        <td>{{ $pago->concepto }}</td>
                        <td><strong>S/ {{ number_format($pago->monto, 2) }}</strong></td>
                        <td>{{ ucfirst($pago->medio_pago) }}</td>
                        <td>{{ $pago->fecha->format('d/m/Y') }}</td>
                        <td>{{ $pago->usuario?->name ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center;color:#ccc;padding:20px;">
                            No hay pagos registrados
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- PAGINACIÓN --}}
        <div style="padding: 20px">
            {{ $pagos->withQueryString()->links() }}
        </div>
    </div>
@endsection
