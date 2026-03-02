@extends('layouts.admin')

@section('admin-content')
    <div class="clinic-admin-header">
        <h1>Редактировать специализацию</h1>
        <p class="clinic-admin-subtitle">Измените название и описание специализации.</p>
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

    <form action="{{ route('admin.specializations.update', $specialization) }}" method="POST" class="clinic-form clinic-form--wide">
        @csrf
        @method('PUT')

        <div class="clinic-form-group">
            <label>Название *</label>
            <input type="text" name="name" value="{{ old('name', $specialization->name) }}" required>
        </div>

        <div class="clinic-form-group">
            <label>Описание</label>
            <textarea name="description" rows="4">{{ old('description', $specialization->description) }}</textarea>
        </div>

        <div class="clinic-form-actions">
            <button type="submit" class="clinic-btn clinic-btn--primary">Сохранить</button>
            <a href="{{ route('admin.specializations.index') }}" class="clinic-btn clinic-btn--ghost">Отмена</a>
        </div>
    </form>
@endsection
