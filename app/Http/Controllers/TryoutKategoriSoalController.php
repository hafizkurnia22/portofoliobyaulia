<?php

namespace App\Http\Controllers;

use App\Models\TryoutKategoriSoal;
use Illuminate\Http\Request;

class TryoutKategoriSoalController extends Controller
{
    public function store(Request $request)
    {
        TryoutKategoriSoal::create($this->validatedData($request));

        return redirect('/admin/dashboard')
            ->with('success', 'Kategori soal berhasil ditambahkan')
            ->with('active_tab', 'master-kategori-soal');
    }

    public function update(Request $request, $id)
    {
        $kategori = TryoutKategoriSoal::findOrFail($id);
        $kategori->update($this->validatedData($request, $kategori->id));

        return redirect('/admin/dashboard')
            ->with('success', 'Kategori soal berhasil diupdate')
            ->with('active_tab', 'master-kategori-soal');
    }

    public function destroy($id)
    {
        $kategori = TryoutKategoriSoal::withCount(['soals', 'materis'])->findOrFail($id);

        if ($kategori->soals_count > 0) {
            return redirect('/admin/dashboard')
                ->with('error', 'Kategori masih digunakan oleh soal')
                ->with('active_tab', 'master-kategori-soal');
        }

        if ($kategori->materis_count > 0) {
            return redirect('/admin/dashboard')
                ->with('error', 'Kategori masih digunakan oleh materi ujian')
                ->with('active_tab', 'master-kategori-soal');
        }

        $kategori->delete();

        return redirect('/admin/dashboard')
            ->with('success', 'Kategori soal berhasil dihapus')
            ->with('active_tab', 'master-kategori-soal');
    }

    private function validatedData(Request $request, ?int $ignoreId = null): array
    {
        $uniqueRule = 'unique:tryout_kategori_soals,kode';

        if ($ignoreId) {
            $uniqueRule .= ',' . $ignoreId;
        }

        $data = $request->validate([
            'kode' => ['required', 'string', 'max:10', $uniqueRule],
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,draft',
        ]);

        $data['kode'] = strtoupper($data['kode']);

        return $data;
    }
}
