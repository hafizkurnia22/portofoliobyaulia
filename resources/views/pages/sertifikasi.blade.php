@extends('layouts.app')

@section('title', 'Sertifikasi | Portofolio')

@section('content')
    <section class="certification-library portfolio-section" aria-labelledby="certification-title">
        <div class="container">
            <header class="certification-library-heading">
                <h1 id="certification-title">Sertifikasi<span>.</span></h1>
                <p>Sertifikat dan pelatihan yang pernah saya ikuti.</p>
            </header>

            <div class="certification-library-list">
                @forelse ($sertifikasi as $item)
                    <article class="certification-library-entry">
                        <p class="portfolio-label">{{ $item->tahun ?? 'Tahun belum tersedia' }}</p>
                        <div>
                            <h2>{{ $item->nama_sertifikat }}</h2>
                            <p class="certification-library-provider">{{ $item->penyelenggara }}</p>
                            @if (!empty($item->deskripsi))
                                <p class="certification-library-description">{{ $item->deskripsi }}</p>
                            @endif
                        </div>
                        @if ($item->file_pdf)
                            <a href="{{ asset('sertifikat/' . $item->file_pdf) }}" target="_blank" rel="noopener noreferrer"
                                class="portfolio-text-link" aria-label="Lihat sertifikat {{ $item->nama_sertifikat }} (PDF, tab baru)">
                                Lihat PDF <i class="bi bi-arrow-up-right" aria-hidden="true"></i>
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
@endsection
