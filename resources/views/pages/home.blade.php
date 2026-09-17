@extends('layouts.app')

@section('title', 'Beranda | Portfolio CV')

@section('content')
    @php
        $availabilityText = trim($tentangSaya->status ?? 'Open for Collaboration');
    @endphp

    <section id="home" class="portfolio-intro" aria-label="Perkenalan dan portofolio">
        <div class="container">
            <div id="portfolioCarousel" class="carousel slide" data-bs-interval="false" role="region" aria-roledescription="carousel" aria-label="Kenali Hafiz" tabindex="0">
                <div class="carousel-inner">
                    <div class="carousel-item active" role="group" aria-roledescription="slide" aria-label="1 dari 3: Perkenalan">
                        <div class="portfolio-intro-grid">
                            <div class="portfolio-intro-copy">
                                @if ($availabilityText !== '')
                                    @php
                                        $friendlyAvailability = match (strtolower($availabilityText)) {
                                            'open to freelance', 'open for freelance' => 'Terbuka untuk project freelance',
                                            'open for collaboration' => 'Terbuka untuk kerja sama',
                                            'open to work' => 'Terbuka untuk peluang kerja',
                                            default => $availabilityText,
                                        };
                                    @endphp
                                    <div class="intro-status"><i class="bi bi-briefcase" aria-hidden="true"></i><span>{{ $friendlyAvailability }}</span></div>
                                @endif
                                <p class="intro-greeting">Halo, saya</p>
                                <h1 id="intro-title">{{ $tentangSaya->nama ?? 'Hafiz' }}</h1>
                                <p class="intro-role">{{ $tentangSaya->bidang ?? 'Pengembang website' }}</p>
                                <div class="intro-actions">
                                    <button type="button" class="intro-primary" data-bs-toggle="modal" data-bs-target="#smartContactModal">Mari berdiskusi <i class="bi bi-arrow-up-right" aria-hidden="true"></i></button>
                                    <a href="#tentang" class="intro-secondary">Tentang saya <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            <div class="intro-portrait">
                                <div class="intro-portrait-frame">
                                    <img src="{{ asset('images/' . ($tentangSaya->foto ?? 'profile.jpeg')) }}" alt="Potret {{ $tentangSaya->nama ?? 'Hafiz' }}" fetchpriority="high" width="440" height="440">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item" role="group" aria-roledescription="slide" aria-label="2 dari 3: Karya digital">
                        <div class="portfolio-intro-grid">
                            <div class="portfolio-intro-copy">
                                <p class="intro-eyebrow">KARYA DIGITAL</p>
                                <h2 class="intro-slide-title">Dari ide menjadi<br><span>aplikasi nyata.</span></h2>
                                <p class="intro-slide-description">Jelajahi website dan sistem informasi yang saya kembangkan, beserta fitur dan teknologi di baliknya.</p>
                                <div class="intro-skills" aria-label="Teknologi"><span>Laravel</span><span>PHP</span><span>MySQL</span></div>
                                <a href="#my-project" class="intro-primary">Jelajahi project <i class="bi bi-arrow-up-right" aria-hidden="true"></i></a>
                            </div>
                            <div class="intro-art"><img src="{{ asset('images/hero-developer-3d.webp') }}" alt="Ilustrasi 3D Hafiz mengembangkan website di laptop" width="1254" height="1254" decoding="async"></div>
                        </div>
                    </div>
                    <div class="carousel-item" role="group" aria-roledescription="slide" aria-label="3 dari 3: Pengalaman dan keahlian">
                        <div class="portfolio-intro-grid">
                            <div class="portfolio-intro-copy">
                                <p class="intro-eyebrow">PENGALAMAN & KEAHLIAN</p>
                                <h2 class="intro-slide-title">Terus belajar.<br><span>Terus berkarya.</span></h2>
                                <p class="intro-slide-description">Kenali perjalanan saya dalam teknologi informasi, analisis data, dan pengelolaan aplikasi.</p>
                                <div class="intro-actions">
                                    <a href="#pengalaman" class="intro-primary">Lihat pengalaman <i class="bi bi-arrow-up-right" aria-hidden="true"></i></a>
                                    <a href="#skill" class="intro-secondary">Lihat keahlian <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            <div class="intro-art"><img src="{{ asset('images/hero-analyst-3d.webp') }}" alt="Ilustrasi 3D Hafiz menjelaskan analisis data dan sistem" width="1254" height="1254" decoding="async"></div>
                        </div>
                    </div>
                </div>
                <div class="intro-carousel-toolbar">
                    <div class="carousel-indicators intro-slide-picker">
                        <button type="button" data-bs-target="#portfolioCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1: Perkenalan"><span>01</span> Perkenalan</button>
                        <button type="button" data-bs-target="#portfolioCarousel" data-bs-slide-to="1" aria-label="Slide 2: Karya digital"><span>02</span> Karya</button>
                        <button type="button" data-bs-target="#portfolioCarousel" data-bs-slide-to="2" aria-label="Slide 3: Pengalaman"><span>03</span> Pengalaman</button>
                    </div>
                    <div class="intro-slide-arrows">
                        <button type="button" data-bs-target="#portfolioCarousel" data-bs-slide="prev" aria-label="Slide sebelumnya"><i class="bi bi-arrow-left" aria-hidden="true"></i></button>
                        <button type="button" data-bs-target="#portfolioCarousel" data-bs-slide="next" aria-label="Slide berikutnya"><i class="bi bi-arrow-right" aria-hidden="true"></i></button>
                    </div>
                </div>
                <span class="visually-hidden" id="introSlideAnnouncement" aria-live="polite" aria-atomic="true">Slide 1 dari 3: Perkenalan</span>
            </div>
        </div>
    </section>

    <!-- About -->
    <section id="tentang" class="about-section">
        <div class="container">
            <div class="row align-items-center g-5">

                <!-- FOTO -->
                <div class="col-lg-5 text-center" data-aos="fade-right" data-aos-delay="100">

                    <div class="about-photo-wrapper">

                        <img src="{{ asset('images/' . ($tentangSaya->foto ?? 'default.png')) }}" alt="Profile"
                            class="about-photo">

                        <div class="about-badge">
                            <i class="bi bi-stars"></i>
                            Professional Profile
                        </div>

                    </div>
                </div>

                <!-- TEKS -->
                <div class="col-lg-7" data-aos="fade-left" data-aos-delay="200">

                    <span class="section-label">
                        Tentang Saya
                    </span>

                    <h2 class="about-title">
                        Halo, saya {{ $tentangSaya->nama ?? 'Nama Anda' }}
                    </h2>

                    <p class="about-desc">
                        {{ $tentangSaya->deskripsi_1 ?? '' }}
                    </p>

                    <p class="about-desc">
                        {{ $tentangSaya->deskripsi_2 ?? '' }}
                    </p>

                    <!-- CARD INFO -->
                    <div class="row g-3 mt-4">

                        <!-- NAMA -->
                        <div class="col-md-4" data-aos="flip-left" data-aos-delay="300">

                            <div class="about-info-card">
                                <i class="bi bi-person-check"></i>

                                <h6>Nama</h6>

                                <p>
                                    {{ $tentangSaya->nama ?? '-' }}
                                </p>
                            </div>
                        </div>

                        <!-- BIDANG -->
                        <div class="col-md-4" data-aos="flip-left" data-aos-delay="400">

                            <div class="about-info-card">
                                <i class="bi bi-briefcase"></i>

                                <h6>Bidang</h6>

                                <p>
                                    {{ $tentangSaya->bidang ?? '-' }}
                                </p>
                            </div>
                        </div>

                        <!-- STATUS -->
                        <div class="col-md-4" data-aos="flip-left" data-aos-delay="500">

                            <div class="about-info-card">
                                <i class="bi bi-graph-up-arrow"></i>

                                <h6>Status</h6>

                                <p>
                                    {{ $tentangSaya->status ?? '-' }}
                                </p>
                            </div>
                        </div>

                    </div>

                    <!-- BUTTON -->
                    <div class="mt-4" data-aos="fade-up" data-aos-delay="600">

                        <a href="#pengalaman" class="btn-about-primary">
                            Lihat Pengalaman
                        </a>

                        <a href="#sertifikasi" class="btn-about-outline">
                            Sertifikasi
                        </a>

                    </div>

                </div>

            </div>
        </div>
    </section>

    <!-- Pengalaman -->
    <section id="pengalaman" class="section-padding bg-light">
        <div class="container">

            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-label">Career Journey</span>
                <h2 class="fw-bold text-primary-dark mt-3">Pengalaman Kerja</h2>
                <p>Perjalanan pengalaman profesional saya.</p>
            </div>

            <div class="timeline-wrapper">

                @forelse($pengalaman as $index => $item)
                    <div class="timeline-item {{ $index % 2 == 0 ? 'left' : 'right' }}" data-aos="fade-up">

                        <div class="timeline-dot">
                            <i class="bi bi-briefcase-fill"></i>
                        </div>

                        <div class="timeline-card">

                            <div class="timeline-logo">
                                @if ($item->logo)
                                    <img src="{{ asset('images/' . $item->logo) }}" alt="{{ $item->nama_perusahaan }}">
                                @else
                                    <i class="bi bi-building"></i>
                                @endif
                            </div>

                            <div class="timeline-content">
                                <span class="timeline-period">
                                    {{ $item->periode }}
                                </span>

                                <h4>{{ $item->jabatan }}</h4>

                                <h6>
                                    <i class="bi bi-building"></i>
                                    {{ $item->nama_perusahaan }}
                                </h6>

                                <p>{{ $item->deskripsi }}</p>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted">
                        Belum ada data pengalaman.
                    </div>
                @endforelse

            </div>

            <div class="mt-5 d-flex justify-content-center">
                {{ $pengalaman->appends(request()->query())->fragment('pengalaman')->links() }}
            </div>

        </div>
    </section>

    <!-- Skill -->
    <section id="skill" class="section-padding skill-section-simple">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold text-primary-dark">Skill</h2>
                <p>Kemampuan dan keahlian yang saya miliki.</p>
            </div>

            <div class="row g-4">
                @forelse($skill as $item)
                    @php
                        $percentage = max(0, min(100, (int) $item->persentase));
                        $progressClass = match (true) {
                            $percentage >= 85 => 'skill-range-expert',
                            $percentage >= 75 => 'skill-range-strong',
                            $percentage >= 60 => 'skill-range-medium',
                            default => 'skill-range-basic',
                        };
                    @endphp

                    <div class="col-md-6" data-aos="fade-up">
                        <div class="card card-custom skill-simple-card p-4 h-100">
                            <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                <div class="skill-simple-title">
                                    <h5 class="fw-bold text-primary-dark mb-1">
                                        {{ $item->nama_skill }}
                                    </h5>

                                    @if ($item->kategori)
                                        <small class="text-muted">
                                            {{ $item->kategori }}
                                        </small>
                                    @endif
                                </div>

                                <span class="skill-percent-badge {{ $progressClass }}">
                                    {{ $percentage }}%
                                </span>
                            </div>

                            <div class="progress skill-gradient-track mt-3">
                                <div class="progress-bar skill-gradient-fill {{ $progressClass }}" role="progressbar"
                                    style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}"
                                    aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p class="text-muted">Belum ada data skill.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
    <!-- My Project -->
    <section id="my-project" class="project-section section-padding">
        <div class="container">
            <div class="project-header" data-aos="fade-up">
                <div>
                    <span class="section-label project-label">My Project</span>
                    <h2 class="project-title">Project Pilihan yang Pernah Saya Buat</h2>
                    <p class="project-subtitle">
                        Kumpulan karya digital dengan fokus pada tampilan profesional, alur yang jelas, dan pengalaman
                        pengguna yang nyaman.
                    </p>
                </div>

                <div class="project-summary">
                    <div>
                        <strong>{{ $projects->count() }}</strong>
                        <span>Project</span>
                    </div>
                    <div>
                        <strong>Web</strong>
                        <span>Based</span>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-2">
                @forelse ($projects as $index => $project)
                    @php
                        $accents = ['gold', 'emerald', 'blue', 'rose'];
                        $accent = $accents[$index % count($accents)];
                        $techList = collect(explode(',', $project->teknologi ?? ''))->map(function ($tech) {
                            return trim($tech);
                        })->filter();
                    @endphp

                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="{{ 100 + $index * 100 }}">
                        <article class="project-card project-accent-{{ $accent }}">
                            @if ($project->gambar)
                                <div class="project-cover">
                                    <img src="{{ asset('images/projects/' . $project->gambar) }}"
                                        alt="{{ $project->nama_project }}">
                                </div>
                            @endif

                            <div class="project-card-top">
                                <div class="project-icon">
                                    <i class="bi bi-kanban"></i>
                                </div>

                                <span class="project-category">
                                    {{ $project->kategori ?? 'Project' }}
                                </span>
                            </div>

                            <h3>{{ $project->nama_project }}</h3>
                            <p class="project-description">
                                {{ \Illuminate\Support\Str::limit(strip_tags($project->deskripsi), 190) }}
                            </p>

                            @if ($techList->isNotEmpty())
                                <div class="project-tech-list">
                                    @foreach ($techList as $tech)
                                        <span>{{ $tech }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <div class="project-actions">
                                <button type="button" class="project-link project-link-outline"
                                    data-bs-toggle="modal" data-bs-target="#projectDetailModal{{ $project->id }}">
                                    Baca Selengkapnya
                                    <i class="bi bi-arrow-right"></i>
                                </button>

                                @if ($project->link_demo)
                                    <a href="{{ $project->link_demo }}" target="_blank" class="project-link">
                                        Demo
                                        <i class="bi bi-arrow-up-right"></i>
                                    </a>
                                @endif

                                @if ($project->link_repository)
                                    <a href="{{ $project->link_repository }}" target="_blank"
                                        class="project-link project-link-outline">
                                        Repository
                                        <i class="bi bi-github"></i>
                                    </a>
                                @endif

                                @if (!$project->link_demo && !$project->link_repository)
                                    <span class="project-link-muted">
                                        Link belum tersedia
                                    </span>
                                @endif
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12" data-aos="fade-up">
                        <div class="project-empty-state">
                            <i class="bi bi-folder2-open"></i>
                            <h3>Belum ada project yang ditampilkan</h3>
                            <p>Project yang ditambahkan dari dashboard admin akan tampil otomatis di bagian ini.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        @foreach ($projects as $project)
            @php
                $modalTechList = collect(explode(',', $project->teknologi ?? ''))->map(function ($tech) {
                    return trim($tech);
                })->filter();
            @endphp

            <div class="modal fade project-detail-modal" id="projectDetailModal{{ $project->id }}" tabindex="-1"
                aria-labelledby="projectDetailModalLabel{{ $project->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <button type="button" class="btn-close project-detail-close" data-bs-dismiss="modal"
                            aria-label="Tutup"></button>

                        @if ($project->gambar)
                            <img src="{{ asset('images/projects/' . $project->gambar) }}"
                                alt="{{ $project->nama_project }}" class="project-detail-cover">
                        @endif

                        <div class="project-detail-body">
                            <span>{{ $project->kategori ?? 'Project' }}</span>
                            <h3 id="projectDetailModalLabel{{ $project->id }}">{{ $project->nama_project }}</h3>
                            <p>{{ $project->deskripsi }}</p>

                            @if ($modalTechList->isNotEmpty())
                                <div class="project-tech-list">
                                    @foreach ($modalTechList as $tech)
                                        <span>{{ $tech }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <div class="project-actions">
                                @if ($project->link_demo)
                                    <a href="{{ $project->link_demo }}" target="_blank" class="project-link">
                                        Demo
                                        <i class="bi bi-arrow-up-right"></i>
                                    </a>
                                @endif

                                @if ($project->link_repository)
                                    <a href="{{ $project->link_repository }}" target="_blank"
                                        class="project-link project-link-outline">
                                        Repository
                                        <i class="bi bi-github"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </section>

    <!-- Sertifikasi -->
    <section id="sertifikasi" class="section-padding bg-light">
        <div class="container">

            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold text-primary-dark">Sertifikasi</h2>
                <p>Beberapa sertifikasi dan pelatihan keahlian yang pernah saya ikuti.</p>
            </div>

            <div class="certificate-grid" data-aos="fade-up">
                @forelse($sertifikasi as $item)
                    <article class="certificate-card">
                        <div class="certificate-card-icon">
                            <i class="bi bi-award-fill"></i>
                        </div>

                        <div class="certificate-card-body">
                            <span class="certificate-card-year">{{ $item->tahun ?? 'Tahun' }}</span>
                            <h4>{{ $item->nama_sertifikat }}</h4>
                            <p class="certificate-card-provider">{{ $item->penyelenggara }}</p>

                            @if (!empty($item->deskripsi))
                                <p class="certificate-card-description">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($item->deskripsi), 150) }}
                                </p>
                            @endif
                        </div>

                        @if ($item->file_pdf)
                            <a href="{{ asset('sertifikat/' . $item->file_pdf) }}" target="_blank"
                                class="certificate-card-action">
                                <i class="bi bi-file-earmark-pdf"></i>
                                Lihat PDF
                            </a>
                        @else
                            <span class="certificate-card-action certificate-card-action-muted">
                                <i class="bi bi-file-earmark-lock"></i>
                                PDF belum tersedia
                            </span>
                        @endif
                    </article>
                @empty
                    <div class="certificate-empty">
                        <i class="bi bi-award"></i>
                        <p>Belum ada data sertifikasi</p>
                    </div>
                @endforelse
            </div>

            <div class="text-center mt-5" data-aos="fade-up" data-aos-delay="200">
                <a href="/sertifikasi" class="btn-view-all">
                    Lihat Semua Sertifikasi →
                </a>
            </div>

        </div>
    </section>

    <!-- Contact CTA -->
    <section class="footer-invitation" aria-labelledby="footerInvitationTitle">
        <div class="container footer-invitation-inner">
            <div>
            <span class="footer-invitation-label">KONTAK & KERJA SAMA</span>

            <h2 id="footerInvitationTitle">Punya ide atau peluang? Mari berdiskusi.</h2>
            <p class="mt-3">
                Sampaikan kebutuhan Anda, mulai dari pengembangan website hingga peluang karier.
            </p>

            </div>

            <button type="button" class="footer-invitation-button" data-bs-toggle="modal"
                data-bs-target="#smartContactModal">

                <i class="bi bi-whatsapp"></i>
                Mulai percakapan

            </button>
        </div>
    </section>

@endsection
