document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-form-tracker]').forEach(container => {
        const form = container.closest('form') || document.querySelector(container.dataset.formTarget || 'form');
        if (!form) return;

        const cancelBtn = container.querySelector('[data-cancel-btn]');
        const submitBtn = container.querySelector('[data-submit-btn]');
        const confirmModal = container.querySelector('[data-confirm-modal]');
        const confirmCancelBtn = container.querySelector('[data-confirm-cancel]');
        const cancelRoute = cancelBtn ? cancelBtn.dataset.cancelRoute : null;

        let formChanged = false;

        const initialData = new FormData(form);

        form.addEventListener('change', () => {
            const currentData = new FormData(form);
            formChanged = ![...initialData.entries()].every(
                ([key, value]) => currentData.get(key) === value
            );
        });

        const showModal = () => {
            if (confirmModal) confirmModal.classList.remove('hidden');
        };

        const hideModal = () => {
            if (confirmModal) confirmModal.classList.add('hidden');
        };

        if (cancelBtn) {
            cancelBtn.addEventListener('click', () => {
                if (formChanged) {
                    showModal();
                } else if (cancelRoute) {
                    window.location.href = cancelRoute;
                }
            });
        }

        if (submitBtn) {
            submitBtn.addEventListener('click', showModal);
        }

        if (confirmCancelBtn) {
            confirmCancelBtn.addEventListener('click', hideModal);
        }

        if (confirmModal) {
            confirmModal.addEventListener('click', (e) => {
                if (e.target === confirmModal) hideModal();
            });
        }
    });
});
