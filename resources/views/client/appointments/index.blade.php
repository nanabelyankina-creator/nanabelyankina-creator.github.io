@extends('layouts.clinic')

@section('content')
<section class="clinic-page">
    <div class="clinic-container">
        <h1>Мои записи</h1>
        <p><a href="{{ route('profile') }}">← Вернуться в профиль</a></p>

        @if(session('success'))
            <div class="clinic-alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="clinic-alert-error">
                <ul style="margin:0;padding-left:1.25rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="appointments-tabs">
            <button type="button" class="appointments-tab active" data-tab="active">Активные</button>
            <button type="button" class="appointments-tab" data-tab="completed">Завершённые</button>
        </div>

        <div id="tab-active" class="appointments-tab-content">
            <h2>Активные записи</h2>
            @if($activeAppointments->isEmpty())
                <p>У вас нет активных записей.</p>
            @else
                <ul class="appointments-list">
                    @foreach($activeAppointments as $appointment)
                        <li>
                            <strong>Ваша запись на {{ $appointment->scheduled_at->format('d.m.Y H:i') }}</strong><br>
                            Специализация: {{ $appointment->specialization->name }}<br>
                            Врач: {{ $appointment->doctor->last_name }} {{ $appointment->doctor->first_name }} {{ $appointment->doctor->middle_name }}<br>
                            Стоимость приёма: {{ $appointment->price }} ₽
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div id="tab-completed" class="appointments-tab-content" style="display:none;">
            <h2>Завершённые приёмы</h2>
            @if($completedAppointments->isEmpty())
                <p>У вас ещё нет завершённых приёмов.</p>
            @else
                <ul class="appointments-list">
                    @foreach($completedAppointments as $appointment)
                        <li>
                            <strong>Приём от {{ $appointment->scheduled_at->format('d.m.Y H:i') }}</strong><br>
                            Специализация: {{ $appointment->specialization->name }}<br>
                            Врач: {{ $appointment->doctor->last_name }} {{ $appointment->doctor->first_name }} {{ $appointment->doctor->middle_name }}<br>
                            Стоимость приёма: {{ $appointment->price }} ₽<br>
                            @if($appointment->review)
                                <span class="review-done">★ Отзыв оставлен ({{ $appointment->review->rating }}/5)</span>
                            @else
                                <button type="button" class="clinic-btn clinic-btn-sm" data-review-appointment="{{ $appointment->id }}" data-doctor="{{ $appointment->doctor->last_name }} {{ $appointment->doctor->first_name }}">Оставить отзыв</button>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</section>

{{-- Модалка отзыва --}}
<div id="review-modal" class="modal" style="display:none;">
    <div class="modal-content">
        <h3>Оставить отзыв</h3>
        <p id="review-doctor-name"></p>
        <form action="{{ route('client.reviews.store') }}" method="POST">
            @csrf
            <input type="hidden" name="appointment_id" id="review-appointment-id">
            <div class="form-group">
                <label>Оценка *</label>
                <select name="rating" required>
                    <option value="5">5 — Отлично</option>
                    <option value="4">4 — Хорошо</option>
                    <option value="3">3 — Удовлетворительно</option>
                    <option value="2">2 — Плохо</option>
                    <option value="1">1 — Очень плохо</option>
                </select>
            </div>
            <div class="form-group">
                <label>Комментарий (необязательно)</label>
                <textarea name="text" rows="3"></textarea>
            </div>
            <button type="submit" class="clinic-btn btn-primary">Отправить отзыв</button>
            <button type="button" class="clinic-btn clinic-btn-secondary modal-close">Отмена</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    document.querySelectorAll('.appointments-tab').forEach(function(tab) {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.appointments-tab').forEach(function(t) { t.classList.remove('active'); });
            document.querySelectorAll('.appointments-tab-content').forEach(function(c) { c.style.display = 'none'; });
            this.classList.add('active');
            document.getElementById('tab-' + this.getAttribute('data-tab')).style.display = 'block';
        });
    });
    document.querySelectorAll('[data-review-appointment]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('review-appointment-id').value = this.getAttribute('data-review-appointment');
            document.getElementById('review-doctor-name').textContent = 'Врач: ' + this.getAttribute('data-doctor');
            document.getElementById('review-modal').style.display = 'flex';
        });
    });
    document.querySelector('.modal-close')?.addEventListener('click', function() {
        document.getElementById('review-modal').style.display = 'none';
    });
})();
</script>
@endpush
