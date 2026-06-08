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
    Schema::create('item_audits', function (Blueprint $table) {
        $table->id();
        $table->foreignId('borang_audit_id')->constrained('borang_audits')->cascadeOnDelete();
        $table->foreignId('templat_klausa_id')->nullable()->constrained('templat_klausas')->nullOnDelete();
        $table->string('no_klausa');
        $table->text('perkara_periksa');
        $table->string('rujukan')->nullable();
        
        // Input dari Juruaudit
        $table->string('nama_auditee')->nullable();
        $table->enum('respon', ['Ya', 'Tidak', 'TB'])->nullable();
        $table->text('ulasan')->nullable();
        $table->string('url_bukti')->nullable(); // Untuk simpan nama fail gambar/pdf
        $table->timestamps();
        $table->integer('ncr_count')->default(0);
        $table->integer('ofi_count')->default(0);
        $table->text('ncr_details')->nullable();
        $table->text('ofi_details')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_audits');
    }
};
