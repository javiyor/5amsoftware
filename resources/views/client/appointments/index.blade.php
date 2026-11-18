@extends('layouts.client')

@section('title', 'Mis citas')

@section('client-content')
    <div class="mb-4">
        <h1 class="h3 mb-1">Solicitar una cita</h1>
        <p class="text-muted mb-0">Elegí un día y horario disponible, y contanos el tema que querés tratar.</p>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card mb-4">
                <div class="card-body p-4">
                    <form method="GET" action="{{ route('client.appointments.index') }}" class="row g-2 align-items-end mb-4">
                        <div class="col-sm-7">
                            <label for="date" class="form-label">Elegí una fecha</label>
                            <input id="date" type="date" name="date" value="{{ $selectedDate }}" min="{{ now()->toDateString() }}" class="form-control" required>
                        </div>
                        <div class="col-sm-5"><button class="btn btn-outline-primary w-100" type="submit">Ver horarios</button></div>
                    </form>

                    <h2 class="h5 mb-3">Horarios disponibles</h2>
                    @if ($slots->isNotEmpty())
                        <form method="POST" action="{{ route('client.appointments.store') }}">
                            @csrf
                            <input type="hidden" name="date" value="{{ $selectedDate }}">
                            <div class="d-flex flex-wrap gap-2 mb-4">
                                @foreach ($slots as $slot)
                                    <label class="btn btn-outline-secondary">
                                        <input class="visually-hidden" type="radio" name="time" value="{{ $slot->format('H:i') }}" @checked(old('time') === $slot->format('H:i')) required>
                                        {{ $slot->format('H:i') }}
                                    </label>
                                @endforeach
                            </div>
                            @error('time')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                            <div class="mb-3">
                                <label for="topic" class="form-label">Tema de interés *</label>
                                <input id="topic" name="topic" value="{{ old('topic') }}" class="form-control @error('topic') is-invalid @enderror"
                                       maxlength="180" placeholder="Ej.: conocer el sistema de gestión comercial" required>
                                @error('topic')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="notes" class="form-label">Más detalles (opcional)</label>
                                <textarea id="notes" name="notes" rows="3" maxlength="1500" class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <button class="btn btn-brand" type="submit">Solicitar cita</button>
                        </form>
                    @else
                        <div class="alert alert-light border mb-0">No hay horarios disponibles para ese día. Probá con otra fecha.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card">
                <div class="card-header bg-white"><strong>Mis solicitudes</strong></div>
                <div class="list-group list-group-flush">
                    @forelse ($appointments as $appointment)
                        <div class="list-group-item py-3">
                            <div class="d-flex justify-content-between gap-2 align-items-start">
                                <div>
                                    <div class="fw-semibold">{{ $appointment->starts_at->format('d/m/Y H:i') }}</div>
                                    <div class="small text-muted">{{ $appointment->topic }}</div>
                                </div>
                                @include('partials.status-badge', ['status' => $appointment->status])
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item text-muted small py-4">Todavía no solicitaste citas.</div>
                    @endforelse
                </div>
            </div>
            <p class="small text-muted mt-3">Duración: {{ $schedule->duration_minutes }} minutos. Las solicitudes quedan pendientes hasta su confirmación.</p>
        </div>
    </div>
@endsection
