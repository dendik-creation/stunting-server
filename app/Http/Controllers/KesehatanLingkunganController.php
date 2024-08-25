<?php

namespace App\Http\Controllers;

use App\Http\Requests\KesehatanLingkunganRequest;
use App\Models\JawabanKriteriaKesehatan;
use App\Models\KesehatanLingkungan;
use App\Models\KomponenKesehatan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KesehatanLingkunganController extends Controller
{
    public function getByKeluarga($keluarga_id)
    {
        $kesehatan_lingkungan = KesehatanLingkungan::where('keluarga_id', $keluarga_id)->latest()->get();
        if ($kesehatan_lingkungan->count() == 0) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Data kesehatan lingkungan tidak ditemukan',
                ],
                404,
            );
        }
        return response()->json(
            [
                'status' => true,
                'message' => 'Data kesehatan lingkungan ditemukan',
                'data' => $kesehatan_lingkungan,
            ],
            200,
        );
    }

    public function getById($keluarga_id, $id)
    {
        $kesehatan_lingkungan = KesehatanLingkungan::with('jawaban_kriteria_kesehatan')->findOrFail($id);
        return response()->json(
            [
                'status' => true,
                'message' => 'Data kesehatan lingkungan ditemukan',
                'data' => $kesehatan_lingkungan,
            ],
            200,
        );
    }

    public function getQuestions()
    {
        $komponen = KomponenKesehatan::all();
        return response()->json(
            [
                'status' => true,
                'message' => 'Data komponen kesehatan ditemukan',
                'data' => $komponen,
            ],
            200,
        );
    }

    public function storeKesehatanLingkungan(KesehatanLingkunganRequest $request, $keluarga_id)
    {
        $validated = $request->validated();
        if (empty($validated)) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Jawaban ditolak, mohon lengkapi semua kriteria',
                ],
                422,
            );
        }
        // Initial Kesehatan Empty Value
        $kesehatan_lingkungan_id = $this->initKesehatanLingkungan($keluarga_id);
        // Calculate Answer & Update Kesehatan
        $this->calculcateAnswer($keluarga_id, $kesehatan_lingkungan_id, $validated['data']);
        return response()->json(
            [
                'status' => true,
                'message' => 'Tes kesehatan lingkungan berhasil ditambahkan',
            ],
            200,
        );
    }

    private function calculcateAnswer($keluarga_id, $kesehatan_lingkungan_id, $answers){
        $nilai_total = 0;
        $final_nilai = 0;
        foreach ($answers as $value) {
            $jawaban_kriteria_kesehatan = JawabanKriteriaKesehatan::create([
                'kesehatan_lingkungan_id' => $kesehatan_lingkungan_id,
                'komponen_kesehatan_id' => $value['komponen_kesehatan_id'],
                'kriteria_kesehatan_id' => $value['kriteria_kesehatan_id'],
                'keluarga_id' => $keluarga_id,
            ]);
            $nilai_total += $jawaban_kriteria_kesehatan->kriteria_kesehatan->nilai;
        }
        $final_nilai = $nilai_total * KesehatanLingkungan::BOBOT;
        // Update Kesehatan Lingkungan
        $update = KesehatanLingkungan::findOrFail($kesehatan_lingkungan_id)->update([
            'nilai_total' => $final_nilai,
            'is_healthy' => $final_nilai > KesehatanLingkungan::HEALTH_METER ? true : false,
        ]);
        return true;
    }

    private function initKesehatanLingkungan($keluarga_id){
        $current_step = KesehatanLingkungan::where('keluarga_id', $keluarga_id)->count();
        $kesehatan_lingkungan = KesehatanLingkungan::create([
            'keluarga_id' => $keluarga_id,
            'nilai_total' => 0,
            'step' => $current_step + 1,
            'tanggal' => Carbon::now()->format('Y-m-d'),
        ]);
        return $kesehatan_lingkungan['id'];
    }
}
