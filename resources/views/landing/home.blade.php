@extends('layouts.landing')

@section('title', '5AM Software — Sistemas que impulsan tu empresa')

@section('landing-content')
    {{-- HERO TECH PREMIUM --}}
    <section class="position-relative overflow-hidden" style="background: radial-gradient(900px 500px at 12% 8%, rgba(245,158,11,0.16), transparent 60%), radial-gradient(1100px 700px at 88% 15%, rgba(37,99,235,0.14), transparent 60%), linear-gradient(180deg, #020617 0%, #0f172a 55%, #111c34 100%); padding: 4.5rem 0 5.5rem;">
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(90deg, transparent 0%, rgba(245,158,11,0.04) 50%, transparent 100%); pointer-events: none;"></div>
        <div class="container position-relative">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <div class="d-inline-flex align-items-center gap-2 rounded-pill px-3 py-1 mb-3" style="background: rgba(245,158,11,0.12); border: 1px solid rgba(245,158,11,0.25);">
                        <span class="rounded-circle" style="width:8px;height:8px;background:#f59e0b; box-shadow: 0 0 10px rgba(245,158,11,0.6);"></span>
                        <span class="small fw-semibold" style="color:#fbbf24; letter-spacing: 0.04em;">SISTEMAS INFORMÁTICOS • 5AM SOFTWARE</span>
                    </div>
                    <div class="mb-4 d-flex align-items-center gap-3">
                        <img src="{{ asset('assets/brand/logo.jpeg') }}" alt="5AM Software" style="height: 64px; border-radius: 14px; background: white; padding: 7px; box-shadow: 0 12px 40px rgba(0,0,0,0.35);" onerror="this.src='{{ asset('storage/company/logo.jpeg') }}'">
                        <div class="d-none d-sm-block" style="width:1px; height:40px; background: rgba(255,255,255,0.15);"></div>
                        <span class="small d-none d-sm-block" style="color:#94a3b8;">Tecnología<br><strong style="color:white;">que simplifica</strong></span>
                    </div>
                    <h1 class="display-4 fw-bold mb-3" style="color:white; line-height: 1.05; letter-spacing: -0.02em;">
                        Software que<br>
                        <span style="background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 45%, #f97316 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">potencia tu empresa</span>
                    </h1>
                    <p class="lead mb-4" style="color:#cbd5e1; font-size: 1.15rem; line-height: 1.6;">
                        Vendemos, licenciamos y mantenemos sistemas a medida. De la demo a la operación en días, con soporte real y licencias seguras.
                    </p>
                    <div class="d-flex flex-wrap gap-3 mb-4">
                        <a href="#sistemas" class="btn btn-lg px-4 fw-bold" style="background: linear-gradient(135deg, #f59e0b, #fbbf24); border:0; color:#0f172a; box-shadow: 0 8px 24px rgba(245,158,11,0.35);">EXPLORAR SISTEMAS →</a>
                        <a href="#demo" class="btn btn-lg px-4 fw-semibold" style="background: rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.15); color:white; backdrop-filter: blur(8px);">Pedir demo gratis</a>
                    </div>
                    <div class="d-flex flex-wrap gap-4 small" style="color:#64748b;">
                        <span class="d-flex align-items-center gap-2"><span style="color:#10b981;">✔</span> Soporte incluido</span>
                        <span class="d-flex align-items-center gap-2"><span style="color:#10b981;">✔</span> Licencias seguras</span>
                        <span class="d-flex align-items-center gap-2"><span style="color:#10b981;">✔</span> Implementación ágil</span>
                    </div>
                </div>
                <div class="col-lg-5 d-none d-lg-block">
                    <div class="position-relative">
                        <div class="position-absolute" style="inset: -20px; background: radial-gradient(400px 300px at 50% 30%, rgba(245,158,11,0.12), transparent 70%); filter: blur(20px);"></div>
                        <div class="card border-0 position-relative" style="background: rgba(255,255,255,0.96); backdrop-filter: blur(12px); border-radius: 1.25rem; box-shadow: 0 24px 64px rgba(0,0,0,0.35), 0 0 0 1px rgba(255,255,255,0.2);">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <span class="small fw-bold" style="letter-spacing:0.08em; color:#64748b;">¿POR QUÉ 5AM?</span>
                                    <span class="badge rounded-pill" style="background:#0f172a; color:#fbbf24;">Tech premium</span>
                                </div>
                                @foreach ([
                                    ['&#9881;', '#f59e0b', 'rgba(245,158,11,0.14)', 'Soporte humano real', 'Te acompañamos en cada etapa, sin bots.'],
                                    ['&#128274;', '#2563eb', 'rgba(37,99,235,0.12)', 'Licencias blindadas', 'Control, monitoreo y activación segura.'],
                                    ['&#9889;', '#10b981', 'rgba(16,185,129,0.12)', 'De la demo a prod en días', 'Configuración ágil, sin fricción.'],
                                ] as [$icon, $color, $bg, $title, $desc])
                                    <div class="d-flex gap-3 {{ !$loop->last ? 'mb-4 pb-4 border-bottom' : '' }}" style="border-color: #f1f5f9 !important;">
                                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:52px;height:52px;background: {{ $bg }}; color: {{ $color }}; font-size:1.5rem;">{!! $icon !!}</div>
                                        <div>
                                            <div class="fw-bold" style="color:#0f172a;">{{ $title }}</div>
                                            <div class="small" style="color:#64748b;">{{ $desc }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SISTEMAS PREMIUM --}}
    <section class="py-5" id="sistemas" style="background: #f8fafc;">
        <div class="container py-4">
            <div class="text-center mb-5" style="max-width: 640px; margin: 0 auto;">
                <span class="badge rounded-pill px-3 py-2 mb-3" style="background: rgba(245,158,11,0.12); color:#b45309; border:1px solid rgba(245,158,11,0.2);">CATÁLOGO</span>
                <h2 class="fw-bold mb-2" style="color:#0f172a; letter-spacing:-0.02em; font-size: 2rem;">Nuestros sistemas</h2>
                <p style="color:#64748b;">Soluciones probadas, listas para escalar tu operación.</p>
            </div>
            <div class="row g-4">
                @forelse ($systems as $system)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 position-relative overflow-hidden" style="border-radius:1.1rem; box-shadow: 0 8px 28px rgba(15,23,42,0.08); transition: all 0.25s ease;">
                            <div style="height:4px; background: linear-gradient(90deg, #f59e0b, #2563eb);"></div>
                            <div class="card-body p-4">
                                <h3 class="h5 fw-bold mb-2" style="color:#0f172a;">{{ $system->name }}</h3>
                                <p class="small mb-3" style="color:#64748b; line-height:1.6;">{{ $system->short_description }}</p>
                                @if ($system->price_from)
                                    <div class="mb-3"><span class="small" style="color:#64748b;">desde</span> <span class="fw-bold" style="color:#f59e0b; font-size:1.15rem;">$ {{ number_format($system->price_from, 0, ',', '.') }}</span> <span class="small" style="color:#94a3b8;">ARS/mes</span></div>
                                @endif
                                <div class="d-flex gap-2">
                                    <a href="{{ route('systems.show', $system) }}" class="btn btn-sm flex-fill fw-semibold" style="border:1px solid #e2e8f0; color:#0f172a;">Información</a>
                                    <a href="{{ route('systems.show', $system) }}#demo" class="btn btn-sm flex-fill fw-bold" style="background:#0f172a; color:#fbbf24; border:0;">Pedir demo</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12"><div class="text-center py-5" style="color:#94a3b8;">Próximamente nuevos sistemas.</div></div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- BENEFICIOS DARK PREMIUM --}}
    <section class="py-5" id="beneficios" style="background: #0f172a;">
        <div class="container py-4">
            <div class="text-center mb-5">
                <span class="badge rounded-pill px-3 py-2 mb-3" style="background: rgba(255,255,255,0.08); color:#fbbf24; border:1px solid rgba(251,191,36,0.2);">VALOR REAL</span>
                <h2 class="fw-bold text-white mb-2" style="letter-spacing:-0.02em;">Beneficios que se notan</h2>
                <p style="color:#94a3b8;">Diseñado para operar, no solo para mostrar.</p>
            </div>
            <div class="row g-4">
                @foreach ([
                    ['&#9881;', 'Adaptado a tu negocio', 'Cada sistema se configura según tu operación, no al revés.'],
                    ['&#128200;', 'Control total', 'Reportes claros, métricas y trazabilidad completa.'],
                    ['&#128737;', 'Seguridad', 'Datos protegidos, accesos controlados y auditoría.'],
                    ['&#128176;', 'Abonos simples', 'Facturación mensual clara, sin letra chica.'],
                ] as [$icon, $title, $desc])
                    <div class="col-md-6 col-lg-3">
                        <div class="h-100 p-4 rounded-4" style="background: rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.08); backdrop-filter: blur(8px);">
                            <div class="rounded-3 d-flex align-items-center justify-content-center mb-3" style="width:48px;height:48px;background: rgba(245,158,11,0.14); color:#fbbf24; font-size:1.4rem; border:1px solid rgba(245,158,11,0.2);">{!! $icon !!}</div>
                            <h3 class="h6 fw-bold text-white mb-2">{{ $title }}</h3>
                            <p class="small mb-0" style="color:#94a3b8; line-height:1.6;">{{ $desc }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- PRESENTACIÓN Y VIDEO INSTITUCIONAL --}}
    @if (! empty($company['company_profile_photo']) || ! empty($company['company_profile_name']) || ! empty($company['company_profession']) || ! empty($company['company_cuit']) || ! empty($company['company_address']) || ! empty($company['company_promo_video']) || ! empty($company['company_promo_video_url']))
        <section class="py-5" id="conocenos" style="background: linear-gradient(180deg, #f8fafc, #fff);">
            <div class="container py-4">
                <div class="row align-items-center g-5">
                    <div class="col-lg-5">
                        <span class="badge rounded-pill px-3 py-2 mb-3" style="background:rgba(245,158,11,.12);color:#b45309;border:1px solid rgba(245,158,11,.2);">CONOCENOS</span>
                        <h2 class="fw-bold mb-3" style="color:#0f172a;">Tecnología con respaldo profesional</h2>
                        <div class="d-flex align-items-start gap-3 mb-4">
                            @if (! empty($company['company_profile_photo']))
                                <img src="{{ asset('storage/' . $company['company_profile_photo']) }}" alt="{{ $company['company_name'] ?? 'Perfil profesional' }}"
                                     class="rounded-circle flex-shrink-0" style="width:92px;height:92px;object-fit:cover;border:3px solid #fff;box-shadow:0 8px 24px rgba(15,23,42,.16);">
                            @endif
                            <div>
                                <h3 class="h4 fw-bold mb-1" style="color:#0f172a;">{{ $company['company_profile_name'] ?? $company['company_name'] ?? '5AM Software' }}</h3>
                                @if (! empty($company['company_profession']))
                                    <div class="fw-semibold" style="color:#b45309;">{{ $company['company_profession'] }}</div>
                                @endif
                            </div>
                        </div>
                        <dl class="row small mb-0">
                            @if (! empty($company['company_cuit']))
                                <dt class="col-sm-3" style="color:#64748b;">CUIT</dt>
                                <dd class="col-sm-9 fw-semibold" style="color:#0f172a;">{{ $company['company_cuit'] }}</dd>
                            @endif
                            @if (! empty($company['company_address']))
                                <dt class="col-sm-3" style="color:#64748b;">Dirección</dt>
                                <dd class="col-sm-9" style="color:#334155;">{{ $company['company_address'] }}</dd>
                            @endif
                        </dl>
                    </div>
                    <div class="col-lg-7">
                        @if (! empty($company['company_promo_video']) || ! empty($company['company_promo_video_url']))
                            <div class="p-2 rounded-4" style="background:linear-gradient(135deg,#f59e0b,#2563eb);box-shadow:0 20px 50px rgba(15,23,42,.16);">
                                <div class="bg-white rounded-4 p-2">
                                    @include('partials.promo-video', [
                                        'videoPath' => $company['company_promo_video'] ?? '',
                                        'videoUrl' => $company['company_promo_video_url'] ?? '',
                                        'title' => 'Presentación de ' . ($company['company_name'] ?? '5AM Software'),
                                    ])
                                </div>
                            </div>
                        @else
                            <div class="rounded-4 p-5 text-center" style="background:#0f172a;color:#cbd5e1;">
                                <div class="fs-1 mb-2" style="color:#fbbf24;">5AM</div>
                                <p class="mb-0">Experiencia, compromiso y tecnología para acompañar el crecimiento de tu empresa.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- PROCESO --}}
    <section class="py-5" id="proceso" style="background: #f8fafc;">
        <div class="container py-4">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-2" style="color:#0f172a;">¿Cómo empezamos?</h2>
                <p style="color:#64748b;">Cuatro pasos, sin vueltas.</p>
            </div>
            <div class="row g-4 position-relative">
                <div class="d-none d-lg-block position-absolute" style="top:20px; left:8%; right:8%; height:1px; background: linear-gradient(90deg, transparent, #f59e0b 20%, #2563eb 80%, transparent); opacity:0.3;"></div>
                @foreach ([
                    ['1', 'Elegís el sistema', 'La solución que encaja con tu empresa.'],
                    ['2', 'Solicitás demo', 'Te mostramos un caso real, sin humo.'],
                    ['3', 'Lo configuramos', 'Ajuste fino a tu operación.'],
                    ['4', 'Operás', 'Soporte y facturación sin fricción.'],
                ] as [$num, $title, $text])
                    <div class="col-md-6 col-lg-3">
                        <div class="text-center">
                            <div class="mx-auto d-flex align-items-center justify-content-center fw-bold mb-3 position-relative" style="width:44px;height:44px;border-radius:50%; background: linear-gradient(135deg, #f59e0b, #f97316); color:white; box-shadow: 0 6px 20px rgba(245,158,11,0.35);">{{ $num }}</div>
                            <h3 class="h6 fw-bold" style="color:#0f172a;">{{ $title }}</h3>
                            <p class="small mb-0" style="color:#64748b;">{{ $text }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section class="py-5 bg-white" id="faq">
        <div class="container py-4" style="max-width: 760px;">
            <div class="text-center mb-5">
                <h2 class="fw-bold" style="color:#0f172a;">Preguntas frecuentes</h2>
                <p style="color:#64748b;">Transparencia total.</p>
            </div>
            <div class="accordion" id="faqAccordion">
                @foreach ([
                    ['¿Cómo funciona el abono?', 'Cuotas mensuales claras. Pago online seguro o transferencia. Sin sorpresas.'],
                    ['¿Y si me atraso?', 'Tenés período de gracia. Si se extiende, se suspende y se reactiva al regularizar, automático.'],
                    ['¿Incluye soporte?', 'Sí, soporte humano durante toda la vigencia.'],
                ] as [$q, $a])
                    <div class="accordion-item border-0 mb-2" style="border-radius:0.9rem; overflow:hidden; box-shadow: 0 4px 16px rgba(15,23,42,0.06);">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq-{{ $loop->index }}" style="color:#0f172a;">{{ $q }}</button>
                        </h3>
                        <div id="faq-{{ $loop->index }}" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body small" style="color:#475569; background:#f8fafc;">{{ $a }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- DEMO PREMIUM --}}
    <section class="py-5" id="demo" style="background: linear-gradient(180deg, #0f172a 0%, #020617 100%);">
        <div class="container py-4" style="max-width: 640px;">
            <div class="text-center mb-4">
                <span class="badge rounded-pill px-3 py-2 mb-3" style="background: rgba(245,158,11,0.14); color:#fbbf24; border:1px solid rgba(245,158,11,0.25);">DEMO GRATUITA</span>
                <h2 class="fw-bold text-white mb-2">Probalo sin compromiso</h2>
                <p class="small" style="color:#94a3b8;">Completá el formulario y te contactamos en el día.</p>
            </div>
            <div class="card border-0 p-4 p-md-5" style="border-radius:1.25rem; background: rgba(255,255,255,0.97); box-shadow: 0 24px 64px rgba(0,0,0,0.35);">
                @include('partials.demo-form')
            </div>
        </div>
    </section>
@endsection
