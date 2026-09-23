(function () {
    function showPaginationError(target) {
        let feedback = target.querySelector('.tryout-pagination-feedback');

        if (!feedback) {
            feedback = document.createElement('p');
            feedback.className = 'tryout-pagination-feedback';
            feedback.setAttribute('role', 'status');
            target.querySelector('.tryout-evaluation-pagination')?.after(feedback);
        }

        feedback.textContent = 'Halaman belum dapat dimuat. Silakan coba lagi.';
    }

    async function loadPage(button) {
        const pageUrl = button?.dataset?.evaluationPageUrl;
        const target = button?.closest('[data-evaluation-pagination-target]');

        if (!pageUrl || !target || button.disabled || target.dataset.loading === 'true') return;

        const requestUrl = new URL(pageUrl, window.location.href);
        requestUrl.protocol = window.location.protocol;
        requestUrl.host = window.location.host;

        target.dataset.loading = 'true';
        target.classList.add('is-loading');
        target.setAttribute('aria-busy', 'true');

        try {
            const response = await fetch(requestUrl.toString(), {
                credentials: 'same-origin',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            });

            if (!response.ok) throw new Error('Gagal memuat halaman evaluasi.');

            const html = await response.text();
            const documentFragment = new DOMParser().parseFromString(html, 'text/html');
            const targetName = target.dataset.evaluationPaginationTarget;
            const nextTarget = documentFragment.querySelector(`[data-evaluation-pagination-target="${targetName}"]`);

            if (!nextTarget) throw new Error('Data halaman evaluasi tidak ditemukan.');

            target.replaceWith(nextTarget);
            window.history.pushState({ tryoutTab: 'evaluasi' }, '', requestUrl.pathname + requestUrl.search + requestUrl.hash);
        } catch (error) {
            target.classList.remove('is-loading');
            target.removeAttribute('aria-busy');
            delete target.dataset.loading;
            showPaginationError(target);
        }
    }

    function bindPagination() {
        document.addEventListener('click', function (event) {
            const button = event.target.closest('[data-evaluation-page-url]');
            if (!button || button.disabled) return;

            event.preventDefault();
            loadPage(button);
        });
    }

    window.loadTryoutEvaluationPage = loadPage;
    bindPagination();
}());
