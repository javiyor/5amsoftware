<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Enums\DemoRequestStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Public\StoreDemoRequestRequest;
use App\Models\DemoRequest;
use App\Models\DemoRequestLog;
use Illuminate\Http\RedirectResponse;

class DemoRequestController extends Controller
{
    public function store(StoreDemoRequestRequest $request): RedirectResponse
    {
        if ($request->filled('company_website')) {
            return $this->succeed();
        }

        $demo = DemoRequest::create([
            ...$request->validated(),
            'status' => DemoRequestStatus::New,
        ]);

        DemoRequestLog::create([
            'demo_request_id' => $demo->id,
            'old_status' => null,
            'new_status' => $demo->status->value,
        ]);

        return $this->succeed();
    }

    private function succeed(): RedirectResponse
    {
        return redirect()->back()
            ->with('demoSubmitted', true)
            ->with('success', '¡Gracias! Tu solicitud fue recibida. Nos pondremos en contacto a la brevedad.');
    }
}
