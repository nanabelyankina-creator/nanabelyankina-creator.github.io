@extends('layouts.clinic')

@section('content')
<section class="clinic-page">
    <div class="clinic-container doctor-layout">
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
            <div class="doctor-analyses-header">
                <div class="doctor-page-head">
                    <h1>Анализы пациента</h1>
                    <p class="clinic-text-muted">
                        Пациент: {{ $patient->last_name }} {{ $patient->first_name }} {{ $patient->middle_name }}
                    </p>
                </div>
                <a href="{{ route('doctor.appointments.past') }}" class="clinic-btn clinic-btn--ghost">Назад к записям</a>
            </div>

            <p class="clinic-text-muted">
                Врач: {{ $doctor->last_name }} {{ $doctor->first_name }} {{ $doctor->middle_name }}
            </p>

            @if($analyses->isEmpty())
                <p class="clinic-empty">У данного пациента пока нет доступных анализов.</p>
            @else
                <div class="doctor-analyses-list">
                    @foreach($analyses as $analysis)
                        <article class="doctor-analysis-card">
                            <div class="doctor-analysis-card__top">
                                <h3>{{ $analysis->type }}</h3>
                                <span class="clinic-badge">
                                    {{ $analysis->taken_at ? $analysis->taken_at->format('d.m.Y') : 'Дата не указана' }}
                                </span>
                            </div>

                            @if($analysis->file_path)
                                <a href="{{ asset($analysis->file_path) }}" target="_blank" class="clinic-btn clinic-btn--ghost doctor-analysis-card__file">
                                    Скачать файл
                                </a>
                            @endif

                            @if($analysis->result_text)
                                <details class="doctor-analysis-card__details">
                                    <summary>Показать текст результата</summary>
                                    <pre>{{ $analysis->result_text }}</pre>
                                </details>
                            @endif
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>
@endsection