<?php

namespace Database\Seeders;

use App\Models\Keluarga;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KeluargaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Keluarga::create([
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Wahyu Wawan',
            'alamat' => 'Jl. Sidorekso No. 14',
            'desa' => 'Sidorekso',
            'rt' => '8',
            'rw' => '3',
            'no_telp' => '081234567890',
            'puskesmas_id' => 2,
        ]);

        Keluarga::create([
            'nik' => '0000000000000000',
            'nama_lengkap' => 'Bachrul Almond',
            'alamat' => 'Jl. Sidorekso No. 14',
            'desa' => 'Sidorekso',
            'rt' => '2',
            'rw' => '3',
            'no_telp' => '08111111121',
            'puskesmas_id' => 2,
        ]);

        Keluarga::create([
            'nik' => '0987654321098765',
            'nama_lengkap' => 'Farhan Kebab',
            'alamat' => 'Jl. Cempaka Barat 12',
            'desa' => 'Cempaka Barat',
            'rt' => '3',
            'rw' => '2',
            'no_telp' => '081234567890',
            'puskesmas_id' => 3,
        ]);

        Keluarga::create([
            'nik' => '1213141516171819',
            'nama_lengkap' => 'Abdul Dudul',
            'alamat' => 'Jl. Cempaka Barat 12',
            'desa' => 'Cempaka Barat',
            'rt' => '1',
            'rw' => '2',
            'no_telp' => '081234567890',
            'puskesmas_id' => 3,
        ]);

        Keluarga::create([
            'nik' => '9999999999999999',
            'nama_lengkap' => 'Adit Kompressor',
            'alamat' => 'Jl. Cempaka Barat 12',
            'desa' => 'Cempaka Barat',
            'rt' => '3',
            'rw' => '2',
            'no_telp' => '081234567890',
            'puskesmas_id' => 3,
        ]);
    }
}
