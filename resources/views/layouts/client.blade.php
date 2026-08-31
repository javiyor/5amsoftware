@extends('layouts.app')

@section('content')
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('client.dashboard') }}">
                {{ $company['company_name'] ?? config('app.name') }}
                <span class="d-block small fw-normal text-muted">Portal del cliente</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#clientNav" aria-controls="clientNav" aria-expanded="false" aria-label="Menú">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="clientNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <li class="nav-item"><a class="nav-link" href="{{ route('client.dashboard') }}">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link disabled" href="#">Mis sistemas</a></li>
                    <li class="nav-item"><a class="nav-link disabled" href="#">Cuotas</a></li>
                    <li class="nav-item"><a class="nav-link disabled" href="#">Pagos</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('password.change') }}">Cambiar contraseña</a></li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary btn-sm ms-lg-2">Salir</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        @yield('client-content')
    </div>
@endsection
