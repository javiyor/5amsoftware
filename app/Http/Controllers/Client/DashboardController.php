<?php

declare(strict_types=1);

namespace App\Http\Controllers\Client;

use Illuminate\View\View;

class DashboardController
{
    public function index(): View
    {
        return view('client.dashboard');
    }
}
