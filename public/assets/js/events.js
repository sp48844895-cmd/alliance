/* ============================================================
   events.js — dynamic calendar, upcoming/past tabs,
                gallery lightbox with keyboard nav
   ============================================================ */

(() => {

  /* ── 1. Upcoming / Past tabs ─────────────────────────────── */
  const panel = document.getElementById('ev-events-panel');
  if (panel) {
    const tabs   = Array.from(panel.querySelectorAll('.ev-events-tab[data-tab]'));
    const panes  = Array.from(panel.querySelectorAll('[data-tab-panel]'));

    const activateTab = (tabName) => {
      tabs.forEach(tab => {
        const active = tab.dataset.tab === tabName;
        tab.classList.toggle('is-active', active);
        tab.setAttribute('aria-selected', String(active));
      });
      panes.forEach(pane => {
        const active = pane.dataset.tabPanel === tabName;
        pane.classList.toggle('is-active', active);
        pane.hidden = !active;
      });
    };

    tabs.forEach(tab => {
      tab.addEventListener('click', () => activateTab(tab.dataset.tab));
    });
  }

  /* ── 2. Dynamic calendar ─────────────────────────────────── */
  const widget = document.getElementById('ev-cal-widget');
  if (!widget) return;

  const API       = widget.dataset.calendarApi;
  const gridEl    = document.getElementById('ev-cal-grid');
  const moLabel   = document.getElementById('ev-cal-mo');
  const footMonth = document.getElementById('ev-cal-foot-month');
  const footCount = document.getElementById('ev-cal-foot-count');
  const prevBtn   = document.getElementById('ev-cal-prev');
  const nextBtn   = document.getElementById('ev-cal-next');
  const eventsPanel = document.getElementById('ev-events-panel');

  const WD_LABELS = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

  const initParts = (widget.dataset.initMonth || '').split('-');
  let curYear  = initParts[0] ? parseInt(initParts[0], 10) : new Date().getFullYear();
  let curMonth = initParts[1] ? parseInt(initParts[1], 10) : new Date().getMonth() + 1;
  let cache    = {};

  function pad(n) { return n < 10 ? '0' + n : '' + n; }
  function monthKey(y, m) { return y + '-' + pad(m); }

  function fetchMonth(year, month, cb) {
    const key = monthKey(year, month);
    if (cache[key]) { cb(cache[key]); return; }
    gridEl.innerHTML = '<div class="ev-cal-loading" style="grid-column:1/-1">'
      + '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="animation:ev-spin .8s linear infinite"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>'
      + '</div>';
    fetch(API + '?month=' + key)
      .then(r => r.json())
      .then(data => { cache[key] = data; cb(data); })
      .catch(() => {
        const loading = gridEl.querySelector('.ev-cal-loading');
        if (loading) loading.textContent = 'Could not load calendar.';
      });
  }

  function buildEventMap(events) {
    const map = {};
    events.forEach(ev => {
      if (!map[ev.date]) map[ev.date] = [];
      map[ev.date].push(ev);
    });
    return map;
  }

  function renderCalendar(data) {
    moLabel.textContent   = data.label;
    footMonth.textContent = data.label;
    footCount.textContent = data.events.length;

    const map    = buildEventMap(data.events);
    const offset = data.first_day_offset;
    const total  = data.days_in_month;
    const today  = data.today;

    let html = WD_LABELS.map(d =>
      `<div class="ev-cal-wd" role="columnheader">${d}</div>`
    ).join('');

    for (let i = 0; i < offset; i++) {
      html += '<div class="ev-cal-d ev-cal-d--out" aria-hidden="true"></div>';
    }

    for (let day = 1; day <= total; day++) {
      const dateStr  = `${data.year}-${pad(data.month)}-${pad(day)}`;
      const hasEvent = !!map[dateStr];
      const isToday  = dateStr === today;

      let cls = 'ev-cal-d';
      if (isToday)  cls += ' ev-cal-d--today';
      if (hasEvent) cls += ' ev-cal-d--has';

      const attrs = hasEvent
        ? ` role="button" tabindex="0" data-date="${dateStr}" aria-label="${day} ${data.label}${isToday ? ', today' : ''}, has event"`
        : ` aria-label="${day}${isToday ? ', today' : ''}"`;

      html += `<div class="${cls}"${attrs}><span>${day}</span></div>`;
    }

    const filled   = offset + total;
    const trailing = filled % 7 !== 0 ? 7 - (filled % 7) : 0;
    for (let i = 0; i < trailing; i++) {
      html += '<div class="ev-cal-d ev-cal-d--out" aria-hidden="true"></div>';
    }

    gridEl.innerHTML = html;

    gridEl.querySelectorAll('.ev-cal-d--has[data-date]').forEach(cell => {
      cell.addEventListener('click', () => highlightDate(cell.dataset.date));
      cell.addEventListener('keydown', e => {
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); highlightDate(cell.dataset.date); }
      });
    });
  }

  function highlightDate(date) {
    gridEl.querySelectorAll('.ev-cal-d--has').forEach(c => {
      c.classList.toggle('is-active', c.dataset.date === date);
    });

    if (!eventsPanel) return;

    const card = eventsPanel.querySelector(`.ev-event-card[data-date="${date}"]`);
    if (!card) return;

    const pane = card.closest('[data-tab-panel]');
    const tabName = pane ? pane.dataset.tabPanel : null;

    if (tabName) {
      const tabBtn = eventsPanel.querySelector(`.ev-events-tab[data-tab="${tabName}"]`);
      if (tabBtn) tabBtn.click();
    }

    eventsPanel.querySelectorAll('.ev-event-card.is-flash').forEach(el => el.classList.remove('is-flash'));
    card.classList.add('is-flash');

    const panelRect = eventsPanel.getBoundingClientRect();
    const cardRect  = card.getBoundingClientRect();
    eventsPanel.scrollTo({
      top: eventsPanel.scrollTop + (cardRect.top - panelRect.top) - 24,
      behavior: 'smooth',
    });

    setTimeout(() => card.classList.remove('is-flash'), 2200);
  }

  function loadMonth(year, month) {
    fetchMonth(year, month, renderCalendar);
  }

  prevBtn.addEventListener('click', () => {
    curMonth--;
    if (curMonth < 1) { curMonth = 12; curYear--; }
    loadMonth(curYear, curMonth);
  });

  nextBtn.addEventListener('click', () => {
    curMonth++;
    if (curMonth > 12) { curMonth = 1; curYear++; }
    loadMonth(curYear, curMonth);
  });

  loadMonth(curYear, curMonth);


  /* ── 3. Gallery lightbox ─────────────────────────────────── */
  const galGrid = document.querySelector('[data-gal-grid]');
  const lb      = document.querySelector('[data-gal-lb]');
  if (!galGrid || !lb) return;

  const tiles    = Array.from(galGrid.querySelectorAll('.ev-gal-tile'));
  const imgEl    = lb.querySelector('[data-gal-img]');
  const capEl    = lb.querySelector('[data-gal-cap]');
  const idxEl    = lb.querySelector('[data-gal-i]');
  const totalEl  = lb.querySelector('[data-gal-total]');
  const prevLbBtn = lb.querySelector('[data-gal-prev]');
  const nextLbBtn = lb.querySelector('[data-gal-next]');
  const closeEls  = Array.from(lb.querySelectorAll('[data-gal-close]'));

  const photos = tiles.map(t => {
    const bg  = t.style.backgroundImage || '';
    const url = bg.replace(/^url\(["']?/, '').replace(/["']?\)$/, '');
    return { src: url, cap: t.getAttribute('aria-label') || '' };
  });

  if (totalEl) totalEl.textContent = photos.length;

  let current   = 0;
  let lastFocus = null;

  const renderPhoto = () => {
    const p = photos[current];
    if (!p) return;
    imgEl.src = p.src;
    imgEl.alt = p.cap;
    if (capEl) capEl.textContent = p.cap;
    if (idxEl) idxEl.textContent = current + 1;
  };

  const openLb = (i) => {
    current   = i;
    lastFocus = document.activeElement;
    renderPhoto();
    lb.hidden = false;
    document.body.style.overflow = 'hidden';
    if (closeEls[0]) closeEls[0].focus();
  };

  const closeLb = () => {
    lb.hidden = true;
    if (imgEl) imgEl.src = '';
    document.body.style.overflow = '';
    if (lastFocus && lastFocus.focus) lastFocus.focus();
  };

  const stepLb = (delta) => {
    current = (current + delta + photos.length) % photos.length;
    renderPhoto();
  };

  tiles.forEach((t, i) => t.addEventListener('click', () => openLb(i)));
  closeEls.forEach(el => el.addEventListener('click', closeLb));
  if (prevLbBtn) prevLbBtn.addEventListener('click', () => stepLb(-1));
  if (nextLbBtn) nextLbBtn.addEventListener('click', () => stepLb(1));

  document.addEventListener('keydown', (e) => {
    if (lb.hidden) return;
    if (e.key === 'Escape')       closeLb();
    else if (e.key === 'ArrowLeft')  stepLb(-1);
    else if (e.key === 'ArrowRight') stepLb(1);
  });

})();
