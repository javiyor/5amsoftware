@extends('layouts.admin')

@section('title', $system->name)

@section('page-title', $system->name)

@section('admin-content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.systems.index') }}" class="small text-decoration-none">&larr; Volver</a>
            @if ($system->is_published)
                <span class="badge badge-soft-success">Publicado</span>
            @else
                <span class="badge badge-soft-warning">Borrador</span>
            @endif
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('systems.show', $system) }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-dark">Ver en landing</a>
            <a href="{{ route('admin.systems.edit', $system) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
            <form method="POST" action="{{ route('admin.systems.destroy', $system) }}"
                  onsubmit="return confirm('¿Eliminar este sistema?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
            </form>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-5">
            <div class="card mb-3">
                <div class="card-body">
                    <h2 class="h5 mb-1">{{ $system->name }}</h2>
                    <p class="text-muted small mb-3">{{ $system->short_description }}</p>
                    <p class="small mb-0">{{ $system->description }}</p>
                    <hr>
                    <dl class="row small mb-0">
                        <dt class="col-5 text-muted">Precio desde</dt>
                        <dd class="col-7">${{ number_format($system->price_from ?? 0, 0, ',', '.') }}</dd>
                        <dt class="col-5 text-muted">Instalación</dt>
                        <dd class="col-7">${{ number_format($system->installation_price ?? 0, 0, ',', '.') }}</dd>
                        <dt class="col-5 text-muted">Slug</dt>
                        <dd class="col-7">{{ $system->slug }}</dd>
                    </dl>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-white"><strong>Características</strong></div>
                <div class="card-body">
                    <ul class="list-unstyled mb-3">
                        @forelse ($system->features as $feature)
                            <li class="d-flex justify-content-between align-items-center gap-2 mb-2">
                                <span>
                                    <span class="text-brand">&#10003;</span> {{ $feature->name }}
                                    @if ($feature->description)
                                        <div class="small text-muted">{{ $feature->description }}</div>
                                    @endif
                                </span>
                                <form method="POST" action="{{ route('admin.systems.features.destroy', [$system, $feature]) }}"
                                      onsubmit="return confirm('¿Eliminar característica?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0">&times;</button>
                                </form>
                            </li>
                        @empty
                            <li class="text-muted small">Sin características todavía.</li>
                        @endforelse
                    </ul>

                    <form method="POST" action="{{ route('admin.systems.features.store', $system) }}" class="d-flex gap-2">
                        @csrf
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="Nueva característica" required>
                        <button type="submit" class="btn btn-sm btn-brand">Agregar</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <strong>Planes</strong>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Precio/mes</th>
                                <th>Instalación</th>
                                <th>Gracia</th>
                                <th>Usuarios</th>
                                <th>Activo</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($system->plans as $plan)
                                <tr>
                                    <td class="fw-semibold">{{ $plan->name }}</td>
                                    <td>${{ number_format($plan->price, 0, ',', '.') }}</td>
                                    <td>${{ number_format($plan->setup_price ?? 0, 0, ',', '.') }}</td>
                                    <td>{{ $plan->grace_days }} días</td>
                                    <td>{{ $plan->max_users ?? 'Ilimitado' }}</td>
                                    <td>
                                        @if ($plan->is_active)
                                            <span class="badge badge-soft-success">Sí</span>
                                        @else
                                            <span class="badge badge-soft-secondary">No</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.plans.edit', [$system, $plan]) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                                        <form method="POST" action="{{ route('admin.plans.destroy', [$system, $plan]) }}" class="d-inline"
                                              onsubmit="return confirm('¿Eliminar plan?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted small py-4">Sin planes. Agregá el primero abajo.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-body border-top">
                    <h3 class="h6 mb-3">Nuevo plan</h3>
                    <form method="POST" action="{{ route('admin.plans.store', $system) }}">
                        @csrf
                        <div class="row g-2">
                            <div class="col-md-4">
                                <input type="text" name="name" class="form-control form-control-sm" placeholder="Nombre *" required>
                            </div>
                            <div class="col-md-3">
                                <input type="number" step="0.01" min="0" name="price" class="form-control form-control-sm" placeholder="Precio/mes *" required>
                            </div>
                            <div class="col-md-3">
                                <input type="number" step="0.01" min="0" name="setup_price" class="form-control form-control-sm" placeholder="Instalación">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-sm btn-brand w-100">Crear</button>
                            </div>
                        </div>
                        @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        @error('price')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        @error('slug')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
