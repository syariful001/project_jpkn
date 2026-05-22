<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BorangAudit;
use App\Models\ItemAudit;
use Illuminate\Support\Facades\Auth;

class JuruauditBorangController extends Controller
{
    /**
     * Memaparkan borang untuk diisi oleh Juruaudit
     */
    public function show($id)
    {
        $borang = BorangAudit::findOrFail($id);

        // Pastikan hanya juruaudit yang ditugaskan boleh melihat borang ini
        if ($borang->juruaudit_ditugaskan_id !== Auth::id()) {
            abort(403, 'Akses Ditolak: Anda bukan pemilik tugasan ini.');
        }

        // Tarik semua klausa beserta tajuknya
        $senaraiItem = ItemAudit::with('templatKlausa')
                                ->where('borang_audit_id', $borang->id)
                                ->orderBy('no_klausa', 'asc')
                                ->get();

        return view('juruaudit.isi-borang', compact('borang', 'senaraiItem'));
    }

    /**
     * Memproses dan menyimpan data yang dihantar oleh Juruaudit
     */
    public function update(Request $request, $id)
    {
        $borang = BorangAudit::findOrFail($id);

        // 1. Simpan Nama Auditee
        $borang->nama_auditee = $request->nama_auditee;

        // 2. Tentukan status borang (Simpan Draf vs Hantar Ke Ketua)
        if ($request->has('hantar_tamat')) {
            $borang->status = 'siap_disemak';
        } else {
            $borang->status = 'sedang_diisi';
        }
        $borang->save();

        // 3. Selaraskan status Sesi Utama jika ia masih 'dirancang'
        $sesi = \App\Models\SesiAudit::find($borang->sesi_audit_id);
        if ($sesi && $sesi->status == 'dirancang') {
            $sesi->status = 'sedang_berjalan';
            $sesi->save();
        }

// 4. Proses dan simpan input teks klausa
        $items = ItemAudit::where('borang_audit_id', $borang->id)->get();

        foreach ($items as $item) {
            // Javascript kita mematikan (disable) input jika kotak klausa tidak ditanda.
            // Jadi, kita hanya memproses item yang wujud di dalam $request->ulasan.
            if (isset($request->ulasan[$item->id])) {
                
                // Simpan teks dari textarea
                $item->perkara_periksa = $request->perkara_periksa[$item->id] ?? $item->perkara_periksa;
                $item->ulasan = $request->ulasan[$item->id];
                $item->rujukan = $request->rujukan[$item->id] ?? null;

                // UBAH DI SINI: Kita hantar 'Ya' sahaja sebagai penanda (flag) 
                // supaya pangkalan data tidak ralat, dan filter KetuaSemakanController tetap berfungsi.
                $item->respon = 'Ya'; 
                
            } else {
                // Jika kotak nyah-tanda (unticked), kosongkan datanya
                $item->ulasan = null;
                $item->rujukan = null;
                $item->respon = null;
            }
            $item->save();
        }

        // 5. Halakan pengguna ke skrin yang betul selepas menyimpan
        if ($request->has('hantar_tamat')) {
            return redirect()->route('dashboard')->with('success', 'Borang audit telah dikunci dan dihantar kepada Ketua Juruaudit.');
        }

        return back()->with('success', 'Draf jawapan, pemerhatian dan ulasan berjaya disimpan.');
    }
}