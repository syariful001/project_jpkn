<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SesiAudit;
use App\Models\BorangAudit;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SesiAuditController extends Controller
{
    // Memaparkan halaman borang
    public function create()
    {
        // Tarik senarai pengguna yang mempunyai peranan 'juruaudit' sahaja
        $senaraiJuruaudit = User::where('peranan', 'juruaudit')->get();
        $ketua = \Illuminate\Support\Facades\Auth::user();
        $senaraiJuruaudit->push($ketua);
        
        return view('ketua-juruaudit.cipta-sesi', compact('senaraiJuruaudit'));
    }

    // Menyimpan data ke pangkalan data
    public function store(Request $request)
    {
        // 1. SAHKAN DATA SESI (Pastikan tatasusunan/array diterima dengan baik)
        $request->validate([
            'tajuk_sesi' => 'required|string', 
            'kategori_senarai_semak' => 'required|string',
            'tarikh_mula' => 'required|date',    
            'tarikh_tamat' => 'required|date|after_or_equal:tarikh_mula', 
            
            // Data dalam bentuk Array (Penugasan Dinamik)
            'juruaudit_id' => 'required|array|min:1', 
            'juruaudit_id.*' => 'required|exists:users,id',
            'bahagian_cawangan' => 'required|array|min:1',
            'bahagian_cawangan.*' => 'required|string',
        ]);

        // Langkah Berjaga-jaga: Pastikan bilangan juruaudit dipilih sama dengan bilangan lokasi
        if (count($request->juruaudit_id) !== count($request->bahagian_cawangan)) {
            return back()->withErrors(['msg' => 'Ralat sistem: Maklumat Juruaudit dan Lokasi tidak sepadan.'])->withInput();
        }

        // 2. CIPTA SESI UTAMA (Ibu fail)
        $sesi = \App\Models\SesiAudit::create([
            'pencipta_id' => Auth::id(),
            'tajuk_sesi' => $request->tajuk_sesi, 
            'tarikh_mula' => $request->tarikh_mula,   
            'tarikh_tamat' => $request->tarikh_tamat, 
            'status' => 'dirancang',
        ]);

        // 3. TARIK SEMUA TEMPLAT KLAUSA (Di luar gelung untuk kelajuan sistem)
        $semuaTemplatKlausa = \App\Models\TemplatKlausa::all();

        // 4. ENJIN PENGGANDA DINAMIK (Cipta borang berserta lokasi khusus untuk setiap juruaudit)
        foreach ($request->juruaudit_id as $index => $id_juruaudit) {
            
            // Dapatkan lokasi khusus berdasarkan index baris juruaudit ini
            $lokasiTugasan = $request->bahagian_cawangan[$index];

            // A: Cipta fail borang untuk individu ini
            $borang = \App\Models\BorangAudit::create([
                'sesi_audit_id' => $sesi->id,
                'ketua_juruaudit_id' => Auth::id(),
                'juruaudit_ditugaskan_id' => $id_juruaudit, 
                'bahagian_cawangan' => $lokasiTugasan, // Memasukkan lokasi yang berbeza-beza
                'kategori_senarai_semak' => $request->kategori_senarai_semak,
                'status' => 'ditugaskan', 
            ]);

            // B: SALIN KLAUSA KE DALAM BORANG INDIVIDU INI
            foreach ($semuaTemplatKlausa as $templat) {
                \App\Models\ItemAudit::create([
                    'borang_audit_id' => $borang->id, 
                    'templat_klausa_id' => $templat->id,
                    'no_klausa' => $templat->no_klausa,
                    'perkara_periksa' => $templat->deskripsi ?? $templat->tajuk_klausa,
                    'respon' => null, 
                    'ulasan' => null,
                    'rujukan' => null,
                ]);
            }
        }

        // 5. KEMBALI KE DASHBOARD
        return redirect()->route('dashboard')->with('success', 'Sesi audit berjaya dicipta! Kesemua tugasan berserta cawangan berbeza telah dihantar ke peti masuk juruaudit masing-masing.');
    }
}