<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SesiAudit;
use App\Models\BorangAudit;

class AdminPengurusanController extends Controller
{
    // ==========================================
    // PENGURUSAN SESI AUDIT
    // ==========================================
    public function editSesi($id)
    {
        $sesi = SesiAudit::findOrFail($id);
        return view('admin-edit-sesi', compact('sesi'));
    }

    public function updateSesi(Request $request, $id)
    {
        $request->validate([
            'tajuk_sesi' => 'required|string|max:255',
            'tarikh_mula' => 'required|date',
            'tarikh_tamat' => 'required|date|after_or_equal:tarikh_mula',
            'status' => 'required|string'
        ]);

        $sesi = SesiAudit::findOrFail($id);
        $sesi->tajuk_sesi = $request->tajuk_sesi;
        $sesi->tarikh_mula = $request->tarikh_mula;
        $sesi->tarikh_tamat = $request->tarikh_tamat;
        $sesi->status = $request->status;
        $sesi->save();

        return redirect()->route('admin.dashboard')->with('success', 'Sesi Audit berjaya dikemas kini!');
    }

    public function destroySesi($id)
    {
        $sesi = SesiAudit::findOrFail($id);
        // Memadam borang yang bernaung di bawah sesi ini terlebih dahulu (Cascade Delete)
        BorangAudit::where('sesi_audit_id', $sesi->id)->delete();
        $sesi->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Sesi Audit berserta borang di dalamnya telah dipadam!');
    }

// ==========================================
    // PENGURUSAN BORANG AUDIT (EDIT & DELETE)
    // ==========================================
    public function editBorang($id)
    {
        $borang = BorangAudit::findOrFail($id);
        return view('admin-edit-borang', compact('borang'));
    }

    public function updateBorang(Request $request, $id)
    {
        $request->validate([
            'bahagian_cawangan' => 'required|string|max:255',
            'status' => 'required|string'
        ]);

        $borang = BorangAudit::findOrFail($id);
        $borang->bahagian_cawangan = $request->bahagian_cawangan;
        $borang->nama_auditee = $request->nama_auditee;
        $borang->status = $request->status;
        
        // [KEMASKINI BARU] Matikan kemaskini automatik lajur 'updated_at'
        $borang->timestamps = false; 
        
        $borang->save();

        return redirect()->route('admin.dashboard')->with('success', 'Maklumat Borang Audit berjaya dikemas kini.');
    }

    public function destroyBorang($id)
    {
        $borang = BorangAudit::findOrFail($id);
        $borang->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Borang Audit telah dipadam dari sistem');
    }
}