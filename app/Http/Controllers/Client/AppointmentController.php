<?php

declare(strict_types=1);

namespace App\Http\Controllers\Client;

use App\Enums\AppointmentStatus;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentSchedule;
use App\Services\Appointments\AppointmentAvailabilityService;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AppointmentController extends Controller
{
    public function index(Request $request, AppointmentAvailabilityService $availability): View
    {
        $selectedDate = $request->input('date', CarbonImmutable::tomorrow()->toDateString());
        if (! is_string($selectedDate) || ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate) || ! CarbonImmutable::hasFormat($selectedDate, 'Y-m-d')) {
            $selectedDate = CarbonImmutable::tomorrow()->toDateString();
        }

        return view('client.appointments.index', [
            'selectedDate' => $selectedDate,
            'slots' => $availability->slotsForDate($selectedDate),
            'schedule' => $availability->schedule(),
            'appointments' => Appointment::query()
                ->where('user_id', $request->user()->id)
                ->with('user')
                ->orderByDesc('starts_at')
                ->limit(20)
                ->get(),
            'statuses' => AppointmentStatus::cases(),
        ]);
    }

    public function store(Request $request, AppointmentAvailabilityService $availability): RedirectResponse
    {
        $validated = $request->validate([
            'date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'time' => ['required', 'date_format:H:i'],
            'topic' => ['required', 'string', 'min:3', 'max:180'],
            'notes' => ['nullable', 'string', 'max:1500'],
        ]);

        $timezone = (string) config('app.timezone');
        $start = CarbonImmutable::createFromFormat('!Y-m-d H:i', $validated['date'].' '.$validated['time'], $timezone);

        DB::transaction(function () use ($availability, $request, $validated, $start): void {
            $schedule = AppointmentSchedule::query()->lockForUpdate()->firstOrFail();
            $availableSlots = $availability->slotsForDate($validated['date'], $schedule);

            if (! $availableSlots->contains(fn (CarbonImmutable $slot): bool => $slot->equalTo($start))) {
                throw ValidationException::withMessages([
                    'time' => 'Ese horario ya no está disponible. Elegí otro, por favor.',
                ]);
            }

            Appointment::query()->create([
                'user_id' => $request->user()->id,
                'starts_at' => $start,
                'duration_minutes' => $schedule->duration_minutes,
                'topic' => $validated['topic'],
                'notes' => $validated['notes'] ?? null,
                'status' => AppointmentStatus::Pending,
            ]);
        });

        return redirect()
            ->route('client.appointments.index', ['date' => $validated['date']])
            ->with('status', 'Tu solicitud de cita quedó registrada. Te confirmaremos el horario.');
    }
}
