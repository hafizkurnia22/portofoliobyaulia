@php
    $availabilityText = trim($tentangSaya->status ?? 'Open for Collaboration');
@endphp

<nav class="navbar navbar-expand-xl custom-navbar fixed-top" data-aos="fade-down">
    <div class="container">
        <a class="navbar-brand brand-premium" href="{{ url('/') }}">
            <span class="brand-logo-shell">
                <img src="{{ asset('images/logo-mhk.png') }}" class="brand-logo-img" alt="MHK Logo">
            </span>
            <span class="brand-copy-stack">
                <span class="brand-text-shine brand-text-top">Hafiz's</span>
                <span class="brand-text-shine brand-text-bottom">Portofolio</span>
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

                <li class="nav-item dropdown">
                    <button class="nav-link dropdown-toggle nav-more-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="bi bi-grid"></i>
                        Lainnya
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end premium-dropdown-menu">
                        @if ($availabilityText !== '')
                            <li>
                                <button type="button" class="dropdown-item premium-dropdown-status"
                                    data-bs-toggle="modal" data-bs-target="#smartContactModal">
                                    <span class="availability-live-dot"></span>
                                    <span>
                                        <small>Availability</small>
                                        <strong>{{ $availabilityText }}</strong>
                                    </span>
                                </button>
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>
                        @endif

                        <li>
                            <a class="dropdown-item nav-scroll-link" href="{{ url('/#sertifikasi') }}"
                                data-section="sertifikasi">
                                <i class="bi bi-award"></i>
                                Sertifikasi
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item nav-scroll-link" href="{{ url('/#tentang') }}" data-section="tentang">
                                <i class="bi bi-person"></i>
                                Tentang Saya
                            </a>
                        </li>
                    </ul>
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
    const moreToggle = document.querySelector('.nav-more-toggle');

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

        if (moreToggle) {
            moreToggle.classList.toggle('active', ['sertifikasi', 'tentang'].includes(currentSection));
        }
    }

    setActiveNav();
    window.addEventListener('scroll', setActiveNav);
</script>
