<?php

namespace App\Http\Controllers;

use App\Helpers\ScreeningTestHelper;
use App\Models\JawabanKriteriaKemandirian;
use App\Models\Keluarga;
use App\Models\KriteriaKemandirian;
use App\Models\TingkatKemandirian;
use Illuminate\Http\Request;

class KemandirianController extends Controller
{
    public function getQuestions()
    {
        $questions = KriteriaKemandirian::all();
        return response()->json(
            [
                'status' => true,
                'message' => 'Pertanyaan kritetia kemandirian berhasil disiapkan',
                'data' => $questions,
            ],
            200
        );
    }

    public function answerQuestion(Request $request, $keluarga_id)
    {
        $request->validate([
            'data.*kriteria_kemandirian_id' => 'required',
        ]);
        $answer_count = 0;
        $available_answer = ScreeningTestHelper::availableToAnswer($keluarga_id);
        // Is Available to Answer
        if ($available_answer['status']) {
            // Initial Null Value Tingkat Kemandirian
            $step_tingkatan = TingkatKemandirian::where('keluarga_id', $keluarga_id)->count();
            $tingkat_kemandirian = TingkatKemandirian::create([
                'tingkatan' => 0,
                'step' => $step_tingkatan + 1,
                'tanggal' => date('Y-m-d'),
                'keluarga_id' => $keluarga_id,
            ]);

            if($step_tingkatan + 1 == 2){
                Keluarga::findOrFail($keluarga_id)->update([
                    'is_test_done' => 1,
                ]);
            }

            // Store Answer Question
            if (!empty($request->data) && count($request->data) > 0) {
                foreach ($request->data as $item) {
                    JawabanKriteriaKemandirian::create([
                        'tingkat_kemandirian_id' => $tingkat_kemandirian['id'],
                        'kriteria_kemandirian_id' => $item['kriteria_kemandirian_id'],
                        'keluarga_id' => $keluarga_id,
                    ]);
                    $answer_count++;
                }
            }

            // Calculate Update Tingkat Kemandiian
            if (ScreeningTestHelper::calculateAnswertoTingkatan($answer_count, $tingkat_kemandirian['id'])) {
                return response()->json(
                    [
                        'status' => true,
                        'message' => 'Jawaban tingkat kemandirian berhasil dikirim',
                    ],
                    200
                );
            }
        } else {
            return response()->json(
                [
                    'status' => $available_answer['status'],
                    'message' => $available_answer['message'],
                ],
                401
            );
        }
    }

    public function availableToNextTest($keluarga_id)
    {
        $is_available = ScreeningTestHelper::availableToAnswer($keluarga_id);
        if ($is_available['status']) {
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Anda dapat mengisi kriteria kemandirian kembali',
                ],
                200
            );
        } else {
            return response()->json(
                [
                    'status' => $is_available['status'],
                    'message' => $is_available['message'],
                ],
                401
            );
        }
    }
}
