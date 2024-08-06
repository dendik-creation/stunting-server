<?php

namespace Database\Seeders;

use App\Models\Puskesmas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PuskesmasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Puskesmas::create([
            'nama_puskesmas' => 'Puskesmas Cempaka',
            'alamat' => 'Jl. Cempaka 12',
        ]);

        Puskesmas::create([
            'nama_puskesmas' => 'Puskesmas Cempaka Timur',
            'alamat' => 'Jl. Cempaka Timur 12',
        ]);

        Puskesmas::create([
            'nama_puskesmas' => 'Puskesmas Cempaka Barat',
            'alamat' => 'Jl. Cempaka Barat 12',
        ]);

        Puskesmas::create([
            'nama_puskesmas' => 'Puskesmas Cempaka Selatan',
            'alamat' => 'Jl. Cempaka Selatan 12',
        ]);
    }
}
