{{--
    Testimonial Card Component
    Props: name, role, quote, avatar (image url)
--}}
@props([
    'name' => '',
    'role' => '',
    'quote' => '',
    'avatar' => '',
])

<div {{ $attributes->merge(['class' => 'testimonial-card']) }}>
    <i class="fa-solid fa-quote-left testimonial-quote-icon"></i>
    <div class="testimonial-stars">
        <i class="fa-solid fa-star"></i>
        <i class="fa-solid fa-star"></i>
        <i class="fa-solid fa-star"></i>
        <i class="fa-solid fa-star"></i>
        <i class="fa-solid fa-star"></i>
    </div>
    <p class="quote">"{{ $quote }}"</p>
    <div class="testimonial-author">
        <img src="{{ $avatar }}" alt="{{ $name }}" loading="lazy">
        <div>
            <div class="name">{{ $name }}</div>
            <div class="role">{{ $role }}</div>
        </div>
    </div>
</div>
