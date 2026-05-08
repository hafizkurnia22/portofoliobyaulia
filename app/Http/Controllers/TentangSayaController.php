<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TentangSaya;

class TentangSayaController extends Controller
{
    public function storeOrUpdate(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'bidang' => 'nullable',
            'status' => 'nullable',
            'deskripsi_1' => 'nullable',
            'deskripsi_2' => 'nullable',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $tentang = TentangSaya::first();

        $data = $request->only([
            'nama',
            'bidang',
            'status',
            'deskripsi_1',
            'deskripsi_2',
        ]);

        if ($request->hasFile('foto')) {
            if ($tentang && $tentang->foto && file_exists(public_path('images/' . $tentang->foto))) {
                unlink(public_path('images/' . $tentang->foto));
            }

            $file = $request->file('foto');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $namaFile);

            $data['foto'] = $namaFile;
        }

        if ($tentang) {
            $tentang->update($data);
        } else {
            TentangSaya::create($data);
        }

        return redirect('/admin/dashboard')
            ->with('success', 'Data tentang saya berhasil disimpan')
            ->with('active_tab', 'tentang');
    }
}