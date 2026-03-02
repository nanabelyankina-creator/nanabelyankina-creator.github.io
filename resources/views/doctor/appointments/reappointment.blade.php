@extends('layouts.clinic')

@section('content')
<section class="clinic-page">
    <div class="clinic-container">
        <h1>Повторная запись пациента</h1>

        <p>
            Пациент: {{ $appointment->patient->last_name }} {{ $appointment->patient->first_name }} {{ $appointment->patient->middle_name }}<br>
            Специализация: {{ $appointment->specialization->name }}
        </p>

        @if ($errors->any())
            <div class="clinic-alert clinic-alert--error">
                <ul>
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="GET" action="{{ route('doctor.appointments.reappointment', $appointment) }}" class="clinic-form">
            <div class="clinic-form-group">
                <label>Дата</label>
                <input type="date" name="date" value="{{ $currentDate->toDateString() }}" required>
            </div>
            <button type="submit" class="clinic-btn clinic-btn--ghost">Показать слоты</button>
        </form>

        <hr style="margin:1.5rem 0;">
        <h2>Выбор времени на {{ $currentDate->format('d.m.Y') }}</h2>

        <form method="POST" action="{{ route('doctor.appointments.reappointment.store', $appointment) }}" class="clinic-form">
            @csrf
            <input type="hidden" name="date" value="{{ $currentDate->toDateString() }}">

            <div class="clinic-time-slots">
                @foreach($timeSlots as $slot)
                    @php
                        $time = $slot->format('H:i');
                        $isOccupied = in_array($time, $occupiedTimes, true);
                    @endphp
                    <label class="clinic-time-slot {{ $isOccupied ? 'clinic-time-slot--disabled' : '' }}">
                        <input type="radio" name="time" value="{{ $time }}" {{ $isOccupied ? 'disabled' : '' }} required>
                        <span>{{ $time }} {{ $isOccupied ? '(занято)' : '' }}</span>
                    </label>
                @endforeach
            </div>

            <div class="clinic-form-actions">
                <button type="submit" class="clinic-btn clinic-btn--primary">Создать повторную запись</button>
                <a href="{{ route('doctor.appointments.index') }}" class="clinic-btn clinic-btn--ghost">Назад к записям</a>
            </div>
        </form>
    </div>
</section>
@endsection
