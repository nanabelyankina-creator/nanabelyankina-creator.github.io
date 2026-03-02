@extends('layouts.admin')

@section('admin-content')
    <div class="clinic-admin-header">
        <div>
            <h1>Врачи</h1>
            <p class="clinic-admin-subtitle">
                Всего врачей: {{ $doctors->count() }}
            </p>
        </div>

        <div class="clinic-admin-actions">
            @if (Route::has('admin.doctors.create'))
                <a href="{{ route('admin.doctors.create') }}" class="clinic-btn">
                    + Добавить врача
                </a>
            @endif
        </div>
    </div>

    {{-- Фильтры --}}
    <form method="GET" action="{{ route('admin.doctors.index') }}" class="clinic-filter">
        <div class="clinic-form-group">
            <label for="q">Поиск по ФИО</label>
            <input
                type="text"
                id="q"
                name="q"
                value="{{ request('q') }}"
                placeholder="Фамилия или имя врача"
            >
        </div>

        <div class="clinic-form-group">
            <label for="specialization_id">Специализация</label>
            <select name="specialization_id" id="specialization_id">
                <option value="">Все специализации</option>
                @foreach(\App\Models\Specialization::orderBy('name')->get() as $spec)
                    <option value="{{ $spec->id }}"
                        {{ (string)request('specialization_id') === (string)$spec->id ? 'selected' : '' }}>
                        {{ $spec->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="clinic-form-actions">
            <button type="submit" class="clinic-btn">
                Применить
            </button>
            <a href="{{ route('admin.doctors.index') }}" class="clinic-btn clinic-btn--ghost">
                Сбросить
            </a>
        </div>
    </form>

    {{-- Сообщения --}}
    @if (session('success'))
        <div class="clinic-alert clinic-alert--success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="clinic-alert clinic-alert--error">
            {{ session('error') }}
        </div>
    @endif

    {{-- Таблица врачей --}}
    @if ($doctors->isEmpty())
        <p class="clinic-empty">
            В системе пока нет ни одного врача.
            @if (Route::has('admin.doctors.create'))
                <a href="{{ route('admin.doctors.create') }}" class="clinic-link">Создать первого врача</a>.
            @endif
        </p>
    @else
        <div class="clinic-table-wrapper">
            <table class="clinic-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ФИО</th>
                        <th>Специализация</th>
                        <th>Аккаунт</th>
                        <th>Стаж</th>
                        <th>Категория</th>
                        <th>Базовая цена</th>
                        <th style="text-align: right;">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($doctors as $doctor)
                        <tr>
                            <td>{{ $doctor->id }}</td>

                            <td>
                                <div>
                                    <strong>
                                        {{ $doctor->last_name }}
                                        {{ $doctor->first_name }}
                                        @if($doctor->middle_name)
                                            {{ $doctor->middle_name }}
                                        @endif
                                    </strong>
                                </div>
                                @if ($doctor->about)
                                    <div class="clinic-text-muted" style="font-size: 0.8rem;">
                                        {{ \Illuminate\Support\Str::limit($doctor->about, 80) }}
                                    </div>
                                @endif
                            </td>

                            <td>
                                {{ $doctor->specialization?->name ?? '—' }}
                            </td>

                            <td>
                                @if ($doctor->user)
                                    <div><strong>{{ $doctor->user->name }}</strong></div>
                                    <div class="clinic-text-muted" style="font-size: 0.8rem;">
                                        {{ $doctor->user->email ?? 'email не указан' }}<br>
                                        {{ $doctor->user->phone ?? 'телефон не указан' }}
                                    </div>
                                    <div style="margin-top: 0.2rem;">
                                        <span class="clinic-badge clinic-badge--muted">
                                            Роль: {{ $doctor->user->role }}
                                        </span>
                                        @if($doctor->user->is_blocked)
                                            <span class="clinic-badge clinic-badge--danger">
                                                Заблокирован
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="clinic-badge clinic-badge--muted">
                                        Нет связанного пользователя
                                    </span>
                                @endif
                            </td>

                            <td>{{ $doctor->experience_years }} лет</td>
                            <td>{{ $doctor->category ?? '—' }}</td>
                            <td>
                                @if ($doctor->base_price)
                                    {{ number_format($doctor->base_price, 0, ',', ' ') }} ₽
                                @else
                                    —
                                @endif
                            </td>

                            <td style="text-align: right;">
                                <div class="clinic-table-actions">
                                    @if (Route::has('admin.doctors.edit'))
                                        <a href="{{ route('admin.doctors.edit', $doctor) }}"
                                           class="clinic-link">
                                            Редактировать
                                        </a>
                                    @endif

                                    @if (Route::has('admin.doctors.destroy'))
                                        <form action="{{ route('admin.doctors.destroy', $doctor) }}"
                                              method="POST"
                                              class="clinic-inline-form"
                                              onsubmit="return confirm('Удалить этого врача? Действие необратимо.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="clinic-link clinic-link--danger"
                                                    style="background:none;border:none;padding:0;cursor:pointer;">
                                                Удалить
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection