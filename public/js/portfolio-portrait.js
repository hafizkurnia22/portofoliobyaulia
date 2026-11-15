(() => {
    const portrait = document.querySelector('.portfolio-portrait-mat');
    const hero = portrait.closest('.portfolio-hero-grid');
    const motion = matchMedia('(any-hover: hover) and (any-pointer: fine) and (prefers-reduced-motion: no-preference)');
    let targetSplit = 50;
    let split = 50;
    let targetX = 0;
    let targetY = 0;
    let x = 0;
    let y = 0;
    let frame = 0;
    let lastFrame = null;
    let tracking = false;

    function draw(time) {
        const elapsed = lastFrame === null ? 16 : time - lastFrame;
        lastFrame = time;
        const ease = 1 - Math.exp(-elapsed / 100);
        split += (targetSplit - split) * ease;
        x += (targetX - x) * ease;
        y += (targetY - y) * ease;

        const settled = Math.abs(targetSplit - split) < 0.08
            && Math.abs(targetX - x) < 0.003
            && Math.abs(targetY - y) < 0.003;
        if (settled) {
            split = targetSplit;
            x = targetX;
            y = targetY;
        }

        hero.style.setProperty('--portrait-split', `${split}%`);
        hero.style.setProperty('--portrait-x', x);
        hero.style.setProperty('--portrait-y', y);
        if (!settled) {
            frame = requestAnimationFrame(draw);
            return;
        }

        frame = 0;
        lastFrame = null;
        if (!tracking) clearPose();
    }

    function clearPose() {
        hero.style.removeProperty('--portrait-split');
        hero.style.removeProperty('--portrait-x');
        hero.style.removeProperty('--portrait-y');
        delete hero.dataset.portraitTracking;
    }

    function move(event) {
        if (!motion.matches || event.pointerType === 'touch') return;

        const { left, top, width, height } = portrait.getBoundingClientRect();
        if (!width || !height) return;

        if (!tracking && !frame) {
            split = parseFloat(getComputedStyle(hero).getPropertyValue('--portrait-split'));
            x = 0;
            y = 0;
        }
        tracking = true;
        hero.dataset.portraitTracking = '';
        targetX = Math.min(1, Math.max(-1, ((event.clientX - left) / width) * 2 - 1));
        targetY = Math.min(1, Math.max(-1, ((event.clientY - top) / height) * 2 - 1));
        targetSplit = 50 - targetX * 46;
        if (!frame) frame = requestAnimationFrame(draw);
    }

    function recenter() {
        if (!tracking) return;

        tracking = false;
        targetSplit = 50;
        targetX = 0;
        targetY = 0;
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
        clearPose();
    });
})();
