@extends('layouts.clinic')

@section('content')
<section class="clinic-page" style="padding:2rem 0 3rem;">
    <div class="clinic-container">
        <p><a href="{{ route('doctors.index') }}" style="color:var(--clinic-primary);">← Все врачи</a></p>

        <div style="display:flex;flex-direction:column;gap:1.5rem;margin-top:1rem;">
            <div class="doctor-profile-header">
                <div class="clinic-avatar clinic-avatar--xl">
                    @if($doctor->avatar_path)
                        <img class="clinic-img clinic-img--cover" src="{{ asset($doctor->avatar_path) }}" alt="{{ $doctor->last_name }} {{ $doctor->first_name }}" loading="lazy">
                    @else
                        <picture class="clinic-picture">
                            <source type="image/avif" srcset="{{ asset('images/doctor-placeholder.avif') }}">
                            <source type="image/webp" srcset="{{ asset('images/doctor-placeholder.webp') }}">
                            <img class="clinic-img clinic-img--cover" src="{{ file_exists(public_path('images/doctor-placeholder.jpeg')) ? asset('images/doctor-placeholder.jpeg') : 'https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?w=200&h=200&fit=crop' }}" alt="Фото врача" loading="lazy">
                        </picture>
                    @endif
                </div>
                <div>
                    <h1>{{ $doctor->last_name }} {{ $doctor->first_name }} {{ $doctor->middle_name }}</h1>
                    <p><strong>Специализация:</strong> {{ $doctor->specialization->name }}</p>
                    <p><strong>Стаж:</strong> {{ $doctor->experience_years }} лет</p>
                    <p><strong>Категория:</strong> {{ $doctor->category ?? 'не указана' }}</p>
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
                        <p>
                            <strong>Стоимость приёма:</strong>
                            <span class="doctor-price-old">{{ $basePrice }} ₽</span>
                            <span class="doctor-price-new">{{ $finalPrice }} ₽</span>
                            <span class="doctor-price-badge">-{{ $discountPercent }}%</span>
                        </p>
                    @else
                        <p>
                            <strong>Стоимость приёма:</strong>
                            <span class="doctor-price-new">{{ $basePrice }} ₽</span>
                        </p>
                    @endif
                </div>
            </div>

            <div>
                <h2>О враче</h2>
                <p>{{ $doctor->about ?: 'Информация о враче будет добавлена позже.' }}</p>
            </div>

            @if(isset($reviews) && $reviews->isNotEmpty())
                <div>
                    <h2>Отзывы пациентов</h2>
                    @if($doctor->reviews_count > 0)
                        <p class="clinic-doctor-rating">★ {{ number_format($doctor->reviews_avg_rating ?? 0, 1) }} ({{ $doctor->reviews_count }} {{ $doctor->reviews_count % 10 == 1 && $doctor->reviews_count % 100 != 11 ? 'отзыв' : ($doctor->reviews_count % 10 >= 2 && $doctor->reviews_count % 10 <= 4 && ($doctor->reviews_count % 100 < 10 || $doctor->reviews_count % 100 >= 20) ? 'отзыва' : 'отзывов') }})</p>
                    @endif
                    <ul class="clinic-reviews-list" style="list-style:none;padding:0;">
                        @foreach($reviews as $r)
                            <li style="padding:1rem 0;border-bottom:1px solid var(--clinic-gray-border);">
                                <strong>★ {{ $r->rating }}/5</strong>
                                <p style="margin:0.5rem 0;">{{ $r->text ?: 'Положительный отзыв.' }}</p>
                                <small style="color:var(--clinic-gray);">{{ $r->patient->last_name }} {{ mb_substr($r->patient->first_name ?? '', 0, 1) }}.</small>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($doctor->educations && $doctor->educations->isNotEmpty())
                <div>
                    <h2>Образование</h2>
                    <ul style="list-style:none;padding:0;">
                        @foreach($doctor->educations as $ed)
                            <li style="margin-bottom:0.5rem;">
                                {{ \App\Models\DoctorEducation::types()[$ed->type] ?? $ed->type }}:
                                {{ $ed->institution }}
                                @if($ed->year) ({{ $ed->year }}) @endif
                                @if($ed->specialty) — {{ $ed->specialty }} @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <p>
                @auth
                    <a href="{{ route('client.book.datetime', ['specialization_id' => $doctor->specialization_id, 'doctor_id' => $doctor->id]) }}" class="clinic-btn" style="display:inline-block;">Записаться к врачу</a>
                @else
                    <a href="{{ route('guest.book.patient') }}" class="clinic-btn" style="display:inline-block;">Записаться к врачу без регистрации</a>
                @endauth
            </p>
        </div>
    </div>
</section>
@endsection
