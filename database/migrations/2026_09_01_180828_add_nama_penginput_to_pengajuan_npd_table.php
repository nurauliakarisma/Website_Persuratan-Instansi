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
        if (Schema::hasTable('pengajuan_npd')) {
            Schema::table('pengajuan_npd', function (Blueprint $table) {
                $table->string('nama_penginput')->nullable()->after('anggaran');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('pengajuan_npd')) {
            Schema::table('pengajuan_npd', function (Blueprint $table) {
                $table->dropColumn('nama_penginput');
            });
        }
    }
};
