(() => {
    const library = document.getElementById('materiLibrary');
    if (!library) return;
    const search = document.getElementById('materiSearchInput');
    const category = document.getElementById('materiCategory');
    const topic = document.getElementById('materiTopic');
    const perPage = document.getElementById('materiPerPage');
    const reset = document.getElementById('materiReset');
    const pagination = document.getElementById('materiPagination');
    const prev = document.getElementById('materiPrevPage');
    const next = document.getElementById('materiNextPage');
    const pageNumbers = document.getElementById('materiPageNumbers');
    const rows = Array.from(library.querySelectorAll('.materi-reading-item'));
    let currentPage = 1;

    function apply(resetPage = true) {
        if (resetPage) currentPage = 1;
        const keyword = search.value.trim().toLocaleLowerCase('id');
        const filteredRows = rows.filter(row => (!category.value || row.dataset.category === category.value)
            && (!topic.value || JSON.parse(row.dataset.topics).includes(topic.value))
            && (!keyword || row.dataset.search.toLocaleLowerCase('id').includes(keyword)));
        const count = filteredRows.length;
        const limit = Number(perPage.value) || 5;
        const totalPages = Math.max(1, Math.ceil(count / limit));
        currentPage = Math.min(currentPage, totalPages);
        const start = (currentPage - 1) * limit;
        const visibleRows = new Set(filteredRows.slice(start, start + limit));

        rows.forEach(row => {
            row.hidden = !visibleRows.has(row);
        });
        const first = count ? start + 1 : 0;
        const last = Math.min(start + limit, count);
        document.getElementById('materiResultCount').textContent = count ? `${first}-${last} dari ${count} bacaan tersedia` : '0 bacaan tersedia';
        document.getElementById('materiEmpty').hidden = count > 0;
        renderPagination(totalPages, count);
        reset.hidden = !keyword && !category.value && !topic.value;
    }

    function renderPagination(totalPages, count) {
        pagination.hidden = count <= Number(perPage.value);
        prev.disabled = currentPage <= 1;
        next.disabled = currentPage >= totalPages;
        pageNumbers.innerHTML = '';
        for (let page = 1; page <= totalPages; page++) {
            const button = document.createElement('button');
            button.type = 'button';
            button.textContent = page;
            button.setAttribute('aria-label', `Halaman ${page}`);
            if (page === currentPage) {
                button.classList.add('active');
                button.setAttribute('aria-current', 'page');
            }
            button.addEventListener('click', () => {
                currentPage = page;
                apply(false);
            });
            pageNumbers.append(button);
        }
    }

    category.addEventListener('change', () => {
        if (topic.selectedOptions[0]?.dataset.category !== category.value) topic.value = '';
        topic.querySelectorAll('optgroup').forEach(group => {
            group.disabled = !!category.value && group.dataset.category !== category.value;
            group.hidden = group.disabled;
        });
        apply();
    });
    topic.addEventListener('change', apply);
    search.addEventListener('input', apply);
    perPage.addEventListener('change', apply);
    prev.addEventListener('click', () => {
        currentPage -= 1;
        apply(false);
    });
    next.addEventListener('click', () => {
        currentPage += 1;
        apply(false);
    });
    reset.addEventListener('click', () => {
        search.value = ''; category.value = ''; topic.value = '';
        category.dispatchEvent(new Event('change'));
        search.focus();
    });
    apply();
})();
