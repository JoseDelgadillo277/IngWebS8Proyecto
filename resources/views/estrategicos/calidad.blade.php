@extends('layouts.app')
@section('hide_default_nav', true)
@section('title', 'Gestión de calidad')

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

        .pill {
            display: inline-block;
            margin-top: 8px;
            padding: .22rem .6rem;
            border: 1px solid var(--b);
            border-radius: 999px;
            color: #e5e7eb;
            font-size: .85rem
        }

        .grid {
            display: grid;
            gap: 16px;
            grid-template-columns: 1fr
        }

        @media(min-width:980px) {
            .grid {
                grid-template-columns: 1fr 1fr
            }
        }
    </style>

    <div class="bg"></div>
    <a href="{{ route('dashboard') }}" class="btn-back">← Volver al panel</a>
    <div class="brand"><img src="/images/logo.png" alt="ArteDental"></div>

    <div class="wrap">
        <main class="panel" role="main" aria-labelledby="ttl">
            <header class="hero">
                <h1 id="ttl">✅ Gestión de calidad</h1>
                <span class="mut">Nos comprometemos con tu seguridad y satisfacción</span>
            </header>

            <section class="content">
                <div class="grid">
                    <article class="card">
                        <h3>Política de calidad para pacientes</h3>
                        <ul>
                            <li>Trato digno y confidencialidad de tu información clínica.</li>
                            <li>Consentimiento informado claro antes de cada procedimiento.</li>
                            <li>Esterilización y bioseguridad conforme a normativa vigente.</li>
                            <li>Comunicación empática y seguimiento post-atención.</li>
                        </ul>
                        <span class="pill">Seguridad del paciente</span>
                        <span class="pill">Ética profesional</span>
                    </article>

                    <article class="card">
                        <h3>Compromisos de servicio</h3>
                        <ul>
                            <li>Respeto del horario de tu cita y tiempos de espera razonables.</li>
                            <li>Explicación de alternativas terapéuticas y presupuesto transparente.</li>
                            <li>Atención de urgencias odontológicas en horario extendido.</li>
                            <li>Canal de sugerencias y reclamos con respuesta oportuna.</li>
                        </ul>
                        <span class="pill">Transparencia</span>
                        <span class="pill">Mejora continua</span>
                    </article>
                </div>

                <article class="card">
                    <h3>Derechos y deberes</h3>
                    <ul>
                        <li><b>Derechos:</b> Información clara, privacidad, consentimiento, segunda opinión y acceso a tu
                            historia clínica.</li>
                        <li><b>Deberes:</b> Cuidar tu salud bucal, brindar datos veraces, respetar horarios y normas de
                            bioseguridad.</li>
                    </ul>
                </article>



                </article>
            </section>
        </main>
    </div>
@endsection
