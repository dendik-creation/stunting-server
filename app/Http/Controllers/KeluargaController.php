<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterKeluargaRequest;
use App\Http\Resources\Keluarga\HomeResource;
use App\Models\Keluarga;
use Illuminate\Http\Request;

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
}
