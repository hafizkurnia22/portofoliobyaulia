<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Skill;

class SkillController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_skill' => 'required',
            'kategori' => 'nullable',
            'persentase' => 'required|integer|min:0|max:100',
        ]);

        Skill::create([
            'nama_skill' => $request->nama_skill,
            'kategori' => $request->kategori,
            'persentase' => $request->persentase,
        ]);

return redirect('/admin/dashboard')->with('success', 'Skill berhasil ditambahkan')->with('active_tab', 'skill');    }

    public function destroy($id)
    {
        Skill::findOrFail($id)->delete();

return redirect('/admin/dashboard')->with('success', 'Skill berhasil dihapus')->with('active_tab', 'skill');    }

    public function update(Request $request, $id)
{
    $request->validate([
        'nama_skill' => 'required',
        'kategori' => 'nullable',
        'persentase' => 'required|integer|min:0|max:100',
    ]);

    $skill = Skill::findOrFail($id);

    $skill->update([
        'nama_skill' => $request->nama_skill,
        'kategori' => $request->kategori,
        'persentase' => $request->persentase,
    ]);

return redirect('/admin/dashboard')->with('success', 'Skill berhasil diupdate')->with('active_tab', 'skill');}
}