<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Arahan paksaan SQL untuk menukar kolum status menjadi teks bebas (VARCHAR 255)
        DB::statement("ALTER TABLE sesi_audits MODIFY status VARCHAR(255) DEFAULT 'dirancang'");
    }

    public function down(): void
    {
        // 
    }
};