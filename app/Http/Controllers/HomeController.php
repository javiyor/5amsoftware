<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\System;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('landing.home', [
            'systems' => System::published()->orderBy('sort_order')->get(),
        ]);
    }
}
