@extends('layouts.admin')

@section('admin-content')
    <div class="clinic-admin-header">
        <h1>Редактировать страницу</h1>
        <p class="clinic-admin-subtitle">Измените содержимое и URL для страницы «{{ $page->title }}».</p>
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

    <form action="{{ route('admin.pages.update', $page) }}" method="POST" class="clinic-form clinic-form--wide">
        @csrf
        @method('PUT')
        <div class="clinic-form-group">
            <label>Slug (URL) *</label>
            <input type="text" name="slug" value="{{ old('slug', $page->slug) }}" required pattern="[a-z0-9\-]+">
        </div>
        <div class="clinic-form-group">
            <label>Заголовок *</label>
            <input type="text" name="title" value="{{ old('title', $page->title) }}" required>
        </div>
        <div class="clinic-form-group">
            <label>Содержимое</label>
            <textarea name="content" rows="15">{{ old('content', $page->content) }}</textarea>
        </div>

        <div class="clinic-form-actions">
            <button type="submit" class="clinic-btn clinic-btn--primary">Сохранить</button>
            <a href="{{ route('admin.pages.index') }}" class="clinic-btn clinic-btn--ghost">Отмена</a>
        </div>
    </form>
@endsection

