<footer class="clinic-footer">
    <div class="clinic-container">
        <div class="clinic-footer-inner">
            <p>© {{ date('Y') }} {{ config('app.name', 'Клиника') }}. Все права защищены.</p>
            <div class="clinic-footer-links">
                <a href="{{ route('home') }}">Главная</a>
                <a href="{{ route('doctors.index') }}">Врачи</a>
                <a href="{{ route('promotions.index') }}">Акции</a>
                <a href="{{ route('faq.index') }}">Вопросы и ответы</a>
                @if(in_array('about', $layoutPages ?? []))<a href="{{ route('page.show', 'about') }}">О компании</a>@endif
                @if(in_array('contacts', $layoutPages ?? []))<a href="{{ route('page.show', 'contacts') }}">Где нас найти</a>@endif
                <a href="{{ auth()->check() && auth()->user()->isPatient() ? route('client.book.specialization') : route('guest.book.patient') }}">Записаться к врачу</a>
            </div>
        </div>
    </div>
</footer>
