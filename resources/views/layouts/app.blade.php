<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'Software que simplifica la gestión de tu empresa')">
    <meta name="keywords" content="@yield('meta_keywords', 'software, sistemas informáticos, facturación, gestión comercial, abono mensual')">
    <meta name="robots" content="@yield('meta_robots', 'index, follow')">
    <link rel="canonical" href="@yield('canonical', url()->current())">
    <meta property="og:site_name" content="{{ $company['company_name'] ?? config('app.name') }}">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('og_title', Str::limit(View::getSection('title', config('app.name')), 60))">
    <meta property="og:description" content="@yield('og_description', View::getSection('meta_description', 'Software que simplifica la gestión de tu empresa'))">
    <meta property="og:url" content="@yield('canonical', url()->current())">
    @if (! empty($company['company_logo']))
        <meta property="og:image" content="{{ asset('storage/' . $company['company_logo']) }}">
    @endif
    @if (! empty($company['company_logo']))
        <link rel="icon" type="image/jpeg" href="{{ asset('storage/' . $company['company_logo']) }}">
    @else
        <link rel="icon" type="image/jpeg" href="{{ asset('assets/brand/logo.jpeg') }}">
    @endif
    <meta name="twitter:card" content="summary_large_image">
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <style>
        :root {
            --brand-primary: {{ $company['brand_primary_color'] ?? '#2563eb' }};
            --brand-secondary: {{ $company['brand_secondary_color'] ?? '#7c3aed' }};
            --brand-ink: #0f172a;
            --brand-body: #475569;
            --brand-bg: #f8fafc;
        }
    </style>
    @stack('head')
</head>
<body class="@yield('body-class', '')">
    @yield('content')
    @stack('scripts')
</body>
</html>
