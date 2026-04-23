@extends('layouts.clinic')

@section('content')
<section class="clinic-page">
    <div class="clinic-container client-analyses">
        <div class="client-analyses__head">
            <h1>Мои анализы</h1>
            <a href="{{ route('profile') }}" class="clinic-btn clinic-btn--ghost">Вернуться в профиль</a>
        </div>

        @if($analyses->isEmpty())
            <p class="clinic-empty">У вас еще нет анализов, обратитесь к врачу.</p>
        @else
            <ul class="analyses-list analyses-list--cards">
                @foreach($analyses as $analysis)
                    <li class="analyses-list__item">
                        <div class="analyses-list__top">
                            <strong>{{ $analysis->type }}</strong>
                            <span class="clinic-badge">
                                {{ $analysis->taken_at ? $analysis->taken_at->format('d.m.Y') : 'Дата не указана' }}
                            </span>
                        </div>
                        @if($analysis->doctor)
                            <p class="clinic-text-muted analyses-list__doctor">
                                Назначил врач: {{ $analysis->doctor->last_name }} {{ $analysis->doctor->first_name }} {{ $analysis->doctor->middle_name }}
                            </p>
                        @endif
                        @if($analysis->file_path)
                            <a href="{{ asset($analysis->file_path) }}" target="_blank" class="clinic-btn clinic-btn--ghost analyses-list__file-link">
                                Скачать файл
                            </a>
                        @endif
                        @if($analysis->result_text)
                            <details class="analyses-list__details">
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
