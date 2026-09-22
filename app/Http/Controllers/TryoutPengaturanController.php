<?php

namespace App\Http\Controllers;

use App\Models\TryoutPengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TryoutPengaturanController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'durasi_menit' => 'required|integer|min:1|max:300',
            'jumlah_soal_kategori' => 'required|array',
            'jumlah_soal_kategori.TWK' => 'required|integer|min:1|max:500',
            'jumlah_soal_kategori.TIU' => 'required|integer|min:1|max:500',
            'jumlah_soal_kategori.TKP' => 'required|integer|min:1|max:500',
            'durasi_menit_kategori' => 'required|array',
            'durasi_menit_kategori.TWK' => 'required|integer|min:1|max:300',
            'durasi_menit_kategori.TIU' => 'required|integer|min:1|max:300',
            'durasi_menit_kategori.TKP' => 'required|integer|min:1|max:300',
            'kisi_kisi_deskripsi' => 'nullable|string|max:2000',
            'permenpan_file' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $pengaturan = TryoutPengaturan::current();
        $jumlahSoalKategori = $this->categorySettings($validated['jumlah_soal_kategori']);
        $payload = [
            'acak_soal' => $request->boolean('acak_soal'),
            'acak_jawaban' => $request->boolean('acak_jawaban'),
            'durasi_menit' => $validated['durasi_menit'],
            'jumlah_soal' => array_sum($jumlahSoalKategori),
            'jumlah_soal_kategori' => $jumlahSoalKategori,
            'durasi_menit_kategori' => $this->categorySettings($validated['durasi_menit_kategori']),
            'kisi_kisi_deskripsi' => ($validated['kisi_kisi_deskripsi'] ?? null) ?: null,
        ];

        if ($request->hasFile('permenpan_file')) {
            if ($pengaturan->permenpan_file) {
                Storage::disk('public')->delete($pengaturan->permenpan_file);
            }

            $file = $request->file('permenpan_file');
            $payload['permenpan_file'] = $file->store('tryout/permenpan', 'public');
            $payload['permenpan_nama'] = $file->getClientOriginalName();
        }

        $pengaturan->update($payload);

        return redirect()->route('admin.tryout.settings')
            ->with('success', 'Pengaturan tryout berhasil disimpan')
            ->with('active_tab', 'master-soal');
    }

    public function updateKelulusan(Request $request)
    {
        $validated = $request->validate([
            'minimal_skor_kelulusan' => 'required|array',
            'minimal_skor_kelulusan.TWK' => 'required|integer|min:0|max:2500',
            'minimal_skor_kelulusan.TIU' => 'required|integer|min:0|max:2500',
            'minimal_skor_kelulusan.TKP' => 'required|integer|min:0|max:2500',
        ]);

        TryoutPengaturan::current()->update([
            'minimal_skor_kelulusan' => $this->passingScoreSettings($validated['minimal_skor_kelulusan']),
        ]);

        return redirect('/admin/dashboard?active_tab=master-kelulusan')
            ->with('success', 'Batas minimal skor kelulusan berhasil disimpan.');
    }

    private function categorySettings(array $values): array
    {
        return collect(['TWK', 'TIU', 'TKP'])
            ->mapWithKeys(fn ($kode) => [$kode => max((int) ($values[$kode] ?? 1), 1)])
            ->all();
    }

    private function passingScoreSettings(array $values): array
    {
        return collect(['TWK', 'TIU', 'TKP'])
            ->mapWithKeys(fn ($kode) => [$kode => max((int) ($values[$kode] ?? 0), 0)])
            ->all();
    }
}
