@extends('layouts.clinic')

@section('content')
<div class="clinic-admin-layout">
    <aside class="clinic-admin-sidebar">
        <h2 class="clinic-admin-title">Админ-панель</h2>
        <nav class="clinic-admin-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">Обзор</a>
            <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'is-active' : '' }}">Пользователи</a>
            <a href="{{ route('admin.specializations.index') }}" class="{{ request()->routeIs('admin.specializations.*') ? 'is-active' : '' }}">Специализации</a>
            <a href="{{ route('admin.doctors.index') }}" class="{{ request()->routeIs('admin.doctors.*') ? 'is-active' : '' }}">Врачи</a>
            <a href="{{ route('admin.analyses.index') }}" class="{{ request()->routeIs('admin.analyses.*') ? 'is-active' : '' }}">Анализы</a>
            <a href="{{ route('admin.promotions.index') }}" class="{{ request()->routeIs('admin.promotions.*') ? 'is-active' : '' }}">Акции</a>
            <a href="{{ route('admin.faqs.index') }}" class="{{ request()->routeIs('admin.faqs.*') ? 'is-active' : '' }}">FAQ</a>
            <a href="{{ route('admin.chat.index') }}" class="{{ request()->routeIs('admin.chat.*') ? 'is-active' : '' }}">Чат</a>
        </nav>
        <form action="{{ route('logout') }}" method="POST" class="clinic-admin-logout">
            @csrf
            <button type="submit" class="clinic-btn clinic-btn-secondary clinic-logout-btn">Выйти</button>
        </form>
    </aside>

    <section class="clinic-admin-content">
        @yield('admin-content')
    </section>
</div>
@endsection

