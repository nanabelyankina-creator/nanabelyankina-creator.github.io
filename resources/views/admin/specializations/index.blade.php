@extends('layouts.admin')

@section('admin-content')
    <div class="clinic-admin-header">
        <h1>Специализации</h1>
        <p class="clinic-admin-subtitle">Управление списком медицинских специализаций.</p>
        <a href="{{ route('admin.specializations.create') }}" class="clinic-btn clinic-btn--primary">Добавить специализацию</a>
    </div>

    @if(session('success'))
        <div class="clinic-alert clinic-alert--success">{{ session('success') }}</div>
    @endif

    @if($specializations->isEmpty())
        <p class="clinic-empty">Специализации не добавлены.</p>
    @else
        <div class="clinic-table-wrapper">
            <table class="clinic-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Описание</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($specializations as $spec)
                        <tr>
                            <td>{{ $spec->id }}</td>
                            <td>{{ $spec->name }}</td>
                            <td>{{ $spec->description }}</td>
                            <td class="clinic-table-actions">
                                <a href="{{ route('admin.specializations.edit', $spec) }}" class="clinic-link">Редактировать</a>
                                <form action="{{ route('admin.specializations.destroy', $spec) }}" method="POST" class="clinic-inline-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="clinic-link clinic-link--danger" onclick="return confirm('Удалить специализацию?')">Удалить</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
