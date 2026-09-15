@extends('layouts.app')

@section('title', $materi->judul . ' | Materi Tryout CPNS')

@section('content')
    <section class="tryout-page">
        <div class="container">
            <div class="tryout-header" data-aos="fade-down">
                <span class="section-label">Materi Ujian</span>
                <h1>{{ $materi->judul }}</h1>
                <p>{{ $materi->ringkasan ?: 'Pembahasan pembelajaran untuk persiapan tryout CAT CPNS.' }}</p>
                <div class="tryout-participant-bar">
                    <span>
                        <i class="bi bi-person-check"></i>
                        {{ $peserta->nama ?: $peserta->username }}
                    </span>

                    <form action="{{ route('tryout.logout') }}" method="POST">
                        @csrf
                        <button type="submit">
                            <i class="bi bi-box-arrow-right"></i>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>

            <div class="tryout-mode-actions" data-aos="fade-up">
                <a href="{{ route('tryout.index', ['mode' => 'materi']) }}" class="cat-action-btn cat-action-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Daftar Materi
                </a>

                <a href="{{ route('tryout.index', ['mode' => 'ujian']) }}" class="cat-action-btn">
                    <i class="bi bi-display"></i>
                    Ikuti Simulasi
                </a>
            </div>

            <article class="tryout-materi-detail-page" data-aos="fade-up">
                <div class="tryout-materi-group-title">
                    <span>{{ $materi->kategoriSoal->kode ?? 'LAIN' }}</span>
                    <strong>{{ $materi->kategoriSoal->nama ?? 'Materi Tambahan' }}</strong>
                </div>

                <div class="tryout-materi-detail-card">
                    @if ($materi->ringkasan)
                        <p class="tryout-materi-lead">{{ $materi->ringkasan }}</p>
                    @endif

                    <div class="tryout-materi-content">
                        @if (\Illuminate\Support\Str::contains($materi->isi_materi, '<'))
                            {!! $materi->isi_materi !!}
                        @else
                            {!! nl2br(e($materi->isi_materi)) !!}
                        @endif
                    </div>
                </div>
            </article>

            @if ($materiLainnya->isNotEmpty())
                <div class="tryout-related-materi" data-aos="fade-up">
                    <div class="tryout-materi-heading">
                        <span>Materi Lainnya</span>
                        <h2>Lanjutkan Belajar</h2>
                    </div>

                    <div class="tryout-related-grid">
                        @foreach ($materiLainnya as $item)
                            <a href="{{ route('tryout.materi.show', $item) }}" class="tryout-choice-card">
                                <span><i class="bi bi-journal-bookmark"></i></span>
                                <strong>{{ $item->judul }}</strong>
                                <small>{{ $item->kategoriSoal->kode ?? 'LAIN' }} - {{ \Illuminate\Support\Str::limit(strip_tags($item->ringkasan ?: $item->isi_materi), 100) }}</small>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
