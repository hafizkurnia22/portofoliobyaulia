<?php

namespace App\Http\Controllers;

use App\Models\TryoutPeserta;
use App\Models\TryoutPengaturan;
use App\Models\TryoutRiwayat;
use App\Models\TryoutSoal;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TryoutRiwayatController extends Controller
{
    public function store(Request $request)
    {
        $peserta = TryoutPeserta::aktif()->find(session('tryout_peserta_id'));

        if (!$peserta) {
            return response()->json([
                'message' => 'Sesi peserta tidak aktif. Silakan login ulang.',
            ], 401);
        }

        $validated = $request->validate([
            'answers' => 'nullable|array',
            'answers.*' => 'nullable|in:A,B,C,D,E',
            'marked' => 'nullable|array',
            'marked.*' => 'boolean',
            'question_ids' => 'required|array|min:1',
            'question_ids.*' => 'integer|exists:tryout_soals,id',
            'durasi_detik' => 'nullable|integer|min:0|max:86400',
            'started_at' => 'nullable|date',
        ]);

        $questionIds = collect($validated['question_ids'])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();
        $answers = $validated['answers'] ?? [];
        $marked = $validated['marked'] ?? [];
        $soals = TryoutSoal::with('kategoriSoal')
            ->whereIn('id', $questionIds)
            ->get()
            ->keyBy('id');

        $totalScore = 0;
        $correctCount = 0;
        $answeredCount = 0;
        $detail = [];

        foreach ($questionIds as $questionId) {
            $soal = $soals->get($questionId);

            if (!$soal) {
                continue;
            }

            $answer = $answers[$questionId] ?? null;
            $score = 0;
            $isCorrect = false;

            if ($answer) {
                $answeredCount++;
                $score = $this->optionScore($soal, $answer);
                $isCorrect = $soal->jawaban_benar === $answer;

                if ($isCorrect) {
                    $correctCount++;
                }
            }

            $totalScore += $score;

            $detail[] = [
                'soal_id' => $soal->id,
                'kode_soal' => $soal->kode_soal,
                'kategori' => $soal->kategoriSoal->kode ?? $soal->kategori,
                'pertanyaan' => $soal->pertanyaan,
                'opsi' => [
                    'A' => $soal->opsi_a,
                    'B' => $soal->opsi_b,
                    'C' => $soal->opsi_c,
                    'D' => $soal->opsi_d,
                    'E' => $soal->opsi_e,
                ],
                'jawaban' => $answer,
                'jawaban_benar' => $soal->jawaban_benar,
                'skor' => $score,
                'benar' => $isCorrect,
                'ragu' => (bool) ($marked[$questionId] ?? false),
                'pembahasan' => $soal->pembahasan,
            ];
        }

        $riwayat = TryoutRiwayat::create([
            'tryout_peserta_id' => $peserta->id,
            'total_soal' => $questionIds->count(),
            'total_dijawab' => $answeredCount,
            'total_benar' => $correctCount,
            'total_ragu' => collect($marked)->filter()->count(),
            'total_skor' => $totalScore,
            'durasi_detik' => (int) ($validated['durasi_detik'] ?? 0),
            'detail_jawaban' => $detail,
            'started_at' => isset($validated['started_at']) ? Carbon::parse($validated['started_at']) : null,
            'finished_at' => now(),
        ]);

        $pengaturan = TryoutPengaturan::current();
        $minimumScores = collect(['TWK', 'TIU', 'TKP'])
            ->mapWithKeys(fn ($kode) => [$kode => $pengaturan->minimalSkorKelulusan($kode)]);
        $categoryScores = collect($detail)
            ->groupBy('kategori')
            ->map(fn ($items) => (int) $items->sum('skor'));
        $examCategories = $categoryScores->keys()
            ->filter(fn ($kode) => in_array($kode, ['TWK', 'TIU', 'TKP'], true))
            ->values();
        $categoryResults = $examCategories
            ->mapWithKeys(function ($kode) use ($categoryScores, $minimumScores) {
                $score = (int) $categoryScores->get($kode, 0);
                $minimum = (int) $minimumScores->get($kode, 0);

                return [$kode => [
                    'skor' => $score,
                    'minimal' => $minimum,
                    'lulus' => $score >= $minimum,
                ]];
            });
        $failedCategories = $categoryResults
            ->filter(fn ($result) => ! $result['lulus'])
            ->keys()
            ->values();

        return response()->json([
            'message' => 'Riwayat tryout berhasil disimpan.',
            'riwayat_id' => $riwayat->id,
            'total_skor' => $riwayat->total_skor,
            'total_soal' => $riwayat->total_soal,
            'total_dijawab' => $riwayat->total_dijawab,
            'total_benar' => $riwayat->total_benar,
            'total_ragu' => $riwayat->total_ragu,
            'durasi_detik' => $riwayat->durasi_detik,
            'finished_at' => $riwayat->finished_at?->format('d M Y H:i'),
            'hasil_kelulusan' => [
                'lulus' => $failedCategories->isEmpty(),
                'kategori_gagal' => $failedCategories,
                'kategori' => $categoryResults,
            ],
        ]);
    }

    private function optionScore(TryoutSoal $soal, string $option): int
    {
        $score = (int) $soal->{'skor_' . strtolower($option)};

        if ($score > 0) {
            return $score;
        }

        return $soal->jawaban_benar === $option ? 5 : 0;
    }
}
