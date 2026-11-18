<?php

declare(strict_types=1);

namespace App\Services\Appointments;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\AppointmentSchedule;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

class AppointmentAvailabilityService
{
    public function schedule(): AppointmentSchedule
    {
        return AppointmentSchedule::query()->firstOrFail();
    }

    /** @return Collection<int, CarbonImmutable> */
    public function slotsForDate(string $date, ?AppointmentSchedule $schedule = null): Collection
    {
        $schedule ??= $this->schedule();
        $timezone = (string) config('app.timezone');
        $day = CarbonImmutable::createFromFormat('!Y-m-d', $date, $timezone);

        if (! $day || $day->lt(CarbonImmutable::now($timezone)->startOfDay()) || ! in_array($day->dayOfWeekIso, $schedule->open_days ?? [], true)) {
            return collect();
        }

        $duration = $schedule->duration_minutes;
        $slot = CarbonImmutable::parse($date.' '.$schedule->open_time, $timezone);
        $closing = CarbonImmutable::parse($date.' '.$schedule->close_time, $timezone);

        if ($duration < 1 || $closing <= $slot) {
            return collect();
        }

        $appointments = Appointment::query()
            ->whereDate('starts_at', $date)
            ->whereNotIn('status', [AppointmentStatus::Cancelled->value])
            ->get(['starts_at', 'duration_minutes']);

        $available = collect();

        while ($slot->addMinutes($duration) <= $closing) {
            $slotEnd = $slot->addMinutes($duration);
            $overlaps = $appointments->contains(function (Appointment $appointment) use ($slot, $slotEnd, $timezone): bool {
                $appointmentStart = CarbonImmutable::instance($appointment->starts_at)->setTimezone($timezone);
                $appointmentEnd = $appointmentStart->addMinutes($appointment->duration_minutes);

                return $appointmentStart < $slotEnd && $appointmentEnd > $slot;
            });

            if (! $overlaps && $slot > CarbonImmutable::now($timezone)) {
                $available->push($slot);
            }

            $slot = $slotEnd;
        }

        return $available;
    }
}
