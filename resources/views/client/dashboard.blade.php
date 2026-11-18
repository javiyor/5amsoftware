@extends('layouts.client')

@section('title', 'Mi cuenta')

@section('client-content')
    <h1 class="h3 mb-4">Hola, {{ Auth::user()->name }}</h1>

    <div class="card mb-3">
        <div class="card-body">
            <p class="text-muted mb-0">
                Acá vas a ver tus sistemas, cuotas y pagos. Esta sección se completa en la fase del portal del cliente.
            </p>
        </div>
    </div>

    <div class="card">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h2 class="h5 mb-1">¿Querés hablar con nuestro equipo?</h2>
                <p class="text-muted small mb-0">Elegí un día y horario disponible y contanos en qué podemos ayudarte.</p>
            </div>
            <a href="{{ route('client.appointments.index') }}" class="btn btn-brand">Solicitar una cita</a>
        </div>
    </div>
@endsection
