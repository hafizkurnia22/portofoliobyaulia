<?php

namespace App\Http\Controllers;

use App\Models\TryoutPengaturan;
use Illuminate\Http\Request;

class TryoutPengaturanController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'durasi_menit' => 'required|integer|min:1|max:300',
            'jumlah_soal' => 'required|integer|min:1|max:500',
        ]);

        TryoutPengaturan::current()->update([
            'acak_soal' => $request->boolean('acak_soal'),
            'acak_seimbang_kategori' => $request->boolean('acak_seimbang_kategori'),
            'acak_jawaban' => $request->boolean('acak_jawaban'),
            'durasi_menit' => $validated['durasi_menit'],
            'jumlah_soal' => $validated['jumlah_soal'],
        ]);

        return redirect('/admin/dashboard')
            ->with('success', 'Pengaturan tryout berhasil disimpan')
            ->with('active_tab', 'master-soal');
    }
}
