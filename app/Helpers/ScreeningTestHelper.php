<?php

namespace App\Helpers;

use App\Models\AnakSakit;
use App\Models\KesehatanLingkungan;
use App\Models\TingkatKemandirian;
use Carbon\Carbon;

class ScreeningTestHelper
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
            $step_tingkatan = TingkatKemandirian::where('keluarga_id', $keluarga_id)->count();
            TingkatKemandirian::create([
                'tingkatan' => $tingkatan,
                'tanggal' => date('Y-m-d'),
                'keluarga_id' => $keluarga_id,
                'step' => $step_tingkatan + 1
            ]);
            return true;
        }
        return false;
    }

    public static function getCurrentScreening($data){
        return max(
            optional($data->tingkat_kemandirian->last())->step,
            optional($data->anak_sakit->last())->step,
            optional($data->kesehatan_lingkungan->last())->step
        ) ?? null;
    }

    public static function currentTestStatus($data){
        $current_screening = self::getCurrentScreening($data);
        $response = [
            'tingkat_kemandirian' => false,
            'anak_sakit' => false,
            'kesehatan_lingkugan' => false,
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
            'anak_sakit' => null,
            'kesehatan_lingkungan' => null,
        ];

        foreach ($result as $test => &$value) {
            if ($data->$test->last() && $data->$test->last()->step == $current_screening) {
                $value = $data->$test->last();
            }
        }

        return $result;
    }

    public static function completeCheck($data){
        $response = [
            'status' => false,
        ];
        $all_completed = !array_search(false, self::currentTestStatus($data));
        if($all_completed){
            $response['status'] = true;
            $response['next_test'] = Carbon::parse($data?->kesehatan_lingkungan?->last()?->tanggal)->addWeeks(4)->format('Y-m-d');
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
