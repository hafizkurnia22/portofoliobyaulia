<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengalamanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SertifikasiController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\TentangSayaController;

/*
|--------------------------------------------------------------------------
| FRONTEND / USER
|--------------------------------------------------------------------------
*/

Route::get('/', [PengalamanController::class, 'index']);

Route::get('/sertifikasi', function () {
    $sertifikasi = \App\Models\Sertifikasi::latest()->get();

    return view('pages.sertifikasi', compact('sertifikasi'));
});

/*
|--------------------------------------------------------------------------
| ADMIN LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', function () {
    return view('admin.login');
});

Route::post('/admin/login', [AdminController::class, 'login']);

Route::get('/admin/logout', [AdminController::class, 'logout']);

/*
|--------------------------------------------------------------------------
| ADMIN DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/admin/dashboard', function () {

    if (!session('admin_login')) {
        return redirect('/admin/login');
    }

    $pengalaman = \App\Models\Pengalaman::latest()
        ->paginate(5, ['*'], 'pengalaman_page');

    $sertifikasi = \App\Models\Sertifikasi::latest()
        ->paginate(5, ['*'], 'sertifikasi_page');

    $skill = \App\Models\Skill::latest()
        ->paginate(5, ['*'], 'skill_page');

        $tentangSaya = \App\Models\TentangSaya::first();

    return view('admin.dashboard', compact(
    'pengalaman',
    'sertifikasi',
    'skill',
    'tentangSaya'
));
});

/*
|--------------------------------------------------------------------------
| PENGALAMAN
|--------------------------------------------------------------------------
*/

Route::post('/simpan-pengalaman', [PengalamanController::class, 'store']);

Route::put('/update-pengalaman/{id}', [PengalamanController::class, 'update']);

Route::delete('/hapus-pengalaman/{id}', [PengalamanController::class, 'destroy']);

/*
|--------------------------------------------------------------------------
| SERTIFIKASI
|--------------------------------------------------------------------------
*/

Route::post('/simpan-sertifikasi', [SertifikasiController::class, 'store']);
Route::delete('/hapus-sertifikasi/{id}', [SertifikasiController::class, 'destroy']);
Route::put('/update-sertifikasi/{id}', [SertifikasiController::class, 'update']);
/*Skill*/

Route::post('/simpan-skill', [SkillController::class, 'store']);
Route::delete('/hapus-skill/{id}', [SkillController::class, 'destroy']);

Route::put('/update-skill/{id}', [SkillController::class, 'update']);

/*Tentang Saya*/
Route::post('/simpan-tentang-saya', [TentangSayaController::class, 'storeOrUpdate']);


/*Buat Admin*/
