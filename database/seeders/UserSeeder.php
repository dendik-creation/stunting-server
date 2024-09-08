<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username' => 'admin',
            'nama_lengkap' => 'Admin Stunting',
            'password' => Hash::make('admin1234'),
            'role' => 'admin',
        ]);

        User::create([
            'username' => 'dinas_kudus',
            'nama_lengkap' => 'Dinas Kudus',
            'password' => Hash::make('12345'),
            'role' => 'dinas',
            'kabupaten_id' => 1,
        ]);

        User::create([
            'username' => 'dinas_demak',
            'nama_lengkap' => 'Dinas Demak',
            'password' => Hash::make('12345'),
            'role' => 'dinas',
            'kabupaten_id' => 2,
        ]);

        User::create([
            'username' => 'wildan',
            'nama_lengkap' => 'Wildan Mewing',
            'password' => Hash::make('12345'),
            'role' => 'operator',
            'puskesmas_id' => 2,
        ]);

        User::create([
            'username' => 'agus',
            'nama_lengkap' => 'Agus Bagus',
            'password' => Hash::make('12345'),
            'role' => 'operator',
            'puskesmas_id' => 3,
        ]);

        User::create([
            'username' => 'dimas',
            'nama_lengkap' => 'Muhammad Dimas Mewing',
            'password' => Hash::make('12345'),
            'role' => 'operator',
            'puskesmas_id' => 4,
        ]);

        User::create([
            'username' => 'prengki',
            'nama_lengkap' => 'Prengki Mendoan',
            'password' => Hash::make('12345'),
            'role' => 'operator',
            'puskesmas_id' => 5,
        ]);
    }
}
