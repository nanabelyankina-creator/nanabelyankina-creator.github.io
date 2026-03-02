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
                                @if ($doctor->avatar_path)
                                    <img
                                        src="{{ asset($doctor->avatar_path) }}"
                                        alt="Фото врача {{ $doctor->last_name }} {{ $doctor->first_name }}"
                                    >
                                @else
                                    <div class="avatar-placeholder">
                                        {{ mb_substr($doctor->first_name, 0, 1) }}{{ mb_substr($doctor->last_name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <h3>{{ $doctor->last_name }} {{ $doctor->first_name }} {{ $doctor->middle_name }}</h3>
                            <p class="clinic-doctor-specialty">{{ $doctor->specialization->name ?? '' }}</p>

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
