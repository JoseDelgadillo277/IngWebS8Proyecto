{{-- Modulo de soporte administrativo. --}}
@extends('layouts.app')
@section('hide_default_nav', true)
@section('title', 'Soporte · Administración')

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
        <main class="panel" role="main" aria-labelledby="ttl-adm">
            <header class="hero">
                <span class="badge">🏛️ Soporte</span>
                <h1 id="ttl-adm">Administración</h1>
            </header>

            <section class="content">
                <div class="grid">
                    <article class="card">
                        <h3>Subprocesos</h3>
                        <ul class="list">
                            <li><b>Gestión documental</b> (contratos, consentimientos, políticas).</li>
                            <li><b>Compras &amp; Proveedores</b> (requerimientos, órdenes, evaluación).</li>
                            <li><b>Activos &amp; Mantenimiento</b> (inventario, odontología, TI).</li>
                            <li><b>Talento &amp; Turnos</b> (perfiles, capacitaciones, programación).</li>
                            <li><b>Atención al cliente</b> (quejas/sugerencias, SLA de respuesta).</li>
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
                                    <td>Ciclo de compra</td>
                                    <td>≤ 5 días</td>
                                </tr>
                                <tr>
                                    <td>Stock crítico</td>
                                    <td>0 quiebres</td>
                                </tr>
                                <tr>
                                    <td>SLA respuestas</td>
                                    <td>≤ 24 h</td>
                                </tr>
                                <tr>
                                    <td>Capacitaciones</td>
                                    <td>1/mes por rol</td>
                                </tr>
                                <tr>
                                    <td>Documentos vigentes</td>
                                    <td>100%</td>
                                </tr>
                            </tbody>
                        </table>
                    </aside>
                </div>

                <article class="card">
                    <h3>Puntos clave</h3>
                    <ul class="list">
                        <li>Flujo de <b>compras→ingreso a almacén→consumo</b> con trazabilidad.</li>
                        <li>Control de <b>vencimientos</b> (insumos clínicos) y <b>mantenimientos</b> preventivos.</li>
                        <li>Gestión de <b>turnos</b> y <b>cobertura</b> por especialidad.</li>
                        <li>Registro y atención de <b>quejas/sugerencias</b> con métricas.</li>
                    </ul>
                    <span class="pill">Responsable: Administración</span>
                </article>
            </section>
        </main>
    </div>

    <footer>© {{ now()->year }} Clínica Arte Dental — Todos los derechos reservados.</footer>
@endsection
