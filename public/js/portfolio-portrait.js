(() => {
    const portrait = document.querySelector('.portfolio-portrait-mat');
    const hero = portrait.closest('.portfolio-hero-grid');
    const motion = matchMedia('(any-hover: hover) and (any-pointer: fine) and (prefers-reduced-motion: no-preference)');
    let target = 50;
    let split = 50;
    let frame = 0;
    let lastFrame = null;
    let tracking = false;

    function draw(time) {
        const elapsed = lastFrame === null ? 16 : time - lastFrame;
        lastFrame = time;
        split += (target - split) * (1 - Math.exp(-elapsed / 85));

        if (Math.abs(target - split) > 0.08) {
            hero.style.setProperty('--portrait-split', `${split}%`);
            frame = requestAnimationFrame(draw);
            return;
        }

        split = target;
        hero.style.setProperty('--portrait-split', `${split}%`);
        frame = 0;
        lastFrame = null;
        if (!tracking) {
            hero.style.removeProperty('--portrait-split');
            delete hero.dataset.portraitTracking;
        }
    }

    function move(event) {
        if (!motion.matches || event.pointerType === 'touch') return;

        const { left, width } = portrait.getBoundingClientRect();
        if (!width) return;

        if (!tracking && !frame) {
            split = parseFloat(getComputedStyle(hero).getPropertyValue('--portrait-split'));
        }
        tracking = true;
        hero.dataset.portraitTracking = '';
        const progress = Math.min(1, Math.max(0, (event.clientX - left) / width));
        target = 96 - progress * 92;
        if (!frame) frame = requestAnimationFrame(draw);
    }

    function recenter() {
        if (!tracking) return;

        tracking = false;
        target = 50;
        if (!frame) frame = requestAnimationFrame(draw);
    }

    hero.addEventListener('pointermove', move, { passive: true });
    hero.addEventListener('pointerleave', recenter);
    hero.addEventListener('pointercancel', recenter);
    motion.addEventListener('change', () => {
        cancelAnimationFrame(frame);
        tracking = false;
        frame = 0;
        lastFrame = null;
        hero.style.removeProperty('--portrait-split');
        delete hero.dataset.portraitTracking;
    });
})();
