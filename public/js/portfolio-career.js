(() => {
    const career = document.querySelector('.portfolio-career');
    const strip = career.querySelector('.portfolio-career-logos');
    if (!strip) return;

    const logos = [...strip.querySelectorAll('a')];
    const dots = career.querySelector('.portfolio-career-dots');
    const indicators = [...career.querySelectorAll('[data-experience]')];
    const entries = [...career.querySelectorAll('.portfolio-career-entry')];
    const reducedMotion = matchMedia('(prefers-reduced-motion: reduce)');

    function select(id, revealLogo = false) {
        entries.forEach((entry) => {
            entry.hidden = entry.id !== id;
        });
        logos.forEach((logo) => {
            const isCurrent = logo.hash === '#' + id;
            if (isCurrent) logo.setAttribute('aria-current', 'true');
            else logo.removeAttribute('aria-current');

            if (isCurrent && revealLogo && strip.scrollWidth > strip.clientWidth) {
                const stripBox = strip.getBoundingClientRect();
                const logoBox = logo.getBoundingClientRect();
                strip.scrollTo({
                    left: strip.scrollLeft + logoBox.left - stripBox.left + (logoBox.width - stripBox.width) / 2,
                    behavior: reducedMotion.matches ? 'instant' : 'smooth',
                });
            }
        });
        indicators.forEach((button) => {
            button.setAttribute('aria-pressed', String(button.dataset.experience === id));
        });
    }

    const linkedEntry = entries.find((entry) => location.hash === '#' + entry.id);
    select((linkedEntry ?? entries[0]).id, Boolean(linkedEntry));
    if (dots) dots.hidden = false;

    career.addEventListener('click', (event) => {
        const button = event.target.closest('[data-experience]');
        if (button) {
            select(button.dataset.experience, true);
            return;
        }

        const logo = event.target.closest('.portfolio-career-logos a');
        if (logo && !event.ctrlKey && !event.metaKey && !event.shiftKey && !event.altKey) {
            event.preventDefault();
            select(logo.hash.slice(1), true);
        }
    });
})();
