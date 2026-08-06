<nav class="navbar navbar-expand-lg custom-navbar fixed-top" data-aos="fade-down">
    <div class="container">
        <a class="navbar-brand" href="#">
            My Portfolio
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link" href="/#">Beranda</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/#pengalaman">Pengalaman</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/#skill">Skill</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/#sertifikasi">Sertifikasi</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/#tentang">Tentang Saya</a>
                </li>

                {{-- Menu ini menggantikan download langsung agar pengunjung bisa memilih template CV dulu. --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('cv.builder') }}">
                        Generate CV saya
                    </a>
                </li>

                <li class="nav-item ms-lg-3">
                    <a href="/admin/login" class="btn-login-nav">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Login Admin
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>
<script>
    window.addEventListener('scroll', function() {

        const navbar = document.querySelector('.custom-navbar');

        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }

    });
</script>
