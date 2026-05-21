/* ============================================================
   get-involved.js — countup, apply preselect, form helpers
   ============================================================ */

(() => {
  /* — Animated stat counters — */
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


  /* — Apply CTA → scroll to form + preselect role — */
  const form        = document.querySelector('[data-gi-form]');
  const roleSelect  = document.querySelector('[data-gi-role]');
  const applyBtns   = Array.from(document.querySelectorAll('[data-gi-apply]'));

  applyBtns.forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const role = btn.dataset.giApply;
      if (roleSelect && role) {
        roleSelect.value = role;
        roleSelect.dispatchEvent(new Event('change', { bubbles: true }));
      }
      if (form) {
        form.scrollIntoView({ behavior: 'smooth', block: 'start' });
        const firstField = form.querySelector('input[name="name"]');
        if (firstField) {
          window.setTimeout(() => firstField.focus({ preventScroll: true }), 600);
        }
      }
    });
  });


  /* — Char counter on textarea — */
  const textarea = document.querySelector('#gi-why');
  const counter  = document.querySelector('[data-gi-count]');
  if (textarea && counter) {
    const update = () => {
      const len = textarea.value.length;
      counter.textContent = len;
      counter.parentElement.classList.toggle('is-near', len > 540);
    };
    textarea.addEventListener('input', update);
    update();
  }


  /* — File input filename display — */
  const fileInput = document.querySelector('[data-gi-file]');
  const fileLabel = document.querySelector('[data-gi-file-label]');
  if (fileInput && fileLabel) {
    fileInput.addEventListener('change', () => {
      const wrap = fileInput.closest('.gi-form-file');
      const file = fileInput.files && fileInput.files[0];
      if (file) {
        const sizeKB = Math.round(file.size / 1024);
        const sizeText = sizeKB > 1024
          ? (sizeKB / 1024).toFixed(1) + ' MB'
          : sizeKB + ' KB';
        fileLabel.textContent = `${file.name} · ${sizeText}`;
        if (wrap) wrap.classList.add('is-filled');
      } else {
        fileLabel.textContent = 'Click to attach a file';
        if (wrap) wrap.classList.remove('is-filled');
      }
    });
  }


  /* — Mock form submit + success state — */
  const successEl = document.querySelector('[data-gi-success]');
  if (form && successEl) {
    form.addEventListener('submit', (e) => {
      e.preventDefault();
      if (typeof form.checkValidity === 'function' && !form.checkValidity()) {
        form.reportValidity();
        return;
      }
      const fields = form.querySelectorAll('input, select, textarea, button[type="submit"], button[type="reset"]');
      fields.forEach(f => f.setAttribute('disabled', 'disabled'));
      successEl.hidden = false;
      successEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
    });

    form.addEventListener('reset', () => {
      successEl.hidden = true;
      const wrap = form.querySelector('.gi-form-file');
      if (wrap) wrap.classList.remove('is-filled');
      if (fileLabel) fileLabel.textContent = 'Click to attach a file';
      if (counter) counter.textContent = '0';
    });
  }


  /* — Smooth-scroll for hero pathway anchors — */
  document.querySelectorAll('.gi-hero-paths-list a[href^="#"]').forEach(a => {
    a.addEventListener('click', (e) => {
      const id = a.getAttribute('href').slice(1);
      const target = document.getElementById(id);
      if (!target) return;
      e.preventDefault();
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  });
})();
