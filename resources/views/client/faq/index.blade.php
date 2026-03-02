@extends('layouts.clinic')

@section('content')
<section class="clinic-page">
    <div class="clinic-container">
        <h1>Ответы на вопросы</h1>
        <p><a href="{{ route('home') }}" style="color:var(--clinic-primary);">← На главную</a></p>

        @if($faqs->isEmpty())
            <p>Пока нет вопросов.</p>
        @else
            <div style="display:flex;flex-direction:column;gap:1rem;margin-top:1.5rem;">
                @foreach($faqs as $faq)
                    <div style="padding:1.25rem;border:1px solid var(--clinic-gray-border);border-radius:var(--clinic-radius);">
                        <h3 style="margin:0 0 0.5rem;">{{ $faq->question }}</h3>
                        <p style="margin:0;color:var(--clinic-gray);">{{ $faq->answer }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
