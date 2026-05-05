@extends('layouts.app')
@section('hide_default_nav', true)
@section('title', 'Innovación y mejora')

@section('content')
    <style>
        :root {
            --bg1: rgba(6, 11, 28, .86);
            --bg2: rgba(6, 11, 28, .58);
            --glass: rgba(17, 24, 39, .62);
            --b: rgba(255, 255, 255, .12);
            --txt: #eaf2ff;
            --mut: #cbd5e1;
            --gold: #fbbf24;
            --gold2: #f59e0b;
            --r: 22px
        }

        .bg {
            position: fixed;
            inset: 0;
            z-index: -3;
            background: linear-gradient(180deg, var(--bg1), var(--bg2)), url('/images/bg-dashboard.jpg') center/cover no-repeat
        }

        .brand {
            position: fixed;
            top: 86px;
            left: 22px;
            z-index: 20
        }

        .brand img {
            height: 56px;
            filter: drop-shadow(0 0 12px rgba(251, 191, 36, .55))
        }

        .btn-back {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 30;
            padding: 10px 16px;
            border-radius: 12px;
            border: 1px solid var(--b);
            color: #fff;
            text-decoration: none;
            font-weight: 800;
            background: linear-gradient(180deg, rgba(255, 255, 255, .14), rgba(255, 255, 255, .08));
            backdrop-filter: blur(8px);
            transition: .2s
        }

        .btn-back:hover {
            background: linear-gradient(180deg, var(--gold), var(--gold2));
            transform: translateY(-2px);
            box-shadow: 0 0 22px rgba(251, 191, 36, .35);
            border-color: rgba(251, 191, 36, .45)
        }

        .wrap {
            min-height: 100dvh;
            display: grid;
            place-items: start center;
            padding: clamp(90px, 10vw, 130px) 18px 96px
        }

        .panel {
            width: min(1080px, 96vw);
            border: 1px solid var(--b);
            border-radius: var(--r);
            background: var(--glass);
            backdrop-filter: blur(14px) saturate(120%);
            box-shadow: 0 18px 40px rgba(0, 0, 0, .38);
            overflow: clip
        }

        .hero {
            display: flex;
            gap: 12px;
            align-items: center;
            justify-content: space-between;
            padding: 20px 22px;
            border-bottom: 1px solid var(--b);
            color: #fff
        }

        .hero h1 {
            margin: 0;
            font-weight: 900;
            font-size: clamp(22px, 3.2vw, 30px)
        }

        .mut {
            color: var(--mut)
        }

        .content {
            padding: 22px;
            color: var(--txt);
            display: grid;
            gap: 16px
        }

        .card {
            border: 1px solid var(--b);
            border-radius: 16px;
            background: rgba(255, 255, 255, .06);
            padding: 16px 18px
        }

        h3 {
            margin: 0 0 8px;
            color: #fff;
            font-weight: 900
        }

        ul {
            margin: 0;
            padding-left: 18px
        }

        li {
            margin: 6px 0
        }

        .timeline {
            display: grid;
            gap: 10px
        }

        .item {
            display: grid;
            grid-template-columns: 26px 1fr;
            gap: 10px;
            align-items: start
        }

        .dot {
            width: 12px;
            height: 12px;
            border-radius: 9999px;
            background: #fde68a;
            box-shadow: 0 0 0 5px rgba(251, 191, 36, .18)
        }

        .pill {
            display: inline-block;
            margin-top: 8px;
            padding: .22rem .6rem;
            border: 1px solid var(--b);
            border-radius: 999px;
            color: #e5e7eb;
            font-size: .85rem
        }

        .cta {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 8px
        }

        .btn {
            padding: 10px 14px;
            border-radius: 12px;
            border: 1px solid var(--b);
            color: #fff;
            text-decoration: none;
            background: rgba(255, 255, 255, .10)
        }

        .btn.primary {
            background: linear-gradient(180deg, var(--gold), var(--gold2));
            border-color: rgba(251, 191, 36, .45);
            font-weight: 800
        }
    </style>

    <div class="bg"></div>
    <a href="{{ route('dashboard') }}" class="btn-back">← Volver al panel</a>
    <div class="brand"><img src="/images/logo.png" alt="ArteDental"></div>

    <div class="wrap">
        <main class="panel" role="main" aria-labelledby="ttl">
            <header class="hero">
                <h1 id="ttl">💡 Innovación y mejora continua</h1>
                <span class="mut">Tecnología al servicio de tu sonrisa</span>
            </header>

            <section class="content">
                <article class="card">
                    <h3>¿Qué significa para ti?</h3>
                    <ul>
                        <li>Recordatorios de cita y reprogramación simple.</li>
                        <li>Historia clínica digital y entrega de documentos en línea.</li>
                        <li>Imágenes y diagnósticos apoyados en herramientas digitales.</li>
                        <li>Teleorientación para resolver dudas antes y después del procedimiento.</li>
                    </ul>
                    <span class="pill">Experiencia paciente</span>
                    <span class="pill">Confianza y claridad</span>
                </article>

                <article class="card">
                    <h3>Roadmap público</h3>
                    <div class="timeline">
                        <div class="item"><span class="dot"></span>
                            <div><b>Q1:</b> Recordatorios por WhatsApp/SMS y confirmación 1 clic.</div>
                        </div>
                        <div class="item"><span class="dot"></span>
                            <div><b>Q2:</b> Portal del paciente (descarga de recetas, presupuestos y constancias).</div>
                        </div>
                        <div class="item"><span class="dot"></span>
                            <div><b>Q3:</b> Firma digital de consentimientos y teleorientación segura.</div>
                        </div>
                        <div class="item"><span class="dot"></span>
                            <div><b>Q4:</b> Apoyo inteligente al diagnóstico (uso interno, validado clínicamente).</div>
                        </div>
                    </div>
                </article>

                <article class="card">
                    <h3>Protección de tus datos</h3>
                    <ul>
                        <li>Uso responsable de información y acceso controlado por perfiles.</li>
                        <li>Cifrado en tránsito y respaldo periódico.</li>
                        <li>Respeto a tu consentimiento para comunicaciones y uso de imágenes.</li>
                    </ul>
                </article>


            </section>
        </main>
    </div>
@endsection
