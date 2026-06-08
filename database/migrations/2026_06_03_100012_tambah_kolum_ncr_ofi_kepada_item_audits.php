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
        Schema::table('item_audits', function (Blueprint $table) {
            // Semak dan tambah jika belum wujud
            if (!Schema::hasColumn('item_audits', 'ncr_count')) {
                $table->integer('ncr_count')->default(0)->after('rujukan');
            }
            if (!Schema::hasColumn('item_audits', 'ofi_count')) {
                $table->integer('ofi_count')->default(0)->after('ncr_count');
            }
            if (!Schema::hasColumn('item_audits', 'ncr_details')) {
                $table->text('ncr_details')->nullable()->after('ofi_count');
            }
            if (!Schema::hasColumn('item_audits', 'ofi_details')) {
                $table->text('ofi_details')->nullable()->after('ncr_details');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_audits', function (Blueprint $table) {
            if (Schema::hasColumn('item_audits', 'ncr_count')) {
                $table->dropColumn('ncr_count');
            }
            if (Schema::hasColumn('item_audits', 'ofi_count')) {
                $table->dropColumn('ofi_count');
            }
            if (Schema::hasColumn('item_audits', 'ncr_details')) {
                $table->dropColumn('ncr_details');
            }
            if (Schema::hasColumn('item_audits', 'ofi_details')) {
                $table->dropColumn('ofi_details');
            }
        });
    }
};