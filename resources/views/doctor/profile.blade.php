@extends('layouts.clinic')

@section('content')
<section class="clinic-page clinic-profile doctor-profile-page">
    <div class="clinic-container">
        <div class="clinic-admin-topbar">
            <a href="{{ route('doctor.appointments.index') }}" class="clinic-admin-back">
                <svg class="clinic-admin-back__icon" width="16" height="16" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                    <path d="M12.5 4.5L7 10l5.5 5.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="clinic-admin-back__text">Назад</span>
            </a>
        </div>

        <h1>Личный кабинет врача</h1>

        @php
            $avatarPath = $doctor->avatar_path ?? null;
            $avg = $doctor->reviews()->avg('rating');
            $avgRounded = (int) round($avg ?: 0);
            $reviewsCount = $doctor->reviews()->count();
        @endphp

        <div class="clinic-blue-card doctor-profile-avatar-card">
            <div class="doctor-profile-avatar-card__left">
                <div class="clinic-avatar clinic-avatar--lg doctor-profile-avatar" data-avatar-preview>
                    <img
                        id="doctor-avatar-preview-img"
                        class="clinic-img clinic-img--cover"
                        src="{{ $avatarPath ? asset($avatarPath) : asset('images/startphotodoctor.png') }}"
                        alt="Аватар врача"
                        loading="lazy"
                    >
                </div>
                <div>
                    <div class="doctor-profile-avatar-card__title">Фото профиля</div>
                    <div class="doctor-profile-avatar-card__hint">Загрузите новое изображение или удалите текущее.</div>
                </div>
            </div>

            <div class="doctor-profile-avatar-card__actions">
                <form action="{{ route('doctor.profile.update') }}" method="POST" enctype="multipart/form-data" class="doctor-avatar-upload-form">
                    @csrf
                    <input type="hidden" name="last_name" value="{{ $doctor->last_name }}">
                    <input type="hidden" name="first_name" value="{{ $doctor->first_name }}">
                    <input type="hidden" name="middle_name" value="{{ $doctor->middle_name }}">
                    <input type="hidden" name="email" value="{{ $user->email }}">
                    <input type="hidden" name="about" value="{{ $doctor->about }}">
                    <input type="file" name="avatar" accept="image/*" id="doctor-avatar-file" class="doctor-avatar-upload-form__input" data-avatar-input>
                    <label for="doctor-avatar-file" class="clinic-btn clinic-btn--primary">Выберите файл</label>
                    <button type="submit" class="clinic-btn clinic-btn--primary">Сохранить</button>
                </form>

                <form action="{{ route('doctor.profile.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="last_name" value="{{ $doctor->last_name }}">
                    <input type="hidden" name="first_name" value="{{ $doctor->first_name }}">
                    <input type="hidden" name="middle_name" value="{{ $doctor->middle_name }}">
                    <input type="hidden" name="email" value="{{ $user->email }}">
                    <input type="hidden" name="about" value="{{ $doctor->about }}">
                    <input type="hidden" name="avatar_remove" value="1">
                    <button type="submit" class="clinic-btn clinic-btn--ghost">Удалить фото</button>
                </form>
            </div>
        </div>

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

        <div class="profile-block profile-block-expanded">
            <button type="button" class="profile-block-toggle" data-block="doctor-main" aria-expanded="true">
                <h2>Данные профиля</h2>
                <span class="profile-block-arrow">▼</span>
            </button>
            <div class="profile-block-content" id="block-doctor-main">
                <form action="{{ route('doctor.profile.update') }}" method="POST" class="clinic-form clinic-form--wide">
                    @csrf

                    <div class="doctor-profile-grid">
                        <div class="profile-field">
                            <span class="profile-field-label">Фамилия *</span>
                            <input type="text" name="last_name" value="{{ old('last_name', $doctor->last_name) }}" required>
                        </div>

                        <div class="profile-field">
                            <span class="profile-field-label">Имя *</span>
                            <input type="text" name="first_name" value="{{ old('first_name', $doctor->first_name) }}" required>
                        </div>

                        <div class="profile-field">
                            <span class="profile-field-label">Отчество</span>
                            <input type="text" name="middle_name" value="{{ old('middle_name', $doctor->middle_name) }}">
                        </div>

                        <div class="profile-field">
                            <span class="profile-field-label">Почта</span>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" placeholder="example@mail.ru">
                        </div>
                    </div>

                    <div class="profile-field">
                        <span class="profile-field-label">О себе</span>
                        <textarea name="about" rows="5">{{ old('about', $doctor->about) }}</textarea>
                    </div>

                    <button type="submit" class="clinic-btn clinic-btn--primary">Сохранить изменения</button>
                </form>
            </div>
        </div>

        <div class="profile-block">
            <button type="button" class="profile-block-toggle" data-block="doctor-meta" aria-expanded="false">
                <h2>Профессиональная информация</h2>
                <span class="profile-block-arrow">▶</span>
            </button>
            <div class="profile-block-content profile-block-collapsed" id="block-doctor-meta">
                <div class="doctor-meta-grid">
                    <div class="doctor-meta-card">
                        <div class="doctor-meta-card__label">Специализация</div>
                        <div class="doctor-meta-card__value">{{ $doctor->specialization->name }}</div>
                    </div>
                    <div class="doctor-meta-card">
                        <div class="doctor-meta-card__label">Стаж</div>
                        <div class="doctor-meta-card__value">{{ $doctor->experience_years }} лет</div>
                    </div>
                    <div class="doctor-meta-card">
                        <div class="doctor-meta-card__label">Категория</div>
                        <div class="doctor-meta-card__value">{{ $doctor->category ?? 'Не указана' }}</div>
                    </div>
                    <div class="doctor-meta-card">
                        <div class="doctor-meta-card__label">Стоимость приёма</div>
                        <div class="doctor-meta-card__value">{{ $doctor->base_price }} ₽</div>
                    </div>
                </div>

                @if($reviewsCount > 0)
                    <div class="doctor-profile-rating">
                        <div class="doctor-rating-stars" aria-label="Средняя оценка: {{ $avgRounded }} из 5">
                            @for($i=1;$i<=5;$i++)
                                <span class="doctor-rating-stars__star {{ $i <= $avgRounded ? 'is-active' : '' }}">★</span>
                            @endfor
                        </div>
                        <p class="clinic-text-muted">Средняя оценка: {{ $avgRounded }}/5 ({{ $reviewsCount }} отзывов)</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="profile-block">
            <button type="button" class="profile-block-toggle" data-block="doctor-education" aria-expanded="false">
                <h2>Образование</h2>
                <span class="profile-block-arrow">▶</span>
            </button>
            <div class="profile-block-content profile-block-collapsed" id="block-doctor-education">
                @if($doctor->educations && $doctor->educations->isNotEmpty())
                    <ul class="profile-list">
                        @foreach($doctor->educations as $ed)
                            <li>
                                <strong>{{ \App\Models\DoctorEducation::types()[$ed->type] ?? $ed->type }}</strong>: {{ $ed->institution }}
                                @if($ed->year) ({{ $ed->year }}) @endif
                                @if($ed->specialty) — {{ $ed->specialty }} @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="profile-empty">Образование добавляется администратором.</p>
                @endif
            </div>
        </div>

        <p class="doctor-profile-links"><a href="{{ route('doctor.profile.password.show') }}">Сменить пароль</a></p>

        <form action="{{ route('logout') }}" method="POST" class="clinic-logout-form doctor-profile-logout">
            @csrf
            <button type="submit" class="clinic-btn clinic-btn-secondary clinic-btn-sm clinic-logout-btn">Выйти</button>
        </form>
    </div>
</section>

@push('scripts')
<script>
(function() {
    document.querySelectorAll('.profile-block-toggle').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var blockId = this.getAttribute('data-block');
            var content = document.getElementById('block-' + blockId);
            var arrow = this.querySelector('.profile-block-arrow');
            var expanded = content.classList.toggle('profile-block-collapsed');
            arrow.textContent = expanded ? '▶' : '▼';
            this.setAttribute('aria-expanded', !expanded);
        });
    });

    var avatarInput = document.querySelector('[data-avatar-input]');
    var img = document.getElementById('doctor-avatar-preview-img');
    var lastObjectUrl = null;

    if (avatarInput && img) {
        avatarInput.addEventListener('change', function() {
            var file = this.files && this.files[0] ? this.files[0] : null;
            if (lastObjectUrl) URL.revokeObjectURL(lastObjectUrl);
            if (file) {
                lastObjectUrl = URL.createObjectURL(file);
                img.src = lastObjectUrl;
            }
        });
    }
})();
</script>
@endpush
@endsection