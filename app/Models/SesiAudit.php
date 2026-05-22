<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SesiAudit extends Model
{
    // Benarkan semua kolum diisi kecuali ID
    protected $guarded = ['id'];

    public function senaraiBorang()
    {
        return $this->hasMany(BorangAudit::class, 'sesi_audit_id');
    }
}

