@extends('layouts.admin')

@section('title', 'Perfil de empresa')

@section('page-title', 'Perfil de empresa')

@section('admin-content')
    <div class="mb-4">
        <h1 class="h3 mb-1">Perfil público</h1>
        <p class="text-muted mb-0">Estos datos aparecen en la sección “Conocenos” de la página principal.</p>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-4">
            <div class="card p-3 h-100"><div class="small text-muted">Visitas públicas totales</div><div class="fs-3 fw-bold">{{ number_format((int) ($visitCounts['total'] ?? 0)) }}</div></div>
        </div>
        <div class="col-sm-4">
            <div class="card p-3 h-100"><div class="small text-muted">Visitas a la página principal</div><div class="fs-3 fw-bold">{{ number_format((int) ($visitCounts['home'] ?? 0)) }}</div></div>
        </div>
        <div class="col-sm-4">
            <div class="card p-3 h-100"><div class="small text-muted">Visitas a productos</div><div class="fs-3 fw-bold">{{ number_format((int) ($visitCounts['systems'] ?? 0)) }}</div></div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.company-profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-lg-7">
                <div class="card h-100">
                    <div class="card-header bg-white"><strong>Datos personales y profesionales</strong></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="company_profile_name">Nombre *</label>
                            <input id="company_profile_name" name="company_profile_name" value="{{ old('company_profile_name', $settings['company_profile_name'] ?? '') }}"
                                   class="form-control @error('company_profile_name') is-invalid @enderror" required maxlength="180">
                            @error('company_profile_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="company_cuit">CUIT</label>
                            <input id="company_cuit" name="company_cuit" value="{{ old('company_cuit', $settings['company_cuit'] ?? '') }}"
                                   class="form-control @error('company_cuit') is-invalid @enderror" maxlength="30" placeholder="20-12345678-9">
                            @error('company_cuit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="company_address">Dirección</label>
                            <input id="company_address" name="company_address" value="{{ old('company_address', $settings['company_address'] ?? '') }}"
                                   class="form-control @error('company_address') is-invalid @enderror" maxlength="255">
                            @error('company_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="form-label" for="company_profession">Profesión / especialidad</label>
                            <input id="company_profession" name="company_profession" value="{{ old('company_profession', $settings['company_profession'] ?? '') }}"
                                   class="form-control @error('company_profession') is-invalid @enderror" maxlength="180"
                                   placeholder="Ej.: Desarrollo de software y consultoría tecnológica">
                            @error('company_profession')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card h-100">
                    <div class="card-header bg-white"><strong>Foto de presentación</strong></div>
                    <div class="card-body">
                        @if (! empty($settings['company_profile_photo']))
                            <img src="{{ asset('storage/' . $settings['company_profile_photo']) }}" alt="Foto actual del perfil"
                                 class="rounded-4 mb-3" style="width: 160px; height: 160px; object-fit: cover;">
                        @else
                            <div class="rounded-4 bg-light d-flex align-items-center justify-content-center text-muted mb-3"
                                 style="width: 160px; height: 160px;">Sin foto</div>
                        @endif
                        <label class="form-label" for="company_profile_photo">Subir foto</label>
                        <input id="company_profile_photo" type="file" name="company_profile_photo" accept="image/jpeg,image/png,image/webp"
                               class="form-control @error('company_profile_photo') is-invalid @enderror">
                        <div class="form-text">JPG, PNG o WebP. Máximo 5 MB.</div>
                        @error('company_profile_photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white"><strong>Video promocional</strong></div>
                    <div class="card-body">
                        <p class="small text-muted">Podés pegar un enlace público de YouTube/Vimeo o subir un video MP4, WebM o MOV.</p>
                        @if (! empty($settings['company_promo_video']) || ! empty($settings['company_promo_video_url']))
                            <div class="alert alert-light border d-flex justify-content-between align-items-center gap-3">
                                <span class="small">
                                    @if (! empty($settings['company_promo_video']))
                                        Video subido: <code>{{ basename($settings['company_promo_video']) }}</code>
                                    @else
                                        Hay un enlace de video promocional configurado.
                                    @endif
                                </span>
                                <label class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox" name="remove_promo_video" value="1" @checked(old('remove_promo_video'))>
                                    <span class="form-check-label">Quitar video actual</span>
                                </label>
                            </div>
                        @endif
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <label class="form-label" for="company_promo_video_url">Enlace promocional</label>
                                <input id="company_promo_video_url" type="url" name="company_promo_video_url"
                                       value="{{ old('company_promo_video_url', $settings['company_promo_video_url'] ?? '') }}"
                                       class="form-control @error('company_promo_video_url') is-invalid @enderror"
                                       placeholder="https://youtu.be/...">
                                @error('company_promo_video_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label" for="company_promo_video">O subir un archivo de video</label>
                                <input id="company_promo_video" type="file" name="company_promo_video" accept="video/mp4,video/webm,video/quicktime,.mp4,.webm,.mov"
                                       class="form-control @error('company_promo_video') is-invalid @enderror">
                                <div class="form-text">MP4, WebM o MOV. Máximo 40 MB.</div>
                                @error('company_promo_video')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2 mt-4">
            <button type="submit" class="btn btn-brand">Guardar perfil</button>
            <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn btn-outline-secondary">Ver página pública</a>
        </div>
    </form>
@endsection
