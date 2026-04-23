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

        <div class="doctor-content">
            <div class="doctor-page-head">
                <h1>Прошлые записи</h1>
                <p class="clinic-text-muted">
                    Врач: {{ $doctor->last_name }} {{ $doctor->first_name }} {{ $doctor->middle_name }}
                </p>
            </div>

            <form method="GET" action="{{ route('doctor.appointments.past') }}" class="doctor-filters">
                <div class="doctor-filters-row">
                    <label>
                        Дата (опционально):
                        <input type="date" name="date" value="{{ $filterDate ?? '' }}">
                    </label>

                    <label>
                        Поиск по ФИО клиента:
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Введите ФИО">
                    </label>

                    <button type="submit" class="clinic-btn">Фильтровать</button>
                </div>
            </form>

            @if($appointments->isEmpty())
                <p class="clinic-empty">Прошлых приёмов пока нет.</p>
            @else
                <div class="doctor-appointments-list">
                    @foreach($appointments as $appointment)
                        <div class="doctor-appointment-card">
                            <div class="doctor-appointment-card__top">
                                <h3>Приём {{ $appointment->scheduled_at->format('d.m.Y H:i') }}</h3>
                                <span class="clinic-badge doctor-status-badge">
                                    @switch($appointment->status)
                                        @case('scheduled') Запланирован @break
                                        @case('in_progress') Идёт @break
                                        @case('completed') Завершён @break
                                        @case('no_show') Не пришёл @break
                                    @endswitch
                                </span>
                            </div>
                            <p>
                                <strong>Пациент:</strong>
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
                        </div>
                    @endforeach
                </div>

                {{ $appointments->withQueryString()->links() }}
            @endif
        </div>
    </div>
</section>
@endsection