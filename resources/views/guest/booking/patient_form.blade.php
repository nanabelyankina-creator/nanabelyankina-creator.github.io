@extends('layouts.clinic')

@section('content')
<section class="clinic-page">
    <div class="clinic-container">
        <h1>Записаться к врачу без регистрации</h1>
        <p class="clinic-form-intro">Введите данные пациента. После записи вы сможете зарегистрироваться и видеть историю в личном кабинете.</p>

        @if ($errors->any())
            <div class="clinic-alert-error">
                <ul style="margin:0;padding-left:1.25rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('guest.book.patient.store') }}" method="POST" class="clinic-form">
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
                <small class="form-hint">Только цифры, автоформат</small>
            </div>

            <div class="form-group">
                <label>Телефон *</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="+7 (999) 123-45-67" required data-mask="phone-ru">
                <small class="form-hint">Российский номер</small>
            </div>

            <div class="form-group">
                <label class="form-checkbox">
                    <input type="checkbox" name="agree" value="1" {{ old('agree') ? 'checked' : '' }} required>
                    <span>Согласие на обработку персональных данных *</span>
                </label>
            </div>

            <button type="submit" class="btn-primary">Далее</button>
        </form>
    </div>
</section>
@endsection
