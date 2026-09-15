document.addEventListener('DOMContentLoaded', function () {
    const body = document.body;
    const adminDashboard = document.querySelector('.admin-dashboard');

    if (!adminDashboard) return;

    function initTryoutScoringMode(root = document) {
        root.querySelectorAll('.tryout-soal-form').forEach(form => {
            if (form.dataset.scoringReady === 'true') return;

            const categorySelect = form.querySelector('.tryout-kategori-select');
            const answerGroup = form.querySelector('.tryout-answer-key-group');
            const answerSelect = answerGroup ? answerGroup.querySelector('select[name="jawaban_benar"]') : null;
            const scoreGroup = form.querySelector('.tryout-score-group');
            const scoreInputs = form.querySelectorAll('.tryout-score-input');

            if (!categorySelect) return;

            form.dataset.scoringReady = 'true';

            function selectedCategoryCode() {
                const selectedOption = categorySelect.options[categorySelect.selectedIndex];
                return selectedOption ? selectedOption.dataset.kode : '';
            }

            function applyScoringMode() {
                const code = selectedCategoryCode();
                const isTkp = code === 'TKP';

                if (answerGroup && answerSelect) {
                    answerGroup.classList.toggle('d-none', isTkp);
                    answerSelect.disabled = isTkp;
                    answerSelect.required = !isTkp;
                }

                if (scoreGroup) {
                    scoreGroup.classList.toggle('d-none', !isTkp);
                }

                scoreInputs.forEach((input, index) => {
                    input.disabled = !isTkp;
                    input.required = isTkp;
                    input.min = isTkp ? 1 : 0;
                    input.max = 5;

                    if (isTkp && (!input.value || Number(input.value) === 0)) {
                        input.value = 5 - index;
                    }
                });
            }

            categorySelect.addEventListener('change', applyScoringMode);
            applyScoringMode();
        });
    }

    function initRichEditors(root = document) {
        root.querySelectorAll('[data-rich-editor]').forEach(editor => {
            if (editor.dataset.editorReady === 'true') return;

            const toolbar = editor.querySelector('.rich-editor-toolbar');
            const area = editor.querySelector('.rich-editor-area');
            const input = editor.querySelector('.rich-editor-input');
            const form = editor.closest('form');

            if (!toolbar || !area || !input || !form) return;

            editor.dataset.editorReady = 'true';

            toolbar.querySelectorAll('button[data-command]').forEach(button => {
                button.addEventListener('click', function () {
                    const command = this.dataset.command;
                    let value = this.dataset.value || null;

                    area.focus();

                    if (command === 'createLink') {
                        value = window.prompt('Masukkan link, contoh: https://example.com');

                        if (!value) return;
                    }

                    document.execCommand(command, false, value);
                    input.value = area.innerHTML.trim();
                });
            });

            area.addEventListener('input', function () {
                input.value = area.innerHTML.trim();
            });

            form.addEventListener('submit', function () {
                input.value = area.innerHTML.trim();
            });
        });
    }

    function updateAdminChrome(nextDoc) {
        const currentSidebar = document.querySelector('.admin-sidebar');
        const nextSidebar = nextDoc.querySelector('.admin-sidebar');

        if (currentSidebar && nextSidebar) {
            currentSidebar.innerHTML = nextSidebar.innerHTML;
        }
    }

    function getActiveTabFromUrl(url) {
        return new URL(url, window.location.href).searchParams.get('active_tab') || 'dashboard';
    }

    function switchAdminTab(url, pushState = true) {
        const nextUrl = new URL(url, window.location.href);
        const activeTab = getActiveTabFromUrl(nextUrl.href);
        const targetPanel = document.getElementById(`${activeTab}-panel`);

        if (!targetPanel || nextUrl.pathname !== window.location.pathname) return false;

        document.querySelectorAll('.admin-content-panel .tab-pane').forEach(panel => {
            panel.classList.remove('show', 'active');
        });

        targetPanel.classList.add('show', 'active');

        document.querySelectorAll('.admin-sidebar-nav .nav-link[href*="active_tab="]').forEach(link => {
            const linkTab = getActiveTabFromUrl(link.href);
            link.classList.toggle('active', linkTab === activeTab);
        });

        if (pushState) {
            window.history.pushState({ adminPanel: true }, '', nextUrl.href);
        }

        if (window.AOS) {
            window.AOS.refreshHard();
        }

        return true;
    }

    async function loadAdminPanel(url, pushState = true) {
        const contentPanel = document.querySelector('.admin-content-panel');
        const currentContent = document.querySelector('.admin-content-panel .tab-content');

        if (!contentPanel || !currentContent) {
            window.location.href = url;
            return;
        }

        contentPanel.classList.add('is-loading');

        try {
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            if (!response.ok) throw new Error('Gagal memuat menu admin');

            const html = await response.text();
            const nextDoc = new DOMParser().parseFromString(html, 'text/html');
            const nextContent = nextDoc.querySelector('.admin-content-panel .tab-content');

            if (!nextContent) throw new Error('Konten admin tidak ditemukan');

            currentContent.innerHTML = nextContent.innerHTML;
            updateAdminChrome(nextDoc);

            if (pushState) {
                window.history.pushState({ adminPanel: true }, '', url);
            }

            if (window.AOS) {
                window.AOS.refreshHard();
            }
        } catch (error) {
            window.location.href = url;
        } finally {
            contentPanel.classList.remove('is-loading');
        }
    }

    document.addEventListener('click', function (event) {
        const deleteButton = event.target.closest('.btn-delete');

        if (deleteButton) {
            const form = deleteButton.closest('.delete-form');

            if (!form) return;

            Swal.fire({
                title: 'Hapus Data?',
                text: 'Data yang sudah dihapus tidak bisa dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                focusCancel: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#0b1f3a'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });

            return;
        }

        const navigationLink = event.target.closest('.admin-sidebar-nav .nav-link[href*="active_tab="]');

        if (!navigationLink || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;

        const url = navigationLink.href;

        if (!url || navigationLink.target === '_blank' || new URL(url).origin !== window.location.origin) return;

        event.preventDefault();

        if (!switchAdminTab(url)) {
            loadAdminPanel(url);
        }
    });

    document.addEventListener('input', function (event) {
        const input = event.target.closest('.admin-live-search');

        if (!input) return;

        const keyword = input.value.toLowerCase();
        const tableId = input.getAttribute('data-target');
        const table = document.getElementById(tableId);

        if (!table) return;

        table.querySelectorAll('tbody tr').forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(keyword) ? '' : 'none';
        });
    });

    window.addEventListener('popstate', function () {
        if (!switchAdminTab(window.location.href, false)) {
            loadAdminPanel(window.location.href, false);
        }
    });

    initTryoutScoringMode();
    initRichEditors();

    const toggle = document.getElementById('darkModeToggle');

    if (toggle) {
        if (localStorage.getItem('adminDarkMode') === 'enabled') {
            body.classList.add('dark-mode');
            toggle.innerHTML = '<i class="bi bi-sun-fill"></i><span>Light Mode</span>';
        }

        toggle.addEventListener('click', function () {
            body.classList.toggle('dark-mode');

            if (body.classList.contains('dark-mode')) {
                localStorage.setItem('adminDarkMode', 'enabled');
                toggle.innerHTML = '<i class="bi bi-sun-fill"></i><span>Light Mode</span>';
            } else {
                localStorage.setItem('adminDarkMode', 'disabled');
                toggle.innerHTML = '<i class="bi bi-moon-stars-fill"></i><span>Dark Mode</span>';
            }
        });
    }
});
