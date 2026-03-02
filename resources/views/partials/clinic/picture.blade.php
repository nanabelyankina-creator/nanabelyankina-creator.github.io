@props([
    'avif' => '',
    'webp' => '',
    'src' => '',
    'alt' => '',
    'class' => '',
    'imgClass' => 'clinic-img',
])
<picture class="clinic-picture {{ $class }}">
    @if($avif)
        <source type="image/avif" srcset="{{ asset($avif) }}">
    @endif
    @if($webp)
        <source type="image/webp" srcset="{{ asset($webp) }}">
    @endif
    <img class="{{ $imgClass }}" src="{{ asset($src) }}" alt="{{ $alt }}" loading="lazy">
</picture>
