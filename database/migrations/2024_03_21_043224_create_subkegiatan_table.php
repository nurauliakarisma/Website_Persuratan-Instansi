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
        Schema::create('subkegiatan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_program', 20);
            $table->string('ket_program', 255);
            $table->string('kode_kegiatan', 20);
            $table->string('ket_kegiatan', 255);
            $table->string('kode_subkegiatan', 20);
            $table->string('ket_subkegiatan', 255);
            $table->unique(['kode_program', 'kode_kegiatan', 'kode_subkegiatan'], 'unique_subkegiatan_kode');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subkegiatan');
    }
};
