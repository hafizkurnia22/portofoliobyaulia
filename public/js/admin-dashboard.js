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