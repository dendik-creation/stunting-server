<?php

namespace Database\Seeders;

use App\Models\KriteriaKesehatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KriteriaKesehatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kriteria = [
            // 1
            [
                'riteria' => 'Tidak ada',
                'nilai' => 0,
                'komponen_id' => 1,
            ],
            [
                'riteria' => 'Ada, bukan milik sendiri, berbau, berwarna dan berasa',
                'nilai' => 1,
                'komponen_id' => 1,
            ],
            [
                'riteria' => 'Ada, milik sendiri, berbau, berwarna dan berasa',
                'nilai' => 2,
                'komponen_id' => 1,
            ],
            [
                'riteria' => 'Ada, milik sendiri, tidak berbau, tidak berwarna, tidak berasa',
                'nilai' => 3,
                'komponen_id' => 1,
            ],
            [
                'riteria' => 'Ada, bukan milik sendiri, tidak berbau, tidak berwarna, tidak berasa',
                'nilai' => 4,
                'komponen_id' => 1,
            ],

            // 2
            [
                "riteria" => "Tidak ada",
                "nilai" => 0,
                "komponen_id" => 2
            ],
            [
                "riteria" => "Ada, bukan leher angsa, tidak ada tutup, disalurkan kesungai/kolam",
                "nilai" => 1,
                "komponen_id" => 2
            ],
            [
                "riteria" => "Ada, bukan leher angsa, ada tutup, disalurkan kesungai atau kekolam",
                "nilai" => 2,
                "komponen_id" => 2
            ],
            [
                "riteria" => "Ada, bukan leher angsa, ada tutup, septic tank",
                "nilai" => 3,
                "komponen_id" => 2
            ],
            [
                "riteria" => "Ada, leher angsa, septic tank",
                "nilai" => 4,
                "komponen_id" => 2
            ],

            // 3
            [
                "riteria" => "Tidak ada, sehingga tergenang tidak teratur dihalaman",
                "nilai" => 0,
                "komponen_id" => 3
            ],
            [
                "riteria" => "Ada, diresapkan tetapi mencemari sumber air (jarak sumber air jarak dari sumber < 10 meter)",
                "nilai" => 1,
                "komponen_id" => 3
            ],
            [
                "riteria" => "Ada, dialirkan keselokan terbuka",
                "nilai" => 2,
                "komponen_id" => 3
            ],
            [
                "riteria" => "Ada, diresapkan dan tidak mencemari sumber air (jarak dengan sumber air > 10 meter)",
                "nilai" => 3,
                "komponen_id" => 3
            ],
            [
                "riteria" => "Ada, dialirkan keselokan tertutup (saluran kota) untuk diolah lebih lanjut",
                "nilai" => 4,
                "komponen_id" => 3
            ],

            // 4
            [
                "riteria" => "Tidak ada",
                "nilai" => 0,
                "komponen_id" => 4
            ],
            [
                "riteria" => "Ada, tetapi tidak kedap air",
                "nilai" => 1,
                "komponen_id" => 4
            ],
            [
                "riteria" => "Ada, kedap air dan tidak bertutup",
                "nilai" => 2,
                "komponen_id" => 4
            ],
            [
                "riteria" => "Ada, kedap air dan bertutup",
                "nilai" => 3,
                "komponen_id" => 4
            ]
        ];

        foreach ($kriteria as $item){
            KriteriaKesehatan::create([
                'komponen_kesehatan_id' => $item['komponen_id'],
                'kriteria' => $item['riteria'],
                'nilai' => $item['nilai'],
            ]);
        }
    }
}
