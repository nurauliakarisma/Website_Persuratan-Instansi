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
        Schema::create('alokasi_npd', function (Blueprint $table) {
            $table->id();
            $table->char('bagian', 1);
            $table->foreignId('subkegiatan_id')->references('id')->on('subkegiatan')->cascadeOnDelete();
            $table->foreignId('rincian_belanja_id')->references('id')->on('rincian_belanja')->cascadeOnDelete();
            $table->integer('total_anggaran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alokasi_npd');
    }
};
