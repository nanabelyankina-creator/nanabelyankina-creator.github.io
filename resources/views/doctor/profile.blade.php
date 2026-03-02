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

        <h2>Аватар</h2>
        <div class="doctor-profile-avatar clinic-avatar clinic-avatar--lg">
            @if($doctor->avatar_path)
                <img class="clinic-img clinic-img--cover" src="{{ asset($doctor->avatar_path) }}" alt="Аватар врача" loading="lazy">
            @else
                <picture class="clinic-picture">
                    <source type="image/avif" srcset="{{ asset('images/doctor-placeholder.avif') }}">
                    <source type="image/webp" srcset="{{ asset('images/doctor-placeholder.webp') }}">
                    <img class="clinic-img clinic-img--cover" src="{{ file_exists(public_path('images/doctor-placeholder.jpeg')) ? asset('images/doctor-placeholder.jpeg') : 'https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?w=200&h=200&fit=crop' }}" alt="Аватар врача" loading="lazy">
                </picture>
            @endif
        </div>

        <h2>Основная информация</h2>
        <p><strong>Специализация:</strong> {{ $doctor->specialization->name }}</p>
        <p><strong>Стаж:</strong> {{ $doctor->experience_years }} лет</p>
        <p><strong>Категория:</strong> {{ $doctor->category ?? 'не указана' }}</p>
        <p><strong>Стоимость приёма:</strong> {{ $doctor->base_price }} ₽</p>

        <hr>

        <h2>Редактируемые данные</h2>

        <form action="{{ route('doctor.profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div>
        <label>Фамилия *</label><br>
        <input type="text" name="last_name" value="{{ old('last_name', $doctor->last_name) }}" required>
    </div>

    <div>
        <label>Имя *</label><br>
        <input type="text" name="first_name" value="{{ old('first_name', $doctor->first_name) }}" required>
    </div>

    <div>
        <label>Отчество</label><br>
        <input type="text" name="middle_name" value="{{ old('middle_name', $doctor->middle_name) }}">
    </div>

    <hr>

            <div class="form-group">
                <label>Загрузить новое фото (аватар)</label>
                <input type="file" name="avatar" accept="image/jpeg,image/png,image/jpg,image/gif">
            </div>

            <h3>О себе</h3>
            <textarea name="about" rows="6" cols="60">{{ old('about', $doctor->about) }}</textarea>

            <br><br>
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

        <form action="{{ route('logout') }}" method="POST" style="margin-top:20px;">
            @csrf
            <button type="submit" class="clinic-btn clinic-btn-secondary">Выйти</button>
        </form>
    </div>
</section>
@endsection