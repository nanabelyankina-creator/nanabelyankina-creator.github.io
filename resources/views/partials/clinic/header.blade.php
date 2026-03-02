<header class="clinic-header">
    <div class="clinic-topbar">
        <div class="clinic-container">
            <div class="clinic-topbar-inner">
                <div class="clinic-topbar-info">
                    <span>📍 {{ config('clinic.address', 'г. Москва') }}</span>
                    <span>📞 {{ config('clinic.phone', '+7 (999) 123-45-67') }}</span>
                </div>
                <div class="clinic-topbar-auth">
                    @auth
                        @if(auth()->user()->isPatient())
                            <a href="{{ route('profile') }}">Личный кабинет</a>
                            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" style="background:none;border:none;color:var(--clinic-primary);cursor:pointer;font:inherit;">Выйти</button>
                            </form>
                        @elseif(auth()->user()->isDoctor())
                            <a href="{{ route('doctor.dashboard') }}">Кабинет врача</a>
                        @elseif(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}">Админ-панель</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}">Вход</a>
                        <span> / </span>
                        <a href="{{ route('register.show') }}">Регистрация</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <nav class="clinic-nav">
        <div class="clinic-container">
            <div class="clinic-nav-inner">
                <a href="{{ route('home') }}" class="clinic-logo">
                    <img src="{{ asset('images/logo-icon.svg') }}" alt="Логотип" class="clinic-logo-icon">
                    <span class="clinic-logo-text">{{ config('app.name', 'Клиника') }}</span>
                </a>

                <div class="clinic-nav-links">
                    <a href="{{ route('doctors.index') }}">Врачи</a>
                    <a href="{{ route('promotions.index') }}">Акции</a>
                    <a href="{{ route('faq.index') }}">Вопросы</a>
                    @if(in_array('about', $layoutPages ?? []))<a href="{{ route('page.show', 'about') }}">О нас</a>@endif
                    @if(in_array('contacts', $layoutPages ?? []))<a href="{{ route('page.show', 'contacts') }}">Контакты</a>@endif
                    <a href="{{ auth()->check() && auth()->user()->isPatient() ? route('client.chat.index') : route('login.show', ['redirect' => urlencode(route('client.chat.index'))]) }}">Связаться по чату</a>
                    <a href="{{ auth()->check() && auth()->user()->isPatient() ? route('client.book.specialization') : route('guest.book.patient') }}" class="clinic-nav-btn">Записаться к врачу</a>
                </div>

                <button type="button" class="clinic-hamburger" id="clinic-hamburger" aria-label="Меню">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>

            <div class="clinic-mobile-menu" id="clinic-mobile-menu">
                <a href="{{ route('doctors.index') }}">Врачи</a>
                <a href="{{ route('promotions.index') }}">Акции</a>
                <a href="{{ route('faq.index') }}">Вопросы</a>
                @if(in_array('about', $layoutPages ?? []))<a href="{{ route('page.show', 'about') }}">О нас</a>@endif
                @if(in_array('contacts', $layoutPages ?? []))<a href="{{ route('page.show', 'contacts') }}">Контакты</a>@endif
                <a href="{{ auth()->check() && auth()->user()->isPatient() ? route('client.chat.index') : route('login.show', ['redirect' => urlencode(route('client.chat.index'))]) }}">Связаться по чату</a>
                <a href="{{ auth()->check() && auth()->user()->isPatient() ? route('client.book.specialization') : route('guest.book.patient') }}" class="clinic-nav-btn" style="text-align:center;">Записаться к врачу</a>
            </div>
        </div>
    </nav>
</header>

<script>
document.getElementById('clinic-hamburger')?.addEventListener('click', function() {
    document.getElementById('clinic-mobile-menu')?.classList.toggle('is-open');
});
</script>
