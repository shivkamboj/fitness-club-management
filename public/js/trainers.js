/* ==========================================================================
   Gym Owner — Trainer Management (AJAX)
   ========================================================================== */

(function () {
  'use strict';

  const cfg = window.TrainerModule || {};
  const csrf = cfg.csrf || document.querySelector('meta[name="csrf-token"]')?.content || '';

  const state = {
    page: 1,
    search: '',
    status: '',
    perPage: 10,
    editingId: null,
    searchTimer: null,
    lastCredentials: null,
    specializationTagify: null,
  };

  const els = {};

  document.addEventListener('DOMContentLoaded', init);

  function init() {
    cacheElements();
    initSpecializationTagify();
    bindEvents();
    loadTrainers();
  }

  function initSpecializationTagify() {
    const input = document.getElementById('specialization');
    if (!input || typeof Tagify === 'undefined') return;

    if (state.specializationTagify) {
      state.specializationTagify.destroy();
      state.specializationTagify = null;
    }

    state.specializationTagify = new Tagify(input, {
      whitelist: cfg.specializationWhitelist || [],
      maxTags: 15,
      dropdown: {
        enabled: 0,
        maxItems: 15,
        classname: 'tags-look',
        closeOnSelect: false,
        highlightFirst: true,
      },
      placeholders: {
        empty: 'Type and press Enter',
      },
      originalInputValueFormat: function (values) {
        return values.map(function (item) { return item.value; }).join(', ');
      },
    });
  }

  function setSpecializationTags(value) {
    if (!state.specializationTagify) {
      const input = document.getElementById('specialization');
      if (input) input.value = value || '';
      return;
    }

    state.specializationTagify.removeAllTags();

    const tags = parseSpecializationTags(value);
    if (tags.length) {
      state.specializationTagify.addTags(tags);
    }
  }

  function getSpecializationValue() {
    if (!state.specializationTagify) {
      return (document.getElementById('specialization')?.value || '').trim();
    }

    const tags = state.specializationTagify.value || [];
    return tags.map(function (item) { return item.value; }).filter(Boolean).join(', ');
  }

  function parseSpecializationTags(value) {
    if (!value) return [];

    // Support Tagify JSON string, comma-separated, or single value
    if (typeof value === 'string' && value.trim().startsWith('[')) {
      try {
        const parsed = JSON.parse(value);
        if (Array.isArray(parsed)) {
          return parsed.map(function (item) {
            return typeof item === 'string' ? item : (item.value || '');
          }).filter(Boolean);
        }
      } catch (e) {
        // fall through
      }
    }

    return String(value)
      .split(',')
      .map(function (part) { return part.trim(); })
      .filter(Boolean);
  }

//   function formatSpecializationTags(value) {
//     const tags = parseSpecializationTags(value);
//     if (!tags.length) return '—';
//     return tags.map(function (tag) {
//       return '<span class="spec-tag">' + escapeHtml(tag) + '</span>';
//     }).join(' ');
//   }

    function formatSpecializationTags(value) {
        const tags = parseSpecializationTags(value);

        if (!tags.length) return '—';

        const visible = tags.slice(0, 1);
        const remaining = tags.length - 1;

        let html = visible.map(tag =>
            `<span class="spec-tag">${escapeHtml(tag)}</span>`
        ).join(' ');

        if (remaining > 0) {
            html += ` <span class="spec-tag spec-more">+${remaining}</span>`;
        }
        return html;
    }

  function cacheElements() {
    els.search = document.getElementById('trainerSearch');
    els.statusFilter = document.getElementById('trainerStatusFilter');
    els.perPage = document.getElementById('trainerPerPage');
    els.tbody = document.getElementById('trainersTableBody');
    els.meta = document.getElementById('trainersMeta');
    els.pagination = document.getElementById('trainersPagination');
    els.overlay = document.getElementById('trainerLoadingOverlay');
    els.form = document.getElementById('trainerForm');
    els.formModal = document.getElementById('trainerFormModal');
    els.formModalLabel = document.getElementById('trainerFormModalLabel');
    els.credentialsModal = document.getElementById('credentialsModal');
    els.saveBtn = document.getElementById('btnSaveTrainer');
    els.openAdd = document.getElementById('btnOpenAddTrainer');
    els.generatePassword = document.getElementById('btnGeneratePassword');
    els.copyCredentials = document.getElementById('btnCopyCredentials');
    els.bsFormModal = els.formModal ? new bootstrap.Modal(els.formModal) : null;
    els.bsCredentialsModal = els.credentialsModal ? new bootstrap.Modal(els.credentialsModal) : null;
  }

  function bindEvents() {
    els.openAdd?.addEventListener('click', openCreateModal);

    els.search?.addEventListener('input', function () {
      clearTimeout(state.searchTimer);
      state.searchTimer = setTimeout(function () {
        state.search = els.search.value.trim();
        state.page = 1;
        loadTrainers();
      }, 350);
    });

    els.statusFilter?.addEventListener('change', function () {
      state.status = els.statusFilter.value;
      state.page = 1;
      loadTrainers();
    });

    els.perPage?.addEventListener('change', function () {
      state.perPage = parseInt(els.perPage.value, 10) || 10;
      state.page = 1;
      loadTrainers();
    });

    els.form?.addEventListener('submit', onSubmitForm);
    els.generatePassword?.addEventListener('click', onGeneratePassword);
    els.copyCredentials?.addEventListener('click', onCopyCredentials);

    document.querySelectorAll('.toggle-password').forEach(function (btn) {
      btn.addEventListener('click', function () {
        const input = document.getElementById(btn.getAttribute('data-target'));
        if (!input) return;
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
          input.type = 'text';
          icon?.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
          input.type = 'password';
          icon?.classList.replace('fa-eye-slash', 'fa-eye');
        }
      });
    });

    setupImageUpload('profile_image', 'profilePreview', 'profilePreviewWrap', 'remove_profile_image');
    setupImageUpload('background_image', 'backgroundPreview', 'backgroundPreviewWrap', 'remove_background_image');

    document.querySelectorAll('[data-remove]').forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        const type = btn.getAttribute('data-remove');
        if (type === 'profile') {
          clearImage('profile_image', 'profilePreview', 'profilePreviewWrap', 'remove_profile_image');
        } else {
          clearImage('background_image', 'backgroundPreview', 'backgroundPreviewWrap', 'remove_background_image');
        }
      });
    });

    els.tbody?.addEventListener('click', onTableAction);
    els.pagination?.addEventListener('click', onPaginationClick);
  }

  function setupImageUpload(inputId, previewId, wrapId, removeFlagId) {
    const box = document.querySelector('[data-upload="' + inputId + '"]');
    const input = document.getElementById(inputId);
    if (!box || !input) return;

    const openPicker = function () { input.click(); };

    box.addEventListener('click', openPicker);
    box.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        openPicker();
      }
    });

    ['dragenter', 'dragover'].forEach(function (evt) {
      box.addEventListener(evt, function (e) {
        e.preventDefault();
        box.classList.add('is-dragover');
      });
    });

    ['dragleave', 'drop'].forEach(function (evt) {
      box.addEventListener(evt, function (e) {
        e.preventDefault();
        box.classList.remove('is-dragover');
      });
    });

    box.addEventListener('drop', function (e) {
      const file = e.dataTransfer?.files?.[0];
      if (!file) return;
      const dt = new DataTransfer();
      dt.items.add(file);
      input.files = dt.files;
      input.dispatchEvent(new Event('change'));
    });

    input.addEventListener('change', function () {
      const file = input.files?.[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = function (ev) {
        const preview = document.getElementById(previewId);
        const wrap = document.getElementById(wrapId);
        if (preview) preview.src = ev.target.result;
        wrap?.classList.add('is-visible');
        const flag = document.getElementById(removeFlagId);
        if (flag) flag.value = '0';
      };
      reader.readAsDataURL(file);
    });
  }

  function clearImage(inputId, previewId, wrapId, removeFlagId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    const wrap = document.getElementById(wrapId);
    const flag = document.getElementById(removeFlagId);
    if (input) input.value = '';
    if (preview) preview.src = '';
    wrap?.classList.remove('is-visible');
    if (flag) flag.value = '1';
  }

  async function loadTrainers() {
    setLoading(true);

    const params = new URLSearchParams({
      page: String(state.page),
      per_page: String(state.perPage),
      search: state.search,
      status: state.status,
    });

    try {
      const res = await fetch(cfg.routes.index + '?' + params.toString(), {
        headers: {
          Accept: 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
        },
        credentials: 'same-origin',
      });

      const json = await res.json();
      if (!res.ok || !json.success) {
        throw new Error(json.message || 'Failed to load trainers.');
      }

      renderRows(json.data || []);
      renderMeta(json.meta || {});
      renderPagination(json.meta || {});
    } catch (err) {
      els.tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger py-4">' + escapeHtml(err.message) + '</td></tr>';
      toast(err.message, 'error');
    } finally {
      setLoading(false);
    }
  }

  function renderRows(rows) {
    if (!rows.length) {
      els.tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">No trainers found.</td></tr>';
      return;
    }

    els.tbody.innerHTML = rows.map(function (t) {
      const avatar = t.profile_image_url
        ? '<img src="' + escapeHtml(t.profile_image_url) + '" alt="" class="trainer-avatar-thumb" loading="lazy">'
        : '<span class="trainer-avatar-thumb">' + escapeHtml(t.initials || '?') + '</span>';

      const statusBadge = t.status === 'active'
        ? '<span class="badge-status badge-active">Active</span>'
        : '<span class="badge-status badge-warning">Inactive</span>';

      const nextStatus = t.status === 'active' ? 'inactive' : 'active';
      const statusTitle = t.status === 'active' ? 'Deactivate' : 'Activate';
      const statusIcon = t.status === 'active' ? 'fa-toggle-on' : 'fa-toggle-off';

      return (
        '<tr data-id="' + t.id + '">' +
          '<td>' +
            '<div class="d-flex align-items-center gap-2">' +
              avatar +
              '<div>' +
                '<div class="fw-semibold text-white">' + escapeHtml(t.full_name) + '</div>' +
                '<div class="small text-muted">' + escapeHtml(t.email) + '</div>' +
              '</div>' +
            '</div>' +
          '</td>' +
          '<td class="text-muted">' + escapeHtml(t.phone || '—') + '</td>' +
          '<td class="text-muted">' + formatSpecializationTags(t.specialization) + '</td>' +
          '<td class="text-muted">' + (t.experience != null ? escapeHtml(String(t.experience)) + ' yrs' : '—') + '</td>' +
          '<td>' + statusBadge + '</td>' +
          '<td class="text-end text-nowrap">' +
            '<button type="button" class="btn btn-sm btn-outline-light me-1" data-action="edit" title="Edit"><i class="fa-solid fa-pen"></i></button>' +
            '<button type="button" class="btn btn-sm btn-outline-warning me-1" data-action="status" data-status="' + nextStatus + '" title="' + statusTitle + '"><i class="fa-solid ' + statusIcon + '"></i></button>' +
            '<button type="button" class="btn btn-sm btn-outline-danger" data-action="delete" title="Delete"><i class="fa-solid fa-trash"></i></button>' +
          '</td>' +
        '</tr>'
      );
    }).join('');
  }

  function renderMeta(meta) {
    if (!meta.total) {
      els.meta.textContent = 'Showing 0 trainers';
      return;
    }
    els.meta.textContent = 'Showing ' + meta.from + '–' + meta.to + ' of ' + meta.total + ' trainers';
  }

  function renderPagination(meta) {
    const last = meta.last_page || 1;
    const current = meta.current_page || 1;

    if (last <= 1) {
      els.pagination.innerHTML = '';
      return;
    }

    let html = '';
    html += pageItem(current - 1, '‹', current <= 1);
    for (let i = 1; i <= last; i++) {
      if (last > 7 && Math.abs(i - current) > 2 && i !== 1 && i !== last) {
        if (i === 2 || i === last - 1) html += '<li class="page-item disabled"><span class="page-link">…</span></li>';
        continue;
      }
      html += '<li class="page-item ' + (i === current ? 'active' : '') + '"><a href="#" class="page-link" data-page="' + i + '">' + i + '</a></li>';
    }
    html += pageItem(current + 1, '›', current >= last);
    els.pagination.innerHTML = html;
  }

  function pageItem(page, label, disabled) {
    return '<li class="page-item ' + (disabled ? 'disabled' : '') + '"><a href="#" class="page-link" data-page="' + page + '">' + label + '</a></li>';
  }

  function onPaginationClick(e) {
    const link = e.target.closest('[data-page]');
    if (!link) return;
    e.preventDefault();
    const page = parseInt(link.getAttribute('data-page'), 10);
    if (!page || page === state.page) return;
    state.page = page;
    loadTrainers();
  }

  function onTableAction(e) {
    const btn = e.target.closest('[data-action]');
    if (!btn) return;
    const row = btn.closest('tr');
    const id = parseInt(row?.getAttribute('data-id'), 10);
    if (!id) return;

    const action = btn.getAttribute('data-action');
    if (action === 'edit') openEditModal(id);
    if (action === 'delete') confirmDelete(id);
    if (action === 'status') confirmStatus(id, btn.getAttribute('data-status'));
  }

  function openCreateModal() {
    state.editingId = null;
    resetForm();
    els.formModalLabel.textContent = 'Add Trainer';
    document.getElementById('passwordHint').textContent = '(auto-generated if empty)';
    els.bsFormModal?.show();
    onGeneratePassword();
  }

  async function openEditModal(id) {
    setLoading(true);
    try {
      const res = await fetch(cfg.routes.show + '/' + id, {
        headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin',
      });
      const json = await res.json();
      if (!res.ok || !json.success) throw new Error(json.message || 'Unable to load trainer.');

      resetForm();
      state.editingId = id;
      els.formModalLabel.textContent = 'Edit Trainer';
      document.getElementById('passwordHint').textContent = '(leave blank to keep current)';

      const t = json.data;
      fillForm(t);
      els.bsFormModal?.show();
    } catch (err) {
      toast(err.message, 'error');
    } finally {
      setLoading(false);
    }
  }

  function fillForm(t) {
    document.getElementById('trainerId').value = t.id;
    document.getElementById('first_name').value = t.first_name || '';
    document.getElementById('last_name').value = t.last_name || '';
    document.getElementById('email').value = t.email || '';
    document.getElementById('phone').value = t.phone || '';
    document.getElementById('gender').value = t.gender || '';
    document.getElementById('dob').value = t.dob || '';
    document.getElementById('joining_date').value = t.joining_date || '';
    setSpecializationTags(t.specialization || '');
    document.getElementById('experience').value = t.experience != null ? t.experience : '';
    document.getElementById('certifications').value = t.certifications || '';
    document.getElementById('skills').value = t.skills || '';
    document.getElementById('status').value = t.status || 'active';

    if (t.profile_image_url) {
      document.getElementById('profilePreview').src = t.profile_image_url;
      document.getElementById('profilePreviewWrap').classList.add('is-visible');
    }
    if (t.background_image_url) {
      document.getElementById('backgroundPreview').src = t.background_image_url;
      document.getElementById('backgroundPreviewWrap').classList.add('is-visible');
    }
  }

  function resetForm() {
    els.form?.reset();
    clearErrors();
    setSpecializationTags('');
    document.getElementById('trainerId').value = '';
    document.getElementById('remove_profile_image').value = '0';
    document.getElementById('remove_background_image').value = '0';
    document.getElementById('profilePreviewWrap').classList.remove('is-visible');
    document.getElementById('backgroundPreviewWrap').classList.remove('is-visible');
    document.getElementById('profilePreview').src = '';
    document.getElementById('backgroundPreview').src = '';
    document.getElementById('password').type = 'password';
    document.getElementById('password_confirmation').type = 'password';
  }

  async function onSubmitForm(e) {
    e.preventDefault();
    clearErrors();
    setButtonLoading(true);

    const formData = new FormData(els.form);
    formData.set('specialization', getSpecializationValue());
    const isEdit = !!state.editingId;
    let url = cfg.routes.store;
    let method = 'POST';

    if (isEdit) {
      url = cfg.routes.update + '/' + state.editingId;
      formData.append('_method', 'PUT');
    }

    // Drop empty password on edit so it is not validated as min:8 empty string issues
    if (isEdit && !formData.get('password')) {
      formData.delete('password');
      formData.delete('password_confirmation');
    }

    try {
      const res = await fetch(url, {
        method: method,
        headers: {
          Accept: 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': csrf,
        },
        body: formData,
        credentials: 'same-origin',
      });

      const json = await res.json();

      if (res.status === 422 && json.errors) {
        showErrors(json.errors);
        toast(json.message || 'Please fix the highlighted errors.', 'error');
        return;
      }

      if (!res.ok || !json.success) {
        throw new Error(json.message || 'Something went wrong.');
      }

      toast(json.message || 'Saved successfully.', 'success');
      els.bsFormModal?.hide();
      state.page = isEdit ? state.page : 1;
      await loadTrainers();

      if (!isEdit && json.credentials) {
        state.lastCredentials = json.credentials;
        document.getElementById('credEmail').textContent = json.credentials.email;
        document.getElementById('credPassword').textContent = json.credentials.password;
        els.bsCredentialsModal?.show();
      }
    } catch (err) {
      toast(err.message, 'error');
    } finally {
      setButtonLoading(false);
    }
  }

  async function onGeneratePassword() {
    try {
      const res = await fetch(cfg.routes.generatePassword, {
        headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin',
      });
      const json = await res.json();
      if (!res.ok || !json.success) throw new Error(json.message || 'Could not generate password.');
      document.getElementById('password').value = json.password;
      document.getElementById('password_confirmation').value = json.password;
    } catch (err) {
      toast(err.message, 'error');
    }
  }

  function onCopyCredentials() {
    if (!state.lastCredentials) return;
    const text = 'Email: ' + state.lastCredentials.email + '\nPassword: ' + state.lastCredentials.password;
    navigator.clipboard.writeText(text).then(function () {
      toast('Credentials copied to clipboard.', 'success');
    }).catch(function () {
      toast('Unable to copy credentials.', 'error');
    });
  }

  function confirmDelete(id) {
    Swal.fire({
      title: 'Delete trainer?',
      text: 'This will soft-delete the trainer account. They will no longer be able to log in.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#dc2626',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Yes, delete',
      reverseButtons: true,
    }).then(async function (result) {
      if (!result.isConfirmed) return;

      try {
        const res = await fetch(cfg.routes.destroy + '/' + id, {
          method: 'DELETE',
          headers: {
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrf,
          },
          credentials: 'same-origin',
        });
        const json = await res.json();
        if (!res.ok || !json.success) throw new Error(json.message || 'Delete failed.');
        toast(json.message, 'success');
        loadTrainers();
      } catch (err) {
        toast(err.message, 'error');
      }
    });
  }

  function confirmStatus(id, status) {
    const label = status === 'active' ? 'activate' : 'deactivate';
    Swal.fire({
      title: 'Change status?',
      text: 'Are you sure you want to ' + label + ' this trainer?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#ff8a3d',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Yes, ' + label,
      reverseButtons: true,
    }).then(async function (result) {
      if (!result.isConfirmed) return;

      try {
        const res = await fetch(cfg.routes.status + '/' + id + '/status', {
          method: 'PATCH',
          headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrf,
          },
          body: JSON.stringify({ status: status }),
          credentials: 'same-origin',
        });
        const json = await res.json();
        if (!res.ok || !json.success) throw new Error(json.message || 'Status update failed.');
        toast(json.message, 'success');
        loadTrainers();
      } catch (err) {
        toast(err.message, 'error');
      }
    });
  }

  function showErrors(errors) {
    Object.keys(errors).forEach(function (field) {
      const input = document.getElementById(field) || document.querySelector('[name="' + field + '"]');
      const errorEl = document.querySelector('[data-error="' + field + '"]');
      input?.classList.add('is-invalid-field');
      if (field === 'specialization' && state.specializationTagify?.DOM?.scope) {
        state.specializationTagify.DOM.scope.classList.add('tagify--invalid');
      }
      if (errorEl) {
        errorEl.textContent = errors[field][0];
        errorEl.classList.add('is-visible');
      }
    });
  }

  function clearErrors() {
    document.querySelectorAll('.is-invalid-field').forEach(function (el) {
      el.classList.remove('is-invalid-field');
    });
    document.querySelectorAll('.field-error').forEach(function (el) {
      el.textContent = '';
      el.classList.remove('is-visible');
    });
    state.specializationTagify?.DOM?.scope?.classList.remove('tagify--invalid');
  }

  function setLoading(on) {
    els.overlay?.classList.toggle('is-active', !!on);
  }

  function setButtonLoading(on) {
    els.saveBtn?.classList.toggle('btn-loading', !!on);
    if (els.saveBtn) els.saveBtn.disabled = !!on;
  }

  function toast(message, type) {
    if (typeof window.showToast === 'function') {
      window.showToast(message, type || 'success');
      return;
    }
    alert(message);
  }

  function escapeHtml(str) {
    return String(str ?? '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }
})();
