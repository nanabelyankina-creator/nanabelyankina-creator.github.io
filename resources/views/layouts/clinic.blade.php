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

    <script>
    (function() {
        function digits(v) { return String(v || '').replace(/\D/g, ''); }

        function formatPhoneRu(raw) {
            var v = digits(raw);
            if (!v) return '';
            if (v[0] === '8') v = '7' + v.slice(1);
            if (v[0] !== '7') v = '7' + v;
            if (v.length > 11) v = v.slice(0, 11);

            var out = '+7';
            if (v.length > 1) out += ' (' + v.slice(1, 4);
            if (v.length > 4) out += ') ' + v.slice(4, 7);
            if (v.length > 7) out += '-' + v.slice(7, 9);
            if (v.length > 9) out += '-' + v.slice(9, 11);
            return out;
        }

        function formatSnils(raw) {
            var v = digits(raw);
            if (!v) return '';
            if (v.length > 11) v = v.slice(0, 11);
            var out = '';
            if (v.length > 0) out += v.slice(0, 3);
            if (v.length > 3) out += '-' + v.slice(3, 6);
            if (v.length > 6) out += '-' + v.slice(6, 9);
            if (v.length > 9) out += ' ' + v.slice(9, 11);
            return out;
        }

        function bindMask(el, formatter) {
            if (!el) return;
            var handler = function() {
                var next = formatter(el.value);
                el.value = next;
            };
            el.addEventListener('input', handler);
            el.addEventListener('blur', handler);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // phone
            document.querySelectorAll('[data-mask="phone-ru"], input[name="phone"], #phone').forEach(function(el) {
                bindMask(el, formatPhoneRu);
            });

            // snils
            document.querySelectorAll('[data-mask="snils"], input[name="snils"], #snils').forEach(function(el) {
                bindMask(el, formatSnils);
            });

            // login identifier: phone/snils depending on selected radio
            var identifier = document.getElementById('identifier');
            var radios = document.querySelectorAll('input[name="login_type"]');
            if (identifier && radios && radios.length) {
                function currentType() {
                    var checked = document.querySelector('input[name="login_type"]:checked');
                    return checked ? checked.value : 'phone';
                }
                function applyIdentifierMask() {
                    var t = currentType();
                    if (t === 'snils') {
                        identifier.placeholder = 'XXX-XXX-XXX XX';
                        identifier.value = formatSnils(identifier.value);
                    } else {
                        identifier.placeholder = '+7 (999) 123-45-67';
                        identifier.value = formatPhoneRu(identifier.value);
                    }
                }
                identifier.addEventListener('input', applyIdentifierMask);
                radios.forEach(function(r) { r.addEventListener('change', applyIdentifierMask); });
                applyIdentifierMask();
            }
        });
    })();
    </script>

    @stack('scripts')
</body>
</html>
