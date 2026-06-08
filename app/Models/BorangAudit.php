<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorangAudit extends Model
{
    // Benarkan semua kolum diisi kecuali ID
    protected $guarded = ['id'];

    public function namaJuruaudit()
    {
        return $this->belongsTo(User::class, 'juruaudit_ditugaskan_id');
    }

    public function juruauditDitugaskan()
    {
        return $this->belongsTo(User::class, 'juruaudit_ditugaskan_id');
    }
}

