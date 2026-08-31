@extends('layouts.admin')

@section('title', 'Editar plan')

@section('page-title', 'Editar plan: ' . $plan->name)

@section('admin-content')
    <a href="{{ route('admin.systems.show', $system) }}" class="small text-decoration-none">&larr; Volver a {{ $system->name }}</a>

    <form method="POST" action="{{ route('admin.plans.update', [$system, $plan]) }}" class="mt-3">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label" for="name">Nombre *</label>
                <input type="text" id="name" name="name" value="{{ old('name', $plan->name) }}"
                       class="form-control @error('name') is-invalid @enderror" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label" for="slug">Slug</label>
                <input type="text" id="slug" name="slug" value="{{ old('slug', $plan->slug) }}"
                       class="form-control @error('slug') is-invalid @enderror">
                @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label" for="description">Descripción</label>
                <textarea id="description" name="description" rows="2" class="form-control @error('description') is-invalid @enderror">{{ old('description', $plan->description) }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="price">Precio mensual (ARS) *</label>
                <input type="number" step="0.01" min="0" id="price" name="price" value="{{ old('price', $plan->price) }}"
                       class="form-control @error('price') is-invalid @enderror" required>
                @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="setup_price">Precio de instalación (ARS)</label>
                <input type="number" step="0.01" min="0" id="setup_price" name="setup_price" value="{{ old('setup_price', $plan->setup_price) }}"
                       class="form-control @error('setup_price') is-invalid @enderror">
                @error('setup_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label" for="billing_interval_days">Ciclo (días)</label>
                <input type="number" min="1" id="billing_interval_days" name="billing_interval_days" value="{{ old('billing_interval_days', $plan->billing_interval_days) }}"
                       class="form-control @error('billing_interval_days') is-invalid @enderror">
                @error('billing_interval_days')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label" for="grace_days">Días de gracia</label>
                <input type="number" min="0" id="grace_days" name="grace_days" value="{{ old('grace_days', $plan->grace_days) }}"
                       class="form-control @error('grace_days') is-invalid @enderror">
                @error('grace_days')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label" for="max_users">Máx. usuarios</label>
                <input type="number" min="1" id="max_users" name="max_users" value="{{ old('max_users', $plan->max_users) }}"
                       class="form-control @error('max_users') is-invalid @enderror" placeholder="Ilimitado">
                @error('max_users')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label" for="sort_order">Orden</label>
                <input type="number" min="0" id="sort_order" name="sort_order" value="{{ old('sort_order', $plan->sort_order) }}"
                       class="form-control @error('sort_order') is-invalid @enderror">
                @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-8 d-flex align-items-end">
                <div class="form-check form-switch">
                    <input type="hidden" name="is_active" value="0">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                           @checked(old('is_active', $plan->is_active))>
                    <label class="form-check-label" for="is_active">Plan activo (vendible)</label>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-brand">Guardar cambios</button>
            <a href="{{ route('admin.systems.show', $system) }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
@endsection
