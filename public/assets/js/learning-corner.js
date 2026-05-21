/**
 * Learning Corner — Smart Tree Knowledge Explorer
 * Handles: tree expand/collapse, AJAX loading, breadcrumb,
 *          search filter, mobile sidebar, and state persistence.
 */
(function () {
  'use strict';

  const cfg        = window.lcConfig || {};
  const AJAX_BASE  = cfg.ajaxBase || '/learning-corner/cat';
  const STORE_KEY  = 'lc_expanded';

  /* ── DOM refs ───────────────────────────────────────────── */
  const sidebar        = document.getElementById('lc-sidebar');
  const backdrop       = document.getElementById('lc-backdrop');
  const fab            = document.getElementById('lc-sidebar-fab');
  const closeBtn       = document.getElementById('lc-sidebar-close');
  const treeSearch     = document.getElementById('lc-tree-search');
  const expandAllBtn   = document.getElementById('lc-expand-all');
  const collapseAllBtn = document.getElementById('lc-collapse-all');
  const breadcrumb     = document.getElementById('lc-breadcrumb');
  const breadcrumbList = document.getElementById('lc-breadcrumb-list');
  const welcome        = document.getElementById('lc-welcome');
  const loadingEl      = document.getElementById('lc-loading');
  const detailEl       = document.getElementById('lc-category-detail');
  const catHeaderEl    = document.getElementById('lc-cat-header');
  const resourceGrid   = document.getElementById('lc-resource-grid');
  const resourceEmpty  = document.getElementById('lc-resource-empty');
  const errorEl        = document.getElementById('lc-error');
  const retryBtn       = document.getElementById('lc-retry');

  /* ── State ──────────────────────────────────────────────── */
  let expandedIds  = readStored();
  let activeId     = null;
  let lastFailId   = null;

  /* ── Boot ───────────────────────────────────────────────── */
  restoreExpanded();
  bindEvents();
  loadFromHash();

  /* ── Event binding ───────────────────────────────────────── */
  function bindEvents() {
    sidebar.addEventListener('click', onTreeClick);

    const quickCats = document.getElementById('lc-quick-cats');
    if (quickCats) {
      quickCats.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-lc-cat]');
        if (btn) loadCat(parseInt(btn.dataset.lcCat, 10));
      });
    }

    if (fab)          fab.addEventListener('click', openSidebar);
    if (closeBtn)     closeBtn.addEventListener('click', closeSidebar);
    if (backdrop)     backdrop.addEventListener('click', closeSidebar);
    if (treeSearch)   treeSearch.addEventListener('input', onSearch);
    if (expandAllBtn) expandAllBtn.addEventListener('click', expandAll);
    if (collapseAllBtn) collapseAllBtn.addEventListener('click', collapseAll);
    if (retryBtn)     retryBtn.addEventListener('click', function () { if (lastFailId) loadCat(lastFailId); });

    window.addEventListener('popstate', loadFromHash);
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeSidebar(); });
  }

  /* ── Tree click handler ─────────────────────────────────── */
  function onTreeClick(e) {
    const toggleBtn = e.target.closest('[data-lc-toggle]');
    if (toggleBtn) {
      e.preventDefault();
      toggleExpand(parseInt(toggleBtn.dataset.lcToggle, 10));
      return;
    }
    const catBtn = e.target.closest('[data-lc-cat]');
    if (catBtn) {
      e.preventDefault();
      const id = parseInt(catBtn.dataset.lcCat, 10);
      loadCat(id);
      if (catBtn.dataset.lcHasChildren === 'true') expandNode(id);
      if (window.innerWidth < 1024) closeSidebar();
    }
  }

  /* ── Expand / collapse ───────────────────────────────────── */
  function toggleExpand(id) {
    expandedIds.has(id) ? collapseNode(id) : (collapseSiblings(id), expandNode(id));
  }

  function expandNode(id) {
    const ch = document.getElementById('lc-children-' + id);
    const tg = sidebar.querySelector('[data-lc-toggle="' + id + '"]');
    const li = document.getElementById('lc-item-' + id);
    if (ch) ch.classList.add('is-open');
    if (tg) { tg.classList.add('is-expanded'); tg.setAttribute('aria-expanded', 'true'); }
    if (li) li.setAttribute('aria-expanded', 'true');
    expandedIds.add(id);
    saveStored();
  }

  function collapseNode(id) {
    const ch = document.getElementById('lc-children-' + id);
    const tg = sidebar.querySelector('[data-lc-toggle="' + id + '"]');
    const li = document.getElementById('lc-item-' + id);
    if (ch) ch.classList.remove('is-open');
    if (tg) { tg.classList.remove('is-expanded'); tg.setAttribute('aria-expanded', 'false'); }
    if (li) li.setAttribute('aria-expanded', 'false');
    expandedIds.delete(id);
    saveStored();
  }

  function collapseSiblings(id) {
    const item = document.getElementById('lc-item-' + id);
    if (!item) return;
    const parent = item.parentElement;
    if (!parent) return;
    parent.querySelectorAll('[data-lc-item-id]').forEach(function (sib) {
      const sibId = parseInt(sib.dataset.lcItemId, 10);
      if (sibId !== id && expandedIds.has(sibId)) collapseNode(sibId);
    });
  }

  function expandAll() {
    sidebar.querySelectorAll('[data-lc-toggle]').forEach(function (tg) {
      expandNode(parseInt(tg.dataset.lcToggle, 10));
    });
  }

  function collapseAll() {
    [...expandedIds].forEach(collapseNode);
  }

  /* ── Persist expanded IDs ────────────────────────────────── */
  function saveStored() {
    try { localStorage.setItem(STORE_KEY, JSON.stringify([...expandedIds])); } catch (_) {}
  }

  function readStored() {
    try { const r = localStorage.getItem(STORE_KEY); return new Set(r ? JSON.parse(r) : []); } catch (_) { return new Set(); }
  }

  function restoreExpanded() {
    expandedIds.forEach(function (id) {
      const ch = document.getElementById('lc-children-' + id);
      const tg = sidebar.querySelector('[data-lc-toggle="' + id + '"]');
      if (ch) ch.classList.add('is-open');
      if (tg) { tg.classList.add('is-expanded'); tg.setAttribute('aria-expanded', 'true'); }
    });
  }

  /* ── Tree search ─────────────────────────────────────────── */
  function onSearch() {
    const q = treeSearch.value.trim().toLowerCase();

    sidebar.querySelectorAll('.lc-tree-item').forEach(function (item) {
      const label   = item.querySelector('.lc-tree-label');
      const text    = label ? label.textContent.toLowerCase() : '';
      const matches = q === '' || text.includes(q) || hasMatchingDesc(item, q);

      item.classList.toggle('is-hidden', !matches);
      item.classList.toggle('is-search-match', q !== '' && text.includes(q));
    });

    if (q !== '') {
      sidebar.querySelectorAll('.lc-tree-children').forEach(function (el) {
        if (el.querySelector('.lc-tree-item:not(.is-hidden)')) el.classList.add('is-open');
      });
    }
  }

  function hasMatchingDesc(item, q) {
    return [...item.querySelectorAll('.lc-tree-label')].some(function (l) {
      return l.textContent.toLowerCase().includes(q);
    });
  }

  /* ── AJAX category load ──────────────────────────────────── */
  function loadCat(id) {
    setActive(id);
    history.pushState({ catId: id }, '', '#cat-' + id);
    showLoading();

    fetch(AJAX_BASE + '/' + id, {
      headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' },
    })
      .then(function (r) { if (!r.ok) throw new Error(r.status); return r.json(); })
      .then(renderDetail)
      .catch(function () { lastFailId = id; showErr(); });
  }

  function loadFromHash() {
    const m = window.location.hash.match(/^#cat-(\d+)$/);
    if (m) loadCat(parseInt(m[1], 10));
  }

  /* ── Active node highlight ───────────────────────────────── */
  function setActive(id) {
    if (activeId) {
      const prev = sidebar.querySelector('[data-lc-cat="' + activeId + '"]');
      if (prev) prev.classList.remove('is-active');
    }
    const cur = sidebar.querySelector('[data-lc-cat="' + id + '"]');
    if (cur) {
      cur.classList.add('is-active');
      cur.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
    activeId = id;
  }

  /* ── Panel states ────────────────────────────────────────── */
  function showLoading() {
    hide(welcome); hide(errorEl); hide(detailEl);
    show(loadingEl);
    if (breadcrumb) breadcrumb.hidden = true;
  }

  function showErr() {
    hide(welcome); hide(loadingEl); hide(detailEl);
    show(errorEl);
  }

  /* ── Render detail panel ─────────────────────────────────── */
  function renderDetail(data) {
    hide(loadingEl); hide(welcome); hide(errorEl);
    drawBreadcrumb(data.breadcrumb || []);
    drawHeader(data.category, data.total);
    drawResources(data.resources || []);
    show(detailEl);
  }

  function drawBreadcrumb(crumbs) {
    if (!breadcrumb || !breadcrumbList) return;
    if (!crumbs.length) { breadcrumb.hidden = true; return; }

    breadcrumbList.innerHTML = crumbs.map(function (c, i) {
      const isLast = i === crumbs.length - 1;
      return '<li class="lc-breadcrumb__item">' +
        (isLast
          ? esc(c.name)
          : '<span class="lc-breadcrumb__link" role="button" tabindex="0" data-lc-cat="' + c.id + '">' + esc(c.name) + '</span>') +
        '</li>';
    }).join('');

    breadcrumb.hidden = false;

    breadcrumbList.querySelectorAll('[data-lc-cat]').forEach(function (el) {
      el.addEventListener('click', function () { loadCat(parseInt(el.dataset.lcCat, 10)); });
      el.addEventListener('keydown', function (e) { if (e.key === 'Enter') loadCat(parseInt(el.dataset.lcCat, 10)); });
    });
  }

  function drawHeader(cat, total) {
    if (!catHeaderEl) return;
    catHeaderEl.innerHTML =
      '<div class="lc-cat-header__top">' +
        '<span class="lc-cat-header__icon"><i class="' + esc(cat.icon || 'bi bi-folder') + '" aria-hidden="true"></i></span>' +
        '<h2 class="lc-cat-header__name">' + esc(cat.name) + '</h2>' +
      '</div>' +
      '<div class="lc-cat-header__meta">' +
        '<span class="lc-cat-header__count"><i class="bi bi-collection" aria-hidden="true"></i>&nbsp;' +
          total + '&nbsp;resource' + (total !== 1 ? 's' : '') +
        '</span>' +
      '</div>' +
      (cat.description ? '<p class="lc-cat-header__desc">' + esc(cat.description) + '</p>' : '');
  }

  function drawResources(resources) {
    if (!resourceGrid || !resourceEmpty) return;
    if (!resources.length) {
      resourceGrid.innerHTML = '';
      show(resourceEmpty);
      return;
    }
    hide(resourceEmpty);
    resourceGrid.innerHTML = resources.map(buildCard).join('');
  }

  function buildCard(r) {
    const label    = getTypeLabel(r.m_type);
    const icon     = getTypeIcon(r.m_type);
    const initials = (r.m_type || 'LC').slice(0, 2).toUpperCase();
    const date     = r.date ? fmtDate(r.date) : '';

    const coverHtml = r.image_url
      ? '<img src="' + esc(r.image_url) + '" alt="" loading="lazy">'
      : '<span class="lc-resource-card__cover-label" aria-hidden="true">' + esc(initials) + '</span>';

    const href = r.link ? esc(r.link) : '';
    const cardTag = href ? 'a' : 'article';
    const cardAttrs = href
      ? ' href="' + href + '" target="_blank" rel="noopener noreferrer"'
      : '';

    return '<' + cardTag + ' class="lc-resource-card"' + cardAttrs + '>' +
      '<div class="lc-resource-card__cover">' +
        coverHtml +
        '<span class="lc-resource-card__type-badge"><i class="' + icon + '" aria-hidden="true"></i>&nbsp;' + esc(label) + '</span>' +
      '</div>' +
      '<div class="lc-resource-card__body">' +
        '<div class="lc-resource-card__tags"><span class="lc-resource-card__tag">' + esc(r.cat_name || '') + '</span></div>' +
        '<h3 class="lc-resource-card__title">' + esc(r.title) + '</h3>' +
        (r.content ? '<p class="lc-resource-card__desc">' + esc(r.content) + '</p>' : '') +
        '<div class="lc-resource-card__footer">' +
          (date ? '<span class="lc-resource-card__date"><i class="bi bi-calendar3" aria-hidden="true"></i>&nbsp;' + esc(date) + '</span>' : '<span></span>') +
          (href ? '<span class="lc-resource-card__link">Open&nbsp;<i class="bi bi-arrow-up-right" aria-hidden="true"></i></span>' : '') +
        '</div>' +
      '</div>' +
    '</' + cardTag + '>';
  }

  /* ── Mobile sidebar ──────────────────────────────────────── */
  function openSidebar() {
    sidebar.classList.add('is-open');
    backdrop.classList.add('is-visible');
    backdrop.removeAttribute('aria-hidden');
    document.body.style.overflow = 'hidden';
  }

  function closeSidebar() {
    sidebar.classList.remove('is-open');
    backdrop.classList.remove('is-visible');
    backdrop.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }

  /* ── Helpers ─────────────────────────────────────────────── */
  function show(el) { if (el) { el.hidden = false; el.style.display = ''; } }
  function hide(el) { if (el) { el.hidden = true; } }

  function esc(str) {
    return String(str || '')
      .replace(/&/g, '&amp;').replace(/</g, '&lt;')
      .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }

  function fmtDate(d) {
    try { return new Date(d).toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' }); }
    catch (_) { return d; }
  }

  function getTypeLabel(t) {
    return { book: 'Book', posters: 'Poster', 'mobile kunji': 'Mobile Kunji', video: 'Video' }[t] || (t || 'Resource');
  }

  function getTypeIcon(t) {
    return { book: 'bi bi-book', posters: 'bi bi-image', 'mobile kunji': 'bi bi-phone', video: 'bi bi-play-circle' }[t] || 'bi bi-file-earmark';
  }

})();
