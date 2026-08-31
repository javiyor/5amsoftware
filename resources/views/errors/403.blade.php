@extends('layouts.app')

@section('title', 'Acceso denegado')

@section('content')
    <div class="auth-shell">
        <div class="auth-card text-center">
            <div class="card p-4 p-md-5">
                <div class="display-4 fw-bold text-gradient mb-2">403</div>
                <h1 class="h5 fw-bold">Acceso denegado</h1>
                <p class="text-muted small">No tenés permiso para ver esta página.</p>
                <div>
                    <a href="{{ route('home') }}" class="btn btn-brand btn-sm">Volver al inicio</a>
                </div>
            </div>
        </div>
    </div>
@endsection
