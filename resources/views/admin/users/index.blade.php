@extends('layouts.admin')

@section('admin-content')
    <div class="clinic-admin-header">
        <h1>Пользователи</h1>
        <p class="clinic-admin-subtitle">Список всех пользователей</p>
    </div>

    @if(session('success'))
        <div class="clinic-alert clinic-alert--success">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('admin.users.index') }}" class="clinic-filter">
        <div class="clinic-form-group">
            <label>Поиск (ФИО/почта/телефон):</label>
            <input type="text" name="q" value="{{ request('q') }}">
        </div>

        <div class="clinic-form-group">
            <label>Роль:</label>
            <select name="role">
                <option value="">Все</option>
                <option value="patient" {{ request('role') === 'patient' ? 'selected' : '' }}>Клиент</option>
                <option value="doctor" {{ request('role') === 'doctor' ? 'selected' : '' }}>Врач</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Админ</option>
            </select>
        </div>

        <button type="submit" class="clinic-btn clinic-btn--ghost">Фильтровать</button>
    </form>

    @if($users->isEmpty())
        <p class="clinic-empty">Пользователи не найдены.</p>
    @else
        <div class="clinic-table-wrapper">
            <table class="clinic-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Роль</th>
                        <th>Телефон</th>
                        <th>Почта</th>
                        <th>Блокировка</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->role }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="clinic-badge {{ $user->is_blocked ? 'clinic-badge--danger' : 'clinic-badge--success' }}">
                                    {{ $user->is_blocked ? 'Заблокирован' : 'Активен' }}
                                </span>
                            </td>
                            <td class="clinic-table-actions">
                                @if($user->is_blocked)
                                    <form action="{{ route('admin.users.unblock', $user) }}" method="POST" class="clinic-inline-form">
                                        @csrf
                                        <button type="submit" class="clinic-link">Разблокировать</button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.users.block', $user) }}" method="POST" class="clinic-inline-form">
                                        @csrf
                                        <button type="submit" class="clinic-link clinic-link--danger">Заблокировать</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $users->links('clinic-pagination') }}
    @endif
@endsection
