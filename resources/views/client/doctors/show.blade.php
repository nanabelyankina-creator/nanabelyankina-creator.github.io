@extends('layouts.clinic')

@section('content')
<section class="clinic-page doctor-public-page">
    <div class="clinic-container">
        <div class="clinic-admin-topbar">
            <a href="{{ route('doctors.index') }}" class="clinic-admin-back">
                <svg class="clinic-admin-back__icon" width="16" height="16" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                    <path d="M12.5 4.5L7 10l5.5 5.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="clinic-admin-back__text">Все врачи</span>
            </a>
        </div>

        <div class="doctor-public-layout">
            <div class="doctor-profile-header">
                <div class="clinic-avatar clinic-avatar--xl">
                    <img
                        class="clinic-img clinic-img--cover"
                        src="{{ $doctor->avatar_path ? asset($doctor->avatar_path) : asset('images/startphotodoctor.png') }}"
                        alt="{{ $doctor->last_name }} {{ $doctor->first_name }}"
                        loading="lazy"
                    >
                </div>
                <div class="doctor-profile-main">
                    <h1>{{ $doctor->last_name }} {{ $doctor->first_name }} {{ $doctor->middle_name }}</h1>
                    @php
                        $basePrice = $doctor->base_price;
                        $finalPrice = $basePrice;
                        $discountPercent = null;

                        if (auth()->check() && auth()->user()->patient) {
                            $discountPercent = auth()->user()->patient->getActiveDiscountPercent();
                            if ($discountPercent) {
                                $finalPrice = (int) round($basePrice * (100 - $discountPercent) / 100);
                            }
                        }
                    @endphp

                    <div class="doctor-info-cards">
                        <div class="doctor-info-card">
                            <div class="doctor-info-card__label">Специализация</div>
                            <div class="doctor-info-card__value">{{ $doctor->specialization->name }}</div>
                        </div>

                        <div class="doctor-info-card">
                            <div class="doctor-info-card__label">Стаж</div>
                            <div class="doctor-info-card__value">{{ $doctor->experience_years }} лет</div>
                        </div>

                        <div class="doctor-info-card">
                            <div class="doctor-info-card__label">Категория</div>
                            <div class="doctor-info-card__value">{{ $doctor->category ?? 'не указана' }}</div>
                        </div>

                        <div class="doctor-info-card">
                            <div class="doctor-info-card__label">Стоимость приёма</div>
                            <div class="doctor-info-card__value doctor-price-value">
                                @if($discountPercent)
                                    <span class="doctor-price-old">{{ $basePrice }} ₽</span>
                                    <span class="doctor-price-new">{{ $finalPrice }} ₽</span>
                                    <span class="doctor-price-badge">-{{ $discountPercent }}%</span>
                                @else
                                    <span class="doctor-price-new">{{ $basePrice }} ₽</span>
                                @endif
                            </div>
                            <div class="doctor-info-card__hint">Стоимость указана для записи на приём</div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="doctor-profile-separator">

            @if($doctor->educations && $doctor->educations->isNotEmpty())
                <div class="doctor-profile-section">
                    <h2>Образование</h2>

                    @php
                        $educations = $doctor->educations ?? collect();
                        $educationsCount = $educations->count();
                    @endphp

                    <div class="doctor-education-slider" data-count="{{ $educationsCount }}">
                        @if($educationsCount > 3)
                            <button type="button" class="doctor-education-nav doctor-education-nav--prev" aria-label="Назад">‹</button>
                        @endif

                        <div class="doctor-education-viewport" id="doctor-education-viewport-{{ $doctor->id }}">
                            <div class="doctor-education-track">
                                @foreach($educations as $ed)
                                    <article class="doctor-education-item">
                                        <div class="doctor-education-field">
                                            <div class="doctor-education-field__label">Место образования</div>
                                            <div class="doctor-education-field__value">{{ $ed->institution }}</div>
                                        </div>

                                        <div class="doctor-education-field">
                                            <div class="doctor-education-field__label">Специальность</div>
                                            <div class="doctor-education-field__value">
                                                {{ \App\Models\DoctorEducation::types()[$ed->type] ?? $ed->type }}
                                                @if($ed->specialty) — {{ $ed->specialty }} @endif
                                            </div>
                                        </div>

                                        <div class="doctor-education-field">
                                            <div class="doctor-education-field__label">Годы обучения</div>
                                            <div class="doctor-education-field__value">
                                                @if($ed->year) {{ $ed->year }} @else — @endif
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </div>

                        @if($educationsCount > 3)
                            <button type="button" class="doctor-education-nav doctor-education-nav--next" aria-label="Вперёд">›</button>
                        @endif
                    </div>
                </div>
            @endif

            @if(isset($reviews) && $reviews->isNotEmpty())
                <div class="doctor-profile-section doctor-reviews-section">
                    <h2>Отзывы пациентов</h2>
                    @if($doctor->reviews_count > 0)
                        @php
                            $avgRounded = (int) round($doctor->reviews_avg_rating ?? 0);
                            $reviewsCount = $doctor->reviews_count ?? 0;
                        @endphp
                        <div class="doctor-rating-stars" aria-label="Средняя оценка: {{ $avgRounded }} из 5">
                            @for($i=1;$i<=5;$i++)
                                <span class="doctor-rating-stars__star {{ $i <= $avgRounded ? 'is-active' : '' }}">★</span>
                            @endfor
                        </div>
                        <p class="clinic-text-muted" style="margin:0.35rem 0 0; font-size:0.85rem;">
                            {{ $avgRounded }}/5
                            ({{ $reviewsCount }} {{ $reviewsCount % 10 == 1 && $reviewsCount % 100 != 11 ? 'отзыв' : ($reviewsCount % 10 >= 2 && $reviewsCount % 10 <= 4 && ($reviewsCount % 100 < 10 || $reviewsCount % 100 >= 20) ? 'отзыва' : 'отзывов') }})
                        </p>
                    @endif

                    <div class="doctor-reviews-list">
                        @foreach($reviews as $r)
                            <article class="doctor-review-card">
                                <div class="doctor-review-author">
                                    <div class="clinic-avatar clinic-avatar--md doctor-review-avatar">
                                        <img
                                            class="clinic-img clinic-img--cover"
                                            src="{{ asset('images/startphotouser.png') }}"
                                            alt="Аватар пациента"
                                            loading="lazy"
                                        >
                                    </div>
                                    <div class="doctor-review-author-name">
                                        @php
                                            $last = $r->patient?->last_name ?? '';
                                            $firstInit = $r->patient?->first_name ? mb_substr($r->patient->first_name, 0, 1) . '.' : '';
                                            $middleInit = $r->patient?->middle_name ? mb_substr($r->patient->middle_name, 0, 1) . '.' : '';
                                        @endphp
                                        {{ trim($last) }}
                                        @if($firstInit) {{ $firstInit }} @endif
                                        @if($middleInit) {{ $middleInit }} @endif
                                    </div>
                                </div>

                                <div class="doctor-review-body">
                                    <div class="doctor-review-rating">
                                        <span>★ {{ $r->rating }}/5</span>
                                    </div>
                                    <p class="doctor-review-text">{{ $r->text ?: 'Положительный отзыв.' }}</p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif

            <p>
                @auth
                    <a href="{{ route('client.book.datetime', ['specialization_id' => $doctor->specialization_id, 'doctor_id' => $doctor->id]) }}" class="clinic-btn clinic-btn--primary" style="display:inline-block;">Записаться к врачу</a>
                @else
                    <a href="{{ route('guest.book.patient') }}" class="clinic-btn clinic-btn--primary" style="display:inline-block;">Записаться к врачу без регистрации</a>
                @endauth
            </p>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
(function() {
    document.querySelectorAll('.doctor-education-slider').forEach(function(slider) {
        const viewport = slider.querySelector('.doctor-education-viewport');
        if (!viewport) return;
        const prev = slider.querySelector('.doctor-education-nav--prev');
        const next = slider.querySelector('.doctor-education-nav--next');
        if (!prev && !next) return;

        const by = viewport.clientWidth * 0.98;
        prev?.addEventListener('click', function() {
            viewport.scrollBy({ left: -by, behavior: 'smooth' });
        });
        next?.addEventListener('click', function() {
            viewport.scrollBy({ left: by, behavior: 'smooth' });
        });
    });
})();
</script>
@endpush
