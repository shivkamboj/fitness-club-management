{{--
    Feature Card Component
    Props: icon (Font Awesome class), title, text
--}}
@props([
    'icon' => 'fa-solid fa-check',
    'title' => 'Feature',
    'text' => '',
])

<div {{ $attributes->merge(['class' => 'feature-card']) }}>
    <div class="feature-icon">
        <i class="{{ $icon }}"></i>
    </div>
    <h5>{{ $title }}</h5>
    @if($text)
        <p>{{ $text }}</p>
    @endif
</div>
