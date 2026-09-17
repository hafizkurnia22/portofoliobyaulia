(() => {
    const carousel = document.getElementById('portfolioCarousel');
    if (!carousel || !window.bootstrap?.Carousel) return;
    // Manual navigation keeps the visitor in control; Bootstrap also supports swiping.
    bootstrap.Carousel.getOrCreateInstance(carousel, { interval: false, keyboard: true, touch: true });
    carousel.addEventListener('slid.bs.carousel', (event) => {
        const labels = ['Perkenalan', 'Karya digital', 'Pengalaman dan keahlian'];
        document.getElementById('introSlideAnnouncement').textContent = `Slide ${event.to + 1} dari 3: ${labels[event.to]}`;
    });
})();
