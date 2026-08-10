<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengalaman;
use App\Models\Project;

class PengalamanController extends Controller
{
public function index()
{
    $pengalaman = Pengalaman::latest()
        ->paginate(6, ['*'], 'pengalaman_page');

    $sertifikasi = \App\Models\Sertifikasi::latest()->get();
    $skill = \App\Models\Skill::latest()->get();
    $projects = Project::latest()->get();
    $tentangSaya = \App\Models\TentangSaya::first();

return view('pages.home', compact(
    'pengalaman',
    'sertifikasi',
    'skill',
    'projects',
    'tentangSaya'
));}
    public function create()
    {
        return view('pages.tambah_pengalaman');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_perusahaan' => 'required',
            'jabatan' => 'required',
            'periode' => 'required',
            'deskripsi' => 'required',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->only([
            'nama_perusahaan',
            'jabatan',
            'periode',
            'deskripsi'
        ]);

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $namaFile);

            $data['logo'] = $namaFile;
        }

        Pengalaman::create($data);

        return redirect('/admin/dashboard')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $pengalaman = Pengalaman::findOrFail($id);
        return view('pages.edit_pengalaman', compact('pengalaman'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_perusahaan' => 'required',
            'jabatan' => 'required',
            'periode' => 'required',
            'deskripsi' => 'required',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $pengalaman = Pengalaman::findOrFail($id);

        $data = $request->only([
            'nama_perusahaan',
            'jabatan',
            'periode',
            'deskripsi'
        ]);

        if ($request->hasFile('logo')) {
            if ($pengalaman->logo && file_exists(public_path('images/' . $pengalaman->logo))) {
                unlink(public_path('images/' . $pengalaman->logo));
            }

            $file = $request->file('logo');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $namaFile);

            $data['logo'] = $namaFile;
        }

        $pengalaman->update($data);

        return redirect('/admin/dashboard')->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        $pengalaman = Pengalaman::findOrFail($id);

        if ($pengalaman->logo && file_exists(public_path('images/' . $pengalaman->logo))) {
            unlink(public_path('images/' . $pengalaman->logo));
        }

        $pengalaman->delete();

        return redirect('/admin/dashboard')->with('success', 'Data berhasil dihapus');
    }
}
