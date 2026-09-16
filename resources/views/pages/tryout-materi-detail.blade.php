@extends('layouts.app')
@section('title', $materi->judul . ' | Materi Tryout CPNS')
@section('content')
<section class="tryout-page learning-page learning-reader">
    <div class="container">
        <nav class="reading-breadcrumb" aria-label="Jejak halaman">
            <a href="{{ route('tryout.index', ['tab' => 'materi']) }}"><i class="bi bi-arrow-left" aria-hidden="true"></i> Semua materi</a>
            <span>/</span><span>{{ $materi->kategoriSoal->kode ?? 'Materi' }}</span>
        </nav>
        <header class="reading-header">
            <p class="learning-eyebrow">{{ $materi->kategoriSoal->nama ?? 'Materi tambahan' }}</p>
            <h1>{{ $materi->judul }}</h1>
            <p>{{ $materi->ringkasan }}</p>
            <div class="reading-meta">
                <span><i class="bi bi-clock" aria-hidden="true"></i> {{ max(1, (int) ceil(count(preg_split('/\s+/u', strip_tags($materi->isi_materi), -1, PREG_SPLIT_NO_EMPTY)) / 180)) }} menit baca</span>
                @if ($materi->topikPelajaran())
                    <span>{{ implode(' · ', $materi->topikPelajaran()) }}</span>
                @endif
            </div>
        </header>
        <div class="reading-layout">
            <aside class="reading-sidebar" id="readingContents" hidden>
                <nav aria-label="Daftar isi materi">
                    <h2>Dalam materi ini</h2>
                    <ol id="readingContentsList"></ol>
                </nav>
                <a href="{{ route('tryout.index', ['tab' => 'materi']) }}">Pilih topik lain</a>
            </aside>
            <div>
                <article class="reading-article" id="readingArticle" aria-label="Isi materi">
                    @if (\Illuminate\Support\Str::contains($materi->isi_materi, '<'))
                        {!! $materi->isi_materi !!}
                    @else
                        {!! nl2br(e($materi->isi_materi)) !!}
                    @endif
                </article>
                <div class="reading-next">
                    <div><h2>Sudah memahami materinya?</h2><p>Lanjutkan belajar atau coba terapkan dalam simulasi ujian.</p></div>
                    <a href="{{ route('tryout.index', ['tab' => 'simulasi']) }}" class="materi-read-link">Coba simulasi <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                </div>
                @if ($materiLainnya->isNotEmpty())
                    <section class="reading-related" aria-labelledby="relatedHeading">
                        <h2 id="relatedHeading">Bacaan berikutnya</h2>
                        @foreach ($materiLainnya as $item)
                            <a href="{{ route('tryout.materi.show', $item) }}"><span>{{ $item->kategoriSoal->kode ?? 'Materi' }}</span> {{ $item->judul }} <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                        @endforeach
                    </section>
                @endif
            </div>
        </div>
    </div>
</section>
<script src="{{ asset('js/materi-reader.js') }}?v={{ filemtime(public_path('js/materi-reader.js')) }}" defer></script>
@endsection
