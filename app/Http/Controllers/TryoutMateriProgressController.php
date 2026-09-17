<?php

namespace App\Http\Controllers;

use App\Models\TryoutMateri;
use App\Models\TryoutMateriProgress;
use App\Models\TryoutPeserta;
use Illuminate\Http\Request;

class TryoutMateriProgressController extends Controller
{
    public function toggleBookmark(Request $request, TryoutMateri $materi)
    {
        $peserta = $this->authenticatedPeserta();

        if (!$peserta || $materi->status !== 'aktif') {
            abort(404);
        }

        $progress = $this->progress($peserta, $materi);
        $progress->update(['is_bookmarked' => !$progress->is_bookmarked]);

        return back()->with('success', $progress->is_bookmarked
            ? 'Materi berhasil disimpan ke bookmark.'
            : 'Materi dihapus dari bookmark.');
    }

    public function markRead(Request $request, TryoutMateri $materi)
    {
        $peserta = $this->authenticatedPeserta();

        if (!$peserta || $materi->status !== 'aktif') {
            abort(404);
        }

        $progress = $this->progress($peserta, $materi);
        $progress->update(['read_at' => $progress->read_at ?: now()]);

        return back()->with('success', 'Materi ditandai sudah dibaca.');
    }

    private function progress(TryoutPeserta $peserta, TryoutMateri $materi): TryoutMateriProgress
    {
        return TryoutMateriProgress::firstOrCreate([
            'tryout_peserta_id' => $peserta->id,
            'tryout_materi_id' => $materi->id,
        ]);
    }

    private function authenticatedPeserta(): ?TryoutPeserta
    {
        return TryoutPeserta::aktif()->find(session('tryout_peserta_id'));
    }
}
