document.addEventListener('DOMContentLoaded', () => {
    const mobileMenuBtn = document.querySelector('[data-mobile-menu-toggle]');
    const mobileMenu = document.getElementById('mobile-menu');
    const searchOverlay = document.getElementById('search-overlay');
    const searchOpenBtns = document.querySelectorAll('[data-search-open]');
    const searchCloseBtns = document.querySelectorAll('[data-search-close]');
    const megaMenuTriggers = document.querySelectorAll('[data-mega-menu]');
    const megaMenuPanels = document.querySelectorAll('[data-mega-menu-panel]');
    const submenuTriggers = document.querySelectorAll('[data-submenu]');

    // Mobile menu
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        document.addEventListener('click', (e) => {
            if (!mobileMenu.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                mobileMenu.classList.add('hidden');
            }
        });
    }

    // Search overlay
    searchOpenBtns.forEach(btn => {
        btn.addEventListener('click', () => searchOverlay && searchOverlay.classList.remove('hidden'));
    });

    searchCloseBtns.forEach(btn => {
        btn.addEventListener('click', () => searchOverlay && searchOverlay.classList.add('hidden'));
    });

    if (searchOverlay) {
        searchOverlay.addEventListener('click', (e) => {
            if (e.target === searchOverlay) searchOverlay.classList.add('hidden');
        });
    }

    // Desktop mega menus (hover)
    let menuCloseTimer = null;

    const closeAllMegaMenus = () => {
        megaMenuPanels.forEach(p => p.classList.add('hidden'));
    };

    megaMenuTriggers.forEach(trigger => {
        const key = trigger.dataset.megaMenu;
        const panel = document.querySelector(`[data-mega-menu-panel="${key}"]`);

        trigger.addEventListener('mouseenter', () => {
            clearTimeout(menuCloseTimer);
            closeAllMegaMenus();
            if (panel) panel.classList.remove('hidden');
        });

        trigger.addEventListener('mouseleave', () => {
            menuCloseTimer = setTimeout(closeAllMegaMenus, 120);
        });
    });

    megaMenuPanels.forEach(panel => {
        panel.addEventListener('mouseenter', () => clearTimeout(menuCloseTimer));
        panel.addEventListener('mouseleave', () => {
            menuCloseTimer = setTimeout(closeAllMegaMenus, 120);
        });
    });

    // Submenus (hover inside mega menu)
    submenuTriggers.forEach(trigger => {
        const key = trigger.dataset.submenu;
        const submenuPanel = document.querySelector(`[data-submenu-panel="${key}"]`);
        const allSubmenuPanels = trigger.closest('[data-mega-menu-panel]')
            ? trigger.closest('[data-mega-menu-panel]').querySelectorAll('[data-submenu-panel]')
            : document.querySelectorAll('[data-submenu-panel]');

        trigger.addEventListener('mouseenter', () => {
            allSubmenuPanels.forEach(p => p.classList.add('hidden'));
            if (submenuPanel) submenuPanel.classList.remove('hidden');
        });

        trigger.addEventListener('mouseleave', () => {
            if (submenuPanel) submenuPanel.classList.add('hidden');
        });
    });

    submenuTriggers.forEach(trigger => {
        const key = trigger.dataset.submenu;
        const submenuPanel = document.querySelector(`[data-submenu-panel="${key}"]`);
        if (!submenuPanel) return;
        submenuPanel.addEventListener('mouseenter', () => submenuPanel.classList.remove('hidden'));
        submenuPanel.addEventListener('mouseleave', () => submenuPanel.classList.add('hidden'));
    });
});
