@php
    $availabilityText = trim($tentangSaya->status ?? 'Open for Collaboration');
@endphp

<nav class="navbar navbar-expand-xxl custom-navbar fixed-top" data-aos="fade-down">
    <div class="container">
        <a class="navbar-brand brand-premium" href="{{ url('/') }}" aria-label="Hafiz’s Portofolio — Beranda">
            <svg class="brand-logo-lockup" viewBox="525 260 1030 228" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                <image href="{{ asset('images/logo-mhk-premium.png') }}" width="2073" height="758" />
            </svg>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu"
            aria-controls="navbarMenu" aria-expanded="false" aria-label="Buka menu navigasi">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav nav-premium ms-auto align-items-xxl-center">

                <li class="nav-item">
                    <a class="nav-link nav-scroll-link" href="{{ url('/#home') }}" data-section="home">
                        <i class="bi bi-house-door"></i>
                        Beranda
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <button class="nav-link dropdown-toggle nav-more-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="bi bi-person-circle"></i>
                        Profile
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end premium-dropdown-menu">
                        <li>
                            <a class="dropdown-item nav-scroll-link" href="{{ url('/#pengalaman') }}"
                                data-section="pengalaman">
                                <i class="bi bi-briefcase"></i>
                                Pengalaman
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item nav-scroll-link" href="{{ url('/#skill') }}" data-section="skill">
                                <i class="bi bi-lightning-charge"></i>
                                Skill
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item nav-scroll-link" href="{{ url('/#my-project') }}"
                                data-section="my-project">
                                <i class="bi bi-kanban"></i>
                                My Project
                            </a>
                        </li>

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

                        @if ($availabilityText !== '')
                            <li>
                                <hr class="dropdown-divider">
                            </li>

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
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('tryout.index', 'tryout.login', 'tryout.materi.show') ? 'active' : '' }}"
                        href="{{ route('tryout.index') }}">
                        <i class="bi bi-pc-display-horizontal"></i>
                        Tryout CPNS
                    </a>
                </li>

                {{-- Menu ini menggantikan download langsung agar pengunjung bisa memilih template CV dulu. --}}
                <li class="nav-item">
                    <a class="nav-link nav-link-feature" href="{{ route('cv.builder') }}">
                        <i class="bi bi-file-earmark-richtext"></i>
                        Generate CV saya
                    </a>
                </li>

                <li class="nav-item nav-login-item">
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
        let currentSection = document.getElementById('home') ? 'home' : null;

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
            moreToggle.classList.toggle('active', ['pengalaman', 'skill', 'my-project', 'sertifikasi', 'tentang'].includes(currentSection));
        }
    }

    setActiveNav();
    window.addEventListener('scroll', setActiveNav);
</script>
