<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\CustomerStatus;
use App\Enums\CustomerSystemStatus;
use App\Enums\DemoRequestStatus;
use App\Enums\InstallmentStatus;
use App\Enums\PaymentStatus;
use App\Models\Customer;
use App\Models\CustomerSystem;
use App\Models\DemoRequest;
use App\Models\Installment;
use App\Models\Payment;
use Illuminate\Contracts\View\View;

class DashboardController
{
    public function index(): View
    {
        $activeCustomers = Customer::where('status', CustomerStatus::Active->value)->count();
        $activeContracts = CustomerSystem::where('status', CustomerSystemStatus::Active->value)->count();
        $overdueInstallments = Installment::where('status', InstallmentStatus::Overdue->value)->count();
        $pendingDemos = DemoRequest::where('status', DemoRequestStatus::New->value)->count();

        $collectedThisMonth = (float) Payment::where('status', PaymentStatus::Approved->value)
            ->whereBetween('paid_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('amount');

        $blockedCustomers = CustomerSystem::whereIn('status', [
            CustomerSystemStatus::Suspended->value,
            CustomerSystemStatus::Overdue->value,
        ])->distinct()->count('customer_id');

        return view('admin.dashboard', [
            'activeCustomers' => $activeCustomers,
            'activeContracts' => $activeContracts,
            'overdueInstallments' => $overdueInstallments,
            'pendingDemos' => $pendingDemos,
            'collectedThisMonth' => $collectedThisMonth,
            'blockedCustomers' => $blockedCustomers,
            'recentCustomers' => Customer::withCount('customerSystems')->latest()->limit(5)->get(),
            'recentDemos' => DemoRequest::with('system')->latest()->limit(5)->get(),
        ]);
    }
}
