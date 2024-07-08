<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert(
            [
                'nama' => 'Super Admin',
                'email' => 'superadmin@gmail.com',
                'password' => \Hash::make('password123'),
                'tipe' => 'Super Admin',
            ],
            [
                'nama' => 'Admin A',
                'email' => 'admina@gmail.com',
                'password' => \Hash::make('password123'),
                'tipe' => 'Admin A',
            ],
            [
                'nama' => 'Admin B',
                'email' => 'adminb@gmail.com',
                'password' => \Hash::make('password123'),
                'tipe' => 'Admin B',
            ],
        );
    }
}
