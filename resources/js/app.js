import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-auto-submit-confirm]').forEach((form) => {
        form.addEventListener('submit', (e) => {
            const msg = form.dataset.autoSubmitConfirm;
            if (msg && ! window.confirm(msg)) {
                e.preventDefault();
            }
        });
    });
});
