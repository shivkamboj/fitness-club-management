/* eslint-disable no-undef */
(function () {
  'use strict';

  const cfg = window.gwbNotifications;
  if (!cfg || !cfg.routes) return;

  const routes = cfg.routes;
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

  const iconsByType = {
    success: { icon: 'fa-circle-check', color: '#22c55e' },
    warning: { icon: 'fa-triangle-exclamation', color: '#fbbf24' },
    error: { icon: 'fa-circle-xmark', color: '#ef4444' },
    information: { icon: 'fa-circle-info', color: '#60a5fa' },
  };

  function escapeHtml(str) {
    return String(str ?? '')
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#039;');
  }

  async function apiGet(url) {
    const res = await fetch(url, { credentials: 'same-origin' });
    if (!res.ok) throw new Error(`GET ${url} failed: ${res.status}`);
    return res.json();
  }

  async function apiPost(url, body) {
    const res = await fetch(url, {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        'Content-Type': 'application/json',
        ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
      },
      body: JSON.stringify(body ?? {}),
    });
    if (!res.ok) throw new Error(`POST ${url} failed: ${res.status}`);
    return res.json();
  }

  function typeBadge(type) {
    const t = iconsByType[type] ? type : 'information';
    const meta = iconsByType[t];
    return meta;
  }

  function renderNotificationItem(n, { showActions }) {
    const meta = typeBadge(n.type);
    const isRead = Boolean(n.is_read);

    const dotClass = isRead ? 'bg-secondary' : 'bg-orange';
    const dotTitle = isRead ? `Read ${n.read_at_human ?? ''}` : 'Unread';

    const actionsHtml = showActions
      ? `
        ${isRead ? '' : `<button type="button" class="btn-gwb-secondary btn-sm js-mark-read-single" data-id="${n.id}">
          <i class="fa-solid fa-check me-1"></i> Mark as read
        </button>`}
      `
      : '';

        //    <div class="gwb-notification-icon" style="color:${meta.color}">
        //   <i class="fa-solid ${meta.icon}"></i>
        // </div>
    return `
      <div class="gwb-notification-item ${isRead ? 'is-read' : 'is-unread'}" data-notification-id="${n.id}">
        <div class="gwb-notification-dot ${dotClass}" title="${escapeHtml(dotTitle)}"></div>

        <div class="gwb-notification-body">
          <div class="gwb-notification-title-row">
            <div class="gwb-notification-title">${escapeHtml(n.title)}</div>
            <div class="gwb-notification-time text-muted small">${escapeHtml(n.created_at_human ?? '')}</div>
          </div>

          <div class="gwb-notification-message">${escapeHtml(n.message)}</div>

          <div class="gwb-notification-meta small">
            <span class="text-muted">${escapeHtml(n.module ?? '')}</span>
            ${isRead ? `<span class="text-muted">• Read: ${escapeHtml(n.read_at_human ?? '')}</span>` : `<span class="text-warning fw-600">• Unread</span>`}
          </div>
        </div>

        ${actionsHtml ? `<div class="gwb-notification-actions d-flex align-items-start justify-content-end">${actionsHtml}</div>` : ''}
      </div>
    `;
  }

  function getUnreadIdsFromLatestList() {
    const unreadEls = document.querySelectorAll('#notificationsDropdownList .gwb-notification-item.is-unread');
    return Array.from(unreadEls).map((el) => el.getAttribute('data-notification-id')).filter(Boolean);
  }

  async function loadNavbarDropdown() {
    const listWrap = document.getElementById('notificationsDropdownList');
    const loadingEl = document.getElementById('notificationsDropdownLoading');
    const emptyEl = document.getElementById('notificationsDropdownEmpty');
    const badgeEl = document.getElementById('notificationsUnreadBadge');
    const markAsReadBtn = document.getElementById('notificationsMarkAsReadBtn');
    const markAllBtn = document.getElementById('notificationsMarkAllReadBtnNavbar');

    if (!listWrap || !loadingEl || !emptyEl || !badgeEl) return;

    loadingEl.classList.remove('d-none');
    emptyEl.classList.add('d-none');
    listWrap.innerHTML = '';

    try {
      const unreadRes = await apiGet(`${routes.unreadCount}`);
      const unreadCount = unreadRes?.unread_count ?? 0;

      if (unreadCount > 0) {
        badgeEl.textContent = unreadCount;
        badgeEl.classList.remove('d-none');
      } else {
        badgeEl.textContent = '0';
        badgeEl.classList.add('d-none');
      }

      const latestRes = await apiGet(`${routes.latest}?limit=5`);
      const items = latestRes?.data ?? [];

      loadingEl.classList.add('d-none');

      if (!items.length) {
        emptyEl.classList.remove('d-none');
        if (markAsReadBtn) markAsReadBtn.disabled = true;
        if (markAllBtn) markAllBtn.disabled = false;
        return;
      }

      listWrap.innerHTML = items.map((n) => renderNotificationItem(n, { showActions: false })).join('');

      const unreadIds = getUnreadIdsFromLatestList();
      if (markAsReadBtn) markAsReadBtn.disabled = unreadIds.length === 0;

      listWrap.querySelectorAll('.gwb-notification-item').forEach((el) => {
        el.addEventListener('click', async () => {
          const id = el.getAttribute('data-notification-id');
          const isUnread = el.classList.contains('is-unread');
          if (!id || !isUnread) return;

          await apiPost(routes.markRead, { ids: [Number(id)] });
          await loadNavbarDropdown();
        }, { once: false });
      });
    } catch (error) {
      console.error('Failed to load notifications:', error);
      loadingEl.classList.add('d-none');
      listWrap.innerHTML = '';
      emptyEl.classList.remove('d-none');
      emptyEl.innerHTML = `
        <i class="fa-regular fa-bell-slash fs-4 text-muted"></i>
        <div class="small text-muted mt-2">Unable to load notifications right now.</div>
      `;
      badgeEl.classList.add('d-none');
      if (markAsReadBtn) markAsReadBtn.disabled = true;
    }
  }

  async function markNavbarAsRead() {
    const unreadIds = getUnreadIdsFromLatestList();
    if (!unreadIds.length) return;
    await apiPost(routes.markRead, { ids: unreadIds.map((x) => Number(x)) });
    await loadNavbarDropdown();
  }

  async function markNavbarAllAsRead() {
    await apiPost(routes.markAllRead);
    await loadNavbarDropdown();
  }

  function initNavbarDropdown() {
    if (!document.getElementById('notificationsBellBtn')) return;

    const markAsReadBtn = document.getElementById('notificationsMarkAsReadBtn');
    const markAllBtn = document.getElementById('notificationsMarkAllReadBtnNavbar');
    const dropdownMenu = document.querySelector('.notifications-dropdown-menu');

    if (markAsReadBtn) markAsReadBtn.addEventListener('click', () => markNavbarAsRead().catch(console.error));
    if (markAllBtn) markAllBtn.addEventListener('click', () => markNavbarAllAsRead().catch(console.error));

    // Load immediately, then re-load whenever dropdown opens.
    loadNavbarDropdown().catch(console.error);

    const bellBtn = document.getElementById('notificationsBellBtn');
    bellBtn?.addEventListener('shown.bs.dropdown', () => {
      if (dropdownMenu && window.innerWidth <= 575) {
        dropdownMenu.style.position = 'fixed';
        dropdownMenu.style.top = 'calc(var(--topbar-height, 70px) + 0.5rem)';
        dropdownMenu.style.left = '0.75rem';
        dropdownMenu.style.right = '0.75rem';
        dropdownMenu.style.transform = 'none';
        dropdownMenu.style.width = 'auto';
      }
      loadNavbarDropdown().catch(console.error);
    });
  }

  // ────────────────────────────────────────────────────────────────────────────
  // View All Notifications page (AJAX pagination)
  // ────────────────────────────────────────────────────────────────────────────
  async function loadAllPage({ page, perPage, append }) {
    const listEl = document.getElementById('notificationsAllList');
    const loadingEl = document.getElementById('notificationsAllLoading');
    const emptyEl = document.getElementById('notificationsAllEmpty');
    const loadMoreBtn = document.getElementById('notificationsLoadMoreBtn');

    if (!listEl || !loadingEl || !emptyEl) return;

    loadingEl.classList.remove('d-none');
    emptyEl.classList.add('d-none');
    if (!append) listEl.innerHTML = '';

    const url = `${routes.list}?per_page=${encodeURIComponent(perPage)}&page=${encodeURIComponent(page)}`;
    try {
      const res = await apiGet(url);

      const items = res?.data ?? [];
      const meta = res?.meta ?? {};

      if (!items.length && page === 1) {
        emptyEl.classList.remove('d-none');
      } else {
        listEl.innerHTML = append ? listEl.innerHTML + items.map((n) => renderNotificationItem(n, { showActions: true })).join('') : items.map((n) => renderNotificationItem(n, { showActions: true })).join('');
      }

      if (loadMoreBtn) {
        const lastPage = Number(meta.last_page ?? 1);
        loadMoreBtn.style.display = page < lastPage && items.length > 0 ? 'inline-flex' : 'none';
      }
    } catch (error) {
      console.error('Failed to load notifications page:', error);
      if (page === 1) {
        emptyEl.classList.remove('d-none');
        emptyEl.innerHTML = `
          <i class="fa-regular fa-bell-slash fs-4 text-muted"></i>
          <div class="mt-2 small text-muted">Unable to load notifications right now.</div>
        `;
      }
    } finally {
      loadingEl.classList.add('d-none');
    }
  }

  function initAllNotificationsPage() {
    const listEl = document.getElementById('notificationsAllList');
    if (!listEl) return;

    const markAllBtn = document.getElementById('notificationsMarkAllReadBtn');
    const loadMoreBtn = document.getElementById('notificationsLoadMoreBtn');

    const state = { page: 1, perPage: 15, lastPage: 1 };

    // Single delegated handler to avoid duplicate listeners on each "load more".
    listEl.addEventListener('click', async (e) => {
      const btn = e.target.closest('.js-mark-read-single');
      if (!btn) return;
      e.preventDefault();
      e.stopPropagation();

      const id = btn.getAttribute('data-id');
      if (!id) return;

      await apiPost(routes.markRead, { ids: [Number(id)] });
      // Reload current page to reflect state.
      await loadAllPage({ page: state.page, perPage: state.perPage, append: false });
      await loadNavbarDropdown().catch(() => {});
    });

    if (markAllBtn) {
      markAllBtn.addEventListener('click', async () => {
        await apiPost(routes.markAllRead);
        state.page = 1;
        await loadAllPage({ page: state.page, perPage: state.perPage, append: false });
        // Also update navbar badge if present.
        await loadNavbarDropdown().catch(() => {});
      });
    }

    if (loadMoreBtn) {
      loadMoreBtn.addEventListener('click', async () => {
        state.page += 1;
        await loadAllPage({ page: state.page, perPage: state.perPage, append: true });
      });
    }

    // Initial load
    loadAllPage({ page: state.page, perPage: state.perPage, append: false }).catch(console.error);
  }

  document.addEventListener('DOMContentLoaded', function () {
    initNavbarDropdown();
    initAllNotificationsPage();
  });
})();

