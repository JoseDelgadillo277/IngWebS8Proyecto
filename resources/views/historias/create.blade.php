{{-- Formulario inicial para abrir la historia clinica de un paciente. --}}
@extends('layouts.app')

@section('hide_default_nav', true)
@section('title', 'Nueva historia clínica')

@section('content')
    <style>
        :root {
            --glass: rgba(17, 24, 39, .55);
            --glass-border: rgba(255, 255, 255, .10);
            --mut: #9aa4bf;
            --txt: #e5e7eb;
            --brand1: #2563eb;
            --brand2: #1d4ed8;
            --ok: #22c55e;
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
            margin: 26px auto 110px;
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
            font-size: .9rem
        }

        .logo {
            height: 56px;
            width: auto;
            filter: drop-shadow(0 0 10px rgba(59, 130, 246, .6))
        }

        .card {
            padding: 20px;
            box-shadow: var(--glow-soft);
        }

        .section-title {
            margin: 0 0 10px;
            color: #dbeafe;
            font-weight: 800
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

        @media(max-width:920px) {

            .col-6,
            .col-4,
            .col-3 {
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
            color: #eaf2ff !important;
            background-color: rgba(255, 255, 255, .08) !important;
            border: 1px solid rgba(255, 255, 255, .16) !important;
            outline: none;
            transition: .18s;
            -webkit-text-fill-color: #eaf2ff !important
        }

        input:focus,
        select:focus,
        textarea:focus {
            background-color: rgba(255, 255, 255, .12) !important;
            border-color: rgba(59, 130, 246, .50) !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .25) !important
        }

        textarea {
            min-height: 110px;
            resize: vertical
        }

        ::placeholder {
            color: #cbd5e1 !important;
            opacity: .85 !important
        }

        select option {
            background: #0f1a33 !important;
            color: #eaf2ff !important
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            border: 1px solid transparent;
            padding: 11px 16px;
            border-radius: 12px;
            font-weight: 800;
            text-decoration: none;
            color: #eaf3ff;
            background: radial-gradient(120% 120% at 0% 0%, rgba(99, 102, 241, .22), transparent 60%),
                radial-gradient(120% 120% at 100% 0%, rgba(56, 189, 248, .18), transparent 60%),
                rgba(255, 255, 255, .10);
            transition: .2s
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

        .error {
            color: #fecaca;
            font-size: .86rem;
            margin-top: 6px
        }
    </style>

    <div class="dash-bg"></div>

    <div class="wrap">
        <div class="glass head">
            <div>
                <div class="crumb">Pacientes / Historia clínica</div>
                <h1 class="title">Crear historia clínica – {{ $paciente->apellido }} {{ $paciente->nombre }}</h1>
            </div>
            <div style="display:flex;align-items:center;gap:10px">
                <a class="btn ghost" href="{{ route('historias.show', $paciente) }}">← Volver</a>
                <img class="logo" src="/images/logo.png" alt="ArteDental">
            </div>
        </div>

        <form class="glass card" method="POST" action="{{ route('historias.store', $paciente) }}" autocomplete="off">
            @csrf

            <h3 class="section-title">Asignación</h3>
            <div class="grid">
                <div class="col-6">
                    <label for="odontologo_id">Odontólogo</label>
                    <select id="odontologo_id" name="odontologo_id" required>
                        <option value="">— Seleccionar —</option>
                        @foreach ($odontologos as $doc)
                            <option value="{{ $doc->id }}" @selected(old('odontologo_id') == $doc->id)>
                                {{ $doc->name }} ({{ $doc->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('odontologo_id')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-6">
                    <label for="fecha_apertura">Fecha de apertura</label>
                    <input type="date" id="fecha_apertura" name="fecha_apertura"
                        value="{{ old('fecha_apertura', now()->toDateString()) }}">
                    @error('fecha_apertura')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <h3 class="section-title" style="margin-top:18px;">Evaluación</h3>
            <div class="grid">
                <div class="col-12">
                    <label for="motivo_consulta">Motivo de consulta</label>
                    <textarea id="motivo_consulta" name="motivo_consulta" placeholder="Describe el motivo principal...">{{ old('motivo_consulta') }}</textarea>
                    @error('motivo_consulta')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label for="diagnostico">Diagnóstico</label>
                    <textarea id="diagnostico" name="diagnostico" placeholder="Diagnóstico clínico y hallazgos...">{{ old('diagnostico') }}</textarea>
                    @error('diagnostico')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label for="plan_tratamiento">Plan de tratamiento</label>
                    <textarea id="plan_tratamiento" name="plan_tratamiento"
                        placeholder="Procedimientos propuestos, sesiones y consideraciones...">{{ old('plan_tratamiento') }}</textarea>
                    @error('plan_tratamiento')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <h3 class="section-title" style="margin-top:18px;">Antecedentes (opcional)</h3>
            <div class="grid">
                <div class="col-6">
                    <label for="alergias">Alergias</label>
                    <textarea id="alergias" name="alergias" placeholder="Medicamentos, anestésicos, alimentos...">{{ old('alergias') }}</textarea>
                </div>
                <div class="col-6">
                    <label for="medicamentos">Medicamentos actuales</label>
                    <textarea id="medicamentos" name="medicamentos" placeholder="Nombre y dosis si aplica...">{{ old('medicamentos') }}</textarea>
                </div>
                <div class="col-6">
                    <label for="antecedentes_personales">Antecedentes personales</label>
                    <textarea id="antecedentes_personales" name="antecedentes_personales">{{ old('antecedentes_personales') }}</textarea>
                </div>
                <div class="col-6">
                    <label for="antecedentes_familiares">Antecedentes familiares</label>
                    <textarea id="antecedentes_familiares" name="antecedentes_familiares">{{ old('antecedentes_familiares') }}</textarea>
                </div>
                <div class="col-12">
                    <label for="habitos">Hábitos</label>
                    <textarea id="habitos" name="habitos" placeholder="Bruxismo, tabaquismo, etc.">{{ old('habitos') }}</textarea>
                </div>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:16px">
                <a class="btn ghost" href="{{ route('historias.show', $paciente) }}">Cancelar</a>
                <button class="btn primary" type="submit">💾 Guardar historia</button>
            </div>
        </form>
    </div>
@endsection
