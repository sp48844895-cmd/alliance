(function () {
  const track = document.getElementById('programs-center-track');
  const prev = document.getElementById('programs-center-prev');
  const next = document.getElementById('programs-center-next');
  const dotsWrap = document.getElementById('programs-center-dots');
  if (!track || !prev || !next || !dotsWrap) return;

  const cards = Array.from(track.querySelectorAll('[data-program-card]'));
  if (!cards.length) return;

  const isMobile = () => window.matchMedia('(max-width: 767px)').matches;
  let current = cards.findIndex((card) => card.hasAttribute('active'));
  if (current < 0) current = 0;

  cards.forEach((_, i) => {
    const dot = document.createElement('button');
    dot.type = 'button';
    dot.className = 'programs-center-dot';
    dot.setAttribute('aria-label', `Go to slide ${i + 1}`);
    dot.addEventListener('click', () => activate(i, true));
    dotsWrap.appendChild(dot);
  });

  const dots = Array.from(dotsWrap.children);

  function centerCard(index) {
    const card = cards[index];
    if (!card) return;
    const axis = isMobile() ? 'top' : 'left';
    const size = isMobile() ? 'clientHeight' : 'clientWidth';
    const start = isMobile() ? card.offsetTop : card.offsetLeft;
    track.parentElement.scrollTo({
      [axis]: start - (track.parentElement[size] / 2 - card[size] / 2),
      behavior: 'smooth',
    });
  }

  function updateUi(index) {
    cards.forEach((card, i) => {
      if (i === index) {
        card.setAttribute('active', '');
      } else {
        card.removeAttribute('active');
      }
    });

    dots.forEach((dot, i) => dot.classList.toggle('active', i === index));
    prev.disabled = index === 0;
    next.disabled = index === cards.length - 1;
  }

  function activate(index, shouldCenter) {
    if (index < 0 || index >= cards.length) return;
    current = index;
    updateUi(index);
    if (shouldCenter) centerCard(index);
  }

  function step(delta) {
    activate(Math.max(0, Math.min(cards.length - 1, current + delta)), true);
  }

  prev.addEventListener('click', () => step(-1));
  next.addEventListener('click', () => step(1));

  cards.forEach((card, i) => {
    card.addEventListener('click', () => activate(i, true));
    card.addEventListener('mouseenter', () => {
      if (window.matchMedia('(hover: hover)').matches) {
        activate(i, true);
      }
    });
  });

  let startX = 0;
  let startY = 0;
  track.addEventListener('touchstart', (e) => {
    startX = e.touches[0].clientX;
    startY = e.touches[0].clientY;
  }, { passive: true });

  track.addEventListener('touchend', (e) => {
    const dx = e.changedTouches[0].clientX - startX;
    const dy = e.changedTouches[0].clientY - startY;
    if (isMobile()) {
      if (Math.abs(dy) > 60) step(dy > 0 ? -1 : 1);
      return;
    }
    if (Math.abs(dx) > 60) step(dx > 0 ? -1 : 1);
  }, { passive: true });

  window.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowRight' || e.key === 'ArrowDown') step(1);
    if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') step(-1);
  });

  window.addEventListener('resize', () => centerCard(current));

  activate(current, true);
})();
