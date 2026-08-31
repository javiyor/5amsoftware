@extends('layouts.guest')

@section('title', 'Cambiar contraseña')

@section('auth-content')
    <h1 class="h4 mb-1">Cambiar contraseña</h1>
    <p class="text-muted small mb-4">Ingresá tu contraseña actual y definí una nueva.</p>

    @if (session('status'))
        <div class="alert alert-success py-2 small">{{ session('status') }}</div>
    @endif

    @if (session('warning'))
        <div class="alert alert-warning py-2 small">{{ session('warning') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger py-2 small">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.change.update') }}">
        @csrf
        <div class="mb-3">
            <label for="current_password" class="form-label">Contraseña actual</label>
            <input type="password" class="form-control" id="current_password" name="current_password" required autocomplete="current-password">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Nueva contraseña</label>
            <input type="password" class="form-control" id="password" name="password" required autocomplete="new-password">
            <div class="form-text">Mínimo 8 caracteres, con letras, números y símbolos.</div>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirmar nueva contraseña</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
        </div>

        <button type="submit" class="btn btn-brand w-100">Guardar contraseña</button>
    </form>
@endsection
