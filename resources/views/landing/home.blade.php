@extends('layouts.landing')

@section('title', 'Software que simplifica la gestión de tu empresa')

@section('landing-content')
    {{-- HERO VISTOSO --}}
    <section class="hero-section position-relative overflow-hidden" style="background: radial-gradient(900px 500px at 15% 10%, rgba(245,158,11,0.18), transparent 60%), radial-gradient(800px 600px at 90% 20%, rgba(37,99,235,0.15), transparent 60%), linear-gradient(180deg, #0f172a 0%, #1e293b 100%); padding: 5rem 0 6rem;">
        <div class="container position-relative">
            <div class="row align-items-center g-5">
                <div class="col-lg-7 text-white">
                    <div class="d-inline-flex align-items-center gap-2 bg-white bg-opacity-10 border border-white border-opacity-10 rounded-pill px-3 py-1 mb-3">
                        <span class="bg-warning rounded-circle" style="width:8px;height:8px;display:inline-block;"></span>
                        <span class="small text-white-50">Sistemas informáticos para tu negocio</span>
                    </div>
                    <div class="mb-4">
                        <img src="{{ asset('assets/brand/logo.jpeg') }}" alt="5AM Software" style="height: 72px; border-radius: 12px; box-shadow: 0 8px 32px rgba(0,0,0,0.3); background: white; padding: 6px;" onerror="this.src='{{ asset('storage/company/logo.jpeg') }}'">
                    </div>
                    <h1 class="display-4 fw-bold mb-3 text-white">
                        Software que simplifica la <span style="background: linear-gradient(135deg, #f59e0b, #fbbf24); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">gestión de tu empresa</span>
                    </h1>
                    <p class="lead mb-4" style="color: #cbd5e1;">
                        Vendemos, licenciamos y mantenemos sistemas informáticos diseñados para que tu organización
                        trabaje más rápido, con menos errores y con total control.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="#sistemas" class="btn btn-lg px-4 fw-semibold" style="background: linear-gradient(135deg, #f59e0b, #fbbf24); border: 0; color: #0f172a;">VER SISTEMAS</a>
                        <a href="#demo" class="btn btn-outline-light btn-lg px-4">PEDIR DEMO GRATIS</a>
                    </div>
                    <div class="d-flex gap-4 mt-4 small" style="color: #94a3b8;">
                        <span>&#10003; Soporte incluido</span>
                        <span>&#10003; Licencias seguras</span>
                        <span>&#10003; Implementación ágil</span>
                    </div>
                </div>
                <div class="col-lg-5 d-none d-lg-block">
                    <div class="card p-4 border-0" style="background: rgba(255,255,255,0.97); backdrop-filter: blur(10px); border-radius: 1rem; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;background: rgba(245,158,11,0.15); color: #f59e0b; font-size: 1.4rem;">&#9881;</div>
                            <div>
                                <div class="fw-semibold" style="color: #0f172a;">Soporte incluido</div>
                                <div class="small text-muted">Acompañamiento en cada etapa</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;background: rgba(37,99,235,0.12); color: #2563eb; font-size: 1.4rem;">&#128274;</div>
                            <div>
                                <div class="fw-semibold" style="color: #0f172a;">Licencias seguras</div>
                                <div class="small text-muted">Control y monitoreo permanente</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;background: rgba(16,185,129,0.12); color: #10b981; font-size: 1.4rem;">&#10003;</div>
                            <div>
                                <div class="fw-semibold" style="color: #0f172a;">Implementación ágil</div>
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
