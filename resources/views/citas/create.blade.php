{{-- Formulario para registrar una nueva cita medica. --}}
@extends('layouts.app')

@section('hide_default_nav', true)
@section('title', 'Programar cita')

@section('content')
    @php
        // Metadatos opcionales (por si desde el backend envías turno/ayudante preasignados)
        $odoMeta = collect($odontologos ?? [])->map(
            fn($o) => [
                'id' => $o->id,
                'name' => $o->name ?? ($o->nombres ?? ''),
                'turno' => $o->turno ?? null,
                'ayudante' => $o->ayudante ?? null,
            ],
        );
    @endphp

    <style>
        :root {
            --glass: rgba(17, 24, 39, .55);
            --glass-border: rgba(255, 255, 255, .10);
            --muted: #c7d2fe;
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
            max-width: 1100px;
            margin: 26px auto 70px;
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

        .col-8 {
            grid-column: span 8
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

        .col-2 {
            grid-column: span 2
        }

        @media (max-width:980px) {

            .col-8,
            .col-6,
            .col-4,
            .col-3,
            .col-2 {
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

        input,
        select,
        textarea {
            width: 100%;
            padding: 12px 14px;
            border-radius: 12px;
            color: #eaf2ff;
            background: rgba(255, 255, 255, .08);
            border: 1px solid rgba(255, 255, 255, .16);
            outline: none;
            transition: .18s
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: rgba(59, 130, 246, .50);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .25);
            background: rgba(255, 255, 255, .12)
        }

        ::placeholder {
            color: #c7d2fe;
            opacity: .75
        }

        input[type="date"],
        input[type="time"] {
            color-scheme: dark
        }

        select,
        option {
            background: #0f1a33;
            color: #eaf2ff
        }

        option:checked,
        option:hover {
            background: #1b2a4d linear-gradient(#1b2a4d, #1b2a4d)
        }

        .row-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 14px;
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
            background:
                radial-gradient(120% 120% at 0% 0%, rgba(99, 102, 241, .22), transparent 60%),
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

        .chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px
        }

        .chip {
            padding: 8px 10px;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            user-select: none;
            border: 1px solid rgba(255, 255, 255, .18);
            color: #e7eeff;
            background: rgba(255, 255, 255, .08)
        }

        .chip:hover {
            background: rgba(59, 130, 246, .22);
            box-shadow: var(--glow-soft)
        }

        .odo-card {
            padding: 14px;
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, .14);
            background: rgba(255, 255, 255, .06)
        }

        .odo-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .38rem .62rem;
            border: 1px solid rgba(255, 255, 255, .2);
            border-radius: 9999px;
            font-size: .78rem;
            color: #e7efff;
            background: rgba(255, 255, 255, .08)
        }

        /* Forzar tema oscuro en inputs (autofill/hover) */
        input,
        select,
        textarea {
            background-color: rgba(255, 255, 255, .08) !important;
            color: #eaf2ff !important;
            border-color: rgba(255, 255, 255, .16) !important;
            background-clip: padding-box !important;
            -webkit-text-fill-color: #eaf2ff !important;
        }

        input:focus,
        select:focus,
        textarea:focus {
            background-color: rgba(255, 255, 255, .12) !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .25) !important;
            border-color: rgba(59, 130, 246, .50) !important;
        }

        input:-webkit-autofill,
        select:-webkit-autofill,
        textarea:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 1000px rgba(255, 255, 255, .08) inset !important;
            box-shadow: 0 0 0 1000px rgba(255, 255, 255, .08) inset !important;
            -webkit-text-fill-color: #eaf2ff !important;
            caret-color: #eaf2ff !important;
        }

        input:-webkit-autofill:focus,
        select:-webkit-autofill:focus,
        textarea:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 1000px rgba(255, 255, 255, .12) inset !important;
            box-shadow: 0 0 0 1000px rgba(255, 255, 255, .12) inset !important;
        }

        input[type="date"]::-webkit-datetime-edit,
        input[type="time"]::-webkit-datetime-edit {
            color: #eaf2ff !important
        }

        input[type="date"]::-webkit-calendar-picker-indicator,
        input[type="time"]::-webkit-calendar-picker-indicator {
            filter: invert(1) opacity(.85)
        }
    </style>

    <div class="dash-bg"></div>

    <div class="wrap">
        {{-- CABECERA --}}
        <div class="glass head">
            <div>
                <div class="crumb">Citas / Nueva</div>
                <h1 class="title">Programar cita</h1>
                <p class="lead">Elige al paciente, fecha y horas. Te mostraré los odontólogos disponibles.</p>
            </div>
            <div style="display:flex;align-items:center;gap:10px">
                <a class="btn ghost" href="{{ route('dashboard') }}">← Volver al Panel</a>
                <img class="logo" src="/images/logo.png" alt="ArteDental">
            </div>
        </div>

        {{-- ERRORES --}}
        @if ($errors->any())
            <div class="glass card" style="border-left:4px solid #ef4444">
                <strong>Corrige lo siguiente:</strong>
                <ul style="margin:8px 0 0 18px">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORMULARIO --}}
        <form class="glass card" method="POST" action="{{ route('citas.store') }}" id="formCita" autocomplete="off">
            @csrf

            <div class="grid">
                <div class="col-8">
                    <label>Paciente</label>
                    <select name="paciente_id" required>
                        <option value="">Seleccione…</option>
                        @foreach ($pacientes as $p)
                            <option value="{{ $p->id }}" @selected(old('paciente_id') == $p->id)>
                                {{ $p->nombre }} {{ $p->apellido }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-4">
                    <label>Estado</label>
                    <select name="estado">
                        <option value="programada" @selected(old('estado') == 'programada')>Programada</option>
                        <option value="atendida" @selected(old('estado') == 'atendida')>Atendida</option>
                        <option value="cancelada" @selected(old('estado') == 'cancelada')>Cancelada</option>
                    </select>
                </div>

                <div class="col-4">
                    <label>Fecha</label>
                    <input type="date" name="fecha" value="{{ old('fecha') }}" required>
                </div>

                <div class="col-4">
                    <label>Hora inicio</label>
                    <input type="time" name="hora_inicio" value="{{ old('hora_inicio') }}" required>
                    <div class="chips" style="margin-top:8px">
                        @foreach (['08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '15:00', '15:30', '16:00', '16:30'] as $h)
                            <span class="chip" data-fill="hora_inicio">{{ $h }}</span>
                        @endforeach
                    </div>
                </div>

                <div class="col-4">
                    <label>Hora fin</label>
                    <input type="time" name="hora_fin" value="{{ old('hora_fin') }}" required>
                    <div class="chips" style="margin-top:8px">
                        @foreach (['09:00', '09:30', '10:00', '10:30', '11:30', '12:00', '15:30', '16:00', '16:30', '17:00'] as $h)
                            <span class="chip" data-fill="hora_fin">{{ $h }}</span>
                        @endforeach
                    </div>
                </div>

                <div class="col-12" style="margin-top:6px"></div>

                {{-- ODONTÓLOGO / ASISTENTES --}}
                <div class="col-8">
                    <label>Odontólogo (según disponibilidad)</label>
                    <select name="odontologo_id" id="odontologo_id" required>
                        <option value="">Selecciona fecha y horas…</option>
                    </select>
                    <div id="ayudantes_box" class="hint"></div>
                    <div id="odonto_hint" class="hint" style="color:#fca5a5"></div>
                </div>

                <div class="col-4">
                    <div class="odo-card">
                        <div class="odo-row">
                            <span class="badge">Turno: <strong id="turnoTxt">—</strong></span>
                            <span class="badge">Ayudante: <strong id="ayudanteTxt">—</strong></span>
                        </div>
                        <div class="hint" style="margin-top:8px">Se completa cuando elijas un odontólogo.</div>
                    </div>
                </div>

                <div class="col-6">
                    <label>Motivo</label>
                    <input type="text" name="motivo" value="{{ old('motivo') }}"
                        placeholder="Ej: Control, limpieza, tratamiento…">
                </div>

                <div class="col-6">
                    <label>Notas</label>
                    <input type="text" name="notas" value="{{ old('notas') }}"
                        placeholder="Comentarios para la atención">
                </div>
            </div>

            <div class="row-actions">
                <a class="btn ghost" href="{{ route('dashboard') }}">Cancelar</a>
                <button class="btn primary" type="submit">💾 Guardar cita</button>
            </div>
        </form>
    </div>

    <script>
        // Chips de hora
        document.querySelectorAll('.chip[data-fill]').forEach(ch => {
            ch.addEventListener('click', () => {
                const target = ch.getAttribute('data-fill');
                const input = document.querySelector(`[name="${target}"]`);
                if (input) {
                    input.value = ch.textContent.trim();
                    input.dispatchEvent(new Event('change'));
                }
            });
        });

        // ====== Cargar odontólogos disponibles (maneja errores HTML, JSON y sesión) ======
        async function cargarOdontologos() {
            const fecha = document.querySelector('[name="fecha"]').value;
            const ini = document.querySelector('[name="hora_inicio"]').value;
            const fin = document.querySelector('[name="hora_fin"]').value;
            const sel = document.getElementById('odontologo_id');
            const ayudB = document.getElementById('ayudantes_box');
            const hint = document.getElementById('odonto_hint');

            ayudB.textContent = '';
            hint.textContent = '';

            if (!fecha || !ini || !fin) {
                sel.innerHTML = '<option value="">Selecciona fecha y horas…</option>';
                return;
            }

            sel.innerHTML = '<option value="">Buscando disponibles…</option>';

            try {
                const params = new URLSearchParams({
                    fecha,
                    inicio: ini,
                    fin
                });
                const url = '{{ route('citas.odontologos.disponibles') }}?' + params.toString();

                const res = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin' // envía cookie de sesión
                });

                // Lee texto y trata de parsear JSON (si retorna un HTML de error lo detectamos)
                const raw = await res.text();
                let data = null;
                try {
                    data = JSON.parse(raw);
                } catch {
                    data = null;
                }

                if (!res.ok) {
                    const msg = (data && (data.message || data.error)) ?
                        (data.message || data.error) :
                        'Error al cargar disponibles';
                    sel.innerHTML = '<option value="">Error al cargar disponibles</option>';
                    hint.textContent = msg;
                    return;
                }

                if (!Array.isArray(data) || data.length === 0) {
                    sel.innerHTML = '<option value="">No hay odontólogos disponibles</option>';
                    return;
                }

                sel.innerHTML = '<option value="">Seleccione…</option>';
                let any = false;

                data.forEach(row => {
                    const labelBase = row.name ?? row.nombre ?? ('Odontólogo ' + row.id);
                    const labelAs = row.asistentes?.length ? ` — Asist.: ${row.asistentes.join(', ')}` : '';
                    const opt = document.createElement('option');
                    opt.value = row.id;

                    if (row.disponible) {
                        opt.textContent = labelBase + labelAs;
                        any = true;
                    } else {
                        opt.textContent = `${labelBase} — (No disponible: ${row.motivo})`;
                        opt.disabled = true;
                    }
                    sel.appendChild(opt);
                });

                if (!any) {
                    hint.textContent = 'No hay odontólogos disponibles para ese rango. Prueba otra hora o fecha.';
                }

                sel.onchange = () => {
                    const selRow = data.find(d => String(d.id) === String(sel.value));
                    ayudB.textContent = selRow && selRow.asistentes?.length ?
                        `Asistente(s) asignado(s): ${selRow.asistentes.join(', ')}` :
                        '';
                };

                sel.dispatchEvent(new Event('change'));
            } catch (e) {
                console.error(e);
                sel.innerHTML = '<option value="">Error al cargar disponibles</option>';
                hint.textContent = 'No se pudo cargar la disponibilidad.';
            }
        }

        document.querySelector('[name="fecha"]').addEventListener('change', cargarOdontologos);
        document.querySelector('[name="hora_inicio"]').addEventListener('change', cargarOdontologos);
        document.querySelector('[name="hora_fin"]').addEventListener('change', cargarOdontologos);

        // Info turno/ayudante (metadatos opcionales)
        const ODO_META = @json($odoMeta);
        const metaById = (id) => ODO_META.find(x => String(x.id) === String(id));
        const selOdo = document.getElementById('odontologo_id');
        const turnoTxt = document.getElementById('turnoTxt');
        const ayudanteTxt = document.getElementById('ayudanteTxt');

        selOdo.addEventListener('change', () => {
            const meta = metaById(selOdo.value);
            turnoTxt.textContent = meta?.turno ?? '—';
            ayudanteTxt.textContent = meta?.ayudante ?? '—';
        });

        // Validación rápida fin > inicio
        document.getElementById('formCita').addEventListener('submit', (e) => {
            const ini = document.querySelector('[name="hora_inicio"]').value;
            const fin = document.querySelector('[name="hora_fin"]').value;
            if (ini && fin && fin <= ini) {
                e.preventDefault();
                alert('⚠️ La hora fin debe ser mayor que la hora inicio.');
            }
        });

        // Si venimos con valores ya llenos
        window.addEventListener('DOMContentLoaded', () => {
            const f = document.querySelector('[name="fecha"]').value;
            const i = document.querySelector('[name="hora_inicio"]').value;
            const x = document.querySelector('[name="hora_fin"]').value;
            if (f && i && x) cargarOdontologos();
        });
    </script>
@endsection
