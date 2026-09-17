@extends('layouts.app')

@section('title', 'Pengaturan Soal Tryout')

@section('content')
    <section class="admin-dashboard">
        <div class="container">
            <div class="admin-header" data-aos="fade-down">
                <div>
                    <span class="admin-label">Tryout CPNS</span>
                    <h1>Pengaturan Soal</h1>
                    <p>Atur durasi, jumlah soal, acuan kisi-kisi, dan mode latihan per kategori.</p>
                </div>

                <div class="admin-header-right">
                    <div class="d-flex gap-2 flex-wrap">
                        <button type="button" class="btn-dark-toggle" id="darkModeToggle">
                            <i class="bi bi-moon-stars-fill"></i>
                            <span>Dark Mode</span>
                        </button>

                        <a href="{{ url('/admin/dashboard?active_tab=master-soal') }}" class="btn-admin-logout btn-admin-back">
                            <i class="bi bi-arrow-left"></i>
                            Master Soal
                        </a>

                        <a href="/admin/logout" class="btn-admin-logout">
                            <i class="bi bi-box-arrow-right"></i>
                            Logout
                        </a>
                    </div>
                </div>
            </div>

            <div class="admin-table-card tryout-settings-page-card" data-aos="fade-up">
                <div class="admin-table-header">
                    <div>
                        <h5>Konfigurasi Latihan dan Simulasi</h5>
                        <p>Pengaturan ini akan langsung dipakai di halaman Tryout CPNS peserta.</p>
                    </div>

                    <a href="{{ url('/tryout?tab=simulasi') }}" class="btn-admin-setting" target="_blank" rel="noopener">
                        <i class="bi bi-box-arrow-up-right"></i>
                        Lihat Halaman Tryout
                    </a>
                </div>

                @include('admin.partials.tryout-settings-form')
            </div>
        </div>
    </section>
@endsection
