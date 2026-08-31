@extends('layouts.app')

@section('title', 'Demasiadas solicitudes')

@section('content')
    <div class="auth-shell">
        <div class="auth-card text-center">
            <div class="card p-4 p-md-5">
                <div class="display-4 fw-bold text-gradient mb-2">429</div>
                <h1 class="h5 fw-bold">Demasiadas solicitudes</h1>
                <p class="text-muted small">Intentaste demasiadas veces. Esperá un momento y volvé a intentarlo.</p>
                <div>
                    <a href="{{ route('home') }}" class="btn btn-brand btn-sm">Volver al inicio</a>
                </div>
            </div>
        </div>
    </div>
@endsection
