@extends('layouts.app')

@section('title', 'Página no encontrada')

@section('content')
    <div class="auth-shell">
        <div class="auth-card text-center">
            <div class="card p-4 p-md-5">
                <div class="display-4 fw-bold text-gradient mb-2">404</div>
                <h1 class="h5 fw-bold">Página no encontrada</h1>
                <p class="text-muted small">El contenido que buscás no existe o fue movido.</p>
                <div>
                    <a href="{{ route('home') }}" class="btn btn-brand btn-sm">Volver al inicio</a>
                </div>
            </div>
        </div>
    </div>
@endsection
