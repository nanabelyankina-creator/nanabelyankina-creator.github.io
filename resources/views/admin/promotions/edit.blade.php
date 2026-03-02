@extends('layouts.admin')

@section('admin-content')
    <div class="clinic-admin-header">
        <div>
            <h1>Редактирование акции</h1>
            <p class="clinic-admin-subtitle">
                {{ $promotion->title }} (ID: {{ $promotion->id }})
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

    @if (session('success'))
        <div class="clinic-alert clinic-alert--success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.promotions.update', $promotion) }}" method="POST" class="clinic-form clinic-form--wide">
        @csrf
        @method('PUT')

        <div class="clinic-form-group">
            <label for="title">Заголовок акции *</label>
            <input type="text" id="title" name="title"
                   value="{{ old('title', $promotion->title) }}" required>
        </div>

        <div class="clinic-form-group">
            <label for="short_description">Краткое описание</label>
            <textarea id="short_description" name="short_description" rows="2">{{ old('short_description', $promotion->short_description) }}</textarea>
        </div>

        <div class="clinic-form-group">
            <label for="content">Полное описание</label>
            <textarea id="content" name="content" rows="6">{{ old('content', $promotion->content) }}</textarea>
        </div>

        <div class="clinic-form-group clinic-form-group--inline">
            <div class="clinic-form-group">
                <label for="starts_at">Дата начала</label>
                <input type="date" id="starts_at" name="starts_at"
                       value="{{ old('starts_at', optional($promotion->starts_at)->format('Y-m-d')) }}">
            </div>

            <div class="clinic-form-group">
                <label for="ends_at">Дата окончания</label>
                <input type="date" id="ends_at" name="ends_at"
                       value="{{ old('ends_at', optional($promotion->ends_at)->format('Y-m-d')) }}">
            </div>
        </div>

        <div class="clinic-form-group clinic-form-group--inline">
            <label class="clinic-checkbox">
                <input type="checkbox" name="is_active" value="1"
                       {{ old('is_active', $promotion->is_active) ? 'checked' : '' }}>
                <span>Акция активна</span>
            </label>
        </div>

        {{-- Скидка, % --}}
        <div class="clinic-form-group clinic-form-group--inline">
            <div class="clinic-form-group">
                <label for="discount_percent">Скидка, %</label>
                <input type="number" id="discount_percent" name="discount_percent"
                       min="0" max="100"
                       value="{{ old('discount_percent', $promotion->discount_percent) }}">
                <p class="clinic-form-hint">
                    Укажите процент скидки для выбранных пациентов (например, 20).
                </p>
            </div>
        </div>

        <div class="clinic-form-actions">
            <button type="submit" class="clinic-btn">
                Сохранить изменения
            </button>
            <a href="{{ route('admin.promotions.index') }}" class="clinic-btn clinic-btn--ghost">
                Отмена
            </a>
        </div>
    </form>

    <hr class="clinic-divider">

    {{-- Блок пациентов акции --}}
    <section class="clinic-admin-section">
        <div class="clinic-admin-header" style="margin-bottom: 0.75rem;">
            <div>
                <h2>Пациенты, на которых действует акция</h2>
                <p class="clinic-admin-subtitle">
                    У этих пациентов цена приёмов будет снижена на {{ $promotion->discount_percent ?? 0 }}%.
                </p>
            </div>
            <div>
                <button type="button" class="clinic-btn" onclick="openPatientModal()">
                    + Добавить пациента
                </button>
            </div>
        </div>

        @if($promotion->patients->isEmpty())
            <p>Пока ни один пациент не привязан к этой акции.</p>
        @else
            <div class="clinic-table-wrapper">
                <table class="clinic-table">
                    <thead>
                    <tr>
                        <th>ФИО</th>
                        <th>СНИЛС</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($promotion->patients as $patient)
                        <tr>
                            <td>
                                {{ $patient->last_name }} {{ $patient->first_name }} {{ $patient->middle_name }}
                            </td>
                            <td>
                                <span style="color:#6b7280;">{{ $patient->snils }}</span>
                            </td>
                            <td class="clinic-table-actions">
                                <form action="{{ route('admin.promotions.patients.detach', [$promotion, $patient]) }}"
                                      method="POST" onsubmit="return confirm('Удалить пациента из акции?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="clinic-btn clinic-btn--ghost clinic-btn--sm">
                                        Удалить
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    {{-- Модалка выбора пациента --}}
    <div id="patientModal" class="clinic-modal" style="display:none;">
        <div class="clinic-modal__backdrop" onclick="closePatientModal()"></div>
        <div class="clinic-modal__dialog">
            <div class="clinic-modal__header">
                <h3 class="clinic-modal__title">Добавить пациента к акции</h3>
                <button type="button" class="clinic-modal__close" onclick="closePatientModal()">×</button>
            </div>
            <div class="clinic-modal__body">
                <div class="clinic-form-group">
                    <label for="patientSearch">Поиск по ФИО или СНИЛС</label>
                    <input type="text" id="patientSearch" class="clinic-input"
                           placeholder="Начните вводить фамилию, имя или СНИЛС"
                           oninput="searchPatients()">
                </div>
                <div id="patientResults" class="clinic-list" style="max-height: 300px; overflow-y: auto; margin-top: 0.5rem;">
                    {{-- сюда AJAX подставит результаты --}}
                </div>
            </div>
        </div>
    </div>

    {{-- Скрытая форма для привязки пациента --}}
    <form id="attachPatientForm" action="{{ route('admin.promotions.patients.attach', $promotion) }}"
          method="POST" style="display:none;">
        @csrf
        <input type="hidden" name="patient_id" id="attachPatientId">
    </form>
@endsection

@push('scripts')
<script>
    function openPatientModal() {
        document.getElementById('patientModal').style.display = 'block';
        document.getElementById('patientSearch').focus();
        document.getElementById('patientResults').innerHTML = '';
    }

    function closePatientModal() {
        document.getElementById('patientModal').style.display = 'none';
    }

    let searchTimeout = null;

    function searchPatients() {
        clearTimeout(searchTimeout);

        searchTimeout = setTimeout(() => {
            const q = document.getElementById('patientSearch').value;
            const url = '{{ route('admin.promotions.patients.search', $promotion) }}' + '?q=' + encodeURIComponent(q);

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(r => r.json())
                .then(data => {
                    const container = document.getElementById('patientResults');
                    container.innerHTML = '';

                    if (!data.length) {
                        container.innerHTML = '<p>Пациенты не найдены.</p>';
                        return;
                    }

                    data.forEach(p => {
                        const item = document.createElement('button');
                        item.type = 'button';
                        item.className = 'clinic-list-item';
                        item.style.textAlign = 'left';
                        item.style.width = '100%';
                        item.innerHTML = `
                            <strong>${p.name}</strong><br>
                            <span style="color:#6b7280;">${p.snils}</span>
                        `;
                        item.onclick = () => selectPatient(p.id);
                        container.appendChild(item);
                    });
                })
                .catch(() => {
                    document.getElementById('patientResults').innerHTML =
                        '<p>Ошибка загрузки данных. Попробуйте ещё раз.</p>';
                });
        }, 300);
    }

    function selectPatient(id) {
        document.getElementById('attachPatientId').value = id;
        document.getElementById('attachPatientForm').submit();
    }
</script>
@endpush