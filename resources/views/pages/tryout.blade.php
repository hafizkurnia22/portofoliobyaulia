@extends('layouts.app')

@section('title', 'Tryout CAT CPNS')

@section('content')
    @php
        $tryoutMode = request('mode', 'menu');
        $tryoutMode = in_array($tryoutMode, ['menu', 'ujian', 'materi'], true) ? $tryoutMode : 'menu';
        $showExam = $tryoutMode === 'ujian';
        $showMateri = $tryoutMode === 'materi';
        $materiKategori = ['TWK', 'TIU', 'TKP'];
        $allMateri = isset($materiTryout)
            ? $materiTryout->flatten(1)->sortBy(fn ($materi) => optional($materi->kategoriSoal)->kode . $materi->judul)->values()
            : collect();
        $kategoriMateriOptions = $allMateri->map(fn ($materi) => optional($materi->kategoriSoal)->kode ?? 'LAIN')->unique()->values();
        $hasMateri = $allMateri->isNotEmpty();
    @endphp

    <section class="tryout-page">
        <div class="container">
            <div class="tryout-header" data-aos="fade-down">
                <span class="section-label">Tryout</span>
                <h1>Simulasi CAT CPNS</h1>
                <p>Latihan soal TWK, TIU, dan TKP dengan tampilan ujian berbasis komputer.</p>
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

            @if ($tryoutMode === 'menu')
                <div class="tryout-choice-grid" data-aos="fade-up">
                    <a href="{{ route('tryout.index', ['mode' => 'ujian']) }}" class="tryout-choice-card">
                        <span><i class="bi bi-display"></i></span>
                        <strong>Mulai Ujian</strong>
                        <small>Masuk ke simulasi CAT CPNS dan kerjakan soal aktif yang sudah disiapkan admin.</small>
                    </a>

                    <a href="{{ route('tryout.index', ['mode' => 'materi']) }}" class="tryout-choice-card">
                        <span><i class="bi bi-journal-bookmark"></i></span>
                        <strong>Materi Ujian</strong>
                        <small>Baca pembahasan pembelajaran untuk TIU, TWK, dan TKP sebelum mulai latihan.</small>
                    </a>
                </div>
            @else
                <div class="tryout-mode-actions" data-aos="fade-up">
                    <a href="{{ route('tryout.index') }}" class="cat-action-btn cat-action-secondary">
                        <i class="bi bi-grid"></i>
                        Pilihan Tryout
                    </a>

                    @if ($showExam)
                        <a href="{{ route('tryout.index', ['mode' => 'materi']) }}" class="cat-action-btn">
                            <i class="bi bi-journal-bookmark"></i>
                            Materi Ujian
                        </a>
                    @else
                        <a href="{{ route('tryout.index', ['mode' => 'ujian']) }}" class="cat-action-btn">
                            <i class="bi bi-display"></i>
                            Mulai Ujian
                        </a>
                    @endif
                </div>
            @endif

            @if (!$showMateri)
                <div class="tryout-history-card" id="tryoutHistoryCard" data-aos="fade-up">
                    <div class="tryout-history-header">
                        <div>
                            <span>Riwayat Saya</span>
                            <h2>Riwayat mengikuti tryout</h2>
                        </div>
                    </div>

                    <div class="tryout-history-list" id="tryoutHistoryList">
                        @forelse ($riwayatTryout as $riwayat)
                            <div class="tryout-history-item">
                                <div>
                                    <strong>{{ $riwayat->finished_at ? $riwayat->finished_at->format('d M Y H:i') : '-' }}</strong>
                                    <small>{{ $riwayat->total_dijawab }}/{{ $riwayat->total_soal }} dijawab, {{ $riwayat->total_benar }} benar</small>
                                </div>

                                <span>
                                    <small>Skor</small>
                                    {{ $riwayat->total_skor }}
                                </span>
                            </div>
                        @empty
                            <div class="tryout-history-empty" id="tryoutHistoryEmpty">
                                Belum ada riwayat tryout.
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif

            @if ($riwayatTryout->isNotEmpty() && !$showMateri)
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const historyList = document.getElementById('tryoutHistoryList');

                        if (!historyList) {
                            return;
                        }

                        historyList.dataset.hasInitialHistory = '1';
                    });
                </script>
            @endif

            @if ($showMateri)
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
                                                    <span class="tryout-materi-code">
                                                        {{ $kodeKategori }}
                                                    </span>
                                                    <small>{{ $namaKategori }}</small>
                                                </td>
                                                <td>
                                                    <strong>{{ $materi->judul }}</strong>
                                                </td>
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
                            <p>Silakan input materi dari menu Admin Tryout > Master Materi.</p>
                        </div>
                    @endif
                </div>
            @endif

            @if ($showMateri && $hasMateri)
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
                        <p>Silakan input soal dari menu Admin Tryout > Master Soal.</p>
                    </div>
                @else
                    <div class="cat-shell" data-aos="fade-up">
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

                            <div class="cat-number-grid" id="questionNav"></div>
                        </aside>

                        <div class="cat-main">
                            <div class="cat-question-top">
                                <div>
                                    <span class="cat-kategori" id="questionCategory">TWK</span>
                                    <h2 id="questionTitle">Soal 1</h2>
                                </div>

                                <button type="button" class="cat-mark-btn" id="markButton">
                                    <i class="bi bi-bookmark"></i>
                                    Ragu-ragu
                                </button>
                            </div>

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
                                    Selesai
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
        <div class="modal fade cat-result-modal" id="catResultModal" tabindex="-1" aria-hidden="true"
            data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="cat-result-modal-body">
                        <span class="cat-result-label">Hasil Tryout</span>
                        <strong class="cat-result-score-label">Skor</strong>
                        <h2 id="resultScore">0</h2>
                        <p id="resultMeta"></p>
                        <small id="resultSaveStatus">Menyimpan riwayat...</small>

                        <div class="cat-result-modal-actions">
                            <button type="button" class="cat-action-btn" id="reviewButton" data-bs-dismiss="modal">
                                <i class="bi bi-search"></i>
                                Review Jawaban
                            </button>

                            <a href="{{ route('tryout.index', ['mode' => 'ujian']) }}" class="cat-action-btn cat-action-secondary">
                                <i class="bi bi-arrow-repeat"></i>
                                Kerjakan Lagi
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
                let remainingSeconds = Math.max(Number(@json((int) ($tryoutPengaturan->durasi_menit ?? 45))) * 60, 60);
                const initialSeconds = remainingSeconds;
                const startedAt = new Date().toISOString();
                const shouldShuffleQuestions = @json((bool) $tryoutPengaturan->acak_soal);
                const shouldShuffleAnswers = @json((bool) $tryoutPengaturan->acak_jawaban);

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
                const historyList = document.getElementById('tryoutHistoryList');
                const historyEmpty = document.getElementById('tryoutHistoryEmpty');
                const resultScore = document.getElementById('resultScore');
                const resultMeta = document.getElementById('resultMeta');
                const resultSaveStatus = document.getElementById('resultSaveStatus');
                const reviewButton = document.getElementById('reviewButton');
                const resultModal = new bootstrap.Modal(document.getElementById('catResultModal'));

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

                function updateSummary() {
                    answeredCountEl.textContent = Object.keys(answers).length;
                    markedCountEl.textContent = Object.keys(marked).length;
                }

                function renderNav() {
                    navEl.innerHTML = '';

                    soals.forEach(function(_, index) {
                        const soal = soals[index];
                        const numberButton = document.createElement('button');
                        numberButton.type = 'button';
                        numberButton.textContent = index + 1;
                        numberButton.className = 'cat-number-btn';

                        if (index === currentIndex) numberButton.classList.add('active');
                        if (answers[soal.id]) numberButton.classList.add('answered');
                        if (marked[soal.id]) numberButton.classList.add('marked');

                        numberButton.addEventListener('click', function() {
                            currentIndex = index;
                            renderQuestion();
                        });

                        navEl.appendChild(numberButton);
                    });
                }

                function renderQuestion() {
                    const soal = soals[currentIndex];
                    const selectedAnswer = answers[soal.id];
                    questionCategoryEl.textContent = soal.kategori;
                    questionTitleEl.textContent = `Soal ${currentIndex + 1}`;
                    questionTextEl.textContent = soal.pertanyaan;
                    questionOptionsEl.innerHTML = '';

                    soal.displayOptions.forEach(function(option) {
                        const optionButton = document.createElement('button');
                        optionButton.type = 'button';
                        optionButton.className = 'cat-option-btn';

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
                                answers[soal.id] = option.originalKey;
                                renderQuestion();
                            });
                        }

                        questionOptionsEl.appendChild(optionButton);
                    });

                    if (isReview && soal.pembahasan) {
                        const discussion = document.createElement('div');
                        discussion.className = 'cat-discussion';
                        discussion.textContent = soal.pembahasan;
                        questionOptionsEl.appendChild(discussion);
                    }

                    markButton.classList.toggle('active', Boolean(marked[soal.id]));
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

                function prependHistory(data) {
                    if (!historyList) {
                        return;
                    }

                    if (historyEmpty) {
                        historyEmpty.remove();
                    }

                    const item = document.createElement('div');
                    item.className = 'tryout-history-item';

                    const meta = document.createElement('div');
                    const finishedAt = document.createElement('strong');
                    finishedAt.textContent = data.finished_at || 'Baru saja';

                    const detail = document.createElement('small');
                    detail.textContent = `${data.total_dijawab}/${data.total_soal} dijawab, ${data.total_benar} benar`;

                    const score = document.createElement('span');
                    const scoreLabel = document.createElement('small');
                    scoreLabel.textContent = 'Skor';
                    score.appendChild(scoreLabel);
                    score.appendChild(document.createTextNode(data.total_skor));

                    meta.appendChild(finishedAt);
                    meta.appendChild(detail);
                    item.appendChild(meta);
                    item.appendChild(score);
                    historyList.prepend(item);

                    while (historyList.querySelectorAll('.tryout-history-item').length > 5) {
                        historyList.querySelector('.tryout-history-item:last-child').remove();
                    }
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
                            resultSaveStatus.textContent = 'Riwayat tryout sudah tersimpan.';
                            prependHistory(data);
                        })
                        .catch(function() {
                            resultSaveStatus.textContent = 'Riwayat belum tersimpan. Silakan hubungi admin jika diperlukan.';
                        });
                }

                function finishTryout() {
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
                    renderQuestion();
                    resultModal.show();
                    saveResult(buildResultPayload(totalScore, correctCount));
                }

                setInterval(function() {
                    if (remainingSeconds <= 0 || isReview) return;
                    remainingSeconds -= 1;
                    timerEl.textContent = formatTime(remainingSeconds);

                    if (remainingSeconds === 0) {
                        finishTryout();
                    }
                }, 1000);

                timerEl.textContent = formatTime(remainingSeconds);

                markButton.addEventListener('click', function() {
                    const soal = soals[currentIndex];

                    if (marked[soal.id]) {
                        delete marked[soal.id];
                    } else {
                        marked[soal.id] = true;
                    }

                    renderQuestion();
                });

                prevButton.addEventListener('click', function() {
                    if (currentIndex > 0) {
                        currentIndex -= 1;
                        renderQuestion();
                    }
                });

                nextButton.addEventListener('click', function() {
                    if (currentIndex < soals.length - 1) {
                        currentIndex += 1;
                        renderQuestion();
                    }
                });

                finishButton.addEventListener('click', finishTryout);

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

                buildSoals();
            });
        </script>
    @endif
@endsection
