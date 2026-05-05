@extends('layouts.app')
@section('hide_default_nav', true)
@section('title', 'Soporte · Finanzas')

@section('content')
    <style>
        :root {
            --bg1: rgba(6, 11, 28, .86);
            --bg2: rgba(6, 11, 28, .58);
            --glass: rgba(17, 24, 39, .62);
            --b: rgba(255, 255, 255, .12);
            --txt: #eaf2ff;
            --mut: #cbd5e1;
            --r: 18px
        }

        .bg {
            position: fixed;
            inset: 0;
            z-index: -2;
            background: linear-gradient(180deg, var(--bg1), var(--bg2)), url('/images/bg-dashboard.jpg') center/cover no-repeat
        }

        .wrap {
            min-height: 100dvh;
            display: grid;
            place-items: start center;
            padding: clamp(80px, 10vw, 120px) 18px 96px
        }

        .panel {
            width: min(1120px, 96vw);
            border: 1px solid var(--b);
            border-radius: var(--r);
            background: var(--glass);
            backdrop-filter: blur(14px) saturate(120%);
            overflow: clip
        }

        .btn {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 10;
            padding: 10px 16px;
            border-radius: 12px;
            color: #fff;
            text-decoration: none;
            border: 1px solid var(--b);
            background: rgba(255, 255, 255, .10);
            backdrop-filter: blur(8px)
        }

        .brand {
            position: fixed;
            top: 86px;
            left: 22px;
            z-index: 9
        }

        .brand img {
            height: 56px;
            filter: drop-shadow(0 0 12px rgba(251, 191, 36, .55))
        }

        .hero {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 12px;
            align-items: center;
            padding: 22px;
            border-bottom: 1px solid var(--b);
            color: #fff
        }

        .badge {
            padding: .38rem .65rem;
            border: 1px solid var(--b);
            border-radius: 999px;
            background: rgba(255, 255, 255, .08)
        }

        .content {
            padding: 22px;
            color: var(--txt);
            display: grid;
            gap: 16px
        }

        .grid {
            display: grid;
            gap: 16px;
            grid-template-columns: 1fr
        }

        @media(min-width:1000px) {
            .grid {
                grid-template-columns: 1.1fr .9fr
            }
        }

        .card {
            border: 1px solid var(--b);
            border-radius: 16px;
            background: rgba(255, 255, 255, .06);
            padding: 16px 18px
        }

        h3 {
            margin: 0 0 8px;
            color: #fff
        }

        .list {
            margin: 0;
            padding-left: 18px
        }

        .list li {
            margin: 6px 0
        }

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border: 1px solid var(--b);
            border-radius: 14px;
            overflow: hidden
        }

        .table th,
        .table td {
            padding: 10px 12px;
            border-bottom: 1px solid var(--b)
        }

        .table th {
            background: rgba(255, 255, 255, .05);
            text-align: left;
            color: #e5e7eb
        }

        .pill {
            display: inline-block;
            padding: .22rem .55rem;
            border-radius: 9999px;
            border: 1px solid var(--b);
            font-size: 12px;
            color: #e5e7eb
        }
    </style>

    <div class="bg"></div>
    <a href="{{ route('dashboard') }}" class="btn">← Volver</a>
    <div class="brand"><img src="/images/logo.png" alt="ArteDental"></div>

    <div class="wrap">
        <main class="panel" role="main" aria-labelledby="ttl-fin">
            <header class="hero">
                <span class="badge">💼 Soporte</span>
                <h1 id="ttl-fin">Finanzas</h1>
            </header>

            <section class="content">
                <div class="grid">
                    <article class="card">
                        <h3>Subprocesos</h3>
                        <ul class="list">
                            <li><b>Presupuesto &amp; Contabilidad</b> (ingresos/egresos, plan anual).</li>
                            <li><b>Cuentas por cobrar</b> (facturas/boletas, convenios, seguimiento).</li>
                            <li><b>Caja y Bancos</b> (arqueos, conciliaciones, depósitos).</li>
                            <li><b>Cuentas por pagar</b> (proveedores, compras, servicios).</li>
                            <li><b>Reportes</b> (estado de resultados, flujo de caja, indicadores).</li>
                        </ul>
                    </article>

                    <aside class="card">
                        <h3>Indicadores</h3>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Indicador</th>
                                    <th>Meta</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>DSO (días de cobro)</td>
                                    <td>≤ 15 días</td>
                                </tr>
                                <tr>
                                    <td>Conciliación bancaria</td>
                                    <td>100% mensual</td>
                                </tr>
                                <tr>
                                    <td>Exactitud de arqueo</td>
                                    <td>100%</td>
                                </tr>
                                <tr>
                                    <td>Margen operativo</td>
                                    <td>≥ objetivo anual</td>
                                </tr>
                            </tbody>
                        </table>
                    </aside>
                </div>

                <article class="card">
                    <h3>Registrar Pago (Cuentas por Cobrar / Caja y Bancos)</h3>
                    <ul class="list">
                        <li><b>Cuándo:</b> al cancelar una atención/plan de tratamiento o una cuota de convenio.</li>
                        <li><b>Datos mínimos:</b> paciente, documento (boleta/factura), concepto, monto, medio
                            (efectivo/TPV/transferencia), fecha, cajero, observación.</li>
                        <li><b>Validaciones:</b> total ≤ saldo pendiente; forma de pago obligatoria; prohibir duplicados por
                            N° de operación.</li>
                        <li><b>Automático:</b> actualizar saldo del paciente; generar asiento contable; emitir comprobante
                            PDF.</li>
                        <li><b>Reportes:</b> arqueo diario, ventas por método de pago, cartera por vencer.</li>
                    </ul>
                    <span class="pill">Responsable: Caja</span>
                    <span class="pill">Soporte: Contabilidad</span>
                </article>
            </section>
        </main>
    </div>

    <footer>© {{ now()->year }} Clínica Arte Dental — Todos los derechos reservados.</footer>
@endsection
