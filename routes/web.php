<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengalamanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CvBuilderController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SertifikasiController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\TentangSayaController;
use App\Http\Controllers\TryoutAuthController;
use App\Http\Controllers\TryoutKategoriSoalController;
use App\Http\Controllers\TryoutMateriController;
use App\Http\Controllers\TryoutPengaturanController;
use App\Http\Controllers\TryoutPesertaController;
use App\Http\Controllers\TryoutRiwayatController;
use App\Http\Controllers\TryoutSoalController;

/*
|--------------------------------------------------------------------------
| FRONTEND / USER
|--------------------------------------------------------------------------
*/

Route::get('/', [PengalamanController::class, 'index']);
Route::get('/cv-builder', [CvBuilderController::class, 'index'])->name('cv.builder');
Route::get('/download-cv', [CvBuilderController::class, 'download'])->name('cv.download');
Route::get('/tryout/login', [TryoutAuthController::class, 'showLogin'])->name('tryout.login');
Route::post('/tryout/login', [TryoutAuthController::class, 'login'])->name('tryout.login.submit');
Route::post('/tryout/logout', [TryoutAuthController::class, 'logout'])->name('tryout.logout');
Route::get('/tryout', [TryoutSoalController::class, 'index'])->name('tryout.index');
Route::get('/tryout/materi/{materi}', [TryoutSoalController::class, 'materiDetail'])->name('tryout.materi.show');
Route::post('/tryout/riwayat', [TryoutRiwayatController::class, 'store'])->name('tryout.riwayat.store');

Route::get('/sertifikasi', function () {
    $sertifikasi = \App\Models\Sertifikasi::byLatestYear()->get();

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

    $activeTab = request('active_tab', old('active_tab', session('active_tab', 'dashboard')));
    $allowedTabs = [
        'dashboard',
        'pengalaman',
        'sertifikasi',
        'skill',
        'project',
        'tentang',
        'master-kategori-soal',
        'master-materi-tryout',
        'master-peserta-tryout',
        'riwayat-tryout',
        'master-soal',
    ];

    if (!in_array($activeTab, $allowedTabs, true)) {
        $activeTab = 'dashboard';
    }

    $emptyPaginator = function (string $pageName, int $perPage) {
        return new \Illuminate\Pagination\LengthAwarePaginator(
            collect(),
            0,
            $perPage,
            request($pageName, 1),
            [
                'path' => request()->url(),
                'pageName' => $pageName,
            ]
        );
    };

    $pengalaman = $activeTab === 'pengalaman'
        ? \App\Models\Pengalaman::byLatestYear()->paginate(5, ['*'], 'pengalaman_page')
        : $emptyPaginator('pengalaman_page', 5);

    $sertifikasi = $activeTab === 'sertifikasi'
        ? \App\Models\Sertifikasi::byLatestYear()->paginate(5, ['*'], 'sertifikasi_page')
        : $emptyPaginator('sertifikasi_page', 5);

    $skill = $activeTab === 'skill'
        ? \App\Models\Skill::latest()->paginate(5, ['*'], 'skill_page')
        : $emptyPaginator('skill_page', 5);

    $project = $activeTab === 'project'
        ? \App\Models\Project::latest()->paginate(5, ['*'], 'project_page')
        : $emptyPaginator('project_page', 5);

    $tryoutKategoriSoal = $activeTab === 'master-kategori-soal'
        ? \App\Models\TryoutKategoriSoal::orderBy('id')->paginate(5, ['*'], 'tryout_kategori_page')
        : $emptyPaginator('tryout_kategori_page', 5);

    $tryoutKategoriOptions = \App\Models\TryoutKategoriSoal::aktif()->orderBy('id')->get();
    $tryoutPengaturan = \App\Models\TryoutPengaturan::current();

    $tryoutPeserta = $activeTab === 'master-peserta-tryout'
        ? \App\Models\TryoutPeserta::latest()->paginate(8, ['*'], 'tryout_peserta_page')
        : $emptyPaginator('tryout_peserta_page', 8);

    $tryoutRiwayat = $activeTab === 'riwayat-tryout'
        ? \App\Models\TryoutRiwayat::with('peserta')->latest()->paginate(10, ['*'], 'tryout_riwayat_page')
        : $emptyPaginator('tryout_riwayat_page', 10);

    $tryoutMateri = $activeTab === 'master-materi-tryout'
        ? \App\Models\TryoutMateri::with('kategoriSoal')->latest()->paginate(8, ['*'], 'tryout_materi_page')
        : $emptyPaginator('tryout_materi_page', 8);

    $tryoutSoal = $activeTab === 'master-soal'
        ? \App\Models\TryoutSoal::with('kategoriSoal')->latest()->paginate(8, ['*'], 'tryout_soal_page')
        : $emptyPaginator('tryout_soal_page', 8);

    $tentangSaya = \App\Models\TentangSaya::first();

    $totalPengalaman = 0;
    $totalSertifikasi = 0;
    $totalSkill = 0;
    $totalProject = 0;
    $totalTryoutSoal = 0;
    $totalTryoutKategori = 0;
    $totalTryoutPeserta = 0;
    $totalTryoutRiwayat = 0;
    $totalTryoutMateri = 0;
    $rataSkill = 0;
    $skillTertinggi = null;
    $sertifikasiTerbaru = null;
    $pengalamanTerbaru = null;
    $projectTerbaru = null;

    if ($activeTab === 'dashboard') {
        $totalPengalaman = \App\Models\Pengalaman::count();
        $totalSertifikasi = \App\Models\Sertifikasi::count();
        $totalSkill = \App\Models\Skill::count();
        $totalProject = \App\Models\Project::count();
        $totalTryoutSoal = \App\Models\TryoutSoal::count();
        $totalTryoutKategori = \App\Models\TryoutKategoriSoal::count();
        $totalTryoutPeserta = \App\Models\TryoutPeserta::count();
        $totalTryoutRiwayat = \App\Models\TryoutRiwayat::count();
        $totalTryoutMateri = \App\Models\TryoutMateri::count();
        $rataSkill = \App\Models\Skill::avg('persentase') ?? 0;
        $skillTertinggi = \App\Models\Skill::orderByDesc('persentase')->first();
        $sertifikasiTerbaru = \App\Models\Sertifikasi::byLatestYear()->first();
        $pengalamanTerbaru = \App\Models\Pengalaman::byLatestYear()->first();
        $projectTerbaru = \App\Models\Project::latest()->first();
    }

    return view('admin.dashboard', compact(
        'pengalaman',
        'sertifikasi',
        'skill',
        'project',
        'tryoutKategoriSoal',
        'tryoutKategoriOptions',
        'tryoutPengaturan',
        'tryoutPeserta',
        'tryoutRiwayat',
        'tryoutMateri',
        'tryoutSoal',
        'tentangSaya',
        'activeTab',
        'totalPengalaman',
        'totalSertifikasi',
        'totalSkill',
        'totalProject',
        'totalTryoutSoal',
        'totalTryoutKategori',
        'totalTryoutPeserta',
        'totalTryoutRiwayat',
        'totalTryoutMateri',
        'rataSkill',
        'skillTertinggi',
        'sertifikasiTerbaru',
        'pengalamanTerbaru',
        'projectTerbaru'
    ));
});

Route::get('/admin/tryout/pengaturan', function () {
    if (!session('admin_login')) {
        return redirect('/admin/login');
    }

    $tryoutPengaturan = \App\Models\TryoutPengaturan::current();

    return view('admin.tryout-settings', compact('tryoutPengaturan'));
})->name('admin.tryout.settings');
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

/* PROJECT */
Route::post('/simpan-project', [ProjectController::class, 'store']);
Route::put('/update-project/{id}', [ProjectController::class, 'update']);
Route::delete('/hapus-project/{id}', [ProjectController::class, 'destroy']);

/*Tentang Saya*/
Route::post('/simpan-tentang-saya', [TentangSayaController::class, 'storeOrUpdate']);

/* TRYOUT */
Route::post('/simpan-tryout-soal', [TryoutSoalController::class, 'store']);
Route::post('/import-tryout-soal', [TryoutSoalController::class, 'import']);
Route::get('/template-import-tryout-soal', [TryoutSoalController::class, 'template']);
Route::put('/update-tryout-soal/{id}', [TryoutSoalController::class, 'update']);
Route::delete('/hapus-tryout-soal/{id}', [TryoutSoalController::class, 'destroy']);
Route::post('/simpan-tryout-pengaturan', [TryoutPengaturanController::class, 'update']);
Route::post('/simpan-tryout-peserta', [TryoutPesertaController::class, 'store']);
Route::put('/update-tryout-peserta/{id}', [TryoutPesertaController::class, 'update']);
Route::delete('/hapus-tryout-peserta/{id}', [TryoutPesertaController::class, 'destroy']);
Route::post('/simpan-tryout-kategori-soal', [TryoutKategoriSoalController::class, 'store']);
Route::put('/update-tryout-kategori-soal/{id}', [TryoutKategoriSoalController::class, 'update']);
Route::delete('/hapus-tryout-kategori-soal/{id}', [TryoutKategoriSoalController::class, 'destroy']);
Route::post('/simpan-tryout-materi', [TryoutMateriController::class, 'store']);
Route::put('/update-tryout-materi/{id}', [TryoutMateriController::class, 'update']);
Route::delete('/hapus-tryout-materi/{id}', [TryoutMateriController::class, 'destroy']);
