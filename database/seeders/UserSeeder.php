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
            'nama_lengkap' => 'Admin Indonesia',
            'password' => Hash::make('admin1234'),
            'role' => 'admin',
        ]);

        User::create([
            'username' => 'dinas1',
            'nama_lengkap' => 'Dinas 1',
            'password' => Hash::make('12345'),
            'role' => 'dinas',
        ]);

        User::create([
            'username' => 'dinas2',
            'nama_lengkap' => 'Dinas 2',
            'password' => Hash::make('12345'),
            'role' => 'dinas',
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
