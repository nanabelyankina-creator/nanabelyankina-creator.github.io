@extends('layouts.admin')

@section('admin-content')
    <div class="clinic-admin-header">
        <h1>Страницы</h1>
        <p class="clinic-admin-subtitle">Управление статическими страницами сайта.</p>
        <a href="{{ route('admin.pages.create') }}" class="clinic-btn clinic-btn--primary">Добавить страницу</a>
    </div>

    @if(session('success'))
        <div class="clinic-alert clinic-alert--success">{{ session('success') }}</div>
    @endif

    @if($pages->isEmpty())
        <p class="clinic-empty">Страницы не добавлены.</p>
    @else
        <div class="clinic-table-wrapper">
            <table class="clinic-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Slug</th>
                        <th>Заголовок</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pages as $page)
                        <tr>
                            <td>{{ $page->id }}</td>
                            <td><code>{{ $page->slug }}</code></td>
                            <td>{{ $page->title }}</td>
                            <td class="clinic-table-actions">
                                <a href="{{ route('page.show', $page->slug) }}" target="_blank" class="clinic-link">Открыть</a>
                                <a href="{{ route('admin.pages.edit', $page) }}" class="clinic-link">Редактировать</a>
                                <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="clinic-inline-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="clinic-link clinic-link--danger" onclick="return confirm('Удалить?')">Удалить</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection

