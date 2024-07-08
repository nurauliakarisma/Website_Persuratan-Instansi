<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pengajuan_npd', function (Blueprint $table) {
            $table->char('bagian', 1)->after('alokasi_npd_id');
            $table->integer('kode')->after('bagian')->default(0);
            $table->year('tahun')
                ->default(DB::raw('year(curdate())'))
                ->after('status');
        });
        Schema::table('pengajuan_nodin', function (Blueprint $table) {
            $table->integer('kode')->after('bagian')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('pengajuan_npd')) {
            Schema::table('pengajuan_npd', function (Blueprint $table) {
                $table->dropColumn('bagian');
                $table->dropColumn('kode');
                $table->dropColumn('tahun');
            });
        }

        if (Schema::hasTable('pengajuan_nodin')) {
            Schema::table('pengajuan_nodin', function (Blueprint $table) {
                $table->dropColumn('kode');
            });
        }
    }
};
