@extends('layouts.admin')

@section('title', 'Sistemas')

@section('page-title', 'Sistemas y planes')

@section('admin-content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <form method="GET" action="{{ route('admin.systems.index') }}" class="d-flex flex-wrap gap-2">
            <input type="search" name="q" value="{{ $filters['q'] }}" placeholder="Buscar sistema..."
                   class="form-control form-control-sm" style="min-width: 240px;">
            <select name="published" class="form-select form-select-sm">
                <option value="">Publicación</option>
                <option value="1" @selected($filters['published'] === '1')>Publicados</option>
                <option value="0" @selected($filters['published'] === '0')>Borradores</option>
            </select>
            <button type="submit" class="btn btn-sm btn-outline-dark">Filtrar</button>
        </form>
        <a href="{{ route('admin.systems.create') }}" class="btn btn-sm btn-brand">+ Nuevo sistema</a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Slug</th>
                        <th>Desde</th>
                        <th>Planes</th>
                        <th>Contratos</th>
                        <th>Publicado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($systems as $system)
                        <tr>
                            <td>
                                <a href="{{ route('admin.systems.show', $system) }}" class="fw-semibold text-decoration-none">{{ $system->name }}</a>
                            </td>
                            <td class="text-muted small">{{ $system->slug }}</td>
                            <td>${{ number_format($system->price_from ?? 0, 0, ',', '.') }}</td>
                            <td><span class="badge badge-soft-info">{{ $system->plans_count }}</span></td>
                            <td><span class="badge badge-soft-secondary">{{ $system->customer_systems_count }}</span></td>
                            <td>
                                @if ($system->is_published)
                                    <span class="badge badge-soft-success">Sí</span>
                                @else
                                    <span class="badge badge-soft-warning">Borrador</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.systems.edit', $system) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted small py-4">No hay sistemas para mostrar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($systems->hasPages())
            <div class="card-footer bg-white">{{ $systems->links() }}</div>
        @endif
    </div>
@endsection
