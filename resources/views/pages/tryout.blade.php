@extends('layouts.app')

@section('title', 'Tryout CPNS')

@section('content')
    @php
        $tryoutMode = request('mode', 'menu');
        $tryoutMode = $tryoutMode === 'ujian' ? 'ujian' : 'menu';
        $showExam = $tryoutMode === 'ujian';
        $practiceCategory = $practiceCategory ?? null;
        $latihanKategori = $latihanKategori ?? collect();
        $materiProgress = $materiProgress ?? collect();
        $scoreStatistics = $scoreStatistics ?? [];
        $recommendedMateri = $recommendedMateri ?? collect();
        $wrongReviewItems = $wrongReviewItems ?? collect();
        $examDurationMinutes = $examDurationMinutes ?? (int) ($tryoutPengaturan->durasi_menit ?? 45);
        $practiceLabels = [
            'TWK' => 'Tes Wawasan Kebangsaan',
            'TIU' => 'Tes Intelegensia Umum',
            'TKP' => 'Tes Karakteristik Pribadi',
        ];
        $practiceFocus = [
            'TWK' => 'Pancasila, UUD 1945, NKRI, Bhinneka Tunggal Ika, nasionalisme, integritas, bela negara, dan bahasa negara.',
            'TIU' => 'Kemampuan verbal, numerik, logika, analitis, figural, deret, perbandingan, dan soal cerita.',
            'TKP' => 'Pelayanan publik, jejaring kerja, sosial budaya, teknologi informasi, profesionalisme, dan anti radikalisme.',
        ];
        $practiceIcons = [
            'TWK' => 'bi-bank',
            'TIU' => 'bi-calculator',
            'TKP' => 'bi-people',
        ];
        $practiceLabel = $practiceCategory ? ($practiceLabels[$practiceCategory] ?? $practiceCategory) : null;
        $examTitle = $practiceCategory ? 'Latihan ' . $practiceCategory : 'Tryout CPNS';
        $activeTryoutTab = request('tab', 'materi');
        $activeTryoutTab = in_array($activeTryoutTab, ['materi', 'simulasi', 'evaluasi'], true) ? $activeTryoutTab : 'materi';
        $defaultKisiKisi = 'Pelajari materi dan berlatih menjawab soal untuk persiapan seleksi CPNS. Dokumen acuan membantu Anda memahami cakupan materi yang dipelajari.';
        $legacyKisiKisi = 'Materi dan simulasi Tryout CPNS disusun berdasarkan kisi-kisi seleksi kompetensi dasar yang berlaku. Admin dapat memperbarui keterangan ini dan mengunggah surat PermenPAN terbaru sebagai acuan belajar peserta.';
        $tryoutKisiKisi = trim($tryoutPengaturan->kisi_kisi_deskripsi ?? '');
        if ($tryoutKisiKisi === '' || $tryoutKisiKisi === $legacyKisiKisi) {
            $tryoutKisiKisi = $defaultKisiKisi;
        }
        $materiKategori = ['TWK', 'TIU', 'TKP'];
        $allMateri = isset($materiTryout)
            ? $materiTryout->flatten(1)->sortBy(function ($materi) {
                $kode = optional($materi->kategoriSoal)->kode;
                $group = array_search($kode, ['TWK', 'TIU', 'TKP']);
                $topic = array_search($materi->judul, \App\Models\TryoutMateri::TOPIK[$kode] ?? []);
                return sprintf('%02d-%03d-%s', $group === false ? 9 : $group, $topic === false ? 99 : $topic, $materi->judul);
            })->values()
            : collect();
        $kategoriMateriOptions = $allMateri->map(fn ($materi) => optional($materi->kategoriSoal)->kode ?? 'LAIN')->unique()->values();
        $hasMateri = $allMateri->isNotEmpty();
    @endphp

    <section class="tryout-page {{ $showExam ? '' : 'learning-page' }}">
        <div class="container">
            @if ($showExam)
                <div class="cat-exam-toolbar">
                    <div class="cat-exam-identity">
                        <h1>{{ $examTitle }}</h1>
                        <div class="cat-exam-statuses" aria-label="Status ujian">
                            <span class="cat-exam-session-badge" id="examSessionStatus" role="status">Persiapan ujian</span>
                            <div class="cat-inline-connection is-checking" id="connectionStatus" role="status" aria-live="polite">
                                <span class="cat-signal-bars" aria-hidden="true"><i></i><i></i><i></i><i></i></span>
                                <span id="connectionStatusText">Memeriksa koneksi</span>
                            </div>
                            <div class="cat-inline-draft" id="draftStatus" role="status" aria-live="polite">
                                <i class="bi bi-save2" aria-hidden="true"></i>
                                <span id="draftStatusText">Belum disimpan</span>
                            </div>
                        </div>
                    </div>
                    <div class="cat-exam-tools">
                        <span class="cat-exam-participant"><i class="bi bi-person-check" aria-hidden="true"></i> {{ $peserta->nama ?: $peserta->username }}</span>
                        <a href="{{ route('tryout.index', ['tab' => 'simulasi']) }}" class="cat-exam-back"><i class="bi bi-arrow-left" aria-hidden="true"></i> Beranda Latihan</a>
                    </div>
                </div>
            @else
            <div class="tryout-header" data-aos="fade-down">
                <h1>Tryout CPNS</h1>
                <p>Mulai dengan membaca materi, coba latihan ujian, lalu lihat hasil dan pembahasannya.</p>
                <div class="tryout-participant-bar">
                    <span>
                        <i class="bi bi-person-check"></i>
                        {{ $peserta->nama ?: $peserta->username }}
                    </span>

                    <form action="{{ route('tryout.logout') }}" method="POST">
                        @csrf
                        <button type="submit">
                            <i class="bi bi-box-arrow-right"></i>
                            Keluar akun
                        </button>
                    </form>
                </div>
            </div>
            @endif

            @if ($tryoutMode === 'menu')
                <div class="tryout-reference-card" data-aos="fade-up">
                    <div class="tryout-reference-icon">
                        <i class="bi bi-patch-check"></i>
                    </div>
                    <div>
                        <h2>Panduan belajar CPNS</h2>
                        <p>{{ $tryoutKisiKisi }}</p>
                        @if ($tryoutPengaturan->permenpan_file ?? null)
                            <div class="tryout-reference-meta">
                                <a href="{{ asset('storage/' . $tryoutPengaturan->permenpan_file) }}" target="_blank" rel="noopener">
                                    <i class="bi bi-file-earmark-pdf" aria-hidden="true"></i>
                                    Baca dokumen acuan (PDF)
                                    <i class="bi bi-box-arrow-up-right" aria-hidden="true"></i>
                                    <span class="visually-hidden"> — dibuka di tab baru</span>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="tryout-tabs" role="tablist" aria-label="Menu Tryout CPNS" data-aos="fade-up">
                    <a href="{{ route('tryout.index', ['tab' => 'materi']) }}" class="tryout-tab {{ $activeTryoutTab === 'materi' ? 'active' : '' }}">
                        <span>1</span>
                        Pelajari Materi
                    </a>
                    <a href="{{ route('tryout.index', ['tab' => 'simulasi']) }}" class="tryout-tab {{ $activeTryoutTab === 'simulasi' ? 'active' : '' }}">
                        <span>2</span>
                        Simulasi Ujian
                    </a>
                    <a href="{{ route('tryout.index', ['tab' => 'evaluasi']) }}" class="tryout-tab {{ $activeTryoutTab === 'evaluasi' ? 'active' : '' }}">
                        <span>3</span>
                        Evaluasi Hasil
                    </a>
                </div>

                <div class="tryout-tab-panel {{ $activeTryoutTab === 'materi' ? '' : 'd-none' }}" data-tryout-panel="materi">
                    @include('components.materi-library')
                </div>

                <div class="tryout-tab-panel {{ $activeTryoutTab === 'simulasi' ? '' : 'd-none' }}" data-tryout-panel="simulasi">
                    <div class="tryout-simulation-panel" data-aos="fade-up">
                        <div>
                            <span class="tryout-panel-label">Petunjuk Ujian</span>
                            <h2>Pilih cara latihan yang paling sesuai</h2>
                            <p>Mulai dari latihan kategori jika ingin fokus memperbaiki bagian tertentu, atau gunakan simulasi penuh untuk merasakan suasana ujian lengkap.</p>
                            <ul class="cat-instructions">
                                <li><strong>Latihan kategori</strong> cocok untuk belajar TWK, TIU, atau TKP secara bertahap.</li>
                                <li><strong>Simulasi penuh</strong> memakai semua kategori aktif sesuai pengaturan admin.</li>
                                <li>Setiap jawaban tersimpan otomatis dan soal berikutnya langsung muncul.</li>
                                <li>Setelah selesai, buka tab <strong>Evaluasi Hasil</strong> untuk melihat skor latihan.</li>
                            </ul>
                            <div class="cat-actions">
                                <a href="{{ route('tryout.index', ['mode' => 'ujian']) }}" class="cat-action-btn">
                                    <i class="bi bi-play-circle"></i>
                                    Simulasi Penuh
                                </a>
                                <a href="{{ route('tryout.index', ['tab' => 'materi']) }}" class="cat-action-btn cat-action-secondary tryout-tab-inline-link">
                                    <i class="bi bi-journal-bookmark"></i>
                                    Pelajari Materi
                                </a>
                            </div>
                        </div>
                        <div class="tryout-simulation-summary">
                            <span><strong>{{ $soals->count() }}</strong> Soal Aktif</span>
                            <span><strong>{{ $examDurationMinutes ?? ($tryoutPengaturan->durasi_menit ?? 45) }}</strong> Menit</span>
                            <span><strong>{{ $soals->pluck('kategori')->unique()->count() }}</strong> Kategori</span>
                        </div>
                    </div>

                    <div class="practice-category-section" data-aos="fade-up">
                        <div class="practice-category-heading">
                            <span class="tryout-panel-label">Mode Latihan</span>
                            <h2>Latihan per kategori</h2>
                            <p>Pilih satu kategori agar belajar lebih fokus. Cocok untuk mengulang bagian yang masih lemah sebelum mencoba simulasi penuh.</p>
                        </div>

                        <div class="practice-category-grid">
                            @forelse ($latihanKategori as $kategoriLatihan)
                                @php
                                    $kodeLatihan = $kategoriLatihan->kode;
                                    $jumlahLatihan = $tryoutPengaturan->jumlahSoalKategori($kodeLatihan);
                                    $durasiLatihan = $tryoutPengaturan->durasiMenitKategori($kodeLatihan);
                                @endphp
                                <article class="practice-category-card">
                                    <div class="practice-category-icon">
                                        <i class="bi {{ $practiceIcons[$kodeLatihan] ?? 'bi-journal-check' }}" aria-hidden="true"></i>
                                    </div>
                                    <div class="practice-category-copy">
                                        <span>{{ $kodeLatihan }}</span>
                                        <h3>{{ $practiceLabels[$kodeLatihan] ?? $kategoriLatihan->nama }}</h3>
                                        <p>{{ $practiceFocus[$kodeLatihan] ?? ($kategoriLatihan->deskripsi ?: 'Latihan soal berdasarkan kategori yang tersedia.') }}</p>
                                    </div>
                                    <dl class="practice-category-meta">
                                        <div>
                                            <dt>Ditampilkan</dt>
                                            <dd>{{ min($jumlahLatihan, $kategoriLatihan->soal_aktif_count) }}</dd>
                                        </div>
                                        <div>
                                            <dt>Durasi</dt>
                                            <dd>{{ $durasiLatihan }}<small> mnt</small></dd>
                                        </div>
                                    </dl>
                                    <p class="practice-category-availability">{{ $kategoriLatihan->soal_aktif_count }} soal aktif · {{ $kategoriLatihan->materi_aktif_count }} materi tersedia</p>
                                    <a href="{{ route('tryout.index', ['mode' => 'ujian', 'latihan' => $kodeLatihan]) }}"
                                        class="practice-category-action {{ $kategoriLatihan->soal_aktif_count < 1 ? 'disabled' : '' }}"
                                        @if ($kategoriLatihan->soal_aktif_count < 1) aria-disabled="true" tabindex="-1" @endif>
                                        Mulai latihan {{ $kodeLatihan }}
                                        <i class="bi bi-arrow-right" aria-hidden="true"></i>
                                    </a>
                                </article>
                            @empty
                                <div class="tryout-history-empty">
                                    Kategori latihan belum tersedia. Admin dapat mengaktifkan kategori TWK, TIU, atau TKP terlebih dahulu.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="tryout-tab-panel {{ $activeTryoutTab === 'evaluasi' ? '' : 'd-none' }}" data-tryout-panel="evaluasi">
                    <div class="tryout-evaluation-grid" data-aos="fade-up">
                        <section class="tryout-evaluation-card tryout-score-card" aria-labelledby="scoreTrendHeading">
                            <div class="tryout-evaluation-heading">
                                <span>Perkembangan</span>
                                <h2 id="scoreTrendHeading">Statistik skor latihan</h2>
                                <p>Bandingkan skor terbaik, terendah, dan rata-rata pada setiap jenis latihan.</p>
                            </div>

                            @if (collect($scoreStatistics)->contains(fn ($tab) => $tab['statistics'] ?? null))
                                @php
                                    $statTabs = ['FULL' => 'Simulasi Penuh', 'TWK' => 'TWK', 'TIU' => 'TIU', 'TKP' => 'TKP'];
                                @endphp
                                <div class="tryout-score-tabs" role="tablist" aria-label="Jenis statistik latihan">
                                    @foreach ($statTabs as $key => $label)
                                        <button type="button" class="tryout-score-tab {{ $key === 'FULL' ? 'active' : '' }}"
                                            role="tab" aria-selected="{{ $key === 'FULL' ? 'true' : 'false' }}"
                                            data-score-tab="{{ $key }}">{{ $label }}</button>
                                    @endforeach
                                </div>

                                @foreach ($statTabs as $key => $label)
                                    @php $statistics = $scoreStatistics[$key]['statistics'] ?? null; @endphp
                                    <section class="tryout-score-panel {{ $key === 'FULL' ? '' : 'd-none' }}" role="tabpanel" data-score-panel="{{ $key }}">
                                        @if ($statistics)
                                            <p class="tryout-score-count">{{ $statistics['count'] }} latihan {{ strtolower($label) }} tercatat.</p>
                                            <div class="tryout-score-summary-grid">
                                                <div>
                                                    <span>Skor terbaik</span>
                                                    <strong>{{ $statistics['best']['score'] }}</strong>
                                                    <small>Maksimum {{ $statistics['best']['max_score'] }}</small>
                                                </div>
                                                <div>
                                                    <span>Skor terendah</span>
                                                    <strong>{{ $statistics['worst']['score'] }}</strong>
                                                    <small>Maksimum {{ $statistics['worst']['max_score'] }}</small>
                                                </div>
                                                <div>
                                                    <span>Rata-rata skor</span>
                                                    <strong>{{ $statistics['average_score'] }}</strong>
                                                    <small>Dari {{ $statistics['count'] }} latihan</small>
                                                </div>
                                            </div>

                                            @if ($key === 'FULL')
                                                <div class="tryout-full-category-stats">
                                                    <h3>Rincian skor simulasi penuh</h3>
                                                    <p>Statistik tiap kategori dihitung dari seluruh simulasi penuh yang telah kamu selesaikan.</p>
                                                    <div>
                                                        @foreach (['TWK', 'TIU', 'TKP'] as $category)
                                                            @php $categoryStats = $scoreStatistics['FULL']['categories'][$category] ?? null; @endphp
                                                            <article>
                                                                <strong>{{ $category }}</strong>
                                                                @if ($categoryStats)
                                                                    <span>Terbaik {{ $categoryStats['best']['score'] }} · Terendah {{ $categoryStats['worst']['score'] }} · Rata-rata {{ $categoryStats['average_score'] }}</span>
                                                                @else
                                                                    <span>Belum ada data kategori ini.</span>
                                                                @endif
                                                            </article>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        @else
                                            <div class="tryout-history-empty">Belum ada riwayat {{ strtolower($label) }}. Selesaikan latihan untuk melihat statistiknya.</div>
                                        @endif
                                    </section>
                                @endforeach
                            @else
                                <div class="tryout-history-empty">Statistik akan tampil setelah kamu menyelesaikan latihan pertama.</div>
                            @endif
                        </section>

                        <section class="tryout-evaluation-card" aria-labelledby="recommendationHeading">
                            <div class="tryout-evaluation-heading">
                                <span>Rekomendasi</span>
                                <h2 id="recommendationHeading">Materi yang sebaiknya dipelajari</h2>
                                <p>Rekomendasi diambil dari kategori yang masih perlu diperbaiki dan status bacaanmu.</p>
                            </div>

                            <div class="tryout-recommendation-list">
                                @forelse ($recommendedMateri as $materiRekomendasi)
                                    @php
                                        $progress = $materiProgress->get($materiRekomendasi->id);
                                    @endphp
                                    <article class="tryout-recommendation-item">
                                        <div>
                                            <span>{{ $materiRekomendasi->kategoriSoal->kode ?? 'Materi' }}</span>
                                            <h3>{{ $materiRekomendasi->judul }}</h3>
                                            <p>{{ \Illuminate\Support\Str::limit(strip_tags($materiRekomendasi->ringkasan ?: $materiRekomendasi->isi_materi), 110) }}</p>
                                            <small>{{ $progress?->read_at ? 'Sudah dibaca' : 'Belum dibaca' }}{{ $progress?->is_bookmarked ? ' · Bookmark' : '' }}</small>
                                        </div>
                                        <a href="{{ route('tryout.materi.show', $materiRekomendasi) }}">Baca</a>
                                    </article>
                                @empty
                                    <div class="tryout-history-empty">Selesaikan latihan dulu agar sistem dapat memberi rekomendasi materi.</div>
                                @endforelse
                            </div>
                        </section>
                    </div>

                    <section class="tryout-evaluation-card tryout-wrong-review" data-aos="fade-up" aria-labelledby="wrongReviewHeading">
                        <div class="tryout-evaluation-heading">
                            <span>Review Cepat</span>
                            <h2 id="wrongReviewHeading">Jawaban salah yang perlu ditinjau</h2>
                            <p>Mulai dari soal yang salah agar kamu tahu bagian mana yang perlu diulang.</p>
                        </div>

                        <div class="tryout-wrong-list">
                            @forelse ($wrongReviewItems as $item)
                                <article class="tryout-wrong-item">
                                    <span>{{ $item['kategori'] ?? 'Soal' }}</span>
                                    <h3>{{ \Illuminate\Support\Str::limit($item['pertanyaan'], 170) }}</h3>
                                    <p>Jawabanmu: <strong>{{ $item['jawaban'] }}</strong> · Jawaban benar: <strong>{{ $item['jawaban_benar'] }}</strong></p>
                                    @if ($item['pembahasan'])
                                        <small>{{ \Illuminate\Support\Str::limit(strip_tags($item['pembahasan']), 220) }}</small>
                                    @endif
                                </article>
                            @empty
                                <div class="tryout-history-empty">Belum ada jawaban salah dari latihan terakhir. Kalau sudah latihan, bagian ini akan berisi soal yang perlu ditinjau.</div>
                            @endforelse
                        </div>
                    </section>

                    <div class="tryout-history-card" id="tryoutHistoryCard" data-aos="fade-up">
                        <div class="tryout-history-header">
                            <div>
                                <span>Evaluasi Hasil</span>
                                <h2>Riwayat mengikuti tryout</h2>
                                <p>Lihat skor terakhir, jumlah soal terjawab, dan jawaban benar sebagai bahan evaluasi latihan berikutnya.</p>
                            </div>
                        </div>

                        <div class="tryout-history-list" id="tryoutHistoryList">
                            @forelse ($riwayatTryout as $riwayat)
                                @php
                                    $riwayatKategori = collect($riwayat->detail_jawaban ?? [])
                                        ->pluck('kategori')
                                        ->filter()
                                        ->unique()
                                        ->values();
                                    $riwayatMode = $riwayatKategori->count() === 1
                                        ? 'Latihan ' . $riwayatKategori->first()
                                        : 'Simulasi penuh';
                                @endphp
                                <div class="tryout-history-item">
                                    <div>
                                        <strong>{{ $riwayat->finished_at ? $riwayat->finished_at->format('d M Y H:i') : '-' }}</strong>
                                        <small>{{ $riwayatMode }} · {{ $riwayat->total_dijawab }}/{{ $riwayat->total_soal }} dijawab, {{ $riwayat->total_benar }} benar, {{ $riwayat->total_ragu }} ragu</small>
                                    </div>

                                    <span>
                                        <small>Skor</small>
                                        {{ $riwayat->total_skor }}
                                    </span>
                                </div>
                            @empty
                                <div class="tryout-history-empty" id="tryoutHistoryEmpty">
                                    Belum ada riwayat. Ikuti simulasi pertamamu; hasilnya akan tampil di sini setelah selesai.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif

            <script src="{{ asset('js/materi-library.js') }}?v={{ filemtime(public_path('js/materi-library.js')) }}" defer></script>
            @if ($tryoutMode === 'menu')
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const tabs = Array.from(document.querySelectorAll('.tryout-tab'));
                        const panels = Array.from(document.querySelectorAll('[data-tryout-panel]'));
                        const localTabLinks = Array.from(document.querySelectorAll('.tryout-tab-inline-link'));
                        const scoreTabs = Array.from(document.querySelectorAll('[data-score-tab]'));
                        const scorePanels = Array.from(document.querySelectorAll('[data-score-panel]'));
                        const validTabs = ['materi', 'simulasi', 'evaluasi'];

                        function tabFromUrl(url) {
                            try {
                                const parsedUrl = new URL(url, window.location.origin);
                                const tab = parsedUrl.searchParams.get('tab') || 'materi';
                                return validTabs.includes(tab) ? tab : 'materi';
                            } catch (error) {
                                return 'materi';
                            }
                        }

                        function setActiveTab(tab, shouldPushState = true) {
                            if (!validTabs.includes(tab)) {
                                tab = 'materi';
                            }

                            tabs.forEach(function(tabLink) {
                                const isActive = tabFromUrl(tabLink.href) === tab;
                                tabLink.classList.toggle('active', isActive);
                                tabLink.setAttribute('aria-current', isActive ? 'page' : 'false');
                            });

                            panels.forEach(function(panel) {
                                panel.classList.toggle('d-none', panel.dataset.tryoutPanel !== tab);
                            });

                            if (shouldPushState) {
                                const nextUrl = new URL(window.location.href);
                                nextUrl.searchParams.set('tab', tab);
                                nextUrl.searchParams.delete('mode');
                                window.history.pushState({ tryoutTab: tab }, '', nextUrl);
                            }
                        }

                        tabs.concat(localTabLinks).forEach(function(link) {
                            link.addEventListener('click', function(event) {
                                const tab = tabFromUrl(link.href);
                                event.preventDefault();
                                setActiveTab(tab);
                                document.querySelector('.tryout-tabs')?.scrollIntoView({
                                    behavior: 'auto',
                                    block: 'start'
                                });
                            });
                        });

                        scoreTabs.forEach(function(scoreTab) {
                            scoreTab.addEventListener('click', function() {
                                const selectedTab = scoreTab.dataset.scoreTab;
                                scoreTabs.forEach(function(tab) {
                                    const isActive = tab.dataset.scoreTab === selectedTab;
                                    tab.classList.toggle('active', isActive);
                                    tab.setAttribute('aria-selected', String(isActive));
                                });
                                scorePanels.forEach(function(panel) {
                                    panel.classList.toggle('d-none', panel.dataset.scorePanel !== selectedTab);
                                });
                            });
                        });

                        window.addEventListener('popstate', function() {
                            setActiveTab(tabFromUrl(window.location.href), false);
                        });
                    });
                </script>
            @endif
            @if ($showExam)
                @if ($soals->isEmpty())
                    <div class="tryout-empty-state" data-aos="fade-up">
                        <i class="bi bi-journal-plus"></i>
                        <h3>Belum ada soal aktif</h3>
                        <p>Soal latihan belum tersedia. Kamu bisa mempelajari materi terlebih dahulu.</p>
                    </div>
                @else
                    <div class="cat-preparation cat-main" id="examPreparation">
                        <span class="cat-kategori">Sebelum mulai</span>
                        <h2>{{ $practiceCategory ? 'Siap latihan ' . $practiceCategory . '?' : 'Siap berlatih?' }}</h2>
                        <p>{{ $practiceCategory ? 'Mode ini hanya menampilkan soal ' . $practiceLabel . ' agar kamu bisa fokus pada satu kemampuan.' : 'Luangkan waktu dan pastikan koneksi internetmu stabil.' }}</p>
                        <div class="cat-preparation-stats">
                            <span><i class="bi bi-file-earmark-text"></i> <strong>{{ $soals->count() }} soal</strong></span>
                            <span><i class="bi bi-clock"></i> <strong>{{ $examDurationMinutes ?? ($tryoutPengaturan->durasi_menit ?? 45) }} menit</strong></span>
                            <span><i class="bi bi-journal-check"></i> {{ $soals->pluck('kategori')->unique()->implode(' · ') }}</span>
                        </div>
                        <ul class="cat-instructions">
                            <li>Pilih satu jawaban untuk menyimpan otomatis di perangkat ini dan lanjut ke soal berikutnya. Kamu tetap bisa kembali untuk mengubah jawaban.</li>
                            <li>Gunakan nomor soal untuk berpindah dan tandai <strong>Ragu-ragu</strong> untuk ditinjau kembali.</li>
                            <li>Klik <strong>Selesaikan Ujian</strong> jika sudah siap. Saat waktu habis, ujian selesai otomatis.</li>
                            <li>Progres dipulihkan saat halaman dimuat ulang pada browser dan akun yang sama. Waktu ujian tetap berjalan; hasil akhir dikirim ke server saat selesai.</li>
                        </ul>
                        <div class="cat-actions">
                            <button type="button" class="cat-action-btn" id="startExamButton"><i class="bi bi-play-circle"></i> Mulai Sekarang</button>
                            <a href="{{ route('tryout.index', ['tab' => 'materi']) }}" class="cat-action-btn cat-action-secondary"><i class="bi bi-journal-bookmark"></i> Pelajari Materi</a>
                        </div>
                        <p class="cat-preparation-note">Timer baru berjalan setelah kamu menekan Mulai Sekarang.</p>
                    </div>
                    <div class="cat-shell d-none" id="examShell">
                        <aside class="cat-sidebar">
                            <section class="cat-question-navigation" aria-labelledby="questionNavigationTitle">
                                <div class="cat-question-navigation-header">
                                    <div>
                                        <h3 class="cat-nav-title" id="questionNavigationTitle">Navigasi soal</h3>
                                        <p>Pilih nomor untuk berpindah soal.</p>
                                    </div>
                                    <div class="cat-nav-legend">
                                        <span><i class="legend-unanswered"></i> Belum dijawab</span>
                                        <span><i class="legend-answered"></i> Terjawab</span>
                                        <span><i class="legend-marked"></i> Ragu-ragu</span>
                                    </div>
                                </div>
                                <div class="cat-number-grid" id="questionNav" aria-label="Navigasi soal"></div>
                            </section>

                            <label class="cat-progress-label" for="examProgress" id="examProgressLabel">0 dari {{ $soals->count() }} soal dijawab</label>
                            <progress class="cat-progress" id="examProgress" max="{{ $soals->count() }}" value="0"></progress>
                        </aside>

                        <div class="cat-main">
                            <div class="cat-question-content">
                            <div class="cat-question-top">
                                <div>
                                    <span class="cat-kategori" id="questionCategory">TWK</span>
                                    <h2 id="questionTitle" tabindex="-1">Soal 1</h2>
                                </div>

                                <div class="cat-question-actions">
                                    <div class="cat-timer cat-timer-inline" aria-label="Sisa waktu ujian">
                                        <span>Sisa waktu</span>
                                        <strong id="catTimer">00:00:00</strong>
                                    </div>
                                    <div class="cat-mini-summary" aria-label="Ringkasan jawaban">
                                        <span><strong id="answeredCount">0</strong> Terjawab</span>
                                        <span><strong id="markedCount">0</strong> Ragu</span>
                                    </div>
                                    <button type="button" class="cat-mark-btn" id="markButton">
                                        <i class="bi bi-bookmark"></i>
                                        Ragu-ragu
                                    </button>
                                </div>
                            </div>

                            <p class="cat-save-status" id="answerSaveStatus" role="status">Pilih jawaban untuk menyimpan dan lanjut otomatis.</p>
                            <p class="cat-question-text" id="questionText"></p>

                            <div class="cat-options" id="questionOptions"></div>

                            <div class="cat-actions">
                                <button type="button" class="cat-action-btn cat-action-secondary" id="prevButton">
                                    <i class="bi bi-arrow-left"></i>
                                    Sebelumnya
                                </button>

                                <button type="button" class="cat-action-btn" id="nextButton">
                                    Selanjutnya
                                    <i class="bi bi-arrow-right"></i>
                                </button>

                                <button type="button" class="cat-action-btn cat-finish-btn" id="finishButton">
                                    <i class="bi bi-check-circle"></i>
                                    Selesaikan Ujian
                                </button>

                                <button type="button" class="cat-action-btn cat-result-back-btn d-none" id="showResultButton">
                                    <i class="bi bi-clipboard2-check"></i>
                                    Lihat Hasil
                                </button>
                            </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </section>

    @if ($showExam && $soals->isNotEmpty())
        <div class="modal fade" id="catFinishModal" tabindex="-1" aria-labelledby="catFinishTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title fs-5" id="catFinishTitle">Selesaikan ujian?</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <p id="finishSummary"></p>
                        <p class="mb-0">Periksa kembali jawabanmu. Setelah selesai, jawaban tidak bisa diubah.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cat-action-btn cat-action-secondary" data-bs-dismiss="modal">Lanjut Mengerjakan</button>
                        <button type="button" class="cat-action-btn" id="confirmFinishButton">Ya, Selesaikan</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade cat-result-modal" id="catResultModal" tabindex="-1" aria-hidden="true"
            aria-labelledby="resultTitle" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="cat-result-modal-body">
                        <span class="cat-result-label" id="resultTitle">{{ $practiceCategory ? 'Hasil Latihan ' . $practiceCategory : 'Hasil Tryout CPNS' }}</span>
                        <strong class="cat-result-score-label">Skor</strong>
                        <h2 id="resultScore">0</h2>
                        <p id="resultMeta"></p>
                        <small id="resultSaveStatus" role="status">Menyimpan riwayat...</small>
                        <div class="cat-result-qualification is-pending" id="resultQualification" role="status" aria-live="polite">
                            <i class="bi bi-hourglass-split" aria-hidden="true"></i>
                            <div>
                                <span>Status kelulusan</span>
                                <strong id="resultQualificationTitle">Memeriksa syarat kelulusan...</strong>
                                <p id="resultQualificationText">Status akan ditampilkan setelah hasil tersimpan.</p>
                            </div>
                        </div>
                        <div class="cat-result-category-scores" id="resultCategoryScores" hidden></div>

                        <div class="cat-result-modal-actions">
                            <button type="button" class="cat-action-btn" id="reviewButton" data-bs-dismiss="modal">
                                <i class="bi bi-search"></i>
                                Review Jawaban
                            </button>
                            <button type="button" class="cat-action-btn cat-action-secondary" id="wrongReviewButton" data-bs-dismiss="modal">
                                <i class="bi bi-exclamation-circle"></i>
                                Review Salah
                            </button>

                            <a href="{{ route('tryout.index', array_filter(['mode' => 'ujian', 'latihan' => $practiceCategory])) }}" class="cat-action-btn cat-action-secondary">
                                <i class="bi bi-arrow-repeat"></i>
                                Kerjakan Lagi
                            </a>
                            <a href="{{ route('tryout.index', ['tab' => 'simulasi']) }}" class="cat-action-btn cat-action-secondary">
                                <i class="bi bi-house"></i> Beranda Tryout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const rawSoals = @json($soals->values());
                const answers = {};
                const marked = {};
                let soals = [];
                let currentIndex = 0;
                let isReview = false;
                let hasStarted = false;
                let deadline = null;
                let remainingSeconds = Math.max(Number(@json((int) ($examDurationMinutes ?? ($tryoutPengaturan->durasi_menit ?? 45)))) * 60, 60);
                let initialSeconds = remainingSeconds;
                let startedAt = null;
                const shouldShuffleQuestions = @json((bool) $tryoutPengaturan->acak_soal);
                const shouldShuffleAnswers = @json((bool) $tryoutPengaturan->acak_jawaban);
                const draftKey = 'tryout-cpns-draft:v1:' + @json((string) ($peserta->id ?? $peserta->username)) + ':' + @json($practiceCategory ?: 'FULL');

                const timerEl = document.getElementById('catTimer');
                const navEl = document.getElementById('questionNav');
                const answeredCountEl = document.getElementById('answeredCount');
                const markedCountEl = document.getElementById('markedCount');
                const questionCategoryEl = document.getElementById('questionCategory');
                const questionTitleEl = document.getElementById('questionTitle');
                const questionTextEl = document.getElementById('questionText');
                const questionOptionsEl = document.getElementById('questionOptions');
                const markButton = document.getElementById('markButton');
                const prevButton = document.getElementById('prevButton');
                const nextButton = document.getElementById('nextButton');
                const finishButton = document.getElementById('finishButton');
                const showResultButton = document.getElementById('showResultButton');
                const resultScore = document.getElementById('resultScore');
                const resultMeta = document.getElementById('resultMeta');
                const resultSaveStatus = document.getElementById('resultSaveStatus');
                const resultQualification = document.getElementById('resultQualification');
                const resultQualificationTitle = document.getElementById('resultQualificationTitle');
                const resultQualificationText = document.getElementById('resultQualificationText');
                const resultCategoryScores = document.getElementById('resultCategoryScores');
                const reviewButton = document.getElementById('reviewButton');
                const wrongReviewButton = document.getElementById('wrongReviewButton');
                const answerSaveStatus = document.getElementById('answerSaveStatus');
                const connectionStatus = document.getElementById('connectionStatus');
                const connectionStatusText = document.getElementById('connectionStatusText');
                const draftStatus = document.getElementById('draftStatus');
                const draftStatusText = document.getElementById('draftStatusText');
                const resultModal = new bootstrap.Modal(document.getElementById('catResultModal'));
                const finishModalEl = document.getElementById('catFinishModal');
                const finishModal = new bootstrap.Modal(finishModalEl);
                let pendingResultModal = false;
                let connectionCheckInFlight = false;

                function setConnectionQuality(quality, message) {
                    connectionStatus.classList.remove('is-checking', 'signal-good', 'signal-warning', 'signal-poor');
                    connectionStatus.classList.add(`signal-${quality}`);
                    connectionStatusText.textContent = message;
                }

                async function updateConnectionStatus() {
                    if (connectionCheckInFlight) return;

                    if (!navigator.onLine) {
                        setConnectionQuality('poor', 'Sinyal jelek');
                        return;
                    }

                    connectionCheckInFlight = true;
                    const startedAt = performance.now();

                    try {
                        const response = await fetch(window.location.href, {
                            method: 'HEAD',
                            cache: 'no-store',
                            headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        });

                        if (!response.ok) throw new Error('Koneksi ke server gagal.');

                        const latency = performance.now() - startedAt;
                        if (latency < 450) {
                            setConnectionQuality('good', 'Sinyal bagus');
                        } else if (latency < 1400) {
                            setConnectionQuality('warning', 'Sinyal kurang bagus');
                        } else {
                            setConnectionQuality('poor', 'Sinyal jelek');
                        }
                    } catch (error) {
                        setConnectionQuality('poor', 'Sinyal jelek');
                    } finally {
                        connectionCheckInFlight = false;
                    }
                }

                function updateDraftStatus(state, message) {
                    draftStatus.classList.remove('is-saved', 'is-error');
                    if (state === 'saved') draftStatus.classList.add('is-saved');
                    if (state === 'error') draftStatus.classList.add('is-error');
                    draftStatusText.textContent = state === 'saved'
                        ? 'Tersimpan'
                        : state === 'error' ? 'Simpan gagal' : 'Belum disimpan';
                    draftStatus.title = message;
                }

                function savedAtLabel() {
                    return new Intl.DateTimeFormat('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                    }).format(new Date());
                }

                function saveDraft() {
                    if (!hasStarted || isReview) return false;
                    try {
                        localStorage.setItem(draftKey, JSON.stringify({
                            soals, answers, marked, currentIndex, deadline, initialSeconds, startedAt,
                        }));
                        updateDraftStatus('saved', `Tersimpan di perangkat · ${savedAtLabel()}`);
                        return true;
                    } catch (error) {
                        answerSaveStatus.textContent = 'Penyimpanan perangkat tidak tersedia. Tetap di halaman ini dan gunakan tombol Selanjutnya; jawaban masih tersimpan selama halaman terbuka.';
                        answerSaveStatus.classList.add('text-danger');
                        updateDraftStatus('error', 'Progres belum dapat disimpan');
                        return false;
                    }
                }

                function restoreDraft() {
                    try {
                        const draft = JSON.parse(localStorage.getItem(draftKey));
                        if (!draft || !Array.isArray(draft.soals) || !draft.soals.length ||
                            !Number.isFinite(draft.deadline) || !Number.isFinite(draft.initialSeconds) ||
                            draft.initialSeconds <= 0 || !Number.isFinite(Date.parse(draft.startedAt)) ||
                            !draft.answers || !draft.marked ||
                            !draft.soals.every(soal => soal.id && soal.opsi && soal.skor && Array.isArray(soal.displayOptions) && soal.displayOptions.length)) return false;

                        soals = draft.soals;
                        Object.assign(answers, draft.answers);
                        Object.assign(marked, draft.marked);
                        currentIndex = Math.min(Math.max(Number(draft.currentIndex) || 0, 0), soals.length - 1);
                        deadline = draft.deadline;
                        initialSeconds = draft.initialSeconds;
                        startedAt = draft.startedAt;
                        remainingSeconds = Math.max(0, Math.ceil((deadline - Date.now()) / 1000));
                        hasStarted = true;
                        showExam();
                        document.getElementById('examProgress').max = soals.length;
                        renderQuestion();
                        timerEl.textContent = formatTime(remainingSeconds);
                        answerSaveStatus.textContent = 'Progres terakhir dipulihkan. Silakan lanjutkan ujian.';
                        updateDraftStatus('saved', 'Progres terakhir dipulihkan');
                        if (remainingSeconds === 0) finishTryout();
                        return true;
                    } catch (error) {
                        return false;
                    }
                }

                function showExam() {
                    document.getElementById('examPreparation').classList.add('d-none');
                    document.getElementById('examShell').classList.remove('d-none');
                    document.getElementById('examSessionStatus').textContent = 'Ujian berlangsung';
                }

                finishModalEl.addEventListener('hidden.bs.modal', function() {
                    if (pendingResultModal) {
                        pendingResultModal = false;
                        resultModal.show();
                    }
                });

                function formatTime(totalSeconds) {
                    const hours = String(Math.floor(totalSeconds / 3600)).padStart(2, '0');
                    const minutes = String(Math.floor((totalSeconds % 3600) / 60)).padStart(2, '0');
                    const seconds = String(totalSeconds % 60).padStart(2, '0');
                    return `${hours}:${minutes}:${seconds}`;
                }

                function shuffleArray(items) {
                    const shuffled = [...items];

                    for (let index = shuffled.length - 1; index > 0; index--) {
                        const randomIndex = Math.floor(Math.random() * (index + 1));
                        [shuffled[index], shuffled[randomIndex]] = [shuffled[randomIndex], shuffled[index]];
                    }

                    return shuffled;
                }

                function buildDisplayOptions(soal) {
                    const options = Object.entries(soal.opsi).map(function([originalKey, text]) {
                        return {
                            originalKey,
                            text
                        };
                    });

                    const orderedOptions = shouldShuffleAnswers ? shuffleArray(options) : options;

                    return orderedOptions.map(function(option, index) {
                        return {
                            originalKey: option.originalKey,
                            label: String.fromCharCode(65 + index),
                            text: option.text
                        };
                    });
                }

                function buildSoals() {
                    const orderedSoals = shouldShuffleQuestions ? shuffleArray(rawSoals) : [...rawSoals];

                    soals = orderedSoals.map(function(soal) {
                        return {
                            ...soal,
                            displayOptions: buildDisplayOptions(soal)
                        };
                    });

                    if (currentIndex >= soals.length) {
                        currentIndex = 0;
                    }

                    renderQuestion();
                }

                function optionScore(soal, option) {
                    const score = Number(soal.skor[option] || 0);

                    if (score > 0) {
                        return score;
                    }

                    return soal.jawaban_benar === option ? 5 : 0;
                }

                function correctDisplayLabel(soal) {
                    const correctOption = soal.displayOptions.find(function(option) {
                        return option.originalKey === soal.jawaban_benar;
                    });

                    return correctOption ? correctOption.label : soal.jawaban_benar;
                }

                function discussionText(soal) {
                    const explanation = String(soal.pembahasan || '').replace(/\s*Jawaban\s*:\s*[A-E]\.?\s*$/i, '').trim();
                    const answerLabel = correctDisplayLabel(soal);

                    return answerLabel ? `${explanation} Jawaban benar: ${answerLabel}.`.trim() : explanation;
                }

                function updateSummary() {
                    answeredCountEl.textContent = Object.keys(answers).length;
                    markedCountEl.textContent = Object.keys(marked).length;
                    document.getElementById('examProgress').value = Object.keys(answers).length;
                    document.getElementById('examProgressLabel').textContent = `${Object.keys(answers).length} dari ${soals.length} soal dijawab`;
                }

                function renderNav() {
                    navEl.innerHTML = '';

                    soals.forEach(function(_, index) {
                        const soal = soals[index];
                        const numberButton = document.createElement('button');
                        numberButton.type = 'button';
                        numberButton.textContent = index + 1;
                        numberButton.className = 'cat-number-btn';
                        numberButton.setAttribute('aria-label', `Soal ${index + 1}, ${answers[soal.id] ? 'terjawab' : 'belum dijawab'}${marked[soal.id] ? ', ragu-ragu' : ''}`);
                        if (index === currentIndex) numberButton.setAttribute('aria-current', 'step');

                        if (index === currentIndex) numberButton.classList.add('active');
                        if (answers[soal.id]) numberButton.classList.add('answered');
                        if (marked[soal.id]) numberButton.classList.add('marked');

                        numberButton.addEventListener('click', function() {
                            currentIndex = index;
                            renderQuestion();
                            saveDraft();
                            questionTitleEl.focus({ preventScroll: true });
                        });

                        navEl.appendChild(numberButton);
                    });
                }

                function renderQuestion() {
                    const soal = soals[currentIndex];
                    const selectedAnswer = answers[soal.id];
                    questionCategoryEl.textContent = soal.kategori;
                    questionTitleEl.textContent = `Soal ${currentIndex + 1} dari ${soals.length}`;
                    questionTextEl.textContent = soal.pertanyaan;
                    questionOptionsEl.innerHTML = '';

                    soal.displayOptions.forEach(function(option) {
                        const optionButton = document.createElement('button');
                        optionButton.type = 'button';
                        optionButton.className = 'cat-option-btn';
                        optionButton.setAttribute('aria-pressed', String(selectedAnswer === option.originalKey));
                        optionButton.disabled = isReview;

                        if (selectedAnswer === option.originalKey) optionButton.classList.add('selected');
                        if (isReview && soal.jawaban_benar === option.originalKey) optionButton.classList.add('correct');
                        if (isReview && selectedAnswer === option.originalKey && soal.jawaban_benar && soal.jawaban_benar !== option.originalKey) {
                            optionButton.classList.add('wrong');
                        }

                        const badge = document.createElement('span');
                        badge.textContent = option.label;

                        const copy = document.createElement('strong');
                        copy.textContent = option.text;

                        optionButton.appendChild(badge);
                        optionButton.appendChild(copy);

                        if (!isReview) {
                            optionButton.addEventListener('click', function() {
                                if (Date.now() >= deadline) { finishTryout(); return; }
                                answers[soal.id] = option.originalKey;
                                const answeredIndex = currentIndex;
                                if (currentIndex < soals.length - 1) currentIndex += 1;
                                if (saveDraft()) {
                                    answerSaveStatus.classList.remove('text-danger');
                                    answerSaveStatus.textContent = answeredIndex === soals.length - 1
                                        ? 'Jawaban terakhir tersimpan di perangkat. Periksa kembali atau klik Selesaikan Ujian.'
                                        : `Jawaban soal ${answeredIndex + 1} tersimpan di perangkat. Lanjut ke soal ${currentIndex + 1}.`;
                                } else {
                                    currentIndex = answeredIndex;
                                }
                                renderQuestion();
                                questionTitleEl.focus({ preventScroll: true });
                                document.querySelector('.cat-main:not(.cat-preparation)').scrollIntoView({ behavior: 'smooth', block: 'start' });
                            });
                        }

                        questionOptionsEl.appendChild(optionButton);
                    });

                    if (isReview && soal.pembahasan) {
                        const discussion = document.createElement('div');
                        discussion.className = 'cat-discussion';
                        discussion.textContent = discussionText(soal);
                        questionOptionsEl.appendChild(discussion);
                    }

                    markButton.classList.toggle('active', Boolean(marked[soal.id]));
                    markButton.setAttribute('aria-pressed', String(Boolean(marked[soal.id])));
                    markButton.disabled = isReview;
                    prevButton.disabled = currentIndex === 0;
                    nextButton.disabled = currentIndex === soals.length - 1;

                    renderNav();
                    updateSummary();
                }

                function buildResultPayload(totalScore, correctCount) {
                    return {
                        answers,
                        marked,
                        question_ids: soals.map(function(soal) {
                            return soal.id;
                        }),
                        durasi_detik: initialSeconds - remainingSeconds,
                        started_at: startedAt,
                        total_skor_browser: totalScore,
                        total_benar_browser: correctCount,
                    };
                }

                function saveResult(payload) {
                    resultSaveStatus.textContent = 'Menyimpan riwayat...';
                    setQualificationPending();

                    fetch(@json(route('tryout.riwayat.store', [], false)), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify(payload),
                    })
                        .then(function(response) {
                            if (!response.ok) {
                                throw new Error('Gagal menyimpan riwayat.');
                            }

                            return response.json();
                        })
                        .then(function(data) {
                            resultScore.textContent = data.total_skor;
                            resultMeta.textContent = `${data.total_dijawab} dari ${data.total_soal} soal dijawab. Jawaban benar: ${data.total_benar}. Ragu-ragu: ${data.total_ragu}.`;
                            resultSaveStatus.textContent = 'Hasil tersimpan. Riwayat dapat dilihat di Beranda Tryout.';
                            renderQualification(data.hasil_kelulusan);
                            updateDraftStatus('saved', 'Hasil tersimpan ke riwayat');
                        })
                        .catch(function() {
                            resultSaveStatus.textContent = 'Riwayat belum tersimpan. Silakan hubungi admin jika diperlukan.';
                            resultQualification.className = 'cat-result-qualification is-pending';
                            resultQualification.querySelector('i').className = 'bi bi-exclamation-triangle';
                            resultQualificationTitle.textContent = 'Status kelulusan belum dapat diverifikasi';
                            resultQualificationText.textContent = 'Sambungkan internet lalu hubungi admin bila hasil belum tersimpan.';
                            resultCategoryScores.hidden = true;
                            resultCategoryScores.innerHTML = '';
                            updateDraftStatus('error', 'Hasil belum tersimpan ke riwayat');
                        });
                }

                function setQualificationPending() {
                    resultQualification.className = 'cat-result-qualification is-pending';
                    resultQualification.querySelector('i').className = 'bi bi-hourglass-split';
                    resultQualificationTitle.textContent = 'Memeriksa syarat kelulusan...';
                    resultQualificationText.textContent = 'Status akan ditampilkan setelah hasil tersimpan.';
                    resultCategoryScores.hidden = true;
                    resultCategoryScores.innerHTML = '';
                }

                function renderQualification(qualification) {
                    if (!qualification || !qualification.kategori) {
                        return;
                    }

                    const passed = Boolean(qualification.lulus);
                    const failedCategories = Array.isArray(qualification.kategori_gagal)
                        ? qualification.kategori_gagal : [];
                    resultQualification.className = `cat-result-qualification ${passed ? 'is-passed' : 'is-failed'}`;
                    resultQualification.querySelector('i').className = passed
                        ? 'bi bi-patch-check-fill' : 'bi bi-x-circle-fill';
                    resultQualificationTitle.textContent = passed
                        ? 'Selamat, Anda lulus!'
                        : 'Mohon maaf, skor belum memenuhi syarat';
                    resultQualificationText.textContent = passed
                        ? 'Semua kategori yang diujikan telah mencapai skor minimal.'
                        : `Perbaiki skor ${failedCategories.join(' dan ')} agar mencapai batas minimal.`;

                    resultCategoryScores.innerHTML = Object.entries(qualification.kategori)
                        .map(function([kode, result]) {
                            const passedClass = result.lulus ? 'is-passed' : 'is-failed';
                            const status = result.lulus ? 'Memenuhi syarat' : 'Belum memenuhi';
                            return `<article class="cat-result-category-score ${passedClass}">
                                <span>${kode}</span>
                                <strong>${result.skor}</strong>
                                <small>Minimal ${result.minimal} · ${status}</small>
                            </article>`;
                        }).join('');
                    resultCategoryScores.hidden = false;
                }

                function finishTryout() {
                    if (!hasStarted || isReview) return;
                    remainingSeconds = Math.max(0, Math.ceil((deadline - Date.now()) / 1000));
                    let totalScore = 0;
                    let correctCount = 0;

                    soals.forEach(function(soal) {
                        const answer = answers[soal.id];

                        if (!answer) return;

                        totalScore += optionScore(soal, answer);

                        if (soal.jawaban_benar && soal.jawaban_benar === answer) {
                            correctCount += 1;
                        }
                    });

                    resultScore.textContent = totalScore;
                    resultMeta.textContent = `${Object.keys(answers).length} dari ${soals.length} soal dijawab. Jawaban benar: ${correctCount}. Ragu-ragu: ${Object.keys(marked).length}.`;
                    finishButton.disabled = true;
                    finishButton.classList.add('d-none');
                    showResultButton.classList.remove('d-none');
                    isReview = true;
                    try { localStorage.removeItem(draftKey); } catch (error) { /* Storage may be unavailable. */ }
                    answerSaveStatus.textContent = 'Ujian selesai. Kamu dapat meninjau jawaban dan pembahasan.';
                    answerSaveStatus.classList.remove('text-danger');
                    updateDraftStatus('saved', 'Ujian selesai · menyiapkan hasil');
                    renderQuestion();
                    timerEl.textContent = formatTime(remainingSeconds);
                    document.getElementById('examSessionStatus').textContent = 'Review jawaban';
                    if (finishModalEl.classList.contains('show')) {
                        pendingResultModal = true;
                        finishModal.hide();
                    } else {
                        resultModal.show();
                    }
                    saveResult(buildResultPayload(totalScore, correctCount));
                }

                setInterval(function() {
                    if (!hasStarted || isReview) return;
                    remainingSeconds = Math.max(0, Math.ceil((deadline - Date.now()) / 1000));
                    timerEl.textContent = formatTime(remainingSeconds);

                    if (remainingSeconds === 0) {
                        finishTryout();
                    }
                }, 1000);

                timerEl.textContent = formatTime(remainingSeconds);

                document.getElementById('startExamButton').addEventListener('click', function() {
                    if (hasStarted) return;
                    hasStarted = true;
                    startedAt = new Date().toISOString();
                    deadline = Date.now() + initialSeconds * 1000;
                    showExam();
                    saveDraft();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    questionTitleEl.focus({ preventScroll: true });
                });

                window.addEventListener('beforeunload', function(event) {
                    if (hasStarted && !isReview && !saveDraft()) {
                        event.preventDefault();
                        event.returnValue = '';
                    }
                });

                markButton.addEventListener('click', function() {
                    const soal = soals[currentIndex];

                    if (marked[soal.id]) {
                        delete marked[soal.id];
                    } else {
                        marked[soal.id] = true;
                    }

                    renderQuestion();
                    saveDraft();
                });

                prevButton.addEventListener('click', function() {
                    if (currentIndex > 0) {
                        currentIndex -= 1;
                        renderQuestion();
                        saveDraft();
                    }
                });

                nextButton.addEventListener('click', function() {
                    if (currentIndex < soals.length - 1) {
                        currentIndex += 1;
                        renderQuestion();
                        saveDraft();
                    }
                });

                finishButton.addEventListener('click', function() {
                    const unanswered = soals.length - Object.keys(answers).length;
                    document.getElementById('finishSummary').textContent = `${Object.keys(answers).length} dari ${soals.length} soal sudah dijawab. ${unanswered} belum dijawab dan ${Object.keys(marked).length} ditandai ragu-ragu.`;
                    finishModal.show();
                });
                document.getElementById('confirmFinishButton').addEventListener('click', finishTryout);

                showResultButton.addEventListener('click', function() {
                    resultModal.show();
                });

                reviewButton.addEventListener('click', function() {
                    currentIndex = 0;
                    renderQuestion();
                    document.querySelector('.cat-shell').scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                });

                window.addEventListener('online', updateConnectionStatus);
                window.addEventListener('offline', updateConnectionStatus);
                updateConnectionStatus();
                setInterval(updateConnectionStatus, 30000);

                wrongReviewButton.addEventListener('click', function() {
                    const firstWrongIndex = soals.findIndex(function(soal) {
                        return answers[soal.id] && soal.jawaban_benar && answers[soal.id] !== soal.jawaban_benar;
                    });
                    currentIndex = firstWrongIndex >= 0 ? firstWrongIndex : 0;
                    renderQuestion();
                    answerSaveStatus.textContent = firstWrongIndex >= 0
                        ? 'Menampilkan jawaban salah pertama. Gunakan tombol Selanjutnya untuk meninjau soal lain.'
                        : 'Tidak ada jawaban salah yang ditemukan. Kamu bisa tetap meninjau seluruh jawaban.';
                    document.querySelector('.cat-shell').scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                });

                if (!restoreDraft()) buildSoals();
            });
        </script>
    @endif
@endsection
