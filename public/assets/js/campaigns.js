/* ============================================================
   campaigns.js — filters and campaign interactions
   ============================================================ */

(() => {
  const root = document.querySelector('[data-cmp-list]');
  if (!root) return;

  const cards = Array.from(root.querySelectorAll('.cmp-card'));
  const tlItems = Array.from(document.querySelectorAll('.cmp-tl-item'));
  const empty = document.querySelector('[data-cmp-empty]');
  const countEl = document.querySelector('[data-cmp-count]');
  const selects = Array.from(document.querySelectorAll('[data-cmp-filter]'));
  const resetBtns = Array.from(document.querySelectorAll('[data-cmp-reset]'));

  let active = { theme: 'all', district: 'all' };

  const cardMatches = (card) => {
    const cardTheme = card.dataset.theme;
    const cardDistricts = (card.dataset.district || '').split(',').map(s => s.trim());
    const themeOk = active.theme === 'all' || cardTheme === active.theme;
    const districtOk = active.district === 'all' || cardDistricts.includes(active.district);
    return themeOk && districtOk;
  };

  const applyFilters = () => {
    let visible = 0;
    cards.forEach(card => {
      const show = cardMatches(card);
      card.classList.toggle('is-hidden', !show);
      if (show) visible++;
    });

    tlItems.forEach(item => {
      const itemTheme = item.dataset.theme;
      const show = active.theme === 'all' || itemTheme === active.theme;
      item.classList.toggle('is-hidden', !show);
    });

    if (countEl) countEl.textContent = visible;
    if (empty) empty.hidden = visible !== 0;
  };

  selects.forEach(sel => {
    sel.addEventListener('change', () => {
      active[sel.dataset.cmpFilter] = sel.value;
      applyFilters();
    });
  });

  resetBtns.forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      active = { theme: 'all', district: 'all' };
      selects.forEach(sel => { sel.value = 'all'; });
      applyFilters();
    });
  });

  /* — Before/After slider — */
  const ba = document.querySelector('[data-cmp-ba]');
  if (ba) {
    const range = ba.querySelector('.cmp-ba-range');
    const clip = ba.querySelector('[data-cmp-ba-clip]');
    const handle = ba.querySelector('[data-cmp-ba-handle]');
    const stage = ba.querySelector('.cmp-ba-stage');

    const setSplit = (pct) => {
      const v = Math.min(100, Math.max(0, pct));
      if (clip) clip.style.clipPath = `inset(0 ${100 - v}% 0 0)`;
      if (handle) handle.style.left = `${v}%`;
    };

    if (range) {
      range.addEventListener('input', () => setSplit(parseFloat(range.value)));
      setSplit(parseFloat(range.value));
    }

    /* — Drag-on-stage support — */
    if (stage) {
      let dragging = false;
      const updateFromX = (clientX) => {
        const rect = stage.getBoundingClientRect();
        const pct = ((clientX - rect.left) / rect.width) * 100;
        setSplit(pct);
        if (range) range.value = String(Math.round(Math.min(100, Math.max(0, pct))));
      };

      const onPointerDown = (e) => {
        dragging = true;
        stage.setPointerCapture?.(e.pointerId);
        updateFromX(e.clientX);
      };
      const onPointerMove = (e) => {
        if (!dragging) return;
        updateFromX(e.clientX);
      };
      const onPointerUp = (e) => {
        dragging = false;
        stage.releasePointerCapture?.(e.pointerId);
      };

      stage.addEventListener('pointerdown', onPointerDown);
      stage.addEventListener('pointermove', onPointerMove);
      stage.addEventListener('pointerup', onPointerUp);
      stage.addEventListener('pointercancel', onPointerUp);
      stage.addEventListener('pointerleave', onPointerUp);
    }
  }

  /* — Timeline accordion: scroll-to-card-anchor closes others, opens target — */
  document.querySelectorAll('a[href^="#cmp-tl-"]').forEach(link => {
    link.addEventListener('click', () => {
      const id = link.getAttribute('href').slice(1);
      const target = document.getElementById(id);
      if (target && !target.open) target.open = true;
    });
  });
})();
