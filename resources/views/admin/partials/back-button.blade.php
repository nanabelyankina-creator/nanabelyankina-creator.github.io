@php
    $fallbackUrl = route('admin.dashboard');
    $prev = url()->previous();

    $prevHost = parse_url($prev, PHP_URL_HOST);
    $currHost = parse_url(url()->current(), PHP_URL_HOST);

    $backUrl = ($prevHost && $currHost && $prevHost === $currHost) ? $prev : $fallbackUrl;
@endphp

<a href="{{ $backUrl }}" class="clinic-admin-back" aria-label="Назад">
    <svg class="clinic-admin-back__icon" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
    <span class="clinic-admin-back__text">Назад</span>
</a>

