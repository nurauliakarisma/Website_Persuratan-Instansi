<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'nama' => 'Super Admin',
                'email' => 'superadmin@gmail.com',
                'password' => \Hash::make('password123'),
                'tipe' => 'Super Admin',
                'nip' => '198001012005011001',
                'jabatan' => 'Kepala Bagian Umum',
            ],
            [
                'nama' => 'Admin Dokinfo',
                'email' => 'admina@gmail.com',
                'password' => \Hash::make('password123'),
                'tipe' => 'Admin A',
                'nip' => '198403122008011002',
                'jabatan' => 'Kasubag Dokumentasi & Informasi',
            ],
            [
                'nama' => 'Admin FPP',
                'email' => 'adminb@gmail.com',
                'password' => \Hash::make('password123'),
                'tipe' => 'Admin B',
                'nip' => '198605152009021003',
                'jabatan' => 'Kasubag Fasilitasi Penganggaran & Pengawasan',
            ],
            [
                'nama' => 'Qc Aulia Karisma, S.STP',
                'email' => 'staff@gmail.com',
                'password' => \Hash::make('password123'),
                'tipe' => 'Staff',
                'nip' => '199108162015031001',
                'jabatan' => 'Pengadministrasi Persuratan',
            ],
        ]);
    }
}
