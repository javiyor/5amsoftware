@extends('layouts.guest')

@section('title', 'Restablecer contraseña')

@section('auth-content')
    <h1 class="h4 mb-1">Restablecer contraseña</h1>
    <p class="text-muted small mb-4">Ingresá tu correo y te enviaremos un enlace para restablecerla.</p>

    @if (session('status'))
        <div class="alert alert-success py-2 small">
            {{ session('status') }}
        </div>
    @endif

    @error('email')
        <div class="alert alert-danger py-2 small">{{ $message }}</div>
    @enderror

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email">
        </div>

        <button type="submit" class="btn btn-brand w-100">Enviar enlace</button>
    </form>
@endsection
