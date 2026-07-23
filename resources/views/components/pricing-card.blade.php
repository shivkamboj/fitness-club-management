{{--
    Pricing Card Component
    Props:
    - name       (string) plan name
    - price      (string) e.g. "4,999" or "999"
    - period     (string) e.g. "One Time" or "/month"
    - features   (array)  list of ['text' => string, 'heading' => bool]
    - button     (string) button label
    - href       (string) button link/route
    - badge      (string|null) e.g. "Most Popular"
    - featured   (bool) highlight style
--}}
@props([
    'name' => 'Plan',
    'price' => '0',
    'period' => 'One Time',
    'features' => [],
    'button' => 'Get Started',
    'href' => '#contact',
    'badge' => null,
    'featured' => false,
])

<div {{ $attributes->merge(['class' => 'pricing-card h-100' . ($featured ? ' featured' : '')]) }}>
    @if($badge)
        <span class="pricing-badge">{{ $badge }}</span>
    @endif

    <span class="pricing-plan-name">{{ $name }}</span>
    <div class="pricing-amount">
        <sup>$</sup>{{ $price }}
    </div>
    <span class="pricing-period">{{ $period }}</span>

    <ul class="pricing-features">
        @foreach($features as $feature)
            @if(!empty($feature['heading']))
                <li class="included-heading">{{ $feature['text'] }}</li>
            @else
                <li><i class="fa-solid fa-circle-check"></i> {{ $feature['text'] }}</li>
            @endif
        @endforeach
    </ul>

    <a href="{{ $href }}" class="btn {{ $featured ? 'btn-gwb-primary' : 'btn-gwb-outline' }} w-100 text-center">
        {{ $button }}
    </a>
</div>
