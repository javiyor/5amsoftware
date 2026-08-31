@extends('layouts.app')

@section('title', 'Servicio en mantenimiento')

@section('content')
    <div class="auth-shell">
        <div class="auth-card text-center">
            <div class="card p-4 p-md-5">
                <div class="display-4 fw-bold text-gradient mb-2">503</div>
                <h1 class="h5 fw-bold">Estamos en mantenimiento</h1>
                <p class="text-muted small">Volvé a intentarlo en unos minutos.</p>
                <div>
                    <a href="{{ route('home') }}" class="btn btn-brand btn-sm">Reintentar</a>
                </div>
            </div>
        </div>
    </div>
@endsection
