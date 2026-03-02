<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $metaDescription ?? 'Медицинская клиника. Запись к врачу онлайн. Найдите своего врача и запишитесь на приём.' }}">
    <meta name="keywords" content="{{ $metaKeywords ?? 'клиника, врач, медцентр, запись к врачу онлайн, анализы, консультация врача, медицинские услуги' }}">
    <meta name="robots" content="index, follow">

    @php
        $pageTitle = $metaTitle ?? config('app.name', 'Клиника');
        $pageDescription = $metaDescription ?? 'Медицинская клиника. Запись к врачу онлайн. Найдите своего врача и запишитесь на приём.';
        $defaultOgImage = null;

        if (file_exists(public_path('images/hero.avif'))) {
            $defaultOgImage = asset('images/hero.avif');
        } elseif (file_exists(public_path('images/hero.webp'))) {
            $defaultOgImage = asset('images/hero.webp');
        } elseif (file_exists(public_path('images/hero.jpeg'))) {
            $defaultOgImage = asset('images/hero.jpeg');
        } elseif (file_exists(public_path('images/hero.jpg'))) {
            $defaultOgImage = asset('images/hero.jpg');
        } else {
            $defaultOgImage = 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=800&h=600&fit=crop';
        }

        $pageOgImage = $metaImage ?? $defaultOgImage;
    @endphp

    <title>{{ $pageTitle }}</title>

    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $pageOgImage }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    <meta name="twitter:image" content="{{ $pageOgImage }}">
    
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/clinic.css'])
    @else
        <link rel="stylesheet" href="{{ asset('css/clinic.css') }}">
    @endif

    @stack('styles')
    @stack('head')
</head>
<body class="clinic-body">
    @include('partials.clinic.header')

    <main class="clinic-main">
        @yield('content')
    </main>

    @include('partials.clinic.footer')

    @stack('scripts')
</body>
</html>
