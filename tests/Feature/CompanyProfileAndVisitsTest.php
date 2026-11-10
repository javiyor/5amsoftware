<?php

declare(strict_types=1);

use App\Models\System;
use App\Services\Support\CompanySettingsService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

it('permite al administrador editar datos personales y foto del perfil público', function () {
    Storage::fake('public');
    $this->actingAs(adminUser());

    $this->get(route('admin.company-profile.edit'))
        ->assertOk()
        ->assertSee('Perfil público')
        ->assertSee('Visitas públicas totales');

    $this->put(route('admin.company-profile.update'), [
        'company_profile_name' => 'Javier Gómez',
        'company_cuit' => '20-12345678-9',
        'company_address' => 'Av. Siempre Viva 123, Córdoba',
        'company_profession' => 'Desarrollo de software',
        'company_profile_photo' => UploadedFile::fake()->image('perfil.jpg'),
    ])->assertRedirect(route('admin.company-profile.edit'));

    $settings = app(CompanySettingsService::class)->all();
    expect($settings['company_profile_name'])->toBe('Javier Gómez')
        ->and($settings['company_name'])->toBe('5AM Software')
        ->and($settings['company_cuit'])->toBe('20-12345678-9')
        ->and($settings['company_address'])->toBe('Av. Siempre Viva 123, Córdoba')
        ->and($settings['company_profession'])->toBe('Desarrollo de software');

    Storage::disk('public')->assertExists($settings['company_profile_photo']);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Javier Gómez')
        ->assertSee('20-12345678-9')
        ->assertSee('Desarrollo de software');
});

it('muestra el video promocional institucional de YouTube en la landing', function () {
    $this->actingAs(adminUser())
        ->put(route('admin.company-profile.update'), [
            'company_profile_name' => 'Javier Gómez',
            'company_promo_video_url' => 'https://youtu.be/abcdefghijk',
        ])->assertRedirect();

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('https://www.youtube-nocookie.com/embed/abcdefghijk', false);
});

it('permite subir y publicar un video promocional', function () {
    Storage::fake('public');

    $this->actingAs(adminUser())
        ->put(route('admin.company-profile.update'), [
            'company_profile_name' => '5AM Software',
            'company_promo_video' => UploadedFile::fake()->create('presentacion.mp4', 256, 'video/mp4'),
        ])->assertRedirect();

    $videoPath = app(CompanySettingsService::class)->get('company_promo_video');
    Storage::disk('public')->assertExists($videoPath);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee(asset('storage/'.$videoPath), false);
});

it('permite cargar un video explicativo en un sistema y lo publica en su ficha', function () {
    Storage::fake('public');
    $admin = adminUser();
    $system = System::factory()->create();

    $this->actingAs($admin)
        ->put(route('admin.systems.update', $system), [
            'name' => $system->name,
            'slug' => $system->slug,
            'short_description' => $system->short_description,
            'description' => $system->description,
            'promo_video_url' => 'https://youtu.be/abcdefghijk',
        ])->assertRedirect();

    expect($system->fresh()->promo_video_url)->toBe('https://youtu.be/abcdefghijk');

    $this->get(route('systems.show', $system))
        ->assertOk()
        ->assertSee('https://www.youtube-nocookie.com/embed/abcdefghijk', false);
});

it('incrementa contadores de visitas públicas y los muestra en el admin', function () {
    $system = System::factory()->create();

    $this->get(route('home'))->assertOk();
    $this->get(route('systems.show', $system))->assertOk();

    expect((int) DB::table('site_visit_counters')->where('key', 'total')->value('visits'))->toBe(2)
        ->and((int) DB::table('site_visit_counters')->where('key', 'home')->value('visits'))->toBe(1)
        ->and((int) DB::table('site_visit_counters')->where('key', 'systems')->value('visits'))->toBe(1)
        ->and((int) DB::table('site_visit_counters')->where('key', 'system:'.$system->id)->value('visits'))->toBe(1);

    $this->actingAs(adminUser())
        ->get(route('admin.company-profile.edit'))
        ->assertOk()
        ->assertSee('Visitas públicas totales')
        ->assertSee('>2<', false);
});

it('no acepta archivos que no sean imágenes permitidas como foto de perfil', function () {
    $this->actingAs(adminUser())
        ->put(route('admin.company-profile.update'), [
            'company_profile_name' => '5AM Software',
            'company_profile_photo' => UploadedFile::fake()->create('foto.txt', 20, 'text/plain'),
        ])
        ->assertSessionHasErrors('company_profile_photo');
});

it('no acepta archivos que no sean formatos de video admitidos', function () {
    $this->actingAs(adminUser())
        ->put(route('admin.company-profile.update'), [
            'company_profile_name' => '5AM Software',
            'company_promo_video' => UploadedFile::fake()->create('video.txt', 20, 'text/plain'),
        ])
        ->assertSessionHasErrors('company_promo_video');
});
