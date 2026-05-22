<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BorangAudit;
use App\Models\ItemAudit;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class KetuaSemakanController extends Controller
{
    // 1. Paparkan borang yang telah diisi untuk disemak
    public function show($id)
    {
        $borang = BorangAudit::findOrFail($id);

        // Pastikan hanya Ketua yang mencipta borang ini boleh melihatnya
        if ($borang->ketua_juruaudit_id !== Auth::id()) {
            abort(403, 'Akses Ditolak: Anda bukan pemilik tugasan ini.');
        }

        // TARIK JAWAPAN: Hanya ambil item yang mempunyai 'respon' (telah diisi)
        $senaraiItem = ItemAudit::with('templatKlausa')
                            ->where('borang_audit_id', $borang->id)
                            ->whereNotNull('respon') // <--- FILTER DITAMBAH DI SINI
                            ->orderBy('no_klausa', 'asc')
                            ->get();

        return view('ketua-juruaudit.semakan', compact('borang', 'senaraiItem'));
    }

    // 2. Simpan Keputusan Semakan Ketua
    public function update(Request $request, $id)
    {
        $request->validate([
            'ulasan_ketua' => 'required|string',
        ]);

        // Cari borang
        $borang = BorangAudit::findOrFail($id);

        // Simpan ulasan.
        $borang->ulasan_ketua = $request->ulasan_ketua;
        $borang->status = 'selesai';
        $borang->save();

        // Selaraskan jadual Sesi Utama
        $sesi = \App\Models\SesiAudit::find($borang->sesi_audit_id);
        if ($sesi) {
            // Semak jika semua borang di bawah sesi ini telah selesai
            $borangBelumSelesai = \App\Models\BorangAudit::where('sesi_audit_id', $sesi->id)
                                    ->where('status', '!=', 'selesai')
                                    ->count();
            
            if ($borangBelumSelesai == 0) {
                $sesi->status = 'selesai';
                $sesi->save();
            }
        }

        // Bawa pengguna KEMBALI ke halaman yang sama
        return redirect()->route('ketua.semakan.show', $borang->id)
                         ->with('success', 'PENGESAHAN BERJAYA: Borang telah ditutup dan PDF sedia dicetak!');
    }

    // 3. Jana dan muat turun laporan PDF
    public function cetakPdf($id)
    {
        $borang = BorangAudit::findOrFail($id);
        
        // PASTIKAN PDF JUGA HANYA MEMAPARKAN KLAUSA YANG DIISI
        $senaraiItem = ItemAudit::with('templatKlausa')
                            ->where('borang_audit_id', $borang->id)
                            ->whereNotNull('respon') // <--- FILTER DITAMBAH DI SINI
                            ->orderBy('no_klausa', 'asc')
                            ->get();

        // Pastikan hanya borang yang dah 'selesai' boleh dicetak
        if ($borang->status !== 'selesai') {
            return back()->with('error', 'Laporan hanya boleh dicetak selepas disahkan (selesai).');
        }

        // Hantar data ke fail pandangan (view) khas PDF
        $pdf = Pdf::loadView('ketua-juruaudit.pdf-laporan', compact('borang', 'senaraiItem'));
        
        // Tetapkan saiz kertas A4
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Laporan_Audit_'.$borang->bahagian_cawangan.'.pdf');
    }
}