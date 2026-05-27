/* ============================================================
   main.js — global behaviors (nav, AOS, smooth scroll)
   ============================================================ */

(() => {
  // — Sticky-nav border on scroll —
  const nav = document.querySelector('.nav');
  if (nav) {
    const onScroll = () => nav.classList.toggle('is-scrolled', window.scrollY > 8);
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  // — Mobile burger —
  const burger = document.querySelector('.nav-burger');
  const menu   = document.querySelector('.nav-menu');
  if (burger && menu) {
    burger.addEventListener('click', () => {
      const open = menu.classList.toggle('is-open');
      burger.classList.toggle('is-open', open);
      burger.setAttribute('aria-expanded', open);
    });
    menu.querySelectorAll('a').forEach(a =>
      a.addEventListener('click', () => {
        menu.classList.remove('is-open');
        burger.classList.remove('is-open');
      })
    );
  }

  const navDetails = Array.from(document.querySelectorAll('.nav-menu details'));
  if (navDetails.length) {
    navDetails.forEach(details => {
      details.addEventListener('toggle', () => {
        if (!details.open) return;
        navDetails.forEach(item => {
          if (item !== details) item.open = false;
        });
      });
    });

    document.addEventListener('click', (e) => {
      navDetails.forEach(details => {
        if (details.open && !details.contains(e.target)) details.open = false;
      });
    });

    document.addEventListener('keydown', (e) => {
      if (e.key !== 'Escape') return;
      navDetails.forEach(details => {
        if (details.open) details.open = false;
      });
    });
  }

  // — AOS init —
  if (window.AOS) {
    AOS.init({
      duration: 720,
      easing: 'ease-out-cubic',
      once: true,
      offset: 80,
      disable: () => window.matchMedia('(prefers-reduced-motion: reduce)').matches,
    });
  }

  // — Set current year in footer —
  document.querySelectorAll('[data-year]').forEach(el => {
    el.textContent = new Date().getFullYear();
  });

  document.querySelectorAll('[data-st-share]').forEach(btn => {
    const label = btn.querySelector('.st-share-btn__label');
    const defaultLabel = label ? label.textContent : 'Share';

    btn.addEventListener('click', async (e) => {
      e.preventDefault();
      e.stopPropagation();

      const url = btn.dataset.shareUrl || window.location.href;
      const title = btn.dataset.shareTitle || document.title;
      const hashtags = btn.dataset.shareHashtags || '#SBCMatters';
      const text = `${title} ${hashtags}`;

      if (navigator.share) {
        try {
          await navigator.share({ title, text, url });
          return;
        } catch (err) {
          if (err && err.name === 'AbortError') return;
        }
      }

      try {
        await navigator.clipboard.writeText(`${text}\n${url}`);
        if (label) {
          label.textContent = 'Copied!';
          window.setTimeout(() => { label.textContent = defaultLabel; }, 2000);
        }
      } catch (_) {
        window.prompt('Copy this link to share:', `${text}\n${url}`);
      }
    });
  });
})();
