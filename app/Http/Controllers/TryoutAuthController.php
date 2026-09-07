<?php

namespace App\Http\Controllers;

use App\Models\TryoutPeserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TryoutAuthController extends Controller
{
    public function showLogin()
    {
        if (session('tryout_peserta_id')) {
            return redirect()->route('tryout.index');
        }

        return view('pages.tryout-login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:100',
            'pin' => 'required|string|max:20',
        ]);

        $peserta = TryoutPeserta::where('username', $validated['username'])->first();

        if (!$peserta || $peserta->status !== 'aktif' || !Hash::check($validated['pin'], $peserta->pin_hash)) {
            return back()
                ->withErrors(['username' => 'Username atau PIN tidak sesuai.'])
                ->withInput($request->only('username'));
        }

        $peserta->update(['last_login_at' => now()]);

        session([
            'tryout_peserta_id' => $peserta->id,
            'tryout_peserta_username' => $peserta->username,
            'tryout_peserta_nama' => $peserta->nama ?: $peserta->username,
        ]);

        return redirect()->route('tryout.index');
    }

    public function logout()
    {
        session()->forget([
            'tryout_peserta_id',
            'tryout_peserta_username',
            'tryout_peserta_nama',
        ]);

        return redirect()->route('tryout.login')->with('success', 'Kamu sudah keluar dari tryout.');
    }
}
