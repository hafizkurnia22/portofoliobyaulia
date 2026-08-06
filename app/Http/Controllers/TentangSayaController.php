<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TentangSaya;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class TentangSayaController extends Controller
{
    public function storeOrUpdate(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'bidang' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'deskripsi_1' => 'nullable|string',
            'deskripsi_2' => 'nullable|string',
            'whatsapp' => 'nullable|string|max:255',
            'email_kontak' => 'nullable|email|max:255',
            'facebook' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'tiktok' => 'nullable|url|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'nama.required' => 'Nama wajib diisi sebelum menyimpan profil.',
            'foto.image' => 'File foto harus berupa gambar.',
            'foto.mimes' => 'Foto harus berformat JPG, JPEG, PNG, atau WEBP.',
            'foto.max' => 'Ukuran foto maksimal 5MB.',
            'email_kontak.email' => 'Format email kontak belum valid.',
            'facebook.url' => 'Link Facebook harus berupa URL lengkap.',
            'instagram.url' => 'Link Instagram harus berupa URL lengkap.',
            'tiktok.url' => 'Link TikTok harus berupa URL lengkap.',
        ]);

        $tentang = TentangSaya::first();

        $data = $request->only([
    'nama',
    'bidang',
    'status',
    'deskripsi_1',
    'deskripsi_2',
    'whatsapp',
    'email_kontak',
    'facebook',
    'instagram',
    'tiktok',
]);

        if ($request->hasFile('foto')) {
            if ($tentang && $tentang->foto && file_exists(public_path('images/' . $tentang->foto))) {
                unlink(public_path('images/' . $tentang->foto));
            }

            $file = $request->file('foto');
            $imageDirectory = public_path('images');
            File::ensureDirectoryExists($imageDirectory);

            $baseName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $namaFile = time() . '_' . Str::slug($baseName) . '.' . $extension;
            $file->move($imageDirectory, $namaFile);

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
