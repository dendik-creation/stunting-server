<?php

namespace App\Helpers;

use App\Models\TingkatKemandirian;
use Carbon\Carbon;

class KemandirianHelper
{
    public static function calculateAnswertoTingkatan(int $answer_count, string|int $keluarga_id)
    {
        $tingkatan = 0;
        if ($answer_count <= 2) {
            $tingkatan = 1;
        } elseif ($answer_count <= 5) {
            $tingkatan = 2;
        } elseif ($answer_count <= 6) {
            $tingkatan = 3;
        } elseif ($answer_count <= 7) {
            $tingkatan = 4;
        }
        if($tingkatan != 0){
            TingkatKemandirian::create([
                'tingkatan' => $tingkatan,
                'tanggal' => date('Y-m-d'),
                'keluarga_id' => $keluarga_id,
            ]);
            return true;
        }
        return false;
    }

    public static function availableToAnswer($keluarga_id){
        $tingkatan = TingkatKemandirian::where('keluarga_id', $keluarga_id)->latest()->first();
        $now = Carbon::now();

        // 4 Week gap by first data to second data
        if(!empty($tingkatan->tanggal)){
            $first_date = Carbon::parse($tingkatan->tanggal);
            if(!$now->diffInWeeks($first_date) == 4) {
                return [
                    'status' => false,
                    'message' => 'Anda bisa menjawab kriteria kemandirian kembali pada ' . $first_date->addWeeks(4)->format('d F Y'),
                ];
            }
        }
        // True if last data is 4 week gap
        return [
            'status' => true,
        ];
    }
}
