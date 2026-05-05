@extends('layouts.app')

@section('title', 'Procesos Estratégicos')
@section('content')
    <div class="dash-bg"></div>

    <div class="wrap glass card" style="max-width:800px; margin:50px auto; padding:30px;">
        <h1 style="color:#fff;">🧭 Procesos Estratégicos</h1>
        <p style="color:#cbd5e1;">Selecciona una opción para ver su contenido:</p>

        <div style="display:flex; flex-direction:column; gap:10px; margin-top:20px;">
            <a href="{{ route('estrategicos.planeamiento') }}" class="btn btn-blue">📊 Planeamiento Estratégico</a>
            <a href="{{ route('estrategicos.calidad') }}" class="btn btn-blue">✅ Gestión de Calidad</a>
            <a href="{{ route('estrategicos.innovacion') }}" class="btn btn-blue">💡 Innovación y Mejora</a>
        </div>
    </div>
@endsection
