(() => {
    const article = document.getElementById('readingArticle');
    if (!article) return;
    const headings = Array.from(article.querySelectorAll('h2, h3'));
    const list = document.getElementById('readingContentsList');
    headings.forEach((heading, index) => {
        heading.id = `reading-section-${index + 1}`;
        heading.tabIndex = -1;
        const item = document.createElement('li');
        const link = document.createElement('a');
        link.href = `#${heading.id}`;
        link.textContent = heading.textContent;
        link.addEventListener('click', () => heading.focus({preventScroll: true}));
        item.append(link);
        list.append(item);
    });
    document.getElementById('readingContents').hidden = !headings.length;
})();
