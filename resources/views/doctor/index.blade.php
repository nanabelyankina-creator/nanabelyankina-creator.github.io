@extends('layouts.admin')

@section('admin-content')
    <div class="clinic-admin-header">
        <h1>Врачи</h1>
        <p class="clinic-admin-subtitle">Список всех врачей клиники.</p>
        <a href="{{ route('admin.doctors.create') }}" class="clinic-btn clinic-btn--primary">Добавить врача</a>
    </div>

    @if(session('success'))
        <div class="clinic-alert clinic-alert--success">{{ session('success') }}</div>
    @endif

    @if($doctors->isEmpty())
        <p class="clinic-empty">Врачи не добавлены.</p>
    @else
        <div class="clinic-table-wrapper">
            <table class="clinic-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ФИО</th>
                        <th>Пользователь</th>
                        <th>Специализация</th>
                        <th>Стаж</th>
                        <th>Категория</th>
                        <th>Цена</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($doctors as $doctor)
                        <tr>
                            <td>{{ $doctor->id }}</td>
                            <td>
                                {{ $doctor->last_name }}
                                {{ $doctor->first_name }}
                                {{ $doctor->middle_name }}
                            </td>
                            <td>
                                @if($doctor->user)
                                    {{ $doctor->user->name }} (ID: {{ $doctor->user->id }})
                                @else
                                    <span class="clinic-text-muted">(нет привязанного пользователя)</span>
                                @endif
                            </td>
                            <td>{{ $doctor->specialization->name }}</td>
                            <td>{{ $doctor->experience_years }} лет</td>
                            <td>{{ $doctor->category ?? '-' }}</td>
                            <td>{{ $doctor->base_price }} ₽</td>
                            <td class="clinic-table-actions">
                                <a href="{{ route('admin.doctors.edit', $doctor) }}" class="clinic-link">Редактировать</a>
                                <form action="{{ route('admin.doctors.destroy', $doctor) }}" method="POST" class="clinic-inline-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="clinic-link clinic-link--danger" onclick="return confirm('Удалить врача?')">Удалить</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
