@extends('layouts.admin')

@section('admin-content')
    <div class="clinic-admin-header">
        <h1>FAQ</h1>
        <p class="clinic-admin-subtitle">Ответы на часто задаваемые вопросы пациентов.</p>
        <a href="{{ route('admin.faqs.create') }}" class="clinic-btn clinic-btn--primary">Добавить вопрос</a>
    </div>

    @if(session('success'))
        <div class="clinic-alert clinic-alert--success">{{ session('success') }}</div>
    @endif

    @if($faqs->isEmpty())
        <p class="clinic-empty">Вопросы не добавлены.</p>
    @else
        <div class="clinic-table-wrapper">
            <table class="clinic-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Вопрос</th>
                        <th>Активен</th>
                        <th>Порядок</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($faqs as $faq)
                        <tr>
                            <td>{{ $faq->id }}</td>
                            <td>{{ Str::limit($faq->question, 80) }}</td>
                            <td>
                                <span class="clinic-badge {{ $faq->is_active ? 'clinic-badge--success' : 'clinic-badge--muted' }}">
                                    {{ $faq->is_active ? 'Да' : 'Нет' }}
                                </span>
                            </td>
                            <td>{{ $faq->sort_order }}</td>
                            <td class="clinic-table-actions">
                                <a href="{{ route('admin.faqs.edit', $faq) }}" class="clinic-link">Редактировать</a>
                                <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" class="clinic-inline-form">
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

