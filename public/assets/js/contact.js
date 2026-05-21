(function () {
    const toast = document.getElementById('site-toast');
    if (!toast) return;

    const hide = () => {
        toast.classList.add('site-toast--hide');
        setTimeout(() => toast.remove(), 350);
    };

    requestAnimationFrame(() => {
        toast.classList.add('site-toast--show');
    });

    toast.querySelector('[data-toast-close]')?.addEventListener('click', hide);

    const form = document.getElementById('contact-form');
    if (form && toast.classList.contains('site-toast--success')) {
        form.reset();
    }

    if (window.location.hash !== '#contact-form') {
        history.replaceState(null, '', window.location.pathname + '#contact-form');
    }

    const target = document.getElementById('contact-form') || toast;
    target.scrollIntoView({ behavior: 'smooth', block: 'start' });

    setTimeout(hide, 8000);
})();
