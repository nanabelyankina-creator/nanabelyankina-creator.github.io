@extends('layouts.clinic')

@section('content')
<section class="clinic-page">
    <div class="clinic-container">
        <h1>Акции</h1>
        <p><a href="{{ route('home') }}" style="color:var(--clinic-primary);">← На главную</a></p>

        @if($promotions->isEmpty())
            <p>Акций пока нет.</p>
        @else
            <div style="display:flex;flex-direction:column;gap:1.5rem;margin-top:1.5rem;">
                @foreach($promotions as $promo)
                    <article style="padding:1.5rem;border:1px solid var(--clinic-gray-border);border-radius:var(--clinic-radius);">
                        <h3 style="margin:0 0 0.5rem;">{{ $promo->title }}</h3>
                        @if($promo->short_description)
                            <p style="margin:0 0 0.5rem;color:var(--clinic-gray);">{{ $promo->short_description }}</p>
                        @endif
                        @if($promo->content)
                            <p style="margin:0;">{!! nl2br(e($promo->content)) !!}</p>
                        @endif
                        @if($promo->starts_at || $promo->ends_at)
                            <p style="margin:0.5rem 0 0;font-size:0.9rem;color:var(--clinic-gray);">
                                @if($promo->starts_at) с {{ $promo->starts_at->format('d.m.Y') }} @endif
                                @if($promo->ends_at) по {{ $promo->ends_at->format('d.m.Y') }} @endif
                            </p>
                        @endif
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
