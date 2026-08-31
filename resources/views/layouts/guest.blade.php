@extends('layouts.app')

@section('body-class', 'auth-shell')

@section('content')
    <div class="auth-card">
        <div class="text-center mb-4">
            <a href="{{ route('home') }}" class="text-decoration-none">
                @if (! empty($company['company_logo']))
                    <img src="{{ asset('storage/' . $company['company_logo']) }}" alt="{{ $company['company_name'] ?? config('app.name') }}" height="48" style="max-width: 200px; object-fit: contain;" onerror="this.onerror=null;this.src='{{ asset('assets/brand/logo.jpeg') }}'">
                @else
                    <img src="{{ asset('assets/brand/logo.jpeg') }}" alt="{{ $company['company_name'] ?? config('app.name') }}" height="48" style="max-width: 200px; object-fit: contain;">
                @endif
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
