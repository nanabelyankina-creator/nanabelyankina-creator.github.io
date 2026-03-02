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
                <input type="text" name="snils" id="snils" value="{{ old('snils') }}" placeholder="XXX-XXX-XXX XX" maxlength="14" required>
            </div>

            <div class="form-group">
                <label>Телефон *</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="+7 (999) 123-45-67" required>
            </div>

            <div class="form-group">
                <label>Почта</label>
                <input type="email" name="email" value="{{ old('email') }}">
            </div>

            <div class="form-group">
                <label>Пароль *</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <label>Повтор пароля *</label>
                <input type="password" name="password_confirmation" required>
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
    var snils = document.getElementById('snils');
    if (snils) {
        snils.addEventListener('input', function() {
            var v = this.value.replace(/\D/g, '');
            if (v.length > 11) v = v.slice(0, 11);
            var f = '';
            if (v.length > 0) f += v.slice(0, 3);
            if (v.length > 3) f += '-' + v.slice(3, 6);
            if (v.length > 6) f += '-' + v.slice(6, 9);
            if (v.length > 9) f += ' ' + v.slice(9, 11);
            this.value = f;
        });
    }
    var phone = document.getElementById('phone');
    if (phone) {
        phone.addEventListener('input', function() {
            var v = this.value.replace(/\D/g, '');
            if (v[0] === '8') v = '7' + v.slice(1);
            if (v[0] !== '7') v = '7' + v;
            if (v.length > 11) v = v.slice(0, 11);
            var f = '+7';
            if (v.length > 1) f += ' (' + v.slice(1, 4);
            if (v.length > 4) f += ') ' + v.slice(4, 7);
            if (v.length > 7) f += '-' + v.slice(7, 9);
            if (v.length > 9) f += '-' + v.slice(9, 11);
            this.value = f;
        });
    }
})();
</script>
@endpush
@endsection
