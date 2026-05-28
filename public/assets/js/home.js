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

  /* — Champions / home stories swiper — */
  const initChampionsSwiper = () => {
    const el = document.getElementById('home-stories-carousel');
    if (!el || !window.Swiper) return;

    const slides = el.querySelectorAll('.swiper-slide');
    const total = slides.length;
    if (total === 0) return;

    const bar = document.querySelector('.champions-controls .swiper-pagination-bar > span');
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const setBar = (sw) => {
      if (!bar) return;
      const visible = typeof sw.params.slidesPerView === 'number' ? sw.params.slidesPerView : 1;
      const max = Math.max(1, total - visible);
      const pct = Math.min(100, (sw.activeIndex / max) * 100);
      bar.style.width = `${pct}%`;
    };

    const swiper = new Swiper(el, {
      slidesPerView: 1.15,
      spaceBetween: 16,
      grabCursor: true,
      watchOverflow: true,
      speed: 500,
      breakpoints: {
        640: { slidesPerView: 2.1, spaceBetween: 20 },
        1024: { slidesPerView: 3.15, spaceBetween: 24 },
      },
      navigation: {
        prevEl: '.champions-controls .champ-prev',
        nextEl: '.champions-controls .champ-next',
      },
      autoplay: reduceMotion || total < 2 ? false : {
        delay: 4500,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
      },
      keyboard: {
        enabled: true,
        onlyInViewport: true,
      },
      on: {
        init(sw) {
          setBar(sw);
        },
        slideChange: setBar,
        resize: setBar,
      },
    });

    window.addEventListener('load', () => swiper.update(), { once: true });
  };

  const initHomeBannerSwiper = () => {
    const el = document.getElementById('home-banner-carousel');
    if (!el || !window.Swiper) return;

    const total = el.querySelectorAll('.swiper-slide').length;
    if (total === 0) return;

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const hasNav = total > 1;

    new Swiper(el, {
      slidesPerView: 1,
      spaceBetween: 0,
      loop: hasNav,
      grabCursor: hasNav,
      watchOverflow: true,
      speed: 600,
      autoplay: reduceMotion || !hasNav ? false : {
        delay: 5000,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
      },
      pagination: hasNav ? {
        el: document.querySelector('.hero-banner-controls .hero-banner-pagination'),
        clickable: true,
      } : false,
      navigation: hasNav ? {
        prevEl: document.querySelector('.hero-banner-controls .hero-banner-prev'),
        nextEl: document.querySelector('.hero-banner-controls .hero-banner-next'),
      } : false,
      keyboard: {
        enabled: hasNav,
        onlyInViewport: true,
      },
    });
  };

  const initProgramsSwiper = () => {
    const el = document.getElementById('programs-initiatives-slider');
    if (!el || !window.Swiper) return;

    const total = el.querySelectorAll('.swiper-slide').length;
    if (total === 0) return;

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const hasNav = total > 1;

    new Swiper(el, {
      effect: 'coverflow',
      centeredSlides: true,
      slidesPerView: 'auto',
      spaceBetween: 22,
      loop: total > 2,
      speed: 650,
      grabCursor: hasNav,
      coverflowEffect: {
        rotate: 0,
        stretch: 0,
        depth: 140,
        modifier: 1,
        slideShadows: false,
      },
      navigation: hasNav ? {
        prevEl: '.programs-slider-prev',
        nextEl: '.programs-slider-next',
      } : false,
      keyboard: {
        enabled: hasNav,
        onlyInViewport: true,
      },
      autoplay: reduceMotion || !hasNav ? false : {
        delay: 4200,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
      },
      breakpoints: {
        768: {
          spaceBetween: 28,
          coverflowEffect: { rotate: 0, stretch: 0, depth: 180, modifier: 1, slideShadows: false },
        },
        1024: {
          spaceBetween: 36,
          coverflowEffect: { rotate: 0, stretch: 0, depth: 220, modifier: 1.1, slideShadows: false },
        },
      },
    });
  };

  const initHomeCarousels = () => {
    initChampionsSwiper();
    initHomeBannerSwiper();
    initProgramsSwiper();
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initHomeCarousels);
  } else {
    initHomeCarousels();
  }

  document.querySelectorAll('.home-events-grid .tile[data-aos]').forEach((tile) => {
    tile.removeAttribute('data-aos');
    tile.removeAttribute('data-aos-delay');
    tile.classList.remove('aos-init');
  });

  document.querySelectorAll('.home-get-involved [data-aos]').forEach((el) => {
    el.removeAttribute('data-aos');
    el.removeAttribute('data-aos-delay');
    el.classList.remove('aos-init');
  });

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
            titleFont: { family: "'Roboto', sans-serif" },
            bodyFont: { family: "'Roboto', sans-serif" },
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
