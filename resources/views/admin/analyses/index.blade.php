@extends('layouts.admin')

@section('admin-content')
    <div class="clinic-admin-header">
        <h1>Анализы</h1>
        <p class="clinic-admin-subtitle">Учет лабораторных анализов пациентов.</p>
        <a href="{{ route('admin.analyses.create') }}" class="clinic-btn clinic-btn--primary">Добавить анализ</a>
    </div>

    @if(session('success'))
        <div class="clinic-alert clinic-alert--success">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('admin.analyses.index') }}" class="clinic-filter">
        <div class="clinic-form-group">
            <label>СНИЛС пациента:</label>
            <input type="text" name="snils" value="{{ request('snils') }}">
        </div>

        <div class="clinic-form-group">
            <label>Тип анализа:</label>
            <input type="text" name="type" value="{{ request('type') }}">
        </div>

        <button type="submit" class="clinic-btn clinic-btn--ghost">Фильтровать</button>
    </form>

    @if($analyses->isEmpty())
        <p class="clinic-empty">Анализы не найдены.</p>
    @else
        <div class="clinic-table-wrapper">
            <table class="clinic-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Пациент</th>
                        <th>СНИЛС</th>
                        <th>Врач</th>
                        <th>Тип</th>
                        <th>Дата сдачи</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($analyses as $analysis)
                        <tr>
                            <td>{{ $analysis->id }}</td>
                            <td>
                                @if($analysis->patient)
                                    {{ $analysis->patient->last_name }}
                                    {{ $analysis->patient->first_name }}
                                    {{ $analysis->patient->middle_name }}
                                @else
                                    <span class="clinic-text-muted">(пациент удалён)</span>
                                @endif
                            </td>
                            <td>{{ $analysis->patient?->snils }}</td>
                            <td>
                                @if($analysis->doctor)
                                    {{ $analysis->doctor->last_name }} {{ $analysis->doctor->first_name }}
                                @else
                                    <span class="clinic-text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $analysis->type }}</td>
                            <td>
                                {{ $analysis->taken_at ? $analysis->taken_at->format('d.m.Y') : '-' }}
                            </td>
                            <td class="clinic-table-actions">
                                <a href="{{ route('admin.analyses.edit', $analysis) }}" class="clinic-link">Редактировать</a>
                                <form action="{{ route('admin.analyses.destroy', $analysis) }}" method="POST" class="clinic-inline-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="clinic-link clinic-link--danger" onclick="return confirm('Удалить анализ?')">Удалить</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $analyses->links() }}
    @endif
@endsection

