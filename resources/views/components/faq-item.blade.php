{{--
    FAQ Accordion Item Component
    Props: id (unique string), question, answer, open (bool, default expanded)
--}}
@props([
    'id' => 'faq',
    'question' => '',
    'answer' => '',
    'open' => false,
])

<div class="accordion-item">
    <h2 class="accordion-header" id="heading-{{ $id }}">
        <button class="accordion-button {{ $open ? '' : 'collapsed' }}" type="button"
                data-bs-toggle="collapse" data-bs-target="#collapse-{{ $id }}"
                aria-expanded="{{ $open ? 'true' : 'false' }}" aria-controls="collapse-{{ $id }}">
            {{ $question }}
        </button>
    </h2>
    <div id="collapse-{{ $id }}" class="accordion-collapse collapse {{ $open ? 'show' : '' }}"
         aria-labelledby="heading-{{ $id }}" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
            {{ $answer }}
        </div>
    </div>
</div>
