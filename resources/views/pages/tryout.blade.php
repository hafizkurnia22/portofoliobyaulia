@extends('layouts.app')

@section('title', 'Tryout CPNS')

@section('content')
    @php
        $tryoutMode = request('mode', 'menu');
        $tryoutMode = $tryoutMode === 'ujian' ? 'ujian' : 'menu';
        $showExam = $tryoutMode === 'ujian';
        $activeTryoutTab = request('tab', 'materi');
        $activeTryoutTab = in_array($activeTryoutTab, ['materi', 'simulasi', 'evaluasi'], true) ? $activeTryoutTab : 'materi';
        $tryoutKisiKisi = $tryoutPengaturan->kisi_kisi_deskripsi
            ?: 'Materi dan simulasi Tryout CPNS disusun berdasarkan kisi-kisi seleksi kompetensi dasar yang berlaku. Admin dapat memperbarui keterangan ini dan mengunggah surat PermenPAN terbaru sebagai acuan belajar peserta.';
        $materiKategori = ['TWK', 'TIU', 'TKP'];
        $allMateri = isset($materiTryout)
            ? $materiTryout->flatten(1)->sortBy(fn ($materi) => optional($materi->kategoriSoal)->kode . $materi->judul)->values()
            : collect();
        $kategoriMateriOptions = $allMateri->map(fn ($materi) => optional($materi->kategoriSoal)->kode ?? 'LAIN')->unique()->values();
        $hasMateri = $allMateri->isNotEmpty();
    @endphp

    <section class="tryout-page">
        <div class="container">
            @if ($showExam)
                <div class="cat-exam-toolbar">
                    <div class="cat-exam-identity">
                        <h1>Tryout CPNS</h1>
                        <span id="examSessionStatus" role="status">Persiapan ujian</span>
                    </div>
                    <div class="cat-exam-tools">
                        <span class="cat-exam-participant"><i class="bi bi-person-check" aria-hidden="true"></i> {{ $peserta->nama ?: $peserta->username }}</span>
                        <a href="{{ route('tryout.index') }}" class="cat-exam-back"><i class="bi bi-arrow-left" aria-hidden="true"></i> Beranda Tryout</a>
                    </div>
                </div>
            @else
            <div class="tryout-header" data-aos="fade-down">
                <span class="section-label">Latihan CAT</span>
                <h1>Tryout CPNS</h1>
                <p>Latihan TWK, TIU, dan TKP. Pelajari materi, ikuti simulasi, dan pantau hasil latihanmu.</p>
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
            @endif

            @if ($tryoutMode === 'menu')
                <div class="tryout-reference-card" data-aos="fade-up">
                    <div class="tryout-reference-icon">
                        <i class="bi bi-patch-check"></i>
                    </div>
                    <div>
                        <span>Acuan Materi & Ujian</span>
                        <h2>Berdasarkan kisi-kisi CPNS yang dapat diperbarui</h2>
                        <p>{{ $tryoutKisiKisi }}</p>
                        <div class="tryout-reference-meta">
                            <span><i class="bi bi-file-earmark-text"></i> {{ $soals->count() }} soal aktif</span>
                            <span><i class="bi bi-clock"></i> {{ $tryoutPengaturan->durasi_menit ?? 45 }} menit</span>
                            <span><i class="bi bi-grid-3x3-gap"></i> TWK · TIU · TKP</span>
                            @if ($tryoutPengaturan->permenpan_file)
                                <a href="{{ asset('storage/' . $tryoutPengaturan->permenpan_file) }}" target="_blank" rel="noopener">
                                    <i class="bi bi-download"></i>
                                    {{ $tryoutPengaturan->permenpan_nama ?: 'Unduh Surat PermenPAN' }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="tryout-tabs" role="tablist" aria-label="Menu Tryout CPNS" data-aos="fade-up">
                    <a href="{{ route('tryout.index', ['tab' => 'materi']) }}" class="tryout-tab {{ $activeTryoutTab === 'materi' ? 'active' : '' }}">
                        <span>1</span>
                        Materi
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

                @if ($activeTryoutTab === 'materi')
                    <div class="tryout-materi-section" data-aos="fade-up">
                        <div class="tryout-materi-heading">
                            <span>Materi Ujian</span>
                            <h2>Pembahasan Pembelajaran CAT CPNS</h2>
                        </div>

                        @if ($hasMateri)
                            <div class="tryout-materi-toolbar">
                                <div class="tryout-materi-search">
                                    <i class="bi bi-search"></i>
                                    <input type="search" id="materiSearchInput" placeholder="Cari judul atau ringkasan materi...">
                                </div>

                                <select id="materiFilterSelect" class="tryout-materi-filter" aria-label="Filter jenis materi">
                                    <option value="">Semua jenis materi</option>
                                    @foreach ($kategoriMateriOptions as $kodeKategori)
                                        <option value="{{ $kodeKategori }}">{{ $kodeKategori }}</option>
                                    @endforeach
                                </select>

                                <select id="materiSortSelect" class="tryout-materi-filter" aria-label="Urutkan materi">
                                    <option value="kategori-asc">Jenis A-Z</option>
                                    <option value="kategori-desc">Jenis Z-A</option>
                                    <option value="judul-asc">Judul A-Z</option>
                                    <option value="judul-desc">Judul Z-A</option>
                                </select>
                            </div>

                            <div class="tryout-materi-table-card">
                                <div class="table-responsive">
                                    <table class="tryout-materi-table" id="materiTable">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Jenis Materi</th>
                                                <th>Judul Materi</th>
                                                <th>Ringkasan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="materiTableBody">
                                            @foreach ($allMateri as $materi)
                                                @php
                                                    $kodeKategori = $materi->kategoriSoal->kode ?? 'LAIN';
                                                    $namaKategori = $materi->kategoriSoal->nama ?? 'Materi Tambahan';
                                                    $ringkasanMateri = $materi->ringkasan ?: $materi->isi_materi;
                                                @endphp
                                                <tr data-category="{{ $kodeKategori }}"
                                                    data-title="{{ \Illuminate\Support\Str::lower($materi->judul) }}"
                                                    data-summary="{{ \Illuminate\Support\Str::lower(strip_tags($ringkasanMateri)) }}">
                                                    <td class="materi-row-number">{{ $loop->iteration }}</td>
                                                    <td>
                                                        <span class="tryout-materi-code">{{ $kodeKategori }}</span>
                                                        <small>{{ $namaKategori }}</small>
                                                    </td>
                                                    <td><strong>{{ $materi->judul }}</strong></td>
                                                    <td>{{ \Illuminate\Support\Str::limit(strip_tags($ringkasanMateri), 120) }}</td>
                                                    <td>
                                                        <a href="{{ route('tryout.materi.show', $materi) }}" class="tryout-detail-btn">
                                                            <i class="bi bi-eye"></i>
                                                            Detail
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tryout-materi-table-empty d-none" id="materiTableEmpty">
                                Materi tidak ditemukan.
                            </div>
                        @else
                            <div class="tryout-empty-state">
                                <i class="bi bi-journal-plus"></i>
                                <h3>Belum ada materi aktif</h3>
                                <p>Materi pembelajaran belum tersedia. Silakan kembali lagi nanti atau coba simulasi.</p>
                            </div>
                        @endif
                    </div>
                @elseif ($activeTryoutTab === 'simulasi')
                    <div class="tryout-simulation-panel" data-aos="fade-up">
                        <div>
                            <span class="tryout-panel-label">Petunjuk Ujian</span>
                            <h2>Ikuti simulasi setelah memahami aturan pengerjaan</h2>
                            <p>Simulasi berjalan seperti ujian CAT. Jawaban tersimpan otomatis dan peserta langsung diarahkan ke soal berikutnya setelah memilih jawaban.</p>
                            <ul class="cat-instructions">
                                <li>Jumlah soal: <strong>{{ $soals->count() }}</strong>; durasi: <strong>{{ $tryoutPengaturan->durasi_menit ?? 45 }} menit</strong>.</li>
                                <li>Kerjakan soal sesuai urutan atau gunakan navigasi nomor soal.</li>
                                <li>Tandai <strong>Ragu-ragu</strong> bila ingin meninjau jawaban sebelum menyelesaikan ujian.</li>
                                <li>Hasil ujian tersimpan dan dapat dievaluasi pada tab <strong>Evaluasi Hasil</strong>.</li>
                            </ul>
                            <div class="cat-actions">
                                <a href="{{ route('tryout.index', ['mode' => 'ujian']) }}" class="cat-action-btn">
                                    <i class="bi bi-play-circle"></i>
                                    Mulai Ujian
                                </a>
                                <a href="{{ route('tryout.index', ['tab' => 'materi']) }}" class="cat-action-btn cat-action-secondary">
                                    <i class="bi bi-journal-bookmark"></i>
                                    Pelajari Materi
                                </a>
                            </div>
                        </div>
                        <div class="tryout-simulation-summary">
                            <span><strong>{{ $soals->count() }}</strong> Soal Aktif</span>
                            <span><strong>{{ $tryoutPengaturan->durasi_menit ?? 45 }}</strong> Menit</span>
                            <span><strong>{{ $soals->pluck('kategori')->unique()->count() }}</strong> Kategori</span>
                        </div>
                    </div>
                @elseif ($activeTryoutTab === 'evaluasi')
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
                                <div class="tryout-history-item">
                                    <div>
                                        <strong>{{ $riwayat->finished_at ? $riwayat->finished_at->format('d M Y H:i') : '-' }}</strong>
                                        <small>{{ $riwayat->total_dijawab }}/{{ $riwayat->total_soal }} dijawab, {{ $riwayat->total_benar }} benar, {{ $riwayat->total_ragu }} ragu</small>
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
                @endif
            @endif

            @if ($tryoutMode === 'menu' && $activeTryoutTab === 'materi' && $hasMateri)
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const searchInput = document.getElementById('materiSearchInput');
                        const filterSelect = document.getElementById('materiFilterSelect');
                        const sortSelect = document.getElementById('materiSortSelect');
                        const tableBody = document.getElementById('materiTableBody');
                        const emptyState = document.getElementById('materiTableEmpty');

                        if (!searchInput || !filterSelect || !sortSelect || !tableBody || !emptyState) {
                            return;
                        }

                        const rows = Array.from(tableBody.querySelectorAll('tr'));

                        function normalize(value) {
                            return String(value || '').toLowerCase().trim();
                        }

                        function sortRows(items) {
                            const sortMode = sortSelect.value;

                            return items.sort(function(first, second) {
                                const firstCategory = first.dataset.category || '';
                                const secondCategory = second.dataset.category || '';
                                const firstTitle = first.dataset.title || '';
                                const secondTitle = second.dataset.title || '';

                                if (sortMode === 'kategori-desc') {
                                    return secondCategory.localeCompare(firstCategory) || secondTitle.localeCompare(firstTitle);
                                }

                                if (sortMode === 'judul-asc') {
                                    return firstTitle.localeCompare(secondTitle);
                                }

                                if (sortMode === 'judul-desc') {
                                    return secondTitle.localeCompare(firstTitle);
                                }

                                return firstCategory.localeCompare(secondCategory) || firstTitle.localeCompare(secondTitle);
                            });
                        }

                        function applyTableControls() {
                            const keyword = normalize(searchInput.value);
                            const category = filterSelect.value;
                            let visibleIndex = 0;

                            sortRows(rows).forEach(function(row) {
                                const matchesCategory = category === '' || row.dataset.category === category;
                                const searchableText = `${row.dataset.category} ${row.dataset.title} ${row.dataset.summary}`;
                                const matchesSearch = keyword === '' || normalize(searchableText).includes(keyword);
                                const isVisible = matchesCategory && matchesSearch;

                                row.classList.toggle('d-none', !isVisible);

                                if (isVisible) {
                                    visibleIndex += 1;
                                    row.querySelector('.materi-row-number').textContent = visibleIndex;
                                }

                                tableBody.appendChild(row);
                            });

                            emptyState.classList.toggle('d-none', visibleIndex > 0);
                        }

                        searchInput.addEventListener('input', applyTableControls);
                        filterSelect.addEventListener('change', applyTableControls);
                        sortSelect.addEventListener('change', applyTableControls);
                        applyTableControls();
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
                        <h2>Siap berlatih?</h2>
                        <p>Luangkan waktu dan pastikan koneksi internetmu stabil.</p>
                        <div class="cat-preparation-stats">
                            <span><i class="bi bi-file-earmark-text"></i> <strong>{{ $soals->count() }} soal</strong></span>
                            <span><i class="bi bi-clock"></i> <strong>{{ $tryoutPengaturan->durasi_menit ?? 45 }} menit</strong></span>
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
                            <div class="cat-timer">
                                <span>Sisa Waktu</span>
                                <strong id="catTimer">00:00:00</strong>
                            </div>

                            <div class="cat-summary-grid">
                                <div>
                                    <strong id="answeredCount">0</strong>
                                    <span>Terjawab</span>
                                </div>
                                <div>
                                    <strong id="markedCount">0</strong>
                                    <span>Ragu</span>
                                </div>
                            </div>

                            <label class="cat-progress-label" for="examProgress" id="examProgressLabel">0 dari {{ $soals->count() }} soal dijawab</label>
                            <progress class="cat-progress" id="examProgress" max="{{ $soals->count() }}" value="0"></progress>
                            <h3 class="cat-nav-title">Navigasi soal</h3>
                            <div class="cat-number-grid" id="questionNav" aria-label="Navigasi soal"></div>
                            <div class="cat-nav-legend">
                                <span><i class="legend-unanswered"></i> Belum dijawab</span>
                                <span><i class="legend-answered"></i> Terjawab</span>
                                <span><i class="legend-marked"></i> Ragu-ragu</span>
                            </div>
                        </aside>

                        <div class="cat-main">
                            <div class="cat-question-top">
                                <div>
                                    <span class="cat-kategori" id="questionCategory">TWK</span>
                                    <h2 id="questionTitle" tabindex="-1">Soal 1</h2>
                                </div>

                                <button type="button" class="cat-mark-btn" id="markButton">
                                    <i class="bi bi-bookmark"></i>
                                    Ragu-ragu
                                </button>
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
                        <span class="cat-result-label" id="resultTitle">Hasil Tryout CPNS</span>
                        <strong class="cat-result-score-label">Skor</strong>
                        <h2 id="resultScore">0</h2>
                        <p id="resultMeta"></p>
                        <small id="resultSaveStatus" role="status">Menyimpan riwayat...</small>

                        <div class="cat-result-modal-actions">
                            <button type="button" class="cat-action-btn" id="reviewButton" data-bs-dismiss="modal">
                                <i class="bi bi-search"></i>
                                Review Jawaban
                            </button>

                            <a href="{{ route('tryout.index', ['mode' => 'ujian']) }}" class="cat-action-btn cat-action-secondary">
                                <i class="bi bi-arrow-repeat"></i>
                                Kerjakan Lagi
                            </a>
                            <a href="{{ route('tryout.index') }}" class="cat-action-btn cat-action-secondary">
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
                let remainingSeconds = Math.max(Number(@json((int) ($tryoutPengaturan->durasi_menit ?? 45))) * 60, 60);
                let initialSeconds = remainingSeconds;
                let startedAt = null;
                const shouldShuffleQuestions = @json((bool) $tryoutPengaturan->acak_soal);
                const shouldShuffleAnswers = @json((bool) $tryoutPengaturan->acak_jawaban);
                const draftKey = 'tryout-cpns-draft:v1:' + @json((string) ($peserta->id ?? $peserta->username));

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
                const reviewButton = document.getElementById('reviewButton');
                const answerSaveStatus = document.getElementById('answerSaveStatus');
                const resultModal = new bootstrap.Modal(document.getElementById('catResultModal'));
                const finishModalEl = document.getElementById('catFinishModal');
                const finishModal = new bootstrap.Modal(finishModalEl);
                let pendingResultModal = false;

                function saveDraft() {
                    if (!hasStarted || isReview) return false;
                    try {
                        localStorage.setItem(draftKey, JSON.stringify({
                            soals, answers, marked, currentIndex, deadline, initialSeconds, startedAt,
                        }));
                        return true;
                    } catch (error) {
                        answerSaveStatus.textContent = 'Penyimpanan perangkat tidak tersedia. Tetap di halaman ini dan gunakan tombol Selanjutnya; jawaban masih tersimpan selama halaman terbuka.';
                        answerSaveStatus.classList.add('text-danger');
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
                        })
                        .catch(function() {
                            resultSaveStatus.textContent = 'Riwayat belum tersimpan. Silakan hubungi admin jika diperlukan.';
                        });
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

                if (!restoreDraft()) buildSoals();
            });
        </script>
    @endif
@endsection
