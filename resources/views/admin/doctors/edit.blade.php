@extends('layouts.admin')

@section('admin-content')
    <div class="clinic-admin-header">
        <h1>Редактировать врача #{{ $doctor->id }}</h1>
        <p class="clinic-admin-subtitle">Изменение данных врача и его образования.</p>
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

    <form action="{{ route('admin.doctors.update', $doctor) }}" method="POST" class="clinic-form clinic-form--wide">
        @csrf
        @method('PUT')

        <p class="clinic-text-muted">
            Пользователь:
            @if($doctor->user)
                {{ $doctor->user->name }} (ID: {{ $doctor->user->id }})
            @else
                (нет привязанного пользователя)
            @endif
        </p>

        <div class="clinic-form-group">
            <label>Специализация *</label>
            <select name="specialization_id" required>
                @foreach($specializations as $spec)
                    <option value="{{ $spec->id }}" {{ $spec->id === $doctor->specialization_id ? 'selected' : '' }}>
                        {{ $spec->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="clinic-form-group">
            <label>Фамилия *</label>
            <input type="text" name="last_name" value="{{ old('last_name', $doctor->last_name) }}" required>
        </div>

        <div class="clinic-form-group">
            <label>Имя *</label>
            <input type="text" name="first_name" value="{{ old('first_name', $doctor->first_name) }}" required>
        </div>

        <div class="clinic-form-group">
            <label>Отчество</label>
            <input type="text" name="middle_name" value="{{ old('middle_name', $doctor->middle_name) }}">
        </div>

        <div class="clinic-form-group">
            <label>Стаж (лет) *</label>
            <input type="number" name="experience_years" value="{{ old('experience_years', $doctor->experience_years) }}" min="0" max="80" required>
        </div>

        <div class="clinic-form-group">
            <label>Категория</label>
            <input type="text" name="category" value="{{ old('category', $doctor->category) }}">
        </div>

        <div class="clinic-form-group">
            <label>Стоимость приёма (₽) *</label>
            <input type="number" name="base_price" value="{{ old('base_price', $doctor->base_price) }}" min="0" required>
        </div>

        <div class="clinic-form-group">
            <label>О себе</label>
            <textarea name="about" rows="5">{{ old('about', $doctor->about) }}</textarea>
        </div>

        <hr>
        <h3>Образование</h3>
        <p class="clinic-text-muted">ВУЗ, ординатура, курсы — отображается в публичном профиле врача.</p>
        <div id="educations-container">
            @php $educations = old('educations', $doctor->educations ?? collect()); @endphp
            @forelse($educations as $idx => $ed)
                @php $ed = is_object($ed) ? $ed : (object) $ed; @endphp
                <div class="education-row clinic-card clinic-card--inline">
                    <select name="educations[{{ $idx }}][type]" required>
                        @foreach(\App\Models\DoctorEducation::types() as $k => $v)
                            <option value="{{ $k }}" {{ ($ed->type ?? '') == $k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="educations[{{ $idx }}][institution]" placeholder="Учебное заведение" value="{{ $ed->institution ?? '' }}" required>
                    <input type="number" name="educations[{{ $idx }}][year]" placeholder="Год" value="{{ $ed->year ?? '' }}" min="1950" max="{{ date('Y')+5 }}">
                    <input type="text" name="educations[{{ $idx }}][specialty]" placeholder="Специальность" value="{{ $ed->specialty ?? '' }}">
                    <button type="button" class="remove-education clinic-btn clinic-btn--ghost">×</button>
                </div>
            @empty
                {{-- Пусто — кнопка "Добавить" создаст первую запись --}}
            @endforelse
        </div>
        <button type="button" id="add-education" class="clinic-btn clinic-btn--ghost" style="margin-top:10px;">+ Добавить образование</button>

        <div class="clinic-form-actions" style="margin-top:1.5rem;">
            <button type="submit" class="clinic-btn clinic-btn--primary">Сохранить</button>
            <a href="{{ route('admin.doctors.index') }}" class="clinic-btn clinic-btn--ghost">Отмена</a>
        </div>
    </form>

    <script>
    document.getElementById('add-education').addEventListener('click', function() {
        const cnt = document.querySelectorAll('.education-row').length;
        const html = `<div class="education-row clinic-card clinic-card--inline">
            <select name="educations[${cnt}][type]" required>
                @foreach(\App\Models\DoctorEducation::types() as $k => $v)
                    <option value="{{ $k }}">{{ $v }}</option>
                @endforeach
            </select>
            <input type="text" name="educations[${cnt}][institution]" placeholder="Учебное заведение" required>
            <input type="number" name="educations[${cnt}][year]" placeholder="Год" min="1950" max="{{ date('Y')+5 }}">
            <input type="text" name="educations[${cnt}][specialty]" placeholder="Специальность">
            <button type="button" class="remove-education clinic-btn clinic-btn--ghost">×</button>
        </div>`;
        document.getElementById('educations-container').insertAdjacentHTML('beforeend', html);
    });
    document.getElementById('educations-container').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-education')) {
            e.target.closest('.education-row').remove();
        }
    });
    </script>
@endsection
