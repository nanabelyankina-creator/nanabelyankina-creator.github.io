@extends('layouts.clinic')

@section('content')
<section class="clinic-doctors clinic-page">
    <div class="clinic-container">
        <div class="clinic-doctors-header">
            <h1>Наши врачи</h1>
        </div>

        <form method="GET" action="{{ route('doctors.index') }}" class="clinic-doctors-toolbar">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Поиск по ФИО">
            <select name="specialization_id">
                <option value="">Все специализации</option>
                @foreach($specializations as $spec)
                    <option value="{{ $spec->id }}" {{ (string)$spec->id === request('specialization_id') ? 'selected' : '' }}>
                        {{ $spec->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="clinic-btn">Показать</button>
        </form>

        @if($doctors->isEmpty())
            <p>Врачи не найдены.</p>
        @else
            <div class="clinic-doctors-grid">
                @foreach($doctors as $doctor)
                    <article class="clinic-doctor-card">
                        <a href="{{ route('doctors.show', $doctor->id) }}" class="clinic-doctor-link">
                            <div class="clinic-doctor-avatar clinic-avatar clinic-avatar--md">
                                <img
                                    class="clinic-img clinic-img--cover"
                                    src="{{ asset('images/startphotodoctor.png') }}"
                                    alt="Фото врача {{ $doctor->last_name }} {{ $doctor->first_name }}"
                                >
                            </div>
                            <h3>{{ $doctor->last_name }} {{ $doctor->first_name }} {{ $doctor->middle_name }}</h3>
                            <p class="clinic-doctor-specialty">{{ $doctor->specialization->name ?? '' }}</p>

                            @php
                                $avgRounded = (int) round($doctor->reviews_avg_rating ?? 0);
                                $reviewsCount = $doctor->reviews_count ?? 0;
                            @endphp
                            @if($reviewsCount > 0)
                                <div class="doctor-rating-stars doctor-rating-stars--sm" aria-label="Средняя оценка: {{ $avgRounded }} из 5">
                                    @for($i=1;$i<=5;$i++)
                                        <span class="doctor-rating-stars__star {{ $i <= $avgRounded ? 'is-active' : '' }}">★</span>
                                    @endfor
                                </div>
                                <p class="clinic-text-muted" style="margin:0.35rem 0 0; font-size:0.85rem;">
                                    {{ $avgRounded }}/5 ({{ $reviewsCount }} {{ $reviewsCount % 10 == 1 && $reviewsCount % 100 != 11 ? 'отзыв' : ($reviewsCount % 10 >= 2 && $reviewsCount % 10 <= 4 && ($reviewsCount % 100 < 10 || $reviewsCount % 100 >= 20) ? 'отзыва' : 'отзывов') }})
                                </p>
                            @endif

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

                            @if($discountPercent)
                                <p class="clinic-doctor-meta">
                                    Стаж: {{ $doctor->experience_years }} лет ·
                                    <span class="doctor-price-old">{{ $basePrice }} ₽</span>
                                    <span class="doctor-price-new">{{ $finalPrice }} ₽</span>
                                    <span class="doctor-price-badge">-{{ $discountPercent }}%</span>
                                </p>
                            @else
                                <p class="clinic-doctor-meta">
                                    Стаж: {{ $doctor->experience_years }} лет ·
                                    <span class="doctor-price-new">{{ $basePrice }} ₽</span>
                                </p>
                            @endif
                        </a>
                    </article>
                @endforeach
            </div>

            <div style="margin-top:2rem;">{{ $doctors->links('clinic-pagination') }}</div>
        @endif
    </div>
</section>
@endsection
