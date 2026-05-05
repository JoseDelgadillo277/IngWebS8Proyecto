<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fuentes -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- App (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- CSS adicional -->
    <link rel="stylesheet" href="{{ asset('css/login-bg.css') }}">

    <!-- Estilos con equilibrio visual -->
    <style>
        :root {
            --bg-glass: rgba(15, 23, 42, 0.55);
            --glass-border: rgba(255, 255, 255, 0.18);
            --label: #f8fafc;
            --muted: #e2e8f0;
            --input-bg: rgba(255, 255, 255, 0.98);
            --input-fg: #0f172a;
            --focus: #38bdf8;
            --focus-ring: rgba(56, 189, 248, 0.28);
            --link: #e0f2fe;
            --link-hover: #93c5fd;
            --logo-glow: rgba(20, 180, 195, 0.5);
        }

        html,
        body {
            height: 100%;
            background: transparent !important;
        }

        /* VIDEO DE FONDO */
        #bg-video {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
            filter: brightness(.82);
            pointer-events: none;
        }

        /* CAPA PRINCIPAL */
        .layer-top {
            position: relative;
            z-index: 10;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1.2rem;
            padding: 48px 16px;
        }

        /* TARJETA DE CRISTAL */
        .auth-card {
            position: relative;
            backdrop-filter: blur(14px) saturate(120%);
            -webkit-backdrop-filter: blur(14px) saturate(120%);
            background: var(--bg-glass);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            box-shadow: 0 14px 36px rgba(0, 0, 0, .45);
            padding-top: 1.2rem;
            /* espacio arriba */
            padding-bottom: 1.5rem;
            /* espacio abajo */
        }

        /* SUAVE DEGRADADO INTERNO */
        .auth-card::before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 16px;
            background: linear-gradient(180deg, rgba(0, 0, 0, .18) 0%, rgba(0, 0, 0, 0) 35%);
            pointer-events: none;
        }

        /* FONDO SUAVE */
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            z-index: 0;
            background: radial-gradient(closest-side, rgba(0, 0, 0, .1), rgba(0, 0, 0, .45));
            pointer-events: none;
        }

        /* LOGO */
        .logo-arte-dental {
            height: 130px;
            width: auto;
            filter: drop-shadow(0 0 14px var(--logo-glow));
            transition: transform .25s ease, filter .25s ease;
        }

        .logo-arte-dental:hover {
            transform: scale(1.03);
            filter: drop-shadow(0 0 18px rgba(20, 180, 195, .7));
        }

        /* LABELS */
        .auth-card label {
            color: var(--label) !important;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: .3px;
            text-shadow: 0 2px 3px rgba(0, 0, 0, .75);
            display: block;
            margin-top: 0.6rem;
        }

        /* INPUTS */
        .auth-card input[type="email"],
        .auth-card input[type="password"],
        .auth-card input[type="text"],
        .auth-card input[type="number"] {
            background: var(--input-bg) !important;
            color: var(--input-fg) !important;
            border: 1px solid rgba(255, 255, 255, .7) !important;
            border-radius: 10px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .06) inset;
        }

        .auth-card input::placeholder {
            color: #64748b;
            opacity: 1;
        }

        .auth-card input:focus {
            outline: 2px solid var(--focus);
            box-shadow: 0 0 0 4px var(--focus-ring);
        }

        /* TEXTOS SECUNDARIOS */
        .auth-card input[type="checkbox"] {
            accent-color: var(--focus);
        }

        .auth-card .text-gray-600,
        .auth-card .text-gray-500,
        .auth-card .text-sm {
            color: var(--muted) !important;
            text-shadow: 0 1px 1px rgba(0, 0, 0, .55);
        }

        /* LINKS */
        .auth-card a {
            color: var(--link) !important;
            text-decoration: underline;
            text-decoration-color: rgba(238, 242, 255, .6);
        }

        .auth-card a:hover {
            color: var(--link-hover) !important;
        }

        /* BOTÓN */
        .auth-card button[type="submit"] {
            box-shadow: 0 4px 14px rgba(2, 132, 199, .18);
            margin-top: 0.8rem;
            /* más espacio arriba del botón */
            margin-bottom: 0.8rem;
            /* más espacio debajo del botón */
        }

        .auth-card button[type="submit"]:hover {
            box-shadow: 0 6px 18px rgba(2, 132, 199, .26);
        }
    </style>
</head>

<body class="font-sans text-gray-900 antialiased">

    {{-- VIDEO DE FONDO --}}
    <video id="bg-video" autoplay muted loop playsinline preload="metadata" poster="{{ asset('images/poster.jpg') }}">
        <source src="{{ asset('videos/fondo-login-1080p.webm') }}" type="video/webm">
        <source src="{{ asset('videos/fondo-login-1080p.mp4') }}" type="video/mp4">
    </video>

    {{-- CONTENIDO CENTRADO --}}
    <div class="layer-top">
        {{-- LOGO --}}
        <div class="flex justify-center mb-4">
            <img src="/images/logo.png?v={{ filemtime(public_path('images/logo.png')) }}" alt="Arte Dental"
                class="logo-arte-dental" loading="lazy">
        </div>

        {{-- TARJETA LOGIN --}}
        <div class="auth-card w-full sm:max-w-md px-6 py-6 overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>

</body>

</html>
