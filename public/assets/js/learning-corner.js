/**
 * Learning Corner — Smart Tree Explorer
 * Handles: tree expand/collapse, AJAX category loading,
 *          breadcrumb, search filter, mobile sidebar, state persistence.
 */
(function () {
  'use strict';

  const cfg = window.lcConfig || {};
  const AJAX_BASE = cfg.ajaxBase || '/learning-corner/cat';
  const STORAGE_KEY = 'lc_expanded';

  /* ─── DOM refs ─────────────────────────────────────────── */
  const sidebar       = document.getElementById('lc-sidebar');
  const backdrop      = document.getElementById('lc-backdrop');
  const fab           = document.getElementById('lc-sidebar-fab');
  const closeBtn      = document.getElementById('lc-sidebar-close');
  const treeSearch    = document.getElementById('lc-tree-search');
  const expandAllBtn  = document.getElementById('lc-expand-all');
  const collapseAllBtn= document.getElementById('lc-collapse-all');
  const breadcrumb    = document.getElementById('lc-breadcrumb');
  const breadcrumbList= document.getElementById('lc-breadcrumb-list');
  const contentBody   = document.getElementById('lc-content-body');
  const welcome       = document.getElementById('lc-welcome');
  const loading       = document.getElementById('lc-loading');
  const detail        = document.getElementById('lc-category-detail');
  const catHeader     = document.getElementById('lc-cat-header');
  const resourceGrid  = document.getElementById('lc-resource-grid');
  const resourceEmpty = document.getElementById('lc-resource-empty');
  const errorBox      = document.getElementById('lc-error');
  const retryBtn      = document.getElementById('lc-retry');

  /* ─── State ─────────────────────────────────────────────── */
  let expandedIds = loadExpanded();
  let activeId    = null;
  let lastFailedId = null;

  /* ─── Init ──────────────────────────────────────────────── */
  restoreExpandedState();
  bindEvents();
  loadFromHash();

  /* ─── Bind events ────────────────────────────────────────── */
  function bindEvents() {
    /* Tree clicks (delegated) */
    sidebar.addEventListener('click', handleTreeClick);

    /* Welcome quick-cat cards */
    const quickCats = document.getElementById('lc-quick-cats');
    if (quickCats) {
      quickCats.addEventListener('click', function (e) {
        const card = e.target.closest('[data-lc-cat]');
        if (card) loadCategory(parseInt(card.dataset.lcCat, 10));
      });
    }

    /* Mobile sidebar */
    if (fab)       fab.addEventListener('click', openSidebar);
    if (closeBtn)  closeBtn.addEventListener('click', closeSidebar);
    if (backdrop)  backdrop.addEventListener('click', closeSidebar);

    /* Tree search */
    if (treeSearch) treeSearch.addEventListener('input', handleTreeSearch);

    /* Expand / collapse all */
    if (expandAllBtn)   expandAllBtn.addEventListener('click', expandAll);
    if (collapseAllBtn) collapseAllBtn.addEventListener('click', collapseAll);

    /* Retry button */
    if (retryBtn) retryBtn.addEventListener('click', function () {
      if (lastFailedId) loadCategory(lastFailedId);
    });

    /* Browser back/forward */
    window.addEventListener('popstate', loadFromHash);

    /* Escape key */
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeSidebar();
    });
  }

  /* ─── Tree click handler ─────────────────────────────────── */
  function handleTreeClick(e) {
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
      loadCategory(id);
      if (catBtn.dataset.lcHasChildren === 'true') expandNode(id);
      if (window.innerWidth < 1024) closeSidebar();
    }
  }

  /* ─── Expand / collapse ──────────────────────────────────── */
  function toggleExpand(nodeId) {
    if (expandedIds.has(nodeId)) {
      collapseNode(nodeId);
    } else {
      collapseNodeSiblings(nodeId);
      expandNode(nodeId);
    }
  }

  function expandNode(nodeId) {
    const children = document.getElementById('lc-children-' + nodeId);
    const toggle   = sidebar.querySelector('[data-lc-toggle="' + nodeId + '"]');
    if (children) children.classList.add('is-open');
    if (toggle) {
      toggle.classList.add('is-expanded');
      toggle.setAttribute('aria-expanded', 'true');
    }
    expandedIds.add(nodeId);
    saveExpanded();
  }

  function collapseNode(nodeId) {
    const children = document.getElementById('lc-children-' + nodeId);
    const toggle   = sidebar.querySelector('[data-lc-toggle="' + nodeId + '"]');
    if (children) children.classList.remove('is-open');
    if (toggle) {
      toggle.classList.remove('is-expanded');
      toggle.setAttribute('aria-expanded', 'false');
    }
    expandedIds.delete(nodeId);
    saveExpanded();
  }

  function collapseNodeSiblings(nodeId) {
    const item = document.getElementById('lc-item-' + nodeId);
    if (!item) return;
    const parentList = item.parentElement;
    if (!parentList) return;

    parentList.querySelectorAll('[data-lc-item-id]').forEach(function (sibling) {
      const sibId = parseInt(sibling.dataset.lcItemId, 10);
      if (sibId !== nodeId && expandedIds.has(sibId)) {
        collapseNode(sibId);
      }
    });
  }

  function expandAll() {
    sidebar.querySelectorAll('[data-lc-toggle]').forEach(function (toggle) {
      expandNode(parseInt(toggle.dataset.lcToggle, 10));
    });
  }

  function collapseAll() {
    [...expandedIds].forEach(function (id) { collapseNode(id); });
  }

  /* ─── State persistence ──────────────────────────────────── */
  function saveExpanded() {
    try {
      localStorage.setItem(STORAGE_KEY, JSON.stringify([...expandedIds]));
    } catch (_) {}
  }

  function loadExpanded() {
    try {
      const raw = localStorage.getItem(STORAGE_KEY);
      return new Set(raw ? JSON.parse(raw) : []);
    } catch (_) {
      return new Set();
    }
  }

  function restoreExpandedState() {
    expandedIds.forEach(function (id) {
      const children = document.getElementById('lc-children-' + id);
      const toggle   = sidebar.querySelector('[data-lc-toggle="' + id + '"]');
      if (children) children.classList.add('is-open');
      if (toggle) {
        toggle.classList.add('is-expanded');
        toggle.setAttribute('aria-expanded', 'true');
      }
    });
  }

  /* ─── Tree search / filter ───────────────────────────────── */
  function handleTreeSearch() {
    const q = treeSearch.value.trim().toLowerCase();

    sidebar.querySelectorAll('.lc-tree-item').forEach(function (item) {
      const label = item.querySelector('.lc-tree-label');
      const text  = label ? label.textContent.toLowerCase() : '';
      const matches = text.includes(q);

      item.classList.toggle('is-search-match', matches && q !== '');
      item.classList.toggle('is-hidden', q !== '' && !matches && !hasMatchingDescendant(item, q));
    });

    if (q !== '') {
      sidebar.querySelectorAll('.lc-tree-children').forEach(function (el) {
        const hasVisible = el.querySelector('.lc-tree-item:not(.is-hidden)');
        if (hasVisible) el.classList.add('is-open');
      });
    }
  }

  function hasMatchingDescendant(item, q) {
    const labels = item.querySelectorAll('.lc-tree-label');
    return [...labels].some(function (l) { return l.textContent.toLowerCase().includes(q); });
  }

  /* ─── AJAX category loading ──────────────────────────────── */
  function loadCategory(catId) {
    setActiveNode(catId);
    history.pushState({ catId: catId }, '', '#cat-' + catId);
    showLoading();

    fetch(AJAX_BASE + '/' + catId, {
      headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
    })
      .then(function (res) {
        if (!res.ok) throw new Error('HTTP ' + res.status);
        return res.json();
      })
      .then(function (data) {
        renderDetail(data);
      })
      .catch(function () {
        lastFailedId = catId;
        showError();
      });
  }

  function loadFromHash() {
    const hash = window.location.hash;
    if (hash && hash.startsWith('#cat-')) {
      const catId = parseInt(hash.slice(5), 10);
      if (catId) loadCategory(catId);
    }
  }

  /* ─── Active node ────────────────────────────────────────── */
  function setActiveNode(catId) {
    if (activeId) {
      const prev = sidebar.querySelector('[data-lc-cat="' + activeId + '"]');
      if (prev) prev.classList.remove('is-active');
    }

    const current = sidebar.querySelector('[data-lc-cat="' + catId + '"]');
    if (current) {
      current.classList.add('is-active');
      current.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    activeId = catId;
  }

  /* ─── Render functions ───────────────────────────────────── */
  function showLoading() {
    setVisible(welcome, false);
    setVisible(loading, true);
    setVisible(detail, false);
    setVisible(errorBox, false);
    if (breadcrumb) breadcrumb.hidden = true;
  }

  function showError() {
    setVisible(welcome, false);
    setVisible(loading, false);
    setVisible(detail, false);
    setVisible(errorBox, true);
  }

  function renderDetail(data) {
    setVisible(loading, false);
    setVisible(welcome, false);
    setVisible(errorBox, false);

    renderBreadcrumb(data.breadcrumb || []);
    renderCatHeader(data.category, data.total);
    renderResources(data.resources || []);

    setVisible(detail, true);
  }

  function renderBreadcrumb(crumbs) {
    if (!breadcrumb || !breadcrumbList) return;

    if (!crumbs.length) {
      breadcrumb.hidden = true;
      return;
    }

    breadcrumbList.innerHTML = crumbs.map(function (crumb, idx) {
      const isLast = idx === crumbs.length - 1;
      return '<li class="lc-breadcrumb__item">' +
        (isLast
          ? esc(crumb.name)
          : '<span class="lc-breadcrumb__link" role="button" tabindex="0" data-lc-cat="' + crumb.id + '">' + esc(crumb.name) + '</span>'
        ) +
        '</li>';
    }).join('');

    breadcrumb.hidden = false;

    breadcrumbList.querySelectorAll('[data-lc-cat]').forEach(function (el) {
      el.addEventListener('click', function () {
        loadCategory(parseInt(el.dataset.lcCat, 10));
      });
      el.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') loadCategory(parseInt(el.dataset.lcCat, 10));
      });
    });
  }

  function renderCatHeader(cat, total) {
    if (!catHeader) return;

    catHeader.innerHTML =
      '<div class="lc-cat-header__top">' +
        '<span class="lc-cat-header__icon"><i class="' + esc(cat.icon) + '" aria-hidden="true"></i></span>' +
        '<h2 class="lc-cat-header__name">' + esc(cat.name) + '</h2>' +
      '</div>' +
      '<div class="lc-cat-header__meta">' +
        '<span class="lc-cat-header__count"><i class="bi bi-collection" aria-hidden="true"></i>' + total + ' resource' + (total !== 1 ? 's' : '') + '</span>' +
      '</div>' +
      (cat.description ? '<p class="lc-cat-header__desc">' + esc(cat.description) + '</p>' : '');
  }

  function renderResources(resources) {
    if (!resourceGrid || !resourceEmpty) return;

    if (!resources.length) {
      resourceGrid.innerHTML = '';
      setVisible(resourceEmpty, true);
      return;
    }

    setVisible(resourceEmpty, false);
    resourceGrid.innerHTML = resources.map(buildResourceCard).join('');
  }

  function buildResourceCard(res) {
    const typeIcon  = getTypeIcon(res.m_type);
    const typeLabel = getTypeLabel(res.m_type);
    const dateStr   = res.date ? formatDate(res.date) : '';
    const initials  = (res.m_type || 'LC').slice(0, 2).toUpperCase();

    const coverHtml = res.image_url
      ? '<img src="' + esc(res.image_url) + '" alt="" loading="lazy" />'
      : '<span class="lc-resource-card__cover-label" aria-hidden="true">' + esc(initials) + '</span>';

    return '<article class="lc-resource-card">' +
      '<div class="lc-resource-card__cover">' +
        coverHtml +
        '<span class="lc-resource-card__type-badge"><i class="' + typeIcon + '" aria-hidden="true"></i> ' + esc(typeLabel) + '</span>' +
      '</div>' +
      '<div class="lc-resource-card__body">' +
        '<div class="lc-resource-card__tags">' +
          '<span class="lc-resource-card__tag">' + esc(res.cat_name || '') + '</span>' +
        '</div>' +
        '<h3 class="lc-resource-card__title">' + esc(res.title) + '</h3>' +
        (res.content ? '<p class="lc-resource-card__desc">' + esc(res.content) + '</p>' : '') +
        '<div class="lc-resource-card__footer">' +
          (dateStr ? '<span class="lc-resource-card__date"><i class="bi bi-calendar3" aria-hidden="true"></i> ' + esc(dateStr) + '</span>' : '<span></span>') +
          (res.link ? '<a class="lc-resource-card__link" href="' + esc(res.link) + '" target="_blank" rel="noopener noreferrer">' +
            'Open <i class="bi bi-arrow-up-right" aria-hidden="true"></i>' +
          '</a>' : '') +
        '</div>' +
      '</div>' +
    '</article>';
  }

  /* ─── Mobile sidebar ─────────────────────────────────────── */
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

  /* ─── Helpers ────────────────────────────────────────────── */
  function setVisible(el, visible) {
    if (!el) return;
    el.hidden = !visible;
  }

  function esc(str) {
    return String(str || '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  function formatDate(dateStr) {
    try {
      const d = new Date(dateStr);
      return d.toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' });
    } catch (_) {
      return dateStr;
    }
  }

  function getTypeLabel(type) {
    const map = {
      'book': 'Book',
      'posters': 'Poster',
      'mobile kunji': 'Mobile Kunji',
      'video': 'Video',
    };
    return map[type] || (type || 'Resource');
  }

  function getTypeIcon(type) {
    const map = {
      'book': 'bi bi-book',
      'posters': 'bi bi-image',
      'mobile kunji': 'bi bi-phone',
      'video': 'bi bi-play-circle',
    };
    return map[type] || 'bi bi-file-earmark';
  }

})();
