@extends('layouts.app')

@section('title', 'Beranda | Portofolio')

@section('content')
    @php
        $name = $tentangSaya->nama ?? 'Hafiz';
        $photo = asset('images/' . ($tentangSaya->foto ?? 'profile.jpeg'));
        $availability = trim($tentangSaya->status ?? 'Open for Collaboration');
        $availabilityLabel = match (strtolower($availability)) {
            'open to freelance', 'open for freelance' => 'Terbuka untuk proyek freelance',
            'open for collaboration' => 'Terbuka untuk kerja sama',
            'open to work' => 'Terbuka untuk peluang kerja',
            default => $availability,
        };
    @endphp

    <section id="home" class="portfolio-hero" aria-labelledby="portfolio-name">
        <div class="container">
            <div class="portfolio-hero-grid">
                <a href="#pengalaman" class="portfolio-hero-side portfolio-hero-profile">
                    <span class="portfolio-hero-title">profil<span>.</span></span>
                    <span class="portfolio-hero-description">Kenali pengalaman saya dalam teknologi informasi dan pengelolaan aplikasi.</span>
                    <span class="portfolio-hero-link">Lihat pengalaman <i class="bi bi-arrow-up-right" aria-hidden="true"></i></span>
                </a>

                <figure class="portfolio-portrait">
                    <div class="portfolio-portrait-mat">
                        <img src="{{ $photo }}" alt="Foto profil {{ $name }}" fetchpriority="high">
                        <div class="portfolio-portrait-ink" aria-hidden="true">
                            <img src="{{ $photo }}" alt="">
                        </div>
                    </div>
                    <figcaption>
                        <h1 id="portfolio-name">{{ $name }}</h1>
                        <p class="portfolio-role">{{ $tentangSaya->bidang ?? 'Pengembang website' }}</p>
                    </figcaption>
                </figure>

                <a href="#my-project" class="portfolio-hero-side portfolio-hero-code">
                    <span class="portfolio-hero-title"><span>&lt;</span>kode<span>&gt;</span></span>
                    <span class="portfolio-hero-description">Membangun aplikasi web dan sistem informasi yang rapi, cepat, dan mudah digunakan.</span>
                    <span class="portfolio-hero-link">Lihat karya <i class="bi bi-arrow-up-right" aria-hidden="true"></i></span>
                </a>
            </div>
            <div class="portfolio-hero-contact">
                @if ($availabilityLabel !== '')
                    <span class="portfolio-availability">{{ $availabilityLabel }}</span>
                @endif
                <button type="button" class="portfolio-text-link" data-bs-toggle="modal" data-bs-target="#smartContactModal">
                    Diskusikan proyek <i class="bi bi-arrow-up-right" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    </section>

    <section id="my-project" class="portfolio-work portfolio-section" aria-labelledby="portfolio-work-title">
        <div class="container">
            <header class="portfolio-work-heading">
                <h2 id="portfolio-work-title">Karya pilihan</h2>
            </header>

            <div class="portfolio-work-grid">
                @forelse ($projects as $project)
                    @php
                        $technologies = collect(explode(',', $project->teknologi ?? ''))
                            ->map(fn ($technology) => trim($technology))
                            ->filter();
                    @endphp
                    <article class="portfolio-work-entry">
                        <div class="portfolio-work-image">
                            @if ($project->gambar)
                                <img src="{{ asset('images/projects/' . $project->gambar) }}"
                                    alt="Pratinjau {{ $project->nama_project }}" loading="lazy" decoding="async">
                            @else
                                <i class="bi bi-window" aria-hidden="true"></i>
                            @endif
                        </div>
                        <div class="portfolio-work-copy">
                            <p class="portfolio-label">{{ $project->kategori ?? 'Proyek' }}</p>
                            <h3>{{ $project->nama_project }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit(strip_tags($project->deskripsi), 190) }}</p>
                            @if ($technologies->isNotEmpty())
                                <ul class="portfolio-technologies" aria-label="Teknologi">
                                    @foreach ($technologies as $technology)
                                        <li>{{ $technology }}</li>
                                    @endforeach
                                </ul>
                            @endif

                            <div class="portfolio-actions">
                                <button type="button" class="portfolio-text-link" data-bs-toggle="modal"
                                    data-bs-target="#projectDetailModal{{ $project->id }}">
                                    Detail proyek <i class="bi bi-arrow-up-right" aria-hidden="true"></i>
                                </button>
                                @if ($project->link_demo)
                                    <a class="portfolio-text-link" href="{{ $project->link_demo }}" target="_blank" rel="noopener noreferrer">
                                        Demo <i class="bi bi-arrow-up-right" aria-hidden="true"></i>
                                    </a>
                                @endif
                                @if ($project->link_repository)
                                    <a class="portfolio-text-link" href="{{ $project->link_repository }}" target="_blank" rel="noopener noreferrer">
                                        Repository <i class="bi bi-github" aria-hidden="true"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </article>

                    <div class="modal fade project-detail-modal" id="projectDetailModal{{ $project->id }}" tabindex="-1"
                        aria-labelledby="projectDetailModalLabel{{ $project->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <button type="button" class="btn-close project-detail-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                @if ($project->gambar)
                                    <img src="{{ asset('images/projects/' . $project->gambar) }}" alt="Pratinjau {{ $project->nama_project }}"
                                        class="project-detail-cover" loading="lazy">
                                @endif
                                <div class="project-detail-body">
                                    <p class="portfolio-label">{{ $project->kategori ?? 'Proyek' }}</p>
                                    <h3 id="projectDetailModalLabel{{ $project->id }}">{{ $project->nama_project }}</h3>
                                    <p>{{ $project->deskripsi }}</p>
                                    @if ($technologies->isNotEmpty())
                                        <ul class="portfolio-technologies" aria-label="Teknologi">
                                            @foreach ($technologies as $technology)
                                                <li>{{ $technology }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                    <div class="portfolio-actions">
                                        @if ($project->link_demo)
                                            <a class="portfolio-text-link" href="{{ $project->link_demo }}" target="_blank" rel="noopener noreferrer">
                                                Demo <i class="bi bi-arrow-up-right" aria-hidden="true"></i>
                                            </a>
                                        @endif
                                        @if ($project->link_repository)
                                            <a class="portfolio-text-link" href="{{ $project->link_repository }}" target="_blank" rel="noopener noreferrer">
                                                Repository <i class="bi bi-github" aria-hidden="true"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="portfolio-empty">Belum ada proyek yang ditampilkan.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section id="tentang" class="portfolio-about portfolio-section" aria-labelledby="portfolio-about-title">
        <div class="container portfolio-section-grid">
            <header><h2 id="portfolio-about-title">Tentang saya</h2></header>
            <div class="portfolio-about-copy">
                @if (!empty($tentangSaya->deskripsi_1))
                    <p>{{ $tentangSaya->deskripsi_1 }}</p>
                @endif
                @if (!empty($tentangSaya->deskripsi_2))
                    <p>{{ $tentangSaya->deskripsi_2 }}</p>
                @endif

                <dl class="portfolio-profile">
                    <div>
                        <dt>Nama</dt>
                        <dd>{{ $tentangSaya->nama ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt>Bidang</dt>
                        <dd>{{ $tentangSaya->bidang ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt>Status</dt>
                        <dd>{{ $tentangSaya->status ?? '-' }}</dd>
                    </div>
                </dl>
                <div class="portfolio-actions">
                    <a href="#pengalaman" class="portfolio-text-link">Lihat pengalaman <i class="bi bi-arrow-down" aria-hidden="true"></i></a>
                    <a href="{{ route('cv.builder') }}" class="portfolio-text-link">Generate CV saya <i class="bi bi-arrow-up-right" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>
    </section>

    <section id="pengalaman" class="portfolio-career portfolio-section" aria-labelledby="portfolio-career-title">
        <div class="container portfolio-section-grid">
            <header><h2 id="portfolio-career-title">Pengalaman kerja</h2></header>
            <div>
                @forelse ($pengalaman as $item)
                    <article class="portfolio-career-entry">
                        <p class="portfolio-label">{{ $item->periode }}</p>
                        <div>
                            <h3>{{ $item->jabatan }}</h3>
                            <p class="portfolio-company">
                                @if ($item->logo)
                                    <img src="{{ asset('images/' . $item->logo) }}" alt="" width="32" height="32" loading="lazy">
                                @endif
                                {{ $item->nama_perusahaan }}
                            </p>
                            <p class="portfolio-career-description">{{ $item->deskripsi }}</p>
                        </div>
                    </article>
                @empty
                    <p class="portfolio-empty">Belum ada data pengalaman.</p>
                @endforelse

                @if ($pengalaman->hasPages())
                    <div class="portfolio-pages">
                        {{ $pengalaman->appends(request()->query())->fragment('pengalaman')->links() }}
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section id="skill" class="portfolio-skills portfolio-section" aria-labelledby="portfolio-skills-title">
        <div class="container portfolio-section-grid">
            <header><h2 id="portfolio-skills-title">Keahlian</h2></header>
            <div class="portfolio-skill-list">
                @forelse ($skill as $item)
                    @php
                        $percentage = max(0, min(100, (int) $item->persentase));
                    @endphp
                    <article class="portfolio-skill">
                        <div>
                            <h3>{{ $item->nama_skill }}</h3>
                            @if ($item->kategori)
                                <p>{{ $item->kategori }}</p>
                            @endif
                        </div>
                        <div class="portfolio-skill-level">
                            <meter min="0" max="100" value="{{ $percentage }}" aria-label="Tingkat keahlian {{ $item->nama_skill }}"></meter>
                            <span aria-hidden="true">{{ $percentage }}%</span>
                        </div>
                    </article>
                @empty
                    <p class="portfolio-empty">Belum ada data keahlian.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section id="sertifikasi" class="portfolio-certificates portfolio-section" aria-labelledby="portfolio-certificates-title">
        <div class="container portfolio-section-grid">
            <header>
                <h2 id="portfolio-certificates-title">Sertifikasi</h2>
                <a href="{{ url('/sertifikasi') }}" class="portfolio-text-link">Lihat semua <i class="bi bi-arrow-up-right" aria-hidden="true"></i></a>
            </header>
            <div>
                @forelse ($sertifikasi as $item)
                    <article class="portfolio-certificate">
                        <p class="portfolio-label">{{ $item->tahun ?? 'Tahun' }}</p>
                        <div>
                            <h3>{{ $item->nama_sertifikat }}</h3>
                            <p>{{ $item->penyelenggara }}</p>
                            @if (!empty($item->deskripsi))
                                <p>{{ \Illuminate\Support\Str::limit(strip_tags($item->deskripsi), 150) }}</p>
                            @endif
                        </div>
                        @if ($item->file_pdf)
                            <a href="{{ asset('sertifikat/' . $item->file_pdf) }}" target="_blank" rel="noopener noreferrer"
                                class="portfolio-text-link" aria-label="Lihat PDF {{ $item->nama_sertifikat }}">
                                PDF <i class="bi bi-arrow-up-right" aria-hidden="true"></i>
                            </a>
                        @else
                            <p class="portfolio-pdf-unavailable">PDF belum tersedia</p>
                        @endif
                    </article>
                @empty
                    <p class="portfolio-empty">Belum ada data sertifikasi.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section class="portfolio-contact portfolio-section" aria-labelledby="portfolio-contact-title">
        <div class="container portfolio-contact-inner">
            <div>
                <p class="portfolio-label">Kontak & kerja sama</p>
                <h2 id="portfolio-contact-title">Punya ide atau peluang?<br>Mari berdiskusi.</h2>
            </div>
            <button type="button" class="portfolio-button" data-bs-toggle="modal" data-bs-target="#smartContactModal">
                Mulai percakapan <i class="bi bi-arrow-up-right" aria-hidden="true"></i>
            </button>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/portfolio-portrait.js') }}?v={{ filemtime(public_path('js/portfolio-portrait.js')) }}" defer></script>
@endpush
