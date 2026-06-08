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

        if ($borang->ketua_juruaudit_id !== Auth::id()) {
            abort(403, 'Akses Ditolak: Anda bukan pemilik tugasan ini.');
        }

        $senaraiItem = ItemAudit::with('templatKlausa')
                            ->where('borang_audit_id', $borang->id)
                            ->whereNotNull('respon')
                            ->orderBy('no_klausa', 'asc')
                            ->get();

        return view('ketua-juruaudit.semakan', compact('borang', 'senaraiItem'));
    }

    // 2. Paparkan Halaman Khas Edit NCR/OFI
    public function editNcrOfi($borang_id, $item_id)
    {
        $borang = BorangAudit::findOrFail($borang_id);
        $item = ItemAudit::with('templatKlausa')->findOrFail($item_id);

        if ($borang->ketua_juruaudit_id !== Auth::id() || $borang->status == 'selesai') {
            abort(403, 'Akses Ditolak atau Borang telah ditutup.');
        }

        return view('ketua-juruaudit.edit-ncr-ofi', compact('borang', 'item'));
    }

    // 3. Simpan Data NCR/OFI Yang Disunting
    public function updateNcrOfi(Request $request, $borang_id, $item_id)
    {
        $borang = BorangAudit::findOrFail($borang_id);

        if ($borang->ketua_juruaudit_id !== Auth::id() || $borang->status == 'selesai') {
            return redirect()->route('ketua.semakan.show', $borang_id)
                             ->with('error', 'TINDAKAN DITOLAK! Borang ini telah disahkan dan fail telah ditutup. Sebarang suntingan dibatalkan.');
        }

        $item = ItemAudit::findOrFail($item_id);

        $ncrRaw = $request->input('ncr_details', []);
        $ofiRaw = $request->input('ofi_details', []);

        // Tapis array untuk memastikan kotak teks yang dikosongkan tidak disimpan
        $nClean = array_values(array_filter($ncrRaw, function($val) { return !is_null($val) && trim($val) !== ''; }));
        $oClean = array_values(array_filter($ofiRaw, function($val) { return !is_null($val) && trim($val) !== ''; }));

        // KIRA JUMLAH SECARA AUTOMATIK BERDASARKAN ARRAY YANG BERJAYA DITAPIS
        $item->ncr_count = count($nClean);
        $item->ofi_count = count($oClean);

        $item->ncr_details = count($nClean) > 0 ? json_encode($nClean) : null;
        $item->ofi_details = count($oClean) > 0 ? json_encode($oClean) : null;
        
        $item->save();

        return redirect()->route('ketua.semakan.show', $borang_id)
                         ->with('success', 'Rekod NCR/OFI bagi Klausa ' . $item->no_klausa . ' berjaya dikemas kini!');
    }

    // 4. Simpan Pengesahan Semakan Ketua (Tutup Fail)
    public function update(Request $request, $id)
    {
        $borang = BorangAudit::findOrFail($id);

        $request->validate(['pengesahan_ketua' => 'required']);

        $borang->status = 'selesai';
        $borang->save();

        $sesi = \App\Models\SesiAudit::find($borang->sesi_audit_id);
        if ($sesi) {
            $borangBelumSelesai = \App\Models\BorangAudit::where('sesi_audit_id', $sesi->id)
                                    ->where('status', '!=', 'selesai')
                                    ->count();
            
            if ($borangBelumSelesai == 0) {
                $sesi->status = 'selesai';
                $sesi->save();
            }
        }

        return redirect()->route('ketua.semakan.show', $borang->id)
                         ->with('success', 'PENGESAHAN BERJAYA: Borang ditutup dan PDF sedia dicetak!');
    }

    // 5. Jana Laporan PDF
    public function cetakPdf($id)
    {
        $borang = BorangAudit::findOrFail($id);
        
        $senaraiItem = ItemAudit::with('templatKlausa')
                            ->where('borang_audit_id', $borang->id)
                            ->whereNotNull('respon')
                            ->orderBy('no_klausa', 'asc')
                            ->get();

        if ($borang->status !== 'selesai') {
            return back()->with('error', 'Laporan hanya boleh dicetak selepas disahkan (selesai).');
        }

        $pdf = Pdf::loadView('ketua-juruaudit.pdf-laporan', compact('borang', 'senaraiItem'));
        
        // --- UBAH DI SINI: TUKAR PORTRAIT KEPADA LANDSCAPE ---
        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream('Laporan_Audit_'.$borang->bahagian_cawangan.'.pdf');
    }

    // 6. Jana dan muat turun laporan khas PDF NCR/OFI
    public function cetakPdfNcrOfi($id)
    {
        // Tarik borang berserta data juruaudit yang ditugaskan
        $borang = BorangAudit::with('juruauditDitugaskan')->findOrFail($id);
        
        if ($borang->status !== 'selesai') {
            return back()->with('error', 'Laporan NCR/OFI hanya boleh dicetak selepas borang disahkan (selesai).');
        }

        // Hanya tarik Klausa yang mempunyai NCR atau OFI
        $senaraiItem = ItemAudit::with('templatKlausa')
                            ->where('borang_audit_id', $borang->id)
                            ->where(function($query) {
                                $query->where('ncr_count', '>', 0)
                                      ->orWhere('ofi_count', '>', 0);
                            })
                            ->orderBy('no_klausa', 'asc')
                            ->get();

        // Kiraan jumlah keseluruhan
        $totalNcr = $senaraiItem->sum('ncr_count');
        $totalOfi = $senaraiItem->sum('ofi_count');

        // Hantar ke fail view PDF
        $pdf = Pdf::loadView('ketua-juruaudit.pdf-ncr-ofi', compact('borang', 'senaraiItem', 'totalNcr', 'totalOfi'));
        
        // --- UBAH DI SINI: TUKAR PORTRAIT KEPADA LANDSCAPE ---
        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream('Laporan_NCR_OFI_'.$borang->bahagian_cawangan.'.pdf');
    }
}