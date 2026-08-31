@extends('layouts.app')

@section('title', 'Sesión expirada')

@section('content')
    <div class="auth-shell">
        <div class="auth-card text-center">
            <div class="card p-4 p-md-5">
                <div class="display-4 fw-bold text-gradient mb-2">419</div>
                <h1 class="h5 fw-bold">La sesión expiró</h1>
                <p class="text-muted small">Tu sesión expiró por inactividad. Volvé a iniciar sesión.</p>
                <div>
                    <a href="{{ route('login') }}" class="btn btn-brand btn-sm">Iniciar sesión</a>
                </div>
            </div>
        </div>
    </div>
@endsection
