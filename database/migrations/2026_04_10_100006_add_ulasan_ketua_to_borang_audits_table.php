<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('borang_audits', function (Blueprint $table) {
            // Menambah ruangan ulasan ketua selepas kolum status
            $table->text('ulasan_ketua')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borang_audits', function (Blueprint $table) {
            //
        });
    }
};
