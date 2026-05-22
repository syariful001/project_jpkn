<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TemplatKlausa extends Model
{
    /**
     * Nama jadual jika tidak mengikut piawaian plural Laravel.
     * Secara lalai, Laravel akan mencari jadual 'templat_klausas'.
     */
    protected $table = 'templat_klausas';

    /**
     * Membenarkan semua kolum diisi kecuali 'id'.
     * Ini sangat memudahkan penambahan data secara pukal (Mass Assignment).
     */
    protected $guarded = ['id'];

    /**
     * Hubungan: Setiap klausa dicipta oleh seorang Pentadbir Sistem.
     * Ini merujuk kepada kolum 'pentadbir_sistem_id' yang kita bincangkan tadi.
     */
    public function pentadbirSistem(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pentadbir_sistem_id');
    }

    /**
     * Hubungan: Satu Klausa boleh digunakan dalam banyak Item Audit.
     * Ini membolehkan kita melihat semua rekod audit yang menggunakan klausa ini.
     */
    public function itemAudits(): HasMany
    {
        return $this->hasMany(ItemAudit::class, 'templat_klausa_id');
    }
}