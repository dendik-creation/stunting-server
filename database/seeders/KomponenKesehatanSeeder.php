<?php

namespace Database\Seeders;

use App\Models\KomponenKesehatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KomponenKesehatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $komponen = [
            'Sarana air bersih',
            'Jamban (sarana pembuangan kotoran)',
            'Sarana pembuangan air limbah (SPAL)',
            'Sarana pembuangan sampah',
        ];
        foreach ($komponen as $item){
            KomponenKesehatan::create([
                'nama_komponen' => $item,
            ]);
        }
    }
}
