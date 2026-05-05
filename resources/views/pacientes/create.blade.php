{{-- Formulario para registrar un nuevo paciente. --}}
@extends('layouts.app')

@section('hide_default_nav', true)
@section('title', 'Nuevo paciente')

@section('content')
    <style>
        :root {
            --glass: rgba(17, 24, 39, .55);
            --glass-border: rgba(255, 255, 255, .10);
            --mut: #9aa4bf;
            --txt: #e5e7eb;
            --brand1: #2563eb;
            --brand2: #1d4ed8;
            --shadow: 0 12px 30px rgba(0, 0, 0, .35);
            --glow: 0 0 18px rgba(59, 130, 246, .35);
            --glow-soft: 0 0 10px rgba(56, 189, 248, .28);
            --r: 16px;
        }

        .dash-bg {
            position: fixed;
            inset: 0;
            z-index: -2;
            background:
                linear-gradient(180deg, rgba(6, 11, 28, .70), rgba(6, 11, 28, .55)),
                url('/images/bg-dashboard.jpg') center/cover no-repeat;
        }

        .wrap {
            max-width: 1050px;
            margin: 26px auto 60px;
            padding: 0 14px
        }

        .glass {
            backdrop-filter: blur(10px) saturate(120%);
            background: var(--glass);
            border: 1px solid var(--glass-border);
            border-radius: var(--r);
            box-shadow: var(--shadow)
        }

        .head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 18px 22px;
            margin-bottom: 14px;
            box-shadow: var(--glow-soft)
        }

        .title {
            margin: 0;
            font-weight: 900;
            color: #f8fafc;
            font-size: clamp(22px, 3.2vw, 32px);
            text-shadow: var(--glow)
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
            padding: 20px;
            box-shadow: var(--glow-soft)
        }

        .lead {
            color: #c7d2fe;
            margin: 0 0 12px
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 14px
        }

        .col-12 {
            grid-column: span 12
        }

        .col-6 {
            grid-column: span 6
        }

        .col-4 {
            grid-column: span 4
        }

        .col-3 {
            grid-column: span 3
        }

        .col-1 {
            grid-column: span 1
        }

        @media (max-width:920px) {

            .col-6,
            .col-4,
            .col-3,
            .col-1 {
                grid-column: span 12
            }
        }

        label {
            display: block;
            margin: 4px 0 6px;
            color: #e5e7eb;
            font-weight: 700;
            font-size: .92rem
        }

        .hint {
            color: #cbd5e1;
            font-size: .78rem;
            margin-top: 6px
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .34rem .62rem;
            border: 1px solid rgba(255, 255, 255, .2);
            border-radius: 9999px;
            font-size: .78rem;
            color: #e7efff;
            background: rgba(255, 255, 255, .08)
        }

        .badge.good {
            border-color: rgba(34, 197, 94, .5);
            color: #e3ffe9;
            background: rgba(34, 197, 94, .18)
        }

        .badge.bad {
            border-color: rgba(239, 68, 68, .5);
            color: #ffe2e2;
            background: rgba(239, 68, 68, .16)
        }

        .row-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 10px;
            justify-content: flex-end
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
                rgba(255, 255, 255, .10)
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--glow)
        }

        .btn.primary {
            background: linear-gradient(180deg, var(--brand1), var(--brand2));
            color: #fff
        }

        .btn.ghost {
            background: rgba(255, 255, 255, .06);
            border-color: rgba(255, 255, 255, .18)
        }

        /* Inputs cristalinos oscuros + autofill arreglado */
        input,
        select,
        textarea {
            width: 100%;
            padding: 12px 14px;
            border-radius: 12px;
            background-color: rgba(12, 18, 32, .38) !important;
            color: #eaf2ff !important;
            border: 1px solid rgba(255, 255, 255, .16) !important;
            outline: none;
            transition: .18s;
            -webkit-text-fill-color: #eaf2ff !important;
            background-clip: padding-box !important
        }

        input:focus,
        select:focus,
        textarea:focus {
            background-color: rgba(12, 18, 32, .50) !important;
            border-color: rgba(59, 130, 246, .50) !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .25) !important
        }

        ::placeholder {
            color: #cbd5e1 !important;
            opacity: .9 !important
        }

        select,
        option {
            background: #0f1a33 !important;
            color: #eaf2ff !important
        }

        input[type="date"] {
            color-scheme: dark
        }

        input[type="date"]::-webkit-datetime-edit {
            color: #eaf2ff !important
        }

        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1) opacity(.85)
        }

        input:-webkit-autofill,
        select:-webkit-autofill,
        textarea:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 1000px rgba(12, 18, 32, .38) inset !important;
            box-shadow: 0 0 0 1000px rgba(12, 18, 32, .38) inset !important;
            -webkit-text-fill-color: #eaf2ff !important
        }

        input:-webkit-autofill:focus,
        select:-webkit-autofill:focus,
        textarea:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 1000px rgba(12, 18, 32, .50) inset !important;
            box-shadow: 0 0 0 1000px rgba(12, 18, 32, .50) inset !important
        }

        /* Mensajes de error */
        .field-error {
            color: #fecaca;
            font-size: .85rem;
            margin-top: 6px
        }
    </style>

    <div class="dash-bg"></div>

    <div class="wrap">
        <div class="glass head">
            <div>
                <div class="crumb">Pacientes / Nuevo</div>
                <h1 class="title">Registrar nuevo paciente</h1>
                <p class="lead">Completa la información. Podrás editarla más adelante.</p>
            </div>
            <div style="display:flex; align-items:center; gap:10px">
                <a class="btn ghost" href="{{ route('dashboard') }}">← Volver al Panel</a>
                <img class="logo" src="/images/logo.png" alt="ArteDental">
            </div>
        </div>

        {{-- Alert general de errores (opcional) --}}
        @if ($errors->any())
            <div class="glass card"
                style="border-color:rgba(239,68,68,.45);background:rgba(239,68,68,.08);margin-bottom:10px">
                <strong>⚠️ Corrige los errores marcados.</strong>
            </div>
        @endif

        <form class="glass card" method="POST" action="{{ route('pacientes.store') }}" id="formPaciente"
            autocomplete="off">
            @csrf

            <div class="grid">
                {{-- DNI con old() + error --}}
                <div class="col-4">
                    <label for="dni">DNI</label>
                    <input id="dni" name="dni" maxlength="8" inputmode="numeric" placeholder="00000000" required
                        value="{{ old('dni') }}">
                    @error('dni')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                    <div class="hint"><span id="dniBadge" class="badge">Ingresa 8 dígitos</span></div>
                </div>

                <div class="col-4">
                    <label for="telefono">Teléfono</label>
                    <input id="telefono" name="telefono" maxlength="9" inputmode="numeric" placeholder="Ej: 987654321"
                        value="{{ old('telefono') }}">
                    @error('telefono')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                    <div class="hint"><span id="telBadge" class="badge">Ingresa 9 dígitos</span></div>
                </div>

                {{-- Email con old() + error --}}
                <div class="col-4">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" placeholder="correo@dominio.com"
                        value="{{ old('email') }}">
                    @error('email')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-6">
                    <label for="nombre">Nombres</label>
                    <input id="nombre" name="nombre" required value="{{ old('nombre') }}">
                    @error('nombre')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-6">
                    <label for="apellido">Apellidos</label>
                    <input id="apellido" name="apellido" required value="{{ old('apellido') }}">
                    @error('apellido')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-6">
                    <label for="fecha_nacimiento">Fecha de nacimiento</label>
                    <input id="fecha_nacimiento" type="date" name="fecha_nacimiento"
                        value="{{ old('fecha_nacimiento') }}">
                    @error('fecha_nacimiento')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-6">
                    <label for="sexo">Sexo</label>
                    <select id="sexo" name="sexo">
                        <option value="">Selecciona…</option>
                        <option value="M" @selected(old('sexo') === 'M')>Masculino</option>
                        <option value="F" @selected(old('sexo') === 'F')>Femenino</option>
                        <option value="O" @selected(old('sexo') === 'O')>Otro</option>
                    </select>
                    @error('sexo')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-4">
                    <label for="provincia">Provincia</label>
                    <select id="provincia" name="provincia" required>
                        <option value="">Selecciona provincia…</option>
                        <option value="Huancayo" @selected(old('provincia') === 'Huancayo')>Huancayo</option>
                        <option value="Jauja" @selected(old('provincia') === 'Jauja')>Jauja</option>
                    </select>
                    @error('provincia')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-4">
                    <label for="distrito">Distrito</label>
                    <select id="distrito" name="distrito" required {{ old('provincia') ? '' : 'disabled' }}>
                        @if (old('provincia'))
                            <option value="">Selecciona distrito…</option>
                            {{-- se rellenará también por JS al cambiar provincia, pero mostramos old() si existía --}}
                            <option value="{{ old('distrito') }}" selected>{{ old('distrito') }}</option>
                        @else
                            <option value="">Primero elige provincia…</option>
                        @endif
                    </select>
                    @error('distrito')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-3">
                    <label for="calle">Calle / Jr. / Av.</label>
                    <input id="calle" name="calle" placeholder="Ej: Av. Real" value="{{ old('calle') }}">
                    @error('calle')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-1">
                    <label for="numero">N°</label>
                    <input id="numero" name="numero" placeholder="123" value="{{ old('numero') }}">
                    @error('numero')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row-actions">
                <a class="btn ghost" href="{{ route('dashboard') }}">Cancelar</a>
                <button class="btn primary" id="btnGuardar" type="submit">💾 Guardar paciente</button>
            </div>
        </form>
    </div>

    <script>
        const distritos = {
            "Huancayo": ["Huancayo", "Chilca", "El Tambo", "Chupuro", "Cullhuas", "Hualhuas", "Huancán", "Pariahuanca",
                "Pilcomayo", "Pucará", "Quilcas", "Saño", "Sapallanga", "Viques"
            ],
            "Jauja": ["Jauja", "Apata", "Ataura", "El Mantaro", "Huamalí", "Huertas", "Marco", "Masma", "Molinos",
                "Monobamba", "Muqui", "Paca", "Paccha", "Parco", "Ricrán", "San Lorenzo", "Sincos", "Tunan Marca",
                "Yauli", "Yauyos"
            ]
        };

        const selProv = document.getElementById('provincia');
        const selDist = document.getElementById('distrito');

        function fillDistritos(prov, selected = '') {
            selDist.innerHTML = '';
            if (!prov) {
                selDist.disabled = true;
                selDist.innerHTML = '<option value="">Primero elige provincia…</option>';
                return;
            }
            selDist.disabled = false;
            selDist.innerHTML = '<option value="">Selecciona distrito…</option>';
            (distritos[prov] || []).forEach(d => {
                const o = document.createElement('option');
                o.value = o.textContent = d;
                if (selected && selected === d) o.selected = true;
                selDist.appendChild(o);
            });
        }

        // Rellenar distritos si hay old('provincia')
        @if (old('provincia'))
            fillDistritos(@json(old('provincia')), @json(old('distrito')));
        @endif

        selProv.addEventListener('change', () => {
            fillDistritos(selProv.value, '');
        });

        /* Validación DNI/Teléfono con badges */
        const dni = document.getElementById('dni');
        const tel = document.getElementById('telefono');
        const dniBadge = document.getElementById('dniBadge');
        const telBadge = document.getElementById('telBadge');

        function updateDniBadge() {
            const len = (dni.value || '').replace(/\D/g, '').length;
            dniBadge.textContent = len === 8 ? 'DNI correcto' : `Faltan dígitos (${8-len})`;
            dniBadge.className = 'badge ' + (len === 8 ? 'good' : 'bad');
        }

        function updateTelBadge() {
            const len = (tel.value || '').replace(/\D/g, '').length;
            telBadge.textContent = len === 9 ? 'Teléfono válido' : `Faltan dígitos (${9-len})`;
            telBadge.className = 'badge ' + (len === 9 ? 'good' : 'bad');
        }

        dni.addEventListener('input', () => {
            dni.value = dni.value.replace(/\D/g, '').slice(0, 8);
            updateDniBadge();
        });
        tel.addEventListener('input', () => {
            tel.value = tel.value.replace(/\D/g, '').slice(0, 9);
            updateTelBadge();
        });

        // Inicializar badges con old()
        updateDniBadge();
        updateTelBadge();

        /* Bloquea envío si DNI < 8 o Tel < 9 (si se ingresó) */
        document.getElementById('formPaciente').addEventListener('submit', e => {
            const errs = [];
            if ((dni.value || '').length !== 8) errs.push('El DNI debe tener 8 dígitos.');
            if (tel.value && (tel.value || '').length !== 9) errs.push('El teléfono debe tener 9 dígitos.');
            if (errs.length) {
                e.preventDefault();
                alert('⚠️ No se puede guardar:\n\n' + errs.join('\n'));
            }
        });
    </script>
@endsection
