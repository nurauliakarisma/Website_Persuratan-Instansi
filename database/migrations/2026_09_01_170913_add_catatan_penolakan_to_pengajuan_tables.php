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
                $table->text('catatan_penolakan')->nullable()->after('status');
            });
        }

        if (Schema::hasTable('pengajuan_nodin')) {
            Schema::table('pengajuan_nodin', function (Blueprint $table) {
                $table->text('catatan_penolakan')->nullable()->after('status');
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
                $table->dropColumn('catatan_penolakan');
            });
        }

        if (Schema::hasTable('pengajuan_nodin')) {
            Schema::table('pengajuan_nodin', function (Blueprint $table) {
                $table->dropColumn('catatan_penolakan');
            });
        }
    }
};
