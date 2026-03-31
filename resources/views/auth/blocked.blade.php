@extends('layouts.clinic')

@section('content')
<section class="clinic-page">
    <div class="clinic-container">
        <h1>Аккаунт заблокирован</h1>

        <p>
            Вы заблокированы. Пожалуйста, свяжитесь с нами по телефону
            <strong>{{ config('clinic.phone') }}</strong>
            или лично обратитесь в клинику по адресу: {{ config('clinic.address') }}
        </p>

        @php
            $yandexKey = (string) config('clinic.yandex_maps_api_key');
            $lat = (string) config('clinic.map_lat');
            $lng = (string) config('clinic.map_lng');
            $zoom = (int) config('clinic.map_zoom');
        @endphp
        @if($yandexKey !== '')
            <div id="blocked-map" class="clinic-map-container" style="width:100%;max-width:600px;height:400px;margin-top:1.5rem;border-radius:var(--clinic-radius);overflow:hidden;"></div>
        @else
            <iframe
                class="clinic-map-container"
                style="width:100%;max-width:600px;height:400px;margin-top:1.5rem;border-radius:var(--clinic-radius);overflow:hidden;"
                src="https://yandex.ru/map-widget/v1/?ll={{ rawurlencode($lng . ',' . $lat) }}&z={{ $zoom }}&pt={{ rawurlencode($lng . ',' . $lat . ',pm2rdm') }}&lang=ru_RU"
                width="100%"
                height="400"
                frameborder="0"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                aria-label="Карта клиники"
            ></iframe>
        @endif

    </div>
</section>
@endsection

@push('scripts')
@if($yandexKey !== '')
    <script src="https://api-maps.yandex.ru/2.1/?apikey={{ urlencode($yandexKey) }}&lang=ru_RU" type="text/javascript"></script>
    <script>
    ymaps.ready(function() {
        new ymaps.Map('blocked-map', {
            center: [{{ config('clinic.map_lat') }}, {{ config('clinic.map_lng') }}],
            zoom: {{ config('clinic.map_zoom') }},
            controls: ['zoomControl']
        }).geoObjects.add(new ymaps.Placemark([{{ config('clinic.map_lat') }}, {{ config('clinic.map_lng') }}], {
            balloonContent: '{{ addslashes(config('clinic.address')) }}'
        }));
    });
    </script>
@endif
@endpush
