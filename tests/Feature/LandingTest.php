<?php

declare(strict_types=1);

use App\Enums\DemoRequestStatus;
use App\Models\DemoRequest;
use App\Models\DemoRequestLog;
use App\Models\System;
use App\Models\SystemFeature;
use App\Models\SystemPlan;

test('la landing muestra los sistemas publicados', function () {
    $published = System::factory()->create(['name' => 'Sistema Visible']);
    $unpublished = System::factory()->unpublished()->create(['name' => 'Sistema Oculto']);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Sistema Visible')
        ->assertDontSee('Sistema Oculto');
});

test('la landing enlaza al detalle de cada sistema', function () {
    System::factory()->create(['name' => 'Mi Sistema']);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee(route('systems.show', System::first()), false);
});

test('el detalle de sistema publica sus características y planes', function () {
    $system = System::factory()->create(['name' => 'Gestión Test']);
    $feature = SystemFeature::factory()->create(['system_id' => $system->id, 'name' => 'Facturación']);
    $plan = SystemPlan::factory()->create(['system_id' => $system->id, 'name' => 'Profesional', 'price' => 50000]);

    $this->get(route('systems.show', $system))
        ->assertOk()
        ->assertSee('Gestión Test')
        ->assertSee('Facturación')
        ->assertSee('Profesional')
        ->assertSee('50.000');
});

test('un sistema no publicado devuelve 404 en el detalle', function () {
    $system = System::factory()->unpublished()->create();

    $this->get(route('systems.show', $system))->assertNotFound();
});

test('un slug inexistente devuelve 404', function () {
    $this->get('/sistemas/no-existe')->assertNotFound();
});

test('el sitemap lista la home y los sistemas publicados', function () {
    $published = System::factory()->create();
    $unpublished = System::factory()->unpublished()->create();

    $this->get('/sitemap.xml')
        ->assertOk()
        ->assertHeader('Content-Type', 'application/xml')
        ->assertSee(route('home'), false)
        ->assertSee(route('systems.show', $published), false)
        ->assertDontSee(route('systems.show', $unpublished), false);
});

test('el robots.txt permite el rastreo general y bloquea áreas privadas', function () {
    $this->get('/robots.txt')
        ->assertOk()
        ->assertSee('User-agent: *')
        ->assertSee('Disallow: /admin')
        ->assertSee('Disallow: /cliente')
        ->assertSee(route('sitemap'));
});

test('el envío válido de demo crea la solicitud y su log', function () {
    $system = System::factory()->create();

    $this->post(route('demo.store'), [
        'first_name' => 'Ana',
        'last_name' => 'López',
        'email' => 'ana@example.com',
        'system_id' => $system->id,
        'comments' => 'Quiero una demo',
    ])->assertRedirect();

    $this->assertDatabaseHas('demo_requests', [
        'email' => 'ana@example.com',
        'system_id' => $system->id,
        'status' => DemoRequestStatus::New->value,
    ]);

    $demo = DemoRequest::where('email', 'ana@example.com')->firstOrFail();
    expect($demo->logs)->toHaveCount(1)
        ->and($demo->logs->first()->new_status)->toBe(DemoRequestStatus::New->value);
});

test('el envío de demo con honeypot relleno no crea registros', function () {
    $this->post(route('demo.store'), [
        'first_name' => 'Bot',
        'last_name' => 'Bot',
        'email' => 'bot@example.com',
        'company_website' => 'http://spam.example.com',
    ])->assertRedirect();

    expect(DemoRequest::query()->count())->toBe(0)
        ->and(DemoRequestLog::query()->count())->toBe(0);
});

test('el envío de demo con datos inválidos devuelve errores de validación', function () {
    $this->post(route('demo.store'), [
        'first_name' => '',
        'last_name' => '',
        'email' => 'no-es-un-mail',
    ])->assertSessionHasErrors(['first_name', 'last_name', 'email']);
});
