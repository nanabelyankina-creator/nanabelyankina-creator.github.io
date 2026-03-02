@extends('layouts.clinic')

@section('content')
<section class="clinic-page">
    <div class="clinic-container">
        <h1>{{ $page->title }}</h1>
        <p><a href="{{ route('home') }}" style="color:var(--clinic-primary);">← На главную</a></p>
        <div style="margin-top:1.5rem;">
            {!! nl2br(e($page->content ?: 'Содержимое страницы пока не добавлено.')) !!}
        </div>
        @if($page->slug === 'contacts' || $page->slug === 'gde-nas-naiti')
            <p style="margin-top:1.5rem;"><strong>📍 {{ config('clinic.address') }}</strong></p>
            <p><strong>📞 {{ config('clinic.phone') }}</strong></p>
            @php
                $yandexKey = (string) config('clinic.yandex_maps_api_key');
                $lat = (string) config('clinic.map_lat');
                $lng = (string) config('clinic.map_lng');
                $zoom = (int) config('clinic.map_zoom');
            @endphp
            @if($yandexKey !== '')
                <div id="page-map" class="clinic-map-container" style="width:100%;height:400px;margin-top:1rem;border-radius:var(--clinic-radius);overflow:hidden;"></div>
            @else
                <iframe
                    class="clinic-map-container"
                    style="width:100%;height:400px;margin-top:1rem;border-radius:var(--clinic-radius);overflow:hidden;"
                    src="https://yandex.ru/map-widget/v1/?ll={{ rawurlencode($lng . ',' . $lat) }}&z={{ $zoom }}&pt={{ rawurlencode($lng . ',' . $lat . ',pm2rdm') }}&lang=ru_RU"
                    width="100%"
                    height="400"
                    frameborder="0"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    aria-label="Карта клиники"
                ></iframe>
            @endif
        @endif
    </div>
</section>
@if($page->slug === 'contacts' || $page->slug === 'gde-nas-naiti')
@push('scripts')
@if($yandexKey !== '')
    <script src="https://api-maps.yandex.ru/2.1/?apikey={{ urlencode($yandexKey) }}&lang=ru_RU" type="text/javascript"></script>
    <script>
    ymaps.ready(function() {
        new ymaps.Map('page-map', {
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
@endif
@endsection
