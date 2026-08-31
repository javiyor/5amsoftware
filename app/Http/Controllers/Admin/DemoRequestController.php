<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\DemoRequestStatus;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\DemoRequest;
use App\Models\DemoRequestLog;
use App\Services\Support\AuditService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DemoRequestController extends Controller
{
    public function __construct(private readonly AuditService $audit) {}

    public function index(Request $request): View
    {
        $query = DemoRequest::query()->with(['system', 'convertedCustomer']);

        if ($search = trim((string) $request->input('q'))) {
            $query->where(function ($builder) use ($search): void {
                $builder->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        return view('admin.demos.index', [
            'demos' => $query->latest()->paginate(20)->withQueryString(),
            'statuses' => DemoRequestStatus::cases(),
            'filters' => [
                'q' => $request->input('q'),
                'status' => $request->input('status'),
            ],
        ]);
    }

    public function show(DemoRequest $demo): View
    {
        $demo->load(['system', 'assignedTo', 'convertedCustomer', 'logs.user']);

        return view('admin.demos.show', [
            'demo' => $demo,
            'statuses' => DemoRequestStatus::cases(),
            'customers' => Customer::orderBy('first_name')->get(),
        ]);
    }

    public function updateStatus(Request $request, DemoRequest $demo): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::enum(DemoRequestStatus::class)],
            'converted_customer_id' => [
                'nullable',
                'integer',
                'exists:customers,id',
                Rule::requiredIf($request->input('status') === DemoRequestStatus::Converted->value),
            ],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        $oldStatus = $demo->status?->value;

        $demo->update([
            'status' => DemoRequestStatus::from($validated['status']),
            'converted_customer_id' => $validated['converted_customer_id'] ?? $demo->converted_customer_id,
        ]);

        DemoRequestLog::create([
            'demo_request_id' => $demo->id,
            'user_id' => $request->user()?->id,
            'old_status' => $oldStatus,
            'new_status' => $demo->status->value,
            'comment' => $validated['comment'] ?? null,
        ]);

        $this->audit->model('updated', $demo);

        return redirect()
            ->route('admin.demos.show', $demo)
            ->with('status', 'Estado de la solicitud actualizado correctamente.');
    }
}
