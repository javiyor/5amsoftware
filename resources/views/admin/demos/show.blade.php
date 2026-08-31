@extends('layouts.admin')

@section('title', 'Solicitud de demo ' . $demo->fullName())

@section('page-title', 'Solicitud de demo')

@section('admin-content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <a href="{{ route('admin.demos.index') }}" class="small text-decoration-none">&larr; Volver a solicitudes</a>
        @include('partials.status-badge', ['status' => $demo->status])
    </div>

    <div class="row g-3">
        <div class="col-lg-7">
            <div class="card mb-3">
                <div class="card-header bg-white"><strong>Datos del solicitante</strong></div>
                <div class="card-body">
                    <dl class="row small mb-0">
                        <dt class="col-4 text-muted">Nombre</dt>
                        <dd class="col-8">{{ $demo->fullName() }}</dd>
                        <dt class="col-4 text-muted">Email</dt>
                        <dd class="col-8">{{ $demo->email }}</dd>
                        <dt class="col-4 text-muted">Teléfono</dt>
                        <dd class="col-8">{{ $demo->phone ?: '—' }}</dd>
                        <dt class="col-4 text-muted">WhatsApp</dt>
                        <dd class="col-8">{{ $demo->whatsapp ?: '—' }}</dd>
                        <dt class="col-4 text-muted">Empresa</dt>
                        <dd class="col-8">{{ $demo->company_name ?: '—' }}</dd>
                        <dt class="col-4 text-muted">Ciudad</dt>
                        <dd class="col-8">{{ $demo->city ?: '—' }}</dd>
                        <dt class="col-4 text-muted">Sistema</dt>
                        <dd class="col-8">{{ $demo->system?->name ?: '—' }}</dd>
                        <dt class="col-4 text-muted">Usuarios</dt>
                        <dd class="col-8">{{ $demo->number_of_users ?: '—' }}</dd>
                        <dt class="col-4 text-muted">Fecha preferida</dt>
                        <dd class="col-8">{{ $demo->preferred_date?->format('d/m/Y') ?: '—' }}</dd>
                        <dt class="col-4 text-muted">Hora preferida</dt>
                        <dd class="col-8">{{ $demo->preferred_time ?: '—' }}</dd>
                        <dt class="col-4 text-muted">Recibida</dt>
                        <dd class="col-8">{{ $demo->created_at?->format('d/m/Y H:i') ?: '—' }}</dd>
                        @if ($demo->convertedCustomer)
                            <dt class="col-4 text-muted">Cliente</dt>
                            <dd class="col-8">
                                <a href="{{ route('admin.customers.show', $demo->convertedCustomer) }}" class="text-decoration-none">
                                    {{ $demo->convertedCustomer->fullName() }} ({{ $demo->convertedCustomer->code }})
                                </a>
                            </dd>
                        @endif
                    </dl>

                    @if ($demo->comments)
                        <hr>
                        <p class="small mb-0">{{ $demo->comments }}</p>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-white"><strong>Cambiar estado</strong></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.demos.update-status', $demo) }}">
                        @csrf
                        <div class="row g-2">
                            <div class="col-md-5">
                                <select id="status" name="status" class="form-select form-select-sm @error('status') is-invalid @enderror">
                                    @foreach ($statuses as $statusOption)
                                        <option value="{{ $statusOption->value }}" @selected(old('status', $demo->status?->value) === $statusOption->value)>
                                            {{ $statusOption->label() }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-7" id="convertedCustomerWrapper" style="display: none;">
                                <select name="converted_customer_id" class="form-select form-select-sm @error('converted_customer_id') is-invalid @enderror">
                                    <option value="">Asociar cliente (solo si se convierte)</option>
                                    @foreach ($customers as $customerOption)
                                        <option value="{{ $customerOption->id }}" @selected(old('converted_customer_id', $demo->converted_customer_id) == $customerOption->id)>
                                            {{ $customerOption->code }} — {{ $customerOption->fullName() }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('converted_customer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <input type="text" name="comment" class="form-control form-control-sm"
                                       placeholder="Comentario (opcional)" value="{{ old('comment') }}">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-sm btn-brand">Actualizar estado</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card">
                <div class="card-header bg-white"><strong>Historial</strong></div>
                <ul class="list-group list-group-flush">
                    @forelse ($demo->logs as $log)
                        <li class="list-group-item small">
                            <div class="d-flex justify-content-between">
                                <span>
                                    @if ($log->old_status)
                                        <span class="badge badge-soft-secondary">{{ $log->old_status }}</span>
                                        &rarr;
                                    @endif
                                    <span class="badge badge-soft-primary">{{ $log->new_status }}</span>
                                </span>
                                <span class="text-muted">{{ $log->created_at?->format('d/m/Y H:i') }}</span>
                            </div>
                            @if ($log->comment)
                                <div class="mt-1 text-muted">{{ $log->comment }}</div>
                            @endif
                            <div class="mt-1 text-muted">por {{ $log->user?->name ?: 'sistema' }}</div>
                        </li>
                    @empty
                        <li class="list-group-item text-muted small text-center py-4">Sin historial.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const statusSelect = document.getElementById('status');
            const wrapper = document.getElementById('convertedCustomerWrapper');

            function toggle() {
                wrapper.style.display = statusSelect.value === 'converted' ? 'block' : 'none';
            }

            statusSelect.addEventListener('change', toggle);
            toggle();
        });
    </script>
@endpush
