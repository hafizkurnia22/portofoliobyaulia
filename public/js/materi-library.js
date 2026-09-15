(() => {
    const library = document.getElementById('materiLibrary');
    if (!library) return;
    const search = document.getElementById('materiSearchInput');
    const category = document.getElementById('materiCategory');
    const topic = document.getElementById('materiTopic');
    const reset = document.getElementById('materiReset');
    const rows = Array.from(library.querySelectorAll('.materi-reading-item'));
    function apply() {
        const keyword = search.value.trim().toLocaleLowerCase('id');
        let count = 0;
        rows.forEach(row => {
            const matches = (!category.value || row.dataset.category === category.value)
                && (!topic.value || JSON.parse(row.dataset.topics).includes(topic.value))
                && (!keyword || row.dataset.search.toLocaleLowerCase('id').includes(keyword));
            row.hidden = !matches;
            if (matches) count++;
        });
        document.getElementById('materiResultCount').textContent = `${count} bacaan tersedia`;
        document.getElementById('materiEmpty').hidden = count > 0;
        reset.hidden = !keyword && !category.value && !topic.value;
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
    reset.addEventListener('click', () => {
        search.value = ''; category.value = ''; topic.value = '';
        category.dispatchEvent(new Event('change'));
        search.focus();
    });
    apply();
})();
