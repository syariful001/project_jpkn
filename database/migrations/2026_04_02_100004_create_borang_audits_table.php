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
        Schema::create('borang_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sesi_audit_id')->constrained('sesi_audits')->cascadeOnDelete();
            $table->foreignId('ketua_juruaudit_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('juruaudit_ditugaskan_id')->constrained('users')->cascadeOnDelete();
            $table->string('kategori_senarai_semak');
            $table->string('bahagian_cawangan');
            $table->enum('status', ['ditugaskan', 'sedang_diisi', 'siap_disemak'])->default('ditugaskan');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borang_audits');
    }
};
