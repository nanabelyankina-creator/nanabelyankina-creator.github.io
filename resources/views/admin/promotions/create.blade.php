@extends('layouts.admin')

@section('admin-content')
    <div class="clinic-admin-header">
        <div>
            <h1>Новая акция</h1>
            <p class="clinic-admin-subtitle">
                Создание промо‑акции для отображения на сайте.
            </p>
        </div>

        <div>
            <a href="{{ route('admin.promotions.index') }}" class="clinic-btn clinic-btn--ghost">
                ← Назад к списку
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="clinic-alert clinic-alert--error">
            <strong>Исправьте ошибки:</strong>
            <ul style="margin:0.4rem 0 0 1.1rem; padding:0;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.promotions.store') }}" method="POST" class="clinic-form clinic-form--wide">
        @csrf

        <div class="clinic-form-group">
            <label for="title">Заголовок акции *</label>
            <input type="text" id="title" name="title"
                   value="{{ old('title') }}" required>
        </div>

        <div class="clinic-form-group">
            <label for="short_description">Краткое описание</label>
            <textarea id="short_description" name="short_description" rows="2"
                      placeholder="Короткий текст для списка акций">{{ old('short_description') }}</textarea>
        </div>

        <div class="clinic-form-group">
            <label for="content">Полное описание</label>
            <textarea id="content" name="content" rows="6"
                      placeholder="Подробные условия акции, ограничения, как воспользоваться">{{ old('content') }}</textarea>
        </div>

        <div class="clinic-form-group clinic-form-group--inline">
            <div class="clinic-form-group">
                <label for="starts_at">Дата начала</label>
                <input type="date" id="starts_at" name="starts_at"
                       value="{{ old('starts_at') }}">
            </div>

            <div class="clinic-form-group">
                <label for="ends_at">Дата окончания</label>
                <input type="date" id="ends_at" name="ends_at"
                       value="{{ old('ends_at') }}">
            </div>
        </div>

        <div class="clinic-form-group clinic-form-group--inline">
            <label class="clinic-checkbox">
                <input type="checkbox" name="is_active" value="1"
                       {{ old('is_active', 1) ? 'checked' : '' }}>
                <span>Акция активна</span>
            </label>
        </div>

        <div class="clinic-form-actions">
            <button type="submit" class="clinic-btn">
                Создать акцию
            </button>
            <a href="{{ route('admin.promotions.index') }}" class="clinic-btn clinic-btn--ghost">
                Отмена
            </a>
        </div>
    </form>
@endsection