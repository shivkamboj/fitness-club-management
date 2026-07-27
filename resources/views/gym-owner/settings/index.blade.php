@extends('layouts.dashboard')

@section('title', 'Gym Settings')
@section('page_heading', 'Gym Settings')

@php
    $tab = request('tab', old('tab', $activeTab ?? 'profile'));
    $s   = $settings; // shortcut
    $workingDays = json_decode($s['working_days'] ?? '[]', true) ?: [];
    $allDays = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
@endphp

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1 fs-3">Gym Settings</h2>
        <p class="text-muted mb-0 small">Configure your gym profile, branding, hours, branches, integrations &amp; more.</p>
    </div>
</div>

{{-- Flash --}}
@if(session('success'))
    <div class="alert alert-dismissible fade show mb-4 border-0 text-white" style="background:rgba(34,197,94,.15);border-left:4px solid #22c55e!important;" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    </div>
@endif
@if($errors->any())
    <div class="alert alert-dismissible fade show mb-4 border-0 text-white" style="background:rgba(239,68,68,.15);border-left:4px solid #ef4444!important;" role="alert">
        <i class="fa-solid fa-triangle-exclamation me-2"></i>
        <strong>Please fix the following errors:</strong>
        <ul class="mb-0 mt-1 small">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">

    {{-- ── SIDEBAR NAV TABS ── --}}
    <div class="col-12 col-lg-3">
        <div class="gwb-card p-0 overflow-hidden">
            <div class="list-group list-group-flush settings-tabs" id="settingsTab" role="tablist">
                @php
                    $tabs = [
                        'profile'  => ['icon' => 'fa-building',       'label' => 'Gym Profile'],
                        'logo'     => ['icon' => 'fa-image',          'label' => 'Logo & Branding'],
                        'taxes'    => ['icon' => 'fa-percent',        'label' => 'Taxes'],
                        'currency' => ['icon' => 'fa-indian-rupee-sign','label' => 'Currency'],
                        'hours'    => ['icon' => 'fa-clock',          'label' => 'Working Hours & Days'],
                        'branches' => ['icon' => 'fa-map-location-dot','label' => 'Branches'],
                        'sms'      => ['icon' => 'fa-comment-sms',   'label' => 'SMS Settings'],
                        'whatsapp' => ['icon' => 'fa-brands fa-whatsapp', 'label' => 'WhatsApp Settings'],
                        'backup'   => ['icon' => 'fa-database',      'label' => 'Backup & Export'],
                    ];
                @endphp
                @foreach($tabs as $key => $t)
                    <a class="list-group-item list-group-item-action border-0 d-flex align-items-center gap-3 py-3 px-4 settings-tab-link {{ $tab === $key ? 'active' : '' }}"
                       id="tab-{{ $key }}" data-bs-toggle="list" href="#pane-{{ $key }}" role="tab">
                        <i class="fa-solid {{ $t['icon'] }}" style="width:18px;text-align:center;"></i>
                        <span class="small fw-semibold">{{ $t['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ── TAB CONTENT ── --}}
    <div class="col-12 col-lg-9">
        <div class="tab-content" id="settingsContent">

            {{-- ═══ GYM PROFILE ═══ --}}
            <div class="tab-pane fade {{ $tab === 'profile' ? 'show active' : '' }}" id="pane-profile" role="tabpanel">
                <div class="gwb-card">
                    <h3 class="fw-bold text-white fs-5 mb-1"><i class="fa-solid fa-building me-2 text-orange"></i>Gym Profile</h3>
                    <p class="text-muted small mb-4 border-bottom border-secondary pb-3">Basic information about your gym displayed across the platform.</p>

                    <form action="{{ route('gym-owner.settings.profile') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tab" value="profile">
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <label class="form-label text-white small fw-semibold">Gym Name *</label>
                                <input type="text" name="gym_name" class="form-control bg-dark border-secondary text-white"
                                       value="{{ old('gym_name', $s['gym_name'] ?? Auth::user()->gym_name ?? '') }}" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label text-white small fw-semibold">Contact Email</label>
                                <input type="email" name="gym_email" class="form-control bg-dark border-secondary text-white"
                                       value="{{ old('gym_email', $s['gym_email'] ?? '') }}" placeholder="info@mygym.com">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label text-white small fw-semibold">Phone</label>
                                <input type="text" name="gym_phone" class="form-control bg-dark border-secondary text-white"
                                       value="{{ old('gym_phone', $s['gym_phone'] ?? '') }}" placeholder="+91 98765 43210">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label text-white small fw-semibold">Website</label>
                                <input type="url" name="gym_website" class="form-control bg-dark border-secondary text-white"
                                       value="{{ old('gym_website', $s['gym_website'] ?? '') }}" placeholder="https://mygym.com">
                            </div>
                            <div class="col-12">
                                <label class="form-label text-white small fw-semibold">Address</label>
                                <input type="text" name="gym_address" class="form-control bg-dark border-secondary text-white"
                                       value="{{ old('gym_address', $s['gym_address'] ?? '') }}" placeholder="123 Fitness Street">
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label text-white small fw-semibold">City</label>
                                <input type="text" name="gym_city" class="form-control bg-dark border-secondary text-white"
                                       value="{{ old('gym_city', $s['gym_city'] ?? '') }}">
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label text-white small fw-semibold">State</label>
                                <input type="text" name="gym_state" class="form-control bg-dark border-secondary text-white"
                                       value="{{ old('gym_state', $s['gym_state'] ?? '') }}">
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label text-white small fw-semibold">Pincode</label>
                                <input type="text" name="gym_pincode" class="form-control bg-dark border-secondary text-white"
                                       value="{{ old('gym_pincode', $s['gym_pincode'] ?? '') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label text-white small fw-semibold">About / Bio</label>
                                <textarea name="gym_about" rows="3" class="form-control bg-dark border-secondary text-white"
                                          placeholder="Write a short description about your gym...">{{ old('gym_about', $s['gym_about'] ?? '') }}</textarea>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-gwb-primary px-4"><i class="fa-solid fa-floppy-disk me-1"></i> Save Profile</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ═══ LOGO ═══ --}}
            <div class="tab-pane fade {{ $tab === 'logo' ? 'show active' : '' }}" id="pane-logo" role="tabpanel">
                <div class="gwb-card">
                    <h3 class="fw-bold text-white fs-5 mb-1"><i class="fa-solid fa-image me-2 text-orange"></i>Logo &amp; Branding</h3>
                    <p class="text-muted small mb-4 border-bottom border-secondary pb-3">Upload your gym logo. It will appear on invoices, the dashboard, and member portals.</p>

                    @php $currentLogo = $s['gym_logo'] ?? null; @endphp

                    {{-- Current Logo --}}
                    <div class="text-center mb-4">
                        <div class="d-inline-block p-4 rounded-3 border border-secondary" style="background:var(--gwb-surface-2);min-width:160px;">
                            @if($currentLogo)
                                <img src="{{ asset('storage/' . $currentLogo) }}" alt="Gym Logo" class="img-fluid" style="max-height:120px;">
                            @else
                                <div class="py-4">
                                    <i class="fa-regular fa-image text-muted" style="font-size:3rem;"></i>
                                    <p class="text-muted small mt-2 mb-0">No logo uploaded</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <form action="{{ route('gym-owner.settings.logo') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="tab" value="logo">
                        <div class="mb-3">
                            <label class="form-label text-white small fw-semibold">Upload New Logo</label>
                            <input type="file" name="gym_logo" class="form-control bg-dark border-secondary text-white @error('gym_logo') is-invalid @enderror"
                                   accept="image/*" required>
                            @error('gym_logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text text-muted small mt-1">Accepted: JPG, PNG, SVG, WebP. Max 2 MB.</div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-gwb-primary"><i class="fa-solid fa-cloud-arrow-up me-1"></i> Upload Logo</button>
                            @if($currentLogo)
                                <form action="{{ route('gym-owner.settings.logo.remove') }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger"><i class="fa-solid fa-trash me-1"></i> Remove</button>
                                </form>
                            @endif
                        </div>
                    </form>

                    @if($currentLogo)
                        <div class="mt-3">
                            <form action="{{ route('gym-owner.settings.logo.remove') }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-trash me-1"></i> Remove Current Logo</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ═══ TAXES ═══ --}}
            <div class="tab-pane fade {{ $tab === 'taxes' ? 'show active' : '' }}" id="pane-taxes" role="tabpanel">
                <div class="gwb-card">
                    <h3 class="fw-bold text-white fs-5 mb-1"><i class="fa-solid fa-percent me-2 text-orange"></i>Tax Settings</h3>
                    <p class="text-muted small mb-4 border-bottom border-secondary pb-3">Configure GST/tax rates applied to invoices and payments.</p>

                    <form action="{{ route('gym-owner.settings.taxes') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tab" value="taxes">
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="tax_enabled" value="1" id="taxEnabled"
                                           {{ ($s['tax_enabled'] ?? '0') === '1' ? 'checked' : '' }}>
                                    <label class="form-check-label text-white small fw-semibold" for="taxEnabled">Enable Tax on Invoices</label>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label text-white small fw-semibold">Tax Name (e.g. GST, CGST)</label>
                                <input type="text" name="tax_name" class="form-control bg-dark border-secondary text-white"
                                       value="{{ old('tax_name', $s['tax_name'] ?? 'GST') }}" placeholder="GST">
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label text-white small fw-semibold">Tax Rate (%)</label>
                                <input type="number" step="0.01" min="0" max="100" name="tax_rate" class="form-control bg-dark border-secondary text-white"
                                       value="{{ old('tax_rate', $s['tax_rate'] ?? '18') }}" placeholder="18">
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label text-white small fw-semibold">GST / Tax Number</label>
                                <input type="text" name="tax_number" class="form-control bg-dark border-secondary text-white"
                                       value="{{ old('tax_number', $s['tax_number'] ?? '') }}" placeholder="22AAAA0000A1Z5">
                            </div>

                            <div class="col-12"><hr class="border-secondary"></div>

                            <div class="col-12 col-md-6">
                                <label class="form-label text-white small fw-semibold">Secondary Tax Name (optional, e.g. SGST)</label>
                                <input type="text" name="secondary_tax_name" class="form-control bg-dark border-secondary text-white"
                                       value="{{ old('secondary_tax_name', $s['secondary_tax_name'] ?? '') }}" placeholder="SGST">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label text-white small fw-semibold">Secondary Tax Rate (%)</label>
                                <input type="number" step="0.01" min="0" max="100" name="secondary_tax_rate" class="form-control bg-dark border-secondary text-white"
                                       value="{{ old('secondary_tax_rate', $s['secondary_tax_rate'] ?? '') }}" placeholder="9">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-gwb-primary px-4"><i class="fa-solid fa-floppy-disk me-1"></i> Save Tax Settings</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ═══ CURRENCY ═══ --}}
            <div class="tab-pane fade {{ $tab === 'currency' ? 'show active' : '' }}" id="pane-currency" role="tabpanel">
                <div class="gwb-card">
                    <h3 class="fw-bold text-white fs-5 mb-1"><i class="fa-solid fa-indian-rupee-sign me-2 text-orange"></i>Currency Settings</h3>
                    <p class="text-muted small mb-4 border-bottom border-secondary pb-3">Set the default currency used across invoices, payments, and reports.</p>

                    <form action="{{ route('gym-owner.settings.currency') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tab" value="currency">
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-4">
                                <label class="form-label text-white small fw-semibold">Currency Code *</label>
                                <select name="currency_code" class="form-select bg-dark border-secondary text-white" required>
                                    @foreach([
                                        'INR' => 'INR — Indian Rupee',
                                        'USD' => 'USD — US Dollar',
                                        'EUR' => 'EUR — Euro',
                                        'GBP' => 'GBP — British Pound',
                                        'AED' => 'AED — UAE Dirham',
                                        'SAR' => 'SAR — Saudi Riyal',
                                        'CAD' => 'CAD — Canadian Dollar',
                                        'AUD' => 'AUD — Australian Dollar',
                                        'SGD' => 'SGD — Singapore Dollar',
                                        'MYR' => 'MYR — Malaysian Ringgit',
                                    ] as $code => $label)
                                        <option value="{{ $code }}" {{ old('currency_code', $s['currency_code'] ?? 'INR') === $code ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label text-white small fw-semibold">Symbol *</label>
                                <input type="text" name="currency_symbol" class="form-control bg-dark border-secondary text-white"
                                       value="{{ old('currency_symbol', $s['currency_symbol'] ?? '₹') }}" required maxlength="5" placeholder="₹">
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label text-white small fw-semibold">Symbol Position *</label>
                                <select name="currency_position" class="form-select bg-dark border-secondary text-white" required>
                                    <option value="before" {{ old('currency_position', $s['currency_position'] ?? 'before') === 'before' ? 'selected' : '' }}>Before amount (₹500)</option>
                                    <option value="after"  {{ old('currency_position', $s['currency_position'] ?? 'before') === 'after'  ? 'selected' : '' }}>After amount (500₹)</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-gwb-primary px-4"><i class="fa-solid fa-floppy-disk me-1"></i> Save Currency</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ═══ WORKING HOURS & DAYS ═══ --}}
            <div class="tab-pane fade {{ $tab === 'hours' ? 'show active' : '' }}" id="pane-hours" role="tabpanel">
                <div class="gwb-card">
                    <h3 class="fw-bold text-white fs-5 mb-1"><i class="fa-solid fa-clock me-2 text-orange"></i>Working Hours &amp; Days</h3>
                    <p class="text-muted small mb-4 border-bottom border-secondary pb-3">Set your gym's operating schedule.</p>

                    <form action="{{ route('gym-owner.settings.hours') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tab" value="hours">

                        <div class="mb-4">
                            <label class="form-label text-white small fw-semibold mb-2">Working Days</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($allDays as $day)
                                    <div class="day-toggle {{ in_array($day, $workingDays) ? 'selected' : '' }}"
                                         onclick="toggleDay(this)" data-day="{{ $day }}">
                                        {{ $day }}
                                        <input type="checkbox" name="working_days[]" value="{{ $day }}" class="d-none day-checkbox"
                                               {{ in_array($day, $workingDays) ? 'checked' : '' }}>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-6 col-md-3">
                                <label class="form-label text-white small fw-semibold">Weekday Opening</label>
                                <input type="time" name="opening_time" class="form-control bg-dark border-secondary text-white"
                                       value="{{ old('opening_time', $s['opening_time'] ?? '06:00') }}">
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label text-white small fw-semibold">Weekday Closing</label>
                                <input type="time" name="closing_time" class="form-control bg-dark border-secondary text-white"
                                       value="{{ old('closing_time', $s['closing_time'] ?? '22:00') }}">
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label text-white small fw-semibold">Weekend Opening</label>
                                <input type="time" name="weekend_opening" class="form-control bg-dark border-secondary text-white"
                                       value="{{ old('weekend_opening', $s['weekend_opening'] ?? '07:00') }}">
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label text-white small fw-semibold">Weekend Closing</label>
                                <input type="time" name="weekend_closing" class="form-control bg-dark border-secondary text-white"
                                       value="{{ old('weekend_closing', $s['weekend_closing'] ?? '20:00') }}">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-gwb-primary px-4"><i class="fa-solid fa-floppy-disk me-1"></i> Save Hours</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ═══ BRANCHES ═══ --}}
            <div class="tab-pane fade {{ $tab === 'branches' ? 'show active' : '' }}" id="pane-branches" role="tabpanel">
                <div class="gwb-card mb-4">
                    <h3 class="fw-bold text-white fs-5 mb-1"><i class="fa-solid fa-map-location-dot me-2 text-orange"></i>Gym Branches</h3>
                    <p class="text-muted small mb-4 border-bottom border-secondary pb-3">Manage multiple gym locations.</p>

                    {{-- Existing Branches --}}
                    @forelse($branches as $branch)
                        <div class="p-3 rounded border border-secondary mb-3" style="background:var(--gwb-surface-2);">
                            <form action="{{ route('gym-owner.settings.branches.update', $branch->id) }}" method="POST">
                                @csrf @method('PUT')
                                <input type="hidden" name="tab" value="branches">
                                <div class="row g-2 mb-2">
                                    <div class="col-12 col-md-4">
                                        <input type="text" name="name" class="form-control form-control-sm bg-dark border-secondary text-white" value="{{ $branch->name }}" placeholder="Branch Name" required>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input type="text" name="address" class="form-control form-control-sm bg-dark border-secondary text-white" value="{{ $branch->address }}" placeholder="Address">
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <input type="text" name="phone" class="form-control form-control-sm bg-dark border-secondary text-white" value="{{ $branch->phone }}" placeholder="Phone">
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <select name="status" class="form-select form-select-sm bg-dark border-secondary text-white">
                                            <option value="active" {{ $branch->status === 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ $branch->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input type="email" name="email" class="form-control form-control-sm bg-dark border-secondary text-white" value="{{ $branch->email }}" placeholder="Email">
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input type="text" name="manager_name" class="form-control form-control-sm bg-dark border-secondary text-white" value="{{ $branch->manager_name }}" placeholder="Manager Name">
                                    </div>
                                    <div class="col-12 col-md-4 d-flex gap-2">
                                        <button type="submit" class="btn btn-gwb-primary btn-sm flex-fill"><i class="fa-solid fa-check me-1"></i> Update</button>
                                        </form>
                                        <form action="{{ route('gym-owner.settings.branches.destroy', $branch->id) }}" method="POST" class="delete-branch-form">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-outline-danger btn-sm delete-branch-btn"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </div>
                                </div>
                        </div>
                    @empty
                        <p class="text-muted small text-center py-3"><i class="fa-solid fa-info-circle me-1"></i>No branches added yet. Add your first branch below.</p>
                    @endforelse
                </div>

                {{-- Add New Branch --}}
                <div class="gwb-card">
                    <h4 class="fw-semibold text-white fs-6 mb-3"><i class="fa-solid fa-plus me-2 text-orange"></i>Add New Branch</h4>
                    <form action="{{ route('gym-owner.settings.branches.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tab" value="branches">
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-6">
                                <input type="text" name="name" class="form-control bg-dark border-secondary text-white" placeholder="Branch Name *" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <input type="text" name="address" class="form-control bg-dark border-secondary text-white" placeholder="Full Address">
                            </div>
                            <div class="col-12 col-md-4">
                                <input type="text" name="phone" class="form-control bg-dark border-secondary text-white" placeholder="Phone Number">
                            </div>
                            <div class="col-12 col-md-4">
                                <input type="email" name="email" class="form-control bg-dark border-secondary text-white" placeholder="Email">
                            </div>
                            <div class="col-12 col-md-4">
                                <input type="text" name="manager_name" class="form-control bg-dark border-secondary text-white" placeholder="Manager Name">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-gwb-primary"><i class="fa-solid fa-plus me-1"></i> Add Branch</button>
                    </form>
                </div>
            </div>

            {{-- ═══ SMS SETTINGS ═══ --}}
            <div class="tab-pane fade {{ $tab === 'sms' ? 'show active' : '' }}" id="pane-sms" role="tabpanel">
                <div class="gwb-card">
                    <h3 class="fw-bold text-white fs-5 mb-1"><i class="fa-solid fa-comment-sms me-2 text-orange"></i>SMS Settings</h3>
                    <p class="text-muted small mb-4 border-bottom border-secondary pb-3">Configure SMS gateway for automated member notifications, reminders, and OTP.</p>

                    <form action="{{ route('gym-owner.settings.sms') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tab" value="sms">
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="sms_enabled" value="1" id="smsEnabled"
                                           {{ ($s['sms_enabled'] ?? '0') === '1' ? 'checked' : '' }}>
                                    <label class="form-check-label text-white small fw-semibold" for="smsEnabled">Enable SMS Notifications</label>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label text-white small fw-semibold">SMS Provider</label>
                                <select name="sms_provider" class="form-select bg-dark border-secondary text-white">
                                    <option value="">Select Provider</option>
                                    @foreach(['MSG91','Twilio','TextLocal','Fast2SMS','Kaleyra','Exotel','Custom API'] as $p)
                                        <option value="{{ $p }}" {{ old('sms_provider', $s['sms_provider'] ?? '') === $p ? 'selected' : '' }}>{{ $p }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label text-white small fw-semibold">API Key / Auth Token</label>
                                <input type="password" name="sms_api_key" class="form-control bg-dark border-secondary text-white"
                                       value="{{ old('sms_api_key', $s['sms_api_key'] ?? '') }}" placeholder="••••••••">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label text-white small fw-semibold">Sender ID</label>
                                <input type="text" name="sms_sender_id" class="form-control bg-dark border-secondary text-white"
                                       value="{{ old('sms_sender_id', $s['sms_sender_id'] ?? '') }}" placeholder="GYMAPP" maxlength="20">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-gwb-primary px-4"><i class="fa-solid fa-floppy-disk me-1"></i> Save SMS Settings</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ═══ WHATSAPP SETTINGS ═══ --}}
            <div class="tab-pane fade {{ $tab === 'whatsapp' ? 'show active' : '' }}" id="pane-whatsapp" role="tabpanel">
                <div class="gwb-card">
                    <h3 class="fw-bold text-white fs-5 mb-1"><i class="fa-brands fa-whatsapp me-2" style="color:#25d366;"></i>WhatsApp Settings</h3>
                    <p class="text-muted small mb-4 border-bottom border-secondary pb-3">Connect a WhatsApp Business API for member messaging and automated reminders.</p>

                    <form action="{{ route('gym-owner.settings.whatsapp') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tab" value="whatsapp">
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="whatsapp_enabled" value="1" id="waEnabled"
                                           {{ ($s['whatsapp_enabled'] ?? '0') === '1' ? 'checked' : '' }}>
                                    <label class="form-check-label text-white small fw-semibold" for="waEnabled">Enable WhatsApp Integration</label>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label text-white small fw-semibold">API Provider</label>
                                <select name="whatsapp_api_provider" class="form-select bg-dark border-secondary text-white">
                                    <option value="">Select Provider</option>
                                    @foreach(['Meta (Official)','WATI','Interakt','AiSensy','Twilio','360dialog','Custom API'] as $p)
                                        <option value="{{ $p }}" {{ old('whatsapp_api_provider', $s['whatsapp_api_provider'] ?? '') === $p ? 'selected' : '' }}>{{ $p }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label text-white small fw-semibold">API Key / Auth Token</label>
                                <input type="password" name="whatsapp_api_key" class="form-control bg-dark border-secondary text-white"
                                       value="{{ old('whatsapp_api_key', $s['whatsapp_api_key'] ?? '') }}" placeholder="••••••••">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label text-white small fw-semibold">WhatsApp Business Number</label>
                                <input type="text" name="whatsapp_phone_number" class="form-control bg-dark border-secondary text-white"
                                       value="{{ old('whatsapp_phone_number', $s['whatsapp_phone_number'] ?? '') }}" placeholder="+91 98765 43210">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-gwb-primary px-4"><i class="fa-solid fa-floppy-disk me-1"></i> Save WhatsApp Settings</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ═══ BACKUP ═══ --}}
            <div class="tab-pane fade {{ $tab === 'backup' ? 'show active' : '' }}" id="pane-backup" role="tabpanel">
                <div class="gwb-card">
                    <h3 class="fw-bold text-white fs-5 mb-1"><i class="fa-solid fa-database me-2 text-orange"></i>Backup &amp; Export</h3>
                    <p class="text-muted small mb-4 border-bottom border-secondary pb-3">Download a backup of your gym settings and branch data.</p>

                    <div class="p-4 rounded-3 border border-secondary text-center mb-4" style="background:var(--gwb-surface-2);">
                        <i class="fa-solid fa-cloud-arrow-down text-orange mb-3" style="font-size:2.5rem;"></i>
                        <h5 class="text-white fw-semibold mb-2">Export Gym Data</h5>
                        <p class="text-muted small mb-3">Download your gym settings, branches, and configuration as a JSON file.</p>
                        <a href="{{ route('gym-owner.settings.backup') }}" class="btn btn-gwb-primary px-4">
                            <i class="fa-solid fa-download me-1"></i> Download Backup
                        </a>
                    </div>

                    <div class="alert border-0 small" style="background:rgba(59,130,246,.1);color:#93c5fd;border-left:4px solid #3b82f6!important;">
                        <i class="fa-solid fa-info-circle me-2"></i>
                        <strong>Note:</strong> This backup includes gym settings, branch information, and configuration data. Member data and financial records are not included for security purposes. Contact support for a full database export.
                    </div>
                </div>
            </div>

        </div>{{-- /tab-content --}}
    </div>
</div>

@endsection

@push('styles')
<style>
    /* Settings tab sidebar */
    .settings-tab-link {
        background: transparent !important;
        color: #9ca3af;
        border-bottom: 1px solid var(--gwb-border, rgba(255,255,255,.06)) !important;
        transition: all .2s;
    }
    .settings-tab-link:hover,
    .settings-tab-link:focus {
        background: var(--gwb-surface-2, rgba(255,255,255,.04)) !important;
        color: #fff;
    }
    .settings-tab-link.active {
        background: rgba(234,88,12,.1) !important;
        color: #ea580c !important;
        border-left: 3px solid #ea580c !important;
        font-weight: 600;
    }
    .settings-tab-link i {
        color: inherit;
        font-size: .9rem;
        opacity: .7;
    }
    .settings-tab-link.active i {
        opacity: 1;
        color: #ea580c;
    }

    /* Day toggle pills */
    .day-toggle {
        display: inline-flex; align-items: center; justify-content: center;
        width: 52px; height: 38px; border-radius: 8px;
        border: 1px solid var(--gwb-border, #374151);
        color: #9ca3af; font-size: .8rem; font-weight: 600;
        cursor: pointer; transition: all .2s;
        background: var(--gwb-surface-2, #1a1a1a); user-select: none;
    }
    .day-toggle:hover { border-color: #ea580c; color: #ea580c; }
    .day-toggle.selected { background: rgba(234,88,12,.15); border-color: #ea580c; color: #ea580c; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function toggleDay(el) {
    el.classList.toggle('selected');
    el.querySelector('.day-checkbox').checked = el.classList.contains('selected');
}

document.addEventListener('DOMContentLoaded', function () {
    // Delete branch confirmation
    document.querySelectorAll('.delete-branch-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const form   = this.closest('.delete-branch-form');
            const isDark = document.documentElement.getAttribute('data-theme') !== 'light';
            Swal.fire({
                title: 'Delete Branch?',
                text: 'This branch will be permanently removed.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ea580c',
                cancelButtonColor: '#4b5563',
                confirmButtonText: 'Yes, delete',
                cancelButtonText: 'Cancel',
                background: isDark ? 'var(--gwb-surface-1,#1e1e1e)' : '#fff',
                color: isDark ? '#fff' : '#1e1e1e',
                customClass: { popup: isDark ? 'border border-secondary rounded-3' : 'border rounded-3 shadow' }
            }).then(r => { if (r.isConfirmed) form.submit(); });
        });
    });

    // Remember active tab in URL
    document.querySelectorAll('.settings-tab-link').forEach(link => {
        link.addEventListener('shown.bs.tab', function () {
            const tabKey = this.getAttribute('href').replace('#pane-', '');
            const url = new URL(window.location);
            url.searchParams.set('tab', tabKey);
            history.replaceState(null, '', url);
        });
    });
});
</script>
@endpush
