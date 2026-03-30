@extends('layouts.admin')

@section('admin-content')
    <div class="clinic-admin-header">
        <h1>Админ-панель</h1>
        <p class="clinic-admin-subtitle">Управление контентом и пользователями клиники.</p>
    </div>

    <div class="clinic-admin-grid">
        <a href="{{ route('admin.users.index') }}" class="clinic-admin-card">
            <h2>Пользователи</h2>
            <p>Просмотр, блокировка и фильтрация пользователей.</p>
        </a>
        <a href="{{ route('admin.specializations.index') }}" class="clinic-admin-card">
            <h2>Специализации</h2>
            <p>Управление списком медицинских специализаций.</p>
        </a>
        <a href="{{ route('admin.doctors.index') }}" class="clinic-admin-card">
            <h2>Врачи</h2>
            <p>Добавление и редактирование карточек врачей.</p>
        </a>
        <a href="{{ route('admin.analyses.index') }}" class="clinic-admin-card">
            <h2>Анализы</h2>
            <p>Учет лабораторных анализов пациентов.</p>
        </a>
        <a href="{{ route('admin.promotions.index') }}" class="clinic-admin-card">
            <h2>Акции</h2>
            <p>Настройка акций и спецпредложений.</p>
        </a>
        <a href="{{ route('admin.faqs.index') }}" class="clinic-admin-card">
            <h2>FAQ</h2>
            <p>Ответы на популярные вопросы пациентов.</p>
        </a>
        <a href="{{ route('admin.chat.index') }}" class="clinic-admin-card">
            <h2>Чат</h2>
            <p>Переписка с пациентами в режиме реального времени.</p>
        </a>
    </div>
@endsection
