<?php

namespace App\Http\Controllers;

use App\Helpers\ScreeningTestHelper;
use App\Models\JawabanKriteriaKemandirian;
use App\Models\KriteriaKemandirian;
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
            200, [], JSON_NUMERIC_CHECK
        );
    }

    public function answerQuestion(Request $request, $keluarga_id)
    {
        $request->validate([
            'data.*kriteria_kemandirian_id' => 'required',
        ]);
        $step = 0;
        $available_answer = ScreeningTestHelper::availableToAnswer($keluarga_id);
        // Is Available to Answer
        if ($available_answer['status']) {
            if (!empty($request->data) && count($request->data) > 0) {
                // Store Answer Question
                foreach ($request->data as $item) {
                    JawabanKriteriaKemandirian::create([
                        'kriteria_kemandirian_id' => $item['kriteria_kemandirian_id'],
                        'keluarga_id' => $keluarga_id,
                    ]);
                    $step++;
                }
            }
            // Calculate Tingkat Kemandiian
            if (ScreeningTestHelper::calculateAnswertoTingkatan($step, $keluarga_id)) {
                return response()->json(
                    [
                        'status' => true,
                        'message' => 'Jawaban tingkat kemandirian berhasil dikirim',
                    ],
                    200, [], JSON_NUMERIC_CHECK
                );
            }
        } else {
            return response()->json(
                [
                    'status' => $available_answer['status'],
                    'message' => $available_answer['message'],
                ],
                401, [], JSON_NUMERIC_CHECK
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
                200, [], JSON_NUMERIC_CHECK
            );
        } else {
            return response()->json(
                [
                    'status' => $is_available['status'],
                    'message' => $is_available['message'],
                ],
                401, [], JSON_NUMERIC_CHECK
            );
        }
    }
}
