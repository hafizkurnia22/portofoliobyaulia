@extends('layouts.app')

@section('title', 'Beranda | Portfolio CV')

@section('content')
    @php
        $availabilityText = trim($tentangSaya->status ?? 'Open for Collaboration');
    @endphp

    <!-- Hero Carousel -->
    <section id="home" class="hero-section d-flex align-items-center" data-aos="fade-up">
        <div class="container">
            <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">

                <div class="carousel-inner">

                    <div class="carousel-item active">
                        <div class="row align-items-center">
                            <div class="col-md-7" data-aos="fade-right" data-aos-delay="200">
                                @if ($availabilityText !== '')
                                    <button type="button" class="hero-availability-card" data-bs-toggle="modal"
                                        data-bs-target="#smartContactModal">
                                        <span class="availability-live-dot"></span>
                                        <span>Live Availability</span>
                                        <strong>{{ $availabilityText }}</strong>
                                        <i class="bi bi-arrow-up-right"></i>
                                    </button>
                                @endif

                                <h1>
                                    Halo, Saya {{ $tentangSaya->nama ?? 'Nama Anda' }}
                                </h1>

                                <p class="hero-text mt-3">
                                    Saya seorang profesional yang memiliki pengalaman kerja,
                                    kemampuan teknis, dan semangat untuk terus berkembang.
                                </p>

                                <div class="mt-4" data-aos="fade-up" data-aos-delay="400">
                                    <a href="#tentang" class="btn btn-main me-2">Tentang Saya</a>
                                    <a href="#pengalaman" class="btn btn-outline-main">Lihat Pengalaman</a>
                                </div>
                            </div>

                            <div class="col-md-5 text-center mt-5 mt-md-0" data-aos="zoom-in" data-aos-delay="300">
                                <img src="{{ asset('images/' . ($tentangSaya->foto ?? 'profile.jpeg')) }}" class="hero-img"
                                    alt="Foto Profile">
                            </div>
                        </div>
                    </div>

                    <div class="carousel-item">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <h1 class="display-4 fw-bold">
                                    Portfolio & Curriculum Vitae
                                </h1>
                                <p class="lead mt-3">
                                    Website ini berisi profil, pengalaman kerja, skill, dan informasi profesional saya.
                                </p>
                                <a href="#skill" class="btn btn-main mt-3">Lihat Skill</a>
                            </div>

                            <div class="col-md-5 text-center mt-5 mt-md-0">
                                <i class="bi bi-person-workspace" style="font-size: 180px;"></i>
                            </div>
                        </div>
                    </div>

                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>

                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>

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
                            <p>{{ $project->deskripsi }}</p>

                            @if ($techList->isNotEmpty())
                                <div class="project-tech-list">
                                    @foreach ($techList as $tech)
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
    </section>

    <!-- Sertifikasi -->
    <section id="sertifikasi" class="section-padding bg-light">
        <div class="container">

            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold text-primary-dark">Sertifikasi</h2>
                <p>Beberapa sertifikasi dan pelatihan keahlian yang pernah saya ikuti.</p>
            </div>

            <div id="sertifikasiCarousel" class="carousel slide" data-bs-ride="carousel" data-aos="zoom-in">

                <div class="carousel-inner">

                    @forelse($sertifikasi as $index => $item)
                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                            <article class="certificate-showcase-card">
                                <div class="certificate-badge-panel">
                                    <div class="certificate-icon-shell">
                                        <i class="bi bi-award-fill"></i>
                                    </div>

                                    <div class="certificate-badge-copy">
                                        <span>Sertifikasi</span>
                                        <strong>{{ $item->tahun ?? 'Tahun' }}</strong>
                                    </div>
                                </div>

                                <div class="certificate-content">
                                    <span class="certificate-label">Sertifikasi Unggulan</span>

                                    <h4>{{ $item->nama_sertifikat }}</h4>

                                    <div class="certificate-meta-grid">
                                        <div class="certificate-meta-item">
                                            <small>Penyelenggara</small>
                                            <strong>{{ $item->penyelenggara }}</strong>
                                        </div>

                                        <div class="certificate-meta-item">
                                            <small>Tahun</small>
                                            <strong>{{ $item->tahun }}</strong>
                                        </div>
                                    </div>

                                    @if (!empty($item->deskripsi))
                                        <p class="certificate-description">
                                            {{ $item->deskripsi }}
                                        </p>
                                    @endif

                                    @if ($item->file_pdf)
                                        <a href="{{ asset('sertifikat/' . $item->file_pdf) }}" target="_blank"
                                            class="certificate-action">
                                            <i class="bi bi-file-earmark-pdf"></i>
                                            Lihat Sertifikat PDF
                                        </a>
                                    @else
                                        <span class="certificate-action certificate-action-muted">
                                            <i class="bi bi-file-earmark-lock"></i>
                                            PDF belum tersedia
                                        </span>
                                    @endif
                                </div>
                            </article>
                        </div>
                    @empty
                        <div class="carousel-item active">
                            <div class="text-center">
                                <p class="text-muted">
                                    Belum ada data sertifikasi
                                </p>
                            </div>
                        </div>
                    @endforelse

                </div>

                @if ($sertifikasi->count() > 1)
                    <div class="carousel-indicators custom-indicators">
                        @foreach ($sertifikasi as $index => $item)
                            <button type="button" data-bs-target="#sertifikasiCarousel"
                                data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}"
                                aria-label="Slide {{ $index + 1 }}">
                            </button>
                        @endforeach
                    </div>
                @endif

                @if ($sertifikasi->count() > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#sertifikasiCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon bg-primary-dark rounded-circle p-3"></span>
                    </button>

                    <button class="carousel-control-next" type="button" data-bs-target="#sertifikasiCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon bg-primary-dark rounded-circle p-3"></span>
                    </button>
                @endif

            </div>

            <div class="text-center mt-5" data-aos="fade-up" data-aos-delay="200">
                <a href="/sertifikasi" class="btn-view-all">
                    Lihat Semua Sertifikasi →
                </a>
            </div>

        </div>
    </section>

    <!-- Contact CTA -->
    <section class="section-padding contact-cta premium-contact-cta text-center">
        <div class="container" data-aos="zoom-in">
            <span class="contact-cta-kicker">
                <span class="availability-live-dot"></span>
                Smart Contact
            </span>

            <h2 class="fw-bold">Mulai Percakapan yang Tepat</h2>
            <p class="mt-3">
                Pilih tujuan kontak agar pesan WhatsApp langsung rapi, profesional, dan sesuai kebutuhan Anda.
            </p>

            <div class="contact-intent-preview" aria-hidden="true">
                <span><i class="bi bi-briefcase-fill"></i> Rekrutmen</span>
                <span><i class="bi bi-stars"></i> Kerja Sama</span>
                <span><i class="bi bi-file-earmark-person-fill"></i> Minta CV</span>
            </div>

            <button type="button" class="btn btn-main contact-intent-button" data-bs-toggle="modal"
                data-bs-target="#smartContactModal">

                <i class="bi bi-whatsapp"></i>
                Pilih Tujuan Kontak

            </button>
        </div>
    </section>

@endsection
