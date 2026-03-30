@extends('layouts.clinic')

@section('content')
<section class="clinic-hero">
    <div class="clinic-container">
        <div class="clinic-hero-inner">
            <div class="clinic-hero-content">
                <h1>Найдите своего врача</h1>
                <p class="clinic-hero-subtitle">Онлайн-запись на приём в удобное время. Опытные специалисты, внимательный подход.</p>
                <a href="{{ auth()->check() && auth()->user()->isPatient() ? route('client.book.specialization') : route('guest.book.patient') }}" class="clinic-hero-cta clinic-btn">Записаться к врачу</a>
                <form action="{{ route('doctors.index') }}" method="GET" class="clinic-search">
                    <div class="clinic-search-form">
                        <select name="specialization_id">
                            <option value="">Все специализации</option>
                            @foreach($specializations as $spec)
                                <option value="{{ $spec->id }}">{{ $spec->name }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="q" placeholder="Поиск по ФИО врача">
                        <button type="submit" class="clinic-search-btn">Найти врача</button>
                    </div>
                </form>
            </div>
            <div class="clinic-hero-image">
                <picture class="clinic-picture clinic-hero-picture">
                    @if (file_exists(public_path('images/hero.avif')))
                        <source type="image/avif" srcset="{{ asset('images/hero.avif') }}">
                    @endif
                    @if (file_exists(public_path('images/hero.webp')))
                        <source type="image/webp" srcset="{{ asset('images/hero.webp') }}">
                    @endif
                    @php
                        $heroFallback = file_exists(public_path('images/hero.jpeg'))
                            ? asset('images/hero.jpeg')
                            : (file_exists(public_path('images/hero.jpg'))
                                ? asset('images/hero.jpg')
                                : 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=600&h=600&fit=crop');
                    @endphp
                    <img class="clinic-img clinic-hero-img" src="{{ $heroFallback }}" alt="Врач в клинике" width="400" height="400" loading="eager">
                </picture>
            </div>
        </div>
    </div>
</section>

<section class="clinic-features">
    <div class="clinic-container">
        <div class="clinic-features-grid">
            <div class="clinic-feature-card">
                <div class="clinic-feature-icon">👤</div>
                <h3>Войдите в аккаунт</h3>
                <p>Зарегистрируйтесь или войдите, чтобы видеть свои записи и историю приёмов</p>
            </div>
            <div class="clinic-feature-card">
                <div class="clinic-feature-icon">🔍</div>
                <h3>Выберите врача</h3>
                <p>Найдите специалиста по направлению или по имени в нашем каталоге</p>
            </div>
            <div class="clinic-feature-card">
                <div class="clinic-feature-icon">📅</div>
                <h3>Запишитесь на приём</h3>
                <p>Выберите удобные дату и время — запись займёт пару минут</p>
            </div>
        </div>
    </div>
</section>

<section class="clinic-doctors">
    <div class="clinic-container">
        <div class="clinic-doctors-header">
            <h2>{{ $doctorsCount }}+ врачей — найдите специалиста в своей области</h2>
            <p>В нашей клинике работают опытные врачи различных специальностей. Выберите врача и запишитесь на приём.</p>
        </div>

        <form action="{{ route('doctors.index') }}" method="GET" class="clinic-doctors-toolbar">
            <select name="specialization_id">
                <option value="">Сортировать по специализации</option>
                @foreach($specializations as $spec)
                    <option value="{{ $spec->id }}">{{ $spec->name }}</option>
                @endforeach
            </select>
            <input type="text" name="q" placeholder="Поиск по ФИО">
            <button type="submit" class="clinic-btn">Поиск</button>
        </form>

        <div class="clinic-doctors-grid">
            @foreach($featuredDoctors as $doctor)
                <article class="clinic-doctor-card">
                    <div class="clinic-doctor-avatar clinic-avatar clinic-avatar--md">
                        <img
                            class="clinic-img clinic-img--cover"
                            src="{{ $doctor->avatar_path ? asset($doctor->avatar_path) : asset('images/startphotodoctor.png') }}"
                            alt="Фото врача {{ $doctor->last_name }} {{ $doctor->first_name }}"
                            loading="lazy"
                        >
                    </div>
                    <h3>{{ $doctor->last_name }} {{ $doctor->first_name }} {{ $doctor->middle_name }}</h3>
                    <p class="clinic-doctor-specialty">{{ $doctor->specialization->name ?? '' }}</p>
                    <p class="clinic-doctor-meta">Стаж: {{ $doctor->experience_years }} лет · {{ $doctor->base_price }} ₽</p>
                    @if($doctor->reviews_count > 0)
                        @php $rc = $doctor->reviews_count; $w = $rc % 10 == 1 && $rc % 100 != 11 ? 'отзыв' : ($rc % 10 >= 2 && $rc % 10 <= 4 && ($rc % 100 < 10 || $rc % 100 >= 20) ? 'отзыва' : 'отзывов'); @endphp
                        <p class="clinic-doctor-rating">★ {{ number_format($doctor->reviews_avg_rating ?? 0, 1) }} ({{ $rc }} {{ $w }})</p>
                    @endif
                    <a href="{{ route('doctors.show', $doctor->id) }}" class="clinic-btn">Записаться</a>
                </article>
            @endforeach
        </div>

        <p style="text-align:center; margin-top:1.5rem;">
            <a href="{{ route('doctors.index') }}" class="clinic-btn" style="display:inline-block;">Все врачи</a>
        </p>
    </div>
</section>

<section class="clinic-why">
    <div class="clinic-container">
        <div class="clinic-why-inner">
            <div class="clinic-why-content">
                <h2>Почему выбирают нас</h2>
                <p>Удобная онлайн-запись, опытные специалисты и внимательный подход к каждому пациенту.</p>
                <ul class="clinic-why-list">
                    <li>Удобная запись онлайн</li>
                    <li>Опытные врачи</li>
                    <li>Поддержка 24/7</li>
                    <li>Прозрачные цены</li>
                </ul>
            </div>
            <div class="clinic-why-stats">
                <div class="clinic-stat-box">
                    <span class="number">{{ $doctorsCount }}+</span>
                    <span class="label">Врачей</span>
                </div>
                <div class="clinic-stat-box">
                    <span class="number">{{ $specializations->count() }}+</span>
                    <span class="label">Специальностей</span>
                </div>
            </div>
        </div>
    </div>
</section>

@if($featuredReviews->isNotEmpty())
<section class="clinic-reviews">
    <div class="clinic-container">
        <h2>Отзывы о врачах</h2>
        <p class="clinic-reviews-intro">Что говорят наши пациенты</p>
        <div class="clinic-reviews-grid">
            @foreach($featuredReviews as $review)
                <article class="clinic-review-card">
                    <div class="clinic-review-rating">★ {{ $review->rating }}/5</div>
                    <p class="clinic-review-text">{{ Str::limit($review->text, 150) ?: 'Пациент оставил положительный отзыв.' }}</p>
                    <p class="clinic-review-doctor">
                        <a href="{{ route('doctors.show', $review->doctor->id) }}">
                            Доктор {{ $review->doctor->last_name }} {{ $review->doctor->first_name }}
                        </a>
                        ·
                        <a href="{{ route('doctors.show', $review->doctor->id) }}">
                            {{ $review->doctor->specialization->name ?? '' }}
                        </a>
                    </p>
                    <p class="clinic-review-author">{{ $review->patient->last_name }} {{ mb_substr($review->patient->first_name, 0, 1) }}.</p>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="clinic-map-section" id="contacts">
    <div class="clinic-container">
        <h2>Где нас найти</h2>
        <p class="clinic-map-address">📍 {{ config('clinic.address') }}</p>
        <p class="clinic-map-phone">📞 {{ config('clinic.phone') }}</p>
        @php
            $yandexKey = (string) config('clinic.yandex_maps_api_key');
            $lat = (string) config('clinic.map_lat');
            $lng = (string) config('clinic.map_lng');
            $zoom = (int) config('clinic.map_zoom');
        @endphp
        @if($yandexKey !== '')
            <div id="clinic-map" class="clinic-map-container"></div>
        @else
            <iframe
                class="clinic-map-container"
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

@push('head')
@php
    $clinicSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'MedicalClinic',
        'name' => config('app.name', 'Дом здоровья'),
        'url' => url('/'),
        'telephone' => config('clinic.phone'),
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => config('clinic.address'),
        ],
        'geo' => [
            '@type' => 'GeoCoordinates',
            'latitude' => (string) config('clinic.map_lat'),
            'longitude' => (string) config('clinic.map_lng'),
        ],
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($clinicSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@push('scripts')
@if($yandexKey !== '')
    <script src="https://api-maps.yandex.ru/2.1/?apikey={{ urlencode($yandexKey) }}&lang=ru_RU" type="text/javascript"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof ymaps !== 'undefined') {
            ymaps.ready(function() {
                var map = new ymaps.Map('clinic-map', {
                    center: [{{ config('clinic.map_lat') }}, {{ config('clinic.map_lng') }}],
                    zoom: {{ config('clinic.map_zoom') }},
                    controls: ['zoomControl']
                });
                map.geoObjects.add(new ymaps.Placemark([{{ config('clinic.map_lat') }}, {{ config('clinic.map_lng') }}], {
                    balloonContent: '{{ addslashes(config('clinic.address')) }}'
                }));
            });
        }
    });
    </script>
@endif
@endpush
