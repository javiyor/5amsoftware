@extends('layouts.landing')

@section('title', 'Software que simplifica la gestión de tu empresa')

@section('landing-content')
    {{-- HERO --}}
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <span class="badge rounded-pill text-bg-light border mb-3">Sistemas informáticos para tu negocio</span>
                    <h1 class="display-4 fw-bold mb-3">
                        Software que simplifica la <span class="text-gradient">gestión de tu empresa</span>
                    </h1>
                    <p class="lead text-muted mb-4">
                        Vendemos, licenciamos y mantenemos sistemas informáticos diseñados para que tu organización
                        trabaje más rápido, con menos errores y con total control.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="#sistemas" class="btn btn-brand btn-lg px-4">VER SISTEMAS</a>
                        <a href="#demo" class="btn btn-outline-dark btn-lg px-4">PEDIR DEMO GRATIS</a>
                    </div>
                </div>
                <div class="col-lg-5 d-none d-lg-block">
                    <div class="card p-4 bg-white">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="rounded-3 bg-brand-primary bg-opacity-10 p-3 text-brand fs-4">&#9881;</div>
                            <div>
                                <div class="fw-semibold">Soporte incluido</div>
                                <div class="small text-muted">Acompañamiento en cada etapa</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="rounded-3 bg-brand-secondary bg-opacity-10 p-3 text-brand-secondary fs-4">&#128274;</div>
                            <div>
                                <div class="fw-semibold">Licencias seguras</div>
                                <div class="small text-muted">Control y monitoreo permanente</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 bg-success bg-opacity-10 p-3 text-success fs-4">&#10003;</div>
                            <div>
                                <div class="fw-semibold">Implementación ágil</div>
                                <div class="small text-muted">De la demo a la operación en poco tiempo</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SISTEMAS --}}
    <section class="landing-section" id="sistemas">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="h3 fw-bold">Nuestros sistemas</h2>
                <p class="text-muted">Soluciones probadas, listas para tu empresa.</p>
            </div>
            <div class="row g-4">
                @forelse ($systems as $system)
                    <div class="col-md-6 col-lg-4">
                        <div class="card system-card h-100">
                            <div class="card-body p-4">
                                <h3 class="h5 fw-bold">{{ $system->name }}</h3>
                                <p class="text-muted small">{{ $system->short_description }}</p>
                                @if ($system->price_from)
                                    <div class="fw-bold text-brand mb-3">Desde {{ number_format($system->price_from, 0, ',', '.') }} ARS</div>
                                @endif
                                <div class="d-flex gap-2">
                                    <a href="{{ route('systems.show', $system) }}" class="btn btn-outline-dark btn-sm flex-fill">Información</a>
                                    <a href="{{ route('systems.show', $system) }}#demo" class="btn btn-brand btn-sm flex-fill">Pedir demo</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-light border text-center">Próximamente vas a encontrar acá nuestros sistemas.</div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- BENEFICIOS --}}
    <section class="landing-section bg-white" id="beneficios">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="h3 fw-bold">Beneficios</h2>
                <p class="text-muted">Por qué cientos de empresas eligen trabajar con nosotros.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="card feature-card h-100 p-3">
                        <div class="card-body">
                            <div class="fs-4 text-brand mb-2">&#9881;</div>
                            <h3 class="h6 fw-bold">Adaptado a tu negocio</h3>
                            <p class="small text-muted mb-0">Cada sistema se configura según tu operación.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card feature-card h-100 p-3">
                        <div class="card-body">
                            <div class="fs-4 text-brand-secondary mb-2">&#128200;</div>
                            <h3 class="h6 fw-bold">Control total</h3>
                            <p class="small text-muted mb-0">Reportes y métricas claras para decidir mejor.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card feature-card h-100 p-3">
                        <div class="card-body">
                            <div class="fs-4 text-brand mb-2">&#128737;</div>
                            <h3 class="h6 fw-bold">Seguridad</h3>
                            <p class="small text-muted mb-0">Datos protegidos y accesos controlados.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card feature-card h-100 p-3">
                        <div class="card-body">
                            <div class="fs-4 text-brand-secondary mb-2">&#128176;</div>
                            <h3 class="h6 fw-bold">Abonos simples</h3>
                            <p class="small text-muted mb-0">Facturación mensual clara y sin sorpresas.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- PROCESO --}}
    <section class="landing-section" id="proceso">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="h3 fw-bold">¿Cómo empezamos?</h2>
            </div>
            <div class="row g-4">
                @foreach ([
                    ['1', 'Elegís el sistema', 'Seleccionás la solución que mejor se adapta a tu empresa.'],
                    ['2', 'Solicitás una demo', 'Te mostramos cómo funciona en un caso real.'],
                    ['3', 'Lo configuramos', 'Ajustamos el sistema a tu operación.'],
                    ['4', 'Comenzás a usarlo', 'Soporte y facturación mensual sin complicaciones.'],
                ] as [$num, $title, $text])
                    <div class="col-md-6 col-lg-3">
                        <div class="d-flex gap-3">
                            <span class="badge rounded-circle bg-brand text-white fs-5" style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center;">{{ $num }}</span>
                            <div>
                                <h3 class="h6 fw-bold">{{ $title }}</h3>
                                <p class="small text-muted mb-0">{{ $text }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section class="landing-section bg-white" id="faq">
        <div class="container" style="max-width: 760px;">
            <div class="text-center mb-5">
                <h2 class="h3 fw-bold">Preguntas frecuentes</h2>
            </div>
            <div class="accordion" id="faqAccordion">
                @foreach ([
                    ['¿Cómo funciona el abono mensual?', 'Cada sistema se abona con cuotas mensuales claras. Podés pagar online de forma segura o por transferencia.'],
                    ['¿Qué pasa si no puedo pagar a tiempo?', 'Tenés un período de gracia. Si el atraso se extiende, el servicio se suspende temporalmente y se reactiva automáticamente al regularizar.'],
                    ['¿Incluye soporte?', 'Sí. Nuestros planes incluyen soporte y acompañamiento durante toda la vigencia.'],
                ] as [$q, $a])
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-{{ $loop->index }}" aria-expanded="false">
                                {{ $q }}
                            </button>
                        </h3>
                        <div id="faq-{{ $loop->index }}" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body small text-muted">{{ $a }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- DEMO / CONTACTO --}}
    <section class="landing-section" id="demo">
        <div class="container" style="max-width: 640px;">
            <div class="card p-4 p-md-5 text-center">
                <h2 class="h4 fw-bold mb-2">Pedí una demo gratuita</h2>
                <p class="text-muted small mb-4">Completá el formulario y nos comunicaremos a la brevedad.</p>
                @include('partials.demo-form')
            </div>
        </div>
    </section>
@endsection
