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
        Schema::table('alokasi_npd', function (Blueprint $table) {
            $table->year('tahun')
                ->default(DB::raw('year(curdate())'))
                ->after('total_anggaran');
        });

        Schema::table('pengajuan_npd', function (Blueprint $table) {
            $table->enum('status', ['Diajukan', 'Disetujui', 'Ditolak'])
                ->default('Diajukan')
                ->after('anggaran');
        });

        Schema::table('pengajuan_nodin', function (Blueprint $table) {
            $table->year('tahun')
                ->default(DB::raw('year(curdate())'))
                ->after('nama_penginput');
            $table->enum('status', ['Diajukan', 'Disetujui', 'Ditolak'])
                ->default('Diajukan')
                ->after('tahun');
            $table->string('subject', 255)->after('rincian_belanja_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('alokasi_npd')) {
            Schema::table('alokasi_npd', function (Blueprint $table) {
                $table->dropColumn('tahun');
            });
        }

        if (Schema::hasTable('pengajuan_npd')) {
            Schema::table('pengajuan_npd', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }

        if (Schema::hasTable('pengajuan_nodin')) {
            Schema::table('pengajuan_nodin', function (Blueprint $table) {
                $table->dropColumn('status');
                $table->dropColumn('subject');
                $table->dropColumn('tahun');
            });
        }
    }
};
