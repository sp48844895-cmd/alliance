/**
 * Learning Corner — topic browsing with hero, pills, and resource cards.
 */
(function () {
  'use strict';

  const cfg = window.lcConfig || {};
  const AJAX_BASE = cfg.ajaxBase || '/learning-corner/cat';

  const backBtn = document.getElementById('lc-back-all');
  const breadcrumb = document.getElementById('lc-breadcrumb');
  const breadcrumbList = document.getElementById('lc-breadcrumb-list');
  const toolbar = document.getElementById('lc-toolbar');
  const welcome = document.getElementById('lc-welcome');
  const loadingEl = document.getElementById('lc-loading');
  const detailEl = document.getElementById('lc-category-detail');
  const catHeaderEl = document.getElementById('lc-cat-header');
  const resourceGrid = document.getElementById('lc-resource-grid');
  const resourceEmpty = document.getElementById('lc-resource-empty');
  const errorEl = document.getElementById('lc-error');
  const retryBtn = document.getElementById('lc-retry');
  const quickCats = document.getElementById('lc-quick-cats');
  const topicRail = document.getElementById('lc-topic-rail');

  let activeId = null;
  let lastFailId = null;
  let inDetail = false;

  bindEvents();
  loadFromHash();

  function bindEvents() {
    if (quickCats) {
      quickCats.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-lc-cat]');
        if (btn) loadCat(parseInt(btn.dataset.lcCat, 10));
      });
    }

    if (topicRail) {
      topicRail.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-lc-cat]');
        if (btn) loadCat(parseInt(btn.dataset.lcCat, 10));
      });
    }

    if (backBtn) {
      backBtn.addEventListener('click', showHome);
    }

    if (retryBtn) {
      retryBtn.addEventListener('click', function () {
        if (lastFailId) loadCat(lastFailId);
      });
    }

    window.addEventListener('popstate', onPopState);
  }

  function onPopState() {
    const m = window.location.hash.match(/^#cat-(\d+)$/);
    if (m) {
      loadCat(parseInt(m[1], 10), false);
    } else {
      showHome(false);
    }
  }

  function showHome(pushHistory) {
    if (pushHistory !== false) {
      history.pushState({}, '', window.location.pathname + window.location.search);
    }
    inDetail = false;
    activeId = null;
    setActive(null);
    hide(loadingEl);
    hide(detailEl);
    hide(errorEl);
    hide(backBtn);
    hide(breadcrumb);
    show(welcome);
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  function loadCat(id, pushHistory) {
    if (pushHistory !== false) {
      history.pushState({ catId: id }, '', '#cat-' + id);
    }
    inDetail = true;
    setActive(id);
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
    if (m) loadCat(parseInt(m[1], 10), false);
  }

  function setActive(id) {
    document.querySelectorAll('[data-lc-cat].is-active').forEach(function (el) {
      el.classList.remove('is-active');
      if (el.getAttribute('role') === 'tab') {
        el.setAttribute('aria-selected', 'false');
      }
    });

    if (!id) return;

    document.querySelectorAll('[data-lc-cat="' + id + '"]').forEach(function (el) {
      el.classList.add('is-active');
      if (el.getAttribute('role') === 'tab') {
        el.setAttribute('aria-selected', 'true');
        el.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
      }
    });

    activeId = id;
  }

  function showLoading() {
    hide(welcome);
    hide(errorEl);
    hide(detailEl);
    show(loadingEl);
    show(backBtn);
    hide(breadcrumb);
  }

  function showErr() {
    hide(welcome);
    hide(loadingEl);
    hide(detailEl);
    show(errorEl);
    show(backBtn);
  }

  function renderDetail(data) {
    hide(loadingEl);
    hide(welcome);
    hide(errorEl);
    show(backBtn);
    drawBreadcrumb(data.breadcrumb || []);

    if (data.type === 'main') {
      drawHeader(data.category, data.total, 'main');
      drawSubcategories(data.subcategories || []);
    } else {
      drawHeader(data.category, data.total, 'sub');
      drawResources(data.resources || []);
    }

    show(detailEl);
    const headerTop = toolbar ? toolbar.offsetHeight : 0;
    window.scrollTo({ top: headerTop, behavior: 'smooth' });
  }

  function drawBreadcrumb(crumbs) {
    if (!breadcrumb || !breadcrumbList) return;
    if (!crumbs.length) {
      hide(breadcrumb);
      return;
    }

    breadcrumbList.innerHTML = crumbs.map(function (c, i) {
      const isLast = i === crumbs.length - 1;
      return '<li class="lc-breadcrumb__item">' +
        (isLast
          ? esc(c.name)
          : '<span class="lc-breadcrumb__link" role="button" tabindex="0" data-lc-cat="' + c.id + '">' + esc(c.name) + '</span>') +
        '</li>';
    }).join('');

    show(breadcrumb);

    breadcrumbList.querySelectorAll('[data-lc-cat]').forEach(function (el) {
      el.addEventListener('click', function () { loadCat(parseInt(el.dataset.lcCat, 10)); });
      el.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') loadCat(parseInt(el.dataset.lcCat, 10));
      });
    });
  }

  function drawHeader(cat, total, viewType) {
    if (!catHeaderEl) return;
    const isMain = viewType === 'main';
    const badgeIcon = isMain ? 'bi-diagram-3' : 'bi-collection';
    const badgeText = isMain
      ? total + ' subtopic' + (total !== 1 ? 's' : '')
      : total + ' resource' + (total !== 1 ? 's' : '');

    catHeaderEl.innerHTML =
      (isMain ? '<p class="lc-hero-panel__label">Main topic</p>' : '<p class="lc-hero-panel__label">Subtopic</p>') +
      '<div class="lc-hero-panel__row">' +
        '<span class="lc-hero-panel__icon"><div class="' + esc(cat.icon || 'icon-folder') + ' lc-lucide" aria-hidden="true"></div></span>' +
        '<div class="lc-hero-panel__copy">' +
          '<span class="lc-hero-panel__badge"><i class="bi ' + badgeIcon + '" aria-hidden="true"></i> ' + badgeText + '</span>' +
          '<h2 class="lc-hero-panel__title">' + esc(cat.name) + '</h2>' +
          (cat.description ? '<p class="lc-hero-panel__desc">' + esc(cat.description) + '</p>' : '') +
        '</div>' +
      '</div>';

    if (typeof window.mountLucideIcons === 'function') {
      window.mountLucideIcons(catHeaderEl);
    }
  }

  function drawSubcategories(subcategories) {
    if (!resourceGrid || !resourceEmpty) return;

    if (!subcategories.length) {
      resourceGrid.innerHTML = '';
      setEmptyCopy('No subtopics yet', 'This main category does not have any subcategories yet.');
      show(resourceEmpty);
      return;
    }

    hide(resourceEmpty);
    resourceGrid.innerHTML =
      '<div class="lc-subtopic-grid">' +
        subcategories.map(function (sub) {
          const count = sub.resource_count || 0;
          const countText = count > 0
            ? count + ' resource' + (count !== 1 ? 's' : '')
            : 'No resources yet';
          return '<button type="button" class="lc-subtopic-card" data-lc-cat="' + sub.id + '">' +
            '<span><span class="lc-subtopic-card__name">' + esc(sub.name) + '</span><br>' +
            '<span class="lc-subtopic-card__count">' + esc(countText) + '</span></span>' +
            '<span class="lc-subtopic-card__arrow"><i class="bi bi-arrow-right" aria-hidden="true"></i></span>' +
          '</button>';
        }).join('') +
      '</div>';

    resourceGrid.querySelectorAll('[data-lc-cat]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        loadCat(parseInt(btn.dataset.lcCat, 10));
      });
    });
  }

  function drawResources(resources) {
    if (!resourceGrid || !resourceEmpty) return;

    if (!resources.length) {
      resourceGrid.innerHTML = '';
      setEmptyCopy('No resources yet', 'This subtopic does not have any learning material uploaded yet.');
      show(resourceEmpty);
      return;
    }

    hide(resourceEmpty);
    resourceGrid.innerHTML = resources.map(buildCard).join('');
  }

  function setEmptyCopy(title, text) {
    const h = resourceEmpty.querySelector('h3');
    const p = resourceEmpty.querySelector('p');
    if (h) h.textContent = title;
    if (p) p.textContent = text;
  }

  function buildCard(r) {
    const label = getTypeLabel(r.m_type);
    const icon = getTypeIcon(r.m_type);
    const initials = (r.m_type || 'LC').slice(0, 2).toUpperCase();
    const date = r.date ? fmtDate(r.date) : '';

    const coverHtml = r.image_url
      ? '<img src="' + esc(r.image_url) + '" alt="">'
      : '<span class="lc-resource-card__cover-label" aria-hidden="true">' + esc(initials) + '</span>';

    const href = r.link ? esc(r.link) : '';
    const tag = href ? 'a' : 'article';
    const attrs = href ? ' href="' + href + '" target="_blank" rel="noopener noreferrer"' : '';

    return '<' + tag + ' class="lc-resource-card"' + attrs + '>' +
      '<div class="lc-resource-card__cover">' +
        coverHtml +
        '<span class="lc-resource-card__type-badge"><i class="' + icon + '" aria-hidden="true"></i> ' + esc(label) + '</span>' +
      '</div>' +
      '<div class="lc-resource-card__body">' +
        (r.cat_name ? '<span class="lc-resource-card__tag">' + esc(r.cat_name) + '</span>' : '') +
        '<h3 class="lc-resource-card__title">' + esc(r.title) + '</h3>' +
        (r.content ? '<p class="lc-resource-card__desc">' + esc(r.content) + '</p>' : '') +
        '<div class="lc-resource-card__footer">' +
          (date ? '<span class="lc-resource-card__date">' + esc(date) + '</span>' : '<span></span>') +
          (href ? '<span class="lc-resource-card__link">Open <i class="bi bi-arrow-up-right" aria-hidden="true"></i></span>' : '') +
        '</div>' +
      '</div>' +
    '</' + tag + '>';
  }

  function show(el) { if (el) { el.hidden = false; } }
  function hide(el) { if (el) { el.hidden = true; } }

  function esc(str) {
    return String(str || '')
      .replace(/&/g, '&amp;').replace(/</g, '&lt;')
      .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }

  function fmtDate(d) {
    try {
      return new Date(d).toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' });
    } catch (_) {
      return d;
    }
  }

  function getTypeLabel(t) {
    return { book: 'Book', posters: 'Poster', 'mobile kunji': 'Kunji', video: 'Video' }[t] || (t || 'Resource');
  }

  function getTypeIcon(t) {
    return {
      book: 'bi bi-book',
      posters: 'bi bi-image',
      'mobile kunji': 'bi bi-phone',
      video: 'bi bi-play-circle',
    }[t] || 'bi bi-file-earmark';
  }
})();
