@extends('layouts.admin')

@section('title', 'Cliente ' . $customer->fullName())

@section('page-title', 'Cliente ' . $customer->code)

@section('admin-content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <a href="{{ route('admin.customers.index') }}" class="small text-decoration-none">&larr; Volver a clientes</a>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-sm btn-outline-dark">Editar</a>
            <form method="POST" action="{{ route('admin.customers.destroy', $customer) }}"
                  onsubmit="return confirm('¿Eliminar este cliente? Los contratos asociados se eliminarán.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
            </form>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h2 class="h5 mb-1">{{ $customer->fullName() }}</h2>
                    <div class="mb-3">@include('partials.status-badge', ['status' => $customer->status])</div>
                    <dl class="row small mb-0">
                        <dt class="col-5 text-muted">Email</dt>
                        <dd class="col-7">{{ $customer->email }}</dd>
                        <dt class="col-5 text-muted">Teléfono</dt>
                        <dd class="col-7">{{ $customer->phone ?: '—' }}</dd>
                        <dt class="col-5 text-muted">WhatsApp</dt>
                        <dd class="col-7">{{ $customer->whatsapp ?: '—' }}</dd>
                        <dt class="col-5 text-muted">Empresa</dt>
                        <dd class="col-7">{{ $customer->company_name ?: '—' }}</dd>
                        <dt class="col-5 text-muted">Razón social</dt>
                        <dd class="col-7">{{ $customer->business_name ?: '—' }}</dd>
                        <dt class="col-5 text-muted">CUIT</dt>
                        <dd class="col-7">{{ $customer->tax_id ?: '—' }}</dd>
                        <dt class="col-5 text-muted">Ciudad</dt>
                        <dd class="col-7">{{ $customer->city ?: '—' }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <strong>Contratos</strong>
                    <a href="{{ route('admin.contracts.create', ['customer' => $customer->id]) }}" class="small">+ Nuevo contrato</a>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Contrato</th>
                                <th>Sistema</th>
                                <th>Abono</th>
                                <th>Estado</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($customer->customerSystems as $contract)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.contracts.show', $contract) }}" class="fw-semibold text-decoration-none">
                                            {{ $contract->contract_number }}
                                        </a>
                                    </td>
                                    <td>{{ $contract->system->name }}</td>
                                    <td>${{ number_format($contract->monthly_amount, 0, ',', '.') }}</td>
                                    <td>@include('partials.status-badge', ['status' => $contract->status])</td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.contracts.show', $contract) }}" class="btn btn-sm btn-outline-secondary">Ver</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted small py-4">Sin contratos todavía.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header bg-white"><strong>Últimos pagos</strong></div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr><th>Pago</th><th>Método</th><th>Monto</th><th>Estado</th><th>Fecha</th></tr>
                        </thead>
                        <tbody>
                            @forelse ($customer->payments as $payment)
                                <tr>
                                    <td class="text-muted small">{{ $payment->payment_number }}</td>
                                    <td>{{ $payment->payment_method }}</td>
                                    <td>${{ number_format($payment->amount, 0, ',', '.') }}</td>
                                    <td>@include('partials.status-badge', ['status' => $payment->status])</td>
                                    <td class="small text-muted">{{ $payment->paid_at?->format('d/m/Y') ?: '—' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted small py-4">Sin pagos registrados.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
