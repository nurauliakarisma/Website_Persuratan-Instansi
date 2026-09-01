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
        Schema::table('pengajuan_publikasi', function (Blueprint $table) {
            $table->string('nama_penginput')->nullable()->after('nominal_fotocopy');
            $table->text('catatan_penolakan')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_publikasi', function (Blueprint $table) {
            $table->dropColumn(['nama_penginput', 'catatan_penolakan']);
        });
    }
};
