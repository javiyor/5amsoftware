@extends('layouts.guest')

@section('title', 'Iniciar sesión')

@section('auth-content')
    <h1 class="h4 mb-1">Iniciar sesión</h1>
    <p class="text-muted small mb-4">Ingresá a tu cuenta para continuar.</p>

    @if ($errors->any())
        <div class="alert alert-danger py-2 small">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login.attempt') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
        </div>

        <div class="mb-3">
            <div class="d-flex justify-content-between">
                <label for="password" class="form-label">Contraseña</label>
                <a href="{{ route('password.request') }}" class="small text-decoration-none">¿Olvidaste tu contraseña?</a>
            </div>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required autocomplete="current-password">
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="remember" name="remember" value="1">
            <label class="form-check-label" for="remember">Recordarme</label>
        </div>

        <button type="submit" class="btn btn-brand w-100">Ingresar</button>
    </form>
@endsection
