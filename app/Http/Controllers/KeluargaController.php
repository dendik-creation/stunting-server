<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterKeluargaRequest;
use App\Http\Resources\Keluarga\HomeResource;
use App\Http\Resources\ScreeningTestList;
use App\Models\Keluarga;
use App\Models\KesehatanLingkungan;
use App\Models\TingkatKemandirian;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KeluargaController extends Controller
{
    public function findNIK(Request $request){
        $keluarga = Keluarga::with('puskesmas', 'tingkat_kemandirian','anak_sakit', 'kesehatan_lingkungan')->where('nik', $request->nik)->first();
        if(empty($keluarga)){
           return response()->json(['status' => false, 'message' => 'Data tidak ditemukan'], 404);
        }
        $data = [
            "status" => true,
            "message" => 'Data berhasil ditemukan',
            "data" => new HomeResource($keluarga),
        ];
        return response()->json($data, 200);
    }

    public function register(RegisterKeluargaRequest $request){
        $nik = $request->nik;
        if(Keluarga::where('nik', $nik)->exists()){
            return response()->json([
                'status' => false,
                'message' => 'Registrasi data keluarga gagal, NIK sudah terdaftar',
            ], 409);
        }

        $keluarga_created = Keluarga::create($request->validated());
        return response()->json([
            'status' => true,
            'message' => 'Registrasi data keluarga sukses, harap menunggu verifikasi oleh petugas',
            'data' => $keluarga_created,
        ], 201);
    }

    public function homeData($keluarga_id){
        $keluarga = Keluarga::with('puskesmas', 'tingkat_kemandirian','anak_sakit', 'kesehatan_lingkungan')->findOrFail($keluarga_id);
        $data = [
            'status' => true,
            'message' => 'Data keluarga ditemukan',
            'data' => new HomeResource($keluarga),
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
                'screening_test' => new ScreeningTestList(collect($test_result)),
            ],
        ]);
    }

    public function getKeluargaTestByStep($keluarga_id, $step)
    {
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
                'screening_test' => $screening_test,
            ],
        ]);
    }

    public function updateKeluarga($keluarga_id, Request $request){
        $keluarga = Keluarga::findOrFail($keluarga_id);
        $keluarga->update($request->all());
        return response()->json([
            'status' => true,
            'message' => 'Data keluarga diperbarui',
        ], 200);
    }

    public function forceOpenTest($keluarga_id){
        $tingkat_kemandirian  = TingkatKemandirian::where('keluarga_id', $keluarga_id)->where('step', 1)->first();
        $kesehatan_lingkungan  = KesehatanLingkungan::where('keluarga_id', $keluarga_id)->where('step', 1)->first();
        if($tingkat_kemandirian != null){
            $date_data = Carbon::parse($tingkat_kemandirian->tanggal);
            $tingkat_kemandirian->update([
                'tanggal' => $date_data->subWeeks(4),
            ]);
        }
        if($kesehatan_lingkungan != null){
            $date_data = Carbon::parse($kesehatan_lingkungan->tanggal);
            $kesehatan_lingkungan->update([
                'tanggal' => $date_data->subWeeks(4),
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Tes kedua berhasil dibuka sekarang (untuk demo)',
        ], 200);
    }
}
