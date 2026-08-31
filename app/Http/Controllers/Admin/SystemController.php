<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSystemRequest;
use App\Http\Requests\Admin\UpdateSystemRequest;
use App\Models\System;
use App\Models\SystemFeature;
use App\Services\Support\AuditService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SystemController extends Controller
{
    public function __construct(private readonly AuditService $audit) {}

    public function index(Request $request): View
    {
        $query = System::query()->withCount('plans', 'customerSystems');

        if ($search = trim((string) $request->input('q'))) {
            $query->where(function ($builder) use ($search): void {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->has('published') && $request->input('published') !== '') {
            $query->where('is_published', (bool) $request->input('published'));
        }

        return view('admin.systems.index', [
            'systems' => $query->orderBy('sort_order')->orderBy('name')->paginate(20)->withQueryString(),
            'filters' => [
                'q' => $request->input('q'),
                'published' => $request->input('published'),
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.systems.form', ['system' => new System]);
    }

    public function store(StoreSystemRequest $request): RedirectResponse
    {
        $system = System::create($request->validated());

        $this->audit->model('created', $system);

        return redirect()
            ->route('admin.systems.show', $system)
            ->with('status', "Sistema {$system->name} creado correctamente.");
    }

    public function show(System $system): View
    {
        $system->load(['features', 'plans']);

        return view('admin.systems.show', ['system' => $system]);
    }

    public function edit(System $system): View
    {
        return view('admin.systems.form', ['system' => $system]);
    }

    public function update(UpdateSystemRequest $request, System $system): RedirectResponse
    {
        $system->update($request->validated());

        $this->audit->model('updated', $system);

        return redirect()
            ->route('admin.systems.show', $system)
            ->with('status', 'Sistema actualizado correctamente.');
    }

    public function destroy(System $system): RedirectResponse
    {
        $this->audit->model('deleted', $system);

        $system->delete();

        return redirect()
            ->route('admin.systems.index')
            ->with('status', "Sistema {$system->name} eliminado.");
    }

    public function storeFeature(Request $request, System $system): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:180'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $feature = $system->features()->create($data);

        $this->audit->model('created', $feature);

        return redirect()
            ->route('admin.systems.show', $system)
            ->with('status', 'Característica agregada.');
    }

    public function destroyFeature(System $system, SystemFeature $feature): RedirectResponse
    {
        abort_unless($feature->system_id === $system->id, 404);

        $this->audit->model('deleted', $feature);

        $feature->delete();

        return redirect()
            ->route('admin.systems.show', $system)
            ->with('status', 'Característica eliminada.');
    }
}
