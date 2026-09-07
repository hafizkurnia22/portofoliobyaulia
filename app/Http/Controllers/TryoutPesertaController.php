<?php

namespace App\Http\Controllers;

use App\Models\TryoutPeserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TryoutPesertaController extends Controller
{
    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $data['pin_hash'] = Hash::make($data['pin']);
        unset($data['pin']);

        TryoutPeserta::create($data);

        return redirect('/admin/dashboard')
            ->with('success', 'Peserta tryout berhasil ditambahkan')
            ->with('active_tab', 'master-peserta-tryout');
    }

    public function update(Request $request, $id)
    {
        $peserta = TryoutPeserta::findOrFail($id);
        $data = $this->validatedData($request, $peserta->id);

        if (!empty($data['pin'])) {
            $data['pin_hash'] = Hash::make($data['pin']);
        }

        unset($data['pin']);
        $peserta->update($data);

        return redirect('/admin/dashboard')
            ->with('success', 'Peserta tryout berhasil diupdate')
            ->with('active_tab', 'master-peserta-tryout');
    }

    public function destroy($id)
    {
        TryoutPeserta::findOrFail($id)->delete();

        return redirect('/admin/dashboard')
            ->with('success', 'Peserta tryout berhasil dihapus')
            ->with('active_tab', 'master-peserta-tryout');
    }

    private function validatedData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'nama' => 'nullable|string|max:100',
            'username' => [
                'required',
                'string',
                'max:100',
                'regex:/^[A-Za-z0-9_.-]+$/',
                Rule::unique('tryout_pesertas', 'username')->ignore($ignoreId),
            ],
            'pin' => [
                $ignoreId ? 'nullable' : 'required',
                'string',
                'regex:/^[0-9]{4,12}$/',
            ],
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'username.regex' => 'Username hanya boleh berisi huruf, angka, titik, garis bawah, dan strip.',
            'pin.regex' => 'PIN harus berupa angka 4 sampai 12 digit.',
        ]);
    }
}
