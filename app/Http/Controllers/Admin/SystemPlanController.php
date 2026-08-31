<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSystemPlanRequest;
use App\Http\Requests\Admin\UpdateSystemPlanRequest;
use App\Models\System;
use App\Models\SystemPlan;
use App\Services\Support\AuditService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class SystemPlanController extends Controller
{
    public function __construct(private readonly AuditService $audit) {}

    public function store(StoreSystemPlanRequest $request, System $system): RedirectResponse
    {
        $plan = $system->plans()->create($request->validated());

        $this->audit->model('created', $plan);

        return redirect()
            ->route('admin.systems.show', $system)
            ->with('status', "Plan {$plan->name} creado correctamente.");
    }

    public function edit(System $system, SystemPlan $plan): View
    {
        abort_unless($plan->system_id === $system->id, 404);

        return view('admin.plans.form', [
            'system' => $system,
            'plan' => $plan,
        ]);
    }

    public function update(UpdateSystemPlanRequest $request, System $system, SystemPlan $plan): RedirectResponse
    {
        abort_unless($plan->system_id === $system->id, 404);

        $plan->update($request->validated());

        $this->audit->model('updated', $plan);

        return redirect()
            ->route('admin.systems.show', $plan->system)
            ->with('status', "Plan {$plan->name} actualizado correctamente.");
    }

    public function destroy(System $system, SystemPlan $plan): RedirectResponse
    {
        abort_unless($plan->system_id === $system->id, 404);

        if ($plan->customerSystems()->exists()) {
            return redirect()
                ->route('admin.systems.show', $system)
                ->with('warning', 'No se puede eliminar el plan porque tiene contratos asociados.');
        }

        $this->audit->model('deleted', $plan);

        $plan->delete();

        return redirect()
            ->route('admin.systems.show', $system)
            ->with('status', "Plan {$plan->name} eliminado.");
    }
}
