document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('admin-mobile-sidebar');
    const overlay = document.getElementById('admin-sidebar-overlay');
    const openBtn = document.getElementById('admin-sidebar-open');
    const closeBtns = document.querySelectorAll('[data-sidebar-close]');

    const open = () => {
        if (sidebar) sidebar.classList.remove('hidden');
        if (overlay) overlay.classList.remove('hidden');
    };

    const close = () => {
        if (sidebar) sidebar.classList.add('hidden');
        if (overlay) overlay.classList.add('hidden');
    };

    if (openBtn) openBtn.addEventListener('click', open);
    closeBtns.forEach(btn => btn.addEventListener('click', close));
    if (overlay) overlay.addEventListener('click', close);

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') close();
    });
});
