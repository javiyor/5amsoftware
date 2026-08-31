@extends('layouts.app')

@section('content')
    <nav class="navbar navbar-expand-lg navbar-landing sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">
                @if (! empty($company['company_logo']))
                    <img src="{{ asset('storage/' . $company['company_logo']) }}" alt="{{ $company['company_name'] }}" height="32">
                @else
                    {{ $company['company_name'] ?? config('app.name') }}
                @endif
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#landingNav" aria-controls="landingNav" aria-expanded="false" aria-label="Menú">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="landingNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <li class="nav-item"><a class="nav-link" href="#sistemas">Sistemas</a></li>
                    <li class="nav-item"><a class="nav-link" href="#beneficios">Beneficios</a></li>
                    <li class="nav-item"><a class="nav-link" href="#demo">Demo gratuita</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contacto">Contacto</a></li>
                    <li class="nav-item">
                        <a class="btn btn-outline-dark btn-sm ms-lg-2" href="{{ route('login') }}">Acceso clientes</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @yield('landing-content')

    <footer class="footer-landing py-5 mt-5">
        <div class="container">
            <div class="row gy-4">
                <div class="col-md-4">
                    <span class="fw-bold fs-5 text-white">{{ $company['company_name'] ?? config('app.name') }}</span>
                    <p class="mt-2 small">
                        {{ $company['company_footer_text'] ?? 'Software que simplifica la gestión de tu empresa.' }}
                    </p>
                </div>
                <div class="col-md-4">
                    <h6 class="text-white">Contacto</h6>
                    <ul class="list-unstyled small mb-0">
                        @if (! empty($company['company_phone']))
                            <li>{{ $company['company_phone'] }}</li>
                        @endif
                        @if (! empty($company['company_whatsapp']))
                            <li>WhatsApp: {{ $company['company_whatsapp'] }}</li>
                        @endif
                        @if (! empty($company['company_email']))
                            <li>{{ $company['company_email'] }}</li>
                        @endif
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6 class="text-white">Seguinos</h6>
                    <div class="d-flex gap-3">
                        @if (! empty($company['company_instagram']))
                            <a href="{{ $company['company_instagram'] }}" target="_blank" rel="noopener">Instagram</a>
                        @endif
                        @if (! empty($company['company_facebook']))
                            <a href="{{ $company['company_facebook'] }}" target="_blank" rel="noopener">Facebook</a>
                        @endif
                        @if (! empty($company['company_linkedin']))
                            <a href="{{ $company['company_linkedin'] }}" target="_blank" rel="noopener">LinkedIn</a>
                        @endif
                    </div>
                </div>
            </div>
            <hr class="border-secondary my-4">
            <div class="d-flex flex-wrap justify-content-between small">
                <span>&copy; {{ date('Y') }} {{ $company['company_name'] ?? config('app.name') }}</span>
                <div>
                    <a href="#" class="me-3">Términos</a>
                    <a href="#">Política de privacidad</a>
                </div>
            </div>
        </div>
    </footer>
@endsection
