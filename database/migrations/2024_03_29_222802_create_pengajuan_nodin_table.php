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
        Schema::create('pengajuan_nodin', function (Blueprint $table) {
            $table->id();
            $table->char('bagian', 1);
            $table->string('nomor', 50)->unique();
            $table->foreignId('index_kegiatan_id')->references('id')->on('index_kegiatan')->cascadeOnDelete();
            $table->foreignId('subkegiatan_id')->references('id')->on('subkegiatan')->cascadeOnDelete();
            $table->foreignId('rincian_belanja_id')->references('id')->on('rincian_belanja')->cascadeOnDelete();
            $table->text('perihal');
            $table->date('tanggal_pengajuan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->text('atas_nama');
            $table->string('nama_penginput', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_nodin');
    }
};
