@extends('layouts.clinic')

@section('content')
<section class="clinic-page">
    <div class="clinic-container">
        <h1>Регистрация</h1>

        @if ($errors->any())
            <div class="clinic-alert-error">
                <ul style="margin:0;padding-left:1.25rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="clinic-form">
            @csrf

            <div class="form-group">
                <label>Фамилия *</label>
                <input type="text" name="last_name" value="{{ old('last_name') }}" required>
            </div>

            <div class="form-group">
                <label>Имя *</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}" required>
            </div>

            <div class="form-group">
                <label>Отчество</label>
                <input type="text" name="middle_name" value="{{ old('middle_name') }}">
            </div>

            <div class="form-group">
                <label>СНИЛС *</label>
                <input type="text" name="snils" id="snils" value="{{ old('snils') }}" placeholder="XXX-XXX-XXX XX" maxlength="14" required data-mask="snils">
            </div>

            <div class="form-group">
                <label>Телефон *</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="+7 (999) 123-45-67" required data-mask="phone-ru">
            </div>

            <div class="form-group">
                <label>Почта</label>
                <input type="email" name="email" value="{{ old('email') }}">
            </div>

            <div class="form-group">
                <label>Пароль *</label>
                <input type="password" name="password" required data-password>
            </div>

            <div class="form-group">
                <label>Повтор пароля *</label>
                <input type="password" name="password_confirmation" required data-password>
            </div>

            <div class="form-group">
                <label style="display:flex;align-items:center;gap:0.5rem;">
                    <input type="checkbox" data-toggle-password>
                    Показать пароль
                </label>
            </div>

            <div class="form-group">
                <label style="display:flex;align-items:flex-start;gap:0.5rem;">
                    <input type="checkbox" name="agree" {{ old('agree') ? 'checked' : '' }} style="margin-top:0.25rem;">
                    Согласие на обработку персональных данных *
                </label>
            </div>

            <button type="submit" class="btn-primary">Зарегистрироваться</button>
        </form>

        <p style="margin-top:1.5rem;">Уже есть аккаунт? <a href="{{ route('login.show') }}" style="color:var(--clinic-primary);">Войти</a></p>
    </div>
</section>
@push('scripts')
<script>
(function() {
    var form = document.querySelector('form.clinic-form');
    var toggle = form?.querySelector('[data-toggle-password]');
    var pws = form ? Array.from(form.querySelectorAll('[data-password]')) : [];
    if (toggle && pws.length) {
        toggle.addEventListener('change', function() {
            pws.forEach(function(pw) {
                pw.type = toggle.checked ? 'text' : 'password';
            });
        });
    }
})();
</script>
@endpush
@endsection
