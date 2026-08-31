@extends('layouts.admin')

@section('title', $customer->exists ? 'Editar cliente' : 'Nuevo cliente')

@section('page-title', $customer->exists ? 'Editar cliente' : 'Nuevo cliente')

@section('admin-content')
    <form method="POST" action="{{ $customer->exists ? route('admin.customers.update', $customer) : route('admin.customers.store') }}">
        @csrf
        @if ($customer->exists)
            @method('PUT')
        @endif

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label" for="first_name">Nombre *</label>
                <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $customer->first_name) }}"
                       class="form-control @error('first_name') is-invalid @enderror" required>
                @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="last_name">Apellido *</label>
                <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $customer->last_name) }}"
                       class="form-control @error('last_name') is-invalid @enderror" required>
                @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="email">Email *</label>
                <input type="email" id="email" name="email" value="{{ old('email', $customer->email) }}"
                       class="form-control @error('email') is-invalid @enderror" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="status">Estado *</label>
                <select id="status" name="status" class="form-select @error('status') is-invalid @enderror">
                    @foreach ($statuses as $statusOption)
                        <option value="{{ $statusOption->value }}" @selected(old('status', $customer->status?->value) === $statusOption->value)>
                            {{ $statusOption->label() }}
                        </option>
                    @endforeach
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="company_name">Empresa</label>
                <input type="text" id="company_name" name="company_name" value="{{ old('company_name', $customer->company_name) }}"
                       class="form-control @error('company_name') is-invalid @enderror">
                @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="business_name">Razón social</label>
                <input type="text" id="business_name" name="business_name" value="{{ old('business_name', $customer->business_name) }}"
                       class="form-control @error('business_name') is-invalid @enderror">
                @error('business_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="tax_id">CUIT</label>
                <input type="text" id="tax_id" name="tax_id" value="{{ old('tax_id', $customer->tax_id) }}"
                       class="form-control @error('tax_id') is-invalid @enderror">
                @error('tax_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="registered_at">Fecha de alta</label>
                <input type="date" id="registered_at" name="registered_at"
                       value="{{ old('registered_at', $customer->registered_at?->format('Y-m-d')) }}"
                       class="form-control @error('registered_at') is-invalid @enderror">
                @error('registered_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="phone">Teléfono</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone', $customer->phone) }}"
                       class="form-control @error('phone') is-invalid @enderror">
                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="whatsapp">WhatsApp</label>
                <input type="tel" id="whatsapp" name="whatsapp" value="{{ old('whatsapp', $customer->whatsapp) }}"
                       class="form-control @error('whatsapp') is-invalid @enderror">
                @error('whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="address">Dirección</label>
                <input type="text" id="address" name="address" value="{{ old('address', $customer->address) }}"
                       class="form-control @error('address') is-invalid @enderror">
                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="city">Ciudad</label>
                <input type="text" id="city" name="city" value="{{ old('city', $customer->city) }}"
                       class="form-control @error('city') is-invalid @enderror">
                @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="state">Provincia</label>
                <input type="text" id="state" name="state" value="{{ old('state', $customer->state) }}"
                       class="form-control @error('state') is-invalid @enderror">
                @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="country">País</label>
                <input type="text" id="country" name="country" value="{{ old('country', $customer->country) }}"
                       class="form-control @error('country') is-invalid @enderror">
                @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="postal_code">Código postal</label>
                <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $customer->postal_code) }}"
                       class="form-control @error('postal_code') is-invalid @enderror">
                @error('postal_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label" for="notes">Notas</label>
                <textarea id="notes" name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $customer->notes) }}</textarea>
                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-brand">
                {{ $customer->exists ? 'Guardar cambios' : 'Crear cliente' }}
            </button>
            <a href="{{ $customer->exists ? route('admin.customers.show', $customer) : route('admin.customers.index') }}"
               class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
@endsection
