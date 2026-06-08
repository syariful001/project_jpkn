<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SesiAuditController;
use App\Http\Controllers\AdminTemplatController;
use App\Http\Controllers\JuruauditBorangController;
use App\Http\Controllers\KetuaSemakanController;
use App\Http\Controllers\AdminPengurusanController;
use App\Models\SesiAudit;
use App\Models\BorangAudit;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;    

Route::get('/', function () {
    return view('welcome');
});

// ==========================================
// ZON JURUAUDIT & KETUA JURUAUDIT (Kawal selia oleh Auth Biasa)
// ==========================================
Route::get('/dashboard', function () {
    $user = Auth::user();
    $senaraiSesi = collect(); 
    $senaraiTugasan = collect();
    $statistik = [];

    if ($user->peranan === 'ketua_juruaudit') {
        
        // 1. KIRAAN STATISTIK (Dapatkan semua data dahulu supaya kiraan tidak lari)
        $allSesiQuery = SesiAudit::where('pencipta_id', $user->id);
        $jumlahSesi = $allSesiQuery->count();
        $sesiIds = $allSesiQuery->pluck('id');
        
        $totalBorang = BorangAudit::whereIn('sesi_audit_id', $sesiIds)->count();
        $borangSelesai = BorangAudit::whereIn('sesi_audit_id', $sesiIds)->where('status', 'selesai')->count();
        $borangBelum = $totalBorang - $borangSelesai;

        $statistik = [
            'jumlah_sesi' => $jumlahSesi,
            'total_borang' => $totalBorang,
            'borang_selesai' => $borangSelesai,
            'borang_belum' => $borangBelum
        ];

        // 2. PAPARAN JADUAL BERPAGINASI (Limit 5 data, asingkan nama page)
        $senaraiSesi = SesiAudit::with(['senaraiBorang.namaJuruaudit'])
                                ->where('pencipta_id', $user->id)
                                ->orderBy('created_at', 'desc')
                                ->paginate(5, ['*'], 'sesi_page');

        $senaraiTugasan = BorangAudit::where('juruaudit_ditugaskan_id', $user->id)
                                      ->orderBy('created_at','desc')
                                      ->paginate(5, ['*'], 'tugas_page');

    } 
    elseif ($user->peranan === 'juruaudit') {
        
        // 1. KIRAAN STATISTIK JURUAUDIT BIASA
        $allTugasan = BorangAudit::where('juruaudit_ditugaskan_id', $user->id)->get();
        
        $statistik = [
            'jumlah_keseluruhan' => $allTugasan->count(),
            'jumlah_selesai' => $allTugasan->where('status', 'selesai')->count(),
            'jumlah_berjalan' => $allTugasan->whereIn('status', ['ditugaskan', 'sedang_diisi', 'siap_disemak'])->count()
        ];

        // 2. PAPARAN JADUAL BERPAGINASI (Limit 5 data)
        $senaraiTugasan = BorangAudit::where('juruaudit_ditugaskan_id', $user->id)
                                     ->orderBy('created_at', 'desc')
                                     ->paginate(5);
    }

    return view('dashboard', compact('senaraiSesi', 'senaraiTugasan', 'statistik'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/juruaudit/borang/{id}', [JuruauditBorangController::class, 'show'])->name('juruaudit.borang.show');
    Route::put('/juruaudit/borang/{id}', [JuruauditBorangController::class, 'update'])->name('juruaudit.borang.update');
    
    Route::get('/ketua/sesi/cipta', [SesiAuditController::class, 'create'])->name('ketua.sesi.create');
    Route::post('/ketua/sesi/simpan', [SesiAuditController::class, 'store'])->name('ketua.sesi.store');
    Route::get('/ketua/semakan/{id}', [KetuaSemakanController::class, 'show'])->name('ketua.semakan.show');
    Route::post('/ketua/semakan/{id}', [KetuaSemakanController::class, 'update'])->name('ketua.semakan.update');
    Route::get('/ketua/semakan/{borang_id}/item/{item_id}/edit', [KetuaSemakanController::class, 'editNcrOfi'])->name('ketua.semakan.edit_ncr_ofi');
    Route::put('/ketua/semakan/{borang_id}/item/{item_id}/update', [KetuaSemakanController::class, 'updateNcrOfi'])->name('ketua.semakan.update_ncr_ofi');
    Route::get('/ketua/semakan/{id}/pdf', [KetuaSemakanController::class, 'cetakPdf'])->name('ketua.semakan.pdf');
    Route::get('/ketua/semakan/{id}/pdf-ncr-ofi', [KetuaSemakanController::class, 'cetakPdfNcrOfi'])->name('ketua.semakan.pdf_ncr_ofi');
});

require __DIR__.'/auth.php';

// ==========================================
// ZON ADMIN
// ==========================================
Route::prefix('admin')->group(function () {
    
    Route::get('/login', function () {
        return view('admin-login');
    })->name('admin.login');

    Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'Maklumat log masuk pentadbir tidak sah.',
        ])->onlyInput('email');
    })->name('admin.login.submit');

    // Papan Pemuka Admin
    Route::get('/dashboard', function () {
        
        $statistik = [
            'borang_selesai' => \App\Models\BorangAudit::where('status', 'selesai')->count(),
            'juruaudit' => \App\Models\User::whereIn('peranan', ['juruaudit', 'ketua_juruaudit'])->count(),
            'templat' => \App\Models\TemplatKlausa::count(),
        ];

        $dataStatus = \App\Models\BorangAudit::selectRaw('status, count(*) as jumlah')
            ->groupBy('status')
            ->pluck('jumlah', 'status')
            ->toArray();

        $dataLokasi = \App\Models\BorangAudit::selectRaw('bahagian_cawangan, count(*) as jumlah')
            ->groupBy('bahagian_cawangan')
            ->orderBy('jumlah', 'desc')
            ->limit(5)
            ->pluck('jumlah', 'bahagian_cawangan')
            ->toArray();

        // Ambil Data Sesi & Borang Berpaginasi
        $semuaSesi = \App\Models\SesiAudit::orderBy('created_at', 'desc')->paginate(5, ['*'], 'sesi_page');
        $semuaBorang = \App\Models\BorangAudit::with('namaJuruaudit')->orderBy('updated_at', 'desc')->paginate(10, ['*'], 'borang_page');

        return view('admin-dashboard', compact('statistik', 'dataStatus', 'dataLokasi', 'semuaSesi', 'semuaBorang'));
        
    })->middleware('auth:admin')->name('admin.dashboard');

    // Pengurusan Templat Klausa
    Route::get('/templat', [AdminTemplatController::class, 'index'])->middleware('auth:admin')->name('admin.templat.index');
    Route::post('/templat/simpan', [AdminTemplatController::class, 'store'])->middleware('auth:admin')->name('admin.templat.store');
    Route::delete('/templat/padam/{id}', [AdminTemplatController::class, 'destroy'])->middleware('auth:admin')->name('admin.templat.destroy');

    // [TAMBAHAN BARU] Pengurusan Sesi & Borang Audit (Edit/Delete)
    Route::get('/sesi/{id}/edit', [AdminPengurusanController::class, 'editSesi'])->middleware('auth:admin')->name('admin.sesi.edit');
    Route::put('/sesi/{id}', [AdminPengurusanController::class, 'updateSesi'])->middleware('auth:admin')->name('admin.sesi.update');
    Route::delete('/sesi/{id}', [AdminPengurusanController::class, 'destroySesi'])->middleware('auth:admin')->name('admin.sesi.destroy');

    Route::get('/borang/{id}/edit', [AdminPengurusanController::class, 'editBorang'])->middleware('auth:admin')->name('admin.borang.edit');
    Route::put('/borang/{id}', [AdminPengurusanController::class, 'updateBorang'])->middleware('auth:admin')->name('admin.borang.update');
    Route::delete('/borang/{id}', [AdminPengurusanController::class, 'destroyBorang'])->middleware('auth:admin')->name('admin.borang.destroy');

    // Fungsi Log Keluar Admin
    Route::post('/logout', function (Request $request) {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    })->name('admin.logout');
    
});

// ==========================================
// LALUAN UJIAN EKSTREM
// ==========================================
Route::get('/ujian-paksa-simpan/{id}', function($id) {
    try {
        $borang = \App\Models\BorangAudit::findOrFail($id);
        $borang->ulasan_ketua = "INI ADALAH UJIAN PINTASAN SISTEM";
        $borang->status = 'selesai';
        $borang->save();

        $sesi = \App\Models\SesiAudit::find($borang->sesi_audit_id);
        if ($sesi) {
            $sesi->status = 'selesai';
            $sesi->save();
        }

        return "<h1>UJIAN BERJAYA!</h1> <p>Pangkalan data berfungsi dengan sempurna. Sila semak Dashboard Ketua sekarang.</p>";
    } catch (\Exception $e) {
        return "<h1>UJIAN GAGAL.</h1> <p>Ralat Pangkalan Data: " . $e->getMessage() . "</p>";
    }
});