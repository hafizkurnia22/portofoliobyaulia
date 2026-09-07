@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

    @php
        $activeTab = request('active_tab', old('active_tab', session('active_tab', 'pengalaman')));
        $profileTabs = ['pengalaman', 'sertifikasi', 'skill', 'project', 'tentang'];
        $showProfileDashboard = in_array($activeTab, $profileTabs);
    @endphp

    <section class="admin-dashboard">
        <div class="container">

            <div class="admin-header" data-aos="fade-down">
                <div>
                    <span class="admin-label">Admin Panel</span>
                    <h1>Dashboard Portfolio</h1>
                    <p>Kelola data profil portfolio dan bank soal tryout CAT CPNS.</p>
                </div>

                <div class="admin-header-right">

                    <div class="d-flex gap-2 flex-wrap">

                        <button type="button" class="btn-dark-toggle" id="darkModeToggle">
                            <i class="bi bi-moon-stars-fill"></i>
                            <span>Dark Mode</span>
                        </button>

                        <a href="/admin/logout" class="btn-admin-logout">
                            <i class="bi bi-box-arrow-right"></i>
                            Logout
                        </a>

                    </div>

                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-xl col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="admin-stat-card">
                        <i class="bi bi-briefcase-fill"></i>
                        <div>
                            <h4>{{ $totalPengalaman }}</h4>
                            <p>Total Pengalaman</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="admin-stat-card">
                        <i class="bi bi-kanban-fill"></i>
                        <div>
                            <h4>{{ $totalProject }}</h4>
                            <p>Total Project</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="admin-stat-card">
                        <i class="bi bi-award-fill"></i>
                        <div>
                            <h4>{{ $totalSertifikasi }}</h4>
                            <p>Total Sertifikasi</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="admin-stat-card">
                        <i class="bi bi-bar-chart-fill"></i>
                        <div>
                            <h4>{{ $totalSkill }}</h4>
                            <p>Total Skill</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl col-md-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="admin-stat-card">
                        <i class="bi bi-ui-checks-grid"></i>
                        <div>
                            <h4>{{ $totalTryoutSoal }}</h4>
                            <p>Total Soal</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl col-md-6" data-aos="fade-up" data-aos-delay="600">
                    <div class="admin-stat-card">
                        <i class="bi bi-people-fill"></i>
                        <div>
                            <h4>{{ $totalTryoutPeserta }}</h4>
                            <p>Peserta Tryout</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl col-md-6" data-aos="fade-up" data-aos-delay="700">
                    <div class="admin-stat-card">
                        <i class="bi bi-clock-history"></i>
                        <div>
                            <h4>{{ $totalTryoutRiwayat }}</h4>
                            <p>Riwayat Tryout</p>
                        </div>
                    </div>
                </div>
            </div>


            <div class="admin-table-card" data-aos="fade-up" data-aos-delay="400">
                <div class="admin-management-layout">
                    <aside class="admin-sidebar" data-aos="zoom-in">
                        <div class="admin-sidebar-section">
                            <div class="admin-sidebar-title">
                                <i class="bi bi-person-badge"></i>
                                Profil
                            </div>

                            <ul class="nav nav-pills admin-tabs admin-sidebar-nav" id="adminTabs">
                                <li class="nav-item">
                                    <a class="nav-link {{ $activeTab == 'pengalaman' ? 'active' : '' }}"
                                        href="{{ url('/admin/dashboard?active_tab=pengalaman') }}">
                                        <i class="bi bi-briefcase"></i> Pengalaman
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link {{ $activeTab == 'sertifikasi' ? 'active' : '' }}"
                                        href="{{ url('/admin/dashboard?active_tab=sertifikasi') }}">
                                        <i class="bi bi-award"></i> Sertifikasi
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link {{ $activeTab == 'skill' ? 'active' : '' }}"
                                        href="{{ url('/admin/dashboard?active_tab=skill') }}">
                                        <i class="bi bi-bar-chart-fill"></i> Skill
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link {{ $activeTab == 'project' ? 'active' : '' }}"
                                        href="{{ url('/admin/dashboard?active_tab=project') }}">
                                        <i class="bi bi-kanban-fill"></i> Project
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link {{ $activeTab == 'tentang' ? 'active' : '' }}"
                                        href="{{ url('/admin/dashboard?active_tab=tentang') }}">
                                        <i class="bi bi-person-circle"></i> Tentang Saya
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="admin-sidebar-section">
                            <div class="admin-sidebar-title">
                                <i class="bi bi-ui-checks-grid"></i>
                                Tryout
                            </div>

                            <ul class="nav nav-pills admin-tabs admin-sidebar-nav">
                                <li class="nav-item">
                                    <a class="nav-link {{ $activeTab == 'master-kategori-soal' ? 'active' : '' }}"
                                        href="{{ url('/admin/dashboard?active_tab=master-kategori-soal') }}">
                                        <i class="bi bi-tags"></i> Master Kategori
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link {{ $activeTab == 'master-peserta-tryout' ? 'active' : '' }}"
                                        href="{{ url('/admin/dashboard?active_tab=master-peserta-tryout') }}">
                                        <i class="bi bi-people"></i> Master Peserta
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link {{ $activeTab == 'master-soal' ? 'active' : '' }}"
                                        href="{{ url('/admin/dashboard?active_tab=master-soal') }}">
                                        <i class="bi bi-journal-text"></i> Master Soal
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link {{ $activeTab == 'riwayat-tryout' ? 'active' : '' }}"
                                        href="{{ url('/admin/dashboard?active_tab=riwayat-tryout') }}">
                                        <i class="bi bi-clock-history"></i> Riwayat Tryout
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </aside>

                    <main class="admin-content-panel">
                        <div class="tab-content">

                    {{-- TAB PENGALAMAN --}}
                    <div class="tab-pane fade {{ $activeTab == 'pengalaman' ? 'show active' : '' }}" id="pengalaman-panel"
                        role="tabpanel">

                        <div class="admin-table-header align-middle" data-aos="fade-right">
                            <div>
                                <h5>Data Pengalaman Kerja</h5>
                                <p>Tambah, edit, atau hapus pengalaman kerja.</p>
                            </div>

                            <button type="button" class="btn-admin-add" data-bs-toggle="modal"
                                data-bs-target="#tambahModal">
                                <i class="bi bi-plus-circle"></i> Tambah Pengalaman
                            </button>
                        </div>
                        {{-- kolom search pengalaman --}}
                        <div class="admin-search-box mb-3">
                            <i class="bi bi-search"></i>
                            <input type="text" class="form-control admin-live-search" data-target="pengalaman-table"
                                placeholder="Cari pengalaman, perusahaan, jabatan...">
                        </div>

                        <div class="table-responsive" data-aos="fade-up">
                            <table class="table admin-table align-middle"id="pengalaman-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Logo</th>
                                        <th>Perusahaan</th>
                                        <th>Jabatan</th>
                                        <th>Periode</th>
                                        <th>Deskripsi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($pengalaman as $item)
                                        <tr>
                                            <td>{{ $pengalaman->firstItem() + $loop->index }}</td>

                                            <td>
                                                @if ($item->logo)
                                                    <img src="{{ asset('images/' . $item->logo) }}" class="admin-logo">
                                                @else
                                                    <div class="admin-logo-placeholder">
                                                        <i class="bi bi-building"></i>
                                                    </div>
                                                @endif
                                            </td>

                                            <td class="fw-semibold">{{ $item->nama_perusahaan }}</td>
                                            <td>{{ $item->jabatan }}</td>
                                            <td>
                                                <span class="admin-badge">{{ $item->periode }}</span>
                                            </td>
                                            <td class="admin-desc">{{ $item->deskripsi }}</td>

                                            <td>
                                                <div class="d-flex gap-2">
                                                    <button type="button" class="btn-admin-edit" data-bs-toggle="modal"
                                                        data-bs-target="#editModal{{ $item->id }}">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>

                                                    <form action="/hapus-pengalaman/{{ $item->id }}" method="POST"
                                                        class="delete-form">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="button" class="btn-admin-delete btn-delete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                Belum ada data pengalaman
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <div class="mt-4">
                                {{ $pengalaman->appends(array_merge(request()->query(), ['active_tab' => 'pengalaman']))->links() }}
                            </div>
                        </div>


                    </div>

                    {{-- TAB SERTIFIKASI --}}
                    <div class="tab-pane fade {{ $activeTab == 'sertifikasi' ? 'show active' : '' }}"
                        id="sertifikasi-panel" role="tabpanel">

                        <div class="admin-table-header" data-aos="fade-right">
                            <div>
                                <h5>Data Sertifikasi</h5>
                                <p>Tambah, edit, lihat, atau hapus sertifikat PDF.</p>
                            </div>

                            <button type="button" class="btn-admin-add" data-bs-toggle="modal"
                                data-bs-target="#tambahSertifikasiModal">
                                <i class="bi bi-plus-circle"></i> Tambah Sertifikasi
                            </button>
                        </div>
                        {{-- search sertifikasi --}}
                        <div class="admin-search-box mb-3">
                            <i class="bi bi-search"></i>
                            <input type="text" class="form-control admin-live-search" data-target="sertifikasi-table"
                                placeholder="Cari sertifikasi, penyelenggara, tahun...">
                        </div>

                        <div class="table-responsive" data-aos="fade-up">
                            <table class="table admin-table align-middle"id="sertifikasi-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Sertifikat</th>
                                        <th>Penyelenggara</th>
                                        <th>Tahun</th>
                                        <th>Deskripsi</th>
                                        <th>PDF</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($sertifikasi as $item)
                                        <tr>
                                            <td>{{ $sertifikasi->firstItem() + $loop->index }}</td>
                                            <td class="fw-semibold">{{ $item->nama_sertifikat }}</td>
                                            <td>{{ $item->penyelenggara }}</td>
                                            <td>
                                                <span class="admin-badge">{{ $item->tahun }}</span>
                                            </td>
                                            <td class="admin-desc">{{ $item->deskripsi }}</td>

                                            <td>
                                                @if ($item->file_pdf)
                                                    <a href="{{ asset('sertifikat/' . $item->file_pdf) }}"
                                                        target="_blank" class="btn btn-sm btn-primary">
                                                        Lihat PDF
                                                    </a>
                                                @else
                                                    <span class="text-muted">Tidak ada PDF</span>
                                                @endif
                                            </td>

                                            <td>
                                                <div class="d-flex gap-2">
                                                    <button type="button" class="btn-admin-edit" data-bs-toggle="modal"
                                                        data-bs-target="#editSertifikasiModal{{ $item->id }}">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>

                                                    <form action="/hapus-sertifikasi/{{ $item->id }}" method="POST"
                                                        class="delete-form">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="button" class="btn-admin-delete btn-delete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>

                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                Belum ada data sertifikasi
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <div class="mt-4">
                                {{ $sertifikasi->appends(array_merge(request()->query(), ['active_tab' => 'sertifikasi']))->links() }}
                            </div>
                        </div>
                    </div>

                    {{-- TAB SKILL --}}
                    <div class="tab-pane fade {{ $activeTab == 'skill' ? 'show active' : '' }}" id="skill-panel"
                        role="tabpanel">

                        <div class="admin-table-header" data-aos="fade-right">
                            <div>
                                <h5>Data Skill</h5>
                                <p>Kelola skill dan kemampuan.</p>
                            </div>

                            <button type="button" class="btn-admin-add" data-bs-toggle="modal"
                                data-bs-target="#tambahSkillModal">
                                <i class="bi bi-plus-circle"></i> Tambah Skill
                            </button>
                        </div>

                        {{-- search skill --}}
                        <div class="admin-search-box mb-3">
                            <i class="bi bi-search"></i>
                            <input type="text" class="form-control admin-live-search" data-target="skill-table"
                                placeholder="Cari skill, kategori, progress...">
                        </div>

                        <div class="table-responsive" data-aos="fade-up">
                            <table class="table admin-table align-middle"id="skill-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Skill</th>
                                        <th>Kategori</th>
                                        <th width="250">Progress</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($skill as $item)
                                        <tr>
                                            <td>{{ $skill->firstItem() + $loop->index }}</td>
                                            <td class="fw-semibold">{{ $item->nama_skill }}</td>
                                            <td>
                                                <span class="admin-badge">{{ $item->kategori }}</span>
                                            </td>

                                            <td>
                                                <div class="progress" style="height: 12px; border-radius: 30px;">
                                                    <div class="progress-bar bg-success" role="progressbar"
                                                        style="width: {{ $item->persentase }}%">
                                                    </div>
                                                </div>

                                                <small class="fw-semibold text-success">
                                                    {{ $item->persentase }}%
                                                </small>
                                            </td>

                                            <td>
                                                <div class="d-flex gap-2">
                                                    <button type="button" class="btn-admin-edit" data-bs-toggle="modal"
                                                        data-bs-target="#editSkillModal{{ $item->id }}">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>

                                                    <form action="/hapus-skill/{{ $item->id }}" method="POST"
                                                        class="delete-form">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="button" class="btn-admin-delete btn-delete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                Belum ada data skill
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <div class="mt-4">
                                {{ $skill->appends(array_merge(request()->query(), ['active_tab' => 'skill']))->links() }}
                            </div>
                        </div>


                    </div>

                    {{-- TAB PROJECT --}}
                    <div class="tab-pane fade {{ $activeTab == 'project' ? 'show active' : '' }}" id="project-panel"
                        role="tabpanel">

                        <div class="admin-table-header" data-aos="fade-right">
                            <div>
                                <h5>Data My Project</h5>
                                <p>Tambah, edit, atau hapus project yang pernah dikerjakan.</p>
                            </div>

                            <button type="button" class="btn-admin-add" data-bs-toggle="modal"
                                data-bs-target="#tambahProjectModal">
                                <i class="bi bi-plus-circle"></i> Tambah Project
                            </button>
                        </div>

                        @if ($activeTab == 'project' && $errors->any())
                            <div class="alert alert-danger rounded-4 mb-4">
                                <strong>Project belum tersimpan.</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="admin-search-box mb-3">
                            <i class="bi bi-search"></i>
                            <input type="text" class="form-control admin-live-search" data-target="project-table"
                                placeholder="Cari project, kategori, teknologi, status...">
                        </div>

                        <div class="table-responsive" data-aos="fade-up">
                            <table class="table admin-table align-middle" id="project-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Gambar</th>
                                        <th>Project</th>
                                        <th>Kategori</th>
                                        <th>Status</th>
                                        <th>Teknologi</th>
                                        <th>Link</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($project as $item)
                                        <tr>
                                            <td>{{ $project->firstItem() + $loop->index }}</td>
                                            <td>
                                                @if ($item->gambar)
                                                    <img src="{{ asset('images/projects/' . $item->gambar) }}"
                                                        class="admin-logo" alt="{{ $item->nama_project }}">
                                                @else
                                                    <div class="admin-logo-placeholder">
                                                        <i class="bi bi-kanban"></i>
                                                    </div>
                                                @endif
                                            </td>

                                            <td>
                                                <strong>{{ $item->nama_project }}</strong>
                                                <p class="admin-desc mb-0 mt-1">{{ $item->deskripsi }}</p>
                                            </td>
                                            <td>
                                                <span class="admin-badge">{{ $item->kategori ?? 'Project' }}</span>
                                            </td>
                                            <td>{{ $item->status ?? 'Selesai' }}</td>
                                            <td>{{ $item->teknologi ?? '-' }}</td>
                                            <td>
                                                <div class="d-flex gap-2 flex-wrap">
                                                    @if ($item->link_demo)
                                                        <a href="{{ $item->link_demo }}" target="_blank"
                                                            class="btn btn-sm btn-primary">
                                                            Demo
                                                        </a>
                                                    @endif

                                                    @if ($item->link_repository)
                                                        <a href="{{ $item->link_repository }}" target="_blank"
                                                            class="btn btn-sm btn-outline-primary">
                                                            Repo
                                                        </a>
                                                    @endif

                                                    @if (!$item->link_demo && !$item->link_repository)
                                                        <span class="text-muted">Tidak ada link</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <button type="button" class="btn-admin-edit" data-bs-toggle="modal"
                                                        data-bs-target="#editProjectModal{{ $item->id }}">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>

                                                    <form action="/hapus-project/{{ $item->id }}" method="POST"
                                                        class="delete-form">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="button" class="btn-admin-delete btn-delete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-4">
                                                Belum ada data project
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <div class="mt-4">
                                {{ $project->appends(array_merge(request()->query(), ['active_tab' => 'project']))->links() }}
                            </div>
                        </div>
                    </div>

                    {{-- TAB TENTANG SAYA --}}
                    <div class="tab-pane fade {{ $activeTab == 'tentang' ? 'show active' : '' }}" id="tentang-panel"
                        role="tabpanel">

                        <div class="admin-table-header d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h3>Data Tentang Saya</h3>
                                <p>Kelola profil utama portfolio.</p>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-4 p-4">
                            <div class="alert alert-info rounded-4 mb-4">
                                Data ini digunakan untuk menampilkan bagian Tentang Saya di halaman beranda.
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger rounded-4 mb-4">
                                    <strong>Data belum tersimpan.</strong>
                                    <ul class="mb-0 mt-2">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="/simpan-tentang-saya" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Nama</label>
                                        <input type="text" name="nama" class="form-control"
                                            value="{{ old('nama', $tentangSaya->nama ?? '') }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Bidang</label>
                                        <input type="text" name="bidang" class="form-control"
                                            value="{{ old('bidang', $tentangSaya->bidang ?? '') }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Status / Availability</label>
                                        <input type="text" name="status" class="form-control"
                                            placeholder="Contoh: Open for freelance / Ready for collaboration"
                                            value="{{ old('status', $tentangSaya->status ?? '') }}">
                                        <small class="text-muted">
                                            Teks ini tampil sebagai Live Availability Badge di website.
                                        </small>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Nomor WhatsApp</label>
                                        <input type="text" name="whatsapp" class="form-control"
                                            placeholder="mohon menggunakan (62) Contoh: 6281234567890"
                                            value="{{ old('whatsapp', $tentangSaya->whatsapp ?? '') }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Email Kontak</label>
                                        <input type="email" name="email_kontak" class="form-control"
                                            placeholder="Contoh: nama@email.com"
                                            value="{{ old('email_kontak', $tentangSaya->email_kontak ?? '') }}">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label>Facebook</label>
                                        <input type="url" name="facebook" class="form-control"
                                            placeholder="https://facebook.com/username"
                                            value="{{ old('facebook', $tentangSaya->facebook ?? '') }}">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label>Instagram</label>
                                        <input type="url" name="instagram" class="form-control"
                                            placeholder="https://instagram.com/username"
                                            value="{{ old('instagram', $tentangSaya->instagram ?? '') }}">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label>TikTok</label>
                                        <input type="url" name="tiktok" class="form-control"
                                            placeholder="https://tiktok.com/@username"
                                            value="{{ old('tiktok', $tentangSaya->tiktok ?? '') }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Foto Profile</label>
                                        <input type="file" name="foto" class="form-control"
                                            accept="image/jpeg,image/png,image/webp">
                                        <small class="text-muted">Format JPG, PNG, atau WEBP. Maksimal 5MB.</small>

                                        @if (isset($tentangSaya->foto))
                                            <img src="{{ asset('images/' . $tentangSaya->foto) }}" width="100"
                                                class="mt-3 rounded-3">
                                        @endif
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label>Deskripsi 1</label>
                                        <textarea name="deskripsi_1" rows="4" class="form-control">{{ old('deskripsi_1', $tentangSaya->deskripsi_1 ?? '') }}</textarea>
                                    </div>

                                    <div class="col-12 mb-4">
                                        <label>Deskripsi 2</label>
                                        <textarea name="deskripsi_2" rows="4" class="form-control">{{ old('deskripsi_2', $tentangSaya->deskripsi_2 ?? '') }}</textarea>
                                    </div>
                                </div>

                                <button class="btn btn-primary rounded-pill px-4">
                                    <i class="bi bi-save"></i>
                                    Update Profil
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- TAB MASTER KATEGORI SOAL --}}
                    <div class="tab-pane fade {{ $activeTab == 'master-kategori-soal' ? 'show active' : '' }}"
                        id="master-kategori-soal-panel" role="tabpanel">

                        <div class="admin-table-header" data-aos="fade-right">
                            <div>
                                <h5>Master Kategori Soal</h5>
                                <p>Kelola kategori soal CAT CPNS seperti TWK, TIU, dan TKP.</p>
                            </div>

                            <button type="button" class="btn-admin-add" data-bs-toggle="modal"
                                data-bs-target="#tambahTryoutKategoriSoalModal">
                                <i class="bi bi-plus-circle"></i> Tambah Kategori
                            </button>
                        </div>

                        @if ($activeTab == 'master-kategori-soal' && $errors->any())
                            <div class="alert alert-danger rounded-4 mb-4">
                                <strong>Kategori belum tersimpan.</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="table-responsive" data-aos="fade-up">
                            <table class="table admin-table align-middle">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Nama Kategori</th>
                                        <th>Deskripsi</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($tryoutKategoriSoal as $item)
                                        <tr>
                                            <td>{{ $tryoutKategoriSoal->firstItem() + $loop->index }}</td>
                                            <td>
                                                <span class="admin-badge">{{ $item->kode }}</span>
                                            </td>
                                            <td class="fw-semibold">{{ $item->nama }}</td>
                                            <td class="admin-desc">{{ $item->deskripsi ?? '-' }}</td>
                                            <td>
                                                <span class="admin-status-badge {{ $item->status == 'aktif' ? 'active' : 'draft' }}">
                                                    {{ ucfirst($item->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <button type="button" class="btn-admin-edit" data-bs-toggle="modal"
                                                        data-bs-target="#editTryoutKategoriSoalModal{{ $item->id }}">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>

                                                    <form action="/hapus-tryout-kategori-soal/{{ $item->id }}"
                                                        method="POST" class="delete-form">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="button" class="btn-admin-delete btn-delete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                Belum ada data kategori soal
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <div class="mt-4">
                                {{ $tryoutKategoriSoal->appends(array_merge(request()->query(), ['active_tab' => 'master-kategori-soal']))->links() }}
                            </div>
                        </div>
                    </div>

                    {{-- TAB MASTER PESERTA TRYOUT --}}
                    <div class="tab-pane fade {{ $activeTab == 'master-peserta-tryout' ? 'show active' : '' }}"
                        id="master-peserta-tryout-panel" role="tabpanel">

                        <div class="admin-table-header" data-aos="fade-right">
                            <div>
                                <h5>Master Peserta Tryout</h5>
                                <p>Buat username dan PIN peserta sebelum mereka masuk ke halaman Tryout.</p>
                            </div>

                            <button type="button" class="btn-admin-add" data-bs-toggle="modal"
                                data-bs-target="#tambahTryoutPesertaModal">
                                <i class="bi bi-plus-circle"></i> Tambah Peserta
                            </button>
                        </div>

                        @if ($activeTab == 'master-peserta-tryout' && $errors->any())
                            <div class="alert alert-danger rounded-4 mb-4">
                                <strong>Peserta belum tersimpan.</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="admin-search-box mb-3">
                            <i class="bi bi-search"></i>
                            <input type="text" class="form-control admin-live-search" data-target="tryout-peserta-table"
                                placeholder="Cari nama, username, atau status...">
                        </div>

                        <div class="table-responsive" data-aos="fade-up">
                            <table class="table admin-table align-middle" id="tryout-peserta-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Username</th>
                                        <th>PIN</th>
                                        <th>Status</th>
                                        <th>Login Terakhir</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($tryoutPeserta as $item)
                                        <tr>
                                            <td>{{ $tryoutPeserta->firstItem() + $loop->index }}</td>
                                            <td class="fw-semibold">{{ $item->nama ?? '-' }}</td>
                                            <td>{{ $item->username }}</td>
                                            <td>
                                                <span class="admin-badge">Tersimpan aman</span>
                                                <small class="d-block text-muted mt-1">Reset PIN lewat tombol edit.</small>
                                            </td>
                                            <td>
                                                <span class="admin-status-badge {{ $item->status == 'aktif' ? 'active' : 'draft' }}">
                                                    {{ ucfirst($item->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $item->last_login_at ? $item->last_login_at->format('d M Y H:i') : '-' }}</td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <button type="button" class="btn-admin-edit" data-bs-toggle="modal"
                                                        data-bs-target="#editTryoutPesertaModal{{ $item->id }}">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>

                                                    <form action="/hapus-tryout-peserta/{{ $item->id }}" method="POST"
                                                        class="delete-form">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="button" class="btn-admin-delete btn-delete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                Belum ada data peserta tryout
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <div class="mt-4">
                                {{ $tryoutPeserta->appends(array_merge(request()->query(), ['active_tab' => 'master-peserta-tryout']))->links() }}
                            </div>
                        </div>
                    </div>

                    {{-- TAB RIWAYAT TRYOUT --}}
                    <div class="tab-pane fade {{ $activeTab == 'riwayat-tryout' ? 'show active' : '' }}"
                        id="riwayat-tryout-panel" role="tabpanel">

                        <div class="admin-table-header" data-aos="fade-right">
                            <div>
                                <h5>Riwayat Tryout</h5>
                                <p>Pantau hasil pengerjaan tryout dari setiap peserta.</p>
                            </div>
                        </div>

                        <div class="admin-search-box mb-3">
                            <i class="bi bi-search"></i>
                            <input type="text" class="form-control admin-live-search" data-target="tryout-riwayat-table"
                                placeholder="Cari peserta, username, skor, atau waktu...">
                        </div>

                        <div class="table-responsive" data-aos="fade-up">
                            <table class="table admin-table align-middle" id="tryout-riwayat-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Peserta</th>
                                        <th>Skor</th>
                                        <th>Dijawab</th>
                                        <th>Benar</th>
                                        <th>Ragu</th>
                                        <th>Durasi</th>
                                        <th>Selesai</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($tryoutRiwayat as $item)
                                        <tr>
                                            <td>{{ $tryoutRiwayat->firstItem() + $loop->index }}</td>
                                            <td>
                                                <strong>{{ $item->peserta->nama ?? '-' }}</strong>
                                                <small class="d-block text-muted mt-1">
                                                    {{ $item->peserta->username ?? 'Peserta terhapus' }}
                                                </small>
                                            </td>
                                            <td>
                                                <span class="admin-badge">{{ $item->total_skor }}</span>
                                            </td>
                                            <td>{{ $item->total_dijawab }}/{{ $item->total_soal }}</td>
                                            <td>{{ $item->total_benar }}</td>
                                            <td>{{ $item->total_ragu }}</td>
                                            <td>{{ gmdate('H:i:s', $item->durasi_detik) }}</td>
                                            <td>{{ $item->finished_at ? $item->finished_at->format('d M Y H:i') : '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-4">
                                                Belum ada riwayat tryout
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <div class="mt-4">
                                {{ $tryoutRiwayat->appends(array_merge(request()->query(), ['active_tab' => 'riwayat-tryout']))->links() }}
                            </div>
                        </div>
                    </div>

                    {{-- TAB MASTER SOAL --}}
                    <div class="tab-pane fade {{ $activeTab == 'master-soal' ? 'show active' : '' }}"
                        id="master-soal-panel" role="tabpanel">

                        <div class="admin-table-header" data-aos="fade-right">
                            <div>
                                <h5>Master Soal Tryout</h5>
                                <p>Input bank soal CAT CPNS untuk kategori TWK, TIU, dan TKP.</p>
                            </div>

                            <button type="button" class="btn-admin-add" data-bs-toggle="modal"
                                data-bs-target="#tambahTryoutSoalModal">
                                <i class="bi bi-plus-circle"></i> Tambah Soal
                            </button>
                        </div>

                        <form action="/simpan-tryout-pengaturan" method="POST" class="tryout-setting-card mb-4">
                            @csrf

                            <div>
                                <h6>Pengaturan Acak Tryout</h6>
                                <p>Pengaturan ini berlaku untuk peserta di halaman Tryout CAT CPNS.</p>
                            </div>

                            <div class="tryout-setting-toggles">
                                <label class="cat-toggle">
                                    <input type="hidden" name="acak_soal" value="0">
                                    <input type="checkbox" name="acak_soal" value="1"
                                        {{ $tryoutPengaturan->acak_soal ? 'checked' : '' }}>
                                    <span></span>
                                    Acak Soal
                                </label>

                                <label class="cat-toggle">
                                    <input type="hidden" name="acak_jawaban" value="0">
                                    <input type="checkbox" name="acak_jawaban" value="1"
                                        {{ $tryoutPengaturan->acak_jawaban ? 'checked' : '' }}>
                                    <span></span>
                                    Acak Jawaban
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Simpan Pengaturan
                            </button>
                        </form>

                        <div class="tryout-import-card mb-4">
                            <form action="/import-tryout-soal" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div>
                                    <h6>Import Soal dari Excel</h6>
                                    <p>Gunakan kategori TWK, TIU, atau TKP pada kolom kategori.</p>
                                </div>

                                <input type="file" name="file_excel" class="form-control"
                                    accept=".xlsx,.xls,.csv" required>

                                <div class="d-flex gap-2 flex-wrap">
                                    <a href="/template-import-tryout-soal" class="btn btn-outline-primary">
                                        <i class="bi bi-download"></i> Template
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-file-earmark-spreadsheet"></i> Import Excel
                                    </button>
                                </div>
                            </form>
                        </div>

                        @if ($activeTab == 'master-soal' && $errors->any())
                            <div class="alert alert-danger rounded-4 mb-4">
                                <strong>Soal belum tersimpan.</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="admin-search-box mb-3">
                            <i class="bi bi-search"></i>
                            <input type="text" class="form-control admin-live-search" data-target="tryout-soal-table"
                                placeholder="Cari kode, kategori, pertanyaan, status...">
                        </div>

                        <div class="table-responsive" data-aos="fade-up">
                            <table class="table admin-table align-middle" id="tryout-soal-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Kategori</th>
                                        <th>Pertanyaan</th>
                                        <th>Jawaban</th>
                                        <th>Skor A-E</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($tryoutSoal as $item)
                                        <tr>
                                            <td>{{ $tryoutSoal->firstItem() + $loop->index }}</td>
                                            <td class="fw-semibold">{{ $item->kode_soal ?? '-' }}</td>
                                            <td>
                                                <span class="admin-badge">{{ $item->kategoriSoal->kode ?? $item->kategori }}</span>
                                                <small class="d-block text-muted mt-1">
                                                    {{ $item->kategoriSoal->nama ?? '-' }}
                                                </small>
                                            </td>
                                            <td class="admin-desc">
                                                {{ \Illuminate\Support\Str::limit($item->pertanyaan, 95) }}
                                            </td>
                                            <td>{{ $item->jawaban_benar ?? '-' }}</td>
                                            <td>
                                                {{ $item->skor_a }}/{{ $item->skor_b }}/{{ $item->skor_c }}/{{ $item->skor_d }}/{{ $item->skor_e }}
                                            </td>
                                            <td>
                                                <span class="admin-status-badge {{ $item->status == 'aktif' ? 'active' : 'draft' }}">
                                                    {{ ucfirst($item->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <button type="button" class="btn-admin-edit" data-bs-toggle="modal"
                                                        data-bs-target="#editTryoutSoalModal{{ $item->id }}">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>

                                                    <form action="/hapus-tryout-soal/{{ $item->id }}" method="POST"
                                                        class="delete-form">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="button" class="btn-admin-delete btn-delete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-4">
                                                Belum ada data soal tryout
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <div class="mt-4">
                                {{ $tryoutSoal->appends(array_merge(request()->query(), ['active_tab' => 'master-soal']))->links() }}
                            </div>
                        </div>
                    </div>

                        </div>
                    </main>
                </div>
            </div>

            @if ($showProfileDashboard)
                @php
                    $complete = 0;

                    if (!empty($tentangSaya->nama)) {
                        $complete += 20;
                    }
                    if (!empty($tentangSaya->bidang)) {
                        $complete += 20;
                    }
                    if (!empty($tentangSaya->status)) {
                        $complete += 20;
                    }
                    if (!empty($tentangSaya->foto)) {
                        $complete += 20;
                    }
                    if (!empty($tentangSaya->deskripsi_1)) {
                        $complete += 20;
                    }
                @endphp
                <div class="dashboard-stat-section">
                    <div class="row g-4 mb-4">

                        <div class="col-lg-4 col-md-6">
                            <div class="dashboard-insight-card">
                                <div class="insight-icon blue">
                                    <i class="bi bi-speedometer2"></i>
                                </div>

                                <div class="w-100">
                                    <span>Rata-rata Skill</span>
                                    <h3>{{ number_format($rataSkill, 0) }}%</h3>

                                    <div class="insight-progress">
                                        <div style="width: {{ number_format($rataSkill, 0) }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6">
                            <div class="dashboard-insight-card">
                                <div class="insight-icon green">
                                    <i class="bi bi-trophy-fill"></i>
                                </div>

                                <div>
                                    <span>Skill Tertinggi</span>
                                    <h3>{{ $skillTertinggi->nama_skill ?? 'Belum ada' }}</h3>
                                    <p>{{ $skillTertinggi->persentase ?? 0 }}%</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-12">
                            <div class="dashboard-insight-card">
                                <div class="insight-icon purple">
                                    <i class="bi bi-award-fill"></i>
                                </div>

                                <div>
                                    <span>Sertifikasi Terbaru</span>
                                    <h3>{{ $sertifikasiTerbaru->nama_sertifikat ?? 'Belum ada' }}</h3>
                                    <p>{{ $sertifikasiTerbaru->tahun ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="quick-action-card mb-4">
                    <div>
                        <h4>Quick Action</h4>
                        <p>Akses cepat untuk mengelola data portfolio.</p>
                    </div>

                    <div class="quick-action-buttons">
                        <button class="quick-btn" data-bs-toggle="modal" data-bs-target="#tambahModal">
                            <i class="bi bi-briefcase-fill"></i>
                            Tambah Pengalaman
                        </button>

                        <button class="quick-btn" data-bs-toggle="modal" data-bs-target="#tambahProjectModal">
                            <i class="bi bi-kanban-fill"></i>
                            Tambah Project
                        </button>

                        <button class="quick-btn" data-bs-toggle="modal" data-bs-target="#tambahSertifikasiModal">
                            <i class="bi bi-award-fill"></i>
                            Tambah Sertifikasi
                        </button>

                        <button class="quick-btn" data-bs-toggle="modal" data-bs-target="#tambahSkillModal">
                            <i class="bi bi-bar-chart-fill"></i>
                            Tambah Skill
                        </button>
                    </div>
                </div>

                <div class="row g-4 mb-4">

                    <div class="col-lg-6">
                        <div class="recent-card">
                            <div class="recent-header">
                                <i class="bi bi-clock-history"></i>
                                <h5>Aktivitas Terbaru</h5>
                            </div>

                            <div class="recent-item">
                                <i class="bi bi-briefcase"></i>
                                <div>
                                    <strong>Pengalaman Terbaru</strong>
                                    <p>{{ $pengalamanTerbaru->nama_perusahaan ?? 'Belum ada data' }}</p>
                                </div>
                            </div>

                            <div class="recent-item">
                                <i class="bi bi-kanban"></i>
                                <div>
                                    <strong>Project Terbaru</strong>
                                    <p>{{ $projectTerbaru->nama_project ?? 'Belum ada data' }}</p>
                                </div>
                            </div>

                            <div class="recent-item">
                                <i class="bi bi-award"></i>
                                <div>
                                    <strong>Sertifikasi Terbaru</strong>
                                    <p>{{ $sertifikasiTerbaru->nama_sertifikat ?? 'Belum ada data' }}</p>
                                </div>
                            </div>

                            <div class="recent-item">
                                <i class="bi bi-bar-chart"></i>
                                <div>
                                    <strong>Skill Tertinggi</strong>
                                    <p>{{ $skillTertinggi->nama_skill ?? 'Belum ada data' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="profile-completion-card">
                            <div class="recent-header">
                                <i class="bi bi-person-check-fill"></i>
                                <h5>Kelengkapan Profil</h5>
                            </div>

                            <div class="completion-circle" style="--progress: {{ $complete }}%;">
                                <span>{{ $complete }}%</span>
                            </div>

                            <p class="text-muted text-center mt-3">
                                Semakin lengkap profil, semakin profesional tampilan portfolio kamu.
                            </p>

                            <div class="completion-bar">
                                <div style="width: {{ $complete }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
    </section>

    {{-- MODAL EDIT PENGALAMAN --}}
    @foreach ($pengalaman as $item)
        <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">

                    <form action="/update-pengalaman/{{ $item->id }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">Edit Pengalaman</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nama Perusahaan</label>
                                <input type="text" name="nama_perusahaan" class="form-control"
                                    value="{{ $item->nama_perusahaan }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Jabatan</label>
                                <input type="text" name="jabatan" class="form-control" value="{{ $item->jabatan }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Periode</label>
                                <input type="text" name="periode" class="form-control" value="{{ $item->periode }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="4" required>{{ $item->deskripsi }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Logo Perusahaan</label>

                                @if ($item->logo)
                                    <div class="mb-2">
                                        <img src="{{ asset('images/' . $item->logo) }}" width="80" class="rounded">
                                    </div>
                                @else
                                    <small class="text-muted d-block mb-2">Belum ada logo</small>
                                @endif

                                <input type="file" name="logo" class="form-control">
                                <small class="text-muted">Kosongkan jika tidak ingin mengganti
                                    logo.</small>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit" class="btn btn-primary">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    @endforeach

    {{-- MODAL EDIT SERTIFIKASI --}}
    @foreach ($sertifikasi as $item)
        <div class="modal fade" id="editSertifikasiModal{{ $item->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">

                    <form action="/update-sertifikasi/{{ $item->id }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">Edit Sertifikasi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nama Sertifikat</label>
                                <input type="text" name="nama_sertifikat" class="form-control"
                                    value="{{ $item->nama_sertifikat }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Penyelenggara</label>
                                <input type="text" name="penyelenggara" class="form-control"
                                    value="{{ $item->penyelenggara }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tahun</label>
                                <input type="text" name="tahun" class="form-control" value="{{ $item->tahun }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="4">{{ $item->deskripsi }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">File PDF</label>

                                @if ($item->file_pdf)
                                    <div class="mb-2">
                                        <a href="{{ asset('sertifikat/' . $item->file_pdf) }}" target="_blank">
                                            Lihat PDF saat ini
                                        </a>
                                    </div>
                                @endif

                                <input type="file" name="file_pdf" class="form-control" accept="application/pdf">
                                <small class="text-muted">Kosongkan jika tidak ingin mengganti
                                    PDF.</small>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit" class="btn btn-primary">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    @endforeach

    {{-- MODAL EDIT SKILL --}}
    @foreach ($skill as $item)
        <div class="modal fade" id="editSkillModal{{ $item->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">

                    <form action="/update-skill/{{ $item->id }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">Edit Skill</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nama Skill</label>
                                <input type="text" name="nama_skill" class="form-control"
                                    value="{{ $item->nama_skill }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <input type="text" name="kategori" class="form-control"
                                    value="{{ $item->kategori }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Persentase</label>
                                <input type="number" name="persentase" class="form-control" min="0"
                                    max="100" value="{{ $item->persentase }}" required>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit" class="btn btn-primary">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    @endforeach

    {{-- MODAL EDIT PROJECT --}}
    @foreach ($project as $item)
        <div class="modal fade" id="editProjectModal{{ $item->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form action="/update-project/{{ $item->id }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="active_tab" value="project">

                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">Edit Project</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label class="form-label">Nama Project</label>
                                    <input type="text" name="nama_project" class="form-control"
                                        value="{{ $item->nama_project }}" required>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-control">
                                        <option value="Selesai" {{ $item->status == 'Selesai' ? 'selected' : '' }}>
                                            Selesai
                                        </option>
                                        <option value="On Progress"
                                            {{ $item->status == 'On Progress' ? 'selected' : '' }}>
                                            On Progress
                                        </option>
                                        <option value="Maintenance"
                                            {{ $item->status == 'Maintenance' ? 'selected' : '' }}>
                                            Maintenance
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kategori</label>
                                    <input type="text" name="kategori" class="form-control"
                                        value="{{ $item->kategori }}" placeholder="Website / Dashboard / Mobile App">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Teknologi</label>
                                    <input type="text" name="teknologi" class="form-control"
                                        value="{{ $item->teknologi }}" placeholder="Laravel, Bootstrap, MySQL">
                                    <small class="text-muted">Pisahkan dengan koma.</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Link Demo</label>
                                    <input type="url" name="link_demo" class="form-control"
                                        value="{{ $item->link_demo }}" placeholder="https://domain-project.com">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Link Repository</label>
                                    <input type="url" name="link_repository" class="form-control"
                                        value="{{ $item->link_repository }}" placeholder="https://github.com/user/repo">
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">Deskripsi</label>
                                    <textarea name="deskripsi" class="form-control" rows="4" required>{{ $item->deskripsi }}</textarea>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">Gambar Project</label>

                                    @if ($item->gambar)
                                        <div class="mb-2">
                                            <img src="{{ asset('images/projects/' . $item->gambar) }}" width="120"
                                                class="rounded" alt="{{ $item->nama_project }}">
                                        </div>
                                    @else
                                        <small class="text-muted d-block mb-2">Belum ada gambar</small>
                                    @endif

                                    <input type="file" name="gambar" class="form-control"
                                        accept="image/jpeg,image/png,image/webp">
                                    <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar.</small>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit" class="btn btn-primary">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    {{-- MODAL EDIT TRYOUT KATEGORI SOAL --}}
    @foreach ($tryoutKategoriSoal as $item)
        <div class="modal fade" id="editTryoutKategoriSoalModal{{ $item->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form action="/update-tryout-kategori-soal/{{ $item->id }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="active_tab" value="master-kategori-soal">

                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">Edit Kategori Soal</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Kode</label>
                                    <input type="text" name="kode" class="form-control"
                                        value="{{ $item->kode }}" required>
                                </div>

                                <div class="col-md-8 mb-3">
                                    <label class="form-label">Nama Kategori</label>
                                    <input type="text" name="nama" class="form-control"
                                        value="{{ $item->nama }}" required>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-control" required>
                                        <option value="aktif" {{ $item->status == 'aktif' ? 'selected' : '' }}>Aktif
                                        </option>
                                        <option value="draft" {{ $item->status == 'draft' ? 'selected' : '' }}>Draft
                                        </option>
                                    </select>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">Deskripsi</label>
                                    <textarea name="deskripsi" class="form-control" rows="4">{{ $item->deskripsi }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit" class="btn btn-primary">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    {{-- MODAL EDIT TRYOUT PESERTA --}}
    @foreach ($tryoutPeserta as $item)
        <div class="modal fade" id="editTryoutPesertaModal{{ $item->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form action="/update-tryout-peserta/{{ $item->id }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="active_tab" value="master-peserta-tryout">

                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">Edit Peserta Tryout</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Peserta</label>
                                    <input type="text" name="nama" class="form-control"
                                        value="{{ $item->nama }}" placeholder="Contoh: Ahmad Rizki">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" name="username" class="form-control"
                                        value="{{ $item->username }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">PIN Baru</label>
                                    <input type="text" name="pin" class="form-control"
                                        inputmode="numeric" placeholder="Kosongkan jika tidak diganti">
                                    <small class="text-muted">PIN harus angka 4 sampai 12 digit.</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-control" required>
                                        <option value="aktif" {{ $item->status == 'aktif' ? 'selected' : '' }}>Aktif
                                        </option>
                                        <option value="nonaktif" {{ $item->status == 'nonaktif' ? 'selected' : '' }}>
                                            Nonaktif
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit" class="btn btn-primary">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    {{-- MODAL EDIT TRYOUT SOAL --}}
    @foreach ($tryoutSoal as $item)
        <div class="modal fade" id="editTryoutSoalModal{{ $item->id }}" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <form action="/update-tryout-soal/{{ $item->id }}" method="POST" class="tryout-soal-form">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="active_tab" value="master-soal">

                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">Edit Soal Tryout</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Kode Soal</label>
                                    <input type="text" name="kode_soal" class="form-control"
                                        value="{{ $item->kode_soal }}" placeholder="Contoh: TWK-001">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Kategori</label>
                                    <select name="tryout_kategori_soal_id" class="form-control tryout-kategori-select" required>
                                        @foreach ($tryoutKategoriOptions as $kategori)
                                            <option value="{{ $kategori->id }}" data-kode="{{ $kategori->kode }}"
                                                {{ $item->tryout_kategori_soal_id == $kategori->id || (!$item->tryout_kategori_soal_id && $item->kategori == $kategori->kode) ? 'selected' : '' }}>
                                                {{ $kategori->kode }} - {{ $kategori->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-control" required>
                                        <option value="aktif" {{ $item->status == 'aktif' ? 'selected' : '' }}>Aktif
                                        </option>
                                        <option value="draft" {{ $item->status == 'draft' ? 'selected' : '' }}>Draft
                                        </option>
                                    </select>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">Pertanyaan</label>
                                    <textarea name="pertanyaan" class="form-control" rows="4" required>{{ $item->pertanyaan }}</textarea>
                                </div>

                                @foreach (['a', 'b', 'c', 'd', 'e'] as $opsi)
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Opsi {{ strtoupper($opsi) }}</label>
                                        <textarea name="opsi_{{ $opsi }}" class="form-control" rows="2" required>{{ $item->{'opsi_' . $opsi} }}</textarea>
                                    </div>
                                @endforeach

                                <div class="col-md-4 mb-3 tryout-answer-key-group">
                                    <label class="form-label">Jawaban Benar</label>
                                    <select name="jawaban_benar" class="form-control">
                                        <option value="">Pilih jawaban</option>
                                        @foreach (['A', 'B', 'C', 'D', 'E'] as $opsi)
                                            <option value="{{ $opsi }}"
                                                {{ $item->jawaban_benar == $opsi ? 'selected' : '' }}>
                                                {{ $opsi }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Untuk TWK/TIU, jawaban benar otomatis bernilai 5 dan opsi lain 0.</small>
                                </div>

                                <div class="col-md-8 mb-3 tryout-score-group">
                                    <label class="form-label">Skor Pilihan CAT</label>
                                    <div class="tryout-score-grid">
                                        @foreach (['a', 'b', 'c', 'd', 'e'] as $opsi)
                                            <div>
                                                <span>{{ strtoupper($opsi) }}</span>
                                                <input type="number" name="skor_{{ $opsi }}"
                                                    class="form-control tryout-score-input" min="1" max="5"
                                                    value="{{ $item->{'skor_' . $opsi} }}">
                                            </div>
                                        @endforeach
                                    </div>
                                    <small class="text-muted">Untuk TKP, isi semua skor 1 sampai 5. Opsi terbaik diberi 5, opsi paling kurang tepat diberi 1.</small>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">Pembahasan</label>
                                    <textarea name="pembahasan" class="form-control" rows="3">{{ $item->pembahasan }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit" class="btn btn-primary">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    {{-- MODAL TAMBAH PENGALAMAN --}}
    <div class="modal fade" id="tambahModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="/simpan-pengalaman" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Tambah Pengalaman</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Perusahaan</label>
                            <input type="text" name="nama_perusahaan" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jabatan</label>
                            <input type="text" name="jabatan" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Periode</label>
                            <input type="text" name="periode" class="form-control" placeholder="Contoh: 2025-2027"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="4" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Logo Perusahaan</label>
                            <input type="file" name="logo" class="form-control">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH PROJECT --}}
    <div class="modal fade" id="tambahProjectModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="/simpan-project" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="active_tab" value="project">

                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Tambah Project</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Nama Project</label>
                                <input type="text" name="nama_project" class="form-control"
                                    placeholder="Contoh: Website Portfolio CV" value="{{ old('nama_project') }}"
                                    required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="Selesai" {{ old('status') == 'Selesai' ? 'selected' : '' }}>
                                        Selesai
                                    </option>
                                    <option value="On Progress"
                                        {{ old('status') == 'On Progress' ? 'selected' : '' }}>
                                        On Progress
                                    </option>
                                    <option value="Maintenance"
                                        {{ old('status') == 'Maintenance' ? 'selected' : '' }}>
                                        Maintenance
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kategori</label>
                                <input type="text" name="kategori" class="form-control"
                                    placeholder="Website / Dashboard / Mobile App" value="{{ old('kategori') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Teknologi</label>
                                <input type="text" name="teknologi" class="form-control"
                                    placeholder="Laravel, Bootstrap, MySQL" value="{{ old('teknologi') }}">
                                <small class="text-muted">Pisahkan dengan koma.</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Link Demo</label>
                                <input type="url" name="link_demo" class="form-control"
                                    placeholder="https://domain-project.com" value="{{ old('link_demo') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Link Repository</label>
                                <input type="url" name="link_repository" class="form-control"
                                    placeholder="https://github.com/user/repo" value="{{ old('link_repository') }}">
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="4"
                                    placeholder="Ceritakan fungsi utama, fitur, dan hasil project." required>{{ old('deskripsi') }}</textarea>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Gambar Project</label>
                                <input type="file" name="gambar" class="form-control"
                                    accept="image/jpeg,image/png,image/webp">
                                <small class="text-muted">Format JPG, PNG, atau WEBP. Maksimal 5MB.</small>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Simpan Project
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH SERTIFIKASI --}}
    <div class="modal fade" id="tambahSertifikasiModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="/simpan-sertifikasi" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Tambah Sertifikasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Sertifikat</label>
                            <input type="text" name="nama_sertifikat" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Penyelenggara</label>
                            <input type="text" name="penyelenggara" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tahun</label>
                            <input type="text" name="tahun" class="form-control" placeholder="Contoh: 2025"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="4"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">File Sertifikat PDF</label>
                            <input type="file" name="file_pdf" class="form-control" accept="application/pdf">
                            <small class="text-muted">Format PDF, maksimal 5MB.</small>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Simpan Sertifikasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH SKILL --}}
    <div class="modal fade" id="tambahSkillModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="/simpan-skill" method="POST">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Tambah Skill</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Skill</label>
                            <input type="text" name="nama_skill" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <input type="text" name="kategori" class="form-control"
                                placeholder="Frontend / Backend / Design">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Persentase</label>
                            <input type="number" name="persentase" class="form-control" min="0" max="100"
                                required>
                            <small class="text-muted">Masukkan nilai 0 - 100</small>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Simpan Skill
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH TRYOUT KATEGORI SOAL --}}
    <div class="modal fade" id="tambahTryoutKategoriSoalModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="/simpan-tryout-kategori-soal" method="POST">
                    @csrf
                    <input type="hidden" name="active_tab" value="master-kategori-soal">

                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Tambah Kategori Soal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Kode</label>
                                <input type="text" name="kode" class="form-control"
                                    value="{{ old('kode') }}" placeholder="Contoh: TWK" required>
                            </div>

                            <div class="col-md-8 mb-3">
                                <label class="form-label">Nama Kategori</label>
                                <input type="text" name="nama" class="form-control"
                                    value="{{ old('nama') }}" placeholder="Contoh: Tes Wawasan Kebangsaan" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif
                                    </option>
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                </select>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="4">{{ old('deskripsi') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Simpan Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH TRYOUT PESERTA --}}
    <div class="modal fade" id="tambahTryoutPesertaModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="/simpan-tryout-peserta" method="POST">
                    @csrf
                    <input type="hidden" name="active_tab" value="master-peserta-tryout">

                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Tambah Peserta Tryout</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Peserta</label>
                                <input type="text" name="nama" class="form-control"
                                    value="{{ old('nama') }}" placeholder="Contoh: Ahmad Rizki">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control"
                                    value="{{ old('username') }}" placeholder="Contoh: peserta001" required>
                                <small class="text-muted">Boleh huruf, angka, titik, garis bawah, dan strip.</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">PIN</label>
                                <input type="text" name="pin" class="form-control"
                                    value="{{ old('pin') }}" inputmode="numeric" placeholder="Contoh: 123456" required>
                                <small class="text-muted">PIN harus angka 4 sampai 12 digit.</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif
                                    </option>
                                    <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>
                                        Nonaktif
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Simpan Peserta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH TRYOUT SOAL --}}
    <div class="modal fade" id="tambahTryoutSoalModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <form action="/simpan-tryout-soal" method="POST" class="tryout-soal-form">
                    @csrf
                    <input type="hidden" name="active_tab" value="master-soal">

                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Tambah Soal Tryout</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Kode Soal</label>
                                <input type="text" name="kode_soal" class="form-control"
                                    value="{{ old('kode_soal') }}" placeholder="Contoh: TWK-001">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Kategori</label>
                                <select name="tryout_kategori_soal_id" class="form-control tryout-kategori-select" required>
                                    @foreach ($tryoutKategoriOptions as $kategori)
                                        <option value="{{ $kategori->id }}" data-kode="{{ $kategori->kode }}"
                                            {{ old('tryout_kategori_soal_id') == $kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->kode }} - {{ $kategori->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif
                                    </option>
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                </select>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Pertanyaan</label>
                                <textarea name="pertanyaan" class="form-control" rows="4" required>{{ old('pertanyaan') }}</textarea>
                            </div>

                            @foreach (['a', 'b', 'c', 'd', 'e'] as $opsi)
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Opsi {{ strtoupper($opsi) }}</label>
                                    <textarea name="opsi_{{ $opsi }}" class="form-control" rows="2" required>{{ old('opsi_' . $opsi) }}</textarea>
                                </div>
                            @endforeach

                            <div class="col-md-4 mb-3 tryout-answer-key-group">
                                <label class="form-label">Jawaban Benar</label>
                                <select name="jawaban_benar" class="form-control">
                                    <option value="">Pilih jawaban</option>
                                    @foreach (['A', 'B', 'C', 'D', 'E'] as $opsi)
                                        <option value="{{ $opsi }}"
                                            {{ old('jawaban_benar') == $opsi ? 'selected' : '' }}>
                                            {{ $opsi }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Untuk TWK/TIU, jawaban benar otomatis bernilai 5 dan opsi lain 0.</small>
                            </div>

                            <div class="col-md-8 mb-3 tryout-score-group">
                                <label class="form-label">Skor Pilihan CAT</label>
                                <div class="tryout-score-grid">
                                    @foreach (['a', 'b', 'c', 'd', 'e'] as $opsi)
                                        <div>
                                            <span>{{ strtoupper($opsi) }}</span>
                                            <input type="number" name="skor_{{ $opsi }}"
                                                class="form-control tryout-score-input" min="1" max="5"
                                                value="{{ old('skor_' . $opsi, 6 - $loop->iteration) }}">
                                        </div>
                                    @endforeach
                                </div>
                                <small class="text-muted">Untuk TKP, isi semua skor 1 sampai 5. Opsi terbaik diberi 5, opsi paling kurang tepat diberi 1.</small>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Pembahasan</label>
                                <textarea name="pembahasan" class="form-control" rows="3">{{ old('pembahasan') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Simpan Soal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- notifikasi --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    background: '#ffffff',
                    color: '#0b1f3a',
                    iconColor: '#22c55e',
                    customClass: {
                        popup: 'modern-toast'
                    }
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: "{{ session('error') }}",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    background: '#ffffff',
                    color: '#0b1f3a',
                    iconColor: '#ef4444',
                    customClass: {
                        popup: 'modern-toast'
                    }
                });
            @endif
        });
    </script>

@endsection
