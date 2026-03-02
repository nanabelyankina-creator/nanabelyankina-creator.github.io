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
            <h1>Мои записи на {{ $currentDate->format('d.m.Y') }}</h1>

            @if(session('success'))
                <div class="clinic-alert-success">{{ session('success') }}</div>
            @endif

            <p>Врач: {{ $doctor->last_name }} {{ $doctor->first_name }} {{ $doctor->middle_name }}</p>

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

                    <button type="submit" class="clinic-btn">Показать</button>
                </div>
            </form>

            <hr>

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
                                <p>
                                    <a href="{{ route('doctor.appointments.reappointment', $appointment) }}">Повторно записать</a>
                                    @if($appointment->patient)
                                        | <a href="{{ route('doctor.appointments.analyses', $appointment) }}">Анализы пациента</a>
                                    @endif
                                </p>
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