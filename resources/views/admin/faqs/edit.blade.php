@extends('layouts.admin')

@section('admin-content')
    <div class="clinic-admin-header">
        <h1>Редактировать вопрос #{{ $faq->id }}</h1>
        <p class="clinic-admin-subtitle">Измените текст вопроса и ответа для раздела FAQ.</p>
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

    <form action="{{ route('admin.faqs.update', $faq) }}" method="POST" class="clinic-form clinic-form--wide">
        @csrf
        @method('PUT')
        <div class="clinic-form-group">
            <label>Вопрос *</label>
            <input type="text" name="question" value="{{ old('question', $faq->question) }}" required>
        </div>
        <div class="clinic-form-group">
            <label>Ответ *</label>
            <textarea name="answer" rows="5" required>{{ old('answer', $faq->answer) }}</textarea>
        </div>
        <div class="clinic-form-group clinic-form-group--inline">
            <label class="clinic-checkbox">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $faq->is_active) ? 'checked' : '' }}>
                <span>Активен</span>
            </label>
        </div>
        <div class="clinic-form-group">
            <label>Порядок</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $faq->sort_order) }}" min="0">
        </div>

        <div class="clinic-form-actions">
            <button type="submit" class="clinic-btn clinic-btn--primary">Сохранить</button>
            <a href="{{ route('admin.faqs.index') }}" class="clinic-btn clinic-btn--ghost">Отмена</a>
        </div>
    </form>
@endsection

