<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Arahan ini memaksa MySQL untuk menukar kolum status menjadi teks bebas tanpa had perkataan
        DB::statement("ALTER TABLE borang_audits MODIFY status VARCHAR(255) DEFAULT 'ditugaskan'");
    }

    public function down(): void
    {
        //
    }
};