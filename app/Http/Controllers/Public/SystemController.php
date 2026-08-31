<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\System;
use Illuminate\Contracts\View\View;

class SystemController extends Controller
{
    public function show(System $system): View
    {
        abort_unless($system->is_published, 404);

        $system->load(['features', 'plans']);

        return view('landing.systems.show', [
            'system' => $system,
            'systems' => System::published()->orderBy('sort_order')->get(),
        ]);
    }
}
