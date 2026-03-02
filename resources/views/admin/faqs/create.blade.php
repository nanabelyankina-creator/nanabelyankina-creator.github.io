@extends('layouts.admin')

@section('admin-content')
    <div class="clinic-admin-header">
        <h1>Добавить вопрос FAQ</h1>
        <p class="clinic-admin-subtitle">Создайте новый вопрос и ответ для раздела помощи.</p>
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

    <form action="{{ route('admin.faqs.store') }}" method="POST" class="clinic-form clinic-form--wide">
        @csrf
        <div class="clinic-form-group">
            <label>Вопрос *</label>
            <input type="text" name="question" value="{{ old('question') }}" required>
        </div>
        <div class="clinic-form-group">
            <label>Ответ *</label>
            <textarea name="answer" rows="5" required>{{ old('answer') }}</textarea>
        </div>
        <div class="clinic-form-group clinic-form-group--inline">
            <label class="clinic-checkbox">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                <span>Активен</span>
            </label>
        </div>
        <div class="clinic-form-group">
            <label>Порядок</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
        </div>

        <div class="clinic-form-actions">
            <button type="submit" class="clinic-btn clinic-btn--primary">Создать</button>
            <a href="{{ route('admin.faqs.index') }}" class="clinic-btn clinic-btn--ghost">Отмена</a>
        </div>
    </form>
@endsection

