@extends('layouts.clinic')

@section('content')
<section class="clinic-page clinic-profile">
    <div class="clinic-container">
        <h1>Личный кабинет</h1>

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

        {{-- Блок: Мои записи (развёрнут) --}}
        <div class="profile-block profile-block-expanded">
            <button type="button" class="profile-block-toggle" data-block="appointments" aria-expanded="true">
                <h2>Мои записи</h2>
                <span class="profile-block-arrow">▼</span>
            </button>
            <div class="profile-block-content" id="block-appointments">
                <p><a href="{{ route('client.appointments.index') }}">Перейти к полному списку записей →</a></p>
                @php
                    $activePreview = $patient->appointments()
                        ->whereIn('status', ['scheduled', 'in_progress'])
                        ->where('scheduled_at', '>=', now()->startOfDay())
                        ->orderBy('scheduled_at')
                        ->with(['doctor', 'specialization'])
                        ->limit(3)
                        ->get();
                @endphp
                @if($activePreview->isEmpty())
                    <p class="profile-empty">У вас нет активных записей.</p>
                @else
                    <ul class="profile-list">
                        @foreach($activePreview as $a)
                            <li>
                                <strong>Ваша запись на {{ $a->scheduled_at->format('d.m.Y H:i') }}</strong><br>
                                Специализация: {{ $a->specialization->name }} · Врач: {{ $a->doctor->last_name }} {{ $a->doctor->first_name }}<br>
                                Стоимость: {{ $a->price }} ₽
                            </li>
                        @endforeach
                    </ul>
                @endif
                <p><a href="{{ route('client.book.specialization') }}" class="clinic-btn">Записаться к врачу</a></p>
            </div>
        </div>

        {{-- Блок: Личные данные (свёрнут) --}}
        <div class="profile-block">
            <button type="button" class="profile-block-toggle" data-block="personal" aria-expanded="false">
                <h2>Личные данные</h2>
                <span class="profile-block-arrow">▶</span>
            </button>
            <div class="profile-block-content profile-block-collapsed" id="block-personal">
                <div class="profile-field">
                    <span class="profile-field-label">Фамилия</span>
                    <span class="profile-field-value" data-field="last_name">{{ $patient->last_name }}</span>
                    <button type="button" class="profile-edit-btn" data-field="last_name" title="Редактировать">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </button>
                </div>
                <div class="profile-field">
                    <span class="profile-field-label">Имя</span>
                    <span class="profile-field-value" data-field="first_name">{{ $patient->first_name }}</span>
                    <button type="button" class="profile-edit-btn" data-field="first_name" title="Редактировать">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </button>
                </div>
                <div class="profile-field">
                    <span class="profile-field-label">Отчество</span>
                    <span class="profile-field-value" data-field="middle_name">{{ $patient->middle_name ?? '—' }}</span>
                    <button type="button" class="profile-edit-btn" data-field="middle_name" title="Редактировать">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </button>
                </div>
                <div class="profile-field">
                    <span class="profile-field-label">СНИЛС</span>
                    <span class="profile-field-value profile-field-readonly">{{ \App\Services\SnilsValidator::format($patient->snils) }}</span>
                    <small class="profile-field-hint">Только для чтения</small>
                </div>
                <div class="profile-field">
                    <span class="profile-field-label">Телефон</span>
                    <span class="profile-field-value" data-field="phone">{{ $user->phone }}</span>
                    <button type="button" class="profile-edit-btn" data-field="phone" title="Редактировать">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </button>
                </div>
                <div class="profile-field">
                    <span class="profile-field-label">Почта</span>
                    <span class="profile-field-value" data-field="email">{{ $user->email ?? '—' }}</span>
                    <button type="button" class="profile-edit-btn" data-field="email" title="Редактировать">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </button>
                </div>
                <p><a href="{{ route('profile.password.show') }}" class="clinic-btn clinic-btn-secondary">Сменить пароль</a></p>
            </div>
        </div>

        {{-- Блок: Анализы (свёрнут) --}}
        <div class="profile-block">
            <button type="button" class="profile-block-toggle" data-block="analyses" aria-expanded="false">
                <h2>Анализы</h2>
                <span class="profile-block-arrow">▶</span>
            </button>
            <div class="profile-block-content profile-block-collapsed" id="block-analyses">
                <p><a href="{{ route('client.analyses.index') }}">Перейти к моим анализам →</a></p>
                @php $analysesPreview = $patient->analyses()->latest()->limit(2)->get(); @endphp
                @if($analysesPreview->isEmpty())
                    <p class="profile-empty">У вас еще нет анализов, обратитесь к врачу.</p>
                @else
                    <ul class="profile-list">
                        @foreach($analysesPreview as $a)
                            <li>{{ $a->type }} — {{ $a->taken_at?->format('d.m.Y') ?? 'н/д' }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <p style="margin-top:2rem;">
            <a href="{{ route('client.chat.index') }}">Связаться по чату</a> ·
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" style="background:none;border:none;color:var(--clinic-primary);cursor:pointer;font:inherit;padding:0;">Выйти</button>
            </form>
        </p>
    </div>
</section>

<form id="profile-inline-form" action="{{ route('profile.update') }}" method="POST" style="display:none;">
    @csrf
</form>

@push('scripts')
<script>
(function() {
    // Сворачивание блоков
    document.querySelectorAll('.profile-block-toggle').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var blockId = this.getAttribute('data-block');
            var content = document.getElementById('block-' + blockId);
            var arrow = this.querySelector('.profile-block-arrow');
            var expanded = content.classList.toggle('profile-block-collapsed');
            arrow.textContent = expanded ? '▶' : '▼';
            this.setAttribute('aria-expanded', !expanded);
        });
    });

    // Inline редактирование
    document.querySelectorAll('.profile-edit-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var field = this.getAttribute('data-field');
            var valueEl = document.querySelector('.profile-field-value[data-field="' + field + '"]');
            if (!valueEl) return;
            var val = valueEl.textContent.trim();
            if (val === '—') val = '';

            var input = document.createElement('input');
            input.type = field === 'email' ? 'email' : 'text';
            input.name = field;
            input.value = val;
            input.className = 'profile-inline-input';

            var saveBtn = document.createElement('button');
            saveBtn.type = 'button';
            saveBtn.textContent = 'Сохранить';
            saveBtn.className = 'clinic-btn clinic-btn-sm';

            var cancelBtn = document.createElement('button');
            cancelBtn.type = 'button';
            cancelBtn.textContent = 'Отмена';
            cancelBtn.className = 'clinic-btn clinic-btn-secondary clinic-btn-sm';

            var wrap = document.createElement('div');
            wrap.className = 'profile-inline-edit';
            wrap.appendChild(input);
            wrap.appendChild(saveBtn);
            wrap.appendChild(cancelBtn);

            valueEl.style.display = 'none';
            btn.style.display = 'none';
            valueEl.parentNode.insertBefore(wrap, valueEl.nextSibling);

            input.focus();

            function cleanup() {
                wrap.remove();
                valueEl.style.display = '';
                btn.style.display = '';
            }

            saveBtn.addEventListener('click', function() {
                var form = document.getElementById('profile-inline-form');
                form.innerHTML = '';
                var csrf = document.querySelector('input[name="_token"]');
                if (csrf) form.appendChild(csrf.cloneNode(true));
                var inp = document.createElement('input');
                inp.type = 'hidden';
                inp.name = field;
                inp.value = input.value;
                form.appendChild(inp);
                form.submit();
            });

            cancelBtn.addEventListener('click', function() {
                cleanup();
            });

            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') saveBtn.click();
                if (e.key === 'Escape') cancelBtn.click();
            });
        });
    });
})();
</script>
@endpush
@endsection
