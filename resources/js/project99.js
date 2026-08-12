document.addEventListener('DOMContentLoaded', () => {
    const tabs = document.querySelectorAll('[data-tab-target]');
    const panels = document.querySelectorAll('[data-tab-panel]');

    if (tabs.length > 0 && panels.length > 0) {
        const showTab = (id) => {
            const activeId = id || 'user';

            panels.forEach((panel) => {
                panel.classList.toggle('hidden', panel.dataset.tabPanel !== activeId);
            });

            tabs.forEach((tab) => {
                const isActive = tab.dataset.tabTarget === activeId;

                tab.classList.toggle('border-indigo-500', isActive);
                tab.classList.toggle('text-indigo-600', isActive);
                tab.classList.toggle('border-transparent', !isActive);
                tab.classList.toggle('text-gray-500', !isActive);
            });
        };

        const initialTab = window.location.hash.replace('#', '') || 'user';
        showTab(initialTab);

        tabs.forEach((tab) => {
            tab.addEventListener('click', (event) => {
                event.preventDefault();
                const id = tab.dataset.tabTarget;
                window.location.hash = id;
                showTab(id);
            });
        });

        window.addEventListener('hashchange', () => {
            showTab(window.location.hash.replace('#', '') || 'user');
        });
    }

    const navDropdown = document.querySelector('[data-nav-dropdown]');

    if (!navDropdown) {
        return;
    }

    const trigger = navDropdown.querySelector('[data-nav-dropdown-trigger]');
    const panel = navDropdown.querySelector('[data-nav-dropdown-panel]');
    const triggerChevron = navDropdown.querySelector('[data-nav-dropdown-chevron]');

    const setDropdownOpen = (open) => {
        trigger.setAttribute('aria-expanded', open ? 'true' : 'false');
        panel.classList.toggle('hidden', !open);

        if (triggerChevron) {
            triggerChevron.classList.toggle('rotate-180', open);
        }
    };

    const closeDropdown = () => setDropdownOpen(false);

    const setAccordionOpen = (button, open) => {
        const targetId = button.dataset.navAccordionTarget;
        const accordionPanel = navDropdown.querySelector(`[data-nav-accordion-panel="${targetId}"]`);
        const chevron = button.querySelector('[data-nav-accordion-chevron]');

        button.setAttribute('aria-expanded', open ? 'true' : 'false');

        if (accordionPanel) {
            accordionPanel.classList.toggle('hidden', !open);
        }

        if (chevron) {
            chevron.classList.toggle('rotate-90', open);
        }
    };

    trigger.addEventListener('click', (event) => {
        event.stopPropagation();
        const isOpen = trigger.getAttribute('aria-expanded') === 'true';
        setDropdownOpen(!isOpen);
    });

    navDropdown.querySelectorAll('[data-nav-accordion-trigger]').forEach((button) => {
        button.addEventListener('click', (event) => {
            event.stopPropagation();
            const isOpen = button.getAttribute('aria-expanded') === 'true';
            setAccordionOpen(button, !isOpen);
        });
    });

    document.addEventListener('click', (event) => {
        if (!navDropdown.contains(event.target)) {
            closeDropdown();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeDropdown();
        }
    });

    const openAccordionForActiveRoute = () => {
        const activeLink = navDropdown.querySelector('a.bg-indigo-50');

        if (!activeLink) {
            return;
        }

        navDropdown.querySelectorAll('[data-nav-accordion-panel]').forEach((accordionPanel) => {
            if (!accordionPanel.contains(activeLink)) {
                return;
            }

            const triggerButton = navDropdown.querySelector(
                `[data-nav-accordion-target="${accordionPanel.dataset.navAccordionPanel}"]`
            );

            if (triggerButton) {
                setAccordionOpen(triggerButton, true);
            }

            const parentPanel = triggerButton?.closest('[data-nav-accordion-panel]');

            if (parentPanel) {
                const parentTrigger = navDropdown.querySelector(
                    `[data-nav-accordion-target="${parentPanel.dataset.navAccordionPanel}"]`
                );

                if (parentTrigger) {
                    setAccordionOpen(parentTrigger, true);
                }
            }
        });
    };

    openAccordionForActiveRoute();
});
