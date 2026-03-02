@extends('layouts.admin')

@section('admin-content')
    <div class="clinic-admin-header">
        <h1>Чаты с клиентами</h1>
        <p class="clinic-admin-subtitle">Список диалогов с пациентами.</p>
    </div>

    @if($threads->isEmpty())
        <p class="clinic-empty">Пока нет чатов.</p>
    @else
        <div class="clinic-list">
            @foreach($threads as $thread)
                <a href="{{ route('admin.chat.show', $thread) }}" class="clinic-list-item">
                    <div class="clinic-list-item-main">
                        <strong>
                            @if($thread->patient && $thread->patient->user)
                                {{ $thread->patient->last_name }} {{ $thread->patient->first_name }}
                                (user #{{ $thread->patient->user->id }})
                            @else
                                Пациент #{{ $thread->patient_id }}
                            @endif
                        </strong>
                        <span class="clinic-list-item-meta">
                            последнее обновление: {{ $thread->updated_at->format('d.m.Y H:i') }}
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
@endsection
