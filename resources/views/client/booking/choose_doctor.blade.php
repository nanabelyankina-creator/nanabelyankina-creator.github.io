@extends('layouts.clinic')

@section('content')
<section class="clinic-page">
    <div class="clinic-container">
        <h1>Записаться к врачу — шаг 2: выбор врача</h1>

        <p>Специализация: {{ $specialization->name }}</p>

        @if($doctors->isEmpty())
            <p>По выбранной специализации пока нет врачей.</p>
        @else
            <form action="{{ route('client.book.datetime') }}" method="GET" class="clinic-form">
                <input type="hidden" name="specialization_id" value="{{ $specialization->id }}">
                <div class="form-group">
                    <label>Врач:</label>
                    <select name="doctor_id" required>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}">
                                {{ $doctor->last_name }} {{ $doctor->first_name }} {{ $doctor->middle_name }}
                                (стаж: {{ $doctor->experience_years }} лет)
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-primary">Далее</button>
            </form>
        @endif

        <p><a href="{{ route('client.book.specialization') }}">← Назад</a></p>
    </div>
</section>
@endsection