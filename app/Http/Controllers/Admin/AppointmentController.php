<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\AppointmentStatus;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentSchedule;
use App\Services\Support\AuditService;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AppointmentController extends Controller
{
    private const WEEKDAYS = [
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Miércoles',
        4 => 'Jueves',
        5 => 'Viernes',
        6 => 'Sábado',
        7 => 'Domingo',
    ];

    public function __construct(private readonly AuditService $audit) {}

    public function index(Request $request): View
    {
        $monthInput = $request->input('month', now()->format('Y-m'));
        $month = is_string($monthInput) && preg_match('/^\d{4}-\d{2}$/', $monthInput)
            ? CarbonImmutable::createFromFormat('!Y-m', $monthInput)
            : false;
        $month = $month ?: CarbonImmutable::now()->startOfMonth();
        $calendarStart = $month->startOfMonth()->startOfWeek(Carbon::MONDAY);
        $calendarEnd = $month->endOfMonth()->endOfWeek(Carbon::SUNDAY);

        $appointments = Appointment::query()
            ->with('user:id,name,email')
            ->whereBetween('starts_at', [$calendarStart->startOfDay(), $calendarEnd->endOfDay()])
            ->orderBy('starts_at')
            ->get();
        $byDate = $appointments->groupBy(fn (Appointment $appointment): string => $appointment->starts_at->format('Y-m-d'));
        $calendarDays = [];

        for ($date = $calendarStart; $date <= $calendarEnd; $date = $date->addDay()) {
            $calendarDays[] = [
                'date' => $date,
                'appointments' => $byDate->get($date->toDateString(), collect()),
                'inMonth' => $date->month === $month->month,
            ];
        }

        return view('admin.appointments.index', [
            'appointments' => $appointments,
            'calendarDays' => $calendarDays,
            'month' => $month,
            'previousMonth' => $month->subMonth()->format('Y-m'),
            'nextMonth' => $month->addMonth()->format('Y-m'),
            'weekdays' => array_values(self::WEEKDAYS),
            'statuses' => AppointmentStatus::cases(),
        ]);
    }

    public function editSchedule(): View
    {
        return view('admin.appointments.schedule', [
            'schedule' => AppointmentSchedule::query()->firstOrFail(),
            'weekdays' => self::WEEKDAYS,
            'durations' => [15, 30, 45, 60, 90, 120],
        ]);
    }

    public function updateSchedule(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'open_days' => ['required', 'array', 'min:1'],
            'open_days.*' => ['required', 'integer', Rule::in(array_keys(self::WEEKDAYS))],
            'open_time' => ['required', 'date_format:H:i'],
            'close_time' => ['required', 'date_format:H:i', 'after:open_time'],
            'duration_minutes' => ['required', 'integer', Rule::in([15, 30, 45, 60, 90, 120])],
        ]);

        $schedule = AppointmentSchedule::query()->firstOrFail();
        $schedule->update([
            ...$validated,
            'open_days' => array_map('intval', $validated['open_days']),
        ]);
        $this->audit->model('updated', $schedule);

        return redirect()->route('admin.appointments.schedule')->with('status', 'La disponibilidad para citas se actualizó.');
    }

    public function updateStatus(Request $request, Appointment $appointment): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::enum(AppointmentStatus::class)],
            'admin_notes' => ['nullable', 'string', 'max:1500'],
        ]);

        $oldStatus = $appointment->status->value;
        $appointment->update($validated);
        $this->audit->model('updated', $appointment, ['status' => $oldStatus], ['status' => $validated['status']]);

        return redirect()
            ->route('admin.appointments.index', ['month' => $appointment->starts_at->format('Y-m')])
            ->with('status', 'La cita se actualizó correctamente.');
    }
}
