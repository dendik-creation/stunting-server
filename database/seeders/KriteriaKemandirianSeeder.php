<?php

namespace Database\Seeders;

use App\Models\KriteriaKemandirian;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KriteriaKemandirianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [
            'Apakah anda sebagai kepala keluarga/perwakilan keluarga menerima kunjungan petugas kesehatan saat melakukan kunjungan rumah',
            'Apakah anda sebagai kepala keluarga/perwakilan keluarga menerima pelayanan keperawatan yang diberikan sesuai dengan pola asuhan keperawatan dirumah',
            'Apakah anda sebagai kepala keluarga/perwakilan keluarga tau dan dapat mengungkapkan atau menceritakan masalah kesehatan yang dialami keluarga secara benar',
            'Apakah anda sebagai kepala keluarga/perwakilan keluarga dapat memanfaatkan fasilitas pelayanan kesehatan secara aktif',
            'Apakah anda sebagai kepala keluarga/perwakilan keluarga dapat memanfaatkan perawatan kesehatan sederhana sesuai dengan anjuran petugas kesehatan',
            'Apakah anda sebagai kepala keluarga/perwakilan keluarga dapat melakukan tindakan pencegahan secara aktif',
            'Apakah anda sebagai kepala keluarga/perwakilan keluarga menginformasikan cara mengatasi masalah kesehatan yang baik bagi semua anggota keluarga',
        ];

        foreach ($questions as $item){
            KriteriaKemandirian::create([
                'pertanyaan' => $item
            ]);
        }
    }
}
