(() => {
    const carousel = document.getElementById('portfolioCarousel');
    if (!carousel || !window.bootstrap?.Carousel) return;
    const instance = bootstrap.Carousel.getOrCreateInstance(carousel, {
        interval: 5000, ride: 'carousel', pause: false, keyboard: true, touch: true,
    });
    const toggle = document.getElementById('introAutoplayToggle');
    let paused = false;
    toggle.addEventListener('click', () => {
        paused = !paused;
        if (paused) instance.pause();
        else instance.cycle();
        toggle.setAttribute('aria-label', paused ? 'Lanjutkan pergantian otomatis' : 'Jeda pergantian otomatis');
        toggle.setAttribute('aria-pressed', String(paused));
        toggle.querySelector('i').className = paused ? 'bi bi-play-fill' : 'bi bi-pause-fill';
    });
    carousel.addEventListener('slid.bs.carousel', (event) => {
        const labels = ['Perkenalan', 'Karya digital', 'Pengalaman dan keahlian'];
        document.getElementById('introSlideAnnouncement').textContent = `Slide ${event.to + 1} dari 3: ${labels[event.to]}`;
        if (paused) instance.pause();
    });
})();
