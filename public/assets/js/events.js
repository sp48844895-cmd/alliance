/* ============================================================
   events.js — dynamic calendar, timeline filter + highlight,
                gallery lightbox with keyboard nav
   ============================================================ */

(() => {

  /* ── 1. Timeline filter (All / Upcoming / Past) ─────────── */
  const tlList = document.querySelector('[data-tl-list]');
  if (tlList) {
    const items   = Array.from(tlList.querySelectorAll('.ev-tl-item'));
    const todayEl = tlList.querySelector('.ev-tl-today');
    const chips   = Array.from(document.querySelectorAll('.ev-tl-chip[data-filter]'));

    const applyFilter = (filter) => {
      items.forEach(it => {
        it.classList.toggle('is-hidden', filter !== 'all' && it.dataset.status !== filter);
      });
      if (todayEl) todayEl.style.display = filter === 'all' ? '' : 'none';
    };

    chips.forEach(chip => {
      chip.addEventListener('click', () => {
        chips.forEach(c => {
          c.classList.toggle('is-active', c === chip);
          c.setAttribute('aria-selected', String(c === chip));
        });
        applyFilter(chip.dataset.filter);
      });
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
  const tl        = document.querySelector('.ev-tl');

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
        gridEl.querySelector('.ev-cal-loading').textContent = 'Could not load calendar.';
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

    /* rebuild grid — keep day-of-week headers */
    let html = WD_LABELS.map(d =>
      `<div class="ev-cal-wd" role="columnheader">${d}</div>`
    ).join('');

    /* leading blank cells */
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

    /* trailing blank cells to complete last row */
    const filled   = offset + total;
    const trailing = filled % 7 !== 0 ? 7 - (filled % 7) : 0;
    for (let i = 0; i < trailing; i++) {
      html += '<div class="ev-cal-d ev-cal-d--out" aria-hidden="true"></div>';
    }

    gridEl.innerHTML = html;

    /* wire up click → timeline highlight */
    gridEl.querySelectorAll('.ev-cal-d--has[data-date]').forEach(cell => {
      cell.addEventListener('click', () => highlightDate(cell.dataset.date));
      cell.addEventListener('keydown', e => {
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); highlightDate(cell.dataset.date); }
      });
    });
  }

  function highlightDate(date) {
    /* toggle active state on calendar cells */
    gridEl.querySelectorAll('.ev-cal-d--has').forEach(c => {
      c.classList.toggle('is-active', c.dataset.date === date);
    });

    if (!tl) return;

    /* find matching timeline item */
    const target = tl.querySelector(`.ev-tl-item[data-date="${date}"]`);
    if (!target) return;

    tl.querySelectorAll('.ev-tl-item.is-flash').forEach(el => el.classList.remove('is-flash'));
    target.classList.add('is-flash');

    const tlRect   = tl.getBoundingClientRect();
    const itemRect = target.getBoundingClientRect();
    tl.scrollTo({ top: tl.scrollTop + (itemRect.top - tlRect.top) - 80, behavior: 'smooth' });

    setTimeout(() => target.classList.remove('is-flash'), 2200);
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

  /* initial load */
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
