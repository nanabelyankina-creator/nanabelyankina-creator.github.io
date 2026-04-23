@extends('layouts.admin')

@section('admin-content')
    <div class="clinic-admin-header">
        <h1>Редактировать анализ #{{ $analysis->id }}</h1>
        <p class="clinic-admin-subtitle">Измените данные лабораторного анализа пациента.</p>
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

    <form action="{{ route('admin.analyses.update', $analysis) }}" method="POST" enctype="multipart/form-data" class="clinic-form clinic-form--wide">
        @csrf
        @method('PUT')

        <div class="clinic-form-group">
            <label>Пациент *</label>
            <select name="patient_id" required>
                @foreach($patients as $patient)
                    <option value="{{ $patient->id }}" {{ $patient->id === $analysis->patient_id ? 'selected' : '' }}>
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
                    <option value="{{ $doctor->id }}" {{ $doctor->id === $analysis->doctor_id ? 'selected' : '' }}>
                        {{ $doctor->last_name }} {{ $doctor->first_name }} {{ $doctor->middle_name }}
                        ({{ $doctor->specialization->name }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="clinic-form-group">
            <label>Тип анализа *</label>
            <input type="text" name="type" value="{{ old('type', $analysis->type) }}" required>
        </div>

        <div class="clinic-form-group">
            <label>Дата сдачи</label>
            <input type="date" name="taken_at" value="{{ old('taken_at', optional($analysis->taken_at)->toDateString()) }}">
        </div>

        <div class="clinic-form-group">
            <label>Файл анализа (опционально)</label>
            @php
                $existingFile = $analysis->file_path ? basename($analysis->file_path) : null;
                $existingLabel = $existingFile ? \Illuminate\Support\Str::limit($existingFile, 15, '…') : 'Выберите файл';
            @endphp
            <div class="clinic-file-field" data-file-field>
                <div class="clinic-file-field__name" data-file-name title="{{ $existingFile ?? '' }}">{{ $existingLabel }}</div>
                <input type="file" name="analysis_file" id="analysis-file" class="clinic-file-field__input" accept=".pdf,image/*">
                <label for="analysis-file" class="clinic-btn clinic-btn--ghost clinic-file-field__btn">
                    {{ $analysis->file_path ? 'Изменить файл' : 'Выбрать файл' }}
                </label>
            </div>

            @if($analysis->file_path)
                <div class="clinic-file-link">
                    <a class="clinic-link" href="{{ asset($analysis->file_path) }}" target="_blank">Открыть текущий файл</a>
                </div>
            @endif
        </div>

        <div class="clinic-form-group">
            <label>Текстовый результат</label>
            <textarea name="result_text" rows="5">{{ old('result_text', $analysis->result_text) }}</textarea>
        </div>

        <div class="clinic-form-actions">
            <button type="submit" class="clinic-btn clinic-btn--primary">Сохранить</button>
            <a href="{{ route('admin.analyses.index') }}" class="clinic-btn clinic-btn--ghost">Отмена</a>
        </div>
    </form>

    @push('scripts')
        <script>
        (function() {
            function truncateName(name, max) {
                if (!name) return '';
                if (name.length <= max) return name;
                return name.slice(0, max - 1) + '…';
            }

            document.querySelectorAll('[data-file-field]').forEach(function(wrap) {
                var input = wrap.querySelector('input[type="file"]');
                var nameEl = wrap.querySelector('[data-file-name]');
                if (!input || !nameEl) return;

                function render() {
                    var file = input.files && input.files[0] ? input.files[0] : null;
                    if (file) {
                        nameEl.textContent = truncateName(file.name, 15);
                        nameEl.title = file.name;
                    }
                }

                input.addEventListener('change', render);
            });
        })();
        </script>
    @endpush
@endsection
