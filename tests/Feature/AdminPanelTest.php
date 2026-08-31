<?php

declare(strict_types=1);

use App\Enums\CustomerStatus;
use App\Enums\CustomerSystemStatus;
use App\Enums\DemoRequestStatus;
use App\Models\Customer;
use App\Models\CustomerSystem;
use App\Models\DemoRequest;
use App\Models\Installment;
use App\Models\License;
use App\Models\Payment;
use App\Models\System;
use App\Models\SystemFeature;
use App\Models\SystemPlan;
use Illuminate\Support\Facades\DB;

it('el dashboard muestra las métricas reales', function () {
    $this->actingAs(adminUser());

    Customer::factory()->active()->count(2)->create();
    $contract = CustomerSystem::factory()->create(['status' => CustomerSystemStatus::Active]);
    Installment::factory()->overdue()->create([
        'customer_system_id' => $contract->id,
        'customer_id' => $contract->customer_id,
    ]);
    Payment::factory()->approved()->create(['amount' => 123456.00]);
    DemoRequest::factory()->create(['status' => DemoRequestStatus::New]);

    $this->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('2')
        ->assertSee('1')
        ->assertSee('123.456');
});

it('el dashboard no estalla sin datos', function () {
    $this->actingAs(adminUser());

    $this->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Panel de administración');
});

it('permite listar clientes en el panel', function () {
    Customer::factory()->active()->create(['first_name' => 'María', 'last_name' => 'Gómez']);

    $this->actingAs(adminUser())
        ->get(route('admin.customers.index'))
        ->assertOk()
        ->assertSee('María Gómez');
});

it('permite ver el detalle de un cliente con sus contratos', function () {
    $customer = Customer::factory()->active()->create(['first_name' => 'Ana', 'last_name' => 'Pérez']);
    $contract = CustomerSystem::factory()->create(['customer_id' => $customer->id]);

    $this->actingAs(adminUser())
        ->get(route('admin.customers.show', $customer))
        ->assertOk()
        ->assertSee('Ana Pérez')
        ->assertSee($contract->contract_number);
});

it('renderiza el formulario de nuevo cliente', function () {
    $this->actingAs(adminUser())
        ->get(route('admin.customers.create'))
        ->assertOk()
        ->assertSee('Nuevo cliente');
});

it('crea un cliente y registra la auditoría', function () {
    $this->actingAs(adminUser());

    $this->post(route('admin.customers.store'), [
        'first_name' => 'Juan',
        'last_name' => 'López',
        'email' => 'juan@example.com',
        'status' => CustomerStatus::Lead->value,
    ])->assertRedirect();

    $this->assertDatabaseHas('customers', [
        'email' => 'juan@example.com',
        'status' => CustomerStatus::Lead->value,
    ]);

    $customer = Customer::where('email', 'juan@example.com')->firstOrFail();
    expect($customer->code)->toMatch('/^CLI-\d{6}$/');

    $this->assertDatabaseHas('audit_logs', [
        'entity_type' => Customer::class,
        'entity_id' => $customer->id,
        'action' => 'created',
    ]);
});

it('valida los datos al crear un cliente', function () {
    $this->actingAs(adminUser());

    $this->post(route('admin.customers.store'), [
        'first_name' => '',
        'last_name' => '',
        'email' => 'no-es-un-mail',
        'status' => 'invalido',
    ])->assertSessionHasErrors(['first_name', 'last_name', 'email', 'status']);
});

it('edita un cliente', function () {
    $customer = Customer::factory()->create(['first_name' => 'Original']);

    $this->actingAs(adminUser())
        ->put(route('admin.customers.update', $customer), [
            'first_name' => 'Nuevo',
            'last_name' => $customer->last_name,
            'email' => $customer->email,
            'status' => $customer->status->value,
        ])
        ->assertRedirect();

    expect($customer->fresh()->first_name)->toBe('Nuevo');
    $this->assertDatabaseHas('audit_logs', [
        'entity_type' => Customer::class,
        'entity_id' => $customer->id,
        'action' => 'updated',
    ]);
});

it('elimina un cliente de forma blanda', function () {
    $customer = Customer::factory()->create();

    $this->actingAs(adminUser())
        ->delete(route('admin.customers.destroy', $customer))
        ->assertRedirect(route('admin.customers.index'));

    expect(Customer::withTrashed()->find($customer->id))->not->toBeNull()
        ->and(Customer::find($customer->id))->toBeNull();
});

it('permite listar sistemas en el panel', function () {
    $system = System::factory()->create(['name' => 'Sistema de Gestión']);

    $this->actingAs(adminUser())
        ->get(route('admin.systems.index'))
        ->assertOk()
        ->assertSee('Sistema de Gestión');
});

it('renderiza el formulario de nuevo sistema', function () {
    $this->actingAs(adminUser())
        ->get(route('admin.systems.create'))
        ->assertOk()
        ->assertSee('Nuevo sistema');
});

it('renderiza el formulario de edición de plan', function () {
    $plan = SystemPlan::factory()->create(['name' => 'Avanzado']);

    $this->actingAs(adminUser())
        ->get(route('admin.plans.edit', [$plan->system, $plan]))
        ->assertOk()
        ->assertSee('Editar plan')
        ->assertSee('Avanzado');
});

it('crea un sistema y aparece en la landing si está publicado', function () {
    $this->actingAs(adminUser());

    $this->post(route('admin.systems.store'), [
        'name' => 'Gestión Comercial Nuevo',
        'short_description' => 'Administra ventas',
        'description' => 'Descripción completa del sistema.',
        'price_from' => 80000,
        'installation_price' => 15000,
        'is_published' => 1,
    ])->assertRedirect();

    $this->assertDatabaseHas('systems', ['name' => 'Gestión Comercial Nuevo']);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Gestión Comercial Nuevo');
});

it('permite agregar y quitar características de un sistema', function () {
    $system = System::factory()->create();

    $this->actingAs(adminUser());

    $this->post(route('admin.systems.features.store', $system), [
        'name' => 'Multiusuario',
    ])->assertRedirect();

    $feature = SystemFeature::where('system_id', $system->id)->where('name', 'Multiusuario')->firstOrFail();
    expect($feature->system->is($system))->toBeTrue();

    $this->delete(route('admin.systems.features.destroy', [$system, $feature]))->assertRedirect();

    expect(SystemFeature::find($feature->id))->toBeNull();
});

it('crea un plan para un sistema', function () {
    $system = System::factory()->create();

    $this->actingAs(adminUser())
        ->post(route('admin.plans.store', $system), [
            'name' => 'Profesional',
            'price' => 50000,
            'setup_price' => 10000,
            'billing_interval_days' => 30,
            'grace_days' => 10,
            'max_users' => 10,
        ])->assertRedirect();

    $plan = SystemPlan::where('system_id', $system->id)->where('name', 'Profesional')->firstOrFail();
    expect($plan->slug)->toBe('profesional');
});

it('edita un plan', function () {
    $plan = SystemPlan::factory()->create(['name' => 'Básico', 'price' => 30000]);

    $this->actingAs(adminUser())
        ->put(route('admin.plans.update', [$plan->system, $plan]), [
            'name' => 'Avanzado',
            'price' => 60000,
            'setup_price' => 12000,
            'billing_interval_days' => 30,
            'grace_days' => 15,
            'max_users' => 50,
            'is_active' => 1,
        ])
        ->assertRedirect();

    expect($plan->fresh()->name)->toBe('Avanzado')
        ->and((float) $plan->fresh()->price)->toBe(60000.0);
});

it('impide eliminar un plan con contratos asociados', function () {
    $plan = SystemPlan::factory()->create();
    CustomerSystem::factory()->create(['system_plan_id' => $plan->id]);

    $this->actingAs(adminUser())
        ->delete(route('admin.plans.destroy', [$plan->system, $plan]))
        ->assertRedirect()
        ->assertSessionHas('warning');

    expect(SystemPlan::find($plan->id))->not->toBeNull();
});

it('permite listar contratos', function () {
    $contract = CustomerSystem::factory()->create();

    $this->actingAs(adminUser())
        ->get(route('admin.contracts.index'))
        ->assertOk()
        ->assertSee($contract->contract_number);
});

it('renderiza el formulario de contrato con datos de planes para auto-relleno', function () {
    $customer = Customer::factory()->create();
    $system = System::factory()->create();
    SystemPlan::factory()->create(['system_id' => $system->id, 'name' => 'Estandar']);

    $this->actingAs(adminUser())
        ->get(route('admin.contracts.create', ['customer' => $customer->id]))
        ->assertOk()
        ->assertSee('Estandar')
        ->assertSee('data-plans')
        ->assertSee('billing_interval_days');
});

it('crea un contrato y su licencia automáticamente', function () {
    $this->actingAs(adminUser());

    $customer = Customer::factory()->create();
    $system = System::factory()->create();
    $plan = SystemPlan::factory()->create(['system_id' => $system->id]);

    $this->post(route('admin.contracts.store'), [
        'customer_id' => $customer->id,
        'system_id' => $system->id,
        'system_plan_id' => $plan->id,
        'billing_interval_days' => 30,
        'grace_days' => 10,
        'monthly_amount' => 55000,
        'setup_amount' => 0,
        'currency' => 'ARS',
        'automatic_billing' => 1,
        'automatic_suspension' => 1,
        'status' => CustomerSystemStatus::Active->value,
    ])->assertRedirect();

    $contract = CustomerSystem::where('customer_id', $customer->id)->firstOrFail();

    expect($contract->contract_number)->toMatch('/^CTR-\d{6}$/');

    $this->assertDatabaseHas('licenses', [
        'customer_system_id' => $contract->id,
        'status' => 'pending',
    ]);

    $license = License::where('customer_system_id', $contract->id)->firstOrFail();
    expect($license->license_key)->toMatch('/^[A-Z]{4}-[A-Z]{4}-[A-Z]{4}-[A-Z]{4}$/');
});

it('valida los datos al crear un contrato', function () {
    $this->actingAs(adminUser());

    $this->post(route('admin.contracts.store'), [
        'customer_id' => 99999,
        'billing_interval_days' => 0,
        'monthly_amount' => -5,
        'status' => 'invalido',
    ])->assertSessionHasErrors(['customer_id', 'billing_interval_days', 'monthly_amount', 'status']);
});

it('muestra el detalle de un contrato con su licencia', function () {
    $contract = CustomerSystem::factory()->create(['monthly_amount' => 88000]);
    License::factory()->create([
        'customer_system_id' => $contract->id,
        'license_key' => 'AAAA-BBBB-CCCC-DDDD',
    ]);

    $this->actingAs(adminUser())
        ->get(route('admin.contracts.show', $contract))
        ->assertOk()
        ->assertSee($contract->contract_number)
        ->assertSee('AAAA-BBBB-CCCC-DDDD')
        ->assertSee('88.000');
});

it('edita un contrato', function () {
    $contract = CustomerSystem::factory()->create(['monthly_amount' => 30000]);

    $this->actingAs(adminUser())
        ->put(route('admin.contracts.update', $contract), [
            'system_id' => $contract->system_id,
            'billing_interval_days' => 30,
            'grace_days' => 10,
            'monthly_amount' => 45000,
            'setup_amount' => 0,
            'currency' => 'ARS',
            'automatic_billing' => 1,
            'automatic_suspension' => 1,
            'status' => $contract->status->value,
        ])
        ->assertRedirect();

    expect((float) $contract->fresh()->monthly_amount)->toBe(45000.0);
});

it('elimina un contrato', function () {
    $contract = CustomerSystem::factory()->create();

    $this->actingAs(adminUser())
        ->delete(route('admin.contracts.destroy', $contract))
        ->assertRedirect(route('admin.contracts.index'));

    expect(CustomerSystem::withTrashed()->find($contract->id))->not->toBeNull();
});

it('permite listar solicitudes de demo', function () {
    DemoRequest::factory()->create(['first_name' => 'Laura', 'last_name' => 'Díaz']);

    $this->actingAs(adminUser())
        ->get(route('admin.demos.index'))
        ->assertOk()
        ->assertSee('Laura Díaz');
});

it('muestra el detalle de una solicitud de demo con su historial', function () {
    $demo = DemoRequest::factory()->create(['first_name' => 'Carlos', 'last_name' => 'Méndez']);

    $this->actingAs(adminUser())
        ->get(route('admin.demos.show', $demo))
        ->assertOk()
        ->assertSee('Carlos Méndez');
});

it('actualiza el estado de una demo y registra el cambio', function () {
    $admin = adminUser();
    $demo = DemoRequest::factory()->create(['status' => DemoRequestStatus::New]);

    $this->actingAs($admin)
        ->put(route('admin.demos.update-status', $demo), [
            'status' => DemoRequestStatus::Contacted->value,
            'comment' => 'Se contactó por teléfono',
        ])
        ->assertRedirect();

    expect($demo->fresh()->status)->toBe(DemoRequestStatus::Contacted);

    $this->assertDatabaseHas('demo_request_logs', [
        'demo_request_id' => $demo->id,
        'user_id' => $admin->id,
        'old_status' => DemoRequestStatus::New->value,
        'new_status' => DemoRequestStatus::Contacted->value,
        'comment' => 'Se contactó por teléfono',
    ]);
});

it('al convertir una demo exige asociar un cliente', function () {
    $demo = DemoRequest::factory()->create(['status' => DemoRequestStatus::New]);

    $this->actingAs(adminUser())
        ->put(route('admin.demos.update-status', $demo), [
            'status' => DemoRequestStatus::Converted->value,
        ])
        ->assertSessionHasErrors('converted_customer_id');
});

it('convierte una demo asociando el cliente', function () {
    $customer = Customer::factory()->create();
    $demo = DemoRequest::factory()->create(['status' => DemoRequestStatus::Completed]);

    $this->actingAs(adminUser())
        ->put(route('admin.demos.update-status', $demo), [
            'status' => DemoRequestStatus::Converted->value,
            'converted_customer_id' => $customer->id,
        ])
        ->assertRedirect();

    expect($demo->fresh()->status)->toBe(DemoRequestStatus::Converted)
        ->and($demo->fresh()->converted_customer_id)->toBe($customer->id);
});

it('los índices de la base de datos cubren las búsquedas del panel', function () {
    $indexes = collect(DB::select('PRAGMA index_list(customer_systems)'))->pluck('name');

    expect($indexes->contains('customer_systems_customer_id_index'))->toBeTrue()
        ->and($indexes->contains('customer_systems_status_index'))->toBeTrue();
});
