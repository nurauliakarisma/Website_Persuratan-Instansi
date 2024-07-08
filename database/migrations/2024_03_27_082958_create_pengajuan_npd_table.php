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
        Schema::create('pengajuan_npd', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alokasi_npd_id')->references('id')->on('alokasi_npd')->cascadeOnDelete();
            $table->string('nomor', 50);
            $table->date('tanggal_pengajuan');
            $table->text('uraian_kegiatan');
            $table->integer('anggaran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_npd');
    }
};
