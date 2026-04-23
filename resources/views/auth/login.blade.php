@extends('layouts.clinic')

@section('content')
<section class="clinic-page">
    <div class="clinic-container">
        <h1>Вход</h1>

        @if ($errors->any())
            <div class="clinic-alert-error">
                <ul style="margin:0;padding-left:1.25rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="clinic-form" id="login-form">
            @csrf
            @if(request('redirect'))
                <input type="hidden" name="redirect" value="{{ request('redirect') }}">
            @endif
            <input type="hidden" name="login_as" id="login-as-input" value="{{ old('login_as', 'client') }}">

            {{-- Блок для входа клиента: телефон / СНИЛС --}}
            <div id="login-client-fields" class="login-as-fields">
                <div class="form-group">
                    <label>Способ входа:</label>
                    <div class="login-type-switch" role="radiogroup" aria-label="Способ входа">
                        <label class="login-type-switch__item">
                            <input type="radio" name="login_type" value="phone" {{ old('login_type', 'phone') === 'phone' ? 'checked' : '' }}>
                            <span>По телефону</span>
                        </label>
                        <label class="login-type-switch__item">
                            <input type="radio" name="login_type" value="snils" {{ old('login_type') === 'snils' ? 'checked' : '' }}>
                            <span>По СНИЛС</span>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Телефон или СНИЛС *</label>
                    <input type="text" name="identifier" id="identifier" value="{{ old('identifier') }}" data-mask="phone-or-snils">
                </div>
            </div>

            {{-- Блок для входа врача/админа: email --}}
            <div id="login-staff-fields" class="login-as-fields" style="display:none;">
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" id="login-email" value="{{ old('email') }}" autocomplete="email">
                </div>
            </div>

            <div class="form-group">
                <label>Пароль *</label>
                <input type="password" name="password" required data-password>
            </div>

            <div class="form-group">
                <label style="display:flex;align-items:center;gap:0.5rem;">
                    <input type="checkbox" data-toggle-password>
                    Показать пароль
                </label>
            </div>

            <div class="form-group">
                <label style="display:flex;align-items:center;gap:0.5rem;">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    Запомнить меня
                </label>
            </div>

            <button type="submit" class="btn-primary">Войти</button>

            <p id="you-doctor-wrap" style="margin-top:1rem;">
                <a href="#" id="you-doctor-link" style="color:var(--clinic-gray);font-size:0.95rem;">Вы врач?</a>
            </p>
            <p id="back-to-client-wrap" style="margin-top:1rem;display:none;">
                <a href="#" id="back-to-client-link" style="color:var(--clinic-primary);font-size:0.95rem;">← Вход для клиента</a>
            </p>
        </form>

        <script>
        (function() {
            var form = document.getElementById('login-form');
            var loginAsInput = document.getElementById('login-as-input');
            var clientFields = document.getElementById('login-client-fields');
            var staffFields = document.getElementById('login-staff-fields');
            var identifier = document.getElementById('identifier');
            var emailInput = document.getElementById('login-email');
            var youDoctorWrap = document.getElementById('you-doctor-wrap');
            var backToClientWrap = document.getElementById('back-to-client-wrap');
            var youDoctorLink = document.getElementById('you-doctor-link');
            var backToClientLink = document.getElementById('back-to-client-link');
            var isStaff = '{{ old('login_as', 'client') }}' === 'staff';

            function showStaffForm() {
                isStaff = true;
                loginAsInput.value = 'staff';
                clientFields.style.display = 'none';
                staffFields.style.display = 'block';
                if (youDoctorWrap) youDoctorWrap.style.display = 'none';
                if (backToClientWrap) backToClientWrap.style.display = 'block';
                if (identifier) identifier.required = false;
                if (emailInput) emailInput.required = true;
                if (document.getElementById('register-hint')) document.getElementById('register-hint').style.display = 'none';
            }

            function showClientForm() {
                isStaff = false;
                loginAsInput.value = 'client';
                clientFields.style.display = 'block';
                staffFields.style.display = 'none';
                if (youDoctorWrap) youDoctorWrap.style.display = 'block';
                if (backToClientWrap) backToClientWrap.style.display = 'none';
                if (identifier) identifier.required = true;
                if (emailInput) emailInput.required = false;
                if (document.getElementById('register-hint')) document.getElementById('register-hint').style.display = 'block';
            }

            if (youDoctorLink) youDoctorLink.addEventListener('click', function(e) { e.preventDefault(); showStaffForm(); });
            if (backToClientLink) backToClientLink.addEventListener('click', function(e) { e.preventDefault(); showClientForm(); });

            if (isStaff) showStaffForm(); else showClientForm();

            function syncLoginTypeButtons() {
                var selected = form.querySelector('input[name="login_type"]:checked');
                var selectedValue = selected ? selected.value : 'phone';
                form.querySelectorAll('.login-type-switch__item').forEach(function(item) {
                    var radio = item.querySelector('input[name="login_type"]');
                    item.classList.toggle('is-active', !!radio && radio.value === selectedValue);
                });
            }

            form.querySelectorAll('input[name="login_type"]').forEach(function(radio) {
                radio.addEventListener('change', syncLoginTypeButtons);
            });
            syncLoginTypeButtons();

            // show/hide password
            var toggle = form?.querySelector('[data-toggle-password]');
            var pw = form?.querySelector('input[name="password"][data-password]');
            if (toggle && pw) {
                toggle.addEventListener('change', function() {
                    pw.type = this.checked ? 'text' : 'password';
                });
            }
        })();
        </script>

        <p id="register-hint" style="margin-top:1.5rem;">Нет аккаунта? <a href="{{ route('register.show') }}" style="color:var(--clinic-primary);">Зарегистрироваться</a></p>
    </div>
</section>
@endsection
