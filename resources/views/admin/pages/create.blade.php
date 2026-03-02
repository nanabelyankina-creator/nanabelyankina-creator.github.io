@extends('layouts.admin')

@section('admin-content')
    <div class="clinic-admin-header">
        <h1>Создать страницу</h1>
        <p class="clinic-admin-subtitle">Добавьте новую информационную страницу (например: «О нас», «Контакты»).</p>
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

    <form action="{{ route('admin.pages.store') }}" method="POST" class="clinic-form clinic-form--wide">
        @csrf
        <div class="clinic-form-group">
            <label>Slug (URL) * <small>например: about, contacts</small></label>
            <input type="text" name="slug" value="{{ old('slug') }}" required pattern="[a-z0-9\-]+" placeholder="about">
        </div>
        <div class="clinic-form-group">
            <label>Заголовок *</label>
            <input type="text" name="title" value="{{ old('title') }}" required>
        </div>
        <div class="clinic-form-group">
            <label>Содержимое</label>
            <textarea name="content" rows="15">{{ old('content') }}</textarea>
        </div>

        <div class="clinic-form-actions">
            <button type="submit" class="clinic-btn clinic-btn--primary">Создать</button>
            <a href="{{ route('admin.pages.index') }}" class="clinic-btn clinic-btn--ghost">Отмена</a>
        </div>
    </form>
@endsection

