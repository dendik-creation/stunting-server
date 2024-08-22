<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PenyakitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $penyakit = [
            [
                "nama_penyakit" => "TB Anak",
                "jenis_penyakit" => "penyerta"
            ],
            [
                "nama_penyakit" => "Pneunomia",
                "jenis_penyakit" => "penyerta"
            ],
            [
                "nama_penyakit" => "HIV",
                "jenis_penyakit" => "penyerta"
            ],
            [
                "nama_penyakit" => "ISPA",
                "jenis_penyakit" => "penyerta"
            ],
            [
                "nama_penyakit" => "Diare",
                "jenis_penyakit" => "penyerta"
            ],
            [
                "nama_penyakit" => "Hiperemesis gravidarum (mual muntah saat hamil)",
                "jenis_penyakit" => "komplikasi"
            ],
            [
                "nama_penyakit" => "Hipertensi Kehamilan",
                "jenis_penyakit" => "komplikasi"
            ],
            [
                "nama_penyakit" => "Diabetes Militus Kehamilan",
                "jenis_penyakit" => "komplikasi"
            ],
            [
                "nama_penyakit" => "Anemia",
                "jenis_penyakit" => "komplikasi"
            ],
        ];

        foreach ($penyakit as $key => $value) {
            \App\Models\Penyakit::create($value);
        }
    }
}
