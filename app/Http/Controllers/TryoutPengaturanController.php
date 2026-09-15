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
            'jumlah_soal' => 'required|integer|min:1|max:500',
            'kisi_kisi_deskripsi' => 'nullable|string|max:2000',
            'permenpan_file' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $pengaturan = TryoutPengaturan::current();
        $payload = [
            'acak_soal' => $request->boolean('acak_soal'),
            'acak_seimbang_kategori' => $request->boolean('acak_seimbang_kategori'),
            'acak_jawaban' => $request->boolean('acak_jawaban'),
            'durasi_menit' => $validated['durasi_menit'],
            'jumlah_soal' => $validated['jumlah_soal'],
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

        return redirect('/admin/dashboard')
            ->with('success', 'Pengaturan tryout berhasil disimpan')
            ->with('active_tab', 'master-soal');
    }
}
