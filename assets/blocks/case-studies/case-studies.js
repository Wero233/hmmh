
(function () {
    'use strict';

    const filterBlocks = document.querySelectorAll('.case-studies__filter');

    filterBlocks.forEach((filterEl) => {
        let listEl = filterEl.nextElementSibling;
        while (listEl && !listEl.classList.contains('case-studies')) {
            listEl = listEl.nextElementSibling;
        }
        if (!listEl) return;

        const buttons = filterEl.querySelectorAll('.case-studies__filter-button, .case-studies__filter-button--all');
        const items = listEl.querySelectorAll('.case-studies__item');

        function setActive(btn) {
            buttons.forEach(b => {
                b.classList.remove('is-active');
                b.setAttribute('aria-selected', 'false');
            });
            btn.classList.add('is-active');
            btn.setAttribute('aria-selected', 'true');
        }

        function applyFilter(slug) {
            items.forEach((item) => {
                if (slug === '*') {
                    item.hidden = false;
                    item.classList.remove('is-hidden');
                    return;
                }
                const terms = (item.getAttribute('data-terms') || '')
                    .trim()
                    .split(/\s+/)
                    .filter(Boolean);

                const show = terms.includes(slug);
                item.hidden = !show;
                item.classList.toggle('is-hidden', !show);
            });
        }


        filterEl.addEventListener('click', (e) => {
            const btn = e.target.closest('.case-studies__filter-button, .case-studies__filter-button--all');
            if (!btn) return;
            e.preventDefault();
            setActive(btn);
            applyFilter(btn.dataset.filter || '*');
        });

        filterEl.addEventListener('keydown', (e) => {
            const isAction = e.key === 'Enter' || e.key === ' ';
            const btn = e.target.closest('.case-studies__filter-button, .case-studies__filter-button--all');
            if (!btn || !isAction) return;
            e.preventDefault();
            setActive(btn);
            applyFilter(btn.dataset.filter || '*');
        });

        const initial = filterEl.querySelector('.is-active');
        if (initial) applyFilter(initial.dataset.filter || '*');
    });
})();
