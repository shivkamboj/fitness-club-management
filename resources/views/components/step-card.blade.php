{{--
    Step Card Component (How It Works)
    Props: number, title, text, last (bool - hides connector line)
--}}
@props([
    'number' => '01',
    'title' => 'Step',
    'text' => '',
    'last' => false,
])

<div {{ $attributes->merge(['class' => 'step-card']) }}>
    @if(!$last)
        <div class="step-connector"></div>
    @endif
    <div class="step-num">{{ $number }}</div>
    <h5>{{ $title }}</h5>
    <p>{{ $text }}</p>
</div>
