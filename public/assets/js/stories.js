/* ============================================================
   stories.js — filters, video modal, keyboard handling
   ============================================================ */

(() => {
  /* — Filtering — */
  const root = document.querySelector('[data-st-list]');
  if (root) {
    const cards     = Array.from(root.querySelectorAll('.st-card'));
    const empty     = document.querySelector('[data-st-empty]');
    const countEl   = document.querySelector('[data-st-count]');
    const selects   = Array.from(document.querySelectorAll('[data-st-filter]'));
    const dateInputs = Array.from(document.querySelectorAll('[data-st-date]'));
    const resetBtns = Array.from(document.querySelectorAll('[data-st-reset]'));

    let active = { category: 'all', district: 'all', from: '', to: '' };

    const cardMatches = (card) => {
      const category = card.dataset.category;
      const district = (card.dataset.district || '').split(',').map(s => s.trim());
      const date = card.dataset.date || '';

      const matchesFrom = !active.from || (date && date >= active.from);
      const matchesTo = !active.to || (date && date <= active.to);

      return (active.category === 'all' || category === active.category)
          && (active.district === 'all' || district.includes(active.district))
          && matchesFrom
          && matchesTo;
    };

    const syncDateBounds = () => {
      const fromInput = document.querySelector('[data-st-date="from"]');
      const toInput = document.querySelector('[data-st-date="to"]');
      if (fromInput) fromInput.max = active.to || '';
      if (toInput) toInput.min = active.from || '';
    };

    const apply = () => {
      let visible = 0;
      cards.forEach(card => {
        const show = cardMatches(card);
        card.classList.toggle('is-hidden', !show);
        if (show) visible++;
      });
      if (countEl) countEl.textContent = visible;
      if (empty) empty.hidden = visible !== 0;
    };

    selects.forEach(sel => {
      sel.addEventListener('change', () => {
        active[sel.dataset.stFilter] = sel.value;
        apply();
      });
    });

    dateInputs.forEach(input => {
      const updateDateFilter = () => {
        active[input.dataset.stDate] = input.value;
        syncDateBounds();
        apply();
      };

      input.addEventListener('input', updateDateFilter);
      input.addEventListener('change', updateDateFilter);
    });

    resetBtns.forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        active = { category: 'all', district: 'all', from: '', to: '' };
        selects.forEach(sel => { sel.value = 'all'; });
        dateInputs.forEach(input => {
          input.value = '';
          input.min = '';
          input.max = '';
        });
        apply();
      });
    });

    syncDateBounds();
  }

  const tocLinks = Array.from(document.querySelectorAll('.st-article-toc-link'));
  if (tocLinks.length && 'IntersectionObserver' in window) {
    const sections = tocLinks
      .map(link => document.querySelector(link.getAttribute('href')))
      .filter(Boolean);

    const setActive = (id) => {
      tocLinks.forEach(link => {
        const matches = link.getAttribute('href') === `#${id}`;
        link.classList.toggle('is-active', matches);
      });
    };

    const observer = new IntersectionObserver((entries) => {
      const visible = entries
        .filter(entry => entry.isIntersecting)
        .sort((a, b) => b.intersectionRatio - a.intersectionRatio)[0];

      if (visible?.target?.id) {
        setActive(visible.target.id);
      }
    }, {
      rootMargin: '-20% 0px -55% 0px',
      threshold: [0.2, 0.45, 0.7],
    });

    sections.forEach(section => observer.observe(section));

    tocLinks.forEach(link => {
      link.addEventListener('click', () => {
        const id = link.getAttribute('href').slice(1);
        setActive(id);
      });
    });
  }

  /* — Video modal — */
  const modal     = document.querySelector('[data-st-modal]');
  const modalTtl  = document.querySelector('[data-st-modal-title]');
  const modalFrm  = document.querySelector('[data-st-modal-frame]');
  const triggers  = Array.from(document.querySelectorAll('.st-video[data-video-id]'));
  const closeEls  = Array.from(document.querySelectorAll('[data-st-modal-close]'));

  if (modal && triggers.length) {
    let lastFocus = null;

    const open = (id, title) => {
      lastFocus = document.activeElement;
      if (modalTtl) modalTtl.textContent = title || 'Video';
      if (modalFrm) {
        const iframe = document.createElement('iframe');
        iframe.src = `https://www.youtube.com/embed/${encodeURIComponent(id)}?autoplay=1&rel=0`;
        iframe.title = title || 'Video';
        iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
        iframe.setAttribute('allowfullscreen', '');
        modalFrm.appendChild(iframe);
      }
      modal.hidden = false;
      document.body.style.overflow = 'hidden';
      const focusEl = modal.querySelector('.st-modal-close');
      if (focusEl) focusEl.focus();
    };

    const close = () => {
      modal.hidden = true;
      if (modalFrm) modalFrm.innerHTML = '';
      document.body.style.overflow = '';
      if (lastFocus && lastFocus.focus) lastFocus.focus();
    };

    triggers.forEach(t => {
      t.addEventListener('click', () => {
        open(t.dataset.videoId, t.dataset.videoTitle);
      });
    });

    closeEls.forEach(el => el.addEventListener('click', close));

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && !modal.hidden) close();
    });
  }
})();
