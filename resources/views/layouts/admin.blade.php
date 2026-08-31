@extends('layouts.app')

@section('body-class', 'admin-shell')

@section('content')
    <div class="d-lg-flex">
        <aside class="admin-sidebar p-3 p-lg-4 col-lg-2 d-flex flex-column">
            <a href="{{ route('admin.dashboard') }}" class="navbar-brand mb-4 text-decoration-none d-flex align-items-center gap-2">
                @if (! empty($company['company_logo']))
                    <img src="{{ asset('storage/' . $company['company_logo']) }}" alt="{{ $company['company_name'] ?? config('app.name') }}" height="36" style="max-width: 160px; object-fit: contain;" onerror="this.onerror=null;this.src='{{ asset('assets/brand/logo.jpeg') }}'">
                @else
                    <img src="{{ asset('assets/brand/logo.jpeg') }}" alt="{{ $company['company_name'] ?? config('app.name') }}" height="36" style="max-width: 160px; object-fit: contain;">
                @endif
                <span class="d-block small fw-normal text-secondary ms-1">Panel de administración</span>
            </a>

            <ul class="nav flex-column gap-1">
                <li class="nav-item">
                    <a class="nav-link @if (request()->routeIs('admin.dashboard')) active @endif" href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>

                <li class="nav-item mt-3 text-uppercase small text-secondary fw-semibold px-2">Comercial</li>
                <li class="nav-item">
                    <a class="nav-link @if (request()->routeIs('admin.demos.*')) active @endif" href="{{ route('admin.demos.index') }}">Demos</a>
                </li>

                <li class="nav-item mt-3 text-uppercase small text-secondary fw-semibold px-2">Clientes</li>
                <li class="nav-item">
                    <a class="nav-link @if (request()->routeIs('admin.customers.*')) active @endif" href="{{ route('admin.customers.index') }}">Clientes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if (request()->routeIs('admin.contracts.*')) active @endif" href="{{ route('admin.contracts.index') }}">Contratos</a>
                </li>
                <li class="nav-item"><a class="nav-link disabled" href="#">Licencias</a></li>

                <li class="nav-item mt-3 text-uppercase small text-secondary fw-semibold px-2">Facturación</li>
                <li class="nav-item"><a class="nav-link disabled" href="#">Cuotas</a></li>
                <li class="nav-item"><a class="nav-link disabled" href="#">Pagos</a></li>

                <li class="nav-item mt-3 text-uppercase small text-secondary fw-semibold px-2">Contenido</li>
                <li class="nav-item">
                    <a class="nav-link @if (request()->routeIs('admin.systems.*', 'admin.plans.*')) active @endif" href="{{ route('admin.systems.index') }}">Sistemas y planes</a>
                </li>
                <li class="nav-item"><a class="nav-link disabled" href="#">Landing</a></li>

                <li class="nav-item mt-3 text-uppercase small text-secondary fw-semibold px-2">Configuración</li>
                <li class="nav-item"><a class="nav-link disabled" href="#">Empresa</a></li>
                <li class="nav-item"><a class="nav-link disabled" href="#">Usuarios</a></li>
                <li class="nav-item"><a class="nav-link disabled" href="#">Roles</a></li>
                <li class="nav-item"><a class="nav-link disabled" href="#">Parámetros</a></li>
                <li class="nav-item"><a class="nav-link disabled" href="#">Mercado Pago</a></li>
            </ul>
        </aside>

        <main class="admin-content flex-grow-1">
            <nav class="navbar navbar-light bg-white border-bottom px-3 px-lg-4">
                <div class="d-flex w-100 justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge text-bg-light border">@yield('page-title', 'Dashboard')</span>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('password.change') }}">Cambiar contraseña</a>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Cerrar sesión</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="p-3 p-lg-4">
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                @endif

                @yield('admin-content')
            </div>
        </main>
    </div>
@endsection
