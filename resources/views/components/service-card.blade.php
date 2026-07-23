{{--
    Extra Service Card Component
    Props: icon, title, soon (bool - shows "Coming Soon" tag)
--}}
@props([
    'icon' => 'fa-solid fa-star',
    'title' => 'Service',
    'soon' => false,
])

<div {{ $attributes->merge(['class' => 'service-card']) }}>
    <i class="{{ $icon }}"></i>
    <h6>
        {{ $title }}
        @if($soon)
            <span class="soon-tag">Coming Soon</span>
        @endif
    </h6>
</div>
