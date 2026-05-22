<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TemplatKlausa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class AdminTemplatController extends Controller
{
    // 1. Paparkan halaman senarai & borang tambah klausa
    public function index()
    {
        // 1. Ambil semua data dan susun secara natural (Kekalkan logik bijak anda)
        $semuaKlausa = \App\Models\TemplatKlausa::all()
                            ->sortBy('no_klausa', SORT_NATURAL)
                            ->values();

        // 2. Konfigurasi Pagination (10 barisan)
        $perPage = 10;
        $currentPage = Paginator::resolveCurrentPage() ?: 1;
        
        // 3. Potong data mengikut muka surat semasa
        $items = $semuaKlausa->slice(($currentPage - 1) * $perPage, $perPage);

        // 4. Hasilkan objek Paginator
        $senaraiKlausa = new LengthAwarePaginator(
            $items, 
            $semuaKlausa->count(), 
            $perPage, 
            $currentPage, 
            ['path' => Paginator::resolveCurrentPath()]
        );

        return view('admin-templat', compact('senaraiKlausa'));
    }

    // 2. Simpan klausa baharu ke pangkalan data
    public function store(Request $request)
    {
        // 1. Pengesahan data dengan Regex untuk format ISO
        $request->validate([
            'no_klausa' => [
                'required', 
                'string', 
                'regex:/^\d+(\.\d+)*$/', 
                'unique:templat_klausas,no_klausa'
            ],
            'tajuk_klausa' => 'required|string',
            'tajuk_sub_klausa' => 'nullable|string', 
            'deskripsi' => 'nullable|string',
        ], [
            'no_klausa.regex' => 'Format salah! Sila gunakan format nombor bertitik MS ISO (Contoh: 4, 4.1, 7.1.5.1).',
            'no_klausa.unique' => 'Nombor klausa ini sudah wujud dalam sistem. Sila masukkan nombor lain.'
        ]);

        // 2. Simpan ke pangkalan data
        \App\Models\TemplatKlausa::create([
            'no_klausa' => $request->no_klausa,
            'tajuk_klausa' => $request->tajuk_klausa,
            'tajuk_sub_klausa' => $request->tajuk_sub_klausa,
            'deskripsi' => $request->deskripsi,
            
            // ==========================================
            // TAMBAH BARIS INI: Penyelamat pangkalan data lama
            // Jika deskripsi kosong, ia akan masukkan tajuk klausa.
            // ==========================================
            'perkara_periksa' => $request->deskripsi ?? $request->tajuk_klausa, 
            
            'pentadbir_sistem_id' => Auth::guard('admin')->id(), 
        ]);

        return back()->with('success', 'Klausa ISO berjaya ditambah dan direkodkan!');
    }

    // 3. Padam klausa
    public function destroy($id)
    {
        $klausa = TemplatKlausa::findOrFail($id);
        $klausa->delete();

        return redirect()->route('admin.templat.index')->with('success', 'Klausa telah dipadam.');
    }
}