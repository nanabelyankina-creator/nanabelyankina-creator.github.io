@extends('layouts.clinic')

@section('content')
<section class="clinic-page">
    <div class="clinic-container">
        <h1>Ответы на вопросы</h1>

        @if($faqs->isEmpty())
            <p>Пока нет вопросов.</p>
        @else
            <div class="clinic-faq-list">
                @foreach($faqs as $faq)
                    <div class="clinic-faq-item">
                        <button
                            type="button"
                            class="clinic-faq-question"
                            aria-expanded="false"
                        >
                            <h3 class="clinic-faq-question-text">{{ $faq->question }}</h3>
                            <span class="clinic-faq-caret" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M7 10l5 5 5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </button>

                        <div class="clinic-faq-answer">
                            {!! nl2br(e($faq->answer)) !!}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
(function() {
    document.querySelectorAll('.clinic-faq-item').forEach(function(item) {
        const btn = item.querySelector('.clinic-faq-question');
        const answer = item.querySelector('.clinic-faq-answer');
        if (!btn || !answer) return;

        function closeAnswer() {
            answer.style.height = answer.scrollHeight + 'px';
            answer.offsetHeight; // force reflow
            answer.style.height = '0px';
            item.classList.remove('is-open');
            btn.setAttribute('aria-expanded', 'false');
        }

        function openAnswer() {
            item.classList.add('is-open');
            btn.setAttribute('aria-expanded', 'true');
            answer.style.height = answer.scrollHeight + 'px';
            const onEnd = function(e) {
                if (e.propertyName !== 'height') return;
                answer.style.height = 'auto';
                answer.removeEventListener('transitionend', onEnd);
            };
            answer.addEventListener('transitionend', onEnd);
        }

        btn.addEventListener('click', function() {
            const isOpen = item.classList.contains('is-open');
            if (isOpen) closeAnswer();
            else openAnswer();
        });

        // ensure initial state is closed
        answer.style.height = '0px';
        btn.setAttribute('aria-expanded', 'false');
    });
})();
</script>
@endpush
