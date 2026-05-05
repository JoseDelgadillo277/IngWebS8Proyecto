@extends('layouts.app')

@section('hide_default_nav', true)
@section('title', 'Editar paciente')

@section('content')
    <style>
        :root {
            --glass: rgba(17, 24, 39, .55);
            --glass-border: rgba(255, 255, 255, .10);
            --txt: #e5e7eb;
            --brand1: #2563eb;
            --brand2: #1d4ed8;
            --success: #22c55e;
            --shadow: 0 12px 30px rgba(0, 0, 0, .35);
            --glow: 0 0 18px rgba(59, 130, 246, .35);
            --glow-soft: 0 0 10px rgba(56, 189, 248, .28);
            --r: 16px;
        }

        .dash-bg {
            position: fixed;
            inset: 0;
            z-index: -2;
            background: linear-gradient(180deg, rgba(6, 11, 28, .70), rgba(6, 11, 28, .55)), url('/images/bg-dashboard.jpg') center/cover no-repeat;
        }

        .wrap {
            max-width: 1050px;
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
            text-shadow: var(--glow);
        }

        .crumb {
            color: #cbd5e1;
            font-size: .9rem;
        }

        .logo {
            height: 56px;
            width: auto;
            filter: drop-shadow(0 0 10px rgba(59, 130, 246, .6));
        }

        .card {
            padding: 20px;
            box-shadow: var(--glow-soft);
        }

        .lead {
            color: #c7d2fe;
            margin: 0 0 12px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 14px;
        }

        .col-12 {
            grid-column: span 12;
        }

        .col-6 {
            grid-column: span 6;
        }

        .col-4 {
            grid-column: span 4;
        }

        .col-3 {
            grid-column: span 3;
        }

        .col-1 {
            grid-column: span 1;
        }

        @media(max-width:920px) {

            .col-6,
            .col-4,
            .col-3,
            .col-1 {
                grid-column: span 12;
            }
        }

        label {
            display: block;
            margin: 4px 0 6px;
            color: #e5e7eb;
            font-weight: 700;
            font-size: .92rem;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 12px 14px;
            border-radius: 12px;
            color: #eaf2ff !important;
            -webkit-text-fill-color: #eaf2ff !important;
            background-color: rgba(255, 255, 255, .08) !important;
            border: 1px solid rgba(255, 255, 255, .16) !important;
            outline: none;
            transition: .18s;
        }

        input:focus,
        select:focus,
        textarea:focus {
            background-color: rgba(255, 255, 255, .12) !important;
            border-color: rgba(59, 130, 246, .50) !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .25) !important;
        }

        ::placeholder {
            color: #cbd5e1 !important;
            opacity: .85 !important;
        }

        input[type="date"] {
            color-scheme: dark;
        }

        select option {
            background: #0f1a33 !important;
            color: #eaf2ff !important;
        }

        .row-actions {
            display: flex;
            gap: 10px;
            margin-top: 12px;
            justify-content: flex-end;
            flex-wrap: wrap;
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
                rgba(255, 255, 255, .10);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--glow);
        }

        .btn.primary {
            background: linear-gradient(180deg, var(--brand1), var(--brand2));
            color: #fff;
        }

        .btn.ghost {
            background: rgba(255, 255, 255, .06);
            border-color: rgba(255, 255, 255, .18);
        }

        .alert-success {
            position: fixed;
            top: 30px;
            right: 30px;
            background: linear-gradient(90deg, var(--success), #16a34a);
            color: #fff;
            font-weight: 700;
            padding: 14px 20px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(34, 197, 94, .6);
            animation: slideIn .6s ease, fadeOut 4s 2.8s forwards;
            z-index: 9999;
        }

        @keyframes slideIn {
            from {
                transform: translateX(150%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: translateX(150%);
            }
        }
    </style>

    <div class="dash-bg"></div>

    @if (session('ok'))
        <div class="alert-success">✅ {{ session('ok') }}</div>
    @endif

    <div class="wrap">
        <div class="glass head">
            <div>
                <div class="crumb">Pacientes / Editar</div>
                <h1 class="title">Editar paciente</h1>
                <p class="lead">Actualiza la información del paciente.</p>
            </div>
            <div style="display:flex; align-items:center; gap:10px">
                <a class="btn ghost" href="{{ route('pacientes.index') }}">← Volver</a>
                <img class="logo" src="/images/logo.png" alt="ArteDental">
            </div>
        </div>

        <form class="glass card" method="POST" action="{{ route('pacientes.update', $paciente) }}" autocomplete="off">
            @csrf @method('PUT')

            <div class="grid">
                <div class="col-4"><label>DNI</label>
                    <input name="dni" maxlength="8" value="{{ old('dni', $paciente->dni) }}" required>
                </div>
                <div class="col-4"><label>Teléfono</label>
                    <input name="telefono" maxlength="9" value="{{ old('telefono', $paciente->telefono) }}">
                </div>
                <div class="col-4"><label>Email</label>
                    <input name="email" type="email" value="{{ old('email', $paciente->email) }}">
                </div>

                <div class="col-6"><label>Nombres</label>
                    <input name="nombre" value="{{ old('nombre', $paciente->nombre) }}" required>
                </div>
                <div class="col-6"><label>Apellidos</label>
                    <input name="apellido" value="{{ old('apellido', $paciente->apellido) }}" required>
                </div>

                <div class="col-6"><label>Fecha de nacimiento</label>
                    <input type="date" name="fecha_nacimiento"
                        value="{{ old('fecha_nacimiento', optional($paciente->fecha_nacimiento)->format('Y-m-d')) }}">
                </div>
                <div class="col-6"><label>Sexo</label>
                    <select name="sexo">
                        @php $sx = old('sexo', $paciente->sexo); @endphp
                        <option value="">Selecciona…</option>
                        <option value="M" {{ $sx === 'M' ? 'selected' : '' }}>Masculino</option>
                        <option value="F" {{ $sx === 'F' ? 'selected' : '' }}>Femenino</option>
                        <option value="O" {{ $sx === 'O' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>

                <div class="col-4"><label>Provincia</label>
                    <select id="provincia" name="provincia">
                        @php $prov = old('provincia', $paciente->provincia); @endphp
                        <option value="">Selecciona provincia…</option>
                        <option value="Huancayo" {{ $prov === 'Huancayo' ? 'selected' : '' }}>Huancayo</option>
                        <option value="Jauja" {{ $prov === 'Jauja' ? 'selected' : '' }}>Jauja</option>
                    </select>
                </div>

                <div class="col-4"><label>Distrito</label>
                    <select id="distrito" name="distrito" {{ $prov ? '' : 'disabled' }}>
                        <option value="">{{ $prov ? 'Selecciona distrito…' : 'Primero elige provincia…' }}</option>
                    </select>
                </div>

                <div class="col-3"><label>Calle / Jr. / Av.</label>
                    <input name="calle" value="{{ old('calle', $paciente->calle) }}" placeholder="Ej: Av. Real">
                </div>

                <div class="col-1"><label>N°</label>
                    <input name="numero" value="{{ old('numero', $paciente->numero) }}" placeholder="123">
                </div>

                <div class="col-12"><label>Dirección</label>
                    <input name="direccion" value="{{ old('direccion', $paciente->direccion) }}">
                </div>
            </div>

            {{-- 🔹 Botones finales (sin Registrar pago) --}}
            <div class="row-actions">
                <a class="btn ghost" href="{{ route('pacientes.index') }}">Cancelar</a>
                <button class="btn primary" type="submit">💾 Actualizar paciente</button>
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
        const currentProv = "{{ old('provincia', $paciente->provincia) }}";
        const currentDist = "{{ old('distrito', $paciente->distrito) }}";

        function fillDistricts(prov, selected) {
            selDist.innerHTML = '';
            if (!prov) {
                selDist.disabled = true;
                selDist.innerHTML = '<option value="">Primero elige provincia…</option>';
                return;
            }
            selDist.disabled = false;
            const opts = distritos[prov] || [];
            selDist.innerHTML = '<option value="">Selecciona distrito…</option>';
            opts.forEach(d => {
                const o = document.createElement('option');
                o.value = d;
                o.textContent = d;
                if (selected && selected === d) o.selected = true;
                selDist.appendChild(o);
            });
        }

        if (currentProv) {
            fillDistricts(currentProv, currentDist);
        }
        selProv.addEventListener('change', () => fillDistricts(selProv.value, null));
    </script>
@endsection
