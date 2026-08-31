@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('admin-content')
    <h1 class="h3 mb-4">Panel de administración</h1>

    <div class="row g-3">
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card p-3">
                <div class="text-muted small">Clientes activos</div>
                <div class="stat-value">{{ number_format($activeCustomers) }}</div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card p-3">
                <div class="text-muted small">Contratos activos</div>
                <div class="stat-value">{{ number_format($activeContracts) }}</div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card p-3">
                <div class="text-muted small">Cuotas vencidas</div>
                <div class="stat-value text-danger">{{ number_format($overdueInstallments) }}</div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card p-3">
                <div class="text-muted small">Cobrado este mes</div>
                <div class="stat-value">${{ number_format($collectedThisMonth, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card p-3">
                <div class="text-muted small">Demos pendientes</div>
                <div class="stat-value">{{ number_format($pendingDemos) }}</div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card p-3">
                <div class="text-muted small">Clientes con deuda</div>
                <div class="stat-value text-warning">{{ number_format($blockedCustomers) }}</div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <strong>Últimos clientes</strong>
                    <a href="{{ route('admin.customers.index') }}" class="small">Ver todos</a>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <tbody>
                            @forelse ($recentCustomers as $customer)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.customers.show', $customer) }}" class="fw-semibold text-decoration-none">
                                            {{ $customer->fullName() }}
                                        </a>
                                        <div class="small text-muted">{{ $customer->email }}</div>
                                    </td>
                                    <td class="text-end">
                                        <span class="small text-muted">{{ $customer->customer_systems_count }} contratos</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td class="text-muted small text-center py-4">Sin clientes todavía.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <strong>Últimas demos</strong>
                    <a href="{{ route('admin.demos.index') }}" class="small">Ver todas</a>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <tbody>
                            @forelse ($recentDemos as $demo)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.demos.show', $demo) }}" class="fw-semibold text-decoration-none">
                                            {{ $demo->fullName() }}
                                        </a>
                                        <div class="small text-muted">{{ $demo->system?->name ?? 'Sin sistema' }}</div>
                                    </td>
                                    <td class="text-end">
                                        @include('partials.status-badge', ['status' => $demo->status])
                                    </td>
                                </tr>
                            @empty
                                <tr><td class="text-muted small text-center py-4">Sin solicitudes de demo.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
