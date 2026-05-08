@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

    @php
        $activeTab = session('active_tab', 'pengalaman');
    @endphp

    <section class="admin-dashboard">
        <div class="container">

            <div class="admin-header" data-aos="fade-down">
                <div>
                    <span class="admin-label">Admin Panel</span>
                    <h1>Dashboard Portfolio</h1>
                    <p>Kelola data pengalaman kerja, sertifikasi, dan skill.</p>
                </div>

                <a href="/admin/logout" class="btn-admin-logout">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="admin-stat-card">
                        <i class="bi bi-briefcase-fill"></i>
                        <div>
                            <h4>{{ $pengalaman->total() }}</h4>
                            <p>Total Pengalaman</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="admin-stat-card">
                        <i class="bi bi-award-fill"></i>
                        <div>
                            <h4>{{ $sertifikasi->total() }}</h4>
                            <p>Total Sertifikasi</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="admin-stat-card">
                        <i class="bi bi-bar-chart-fill"></i>
                        <div>
                            <h4>{{ $skill->total() }}</h4>
                            <p>Total Skill</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-table-card" data-aos="fade-up" data-aos-delay="400">

                <ul class="nav nav-pills admin-tabs mb-4" id="adminTabs" role="tablist" data-aos="zoom-in">

                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $activeTab == 'pengalaman' ? 'active' : '' }}" data-bs-toggle="pill"
                            data-bs-target="#pengalaman-panel" type="button" role="tab">
                            <i class="bi bi-briefcase"></i> Pengalaman
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $activeTab == 'sertifikasi' ? 'active' : '' }}" data-bs-toggle="pill"
                            data-bs-target="#sertifikasi-panel" type="button" role="tab">
                            <i class="bi bi-award"></i> Sertifikasi
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $activeTab == 'skill' ? 'active' : '' }}" data-bs-toggle="pill"
                            data-bs-target="#skill-panel" type="button" role="tab">
                            <i class="bi bi-bar-chart-fill"></i> Skill
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $activeTab == 'tentang' ? 'active' : '' }}" data-bs-toggle="pill"
                            data-bs-target="#tentang-panel" type="button" role="tab">
                            <i class="bi bi-person-circle"></i> Tentang Saya
                        </button>
                    </li>

                </ul>

                <div class="tab-content">

                    {{-- TAB PENGALAMAN --}}
                    <div class="tab-pane fade {{ $activeTab == 'pengalaman' ? 'show active' : '' }}" id="pengalaman-panel"
                        role="tabpanel">

                        <div class="admin-table-header" data-aos="fade-right">
                            <div>
                                <h5>Data Pengalaman Kerja</h5>
                                <p>Tambah, edit, atau hapus pengalaman kerja.</p>
                            </div>

                            <button type="button" class="btn-admin-add" data-bs-toggle="modal"
                                data-bs-target="#tambahModal">
                                <i class="bi bi-plus-circle"></i> Tambah Pengalaman
                            </button>
                        </div>

                        <div class="table-responsive" data-aos="fade-up">
                            <table class="table admin-table align-middle">
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

                                                    <form action="/hapus-pengalaman/{{ $item->id }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit" class="btn-admin-delete"
                                                            onclick="return confirm('Yakin ingin menghapus data ini?')">
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
                                {{ $pengalaman->appends(request()->query())->links() }}
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

                        <div class="table-responsive" data-aos="fade-up">
                            <table class="table admin-table align-middle">
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

                                                    <form action="/hapus-sertifikasi/{{ $item->id }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit" class="btn-admin-delete"
                                                            onclick="return confirm('Yakin ingin menghapus sertifikasi ini?')">
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
                                {{ $sertifikasi->appends(request()->query())->links() }}
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

                        <div class="table-responsive" data-aos="fade-up">
                            <table class="table admin-table align-middle">
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

                                                    <form action="/hapus-skill/{{ $item->id }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit" class="btn-admin-delete"
                                                            onclick="return confirm('Yakin ingin menghapus skill ini?')">
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
                                {{ $skill->appends(request()->query())->links() }}
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

                            <form action="/simpan-tentang-saya" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Nama</label>
                                        <input type="text" name="nama" class="form-control"
                                            value="{{ $tentangSaya->nama ?? '' }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Bidang</label>
                                        <input type="text" name="bidang" class="form-control"
                                            value="{{ $tentangSaya->bidang ?? '' }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Status</label>
                                        <input type="text" name="status" class="form-control"
                                            value="{{ $tentangSaya->status ?? '' }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Foto Profile</label>
                                        <input type="file" name="foto" class="form-control">

                                        @if (isset($tentangSaya->foto))
                                            <img src="{{ asset('images/' . $tentangSaya->foto) }}" width="100"
                                                class="mt-3 rounded-3">
                                        @endif
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label>Deskripsi 1</label>
                                        <textarea name="deskripsi_1" rows="4" class="form-control">{{ $tentangSaya->deskripsi_1 ?? '' }}</textarea>
                                    </div>

                                    <div class="col-12 mb-4">
                                        <label>Deskripsi 2</label>
                                        <textarea name="deskripsi_2" rows="4" class="form-control">{{ $tentangSaya->deskripsi_2 ?? '' }}</textarea>
                                    </div>
                                </div>

                                <button class="btn btn-primary rounded-pill px-4">
                                    <i class="bi bi-save"></i>
                                    Update Profil
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

        </div>
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

@endsection
