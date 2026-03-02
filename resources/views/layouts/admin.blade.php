@extends('layouts.clinic')

@section('content')
<div class="clinic-admin-layout">
    <aside class="clinic-admin-sidebar">
        <h2 class="clinic-admin-title">Админ-панель</h2>
        <nav class="clinic-admin-nav">
            <a href="{{ route('admin.dashboard') }}">Обзор</a>
            <a href="{{ route('admin.users.index') }}">Пользователи</a>
            <a href="{{ route('admin.specializations.index') }}">Специализации</a>
            <a href="{{ route('admin.doctors.index') }}">Врачи</a>
            <a href="{{ route('admin.analyses.index') }}">Анализы</a>
            <a href="{{ route('admin.promotions.index') }}">Акции</a>
            <a href="{{ route('admin.faqs.index') }}">FAQ</a>
            <a href="{{ route('admin.pages.index') }}">Страницы</a>
            <a href="{{ route('admin.chat.index') }}">Чат</a>
        </nav>
        <form action="{{ route('logout') }}" method="POST" class="clinic-admin-logout">
            @csrf
            <button type="submit">Выйти</button>
        </form>
    </aside>

    <section class="clinic-admin-content">
        @yield('admin-content')
    </section>
</div>
@endsection

