<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
class AdminController extends Controller
{
    public function login(Request $request)
{
    $admin = Admin::where('email', $request->email)->first();

    if ($admin && Hash::check($request->password, $admin->password)) {
        session([
            'admin_login' => true,
            'admin_name' => $admin->name,
            'admin_id' => $admin->id,
        ]);

        return redirect('/admin/dashboard');
    }

    return redirect('/admin/login')->with('error', 'Email atau password salah');
}

    public function logout()
    {
        session()->forget('admin_login');
        return redirect('/admin/login');
    }
}