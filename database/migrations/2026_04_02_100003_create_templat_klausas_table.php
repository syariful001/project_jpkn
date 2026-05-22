<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('templat_klausas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pentadbir_sistem_id')->constrained('pentadbir_sistems')->cascadeOnDelete();
        $table->string('no_klausa');
        $table->text('perkara_periksa');
        $table->string('rujukan')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('templat_klausas');
    }
};
