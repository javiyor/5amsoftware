<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\System;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $urls = collect([route('home')]);
        $urls = $urls->merge(
            System::published()->orderBy('sort_order')->get()
                ->map(fn (System $system) => route('systems.show', $system))
        );

        $content = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $content .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach ($urls as $url) {
            $content .= '  <url><loc>'.e($url)."</loc></url>\n";
        }

        $content .= '</urlset>';

        return response($content, 200, ['Content-Type' => 'application/xml']);
    }

    public function robots(): Response
    {
        $content = implode("\n", [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            'Disallow: /cliente',
            'Disallow: /login',
            'Disallow: /olvide-contrasena',
            'Disallow: /restablecer-contrasena',
            'Disallow: /verificar-correo',
            'Disallow: /cambiar-contrasena',
            '',
            'Sitemap: '.route('sitemap'),
            '',
        ]);

        return response($content, 200, ['Content-Type' => 'text/plain']);
    }
}
