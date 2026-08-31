@extends('layouts.admin')

@section('title', 'Solicitudes de demo')

@section('page-title', 'Solicitudes de demo')

@section('admin-content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <form method="GET" action="{{ route('admin.demos.index') }}" class="d-flex flex-wrap gap-2">
            <input type="search" name="q" value="{{ $filters['q'] }}" placeholder="Buscar por nombre, email o empresa..."
                   class="form-control form-control-sm" style="min-width: 260px;">
            <select name="status" class="form-select form-select-sm">
                <option value="">Todos los estados</option>
                @foreach ($statuses as $statusOption)
                    <option value="{{ $statusOption->value }}" @selected($filters['status'] === $statusOption->value)>
                        {{ $statusOption->label() }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-sm btn-outline-dark">Filtrar</button>
        </form>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Solicitante</th>
                        <th>Empresa</th>
                        <th>Sistema</th>
                        <th>Recibida</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($demos as $demo)
                        <tr>
                            <td>
                                <span class="fw-semibold">{{ $demo->fullName() }}</span>
                                <div class="small text-muted">{{ $demo->email }}</div>
                            </td>
                            <td>{{ $demo->company_name ?: '—' }}</td>
                            <td>{{ $demo->system?->name ?: '—' }}</td>
                            <td class="small text-muted">{{ $demo->created_at?->format('d/m/Y H:i') }}</td>
                            <td>@include('partials.status-badge', ['status' => $demo->status])</td>
                            <td class="text-end">
                                <a href="{{ route('admin.demos.show', $demo) }}" class="btn btn-sm btn-outline-secondary">Ver</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted small py-4">No hay solicitudes de demo.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($demos->hasPages())
            <div class="card-footer bg-white">{{ $demos->links() }}</div>
        @endif
    </div>
@endsection
