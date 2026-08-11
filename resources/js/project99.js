document.addEventListener('DOMContentLoaded', () => {
    const tabs = document.querySelectorAll('[data-tab-target]');
    const panels = document.querySelectorAll('[data-tab-panel]');

    if (tabs.length === 0 || panels.length === 0) {
        return;
    }

    const showTab = (id) => {
        const activeId = id || 'profile';

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

    const initialTab = window.location.hash.replace('#', '') || 'profile';
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
        showTab(window.location.hash.replace('#', '') || 'profile');
    });
});
