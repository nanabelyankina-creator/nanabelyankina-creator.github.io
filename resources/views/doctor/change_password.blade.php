@extends('layouts.clinic')

@section('content')
<section class="clinic-page">
    <div class="clinic-container">
        <h1>Смена пароля</h1>

        @if ($errors->any())
            <div class="clinic-alert-error">
                <ul style="margin:0;padding-left:1.25rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('doctor.profile.password.change') }}" method="POST" class="clinic-form">
            @csrf
            <div class="form-group">
                <label>Текущий пароль *</label>
                <input type="password" name="current_password" required>
            </div>
            <div class="form-group">
                <label>Новый пароль *</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label>Повтор нового пароля *</label>
                <input type="password" name="password_confirmation" required>
            </div>
            <button type="submit" class="btn-primary">Изменить пароль</button>
        </form>

        <p style="margin-top:1.5rem;"><a href="{{ route('doctor.profile') }}">← Вернуться в профиль</a></p>
    </div>
</section>
@endsection
