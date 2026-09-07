document.addEventListener('DOMContentLoaded', function () {

    // SWEETALERT DELETE
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function () {
            const form = this.closest('.delete-form');

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
        });
    });

    // LIVE SEARCH
    document.querySelectorAll('.admin-live-search').forEach(input => {
        input.addEventListener('keyup', function () {
            const keyword = this.value.toLowerCase();
            const tableId = this.getAttribute('data-target');
            const table = document.getElementById(tableId);

            if (!table) return;

            table.querySelectorAll('tbody tr').forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(keyword) ? '' : 'none';
            });
        });
    });

    // TRYOUT SCORING MODE
    document.querySelectorAll('.tryout-soal-form').forEach(form => {
        const categorySelect = form.querySelector('.tryout-kategori-select');
        const answerGroup = form.querySelector('.tryout-answer-key-group');
        const answerSelect = answerGroup ? answerGroup.querySelector('select[name="jawaban_benar"]') : null;
        const scoreGroup = form.querySelector('.tryout-score-group');
        const scoreInputs = form.querySelectorAll('.tryout-score-input');

        if (!categorySelect) return;

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

    // RICH TEXT EDITOR
    document.querySelectorAll('[data-rich-editor]').forEach(editor => {
        const toolbar = editor.querySelector('.rich-editor-toolbar');
        const area = editor.querySelector('.rich-editor-area');
        const input = editor.querySelector('.rich-editor-input');
        const form = editor.closest('form');

        if (!toolbar || !area || !input || !form) return;

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

    // DARK MODE
    const toggle = document.getElementById('darkModeToggle');
    const body = document.body;

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
