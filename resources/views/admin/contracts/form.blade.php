@extends('layouts.admin')

@section('title', $contract->exists ? 'Editar contrato' : 'Nuevo contrato')

@section('page-title', $contract->exists ? 'Editar contrato ' . $contract->contract_number : 'Nuevo contrato')

@section('admin-content')
    <form method="POST"
          action="{{ $contract->exists ? route('admin.contracts.update', $contract) : route('admin.contracts.store') }}">
        @csrf
        @if ($contract->exists)
            @method('PUT')
        @endif

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label" for="customer_id">Cliente *</label>
                <select id="customer_id" name="customer_id" class="form-select @error('customer_id') is-invalid @enderror"
                        @if ($contract->exists) disabled @endif required>
                    <option value="">Seleccionar cliente</option>
                    @foreach ($customers as $customerOption)
                        <option value="{{ $customerOption->id }}"
                                @selected(old('customer_id', $preselectedCustomer) == $customerOption->id)>
                            {{ $customerOption->code }} — {{ $customerOption->fullName() }}
                        </option>
                    @endforeach
                </select>
                @if ($contract->exists)
                    <input type="hidden" name="customer_id" value="{{ $contract->customer_id }}">
                @endif
                @error('customer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label" for="custom_name">Nombre personalizado</label>
                <input type="text" id="custom_name" name="custom_name" value="{{ old('custom_name', $contract->custom_name) }}"
                       class="form-control @error('custom_name') is-invalid @enderror"
                       placeholder="Opcional, ej: Sucursal Córdoba">
                @error('custom_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label" for="system_id">Sistema *</label>
                <select id="system_id" name="system_id" class="form-select @error('system_id') is-invalid @enderror" required>
                    <option value="">Seleccionar sistema</option>
                    @foreach ($systems as $systemOption)
                        <option value="{{ $systemOption->id }}" data-plans='{{ json_encode($systemOption->plans->map(fn ($p) => [
                            'id' => $p->id,
                            'name' => $p->name,
                            'price' => $p->price,
                            'setup_price' => $p->setup_price,
                            'billing_interval_days' => $p->billing_interval_days,
                            'grace_days' => $p->grace_days,
                        ])) }}'
                                @selected(old('system_id', $contract->system_id) == $systemOption->id)>
                            {{ $systemOption->name }}
                        </option>
                    @endforeach
                </select>
                @error('system_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label" for="system_plan_id">Plan</label>
                <select id="system_plan_id" name="system_plan_id" class="form-select @error('system_plan_id') is-invalid @enderror">
                    <option value="">Sin plan (personalizado)</option>
                    @foreach ($plans as $planOption)
                        <option value="{{ $planOption->id }}"
                                @selected(old('system_plan_id', $contract->system_plan_id) == $planOption->id)>
                            {{ $planOption->name }}
                        </option>
                    @endforeach
                </select>
                @error('system_plan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label" for="monthly_amount">Abono mensual (ARS) *</label>
                <input type="number" step="0.01" min="0" id="monthly_amount" name="monthly_amount"
                       value="{{ old('monthly_amount', $contract->monthly_amount) }}"
                       class="form-control @error('monthly_amount') is-invalid @enderror" required>
                @error('monthly_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label" for="setup_amount">Instalación (ARS)</label>
                <input type="number" step="0.01" min="0" id="setup_amount" name="setup_amount"
                       value="{{ old('setup_amount', $contract->setup_amount ?? 0) }}"
                       class="form-control @error('setup_amount') is-invalid @enderror">
                @error('setup_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label" for="billing_interval_days">Ciclo (días) *</label>
                <input type="number" min="1" id="billing_interval_days" name="billing_interval_days"
                       value="{{ old('billing_interval_days', $contract->billing_interval_days ?? 30) }}"
                       class="form-control @error('billing_interval_days') is-invalid @enderror" required>
                @error('billing_interval_days')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label" for="grace_days">Días de gracia *</label>
                <input type="number" min="0" id="grace_days" name="grace_days"
                       value="{{ old('grace_days', $contract->grace_days ?? 10) }}"
                       class="form-control @error('grace_days') is-invalid @enderror" required>
                @error('grace_days')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label" for="currency">Moneda *</label>
                <select id="currency" name="currency" class="form-select @error('currency') is-invalid @enderror">
                    <option value="ARS" @selected(old('currency', $contract->currency ?? 'ARS') === 'ARS')>ARS</option>
                    <option value="USD" @selected(old('currency', $contract->currency) === 'USD')>USD</option>
                </select>
                @error('currency')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label" for="started_at">Fecha de inicio</label>
                <input type="date" id="started_at" name="started_at"
                       value="{{ old('started_at', $contract->started_at?->format('Y-m-d')) }}"
                       class="form-control @error('started_at') is-invalid @enderror">
                @error('started_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label" for="billing_started_at">Inicio de facturación</label>
                <input type="date" id="billing_started_at" name="billing_started_at"
                       value="{{ old('billing_started_at', $contract->billing_started_at?->format('Y-m-d')) }}"
                       class="form-control @error('billing_started_at') is-invalid @enderror">
                @error('billing_started_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label" for="grace_override_until">Gracia extendida hasta</label>
                <input type="date" id="grace_override_until" name="grace_override_until"
                       value="{{ old('grace_override_until', $contract->grace_override_until?->format('Y-m-d')) }}"
                       class="form-control @error('grace_override_until') is-invalid @enderror">
                @error('grace_override_until')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label" for="status">Estado *</label>
                <select id="status" name="status" class="form-select @error('status') is-invalid @enderror">
                    @foreach ($statuses as $statusOption)
                        <option value="{{ $statusOption->value }}"
                                @selected(old('status', $contract->status?->value ?? 'pending') === $statusOption->value)>
                            {{ $statusOption->label() }}
                        </option>
                    @endforeach
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Cobro automático</label>
                <div class="form-check form-switch mt-2">
                    <input type="hidden" name="automatic_billing" value="0">
                    <input class="form-check-input" type="checkbox" name="automatic_billing" id="automatic_billing" value="1"
                           @checked(old('automatic_billing', $contract->automatic_billing ?? true))>
                    <label class="form-check-label" for="automatic_billing">Generar cobros automáticamente</label>
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label">Suspensión automática</label>
                <div class="form-check form-switch mt-2">
                    <input type="hidden" name="automatic_suspension" value="0">
                    <input class="form-check-input" type="checkbox" name="automatic_suspension" id="automatic_suspension" value="1"
                           @checked(old('automatic_suspension', $contract->automatic_suspension ?? true))>
                    <label class="form-check-label" for="automatic_suspension">Suspender al vencer la gracia</label>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label" for="domain">Dominio</label>
                <input type="text" id="domain" name="domain" value="{{ old('domain', $contract->domain) }}"
                       class="form-control @error('domain') is-invalid @enderror" placeholder="app.cliente.com">
                @error('domain')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label" for="installation_id">ID de instalación</label>
                <input type="text" id="installation_id" name="installation_id" value="{{ old('installation_id', $contract->installation_id) }}"
                       class="form-control @error('installation_id') is-invalid @enderror">
                @error('installation_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
                <label class="form-label" for="notes">Notas</label>
                <textarea id="notes" name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $contract->notes) }}</textarea>
                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-brand">
                {{ $contract->exists ? 'Guardar cambios' : 'Crear contrato' }}
            </button>
            <a href="{{ $contract->exists ? route('admin.contracts.show', $contract) : route('admin.contracts.index') }}"
               class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const systemSelect = document.getElementById('system_id');
            const planSelect = document.getElementById('system_plan_id');
            const monthlyAmount = document.getElementById('monthly_amount');
            const setupAmount = document.getElementById('setup_amount');
            const billingInterval = document.getElementById('billing_interval_days');
            const graceDays = document.getElementById('grace_days');

            function renderPlans() {
                const selected = systemSelect.selectedOptions[0];
                const plans = selected ? JSON.parse(selected.dataset.plans || '[]') : [];

                planSelect.innerHTML = '<option value="">Sin plan (personalizado)</option>';
                plans.forEach(function (plan) {
                    const option = document.createElement('option');
                    option.value = plan.id;
                    option.textContent = plan.name;
                    option.dataset.plan = JSON.stringify(plan);
                    planSelect.appendChild(option);
                });
            }

            function autofillFromPlan() {
                const option = planSelect.selectedOptions[0];
                if (!option || !option.dataset.plan) {
                    return;
                }

                const plan = JSON.parse(option.dataset.plan);
                monthlyAmount.value = plan.price;
                setupAmount.value = plan.setup_price ?? 0;
                billingInterval.value = plan.billing_interval_days ?? 30;
                graceDays.value = plan.grace_days ?? 10;
            }

            systemSelect.addEventListener('change', function () {
                renderPlans();
                autofillFromPlan();
            });

            planSelect.addEventListener('change', autofillFromPlan);

            renderPlans();
        });
    </script>
@endpush
