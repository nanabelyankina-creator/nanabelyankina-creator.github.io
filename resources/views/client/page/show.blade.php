@extends('layouts.clinic')

@section('content')
<section class="clinic-page">
    <div class="clinic-container">
        <h1>{{ $page->title }}</h1>

        @php
            $pageContent = $page->content ?: 'Содержимое страницы пока не добавлено.';
            if ($page->slug === 'contacts' || $page->slug === 'gde-nas-naiti') {
                // В контенте могут быть уже вставлены теги <br> как текст — убираем их и делаем перенос строк.
                $pageContent = str_ireplace(['<br>', '<br/>', '<br />'], "\n", $pageContent);
            }
        @endphp

        @if($page->slug === 'about')
            <div class="clinic-about-slider" id="clinic-about-slider">
                <div class="clinic-about-slider__viewport">
                    <div class="clinic-about-slider__track">
                        @for($i = 1; $i <= 3; $i++)
                            <div class="clinic-about-slider__slide">
                                <img
                                    class="clinic-about-slider__img"
                                    src="{{ asset('images/about'.$i.'.jpg') }}"
                                    alt="Фото оборудования {{ $i }}"
                                    loading="lazy"
                                >
                            </div>
                        @endfor
                    </div>
                </div>

                <div class="clinic-about-slider__controls">
                    <button type="button" class="clinic-about-slider__btn" id="clinic-about-slider-prev" aria-label="Назад">‹</button>
                    <div class="clinic-about-slider__dots" id="clinic-about-slider-dots">
                        @for($i = 0; $i < 3; $i++)
                            <button type="button" class="clinic-about-slider__dot {{ $i === 0 ? 'is-active' : '' }}" data-index="{{ $i }}" aria-label="Слайд {{ $i+1 }}"></button>
                        @endfor
                    </div>
                    <button type="button" class="clinic-about-slider__btn" id="clinic-about-slider-next" aria-label="Вперёд">›</button>
                </div>
            </div>

            <div style="margin-top:1.5rem;display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1rem;">
                <div class="clinic-blue-card">
                    <h2 style="margin:0 0 0.6rem;">Оборудование и подход</h2>
                    <p style="margin:0;color:var(--clinic-gray);">
                        Современная диагностика и стандартизированные протоколы помогают быстрее понимать причину симптомов и выбирать правильную тактику лечения.
                    </p>
                </div>

                <div class="clinic-blue-card">
                    <h2 style="margin:0 0 0.6rem;">Комфорт пациента</h2>
                    <p style="margin:0;color:var(--clinic-gray);">
                        Мы делаем упор на удобство: понятная запись, прозрачные рекомендации и бережный подход на каждом этапе.
                    </p>
                </div>

                <div class="clinic-blue-card">
                    <h2 style="margin:0 0 0.6rem;">Точность результатов</h2>
                    <p style="margin:0;color:var(--clinic-gray);">
                        Контроль качества исследований и корректная интерпретация анализов дают надежную основу для решений врача.
                    </p>
                </div>

                <div class="clinic-blue-card">
                    <h2 style="margin:0 0 0.6rem;">План лечения</h2>
                    <p style="margin:0;color:var(--clinic-gray);">
                        После приема вы получаете структурированный план: что делать сейчас, что проверить дальше и когда прийти повторно.
                    </p>
                </div>

                <div class="clinic-blue-card" style="grid-column:1 / -1;">
                    <h2 style="margin:0 0 0.6rem;">Безопасность и стандарты</h2>
                    <p style="margin:0;color:var(--clinic-gray);">
                        Мы придерживаемся клинических рекомендаций и аккуратно ведем историю обращений, чтобы лечение было последовательным и безопасным.
                    </p>
                </div>
            </div>
        @endif

        <div style="margin-top:1.5rem;">
            {!! nl2br(e($pageContent)) !!}
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

@if($page->slug === 'about')
@push('scripts')
<script>
(function() {
    const slider = document.getElementById('clinic-about-slider');
    if (!slider) return;

    const track = slider.querySelector('.clinic-about-slider__track');
    const dots = Array.from(slider.querySelectorAll('.clinic-about-slider__dot'));
    const prevBtn = document.getElementById('clinic-about-slider-prev');
    const nextBtn = document.getElementById('clinic-about-slider-next');
    const viewport = slider.querySelector('.clinic-about-slider__viewport');

    let index = 0;
    const slidesCount = dots.length || 3;
    let timer = null;

    function update() {
        if (!track) return;
        track.style.transform = 'translateX(-' + (index * 100) + '%)';
        dots.forEach(function(d) {
            d.classList.toggle('is-active', Number(d.dataset.index) === index);
        });
    }

    function resetTimer() {
        if (timer) clearInterval(timer);
        timer = setInterval(function() {
            index = (index + 1) % slidesCount;
            update();
        }, 7000);
    }

    dots.forEach(function(dot) {
        dot.addEventListener('click', function() {
            index = Number(this.dataset.index);
            update();
            resetTimer();
        });
    });

    prevBtn?.addEventListener('click', function() {
        index = (index - 1 + slidesCount) % slidesCount;
        update();
        resetTimer();
    });

    nextBtn?.addEventListener('click', function() {
        index = (index + 1) % slidesCount;
        update();
        resetTimer();
    });

    // Простейший swipe
    if (viewport) {
        let startX = null;
        viewport.addEventListener('touchstart', function(e) {
            startX = e.touches && e.touches[0] ? e.touches[0].clientX : null;
        });
        viewport.addEventListener('touchend', function(e) {
            if (startX === null) return;
            const endX = e.changedTouches && e.changedTouches[0] ? e.changedTouches[0].clientX : null;
            const delta = endX - startX;
            if (Math.abs(delta) < 50) return;
            if (delta < 0) {
                index = (index + 1) % slidesCount;
            } else {
                index = (index - 1 + slidesCount) % slidesCount;
            }
            update();
            resetTimer();
            startX = null;
        });
    }

    update();
    resetTimer();
})();
</script>
@endpush
@endif
@endsection
