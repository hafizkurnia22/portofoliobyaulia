<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sertifikasi;

class SertifikasiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_sertifikat' => 'required',
            'penyelenggara' => 'required',
            'tahun' => 'required',
            'deskripsi' => 'nullable',
            'file_pdf' => 'nullable|mimes:pdf|max:5120',
        ]);

        $data = $request->only([
            'nama_sertifikat',
            'penyelenggara',
            'tahun',
            'deskripsi',
        ]);

        if ($request->hasFile('file_pdf')) {
            $file = $request->file('file_pdf');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('sertifikat'), $namaFile);

            $data['file_pdf'] = $namaFile;
        }

        Sertifikasi::create($data);

        return redirect('/admin/dashboard')->with('success', 'Sertifikasi berhasil ditambahkan')->with('active_tab', 'sertifikasi');
    }

    public function destroy($id)
    {
        $sertifikasi = Sertifikasi::findOrFail($id);

        if ($sertifikasi->file_pdf && file_exists(public_path('sertifikat/' . $sertifikasi->file_pdf))) {
            unlink(public_path('sertifikat/' . $sertifikasi->file_pdf));
        }

        $sertifikasi->delete();

return redirect('/admin/dashboard')->with('success', 'Sertifikasi berhasil dihapus')->with('active_tab', 'sertifikasi');   
 }

    public function update(Request $request, $id)
{
    $request->validate([
        'nama_sertifikat' => 'required',
        'penyelenggara' => 'required',
        'tahun' => 'required',
        'deskripsi' => 'nullable',
        'file_pdf' => 'nullable|mimes:pdf|max:5120',
    ]);

    $sertifikasi = Sertifikasi::findOrFail($id);

    $data = $request->only([
        'nama_sertifikat',
        'penyelenggara',
        'tahun',
        'deskripsi',
    ]);

    if ($request->hasFile('file_pdf')) {
        if ($sertifikasi->file_pdf && file_exists(public_path('sertifikat/' . $sertifikasi->file_pdf))) {
            unlink(public_path('sertifikat/' . $sertifikasi->file_pdf));
        }

        $file = $request->file('file_pdf');
        $namaFile = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('sertifikat'), $namaFile);

        $data['file_pdf'] = $namaFile;
    }

    $sertifikasi->update($data);

return redirect('/admin/dashboard')->with('success', 'Sertifikasi berhasil diupdate')->with('active_tab', 'sertifikasi');
}
}