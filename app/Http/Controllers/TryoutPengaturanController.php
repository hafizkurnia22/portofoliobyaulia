<?php

namespace App\Http\Controllers;

use App\Models\TryoutPengaturan;
use Illuminate\Http\Request;

class TryoutPengaturanController extends Controller
{
    public function update(Request $request)
    {
        TryoutPengaturan::current()->update([
            'acak_soal' => $request->boolean('acak_soal'),
            'acak_jawaban' => $request->boolean('acak_jawaban'),
        ]);

        return redirect('/admin/dashboard')
            ->with('success', 'Pengaturan tryout berhasil disimpan')
            ->with('active_tab', 'master-soal');
    }
}
