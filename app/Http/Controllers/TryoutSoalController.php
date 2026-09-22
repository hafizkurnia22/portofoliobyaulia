<?php

namespace App\Http\Controllers;

use App\Models\TryoutKategoriSoal;
use App\Models\TryoutMateri;
use App\Models\TryoutMateriProgress;
use App\Models\TryoutPengaturan;
use App\Models\TryoutPeserta;
use App\Models\TryoutSoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TryoutSoalController extends Controller
{
    public function index()
    {
        $peserta = $this->authenticatedPeserta();

        if (!$peserta) {
            $this->forgetTryoutSession();

            return redirect()->route('tryout.login');
        }

        $tryoutPengaturan = TryoutPengaturan::current();
        $riwayatTryout = $peserta->riwayats()->latest()->limit(5)->get();
        $scoreStatistics = $this->scoreStatistics($peserta);
        $practiceCategory = $this->practiceCategory(request('latihan'));
        $materiTryout = TryoutMateri::aktif()
            ->with('kategoriSoal')
            ->get()
            ->groupBy(function (TryoutMateri $materi) {
                return $materi->kategoriSoal->kode ?? 'Lainnya';
            });
        $latihanKategori = TryoutKategoriSoal::aktif()
            ->withCount([
                'soals as soal_aktif_count' => function ($query) {
                    $query->aktif();
                },
                'materis as materi_aktif_count' => function ($query) {
                    $query->aktif();
                },
            ])
            ->whereIn('kode', ['TWK', 'TIU', 'TKP'])
            ->orderByRaw("CASE kode WHEN 'TWK' THEN 1 WHEN 'TIU' THEN 2 WHEN 'TKP' THEN 3 ELSE 4 END")
            ->get();
        $materiProgress = TryoutMateriProgress::where('tryout_peserta_id', $peserta->id)
            ->get()
            ->keyBy('tryout_materi_id');
        $latestRiwayat = $riwayatTryout->first();
        $recommendedMateri = $this->recommendedMateri($peserta, $latestRiwayat, $materiProgress);
        $wrongReviewItems = $this->wrongReviewItems($latestRiwayat);
        $examDurationMinutes = $this->examDurationMinutes($tryoutPengaturan, $practiceCategory);
        $soals = $this->selectedSoals($tryoutPengaturan, $practiceCategory)
            ->map(function (TryoutSoal $soal) {
                return [
                    'id' => $soal->id,
                    'kode_soal' => $soal->kode_soal,
                    'kategori' => $soal->kategoriSoal->kode ?? $soal->kategori,
                    'nama_kategori' => $soal->kategoriSoal->nama ?? null,
                    'pertanyaan' => $soal->pertanyaan,
                    'opsi' => [
                        'A' => $soal->opsi_a,
                        'B' => $soal->opsi_b,
                        'C' => $soal->opsi_c,
                        'D' => $soal->opsi_d,
                        'E' => $soal->opsi_e,
                    ],
                    'jawaban_benar' => $soal->jawaban_benar,
                    'skor' => [
                        'A' => $soal->skor_a,
                        'B' => $soal->skor_b,
                        'C' => $soal->skor_c,
                        'D' => $soal->skor_d,
                        'E' => $soal->skor_e,
                    ],
                    'pembahasan' => $soal->pembahasan,
                ];
            });

        return view('pages.tryout', compact(
            'soals',
            'tryoutPengaturan',
            'peserta',
            'riwayatTryout',
            'materiTryout',
            'latihanKategori',
            'practiceCategory',
            'examDurationMinutes',
            'materiProgress',
            'scoreStatistics',
            'recommendedMateri',
            'wrongReviewItems'
        ));
    }

    public function materiDetail(TryoutMateri $materi)
    {
        $peserta = $this->authenticatedPeserta();

        if (!$peserta) {
            $this->forgetTryoutSession();

            return redirect()->route('tryout.login');
        }

        if ($materi->status !== 'aktif') {
            abort(404);
        }

        $materi->load('kategoriSoal');
        $progress = TryoutMateriProgress::firstOrCreate([
            'tryout_peserta_id' => $peserta->id,
            'tryout_materi_id' => $materi->id,
        ]);

        if (!$progress->read_at) {
            $progress->update(['read_at' => now()]);
            $progress->refresh();
        }

        $materiLainnya = TryoutMateri::aktif()
            ->with('kategoriSoal')
            ->where('id', '!=', $materi->id)
            ->orderBy('judul')
            ->limit(4)
            ->get();

        return view('pages.tryout-materi-detail', compact('materi', 'materiLainnya', 'peserta', 'progress'));
    }

    public function store(Request $request)
    {
        TryoutSoal::create($this->validatedData($request));

        return redirect('/admin/dashboard')
            ->with('success', 'Soal tryout berhasil ditambahkan')
            ->with('active_tab', 'master-soal');
    }

    public function update(Request $request, $id)
    {
        $soal = TryoutSoal::findOrFail($id);
        $soal->update($this->validatedData($request));

        return redirect('/admin/dashboard')
            ->with('success', 'Soal tryout berhasil diupdate')
            ->with('active_tab', 'master-soal');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls,csv,txt|max:5120',
        ]);

        $spreadsheet = IOFactory::load($request->file('file_excel')->getRealPath());
        $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        $headers = $this->normalizedHeaders(array_shift($rows) ?? []);
        $imported = 0;
        $skipped = [];

        foreach ($rows as $rowNumber => $row) {
            $excelRowNumber = is_numeric($rowNumber) ? (int) $rowNumber : $rowNumber;

            if ($this->isEmptyRow($row)) {
                continue;
            }

            $payload = $this->payloadFromRow($row, $headers);
            $kategori = $this->resolveKategoriSoal($payload['kategori'] ?? '');

            if (!$kategori) {
                $skipped[] = 'Baris ' . $excelRowNumber . ': kategori soal tidak ditemukan.';
                continue;
            }

            $payload['tryout_kategori_soal_id'] = $kategori->id;
            $payload['kategori'] = $kategori->kode;
            $validator = $this->validator($payload);

            if ($validator->fails()) {
                $skipped[] = 'Baris ' . $excelRowNumber . ': ' . $validator->errors()->first();
                continue;
            }

            TryoutSoal::create($this->prepareData($validator->validated()));
            $imported++;
        }

        $message = $imported . ' soal berhasil diimport.';

        return redirect('/admin/dashboard')
            ->with($imported > 0 ? 'success' : 'error', $skipped ? $message . ' ' . implode(' ', array_slice($skipped, 0, 5)) : $message)
            ->with('active_tab', 'master-soal');
    }

    public function template()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Soal');
        $sheet->fromArray([
            [
                'kode_soal',
                'kategori',
                'pertanyaan',
                'opsi_a',
                'opsi_b',
                'opsi_c',
                'opsi_d',
                'opsi_e',
                'jawaban_benar',
                'skor_a',
                'skor_b',
                'skor_c',
                'skor_d',
                'skor_e',
                'pembahasan',
                'status',
            ],
            [
                'TWK-001',
                'TWK',
                'Contoh pertanyaan TWK?',
                'Pilihan A',
                'Pilihan B',
                'Pilihan C',
                'Pilihan D',
                'Pilihan E',
                'A',
                5,
                0,
                0,
                0,
                0,
                'Contoh pembahasan.',
                'aktif',
            ],
            [
                'TIU-001',
                'TIU',
                'Contoh pertanyaan TIU?',
                'Pilihan A',
                'Pilihan B',
                'Pilihan C',
                'Pilihan D',
                'Pilihan E',
                'C',
                '',
                '',
                '',
                '',
                '',
                'Untuk TWK/TIU cukup isi jawaban_benar. Sistem otomatis memberi nilai benar 5 dan salah 0.',
                'aktif',
            ],
            [
                'TKP-001',
                'TKP',
                'Contoh pertanyaan TKP?',
                'Pilihan A',
                'Pilihan B',
                'Pilihan C',
                'Pilihan D',
                'Pilihan E',
                '',
                1,
                2,
                3,
                4,
                5,
                'Skor TKP mengikuti kualitas respons.',
                'aktif',
            ],
        ], null, 'A1');

        foreach (range('A', 'P') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $path = storage_path('app/template-import-soal-tryout.xlsx');
        (new Xlsx($spreadsheet))->save($path);

        return response()->download($path, 'template-import-soal-tryout.xlsx')->deleteFileAfterSend(true);
    }

    public function destroy($id)
    {
        TryoutSoal::findOrFail($id)->delete();

        return redirect('/admin/dashboard')
            ->with('success', 'Soal tryout berhasil dihapus')
            ->with('active_tab', 'master-soal');
    }

    private function validatedData(Request $request): array
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this->prepareData($validator->validated());
    }

    private function rules(): array
    {
        return [
            'kode_soal' => 'nullable|string|max:50',
            'tryout_kategori_soal_id' => 'required|exists:tryout_kategori_soals,id',
            'kategori' => 'nullable|in:TWK,TIU,TKP',
            'pertanyaan' => 'required|string',
            'opsi_a' => 'required|string',
            'opsi_b' => 'required|string',
            'opsi_c' => 'required|string',
            'opsi_d' => 'required|string',
            'opsi_e' => 'required|string',
            'jawaban_benar' => 'nullable|in:A,B,C,D,E',
            'skor_a' => 'nullable|integer|min:0|max:5',
            'skor_b' => 'nullable|integer|min:0|max:5',
            'skor_c' => 'nullable|integer|min:0|max:5',
            'skor_d' => 'nullable|integer|min:0|max:5',
            'skor_e' => 'nullable|integer|min:0|max:5',
            'pembahasan' => 'nullable|string',
            'status' => 'required|in:aktif,draft',
        ];
    }

    private function validator(array $payload)
    {
        $validator = Validator::make($payload, $this->rules());

        $validator->after(function ($validator) use ($payload) {
            $kategori = TryoutKategoriSoal::find($payload['tryout_kategori_soal_id'] ?? null);

            if (!$kategori) {
                return;
            }

            if ($this->isObjectiveCategory($kategori->kode) && empty($payload['jawaban_benar'])) {
                $validator->errors()->add(
                    'jawaban_benar',
                    'Jawaban benar wajib diisi untuk kategori TWK/TIU.'
                );
            }

            if ($kategori->kode === 'TKP') {
                foreach (['skor_a', 'skor_b', 'skor_c', 'skor_d', 'skor_e'] as $scoreKey) {
                    $score = (int) ($payload[$scoreKey] ?? 0);

                    if ($score < 1 || $score > 5) {
                        $validator->errors()->add(
                            $scoreKey,
                            'Skor TKP untuk semua opsi wajib bernilai 1 sampai 5.'
                        );
                    }
                }
            }
        });

        return $validator;
    }

    private function prepareData(array $data): array
    {
        $kategori = TryoutKategoriSoal::find($data['tryout_kategori_soal_id']);

        if ($kategori) {
            $data['kategori'] = $kategori->kode;
        }

        foreach (['a', 'b', 'c', 'd', 'e'] as $option) {
            $scoreKey = 'skor_' . $option;
            $data[$scoreKey] = (int) ($data[$scoreKey] ?? 0);
        }

        if ($kategori && $this->isObjectiveCategory($kategori->kode)) {
            foreach (['a', 'b', 'c', 'd', 'e'] as $option) {
                $scoreKey = 'skor_' . $option;
                $data[$scoreKey] = strtoupper($option) === ($data['jawaban_benar'] ?? '') ? 5 : 0;
            }
        }

        if ($kategori && $kategori->kode === 'TKP') {
            $data['jawaban_benar'] = $this->highestScoreOption($data);
        }

        if (!$kategori && ($data['jawaban_benar'] ?? null) && $this->hasEmptyScores($data)) {
            $scoreKey = 'skor_' . strtolower($data['jawaban_benar']);
            $data[$scoreKey] = 5;
        }

        return $data;
    }

    private function normalizedHeaders(array $headerRow): array
    {
        $headers = [];

        foreach ($headerRow as $column => $value) {
            $headers[$this->normalizeHeader((string) $value)] = $column;
        }

        return $headers;
    }

    private function payloadFromRow(array $row, array $headers): array
    {
        $payload = [];

        foreach ($this->excelFields() as $field => $aliases) {
            $payload[$field] = $this->cellValue($row, $headers, $aliases);
        }

        $payload['jawaban_benar'] = $payload['jawaban_benar'] !== ''
            ? strtoupper($payload['jawaban_benar'])
            : null;
        $payload['status'] = strtolower($payload['status'] ?: 'aktif');

        foreach (['a', 'b', 'c', 'd', 'e'] as $option) {
            $payload['skor_' . $option] = (int) ($payload['skor_' . $option] ?: 0);
        }

        return $payload;
    }

    private function excelFields(): array
    {
        return [
            'kode_soal' => ['kode_soal', 'kode', 'kode soal'],
            'kategori' => ['kategori', 'kategori_soal', 'kategori soal'],
            'pertanyaan' => ['pertanyaan', 'soal', 'isi_soal', 'isi soal'],
            'opsi_a' => ['opsi_a', 'opsi a', 'a'],
            'opsi_b' => ['opsi_b', 'opsi b', 'b'],
            'opsi_c' => ['opsi_c', 'opsi c', 'c'],
            'opsi_d' => ['opsi_d', 'opsi d', 'd'],
            'opsi_e' => ['opsi_e', 'opsi e', 'e'],
            'jawaban_benar' => ['jawaban_benar', 'jawaban benar', 'kunci', 'kunci jawaban', 'jawaban'],
            'skor_a' => ['skor_a', 'skor a'],
            'skor_b' => ['skor_b', 'skor b'],
            'skor_c' => ['skor_c', 'skor c'],
            'skor_d' => ['skor_d', 'skor d'],
            'skor_e' => ['skor_e', 'skor e'],
            'pembahasan' => ['pembahasan', 'penjelasan'],
            'status' => ['status'],
        ];
    }

    private function cellValue(array $row, array $headers, array $aliases): string
    {
        foreach ($aliases as $alias) {
            $normalizedAlias = $this->normalizeHeader($alias);

            if (isset($headers[$normalizedAlias])) {
                return trim((string) ($row[$headers[$normalizedAlias]] ?? ''));
            }
        }

        return '';
    }

    private function normalizeHeader(string $value): string
    {
        return preg_replace('/[^a-z0-9]+/', '_', strtolower(trim($value)));
    }

    private function resolveKategoriSoal(string $value): ?TryoutKategoriSoal
    {
        $value = trim($value);

        if ($value === '') {
            return null;
        }

        if (preg_match('/^(TWK|TIU|TKP)\b/i', $value, $matches)) {
            return TryoutKategoriSoal::where('kode', strtoupper($matches[1]))->first();
        }

        return TryoutKategoriSoal::where('kode', strtoupper($value))
            ->orWhere('nama', 'like', '%' . $value . '%')
            ->first();
    }

    private function isEmptyRow(array $row): bool
    {
        foreach ($row as $value) {
            if (trim((string) $value) !== '') {
                return false;
            }
        }

        return true;
    }

    private function hasEmptyScores(array $data): bool
    {
        foreach (['skor_a', 'skor_b', 'skor_c', 'skor_d', 'skor_e'] as $scoreKey) {
            if (($data[$scoreKey] ?? 0) > 0) {
                return false;
            }
        }

        return true;
    }

    private function isObjectiveCategory(string $categoryCode): bool
    {
        return in_array($categoryCode, ['TWK', 'TIU'], true);
    }

    private function highestScoreOption(array $data): ?string
    {
        $scores = [
            'A' => $data['skor_a'] ?? 0,
            'B' => $data['skor_b'] ?? 0,
            'C' => $data['skor_c'] ?? 0,
            'D' => $data['skor_d'] ?? 0,
            'E' => $data['skor_e'] ?? 0,
        ];

        arsort($scores);

        return array_key_first($scores);
    }

    private function selectedSoals(TryoutPengaturan $pengaturan, ?string $practiceCategory = null)
    {
        $jumlahSoal = $practiceCategory
            ? $pengaturan->jumlahSoalKategori($practiceCategory)
            : max((int) ($pengaturan->jumlah_soal ?? 30), 1);
        $soals = TryoutSoal::aktif()
            ->with('kategoriSoal')
            ->when($practiceCategory, function ($query) use ($practiceCategory) {
                $query->where(function ($categoryQuery) use ($practiceCategory) {
                    $categoryQuery->where('kategori', $practiceCategory)
                        ->orWhereHas('kategoriSoal', function ($relationQuery) use ($practiceCategory) {
                            $relationQuery->where('kode', $practiceCategory);
                        });
                });
            })
            ->latest()
            ->get();

        if (!$pengaturan->acak_soal) {
            return $soals->take($jumlahSoal)->values();
        }

        if ($soals->count() <= $jumlahSoal) {
            return $soals->shuffle()->values();
        }

        if (!$pengaturan->acak_seimbang_kategori) {
            return $soals->shuffle()->take($jumlahSoal)->values();
        }

        return $this->balancedRandomSoals($soals, $jumlahSoal);
    }

    private function practiceCategory(?string $category): ?string
    {
        $category = strtoupper((string) $category);

        return in_array($category, ['TWK', 'TIU', 'TKP'], true) ? $category : null;
    }

    private function examDurationMinutes(TryoutPengaturan $pengaturan, ?string $practiceCategory = null): int
    {
        $configuredMinutes = max((int) ($pengaturan->durasi_menit ?? 45), 1);

        if (!$practiceCategory) {
            return $configuredMinutes;
        }

        return $pengaturan->durasiMenitKategori($practiceCategory);
    }

    private function scoreStatistics(TryoutPeserta $peserta): array
    {
        $tabs = [
            'FULL' => ['label' => 'Simulasi Penuh', 'attempts' => collect(), 'categories' => []],
            'TWK' => ['label' => 'TWK', 'attempts' => collect(), 'categories' => []],
            'TIU' => ['label' => 'TIU', 'attempts' => collect(), 'categories' => []],
            'TKP' => ['label' => 'TKP', 'attempts' => collect(), 'categories' => []],
        ];

        $fullCategoryAttempts = collect([
            'TWK' => collect(),
            'TIU' => collect(),
            'TKP' => collect(),
        ]);

        $peserta->riwayats()
            ->latest('finished_at')
            ->get()
            ->each(function ($riwayat) use (&$tabs, $fullCategoryAttempts) {
                $details = collect($riwayat->detail_jawaban ?? []);
                $categories = $details->pluck('kategori')->filter()->unique()->values();
                $tabKey = $categories->count() === 1 && isset($tabs[$categories->first()])
                    ? $categories->first()
                    : 'FULL';
                $maxScore = max((int) $riwayat->total_soal * 5, 1);

                $tabs[$tabKey]['attempts']->push([
                    'score' => (int) $riwayat->total_skor,
                    'max_score' => $maxScore,
                ]);

                if ($tabKey !== 'FULL') {
                    return;
                }

                foreach (['TWK', 'TIU', 'TKP'] as $category) {
                    $categoryDetails = $details->where('kategori', $category);
                    if ($categoryDetails->isEmpty()) {
                        continue;
                    }

                    $categoryMaxScore = $categoryDetails->count() * 5;
                    $categoryScore = (int) $categoryDetails->sum('skor');
                    $fullCategoryAttempts->get($category)->push([
                        'score' => $categoryScore,
                        'max_score' => $categoryMaxScore,
                    ]);
                }
            });

        foreach ($tabs as $key => $tab) {
            $tabs[$key]['statistics'] = $this->attemptStatistics($tab['attempts']);
        }

        foreach (['TWK', 'TIU', 'TKP'] as $category) {
            $tabs['FULL']['categories'][$category] = $this->attemptStatistics($fullCategoryAttempts->get($category));
        }

        return $tabs;
    }

    private function attemptStatistics($attempts): ?array
    {
        if ($attempts->isEmpty()) {
            return null;
        }

        $best = $attempts->sortByDesc('score')->first();
        $worst = $attempts->sortBy('score')->first();

        return [
            'count' => $attempts->count(),
            'best' => $best,
            'worst' => $worst,
            'average_score' => (int) round($attempts->avg('score')),
        ];
    }

    private function recommendedMateri(TryoutPeserta $peserta, $latestRiwayat, $materiProgress)
    {
        $categoryOrder = ['TWK', 'TIU', 'TKP'];
        $priorityCategories = collect($latestRiwayat?->detail_jawaban ?? [])
            ->groupBy(fn ($item) => $item['kategori'] ?? 'LAIN')
            ->map(function ($items, $category) {
                $total = $items->count();
                $wrong = $items
                    ->filter(fn ($item) => !($item['benar'] ?? false))
                    ->count();

                return [
                    'category' => $category,
                    'wrong' => $wrong,
                    'accuracy' => $total > 0 ? (int) round((($total - $wrong) / $total) * 100) : 0,
                ];
            })
            ->sortByDesc('wrong')
            ->pluck('category')
            ->filter()
            ->values();

        $categories = $priorityCategories->merge($categoryOrder)->unique()->values();
        $recommendations = collect();

        foreach ($categories as $category) {
            $items = TryoutMateri::aktif()
                ->with('kategoriSoal')
                ->whereHas('kategoriSoal', fn ($query) => $query->where('kode', $category))
                ->orderBy('judul')
                ->get()
                ->sortBy(function (TryoutMateri $materi) use ($materiProgress) {
                    $progress = $materiProgress->get($materi->id);
                    return ($progress?->read_at ? 1 : 0) . ($progress?->is_bookmarked ? 0 : 1) . $materi->judul;
                });

            foreach ($items as $materi) {
                if ($recommendations->count() >= 4) {
                    break 2;
                }

                $recommendations->push($materi);
            }
        }

        return $recommendations;
    }

    private function wrongReviewItems($latestRiwayat)
    {
        $details = collect($latestRiwayat?->detail_jawaban ?? [])
            ->filter(fn ($item) => ($item['jawaban'] ?? null) && !($item['benar'] ?? false))
            ->take(6)
            ->values();

        $soals = TryoutSoal::with('kategoriSoal')
            ->whereIn('id', $details->pluck('soal_id')->filter()->all())
            ->get()
            ->keyBy('id');

        return $details->map(function ($item) use ($soals) {
            $soal = $soals->get($item['soal_id'] ?? null);

            return [
                'kategori' => $item['kategori'] ?? ($soal?->kategoriSoal?->kode ?? $soal?->kategori),
                'pertanyaan' => $soal?->pertanyaan ?? ($item['pertanyaan'] ?? 'Soal tidak ditemukan.'),
                'jawaban' => $item['jawaban'] ?? '-',
                'jawaban_benar' => $item['jawaban_benar'] ?? $soal?->jawaban_benar,
                'pembahasan' => $soal?->pembahasan ?? ($item['pembahasan'] ?? null),
            ];
        });
    }

    private function historyMode(array $detailJawaban): string
    {
        $categories = collect($detailJawaban)->pluck('kategori')->filter()->unique();

        return $categories->count() === 1 ? 'Latihan ' . $categories->first() : 'Simulasi';
    }

    private function balancedRandomSoals($soals, int $jumlahSoal)
    {
        $groups = $soals
            ->groupBy(function (TryoutSoal $soal) {
                return $soal->kategoriSoal->kode ?? $soal->kategori ?? 'Lainnya';
            })
            ->filter(function ($group) {
                return $group->isNotEmpty();
            })
            ->values()
            ->shuffle()
            ->values();

        if ($groups->isEmpty()) {
            return collect();
        }

        $perCategory = intdiv($jumlahSoal, $groups->count());
        $remainder = $jumlahSoal % $groups->count();
        $selected = collect();
        $remaining = collect();

        foreach ($groups as $index => $group) {
            $take = $perCategory + ($index < $remainder ? 1 : 0);
            $shuffledGroup = $group->shuffle()->values();

            $selected = $selected->merge($shuffledGroup->take($take));
            $remaining = $remaining->merge($shuffledGroup->slice($take));
        }

        if ($selected->count() < $jumlahSoal) {
            $selected = $selected->merge(
                $remaining->shuffle()->take($jumlahSoal - $selected->count())
            );
        }

        return $selected->shuffle()->take($jumlahSoal)->values();
    }

    private function authenticatedPeserta(): ?TryoutPeserta
    {
        return TryoutPeserta::aktif()->find(session('tryout_peserta_id'));
    }

    private function forgetTryoutSession(): void
    {
        session()->forget([
            'tryout_peserta_id',
            'tryout_peserta_username',
            'tryout_peserta_nama',
        ]);
    }
}
