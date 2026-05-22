<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemAudit extends Model
{
    // Membenarkan semua kolum disimpan terus ke pangkalan data
    protected $guarded = ['id'];

    /**
     * Relasi: Setiap Item Audit dimiliki oleh satu Borang Audit
     */
    public function borangAudit()
    {
        return $this->belongsTo(BorangAudit::class, 'borang_audit_id');
    }

    /**
     * Relasi: Setiap Item Audit merujuk kepada satu Templat Klausa (Bank Soalan)
     * Ini digunakan untuk menarik data tajuk_klausa dan tajuk_sub_klausa
     */
    public function templatKlausa()
    {
        return $this->belongsTo(TemplatKlausa::class, 'templat_klausa_id');
    }
}