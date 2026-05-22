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
        Schema::create('sesi_audits', function (Blueprint $table) {
            $table->id();
            // ID Ketua Juruaudit yang mencipta sesi ini
            $table->foreignId('pencipta_id')->constrained('users')->cascadeOnDelete(); 
            
            $table->string('tajuk_sesi'); // Contoh: "Audit Dalaman JPKN Bil 1/2026"
            $table->date('tarikh_mula');
            $table->date('tarikh_tamat');
            $table->enum('status', ['dirancang', 'sedang_berjalan', 'selesai'])->default('dirancang');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesi_audits');
    }
};
