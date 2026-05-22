<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('templat_klausas', function (Blueprint $table) {
            // Menambah kolum baharu selepas no_klausa
            $table->string('tajuk_klausa')->nullable()->after('no_klausa');
            $table->string('tajuk_sub_klausa')->nullable()->after('tajuk_klausa');
            $table->text('deskripsi')->nullable()->after('tajuk_sub_klausa');
        });
    }

    public function down(): void
    {
        Schema::table('templat_klausas', function (Blueprint $table) {
            $table->dropColumn(['tajuk_klausa', 'tajuk_sub_klausa', 'deskripsi']);
        });
    }
};