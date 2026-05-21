(() => {
  const form = document.querySelector('[data-members-filters]');
  const district = document.querySelector('[data-members-district]');
  const type = document.querySelector('[data-members-type]');
  const search = document.querySelector('[data-members-search]');
  const reset = document.querySelector('[data-members-reset]');

  if (!form) return;

  let searchTimer;

  [district, type].forEach(field => {
    if (!field) return;
    field.addEventListener('change', () => form.submit());
  });

  if (search) {
    search.addEventListener('input', () => {
      window.clearTimeout(searchTimer);
      searchTimer = window.setTimeout(() => form.submit(), 400);
    });
  }

  if (reset) {
    reset.addEventListener('click', event => {
      event.preventDefault();
      window.location.href = form.getAttribute('action') || window.location.pathname;
    });
  }
})();
