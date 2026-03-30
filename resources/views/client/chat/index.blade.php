@extends('layouts.clinic')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/client-chat.css') }}">
@endpush

@section('content')
    <div class="clinic-chat-page">
        <div class="clinic-chat-page__inner">
            <div class="clinic-chat-card">
                <div class="clinic-chat-card__header">
                    <h1>Чат с администратором</h1>
                </div>

                <div class="clinic-chat-card__messages">
                    @forelse($messages as $message)
                        @php
                            $isMe = $message->sender_id === $user->id;
                            $isAdmin = $message->sender->role === 'admin';
                            $author = $isMe ? 'Вы' : ($isAdmin ? 'Администратор' : $message->sender->name);
                        @endphp

                        <div class="clinic-chat-line {{ $isMe ? 'clinic-chat-line--me' : 'clinic-chat-line--other' }}">
                            <div class="clinic-chat-author">
                                {{ $author }}
                            </div>
                            <div class="clinic-chat-bubble">
                                <div class="clinic-chat-text">
                                    {{ $message->message }}
                                </div>
                                <div class="clinic-chat-time">
                                    {{ $message->created_at->format('d.m.Y H:i') }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="clinic-empty">
                            Сообщений ещё нет. Напишите нам, если у вас есть вопрос.
                        </p>
                    @endforelse
                </div>

                <form action="{{ route('client.chat.send') }}" method="POST" class="clinic-chat-card__form">
                    @csrf
                    <div class="clinic-chat-input-row">
                        <div class="clinic-chat-input-wrap">
                            <textarea
                                class="clinic-chat-textarea"
                                name="message"
                                rows="2"
                                placeholder="Ваш вопрос..."
                                required
                            ></textarea>
                        </div>
                        <button type="submit" class="clinic-btn clinic-btn--primary">
                            Отправить
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
(function() {
    const textarea = document.querySelector('.clinic-chat-textarea');
    if (!textarea) return;

    const MAX_HEIGHT_PX = 160;

    function autoResize() {
        textarea.style.height = 'auto';
        const scrollHeight = textarea.scrollHeight;
        const height = Math.min(scrollHeight, MAX_HEIGHT_PX);
        textarea.style.height = height + 'px';
        textarea.style.overflowY = scrollHeight > MAX_HEIGHT_PX ? 'auto' : 'hidden';
    }

    textarea.addEventListener('input', autoResize);
    textarea.addEventListener('change', autoResize);

    // Initial
    autoResize();
})();
</script>
@endpush