<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\CustomerSystemStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreContractRequest;
use App\Http\Requests\Admin\UpdateContractRequest;
use App\Models\Customer;
use App\Models\CustomerSystem;
use App\Models\System;
use App\Services\Support\AuditService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function __construct(private readonly AuditService $audit) {}

    public function index(Request $request): View
    {
        $query = CustomerSystem::query()
            ->with(['customer:id,first_name,last_name,code', 'system:id,name'])
            ->withCount('installments');

        if ($search = trim((string) $request->input('q'))) {
            $query->where(function ($builder) use ($search): void {
                $builder->where('contract_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', fn ($q) => $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%"));
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        return view('admin.contracts.index', [
            'contracts' => $query->latest()->paginate(20)->withQueryString(),
            'statuses' => CustomerSystemStatus::cases(),
            'filters' => [
                'q' => $request->input('q'),
                'status' => $request->input('status'),
            ],
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.contracts.form', [
            'contract' => new CustomerSystem,
            'customers' => Customer::orderBy('first_name')->get(),
            'systems' => System::with('plans')->orderBy('name')->get(),
            'plans' => collect(),
            'statuses' => CustomerSystemStatus::cases(),
            'preselectedCustomer' => $request->integer('customer') ?: null,
        ]);
    }

    public function store(StoreContractRequest $request): RedirectResponse
    {
        $contract = CustomerSystem::create($request->validated());

        $contract->license()->create([
            'license_key' => self::generateLicenseKey(),
            'status' => 'pending',
        ]);

        $this->audit->model('created', $contract);

        return redirect()
            ->route('admin.contracts.show', $contract)
            ->with('status', "Contrato {$contract->contract_number} creado correctamente.");
    }

    public function show(CustomerSystem $contract): View
    {
        $contract->load([
            'customer',
            'system',
            'plan',
            'license',
            'installments' => fn ($query) => $query->latest('period_start'),
        ]);

        return view('admin.contracts.show', ['contract' => $contract]);
    }

    public function edit(CustomerSystem $contract): View
    {
        return view('admin.contracts.form', [
            'contract' => $contract,
            'customers' => Customer::orderBy('first_name')->get(),
            'systems' => System::with('plans')->orderBy('name')->get(),
            'plans' => $contract->system?->plans ?? collect(),
            'statuses' => CustomerSystemStatus::cases(),
            'preselectedCustomer' => $contract->customer_id,
        ]);
    }

    public function update(UpdateContractRequest $request, CustomerSystem $contract): RedirectResponse
    {
        $contract->update($request->validated());

        $this->audit->model('updated', $contract);

        return redirect()
            ->route('admin.contracts.show', $contract)
            ->with('status', "Contrato {$contract->contract_number} actualizado correctamente.");
    }

    public function destroy(CustomerSystem $contract): RedirectResponse
    {
        $this->audit->model('deleted', $contract);

        $contract->delete();

        return redirect()
            ->route('admin.contracts.index')
            ->with('status', "Contrato {$contract->contract_number} eliminado.");
    }

    public static function generateLicenseKey(): string
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $block = static fn (): string => implode('', array_map(
            static fn () => $alphabet[random_int(0, 25)],
            range(1, 4),
        ));

        return implode('-', array_map($block, range(1, 4)));
    }
}
