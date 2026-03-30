@extends('layouts.clinic')

@section('content')
<section class="clinic-page">
    <div class="clinic-container">
<h1>Записаться к врачу — шаг 3: выбор даты и времени</h1>

<p>Специализация: {{ $specialization->name }}</p>
<p>Врач:
    {{ $doctor->last_name }}
    {{ $doctor->first_name }}
    {{ $doctor->middle_name }}
</p>

        <form method="GET" action="{{ route('client.book.datetime') }}" class="clinic-form">
    <input type="hidden" name="specialization_id" value="{{ $specialization->id }}">
    <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

    <label>Дата:</label>
    <input type="date" name="date" value="{{ $currentDate->toDateString() }}" required>
            <button type="submit" class="btn-primary">Показать слоты</button>
        </form>

        <hr>

        <h2>Время на {{ $currentDate->format('d.m.Y') }}</h2>

        @if ($errors->has('time'))
            <div class="clinic-alert-error">{{ $errors->first('time') }}</div>
        @endif

        @if(empty($timeSlots))
            <p>На выбранную дату нет слотов.</p>
        @else
            <form method="POST" action="{{ route('client.book.store') }}" class="clinic-form">
        @csrf
        <input type="hidden" name="specialization_id" value="{{ $specialization->id }}">
        <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
        <input type="hidden" name="date" value="{{ $currentDate->toDateString() }}">

        @foreach($timeSlots as $slot)
            @php
                $time = $slot->format('H:i');
                $isOccupied = in_array($time, $occupiedTimes, true);
            @endphp

            <div>
                <label>
                    <input type="radio" name="time" value="{{ $time }}" {{ $isOccupied ? 'disabled' : '' }}>
                    {{ $time }} {{ $isOccupied ? '(занято)' : '' }}
                </label>
            </div>
        @endforeach

                <br>
                <button type="submit" class="btn-primary">Подтвердить запись</button>
            </form>
        @endif

        <p>
            <a href="{{ route('client.book.doctor.get', ['specialization_id' => $specialization->id]) }}">
                ← Назад к выбору врача
            </a>
        </p>
    </div>
</section>
@endsection