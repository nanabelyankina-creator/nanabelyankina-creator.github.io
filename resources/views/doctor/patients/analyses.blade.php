<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Анализы пациента</title>
</head>
<body>
<h1>Анализы пациента</h1>

<p>
    Врач:
    {{ $doctor->last_name }} {{ $doctor->first_name }} {{ $doctor->middle_name }}
</p>

<p>
    Пациент:
    {{ $patient->last_name }} {{ $patient->first_name }} {{ $patient->middle_name }}
</p>

<p><a href="{{ route('doctor.appointments.index') }}">← Назад к записям</a></p>

@if($analyses->isEmpty())
    <p>У данного пациента нет доступных анализов.</p>
@else
    <ul>
        @foreach($analyses as $analysis)
            <li style="margin-bottom: 15px;">
                <strong>{{ $analysis->type }}</strong><br>
                Дата сдачи:
                {{ $analysis->taken_at ? $analysis->taken_at->format('d.m.Y') : 'не указана' }}<br>

                @if($analysis->file_path)
                    <a href="{{ asset($analysis->file_path) }}" target="_blank">
                        Скачать/открыть файл
                    </a><br>
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

</body>
</html>