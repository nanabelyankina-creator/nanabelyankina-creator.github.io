@extends('layouts.clinic')

@section('content')
<section class="clinic-page">
    <div class="clinic-container">
        <h1>Записаться к врачу — шаг 1: выбор специализации</h1>

        @if($specializations->isEmpty())
            <p>Специализации пока не настроены.</p>
        @else
            <form action="{{ route('client.book.doctor') }}" method="POST" class="clinic-form">
                @csrf
                <div class="form-group">
                    <label>Специализация:</label>
                    <select name="specialization_id" required>
                        @foreach($specializations as $spec)
                            <option value="{{ $spec->id }}">{{ $spec->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-primary">Далее</button>
            </form>
        @endif

        <p><a href="{{ route('profile') }}">Вернуться в профиль</a></p>
    </div>
</section>
@endsection