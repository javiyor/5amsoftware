@extends('layouts.guest')

@section('title', 'Verificar correo')

@section('auth-content')
    <h1 class="h4 mb-1">Verificá tu correo</h1>
    <p class="text-muted small mb-4">Te enviamos un enlace de verificación. Revisá tu bandeja de entrada.</p>

    @if (session('status'))
        <div class="alert alert-success py-2 small">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-brand w-100">Reenviar enlace</button>
    </form>
@endsection
