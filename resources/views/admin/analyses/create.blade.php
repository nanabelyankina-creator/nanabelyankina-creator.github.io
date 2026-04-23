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

    <form action="{{ route('admin.analyses.store') }}" method="POST" enctype="multipart/form-data" class="clinic-form clinic-form--wide">
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
            <label>Файл анализа (опционально)</label>
            <div class="clinic-file-field" data-file-field>
                <div class="clinic-file-field__name" data-file-name>Выберите файл</div>
                <input type="file" name="analysis_file" id="analysis-file" class="clinic-file-field__input" accept=".pdf,image/*">
                <label for="analysis-file" class="clinic-btn clinic-btn--ghost clinic-file-field__btn">Выбрать файл</label>
            </div>
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
                    nameEl.textContent = file ? truncateName(file.name, 15) : 'Выберите файл';
                    nameEl.title = file ? file.name : '';
                }

                input.addEventListener('change', render);
                render();
            });
        })();
        </script>
    @endpush
@endsection
