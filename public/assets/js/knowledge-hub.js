/* ============================================================
   knowledge-hub.js — search · filter · preview modal · countup
   ============================================================ */

(() => {
  /* — Animated stat counters (CountUp + suffix) — */
  const counters = Array.from(document.querySelectorAll('[data-countup]'));
  if (counters.length) {
    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const io = new IntersectionObserver((entries, obs) => {
      entries.forEach(e => {
        if (!e.isIntersecting) return;
        const el = e.target;
        const end = parseFloat(el.dataset.countup);
        const suffix = el.dataset.suffix || '';
        if (reduce || !window.countUp) {
          el.textContent = end.toLocaleString('en-IN') + suffix;
        } else {
          const up = new countUp.CountUp(el, end, {
            duration: 2.2,
            separator: ',',
            useEasing: true,
            suffix,
          });
          if (!up.error) up.start();
        }
        obs.unobserve(el);
      });
    }, { threshold: 0.4 });
    counters.forEach(el => io.observe(el));
  }


  /* — Search + filter — */
  const grid     = document.querySelector('[data-kh-grid]');
  const cards    = Array.from(document.querySelectorAll('[data-kh-card]'));
  const search   = document.querySelector('[data-kh-search]');
  const clearBtn = document.querySelector('[data-kh-clear]');
  const chips    = Array.from(document.querySelectorAll('.kh-chip[data-filter]'));
  const countEl  = document.querySelector('[data-kh-count]');
  const emptyEl  = document.querySelector('[data-kh-empty]');
  const resetBtns = Array.from(document.querySelectorAll('[data-kh-reset]'));

  let activeCat = 'all';
  let activeQ   = '';
  const params = new URLSearchParams(window.location.search);
  const initialFilter = params.get('filter');

  if (initialFilter && chips.some(chip => chip.dataset.filter === initialFilter)) {
    activeCat = initialFilter;
  }

  const cardMatches = (card) => {
    const cat = card.dataset.category || '';
    if (activeCat !== 'all' && cat !== activeCat) return false;
    if (!activeQ) return true;
    const haystack = [
      card.dataset.khTitle || '',
      card.dataset.khDesc || '',
      card.dataset.keywords || '',
      cat,
    ].join(' ').toLowerCase();
    return activeQ.split(/\s+/).every(t => haystack.includes(t));
  };

  const apply = () => {
    let visible = 0;
    cards.forEach(card => {
      const show = cardMatches(card);
      card.classList.toggle('is-hidden', !show);
      if (show) visible += 1;
    });
    if (countEl) countEl.textContent = visible;
    if (emptyEl) emptyEl.hidden = visible !== 0;
  };

  if (search) {
    search.addEventListener('input', (e) => {
      activeQ = e.target.value.trim().toLowerCase();
      if (clearBtn) clearBtn.hidden = activeQ === '';
      apply();
    });
  }

  if (clearBtn && search) {
    clearBtn.addEventListener('click', () => {
      search.value = '';
      activeQ = '';
      clearBtn.hidden = true;
      apply();
      search.focus();
    });
  }

  chips.forEach(chip => {
    chip.addEventListener('click', () => {
      activeCat = chip.dataset.filter;
      chips.forEach(c => c.classList.toggle('is-active', c === chip));
      apply();
    });
  });

  resetBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      activeCat = 'all';
      activeQ = '';
      if (search) search.value = '';
      if (clearBtn) clearBtn.hidden = true;
      chips.forEach(c => c.classList.toggle('is-active', c.dataset.filter === 'all'));
      apply();
    });
  });

  chips.forEach(chip => {
    chip.classList.toggle('is-active', chip.dataset.filter === activeCat);
  });
  apply();


  /* — Preview modal — */
  const modal      = document.querySelector('[data-kh-mod]');
  if (!modal) return;

  const triggers   = Array.from(document.querySelectorAll('[data-kh-open]'));
  const closeEls   = Array.from(modal.querySelectorAll('[data-kh-close]'));
  const elCover    = modal.querySelector('[data-kh-mod-cover]');
  const elTag      = modal.querySelector('[data-kh-mod-tag]');
  const elFmt      = modal.querySelector('[data-kh-mod-fmt]');
  const elTitle    = modal.querySelector('[data-kh-mod-title]');
  const elDesc     = modal.querySelector('[data-kh-mod-desc]');
  const elPages    = modal.querySelector('[data-kh-mod-pages]');
  const elSize     = modal.querySelector('[data-kh-mod-size]');
  const elLangs    = modal.querySelector('[data-kh-mod-langs]');
  const elPub      = modal.querySelector('[data-kh-mod-published]');
  const elDls      = modal.querySelector('[data-kh-mod-downloads]');
  const elDl       = modal.querySelector('[data-kh-mod-dl]');
  const elCanva    = modal.querySelector('[data-kh-mod-canva]');

  const catLabel = {
    research: 'Research',
    iec:      'IEC Materials',
    toolkit:  'Toolkit',
    guide:    'Guide',
    report:   'Report',
    data:     'Data Resource',
  };

  let lastFocus = null;

  const open = (id) => {
    const card = document.querySelector(`[data-kh-id="${id}"]`);
    if (!card) return;

    lastFocus = document.activeElement;

    const cover = card.dataset.khCover || '';
    if (elCover) elCover.style.backgroundImage = cover ? `url('${cover}')` : '';

    const cat = card.dataset.category || '';
    if (elTag) {
      elTag.textContent = catLabel[cat] || 'Resource';
      elTag.dataset.cat = cat;
    }

    const fmt = card.dataset.khFormat || 'PDF';
    if (elFmt) {
      elFmt.textContent = fmt;
      elFmt.dataset.fmt = fmt;
    }

    if (elTitle) elTitle.textContent = card.dataset.khTitle || '';
    if (elDesc)  elDesc.textContent  = card.dataset.khDesc  || '';
    if (elPages) elPages.textContent = card.dataset.khPages || '—';
    if (elSize)  elSize.textContent  = card.dataset.khSize  || '—';
    if (elLangs) elLangs.textContent = card.dataset.khLangs || '—';
    if (elPub)   elPub.textContent   = card.dataset.khPublished || '—';
    if (elDls)   elDls.textContent   = card.dataset.khDownloads || '—';

    if (elDl) elDl.setAttribute('href', card.dataset.khDl || '#');

    const isCanva = card.dataset.khCanva === 'true';
    if (elCanva) {
      elCanva.hidden = !isCanva;
      if (isCanva) elCanva.setAttribute('href', card.dataset.khCanvaUrl || '#');
    }

    modal.hidden = false;
    document.body.style.overflow = 'hidden';
    const closeBtn = modal.querySelector('.kh-mod-close');
    if (closeBtn) closeBtn.focus();
  };

  const close = () => {
    modal.hidden = true;
    document.body.style.overflow = '';
    if (lastFocus && lastFocus.focus) lastFocus.focus();
  };

  triggers.forEach(t => {
    t.addEventListener('click', (e) => {
      e.preventDefault();
      open(t.dataset.khOpen);
    });
  });

  closeEls.forEach(el => el.addEventListener('click', close));

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !modal.hidden) close();
  });
})();
