<?php

namespace App\Helpers;

use App\Models\AnakSakit;
use App\Models\Keluarga;
use App\Models\KesehatanLingkungan;
use App\Models\TingkatKemandirian;
use Carbon\Carbon;

class ScreeningTestHelper
{
    public static function calculateAnswertoTingkatan(int $answer_count, string|int $tingkat_kemandirian_id)
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
            $tingkat_kemandirian = TingkatKemandirian::findOrFail($tingkat_kemandirian_id);
            $tingkat_kemandirian->update([
                'tingkatan' => $tingkatan
            ]);
            return true;
        }
        return false;
    }

    public static function getCurrentScreening($data): int
    {
    $tingkatKemandirian = optional($data->tingkat_kemandirian->last());
    $kesehatanLingkungan = optional($data->kesehatan_lingkungan->last());

    $tingkatKemandirianStep = $tingkatKemandirian->step;
    $tingkatKemandirianTanggal = $tingkatKemandirian->tanggal;

    $kesehatanLingkunganStep = $kesehatanLingkungan->step;

    if ($tingkatKemandirianStep === $kesehatanLingkunganStep) {
        if (Carbon::parse($tingkatKemandirianTanggal)->addWeeks(4)->isPast()) {
            return ($tingkatKemandirianStep ?? 0) + 1;
        }
        return $tingkatKemandirianStep ?? 0;
    }

    return max($tingkatKemandirianStep ?? 0, $kesehatanLingkunganStep ?? 0);
    }

    public static function currentTestStatus($data){
        $current_screening = self::getCurrentScreening($data);
        $response = [
            'tingkat_kemandirian' => false,
            'kesehatan_lingkungan' => false,
        ];
        foreach ($response as $key => $value) {
            if($current_screening != null){
                $response[$key] = $data->{$key}?->last()?->step == $current_screening;
            }
        }
        return $response;
    }

    public static function getTestResult($data)
    {
        $current_screening = self::getCurrentScreening($data);
        $result = [
            'tingkat_kemandirian' => null,
            'kesehatan_lingkungan' => null,
        ];

        foreach ($result as $test => &$value) {
            if ($data->$test->last() && $data->$test->last()->step == $current_screening) {
                $value = $data->$test->last();
            }
        }

        return $result;
    }

    public static function compareTingkatKemandirian($data){
        $keluarga = Keluarga::findOrFail($data['id']);
        $keluarga->update([
            'is_test_done' => 1,
        ]);
        if(intval($data->tingkat_kemandirian[1]->tingkatan) > intval($data->tingkat_kemandirian[0]->tingkatan) || intval($data->tingkat_kemandirian[1]->tingkatan) == 4){
            $keluarga->update(['is_free_stunting' => 1]);
            return [
                'status' => true,
                'message' => 'Selamat Anda berhasil menyelesaikan tes yang telah dilaksanakan.'
            ];
        }else if(intval($data->tingkat_kemandirian[1]->tingkatan) == intval($data->tingkat_kemandirian[0]->tingkatan)){
            return [
                'status' => false,
                'message' => 'Sayang sekali hasil tes Anda tidak mengalami peningkatan sekali.'
            ];
        }else{
            return [
                'status' => false,
                'message' => 'Anda dinyatakan tidak berhasil dalam mengikuti serangkaian tes yang telah dilaksanakan.'
            ];
        }
    }

    public static function completeCheck($data){
        $response = [
            'status' => false,
        ];
        $current_screening = self::getCurrentScreening($data);
        $all_completed = !array_search(false, self::currentTestStatus($data));
        if($all_completed || count($data->tingkat_kemandirian) == 2 ){
            $response['status'] = true;
            if(count($data->tingkat_kemandirian) == 2){
                $response['is_done'] = true;
                $response['is_good'] = ScreeningTestHelper::compareTingkatKemandirian($data)['status'];
                $response['done_message'] = ScreeningTestHelper::compareTingkatKemandirian($data)['message'];
            }else{
                $response['next_test'] = Carbon::parse($data?->kesehatan_lingkungan?->last()?->tanggal)->addWeeks(4)->format('Y-m-d');
            }
        }
        return $response;
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
