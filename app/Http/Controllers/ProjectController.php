<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_project' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'deskripsi' => 'required|string',
            'teknologi' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'link_demo' => 'nullable|url|max:255',
            'link_repository' => 'nullable|url|max:255',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $data = $request->only([
            'nama_project',
            'kategori',
            'deskripsi',
            'teknologi',
            'status',
            'link_demo',
            'link_repository',
        ]);

        $data['status'] = $request->filled('status') ? $request->status : 'Selesai';

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $this->uploadGambar($request);
        }

        Project::create($data);

        return redirect('/admin/dashboard')
            ->with('success', 'Project berhasil ditambahkan')
            ->with('active_tab', 'project');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_project' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'deskripsi' => 'required|string',
            'teknologi' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'link_demo' => 'nullable|url|max:255',
            'link_repository' => 'nullable|url|max:255',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $project = Project::findOrFail($id);

        $data = $request->only([
            'nama_project',
            'kategori',
            'deskripsi',
            'teknologi',
            'status',
            'link_demo',
            'link_repository',
        ]);

        $data['status'] = $request->filled('status') ? $request->status : 'Selesai';

        if ($request->hasFile('gambar')) {
            $this->deleteGambar($project->gambar);
            $data['gambar'] = $this->uploadGambar($request);
        }

        $project->update($data);

        return redirect('/admin/dashboard')
            ->with('success', 'Project berhasil diupdate')
            ->with('active_tab', 'project');
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);

        $this->deleteGambar($project->gambar);
        $project->delete();

        return redirect('/admin/dashboard')
            ->with('success', 'Project berhasil dihapus')
            ->with('active_tab', 'project');
    }

    private function uploadGambar(Request $request): string
    {
        $file = $request->file('gambar');
        $imageDirectory = public_path('images/projects');
        File::ensureDirectoryExists($imageDirectory);

        $baseName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $namaFile = time() . '_' . Str::slug($baseName) . '.' . $extension;

        $file->move($imageDirectory, $namaFile);

        return $namaFile;
    }

    private function deleteGambar(?string $gambar): void
    {
        if ($gambar && file_exists(public_path('images/projects/' . $gambar))) {
            unlink(public_path('images/projects/' . $gambar));
        }
    }
}
