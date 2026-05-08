@extends('layouts.app')

@section('title', 'Sertifikasi | Portfolio CV')

@section('content')

    <section class="sertifikasi-page">
        <div class="container">

            <div class="sertifikasi-header text-center" data-aos="fade-up">
                <span class="section-label">Sertifikasi</span>
                <h1>Daftar Sertifikasi</h1>
                <p>Kumpulan sertifikat, pelatihan, dan bukti keahlian yang pernah saya ikuti.</p>
            </div>

            <div class="row g-4 mt-5">
                @forelse($sertifikasi as $item)
                    <div class="col-md-6 col-lg-4" data-aos="fade-up">
                        <div class="sertifikasi-card h-100">

                            <div class="sertifikasi-icon">
                                <i class="bi bi-award-fill"></i>
                            </div>

                            <h4>{{ $item->nama_sertifikat }}</h4>

                            <p class="mb-1">
                                <strong>Penyelenggara:</strong> {{ $item->penyelenggara }}
                            </p>

                            <span class="sertifikasi-year">
                                {{ $item->tahun }}
                            </span>

                            <p class="sertifikasi-desc mt-3">
                                {{ $item->deskripsi }}
                            </p>

                            @if ($item->file_pdf)
                                <a href="{{ asset('sertifikat/' . $item->file_pdf) }}" target="_blank"
                                    class="btn-sertifikasi">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                    Lihat Sertifikat
                                </a>
                            @else
                                <span class="text-muted">PDF belum tersedia</span>
                            @endif

                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p class="text-muted">Belum ada data sertifikasi.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </section>

@endsection
