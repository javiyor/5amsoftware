@extends('layouts.app')

@section('title', 'Error interno')

@section('content')
    <div class="auth-shell">
        <div class="auth-card text-center">
            <div class="card p-4 p-md-5">
                <div class="display-4 fw-bold text-gradient mb-2">500</div>
                <h1 class="h5 fw-bold">Ocurrió un error</h1>
                <p class="text-muted small">Algo salió mal. Nuestro equipo ya fue notificado.</p>
                <div>
                    <a href="{{ route('home') }}" class="btn btn-brand btn-sm">Volver al inicio</a>
                </div>
            </div>
        </div>
    </div>
@endsection
