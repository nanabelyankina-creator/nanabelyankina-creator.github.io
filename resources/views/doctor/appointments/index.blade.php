@extends('layouts.clinic')

@section('content')
<section class="clinic-page">
    <div class="clinic-container doctor-layout">
        {{-- Sidebar --}}
        <aside class="doctor-sidebar">
            <h2>Кабинет врача</h2>
            <ul class="doctor-menu">
                <li class="{{ ($activeMenu ?? '') === 'today' ? 'active' : '' }}">
                    <a href="{{ route('doctor.appointments.index') }}">Записи сегодня</a>
                </li>
                <li class="{{ ($activeMenu ?? '') === 'past' ? 'active' : '' }}">
                    <a href="{{ route('doctor.appointments.past') }}">Прошлые записи</a>
                </li>
                <li>
                    <a href="{{ route('doctor.profile') }}">Мой аккаунт</a>
                </li>
            </ul>
        </aside>

        {{-- Основной контент --}}
        <div class="doctor-content">
            <div class="doctor-page-head">
                <h1>Мои записи на {{ $currentDate->format('d.m.Y') }}</h1>
                <p class="clinic-text-muted">
                    Врач: {{ $doctor->last_name }} {{ $doctor->first_name }} {{ $doctor->middle_name }}
                </p>
            </div>

            @if(session('success'))
                <div class="clinic-alert-success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="clinic-alert-error">
                    <ul class="clinic-alert-error__list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="GET" action="{{ route('doctor.appointments.index') }}" class="doctor-filters">
                <div class="doctor-filters-row">
                    <label>
                        Дата:
                        <input type="date" name="date" value="{{ $currentDate->toDateString() }}">
                    </label>

                    <label>
                        Поиск по ФИО клиента:
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Введите ФИО">
                    </label>

                    <button type="submit" class="clinic-btn">Фильтровать</button>
                </div>
            </form>

            <div class="doctor-slots-list">
                @foreach($timeSlots as $slot)
                    @php
                        $timeStr = $slot->format('H:i');
                        $appointment = $appointmentsByTime[$timeStr] ?? null;
                    @endphp
                    @if($appointment)
                        <div class="doctor-slot doctor-slot-occupied">
                            <div class="doctor-slot-time">{{ $timeStr }}</div>
                            <div class="doctor-slot-body">
                                <p><strong>Пациент:</strong>
                                    {{ $appointment->patient->last_name ?? '' }}
                                    {{ $appointment->patient->first_name ?? '' }}
                                    {{ $appointment->patient->middle_name ?? '' }}
                                </p>
                                <p><strong>Телефон:</strong> {{ $appointment->patient->user->phone ?? $appointment->patient->phone ?? 'не указан' }}</p>
                                <div class="doctor-appointment-card__actions">
                                    <a href="{{ route('doctor.appointments.reappointment', $appointment) }}" class="clinic-btn clinic-btn--ghost">
                                        Повторно записать
                                    </a>
                                    @if($appointment->patient)
                                        <a href="{{ route('doctor.appointments.analyses', $appointment) }}" class="clinic-btn clinic-btn--ghost">
                                            Анализы пациента
                                        </a>
                                    @endif
                                </div>
                                <form action="{{ route('doctor.appointments.updateStatus', $appointment) }}" method="POST" class="doctor-status-form">
                                    @csrf
                                    <select name="status">
                                        <option value="scheduled" {{ $appointment->status === 'scheduled' ? 'selected' : '' }}>Запланирован</option>
                                        <option value="in_progress" {{ $appointment->status === 'in_progress' ? 'selected' : '' }}>Идёт</option>
                                        <option value="completed" {{ $appointment->status === 'completed' ? 'selected' : '' }}>Завершён</option>
                                        <option value="no_show" {{ $appointment->status === 'no_show' ? 'selected' : '' }}>Не пришёл</option>
                                    </select>
                                    <button type="submit" class="clinic-btn clinic-btn-sm">Обновить статус</button>
                                </form>

                                @if($appointment->patient && $appointment->status === 'completed')
                                    <details class="doctor-analysis-create">
                                        <summary>Добавить анализ пациенту</summary>
                                        <form action="{{ route('doctor.appointments.analyses.store', $appointment) }}" method="POST" enctype="multipart/form-data" class="doctor-analysis-create__form">
                                            @csrf
                                            <div class="doctor-analysis-create__grid">
                                                <label>
                                                    Тип анализа *
                                                    <input type="text" name="analysis_type" value="{{ old('analysis_type') }}" required>
                                                </label>
                                                <label>
                                                    Дата сдачи
                                                    <input type="date" name="analysis_taken_at" value="{{ old('analysis_taken_at', optional($appointment->scheduled_at)->toDateString()) }}">
                                                </label>
                                            </div>
                                            <label>
                                                Текст результата
                                                <textarea name="analysis_result_text" rows="3" placeholder="Краткое описание результата">{{ old('analysis_result_text') }}</textarea>
                                            </label>
                                            <label>
                                                Файл анализа (опционально)
                                                <input type="file" name="analysis_file" accept=".pdf,image/*">
                                            </label>
                                            <button type="submit" class="clinic-btn clinic-btn-sm">Сохранить анализ</button>
                                        </form>
                                    </details>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="doctor-slot doctor-slot-free">
                            <span class="doctor-slot-time">{{ $timeStr }}</span>
                            <span class="doctor-slot-free-label">— свободно</span>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection