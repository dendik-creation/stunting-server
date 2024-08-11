<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterKeluargaRequest;
use App\Models\Keluarga;
use Illuminate\Http\Request;

class KeluargaController extends Controller
{
    public function findNIK(Request $request){
        $keluarga = Keluarga::with('puskesmas', 'tingkat_kemandirian')->where('nik', $request->nik)->first();
        if(empty($keluarga)){
           return response()->json(['status' => false, 'message' => 'Data tidak ditemukan'], 404);
        }
        $data = [
            "status" => true,
            "message" => 'Data berhasil ditemukan',
            "data" => $keluarga,
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

        Keluarga::create($request->validated());
        return response()->json([
            'status' => true,
            'message' => 'Registrasi data keluarga sukses, harap menunggu verifikasi oleh petugas',
        ], 201);
    }
}
