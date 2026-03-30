@extends('layouts.clinic')

@section('content')
<section class="clinic-page">
    <div class="clinic-container">
        <h1>Профиль врача</h1>

        @if(session('success'))
            <div class="clinic-alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="clinic-alert-error">
                <ul style="margin:0;padding-left:1.25rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <p>
            <a href="{{ route('doctor.appointments.index') }}">Мои записи</a> |
            <a href="{{ route('doctor.profile.password.show') }}">Сменить пароль</a>
        </p>

        <hr>

        @php
            $avg = $doctor->reviews()->avg('rating');
            $avgRounded = (int) round($avg ?: 0);
            $reviewsCount = $doctor->reviews()->count();
        @endphp

        @if($reviewsCount > 0)
            <div class="doctor-rating-stars" aria-label="Средняя оценка: {{ $avgRounded }} из 5">
                @for($i=1;$i<=5;$i++)
                    <span class="doctor-rating-stars__star {{ $i <= $avgRounded ? 'is-active' : '' }}">★</span>
                @endfor
            </div>
            <p class="clinic-text-muted" style="margin:0.35rem 0 1rem; font-size:0.9rem;">
                Средняя оценка: {{ $avgRounded }}/5 ({{ $reviewsCount }} отзывов)
            </p>
        @endif

        <h2>Аватар</h2>
        <div class="doctor-profile-avatar clinic-avatar clinic-avatar--lg">
            <img
                class="clinic-img clinic-img--cover"
                src="{{ $doctor->avatar_path ? asset($doctor->avatar_path) : asset('images/startphotodoctor.png') }}"
                alt="Аватар врача"
                loading="lazy"
            >
        </div>

        <h2>Основная информация</h2>
        <p><strong>Специализация:</strong> {{ $doctor->specialization->name }}</p>
        <p><strong>Стаж:</strong> {{ $doctor->experience_years }} лет</p>
        <p><strong>Категория:</strong> {{ $doctor->category ?? 'не указана' }}</p>
        <p><strong>Стоимость приёма:</strong> {{ $doctor->base_price }} ₽</p>

        <hr>

        <h2>Редактируемые данные</h2>

        <form action="{{ route('doctor.profile.update') }}" method="POST" enctype="multipart/form-data" class="clinic-form clinic-form--wide">
            @csrf

            <div class="form-group">
                <label>Фамилия *</label>
                <input type="text" name="last_name" value="{{ old('last_name', $doctor->last_name) }}" required>
            </div>

            <div class="form-group">
                <label>Имя *</label>
                <input type="text" name="first_name" value="{{ old('first_name', $doctor->first_name) }}" required>
            </div>

            <div class="form-group">
                <label>Отчество</label>
                <input type="text" name="middle_name" value="{{ old('middle_name', $doctor->middle_name) }}">
            </div>

            <div class="form-group">
                <label>Загрузить новое фото (аватар)</label>
                <input type="file" name="avatar" accept="image/jpeg,image/png,image/jpg,image/gif">
                <label class="form-checkbox" style="margin-top:0.5rem;">
                    <input type="checkbox" name="avatar_remove" value="1">
                    <span>Удалить текущую фотографию и использовать стандартную</span>
                </label>
            </div>

            <div class="form-group">
                <label>О себе</label>
                <textarea name="about" rows="6">{{ old('about', $doctor->about) }}</textarea>
            </div>

            <button type="submit" class="clinic-btn btn-primary">Сохранить изменения</button>
        </form>

        <hr>
        <h2>Образование</h2>
        @if($doctor->educations && $doctor->educations->isNotEmpty())
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
        @else
            <p>Образование добавляется администратором.</p>
        @endif

        <form action="{{ route('logout') }}" method="POST" style="margin-top:20px;" class="clinic-logout-form">
            @csrf
            <button type="submit" class="clinic-btn clinic-btn-secondary clinic-btn-sm clinic-logout-btn">Выйти</button>
        </form>
    </div>
</section>
@endsection