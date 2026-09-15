<div class="materi-library" id="materiLibrary">
    <header>
        <h2>Materi belajar</h2>
        <p>Pilih jenis tes dan topik yang ingin Anda pelajari, lalu buka bacaannya.</p>
    </header>
    <div class="materi-controls">
        <label>Cari materi
            <input type="search" id="materiSearchInput" placeholder="Contoh: nasionalisme" autocomplete="off">
        </label>
        <label>Jenis tes
            <select id="materiCategory">
                <option value="">Semua jenis tes</option>
                @foreach (['TWK' => 'Wawasan Kebangsaan', 'TIU' => 'Intelegensia Umum', 'TKP' => 'Karakteristik Pribadi'] as $kode => $nama)
                    <option value="{{ $kode }}">{{ $kode }} — {{ $nama }}</option>
                @endforeach
                @foreach ($kategoriMateriOptions->diff(['TWK', 'TIU', 'TKP']) as $kode)
                    <option value="{{ $kode }}">{{ $kode }}</option>
                @endforeach
            </select>
        </label>
        <label>Jenis pelajaran
            <select id="materiTopic">
                <option value="">Semua topik pelajaran</option>
                @foreach (\App\Models\TryoutMateri::TOPIK as $kode => $topics)
                    <optgroup label="{{ $kode }}" data-category="{{ $kode }}">
                        @foreach ($topics as $topic)
                            <option value="{{ $topic }}" data-category="{{ $kode }}">{{ $topic }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </label>
    </div>
    <div class="materi-list-status">
        <p id="materiResultCount" role="status">{{ $allMateri->count() }} bacaan tersedia</p>
        <button type="button" id="materiReset" hidden>Hapus pencarian & filter</button>
    </div>
    <div class="materi-reading-list">
        @foreach ($allMateri as $materi)
            @php
                $topics = $materi->topikPelajaran();
                $summary = strip_tags($materi->ringkasan ?: $materi->isi_materi);
            @endphp
            <article class="materi-reading-item" data-category="{{ $materi->kategoriSoal->kode ?? 'LAIN' }}"
                data-topics="{{ json_encode($topics) }}" data-search="{{ $materi->judul . ' ' . $summary . ' ' . implode(' ', $topics) }}">
                <div>
                    <p class="materi-category-label">{{ $materi->kategoriSoal->kode ?? 'Materi tambahan' }}</p>
                    <h3><a href="{{ route('tryout.materi.show', $materi) }}">{{ $materi->judul }}</a></h3>
                    <p class="materi-reading-summary">{{ \Illuminate\Support\Str::limit($summary, 160) }}</p>
                    <p class="materi-topic-label">Topik: {{ $topics ? implode(' · ', $topics) : 'Materi umum' }}</p>
                </div>
                <a class="materi-read-link" href="{{ route('tryout.materi.show', $materi) }}">Baca materi <i class="bi bi-arrow-right" aria-hidden="true"></i><span class="visually-hidden">: {{ $materi->judul }}</span></a>
            </article>
        @endforeach
    </div>
    <div id="materiEmpty" class="materi-empty" @if ($allMateri->isNotEmpty()) hidden @endif>
        <h3>Belum ada bacaan yang sesuai</h3>
        <p>Coba topik lain atau hapus pencarian dan filter untuk melihat semua materi.</p>
    </div>
    <p class="materi-source">Pengelompokan topik mengacu pada <a href="https://jdih.menpan.go.id/dokumen-hukum/keputusan-menteri-pendayagunaan-aparatur-negara-dan-reformasi-birokrasi-nomor-321-tahun-2024-tentang-1851" target="_blank" rel="noopener">KepmenPANRB No. 321 Tahun 2024<span class="visually-hidden"> (tab baru)</span></a>.</p>
</div>
