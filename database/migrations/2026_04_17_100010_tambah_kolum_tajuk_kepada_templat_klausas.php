<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('templat_klausas', function (Blueprint $table) {
            // Menambah 3 kolum baharu khas untuk format ISO
            if (!Schema::hasColumn('templat_klausas', 'tajuk_klausa')) {
                $table->string('tajuk_klausa')->nullable()->after('no_klausa');
            }
            if (!Schema::hasColumn('templat_klausas', 'tajuk_sub_klausa')) {
                $table->string('tajuk_sub_klausa')->nullable()->after('tajuk_klausa');
            }
            if (!Schema::hasColumn('templat_klausas', 'deskripsi')) {
                $table->text('deskripsi')->nullable()->after('tajuk_sub_klausa');
            }
        });
    }

    public function down(): void
    {
        Schema::table('templat_klausas', function (Blueprint $table) {
            $table->dropColumn(['tajuk_klausa', 'tajuk_sub_klausa', 'deskripsi']);
        });
    }
};