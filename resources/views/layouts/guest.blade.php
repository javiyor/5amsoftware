@extends('layouts.app')

@section('body-class', 'auth-shell')

@section('content')
    <div class="auth-card">
        <div class="text-center mb-4">
            <a href="{{ route('home') }}" class="text-decoration-none">
                <span class="fs-3 fw-bold text-brand">{{ $company['company_name'] ?? config('app.name') }}</span>
            </a>
        </div>

        <div class="card">
            <div class="card-body p-4 p-md-5">
                @yield('auth-content')
            </div>
        </div>

        <div class="text-center mt-3">
            <a href="{{ route('home') }}" class="small text-muted text-decoration-none">&larr; Volver al sitio</a>
        </div>
    </div>
@endsection
