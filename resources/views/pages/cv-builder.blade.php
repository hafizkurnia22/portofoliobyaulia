@extends('layouts.app')

@section('title', 'Generate CV saya | Portfolio CV')

@section('content')
    @php
        $colors = $cvOptions['colors'];
        // Query ini menjaga pilihan user tetap terbawa saat tombol download PDF diklik.
        $downloadQuery = [
            'builder' => 1,
            'template' => $cvOptions['template'],
            'theme' => $cvOptions['theme'],
            'show_photo' => $cvOptions['show_photo'] ? 1 : 0,
            'show_profile' => $cvOptions['show_profile'] ? 1 : 0,
            'show_skills' => $cvOptions['show_skills'] ? 1 : 0,
            'show_experience' => $cvOptions['show_experience'] ? 1 : 0,
            'show_projects' => $cvOptions['show_projects'] ? 1 : 0,
            'show_certifications' => $cvOptions['show_certifications'] ? 1 : 0,
        ];
    @endphp

    <section class="cv-builder-page">
        <div class="container">
            <div class="cv-builder-hero" data-aos="fade-up">
                <div>
                    <span class="section-label">Generate CV saya</span>
                    <h1>Atur dan download CV PDF yang lebih rapi.</h1>
                    <p>Atur tampilan, pilih warna, dan tentukan bagian yang mau ditampilkan.</p>
                </div>

                <a href="{{ route('cv.download') . '?' . http_build_query($downloadQuery) }}" class="btn-builder-download">
                    <i class="bi bi-file-earmark-arrow-down"></i>
                    Download PDF
                </a>
            </div>

            <div class="row g-4 align-items-start">
                <div class="col-lg-4" data-aos="fade-right">
                    <form action="{{ route('cv.builder') }}" method="GET" class="cv-control-panel">
                        <input type="hidden" name="builder" value="1">

                        {{-- Panel pengaturan ini hanya mengubah tampilan, data tetap dari dashboard portfolio. --}}
                        <div class="cv-control-group">
                            <label class="cv-control-label" for="template">Template</label>
                            <select name="template" id="template" class="form-select">
                                <option value="modern" @selected($cvOptions['template'] === 'modern')>Modern</option>
                                <option value="compact" @selected($cvOptions['template'] === 'compact')>Compact</option>
                            </select>
                        </div>

                        <div class="cv-control-group">
                            <span class="cv-control-label">Tema Warna</span>

                            <div class="cv-theme-grid">
                                @foreach ($cvThemes as $themeKey => $theme)
                                    <label class="cv-theme-option">
                                        <input type="radio" name="theme" value="{{ $themeKey }}"
                                            @checked($cvOptions['theme'] === $themeKey)>
                                        <span>
                                            <i style="background: {{ $theme['primary'] }}"></i>
                                            {{ $theme['label'] }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="cv-control-group">
                            <span class="cv-control-label">Isi CV</span>

                            <label class="cv-builder-check">
                                <input type="checkbox" name="show_photo" value="1" @checked($cvOptions['show_photo'])>
                                <span>Foto profil</span>
                            </label>

                            <label class="cv-builder-check">
                                <input type="checkbox" name="show_profile" value="1" @checked($cvOptions['show_profile'])>
                                <span>Profil singkat</span>
                            </label>

                            <label class="cv-builder-check">
                                <input type="checkbox" name="show_skills" value="1" @checked($cvOptions['show_skills'])>
                                <span>Skill</span>
                            </label>

                            <label class="cv-builder-check">
                                <input type="checkbox" name="show_experience" value="1" @checked($cvOptions['show_experience'])>
                                <span>Pengalaman kerja</span>
                            </label>

                            <label class="cv-builder-check">
                                <input type="checkbox" name="show_projects" value="1" @checked($cvOptions['show_projects'])>
                                <span>Project pilihan</span>
                            </label>

                            <label class="cv-builder-check">
                                <input type="checkbox" name="show_certifications" value="1"
                                    @checked($cvOptions['show_certifications'])>
                                <span>Sertifikasi</span>
                            </label>
                        </div>

                        <button type="submit" class="btn-builder-primary">
                            <i class="bi bi-eye"></i>
                            Preview CV
                        </button>
                    </form>
                </div>

                <div class="col-lg-8" data-aos="fade-left">
                    {{-- Preview memakai opsi yang sama dengan PDF agar hasil download mudah diprediksi. --}}
                    <div class="cv-preview {{ $cvOptions['template'] === 'compact' ? 'is-compact' : '' }}"
                        style="--cv-primary: {{ $colors['primary'] }}; --cv-secondary: {{ $colors['secondary'] }}; --cv-soft: {{ $colors['soft'] }}; --cv-muted: {{ $colors['muted'] }};">
                        <div class="cv-preview-header">
                            @if ($cvOptions['show_photo'])
                                <div class="cv-preview-photo">
                                    @if (!empty($tentangSaya->foto))
                                        <img src="{{ asset('images/' . $tentangSaya->foto) }}"
                                            alt="{{ $tentangSaya->nama ?? 'Foto profil' }}">
                                    @else
                                        <i class="bi bi-person-fill"></i>
                                    @endif
                                </div>
                            @endif

                            <div>
                                <h2>{{ $tentangSaya->nama ?? 'Nama Anda' }}</h2>
                                <p>{{ $tentangSaya->bidang ?? 'Bidang profesional' }}</p>

                                @if (!empty($tentangSaya->status))
                                    <span>{{ $tentangSaya->status }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="cv-preview-contact">
                            <span>
                                <i class="bi bi-whatsapp"></i>
                                {{ $tentangSaya->whatsapp ?? '-' }}
                            </span>
                            <span>
                                <i class="bi bi-envelope"></i>
                                {{ $tentangSaya->email_kontak ?? '-' }}
                            </span>
                            @if (!empty($tentangSaya->instagram))
                                <span>
                                    <i class="bi bi-instagram"></i>
                                    Instagram
                                </span>
                            @endif
                        </div>

                        @if ($cvOptions['show_profile'])
                            <div class="cv-preview-section">
                                <h3>Profil</h3>
                                <p>
                                    {{ $tentangSaya->deskripsi_1 ?? 'Belum ada deskripsi profil.' }}
                                </p>

                                @if (!empty($tentangSaya->deskripsi_2))
                                    <p>{{ $tentangSaya->deskripsi_2 }}</p>
                                @endif
                            </div>
                        @endif

                        @if ($cvOptions['show_skills'])
                            <div class="cv-preview-section">
                                <h3>Skill</h3>
                                <div class="cv-preview-skill-grid">
                                    @forelse ($skill as $item)
                                        <div class="cv-preview-skill">
                                            <div>
                                                <strong>{{ $item->nama_skill }}</strong>
                                                @if ($item->kategori)
                                                    <small>{{ $item->kategori }}</small>
                                                @endif
                                            </div>
                                            <span>{{ $item->persentase }}%</span>
                                        </div>
                                    @empty
                                        <p>Belum ada skill.</p>
                                    @endforelse
                                </div>
                            </div>
                        @endif

                        @if ($cvOptions['show_experience'])
                            <div class="cv-preview-section">
                                <h3>Pengalaman Kerja</h3>

                                @forelse ($pengalaman as $item)
                                    <div class="cv-preview-item">
                                        <div class="cv-preview-item-title">
                                            {{ $item->jabatan }}
                                            <span>{{ $item->nama_perusahaan }}</span>
                                        </div>
                                        <small>{{ $item->periode }}</small>
                                        <p>{{ $item->deskripsi }}</p>
                                    </div>
                                @empty
                                    <p>Belum ada pengalaman kerja.</p>
                                @endforelse
                            </div>
                        @endif

                        @if ($cvOptions['show_projects'])
                            <div class="cv-preview-section">
                                <h3>Project Pilihan</h3>

                                <div class="cv-preview-project-grid">
                                    @forelse ($projects as $project)
                                        @php
                                            $techList = collect(explode(',', $project->teknologi ?? ''))
                                                ->map(function ($tech) {
                                                    return trim($tech);
                                                })
                                                ->filter()
                                                ->take(6);
                                        @endphp

                                        <div class="cv-preview-project">
                                            <div class="cv-preview-project-head">
                                                <div>
                                                    <strong>{{ $project->nama_project }}</strong>

                                                    @if (!empty($project->kategori))
                                                        <small>{{ $project->kategori }}</small>
                                                    @endif
                                                </div>

                                                @if (!empty($project->status))
                                                    <span>{{ $project->status }}</span>
                                                @endif
                                            </div>

                                            @if (!empty($project->deskripsi))
                                                <p>{{ $project->deskripsi }}</p>
                                            @endif

                                            @if ($techList->isNotEmpty())
                                                <div class="cv-preview-project-tech">
                                                    @foreach ($techList as $tech)
                                                        <span>{{ $tech }}</span>
                                                    @endforeach
                                                </div>
                                            @endif

                                            @if (!empty($project->link_demo) || !empty($project->link_repository))
                                                <div class="cv-preview-project-links">
                                                    @if (!empty($project->link_demo))
                                                        <a href="{{ $project->link_demo }}" target="_blank"
                                                            rel="noopener">Demo</a>
                                                    @endif

                                                    @if (!empty($project->link_repository))
                                                        <a href="{{ $project->link_repository }}" target="_blank"
                                                            rel="noopener">Repository</a>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @empty
                                        <p>Belum ada project.</p>
                                    @endforelse
                                </div>
                            </div>
                        @endif
                        @if ($cvOptions['show_certifications'])
                            <div class="cv-preview-section">
                                <h3>Sertifikasi</h3>

                                @forelse ($sertifikasi as $item)
                                    <div class="cv-preview-item">
                                        <div class="cv-preview-item-title">
                                            {{ $item->nama_sertifikat }}
                                            <span>{{ $item->penyelenggara }}</span>
                                        </div>
                                        <small>{{ $item->tahun }}</small>

                                        @if (!empty($item->deskripsi))
                                            <p>{{ $item->deskripsi }}</p>
                                        @endif
                                    </div>
                                @empty
                                    <p>Belum ada sertifikasi.</p>
                                @endforelse
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
