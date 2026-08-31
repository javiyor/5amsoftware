<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\CustomerStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCustomerRequest;
use App\Http\Requests\Admin\UpdateCustomerRequest;
use App\Models\Customer;
use App\Services\Support\AuditService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct(private readonly AuditService $audit) {}

    public function index(Request $request): View
    {
        $query = Customer::query()->withCount('customerSystems');

        if ($search = trim((string) $request->input('q'))) {
            $query->where(function ($builder) use ($search): void {
                $builder->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        return view('admin.customers.index', [
            'customers' => $query->latest()->paginate(20)->withQueryString(),
            'statuses' => CustomerStatus::cases(),
            'filters' => [
                'q' => $request->input('q'),
                'status' => $request->input('status'),
            ],
        ]);
    }

    public function show(Customer $customer): View
    {
        $customer->load([
            'customerSystems' => fn ($query) => $query->with(['system', 'plan', 'license'])->latest(),
            'payments' => fn ($query) => $query->latest()->limit(10),
        ]);

        return view('admin.customers.show', [
            'customer' => $customer,
        ]);
    }

    public function create(): View
    {
        return view('admin.customers.form', [
            'customer' => new Customer,
            'statuses' => CustomerStatus::cases(),
        ]);
    }

    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        $customer = Customer::create($request->validated());

        $this->audit->model('created', $customer);

        return redirect()
            ->route('admin.customers.show', $customer)
            ->with('status', "Cliente {$customer->code} creado correctamente.");
    }

    public function edit(Customer $customer): View
    {
        return view('admin.customers.form', [
            'customer' => $customer,
            'statuses' => CustomerStatus::cases(),
        ]);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer): RedirectResponse
    {
        $customer->update($request->validated());

        $this->audit->model('updated', $customer);

        return redirect()
            ->route('admin.customers.show', $customer)
            ->with('status', "Cliente {$customer->code} actualizado correctamente.");
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $this->audit->model('deleted', $customer);

        $customer->delete();

        return redirect()
            ->route('admin.customers.index')
            ->with('status', "Cliente {$customer->code} eliminado (papelera).");
    }
}
