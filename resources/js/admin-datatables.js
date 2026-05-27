import $ from 'jquery';
import DataTable from 'datatables.net-dt';
import 'datatables.net-responsive-dt';
import 'datatables.net-dt/css/dataTables.dataTables.min.css';
import 'datatables.net-responsive-dt/css/responsive.dataTables.min.css';

window.$ = window.jQuery = $;

function parseOptions(table) {
    const raw = table.getAttribute('data-admin-datatable-options');
    if (!raw) {
        return {};
    }
    try {
        return JSON.parse(raw);
    } catch {
        return {};
    }
}

function defaultOptions(table) {
    const actionIndex = table.querySelector('thead th.col-actions, thead th:last-child');
    const noSortCells = table.querySelectorAll('thead th.no-sort');
    const noSortTargets = Array.from(noSortCells).map(th => Array.from(th.parentElement.children).indexOf(th));

    if (actionIndex && !noSortTargets.includes(actionIndex.cellIndex)) {
        noSortTargets.push(actionIndex.cellIndex);
    }

    const columnDefs = [
        { responsivePriority: 1, targets: 0 },
        { responsivePriority: 10001, targets: -1 },
    ];

    if (noSortTargets.length) {
        columnDefs.unshift({ orderable: false, targets: noSortTargets });
    }

    return {
        responsive: {
            details: {
                type: 'column',
                target: 'tr',
            },
        },
        autoWidth: false,
        scrollX: false,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
        order: [],
        columnDefs,
        language: {
            search: '',
            searchPlaceholder: 'Search this list…',
            lengthMenu: 'Show _MENU_',
            info: 'Showing _START_–_END_ of _TOTAL_',
            infoEmpty: 'No matching rows',
            infoFiltered: '(filtered from _MAX_)',
            zeroRecords: 'Nothing matches your search',
            paginate: {
                first: 'First',
                last: 'Last',
                next: 'Next',
                previous: 'Prev',
            },
        },
        dom: '<"dt-toolbar flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-4 px-4 pt-4"<"dt-length"l><"dt-search"f>>rt<"dt-footer flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-4 pb-4 pt-3 border-t border-[var(--color-line)]"<"dt-info"i><"dt-paging"p>>',
    };
}

export function initAdminDataTables(root = document) {
    root.querySelectorAll('table[data-admin-datatable]').forEach(table => {
        if ($.fn.dataTable.isDataTable(table)) {
            return;
        }

        if (!table.querySelector('tbody tr')) {
            return;
        }

        const options = {
            ...defaultOptions(table),
            ...parseOptions(table),
        };

        new DataTable(table, options);
    });
}

export function initAdminAjaxForms(root = document) {
    root.querySelectorAll('form[data-admin-ajax]').forEach(form => {
        form.addEventListener('submit', async event => {
            event.preventDefault();

            const button = form.querySelector('[type="submit"]');
            if (button) {
                button.disabled = true;
            }

            try {
                const response = await fetch(form.action, {
                    method: (form.querySelector('[name="_method"]')?.value || form.method || 'POST').toUpperCase(),
                    body: new FormData(form),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        Accept: 'application/json',
                    },
                });

                const data = await response.json().catch(() => ({}));

                if (!response.ok) {
                    throw new Error(data.message || 'Something went wrong. Please try again.');
                }

                if (data.reload) {
                    window.location.reload();
                    return;
                }

                if (data.removeRow) {
                    const row = form.closest('tr');
                    const table = row?.closest('table[data-admin-datatable]');
                    if (table && $.fn.dataTable.isDataTable(table)) {
                        const api = $(table).DataTable();
                        api.row(row).remove().draw(false);
                    } else {
                        row?.remove();
                    }
                }

                if (data.toggleHtml && form.dataset.ajaxTarget) {
                    const target = form.querySelector(form.dataset.ajaxTarget);
                    if (target) {
                        target.innerHTML = data.toggleHtml;
                    }
                }

                if (data.statusHtml && form.dataset.ajaxStatusTarget) {
                    const target = form.closest('tr')?.querySelector(form.dataset.ajaxStatusTarget);
                    if (target) {
                        target.innerHTML = data.statusHtml;
                    }
                }

                if (data.rowClass !== undefined) {
                    form.closest('tr')?.classList.toggle('bg-[var(--color-clay-50)]', Boolean(data.rowClass));
                }

                if (data.message) {
                    showToast(data.message, data.toastType || 'success');
                }
            } catch (error) {
                showToast(error.message || 'Request failed', 'error');
            } finally {
                if (button) {
                    button.disabled = false;
                }
            }
        });
    });
}

function showToast(message, type = 'success') {
    const existing = document.querySelector('[data-admin-toast]');
    existing?.remove();

    const toast = document.createElement('div');
    toast.setAttribute('data-admin-toast', '');
    toast.className = `fixed bottom-6 right-6 z-50 px-4 py-3 rounded-lg text-sm font-semibold shadow-lg border ${
        type === 'error' ? 'alert-error' : 'alert-success'
    }`;
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity .3s';
        setTimeout(() => toast.remove(), 300);
    }, 3200);
}

document.addEventListener('DOMContentLoaded', () => {
    initAdminDataTables();
    initAdminAjaxForms();
});
