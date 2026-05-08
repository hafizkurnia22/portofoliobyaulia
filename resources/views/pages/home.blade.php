@extends('layouts.app')

@section('title', 'Beranda | Portfolio CV')

@section('content')

    <!-- Hero Carousel -->
    <section class="hero-section d-flex align-items-center">
        <div class="container">
            <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">

                <div class="carousel-inner">

                    <div class="carousel-item active">
                        <div class="row align-items-center">
                            <div class="col-md-7" data-aos="fade-right" data-aos-delay="200">
                                <h1 class="hero-title">
                                    Halo, Saya Aulia Nur Afifa
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
                                <img src="{{ asset('images/profile.jpeg') }}" class="hero-img" alt="Foto Profile">
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
                <h2 class="fw-bold text-primary-dark">Pengalaman Kerja</h2>
                <p>Beberapa pengalaman kerja saya di berbagai perusahaan.</p>
            </div>

            <div class="row g-4">

                @forelse($pengalaman as $item)
                    <div class="col-md-4" data-aos="fade-up">
                        <div class="card card-custom h-100 p-4 d-flex flex-column">

                            <div class="mb-3">
                                @if ($item->logo)
                                    <img src="{{ asset('images/' . $item->logo) }}" width="60"
                                        style="object-fit:contain;">
                                @else
                                    <i class="bi bi-building fs-1 text-primary-dark"></i>
                                @endif
                            </div>

                            <h5 class="fw-bold">
                                {{ $item->nama_perusahaan }}
                            </h5>

                            <p class="mb-1 text-primary-dark fw-semibold">
                                {{ $item->jabatan }}
                            </p>

                            <small class="text-muted">
                                {{ $item->periode }}
                            </small>

                            <p class="mt-3">
                                {{ $item->deskripsi }}
                            </p>

                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center" data-aos="fade-up">
                        <p class="text-muted">Belum ada data pengalaman</p>
                    </div>
                @endforelse
            </div>
            <div class="mt-5 d-flex justify-content-center">
                {{ $pengalaman->appends(request()->query())->fragment('pengalaman')->links() }} </div>
        </div>
    </section>

    <!-- Skill -->
    <section id="skill" class="section-padding">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold text-primary-dark">Skill</h2>
                <p>Kemampuan dan keahlian yang saya miliki.</p>
            </div>

            <div class="row g-4">
                @forelse($skill as $item)
                    <div class="col-md-6" data-aos="fade-up">
                        <div class="card card-custom p-4 h-100">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <h5 class="fw-bold text-primary-dark mb-1">
                                        {{ $item->nama_skill }}
                                    </h5>

                                    @if ($item->kategori)
                                        <small class="text-muted">
                                            {{ $item->kategori }}
                                        </small>
                                    @endif
                                </div>

                                <span class="admin-badge">
                                    {{ $item->persentase }}%
                                </span>
                            </div>

                            <div class="progress mt-3" style="height: 12px; border-radius: 30px;">
                                <div class="progress-bar bg-primary-dark" role="progressbar"
                                    style="width: {{ $item->persentase }}%;">
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
                            <div class="card card-custom p-4 mx-auto" style="max-width: 700px;">
                                <div class="row align-items-center">

                                    <div class="col-md-4 text-center mb-3 mb-md-0">
                                        <i class="bi bi-award-fill text-primary-dark" style="font-size: 80px;"></i>
                                    </div>

                                    <div class="col-md-8">
                                        <h4 class="fw-bold text-primary-dark">
                                            {{ $item->nama_sertifikat }}
                                        </h4>

                                        <p class="mb-1">
                                            <strong>Penyelenggara:</strong> {{ $item->penyelenggara }}
                                        </p>

                                        <p class="mb-1">
                                            <strong>Tahun:</strong> {{ $item->tahun }}
                                        </p>

                                        <p class="mb-3">
                                            {{ $item->deskripsi }}
                                        </p>

                                        @if ($item->file_pdf)
                                            <a href="{{ asset('sertifikat/' . $item->file_pdf) }}" target="_blank"
                                                class="btn-view-all">
                                                Lihat Sertifikat PDF
                                            </a>
                                        @endif
                                    </div>

                                </div>
                            </div>
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
    <section class="section-padding contact-cta text-center">
        <div class="container" data-aos="zoom-in">
            <h2 class="fw-bold">Tertarik Bekerja Sama?</h2>
            <p class="mt-3">
                Silakan hubungi saya untuk informasi lebih lanjut mengenai profil, pengalaman, dan portofolio saya.
            </p>
            <a href="#" class="btn btn-main mt-3">
                Hubungi Saya
            </a>
        </div>
    </section>

@endsection
