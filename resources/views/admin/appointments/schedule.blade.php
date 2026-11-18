@extends('layouts.admin')

@section('title', 'Disponibilidad de citas')
@section('page-title', 'Disponibilidad de citas')

@section('admin-content')
    <div class="mb-3"><a href="{{ route('admin.appointments.index') }}" class="small text-decoration-none">&larr; Volver al calendario</a></div>
    <div class="card" style="max-width: 850px;">
        <div class="card-header bg-white"><strong>Horarios disponibles para clientes</strong></div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.appointments.schedule.update') }}">
                @csrf
                @method('PUT')

                <fieldset class="mb-4">
                    <legend class="form-label fw-semibold">Días habilitados</legend>
                    <div class="d-flex flex-wrap gap-3">
                        @foreach ($weekdays as $dayNumber => $dayName)
                            <label class="form-check">
                                <input class="form-check-input" type="checkbox" name="open_days[]" value="{{ $dayNumber }}"
                                       @checked(in_array($dayNumber, old('open_days', $schedule->open_days ?? [])))>
                                <span class="form-check-label">{{ $dayName }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('open_days')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    @error('open_days.*')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </fieldset>

                <div class="row g-3">
                    <div class="col-sm-4">
                        <label for="open_time" class="form-label">Desde</label>
                        <input id="open_time" type="time" name="open_time" value="{{ old('open_time', substr($schedule->open_time, 0, 5)) }}"
                               class="form-control @error('open_time') is-invalid @enderror" required>
                        @error('open_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-sm-4">
                        <label for="close_time" class="form-label">Hasta</label>
                        <input id="close_time" type="time" name="close_time" value="{{ old('close_time', substr($schedule->close_time, 0, 5)) }}"
                               class="form-control @error('close_time') is-invalid @enderror" required>
                        @error('close_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-sm-4">
                        <label for="duration_minutes" class="form-label">Duración de cada cita</label>
                        <select id="duration_minutes" name="duration_minutes" class="form-select @error('duration_minutes') is-invalid @enderror">
                            @foreach ($durations as $duration)
                                <option value="{{ $duration }}" @selected((int) old('duration_minutes', $schedule->duration_minutes) === $duration)>{{ $duration }} minutos</option>
                            @endforeach
                        </select>
                        @error('duration_minutes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <p class="small text-muted mt-3">Los horarios se publican con la zona horaria {{ config('app.timezone') }}. Los turnos ya reservados mantienen su duración original.</p>
                <button type="submit" class="btn btn-brand mt-2">Guardar disponibilidad</button>
            </form>
        </div>
    </div>
@endsection
