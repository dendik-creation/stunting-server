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
        $kudusPuskesmas = [
            "Kaliwungu",
            "Sidorekso",
            "Wergu Wetan",
            "Purwosari",
            "Rendeng",
            "Jati",
            "Ngembal Kulon",
            "Undaan",
            "Ngemplak",
            "Mejobo",
            "Jepang",
            "Jekulo",
            "Tanjungrejo",
            "Bae",
            "Dersalam",
            "Gribig",
            "Gondosari",
            "Dawe",
            "Rejosari"
        ];

        foreach ($kudusPuskesmas as $key => $value) {
            Puskesmas::create([
                'nama_puskesmas' => $value,
                'alamat' => 'Jl. ' . $value . ' No '. rand(10, 99),
                'kabupaten_id' => 1,
            ]);
        }
    }
}
