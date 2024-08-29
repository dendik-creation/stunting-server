<?php

namespace App\Http\Controllers;

use App\Http\Resources\ScreeningTestList;
use App\Models\Keluarga;
use App\Models\KesehatanLingkungan;
use App\Models\TingkatKemandirian;
use Illuminate\Http\Request;

class OperatorController extends Controller
{
    public function home()
    {
        // Bisa saja ada chart yang ditampilkan, sementara hanya approval_request
        $approval_request = Keluarga::with('puskesmas')
            ->where('is_approved', 0)
            ->where('puskesmas_id', auth()->user()->puskesmas_id)
            ->latest()
            ->get();
        $data = [
            'status' => true,
            'message' => 'Konten beranda operator berhasil ditampilkan',
            'data' => [
                'approval_request' => $approval_request,
            ],
        ];
        return response()->json($data, 200);
    }

    public function detailRequest($keluarga_id)
    {
        $keluarga = Keluarga::with('puskesmas')->findOrFail($keluarga_id);
        if (filter_var($keluarga['is_approved'], FILTER_VALIDATE_BOOLEAN)) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Data keluarga telah disetujui sebelumnya',
                ],
                401,
            );
        } else {
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Data keluarga ditemukan',
                    'data' => $keluarga,
                ],
                200,
            );
        }
    }

    public function approveKeluarga($keluarga_id)
    {
        $keluarga = Keluarga::with('puskesmas')->findOrFail($keluarga_id);
        if ($keluarga && !filter_var($keluarga['is_approved'], FILTER_VALIDATE_BOOLEAN)) {
            $keluarga->update(['is_approved' => 1]);
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Data keluarga disetujui',
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Data keluarga telah disetujui sebelumnya',
                ],
                401,
            );
        }
    }

    public function getKeluargaList()
    {
        $keluarga = Keluarga::with('puskesmas')
            ->where('is_approved', 1)
            ->where('puskesmas_id', auth()->user()->puskesmas_id)
            ->latest()
            ->get();
        $data = [
            'status' => true,
            'message' => 'Data keluarga ditemukan',
            'data' => $keluarga,
        ];
        return response()->json($data, 200);
    }

    public function getKeluargaById($keluarga_id)
    {
        $keluarga = Keluarga::with('puskesmas')->findOrFail($keluarga_id);
        $data = [
            'status' => true,
            'message' => 'Data keluarga ditemukan',
            'data' => $keluarga,
        ];
        return response()->json($data, 200);
    }

    public function getKeluargaTest($keluarga_id)
    {
        $keluarga = Keluarga::with('puskesmas')->findOrFail($keluarga_id);
        $test_result = [];
        if (count($keluarga->tingkat_kemandirian) > 0) {
            foreach ($keluarga->tingkat_kemandirian as $item) {
                $test_result[] = [
                    'step' => $item['step'],
                    'tanggal' => $item['tanggal'],
                    'tingkat_kemandirian' => $item,
                ];
            }
        }
        if (count($keluarga->kesehatan_lingkungan) > 0) {
            foreach ($keluarga->kesehatan_lingkungan as $item) {
                $test_result[] = [
                    'step' => $item['step'],
                    'tanggal' => $item['tanggal'],
                    'kesehatan_lingkungan' => $item,
                ];
            }
        }
        unset($keluarga['tingkat_kemandirian']);
        unset($keluarga['kesehatan_lingkungan']);
        return response()->json([
            'status' => true,
            'message' => 'Data tes keluarga ditemukan',
            'data' => [
                'keluarga' => $keluarga,
                'screening_test' => new ScreeningTestList(collect($test_result)),
            ],
        ]);
    }

    public function getKeluargaTestByStep($keluarga_id, $step)
    {
        $keluarga = Keluarga::with('puskesmas')->findOrFail($keluarga_id);
        $tingkat_kemandirian = TingkatKemandirian::with('jawaban_kriteria_kemandirian.kriteria_kemandirian')->where('keluarga_id', $keluarga_id)->where('step', $step)->first();
        $kesehatan_lingkungan = KesehatanLingkungan::
        with([
            'jawaban_kriteria_kesehatan' => function ($query){
                $query->select('id', 'komponen_kesehatan_id', 'kriteria_kesehatan_id', 'kesehatan_lingkungan_id');
            },
            'jawaban_kriteria_kesehatan.kriteria_kesehatan' => function ($query){
                $query->select('id', 'kriteria', 'nilai');
            },
            'jawaban_kriteria_kesehatan.komponen_kesehatan' => function ($query){
                $query->select('id', 'nama_komponen');
            },

        ])
        ->where('keluarga_id', $keluarga_id)->where('step', $step)->first();
        $screening_test = [
            'tingkat_kemandirian' => $tingkat_kemandirian,
            'kesehatan_lingkungan' => $kesehatan_lingkungan,
        ];
        if (isset($screening_test['kesehatan_lingkungan']) && $screening_test['kesehatan_lingkungan'] != null) {
            $screening_test['kesehatan_lingkungan']['bobot'] = intval(KesehatanLingkungan::BOBOT);

            foreach ($screening_test['kesehatan_lingkungan']['jawaban_kriteria_kesehatan'] as $item){
                unset($item['komponen_kesehatan']['kriteria_kesehatan']);
            }
        }
        return response()->json([
            'status' => true,
            'message' => 'Data tes keluarga ditemukan',
            'data' => [
                'keluarga' => $keluarga,
                'screening_test' => $screening_test,
            ],
        ]);
    }
}
