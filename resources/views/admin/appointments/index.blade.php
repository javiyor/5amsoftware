@extends('layouts.admin')

@section('title', 'Calendario de citas')
@section('page-title', 'Citas')

@section('admin-content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h1 class="h3 mb-1">Calendario de citas</h1>
            <p class="text-muted small mb-0">Reservas solicitadas por clientes desde el portal.</p>
        </div>
        <a href="{{ route('admin.appointments.schedule') }}" class="btn btn-brand">Configurar disponibilidad</a>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <a href="{{ route('admin.appointments.index', ['month' => $previousMonth]) }}" class="btn btn-sm btn-outline-secondary">&larr; Anterior</a>
            <strong>{{ ucfirst($month->locale('es')->translatedFormat('F Y')) }}</strong>
            <a href="{{ route('admin.appointments.index', ['month' => $nextMonth]) }}" class="btn btn-sm btn-outline-secondary">Siguiente &rarr;</a>
        </div>
        <div class="card-body">
            <div class="row g-1 text-center small fw-semibold text-muted mb-1">
                @foreach ($weekdays as $weekday)
                    <div class="col">{{ $weekday }}</div>
                @endforeach
            </div>
            <div class="row g-1">
                @foreach ($calendarDays as $calendarDay)
                    <div class="col" style="min-width: 13%; flex: 0 0 14.285%;">
                        <div class="border rounded-2 p-2 {{ $calendarDay['inMonth'] ? 'bg-white' : 'bg-light text-muted' }}" style="min-height: 88px;">
                            <div class="small fw-semibold">{{ $calendarDay['date']->day }}</div>
                            @if ($calendarDay['appointments']->isNotEmpty())
                                <a class="badge text-bg-primary text-decoration-none mt-1" href="#citas-{{ $calendarDay['date']->format('Y-m-d') }}">
                                    {{ $calendarDay['appointments']->count() }} {{ $calendarDay['appointments']->count() === 1 ? 'cita' : 'citas' }}
                                </a>
                                @foreach ($calendarDay['appointments']->take(2) as $appointment)
                                    <div class="small text-truncate mt-1" title="{{ $appointment->user->name }}">
                                        {{ $appointment->starts_at->format('H:i') }} {{ $appointment->user->name }}
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @forelse ($appointments->groupBy(fn ($appointment) => $appointment->starts_at->format('Y-m-d')) as $date => $dayAppointments)
        <div class="card mb-3" id="citas-{{ $date }}">
            <div class="card-header bg-white fw-semibold">{{ \Carbon\Carbon::parse($date)->locale('es')->translatedFormat('l d/m/Y') }}</div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light"><tr><th>Hora</th><th>Cliente</th><th>Tema</th><th>Estado</th><th>Actualizar</th></tr></thead>
                    <tbody>
                        @foreach ($dayAppointments as $appointment)
                            <tr>
                                <td class="text-nowrap">{{ $appointment->starts_at->format('H:i') }}–{{ $appointment->starts_at->copy()->addMinutes($appointment->duration_minutes)->format('H:i') }}</td>
                                <td>{{ $appointment->user->name }}<div class="small text-muted">{{ $appointment->user->email }}</div></td>
                                <td>{{ $appointment->topic }}@if ($appointment->notes)<div class="small text-muted">{{ $appointment->notes }}</div>@endif</td>
                                <td>@include('partials.status-badge', ['status' => $appointment->status])</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.appointments.status', $appointment) }}" class="d-flex flex-wrap gap-1">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="form-select form-select-sm" aria-label="Estado de la cita">
                                            @foreach ($statuses as $statusOption)
                                                <option value="{{ $statusOption->value }}" @selected($appointment->status === $statusOption)>{{ $statusOption->label() }}</option>
                                            @endforeach
                                        </select>
                                        <input type="text" name="admin_notes" class="form-control form-control-sm" value="{{ $appointment->admin_notes }}" placeholder="Nota interna" aria-label="Nota interna">
                                        <button class="btn btn-sm btn-outline-primary" type="submit">Guardar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="card"><div class="card-body text-center text-muted">No hay citas registradas para este mes.</div></div>
    @endforelse
@endsection
