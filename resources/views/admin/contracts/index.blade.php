@extends('layouts.admin')

@section('title', 'Contratos')

@section('page-title', 'Contratos')

@section('admin-content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <form method="GET" action="{{ route('admin.contracts.index') }}" class="d-flex flex-wrap gap-2">
            <input type="search" name="q" value="{{ $filters['q'] }}" placeholder="Buscar contrato o cliente..."
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
        <a href="{{ route('admin.contracts.create') }}" class="btn btn-sm btn-brand">+ Nuevo contrato</a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Contrato</th>
                        <th>Cliente</th>
                        <th>Sistema</th>
                        <th>Abono</th>
                        <th>Próx. facturación</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($contracts as $contract)
                        <tr>
                            <td class="text-muted small">{{ $contract->contract_number }}</td>
                            <td>
                                <a href="{{ route('admin.customers.show', $contract->customer) }}" class="fw-semibold text-decoration-none">
                                    {{ $contract->customer->fullName() }}
                                </a>
                            </td>
                            <td>{{ $contract->system->name }}</td>
                            <td>${{ number_format($contract->monthly_amount, 0, ',', '.') }}</td>
                            <td class="small text-muted">{{ $contract->next_billing_at?->format('d/m/Y') ?: '—' }}</td>
                            <td>@include('partials.status-badge', ['status' => $contract->status])</td>
                            <td class="text-end">
                                <a href="{{ route('admin.contracts.show', $contract) }}" class="btn btn-sm btn-outline-secondary">Ver</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted small py-4">No hay contratos para mostrar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($contracts->hasPages())
            <div class="card-footer bg-white">{{ $contracts->links() }}</div>
        @endif
    </div>
@endsection
