<?php

declare(strict_types=1);

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\AppointmentSchedule;
use App\Services\Appointments\AppointmentAvailabilityService;
use Carbon\Carbon;

beforeEach(function () {
    Carbon::setTestNow(Carbon::parse('2026-11-16 08:00:00', 'America/Argentina/Cordoba'));
});

afterEach(function () {
    Carbon::setTestNow();
});

it('muestra los horarios de atención en el portal del cliente autenticado', function () {
    $client = customerUser();

    $this->actingAs($client)
        ->get(route('client.appointments.index', ['date' => '2026-11-16']))
        ->assertOk()
        ->assertSee('Solicitar una cita')
        ->assertSee('09:00')
        ->assertSee('16:30');
});

it('permite al cliente reservar un horario e informa el tema de interés al admin', function () {
    $client = customerUser(['name' => 'María Cliente']);

    $this->actingAs($client)
        ->post(route('client.appointments.store'), [
            'date' => '2026-11-16',
            'time' => '09:30',
            'topic' => 'Demo del sistema comercial',
            'notes' => 'Quiero consultar por varias sucursales.',
        ])
        ->assertRedirect(route('client.appointments.index', ['date' => '2026-11-16']));

    $appointment = Appointment::query()->firstOrFail();
    expect($appointment->user_id)->toBe($client->id)
        ->and($appointment->topic)->toBe('Demo del sistema comercial')
        ->and($appointment->status)->toBe(AppointmentStatus::Pending)
        ->and($appointment->duration_minutes)->toBe(30);

    $this->actingAs(adminUser())
        ->get(route('admin.appointments.index', ['month' => '2026-11']))
        ->assertOk()
        ->assertSee('María Cliente')
        ->assertSee('Demo del sistema comercial');
});

it('no permite reservar el mismo horario dos veces y libera la reserva cancelada', function () {
    $firstClient = customerUser();
    $secondClient = customerUser();

    $this->actingAs($firstClient)->post(route('client.appointments.store'), [
        'date' => '2026-11-16',
        'time' => '10:00',
        'topic' => 'Consulta de producto',
    ])->assertRedirect();

    $this->actingAs($secondClient)->post(route('client.appointments.store'), [
        'date' => '2026-11-16',
        'time' => '10:00',
        'topic' => 'Otra consulta',
    ])->assertSessionHasErrors('time');

    $appointment = Appointment::query()->firstOrFail();
    $this->actingAs(adminUser())->patch(route('admin.appointments.status', $appointment), [
        'status' => AppointmentStatus::Cancelled->value,
    ])->assertRedirect();

    $this->actingAs($secondClient)->post(route('client.appointments.store'), [
        'date' => '2026-11-16',
        'time' => '10:00',
        'topic' => 'Otra consulta',
    ])->assertRedirect();

    expect(Appointment::query()->count())->toBe(2);
});

it('permite personalizar días, horarios y duración desde el admin', function () {
    $this->actingAs(adminUser())
        ->get(route('admin.appointments.schedule'))
        ->assertOk()
        ->assertSee('Días habilitados');

    $this->put(route('admin.appointments.schedule.update'), [
        'open_days' => ['6'],
        'open_time' => '10:00',
        'close_time' => '12:00',
        'duration_minutes' => 60,
    ])->assertRedirect();

    $schedule = AppointmentSchedule::query()->firstOrFail();
    expect($schedule->open_days)->toBe([6])
        ->and($schedule->duration_minutes)->toBe(60);

    $availability = app(AppointmentAvailabilityService::class);
    expect($availability->slotsForDate('2026-11-16', $schedule))->toHaveCount(0)
        ->and($availability->slotsForDate('2026-11-21', $schedule)->map(fn ($slot) => $slot->format('H:i'))->all())
        ->toBe(['10:00', '11:00']);
});

it('valida la configuración de días y horario del calendario', function () {
    $this->actingAs(adminUser())
        ->put(route('admin.appointments.schedule.update'), [
            'open_days' => [],
            'open_time' => '17:00',
            'close_time' => '09:00',
            'duration_minutes' => 17,
        ])
        ->assertSessionHasErrors(['open_days', 'close_time', 'duration_minutes']);
});

it('exige tema de interés y autenticación de cliente para solicitar una cita', function () {
    $client = customerUser();

    $this->actingAs($client)
        ->post(route('client.appointments.store'), [
            'date' => '2026-11-16',
            'time' => '11:00',
        ])
        ->assertSessionHasErrors('topic');

    auth()->logout();
    $this->get(route('client.appointments.index'))->assertRedirect(route('login'));
});
