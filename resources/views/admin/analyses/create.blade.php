@extends('layouts.admin')

@section('admin-content')
    <div class="clinic-admin-header">
        <h1>Добавить анализ</h1>
        <p class="clinic-admin-subtitle">Создайте новую запись лабораторного анализа пациента.</p>
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

    <form action="{{ route('admin.analyses.store') }}" method="POST" class="clinic-form clinic-form--wide">
        @csrf

        <div class="clinic-form-group">
            <label>Пациент *</label>
            <select name="patient_id" required>
                @foreach($patients as $patient)
                    <option value="{{ $patient->id }}">
                        {{ $patient->last_name }} {{ $patient->first_name }} {{ $patient->middle_name }}
                        (СНИЛС: {{ $patient->snils }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="clinic-form-group">
            <label>Врач (опционально)</label>
            <select name="doctor_id">
                <option value="">Не указан</option>
                @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}">
                        {{ $doctor->last_name }} {{ $doctor->first_name }} {{ $doctor->middle_name }}
                        ({{ $doctor->specialization->name }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="clinic-form-group">
            <label>Тип анализа *</label>
            <input type="text" name="type" value="{{ old('type') }}" required>
        </div>

        <div class="clinic-form-group">
            <label>Дата сдачи</label>
            <input type="date" name="taken_at" value="{{ old('taken_at') }}">
        </div>

        <div class="clinic-form-group">
            <label>Путь к файлу (file_path) — пока строка</label>
            <input type="text" name="file_path" value="{{ old('file_path') }}">
        </div>

        <div class="clinic-form-group">
            <label>Текстовый результат</label>
            <textarea name="result_text" rows="5">{{ old('result_text') }}</textarea>
        </div>

        <div class="clinic-form-actions">
            <button type="submit" class="clinic-btn clinic-btn--primary">Создать</button>
            <a href="{{ route('admin.analyses.index') }}" class="clinic-btn clinic-btn--ghost">Отмена</a>
        </div>
    </form>
@endsection
