@extends('layouts.admin')

@section('title', 'Clientes')

@section('page-title', 'Clientes')

@section('admin-content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <form method="GET" action="{{ route('admin.customers.index') }}" class="d-flex flex-wrap gap-2">
            <input type="search" name="q" value="{{ $filters['q'] }}" placeholder="Buscar nombre, email, código..."
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
        <a href="{{ route('admin.customers.create') }}" class="btn btn-sm btn-brand">+ Nuevo cliente</a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Código</th>
                        <th>Cliente</th>
                        <th>Email</th>
                        <th>Empresa</th>
                        <th>Contratos</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customers as $customer)
                        <tr>
                            <td class="text-muted small">{{ $customer->code }}</td>
                            <td>
                                <a href="{{ route('admin.customers.show', $customer) }}" class="fw-semibold text-decoration-none">
                                    {{ $customer->fullName() }}
                                </a>
                            </td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->company_name }}</td>
                            <td><span class="badge badge-soft-info">{{ $customer->customer_systems_count }}</span></td>
                            <td>@include('partials.status-badge', ['status' => $customer->status])</td>
                            <td class="text-end">
                                <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted small py-4">No hay clientes para mostrar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($customers->hasPages())
            <div class="card-footer bg-white">{{ $customers->links() }}</div>
        @endif
    </div>
@endsection
