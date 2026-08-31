@extends('layouts.landing')

@section('title', $system->name . ' | ' . ($company['company_name'] ?? config('app.name')))
@section('meta_description', $system->seo_description ?: $system->short_description)
@section('meta_keywords', $system->name)
@section('canonical', route('systems.show', $system))
@section('og_type', 'article')
@section('og_title', $system->name)
@section('og_description', $system->seo_description ?: $system->short_description)

@section('landing-content')
    <section class="hero-section hero-section-compact">
        <div class="container">
            <nav aria-label="breadcrumb" class="small mb-3">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('home') }}#sistemas">Sistemas</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $system->name }}</li>
                </ol>
            </nav>
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold mb-3">{{ $system->name }}</h1>
                    <p class="lead text-muted mb-0">{{ $system->short_description }}</p>
                </div>
                <div class="col-lg-4">
                    @if ($system->price_from)
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4 text-center">
                                <div class="small text-muted">Abono mensual desde</div>
                                <div class="display-6 fw-bold text-brand">{{ number_format($system->price_from, 0, ',', '.') }} ARS</div>
                                @if ($system->installation_price)
                                    <div class="small text-muted mt-1">+ instalación {{ number_format($system->installation_price, 0, ',', '.') }} ARS</div>
                                @endif
                                <a href="#demo" class="btn btn-brand w-100 mt-3">Pedir demo gratuita</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section class="landing-section">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-7">
                    <h2 class="h4 fw-bold mb-3">¿Qué incluye?</h2>
                    <p class="text-muted">{{ $system->description }}</p>

                    @if ($system->features->isNotEmpty())
                        <h3 class="h5 fw-bold mt-5 mb-3">Características</h3>
                        <ul class="list-unstyled row g-2">
                            @foreach ($system->features as $feature)
                                <li class="col-md-6 d-flex gap-2">
                                    <span class="text-brand">&#10003;</span>
                                    <span>
                                        <strong>{{ $feature->name }}</strong>
                                        @if ($feature->description)
                                            <div class="small text-muted">{{ $feature->description }}</div>
                                        @endif
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="col-lg-5">
                    <h2 class="h4 fw-bold mb-3">Planes</h2>
                    <div class="d-flex flex-column gap-3">
                        @forelse ($system->plans as $plan)
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h3 class="h6 fw-bold mb-1">{{ $plan->name }}</h3>
                                            <div class="small text-muted">{{ $plan->description }}</div>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold text-brand">{{ number_format($plan->price, 0, ',', '.') }} ARS<span class="small text-muted fw-normal">/mes</span></div>
                                            @if ($plan->setup_price)
                                                <div class="small text-muted">+ {{ number_format($plan->setup_price, 0, ',', '.') }} ARS instalación</div>
                                            @endif
                                        </div>
                                    </div>
                                    @if ($plan->max_users)
                                        <div class="small text-muted mt-2">Hasta {{ $plan->max_users }} usuarios</div>
                                    @endif
                                    <a href="#demo" class="btn btn-outline-dark btn-sm mt-3">Consultar</a>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-light border text-center mb-0">Consultá los planes disponibles por WhatsApp.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="landing-section bg-white" id="demo">
        <div class="container" style="max-width: 640px;">
            <div class="card p-4 p-md-5">
                <h2 class="h4 fw-bold mb-1">Pedí una demo de {{ $system->name }}</h2>
                <p class="text-muted small mb-4">Completá el formulario y coordinamos una demostración personalizada.</p>
                @include('partials.demo-form', ['system' => $system])
            </div>
        </div>
    </section>
@endsection
