<?php

namespace Database\Seeders;

use App\Models\Kabupaten;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KabupatenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Kabupaten::create([
            'nama_kabupaten' => 'Kudus',
        ]);

        Kabupaten::create([
            'nama_kabupaten' => 'Demak',
        ]);

        Kabupaten::create([
            'nama_kabupaten' => 'Pati',
        ]);

        Kabupaten::create([
            'nama_kabupaten' => 'Jepara',
        ]);

        Kabupaten::create([
            'nama_kabupaten' => 'Semarang',
        ]);
    }
}
