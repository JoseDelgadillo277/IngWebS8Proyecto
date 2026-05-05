<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'ArteDental'))</title>

    <!-- Fuentes -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Estilo base -->
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #0f172a;
            color: #e2e8f0;
            min-height: 100vh;
        }

        header {
            background: rgba(17, 24, 39, 0.85);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #f8fafc;
        }

        header h1 {
            font-weight: 700;
            font-size: 1.25rem;
            letter-spacing: .3px;
        }

        main {
            padding: 2rem;
        }

        footer {
            text-align: center;
            color: #94a3b8;
            font-size: 0.875rem;
            margin-top: 3rem;
        }
    </style>
</head>

<body class="antialiased">

    {{-- Encabezado general (solo si no está oculto) --}}
    @if (!View::hasSection('hide_default_nav'))
        <header>
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="ArteDental" class="h-8 w-auto">
                <h1>ArteDental</h1>
            </div>
            <nav class="flex items-center gap-3">
                <a href="{{ url('/dashboard') }}" class="text-slate-100 hover:text-sky-300 transition">Inicio</a>
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-slate-100 hover:text-red-400 transition">
                            Cerrar sesión
                        </button>
                    </form>
                @endauth
            </nav>
        </header>
    @endif

    <!-- Contenido dinámico -->
    <main>
        @yield('content')
    </main>

    <footer>
        © {{ date('Y') }} Clínica Arte Dental — Todos los derechos reservados.
    </footer>

</body>

</html>
