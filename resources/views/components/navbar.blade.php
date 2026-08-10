<nav class="navbar navbar-expand-xl custom-navbar fixed-top" data-aos="fade-down">
    <div class="container">
        <a class="navbar-brand brand-premium" href="{{ url('/') }}">
            <span class="brand-icon">
                <i class="bi bi-gem"></i>
            </span>
            <span class="brand-copy">
                <strong>My Portfolio</strong>
                <small>Personal CV</small>
            </span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu"
            aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav nav-premium ms-auto align-items-xl-center">

                <li class="nav-item">
                    <a class="nav-link nav-scroll-link" href="{{ url('/#home') }}" data-section="home">
                        <i class="bi bi-house-door"></i>
                        Beranda
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link nav-scroll-link" href="{{ url('/#pengalaman') }}" data-section="pengalaman">
                        <i class="bi bi-briefcase"></i>
                        Pengalaman
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link nav-scroll-link" href="{{ url('/#skill') }}" data-section="skill">
                        <i class="bi bi-lightning-charge"></i>
                        Skill
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link nav-scroll-link" href="{{ url('/#my-project') }}" data-section="my-project">
                        <i class="bi bi-kanban"></i>
                        My Project
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link nav-scroll-link" href="{{ url('/#sertifikasi') }}" data-section="sertifikasi">
                        <i class="bi bi-award"></i>
                        Sertifikasi
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link nav-scroll-link" href="{{ url('/#tentang') }}" data-section="tentang">
                        <i class="bi bi-person"></i>
                        Tentang Saya
                    </a>
                </li>

                {{-- Menu ini menggantikan download langsung agar pengunjung bisa memilih template CV dulu. --}}
                <li class="nav-item">
                    <a class="nav-link nav-link-feature" href="{{ route('cv.builder') }}">
                        <i class="bi bi-file-earmark-richtext"></i>
                        Generate CV
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

        if (!navbar) {
            return;
        }

        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }

    });

    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-scroll-link');

    function setActiveNav() {
        let currentSection = 'home';

        sections.forEach(function(section) {
            const sectionTop = section.offsetTop - 140;

            if (window.scrollY >= sectionTop) {
                currentSection = section.getAttribute('id');
            }
        });

        navLinks.forEach(function(link) {
            link.classList.toggle('active', link.dataset.section === currentSection);
        });
    }

    setActiveNav();
    window.addEventListener('scroll', setActiveNav);
</script>
