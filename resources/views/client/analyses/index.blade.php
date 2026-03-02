@extends('layouts.clinic')

@section('content')
<section class="clinic-page">
    <div class="clinic-container">
        <h1>Мои анализы</h1>
        <p><a href="{{ route('profile') }}">← Вернуться в профиль</a></p>

        @if($analyses->isEmpty())
            <p>У вас еще нет анализов, обратитесь к врачу.</p>
        @else
            <ul class="analyses-list">
                @foreach($analyses as $analysis)
                    <li>
                        <strong>{{ $analysis->type }}</strong><br>
                        Дата сдачи: {{ $analysis->taken_at ? $analysis->taken_at->format('d.m.Y') : 'не указана' }}<br>
                        @if($analysis->doctor)
                            Назначил врач: {{ $analysis->doctor->last_name }} {{ $analysis->doctor->first_name }} {{ $analysis->doctor->middle_name }}<br>
                        @endif
                        @if($analysis->file_path)
                            <a href="{{ asset($analysis->file_path) }}" target="_blank">Скачать/Открыть файл</a><br>
                        @endif
                        @if($analysis->result_text)
                            <details>
                                <summary>Показать текст результата</summary>
                                <pre>{{ $analysis->result_text }}</pre>
                            </details>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</section>
@endsection
