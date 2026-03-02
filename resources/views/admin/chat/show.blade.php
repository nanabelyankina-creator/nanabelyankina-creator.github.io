@extends('layouts.admin')

@section('admin-content')
    <div class="clinic-admin-header">
        <h1>Чат с клиентом</h1>
        <p class="clinic-admin-subtitle">Переписка с пациентами в режиме реального времени.</p>
    </div>

    <div class="clinic-chat-layout">
        <aside class="clinic-chat-sidebar">
            <h3>Диалоги</h3>
            <ul class="clinic-chat-thread-list">
                @foreach($threads as $t)
                    <li>
                        <a href="{{ route('admin.chat.show', $t) }}" class="{{ $t->id === $thread->id ? 'is-active' : '' }}">
                            @if($t->patient && $t->patient->user)
                                {{ $t->patient->last_name }} {{ $t->patient->first_name }}
                            @else
                                Пациент #{{ $t->patient_id }}
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        </aside>

        <section class="clinic-chat-main">
            <header class="clinic-chat-header">
                <h3>
                    Клиент:
                    @if($thread->patient && $thread->patient->user)
                        {{ $thread->patient->last_name }} {{ $thread->patient->first_name }} {{ $thread->patient->middle_name }}
                        (user #{{ $thread->patient->user->id }})
                    @else
                        Пациент #{{ $thread->patient_id }}
                    @endif
                </h3>
            </header>

            <div class="clinic-chat-messages">
                @forelse($messages as $message)
                    @php
                        $isMe = $message->sender_id === $user->id;
                    @endphp

                    <div class="clinic-chat-message {{ $isMe ? 'clinic-chat-message--me' : 'clinic-chat-message--client' }}">
                        <div class="clinic-chat-bubble">
                            <strong class="clinic-chat-author">
                                {{ $isMe ? 'Вы' : ($message->sender->role === 'patient' ? 'Клиент' : $message->sender->name) }}
                            </strong>
                            <p class="clinic-chat-text">{{ $message->message }}</p>
                            <span class="clinic-chat-time">{{ $message->created_at->format('d.m.Y H:i') }}</span>
                        </div>
                    </div>
                @empty
                    <p class="clinic-empty">Сообщений ещё нет.</p>
                @endforelse
            </div>

            <form action="{{ route('admin.chat.send', $thread) }}" method="POST" class="clinic-chat-form">
                @csrf
                <textarea name="message" rows="3" placeholder="Сообщение клиенту..." required></textarea>
                <button type="submit" class="clinic-btn clinic-btn--primary">Отправить</button>
            </form>
        </section>
    </div>
@endsection
