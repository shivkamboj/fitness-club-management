@extends('layouts.dashboard')

@section('title', 'WhatsApp Event Notifications')
@section('page_heading', 'WhatsApp Event Notifications')

@section('content')

{{-- Header --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1 fs-3">
            <i class="fa-brands fa-whatsapp me-2" style="color: #25D366;"></i>WhatsApp Event Notifications
        </h2>
        <p class="text-muted mb-0 small">Configure automated WhatsApp templates and dispatch event notifications to members.</p>
    </div>
    <div>
        <a href="{{ route('gym-owner.settings.index', ['tab' => 'whatsapp']) }}" class="btn btn-gwb-secondary py-2 px-3">
            <i class="fa-solid fa-gear me-1"></i> WhatsApp API Settings
        </a>
    </div>
</div>

{{-- Flash messages --}}
@if(session('success'))
    <div class="alert alert-dismissible fade show mb-4 border-0 text-white" style="background: rgba(34,197,94,.15); border-left: 4px solid #22c55e !important;" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">

    {{-- Left side: Event Templates Form --}}
    <div class="col-12 col-lg-8">
        <form action="{{ route('gym-owner.notifications.whatsapp.templates') }}" method="POST">
            @csrf

            <div class="d-flex align-items-center justify-content-between mb-3">
                <h3 class="fw-bold text-white fs-5 mb-0">Event Notification Templates</h3>
                <button type="submit" class="btn btn-gwb-primary btn-sm px-3">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Save All Templates
                </button>
            </div>

            <div class="d-flex flex-column gap-3">
                @foreach($templates as $key => $tpl)
                    <div class="gwb-card p-4">
                        <div class="d-flex align-items-start justify-content-between mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle p-2 bg-dark border border-secondary text-orange d-flex align-items-center justify-content-center" style="width:38px; height:38px;">
                                    <i class="fa-solid {{ $tpl['icon'] }} fs-6"></i>
                                </div>
                                <div>
                                    <h4 class="fw-bold text-white fs-6 mb-0">{{ $tpl['title'] }}</h4>
                                    <div class="text-muted small" style="font-size: 0.8rem;">{{ $tpl['description'] }}</div>
                                </div>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="templates[{{ $key }}][is_enabled]" value="1" 
                                       id="toggle-{{ $key }}" {{ $tpl['is_enabled'] ? 'checked' : '' }}>
                                <label class="form-check-label text-white small" for="toggle-{{ $key }}"></label>
                            </div>
                        </div>

                        {{-- Available placeholders --}}
                        <div class="mb-2">
                            <span class="text-muted small me-2" style="font-size: 0.75rem;">Placeholders:</span>
                            @foreach($tpl['placeholders'] as $ph)
                                <span class="badge bg-dark border border-secondary text-info me-1 font-monospace px-2 py-1 cursor-pointer placeholder-badge" 
                                      style="font-size: 0.72rem;" title="Click to insert" onclick="insertPlaceholder('{{ $key }}', '{{ $ph }}')">
                                    {{ $ph }}
                                </span>
                            @endforeach
                        </div>

                        {{-- Template Textarea --}}
                        <div>
                            <textarea name="templates[{{ $key }}][message_template]" id="template-{{ $key }}" rows="3" 
                                      class="form-control bg-dark border-secondary text-white font-monospace small" 
                                      placeholder="Write notification message...">{{ old('templates.'.$key.'.message_template', $tpl['message_template']) }}</textarea>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-gwb-primary px-4 py-2">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Save All Event Templates
                </button>
            </div>
        </form>
    </div>

    {{-- Right side: Instant WhatsApp Event Dispatcher & Message Previewer --}}
    <div class="col-12 col-lg-4">
        <div class="gwb-card p-4 sticky-top" style="top: 80px; z-index: 5;">
            <h3 class="fw-bold text-white fs-5 mb-1">
                <i class="fa-solid fa-paper-plane me-2 text-orange"></i>Instant Event Dispatcher
            </h3>
            <p class="text-muted small mb-4 border-bottom border-secondary pb-3">Select a member &amp; event to preview and send an instant WhatsApp message.</p>

            <div class="mb-3">
                <label for="dispatch_member" class="form-label text-white small fw-semibold">Select Gym Member</label>
                <select id="dispatch_member" class="form-select bg-dark border-secondary text-white" onchange="previewNotification()">
                    <option value="">— Select Member —</option>
                    @foreach($members as $m)
                        <option value="{{ $m->id }}" data-phone="{{ $m->phone }}">{{ $m->full_name }} ({{ $m->phone }})</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="dispatch_event" class="form-label text-white small fw-semibold">Select Event Type</label>
                <select id="dispatch_event" class="form-select bg-dark border-secondary text-white" onchange="previewNotification()">
                    @foreach($templates as $k => $t)
                        <option value="{{ $k }}">{{ $t['title'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3 d-none" id="offer_details_container">
                <label for="dispatch_offer" class="form-label text-white small fw-semibold">Offer Details / Description</label>
                <input type="text" id="dispatch_offer" class="form-control bg-dark border-secondary text-white" 
                       value="Flat 20% OFF on Annual Memberships this week!" oninput="previewNotification()">
            </div>

            {{-- Message Preview Box --}}
            <div class="mb-4">
                <label class="form-label text-white small fw-semibold">Live Message Preview</label>
                <div class="p-3 rounded-3 border border-success border-opacity-25 text-white small" 
                     id="preview_box" style="background: rgba(37, 211, 102, 0.06); min-height: 120px; white-space: pre-wrap;">
                    <span class="text-muted">Select a member to preview the compiled WhatsApp message.</span>
                </div>
            </div>

            <button type="button" class="btn btn-success w-100 py-2 fw-bold text-white" id="send_wa_btn" onclick="sendWhatsAppMessage()" disabled style="background-color: #25D366; border-color: #25D366;">
                <i class="fa-brands fa-whatsapp me-2 fs-5 align-middle"></i> Send via WhatsApp
            </button>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    function insertPlaceholder(eventKey, placeholder) {
        const textarea = document.getElementById('template-' + eventKey);
        if (!textarea) return;
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const text = textarea.value;
        textarea.value = text.substring(0, start) + placeholder + text.substring(end);
        textarea.focus();
        textarea.selectionStart = textarea.selectionEnd = start + placeholder.length;
    }

    function previewNotification() {
        const memberId = document.getElementById('dispatch_member').value;
        const eventKey = document.getElementById('dispatch_event').value;
        const offerDetails = document.getElementById('dispatch_offer').value;
        const offerContainer = document.getElementById('offer_details_container');
        const previewBox = document.getElementById('preview_box');
        const sendBtn = document.getElementById('send_wa_btn');

        if (eventKey === 'offer_notifications') {
            offerContainer.classList.remove('d-none');
        } else {
            offerContainer.classList.add('d-none');
        }

        if (!memberId) {
            previewBox.innerHTML = '<span class="text-muted">Select a member to preview the compiled WhatsApp message.</span>';
            sendBtn.disabled = true;
            return;
        }

        fetch('{{ route("gym-owner.notifications.whatsapp.generate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                member_id: memberId,
                event_key: eventKey,
                offer_details: offerDetails
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                previewBox.textContent = data.message;
                sendBtn.dataset.url = data.whatsapp_url;
                sendBtn.disabled = false;
            } else {
                previewBox.innerHTML = '<span class="text-danger">Failed to generate message preview.</span>';
                sendBtn.disabled = true;
            }
        })
        .catch(err => {
            console.error(err);
            sendBtn.disabled = true;
        });
    }

    function sendWhatsAppMessage() {
        const sendBtn = document.getElementById('send_wa_btn');
        const url = sendBtn.dataset.url;
        if (url) {
            window.open(url, '_blank');
        }
    }
</script>
@endpush
