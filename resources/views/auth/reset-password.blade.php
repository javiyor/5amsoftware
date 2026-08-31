@extends('layouts.guest')

@section('title', 'Nueva contraseña')

@section('auth-content')
    <h1 class="h4 mb-1">Nueva contraseña</h1>
    <p class="text-muted small mb-4">Definí una contraseña segura para tu cuenta.</p>

    @if ($errors->any())
        <div class="alert alert-danger py-2 small">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

        <div class="mb-3">
            <label for="password" class="form-label">Nueva contraseña</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required autocomplete="new-password">
            <div class="form-text">Mínimo 8 caracteres, con letras, números y símbolos.</div>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
        </div>

        <button type="submit" class="btn btn-brand w-100">Guardar contraseña</button>
    </form>
@endsection
