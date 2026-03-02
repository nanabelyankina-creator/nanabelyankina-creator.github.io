@extends('layouts.admin')

@section('admin-content')
    <div class="clinic-admin-header">
        <h1>Добавить врача</h1>
        <p class="clinic-admin-subtitle">Создание карточки врача и привязка к пользователю с ролью doctor.</p>
    </div>

    @if ($errors->any())
        <div class="clinic-alert clinic-alert--error">
            <ul>
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($availableUsers->isEmpty())
        <p class="clinic-empty">
            Нет доступных пользователей с ролью «doctor» без привязки. Сначала создайте такого пользователя (role=doctor) в таблице users.
        </p>
    @else
        <form action="{{ route('admin.doctors.store') }}" method="POST" class="clinic-form clinic-form--wide">
            @csrf

            <div class="clinic-form-group">
                <label>Пользователь (роль doctor) *</label>
                <select name="user_id" required>
                    @foreach($availableUsers as $user)
                        <option value="{{ $user->id }}">
                            ID {{ $user->id }} — {{ $user->name }} ({{ $user->email ?: $user->phone }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="clinic-form-group">
                <label>Специализация *</label>
                <select name="specialization_id" required>
                    @foreach($specializations as $spec)
                        <option value="{{ $spec->id }}">{{ $spec->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="clinic-form-group">
                <label>Фамилия *</label>
                <input type="text" name="last_name" value="{{ old('last_name') }}" required>
            </div>

            <div class="clinic-form-group">
                <label>Имя *</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}" required>
            </div>

            <div class="clinic-form-group">
                <label>Отчество</label>
                <input type="text" name="middle_name" value="{{ old('middle_name') }}">
            </div>

            <div class="clinic-form-group">
                <label>Стаж (лет) *</label>
                <input type="number" name="experience_years" value="{{ old('experience_years', 0) }}" min="0" max="80" required>
            </div>

            <div class="clinic-form-group">
                <label>Категория</label>
                <input type="text" name="category" value="{{ old('category') }}">
            </div>

            <div class="clinic-form-group">
                <label>Стоимость приёма (₽) *</label>
                <input type="number" name="base_price" value="{{ old('base_price', 0) }}" min="0" required>
            </div>

            <div class="clinic-form-group">
                <label>О себе</label>
                <textarea name="about" rows="5">{{ old('about') }}</textarea>
            </div>

            <div class="clinic-form-actions">
                <button type="submit" class="clinic-btn clinic-btn--primary">Создать</button>
                <a href="{{ route('admin.doctors.index') }}" class="clinic-btn clinic-btn--ghost">Отмена</a>
            </div>
        </form>
    @endif
@endsection
