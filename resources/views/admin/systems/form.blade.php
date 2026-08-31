@extends('layouts.admin')

@section('title', $system->exists ? 'Editar sistema' : 'Nuevo sistema')

@section('page-title', $system->exists ? 'Editar sistema' : 'Nuevo sistema')

@section('admin-content')
    <form method="POST" action="{{ $system->exists ? route('admin.systems.update', $system) : route('admin.systems.store') }}">
        @csrf
        @if ($system->exists)
            @method('PUT')
        @endif

        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label" for="name">Nombre *</label>
                <input type="text" id="name" name="name" value="{{ old('name', $system->name) }}"
                       class="form-control @error('name') is-invalid @enderror" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label" for="slug">Slug</label>
                <input type="text" id="slug" name="slug" value="{{ old('slug', $system->slug) }}"
                       class="form-control @error('slug') is-invalid @enderror"
                       placeholder="auto desde el nombre">
                @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label" for="short_description">Descripción corta *</label>
                <input type="text" id="short_description" name="short_description" value="{{ old('short_description', $system->short_description) }}"
                       class="form-control @error('short_description') is-invalid @enderror" required>
                @error('short_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label" for="description">Descripción completa *</label>
                <textarea id="description" name="description" rows="6" class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $system->description) }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="price_from">Precio desde (ARS)</label>
                <input type="number" step="0.01" min="0" id="price_from" name="price_from" value="{{ old('price_from', $system->price_from) }}"
                       class="form-control @error('price_from') is-invalid @enderror">
                @error('price_from')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="installation_price">Precio de instalación (ARS)</label>
                <input type="number" step="0.01" min="0" id="installation_price" name="installation_price" value="{{ old('installation_price', $system->installation_price) }}"
                       class="form-control @error('installation_price') is-invalid @enderror">
                @error('installation_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label" for="sort_order">Orden</label>
                <input type="number" min="0" id="sort_order" name="sort_order" value="{{ old('sort_order', $system->sort_order ?? 0) }}"
                       class="form-control @error('sort_order') is-invalid @enderror">
                @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Publicado</label>
                <div class="form-check form-switch mt-2">
                    <input type="hidden" name="is_published" value="0">
                    <input class="form-check-input" type="checkbox" name="is_published" id="is_published" value="1"
                           @checked(old('is_published', $system->is_published))>
                    <label class="form-check-label" for="is_published">Visible en la landing</label>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label">Destacado</label>
                <div class="form-check form-switch mt-2">
                    <input type="hidden" name="is_featured" value="0">
                    <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1"
                           @checked(old('is_featured', $system->is_featured))>
                    <label class="form-check-label" for="is_featured">Destacar en la portada</label>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="seo_title">Título SEO</label>
                <input type="text" id="seo_title" name="seo_title" value="{{ old('seo_title', $system->seo_title) }}"
                       class="form-control @error('seo_title') is-invalid @enderror">
                @error('seo_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="seo_description">Descripción SEO</label>
                <input type="text" id="seo_description" name="seo_description" value="{{ old('seo_description', $system->seo_description) }}"
                       class="form-control @error('seo_description') is-invalid @enderror">
                @error('seo_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-brand">
                {{ $system->exists ? 'Guardar cambios' : 'Crear sistema' }}
            </button>
            <a href="{{ $system->exists ? route('admin.systems.show', $system) : route('admin.systems.index') }}"
               class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
@endsection
