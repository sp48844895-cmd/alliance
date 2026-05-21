/* ============================================================
   home.js — counters, swiper, donut, lottie
   ============================================================ */

(() => {
  /* — Animated number counters (CountUp.js) — */
  const observe = (el, cb) => {
    const io = new IntersectionObserver((entries, obs) => {
      entries.forEach(e => {
        if (e.isIntersecting) { cb(e.target); obs.unobserve(e.target); }
      });
    }, { threshold: 0.4 });
    io.observe(el);
  };

  document.querySelectorAll('[data-count]').forEach(el => {
    const end = parseFloat(el.dataset.count);
    const decimals = parseInt(el.dataset.decimals || 0, 10);
    const duration = parseFloat(el.dataset.duration || 2.4);
    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (reduce || !window.countUp) {
      el.textContent = end.toLocaleString('en-IN', { minimumFractionDigits: decimals, maximumFractionDigits: decimals });
      return;
    }
    const up = new countUp.CountUp(el, end, {
      duration,
      decimalPlaces: decimals,
      separator: ',',
      useEasing: true,
      enableScrollSpy: false,
    });
    observe(el, () => { if (!up.error) up.start(); });
  });

  /* — Champions swiper — */
  if (window.Swiper) {
    const total = document.querySelectorAll('.champions-swiper .swiper-slide').length;
    const bar = document.querySelector('.swiper-pagination-bar > span');
    const setBar = (sw) => {
      if (!bar) return;
      const visible = sw.params.slidesPerView;
      const max = Math.max(1, total - (typeof visible === 'number' ? visible : 1));
      const pct = Math.min(100, ((sw.activeIndex) / max) * 100);
      bar.style.width = `${pct}%`;
    };

    const swiper = new Swiper('.champions-swiper', {
      slidesPerView: 1.15,
      spaceBetween: 16,
      grabCursor: true,
      breakpoints: {
        640:  { slidesPerView: 2.1, spaceBetween: 20 },
        1024: { slidesPerView: 3.15, spaceBetween: 24 },
      },
      navigation: {
        prevEl: '.champ-prev',
        nextEl: '.champ-next',
      },
      on: {
        init: setBar,
        slideChange: setBar,
        resize: setBar,
      },
    });
  }

  /* — Coverage donut chart (Chart.js) — */
  const donut = document.getElementById('coverageChart');
  if (donut && window.Chart) {
    const ctx = donut.getContext('2d');
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ['NGO / CSO', 'Volunteers', 'Academia', 'Firms / Bodies'],
        datasets: [{
          data: [144, 5000, 35, 15],
          backgroundColor: ['#5d2cb5', '#ff6b35', '#3237f0', '#7d4ad0'],
          borderColor: ['rgba(250, 248, 255, 0.0)', 'rgba(250, 248, 255, 0.0)', 'rgba(250, 248, 255, 0.0)', 'rgba(250, 248, 255, 0.0)'],
          borderWidth: 0,
          cutout: '78%',
          hoverOffset: 6,
        }],
      },
      options: {
        responsive: true,
        animation: { animateRotate: true, duration: 1600, easing: 'easeOutQuart' },
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: '#1a1635',
            titleFont: { family: "'Manrope', sans-serif" },
            bodyFont: { family: "'Manrope', sans-serif" },
            padding: 10,
            cornerRadius: 8,
          },
        },
      },
    });
  }

  /* — Marquee duplication (seamless loop) — */
  document.querySelectorAll('.marquee-track').forEach(track => {
    track.innerHTML = track.innerHTML + track.innerHTML;
  });
})();
