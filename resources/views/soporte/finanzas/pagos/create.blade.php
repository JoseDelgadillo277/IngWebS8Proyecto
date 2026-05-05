@extends('layouts.app')
@section('hide_default_nav', true)
@section('title', 'Registrar Pago')

@section('content')
    <style>
        :root {
            --bg1: rgba(6, 11, 28, .86);
            --bg2: rgba(6, 11, 28, .58);
            --glass: rgba(17, 24, 39, .62);
            --glass-strong: rgba(17, 24, 39, .74);
            --b: rgba(255, 255, 255, .12);
            --txt: #eaf2ff;
            --mut: #cbd5e1;
            --gold: #fbbf24;
            --gold2: #f59e0b;
            --shadow: 0 18px 40px rgba(0, 0, 0, .38);
            --radius: 22px;
            --field-bg: rgba(255, 255, 255, .06);
            --field-bg-focus: rgba(255, 255, 255, .10);
        }

        /* Fondo */
        .bg {
            position: fixed;
            inset: 0;
            z-index: -3;
            background:
                linear-gradient(180deg, var(--bg1), var(--bg2)),
                url('/images/bg-dashboard.jpg') center/cover no-repeat
        }

        .noise {
            position: fixed;
            inset: 0;
            z-index: -2;
            opacity: .08;
            pointer-events: none;
            mix-blend-mode: overlay;
            background-image: radial-gradient(#fff 1px, transparent 1px);
            background-size: 3px 3px;
        }

        /* Marca + Volver */
        .brand {
            position: fixed;
            top: 86px;
            left: 22px;
            z-index: 20;
            display: flex;
            align-items: center;
            gap: 10px
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

        /* Layout */
        .wrap {
            min-height: 100dvh;
            display: grid;
            place-items: start center;
            padding: clamp(80px, 10vw, 120px) 18px 96px
        }

        .panel {
            width: min(820px, 96vw);
            border: 1px solid var(--b);
            border-radius: var(--radius);
            background: var(--glass);
            backdrop-filter: blur(14px) saturate(120%);
            box-shadow: var(--shadow);
            overflow: hidden
        }

        /* Header */
        .hero {
            display: flex;
            gap: 10px;
            align-items: center;
            justify-content: space-between;
            padding: 18px 22px;
            border-bottom: 1px solid var(--b);
            color: #fff
        }

        .hero h1 {
            margin: 0;
            font-weight: 900;
            font-size: 20px
        }

        .mut {
            color: var(--mut)
        }

        .content {
            padding: 18px 22px;
            color: var(--txt);
            display: grid;
            gap: 14px
        }

        hr {
            border: none;
            border-top: 1px solid var(--b);
            margin: 6px 0 2px
        }

        .row {
            display: grid;
            gap: 14px;
            grid-template-columns: 1fr
        }

        @media(min-width:760px) {
            .row {
                grid-template-columns: 1fr 1fr
            }
        }

        label {
            color: #e5e7eb;
            font-size: .9rem;
            margin-bottom: 6px;
            display: block
        }

        /* ====== CAMPOS CRISTALINOS (sin blancos) ====== */
        input,
        select,
        textarea {
            width: 100%;
            padding: 12px 14px;
            border-radius: 14px;
            border: 1px solid var(--b);
            background: var(--field-bg) !important;
            /* 👈 fuerza bg oscuro */
            color: #fff !important;
            outline: none;
            transition: .2s ease;
            appearance: none;
        }

        /* Placeholders y selects invalid */
        ::placeholder {
            color: rgba(226, 232, 240, .75) !important;
        }

        select:has(option[value=""]):invalid {
            color: rgba(226, 232, 240, .75) !important;
        }

        /* Number sin flechas */
        input[type=number]::-webkit-outer-spin-button,
        input[type=number]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }

        /* Date en oscuro + icono */
        input[type="date"] {
            color-scheme: dark;
            background: var(--field-bg) !important;
        }

        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1) opacity(.85);
        }

        /* Focus dorado + fondo más intenso */
        input:focus,
        select:focus,
        textarea:focus {
            border-color: rgba(251, 191, 36, .45) !important;
            box-shadow: 0 0 0 4px rgba(251, 191, 36, .20);
            background: var(--glass-strong) !important;
        }

        /* Autofill Chrome/Edge en oscuro (clave para quitar blanco) */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        select:-webkit-autofill,
        textarea:-webkit-autofill {
            -webkit-text-fill-color: #fff !important;
            -webkit-box-shadow: 0 0 0px 1000px var(--field-bg) inset !important;
            box-shadow: 0 0 0px 1000px var(--field-bg) inset !important;
            transition: background-color 9999s ease-in-out 0s;
        }

        /* Botones */
        .actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 4px
        }

        .btn {
            padding: 10px 16px;
            border-radius: 12px;
            border: 1px solid var(--b);
            color: #fff;
            text-decoration: none;
            background: rgba(255, 255, 255, .08);
            backdrop-filter: blur(6px);
            transition: .2s;
        }

        .btn:hover {
            background: rgba(255, 255, 255, .12)
        }

        .btn-primary {
            background: linear-gradient(180deg, var(--gold), var(--gold2));
            border-color: rgba(251, 191, 36, .45);
            font-weight: 800;
        }

        .btn-primary:hover {
            filter: brightness(1.05)
        }

        .error {
            color: #fecaca;
            font-size: .85rem
        }
    </style>

    <div class="bg"></div>
    <div class="noise"></div>

    <a href="{{ route('dashboard') }}" class="btn-back">← Volver al panel</a>
    <div class="brand"><img src="/images/logo.png" alt="ArteDental"></div>

    <div class="wrap">
        <main class="panel">
            <div class="hero">
                <h1>💳 Registrar Pago</h1>
                <span class="mut">Moneda: <b>S/ PEN</b></span>
            </div>

            <form class="content" method="POST" action="{{ route('soporte.finanzas.pagos.store') }}" autocomplete="off"
                spellcheck="false">
                @csrf
                <!-- Moneda fija (oculta) -->
                <input type="hidden" name="moneda" value="PEN">

                <hr>

                <div class="row">
                    <div>
                        <label>Paciente</label>
                        <select name="paciente_id" required>
                            <option value="" selected disabled>— Seleccione —</option>
                            @foreach ($pacientes as $p)
                                <option value="{{ $p->id }}">{{ $p->label }}</option>
                            @endforeach
                        </select>
                        @error('paciente_id')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label>Fecha</label>
                        <input type="date" name="fecha" value="{{ now()->toDateString() }}" required>
                        @error('fecha')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div>
                        <label>Documento</label>
                        <select name="documento_tipo" required>
                            <option value="boleta">Boleta</option>
                            <option value="factura">Factura</option>
                            <option value="recibo">Recibo</option>
                        </select>
                        @error('documento_tipo')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label>N° Documento (opcional)</label>
                        <input type="text" name="documento_numero" maxlength="30" placeholder="Ejem: B001-12345">
                        @error('documento_numero')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div>
                        <label>Concepto</label>
                        <input type="text" name="concepto" maxlength="120" required
                            placeholder="Pago de consulta / Tratamiento X">
                        @error('concepto')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label>Monto (S/)</label>
                        <input type="number" step="0.01" min="0.10" name="monto" required placeholder="0.00">
                        @error('monto')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div>
                        <label>Medio de pago</label>
                        <select name="medio_pago" required>
                            <option value="" selected disabled>— Seleccione —</option>
                            <option value="efectivo">Efectivo</option>
                            <option value="tarjeta">Tarjeta</option>
                            <option value="transferencia">Transferencia</option>
                            <option value="yape">Yape</option>
                            <option value="plin">Plin</option>
                        </select>
                        @error('medio_pago')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label>N° de operación (si aplica)</label>
                        <input type="text" name="numero_operacion" maxlength="40" placeholder="ID transacción / voucher">
                        @error('numero_operacion')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div>
                        <label>Observación</label>
                        <input type="text" name="observacion" maxlength="255" placeholder="Nota interna (opcional)">
                        @error('observacion')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div></div>
                </div>

                <div class="actions">
                    <a class="btn" href="{{ route('soporte.finanzas.index') }}">Cancelar</a>
                    <button class="btn btn-primary" type="submit">Guardar pago</button>
                </div>
            </form>
        </main>
    </div>
@endsection
