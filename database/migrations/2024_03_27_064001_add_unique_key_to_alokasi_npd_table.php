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
        Schema::table('alokasi_npd', function (Blueprint $table) {
            $table->unique(['bagian', 'subkegiatan_id', 'rincian_belanja_id'], 'unique_alokasi_rincian_kegiatan_bagian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alokasi_npd', function (Blueprint $table) {
            $table->dropUnique('unique_alokasi_rincian_kegiatan_bagian');
        });
    }
};
