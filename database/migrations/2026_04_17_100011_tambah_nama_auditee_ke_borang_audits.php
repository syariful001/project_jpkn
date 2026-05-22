<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('borang_audits', function (Blueprint $table) {
            // Menambah kolum nama_auditee selepas kolum juruaudit_ditugaskan_id
            $table->string('nama_auditee')->nullable()->after('juruaudit_ditugaskan_id');
        });
    } 

    public function down(): void
    {
        Schema::table('borang_audits', function (Blueprint $table) {
            $table->dropColumn('nama_auditee');
        });
    }
};
