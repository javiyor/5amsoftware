@extends('layouts.admin')

@section('title', 'Contrato ' . $contract->contract_number)

@section('page-title', 'Contrato ' . $contract->contract_number)

@section('admin-content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <a href="{{ route('admin.contracts.index') }}" class="small text-decoration-none">&larr; Volver a contratos</a>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.contracts.edit', $contract) }}" class="btn btn-sm btn-outline-dark">Editar</a>
            <form method="POST" action="{{ route('admin.contracts.destroy', $contract) }}"
                  onsubmit="return confirm('¿Eliminar este contrato?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
            </form>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-5">
            <div class="card mb-3">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <strong>Datos del contrato</strong>
                    @include('partials.status-badge', ['status' => $contract->status])
                </div>
                <div class="card-body">
                    <dl class="row small mb-0">
                        <dt class="col-5 text-muted">Cliente</dt>
                        <dd class="col-7">
                            <a href="{{ route('admin.customers.show', $contract->customer) }}" class="text-decoration-none">
                                {{ $contract->customer->fullName() }}
                            </a>
                        </dd>
                        <dt class="col-5 text-muted">Sistema</dt>
                        <dd class="col-7">{{ $contract->system->name }}</dd>
                        <dt class="col-5 text-muted">Plan</dt>
                        <dd class="col-7">{{ $contract->plan?->name ?: '—' }}</dd>
                        <dt class="col-5 text-muted">Nombre</dt>
                        <dd class="col-7">{{ $contract->custom_name ?: '—' }}</dd>
                        <dt class="col-5 text-muted">Inicio</dt>
                        <dd class="col-7">{{ $contract->started_at?->format('d/m/Y') ?: '—' }}</dd>
                        <dt class="col-5 text-muted">Inicio facturación</dt>
                        <dd class="col-7">{{ $contract->billing_started_at?->format('d/m/Y') ?: '—' }}</dd>
                        <dt class="col-5 text-muted">Próx. facturación</dt>
                        <dd class="col-7">{{ $contract->next_billing_at?->format('d/m/Y') ?: '—' }}</dd>
                        <dt class="col-5 text-muted">Ciclo</dt>
                        <dd class="col-7">{{ $contract->billing_interval_days }} días</dd>
                        <dt class="col-5 text-muted">Gracia</dt>
                        <dd class="col-7">{{ $contract->grace_days }} días</dd>
                        <dt class="col-5 text-muted">Abono</dt>
                        <dd class="col-7">${{ number_format($contract->monthly_amount, 2, ',', '.') }} {{ $contract->currency }}</dd>
                        <dt class="col-5 text-muted">Instalación</dt>
                        <dd class="col-7">${{ number_format($contract->setup_amount ?? 0, 2, ',', '.') }} {{ $contract->currency }}</dd>
                        <dt class="col-5 text-muted">Cobro automático</dt>
                        <dd class="col-7">{{ $contract->automatic_billing ? 'Sí' : 'No' }}</dd>
                        <dt class="col-5 text-muted">Susp. automática</dt>
                        <dd class="col-7">{{ $contract->automatic_suspension ? 'Sí' : 'No' }}</dd>
                        @if ($contract->domain)
                            <dt class="col-5 text-muted">Dominio</dt>
                            <dd class="col-7">{{ $contract->domain }}</dd>
                        @endif
                        @if ($contract->notes)
                            <dt class="col-5 text-muted">Notas</dt>
                            <dd class="col-7">{{ $contract->notes }}</dd>
                        @endif
                    </dl>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-white"><strong>Licencia</strong></div>
                <div class="card-body">
                    @if ($contract->license)
                        <div class="mb-3">@include('partials.status-badge', ['status' => $contract->license->status])</div>
                        <div class="font-monospace small text-break">{{ $contract->license->license_key }}</div>
                        <dl class="row small mt-3 mb-0">
                            <dt class="col-5 text-muted">Activada</dt>
                            <dd class="col-7">{{ $contract->license->activated_at?->format('d/m/Y H:i') ?: '—' }}</dd>
                            <dt class="col-5 text-muted">Expira</dt>
                            <dd class="col-7">{{ $contract->license->expires_at?->format('d/m/Y') ?: '—' }}</dd>
                            <dt class="col-5 text-muted">Última consulta</dt>
                            <dd class="col-7">{{ $contract->license->last_check_at?->format('d/m/Y H:i') ?: '—' }}</dd>
                        </dl>
                    @else
                        <p class="text-muted small mb-0">Este contrato no tiene licencia.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card">
                <div class="card-header bg-white"><strong>Cuotas</strong></div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>N.º</th>
                                <th>Período</th>
                                <th>Vence</th>
                                <th>Total</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($contract->installments as $installment)
                                <tr>
                                    <td class="text-muted small">{{ $installment->number }}</td>
                                    <td class="small">
                                        {{ $installment->period_start?->format('d/m/Y') }} — {{ $installment->period_end?->format('d/m/Y') }}
                                    </td>
                                    <td class="small">{{ $installment->due_at?->format('d/m/Y') ?: '—' }}</td>
                                    <td>${{ number_format($installment->total, 2, ',', '.') }}</td>
                                    <td>@include('partials.status-badge', ['status' => $installment->status])</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted small py-4">Sin cuotas generadas todavía.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
