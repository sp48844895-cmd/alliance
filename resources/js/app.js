import './bootstrap';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.querySelector('[data-admin-sidebar]');
    const toggle = document.querySelector('[data-admin-toggle]');
    const overlay = document.querySelector('[data-admin-overlay]');

    const closeSidebar = () => {
        sidebar?.classList.remove('is-open');
        overlay?.classList.remove('is-visible');
    };

    toggle?.addEventListener('click', () => {
        sidebar?.classList.toggle('is-open');
        overlay?.classList.toggle('is-visible');
    });

    overlay?.addEventListener('click', closeSidebar);

    document.querySelectorAll('[data-collapse-toggle]').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-collapse-toggle');
            const target = document.getElementById(id);
            if (!target) return;
            const open = target.classList.toggle('is-open');
            btn.setAttribute('aria-expanded', open ? 'true' : 'false');
            btn.querySelector('[data-chevron]')?.classList.toggle('rotate-180', open);
        });
    });

    document.querySelectorAll('form[data-confirm]').forEach(form => {
        form.addEventListener('submit', e => {
            e.preventDefault();

            const message = form.getAttribute('data-confirm') || 'Are you sure you want to delete this?';
            const title = form.getAttribute('data-confirm-title') || 'Are you sure?';

            Swal.fire({
                title,
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete',
                cancelButtonText: 'Cancel',
            confirmButtonColor: '#5d2cb5',
            cancelButtonColor: '#6b6585',
                reverseButtons: true,
                focusCancel: true,
            }).then(result => {
                if (result.isConfirmed) {
                    form.removeAttribute('data-confirm');
                    form.submit();
                }
            });
        });
    });

    document.querySelectorAll('[data-image-preview]').forEach(input => {
        const preview = document.querySelector(input.getAttribute('data-image-preview'));
        const previewBox = input.getAttribute('data-image-preview-box')
            ? document.querySelector(input.getAttribute('data-image-preview-box'))
            : null;
        const placeholder = input.getAttribute('data-image-preview-placeholder')
            ? document.querySelector(input.getAttribute('data-image-preview-placeholder'))
            : null;

        input.addEventListener('change', e => {
            const file = e.target.files?.[0];
            if (!file || !preview) return;
            const url = URL.createObjectURL(file);
            preview.src = url;
            preview.classList.remove('hidden');
            previewBox?.classList.remove('hidden');
            placeholder?.classList.add('hidden');

            const modalTarget = input.getAttribute('data-image-preview-modal');
            if (modalTarget) {
                const container = document.querySelector(modalTarget);
                if (container) {
                    container.classList.remove('hidden');
                    container.setAttribute('data-admin-image-preview', url);
                }
            }
        });
    });

    setTimeout(() => {
        document.querySelectorAll('[data-auto-dismiss]').forEach(el => {
            el.style.transition = 'opacity .3s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 300);
        });
    }, 4000);
});
