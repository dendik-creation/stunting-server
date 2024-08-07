<?php

namespace App\Http\Controllers;

use App\Models\Keluarga;
use Illuminate\Http\Request;

class KeluargaController extends Controller
{
    public function findNik(Request $request){
        $request->validate([
            'nik' => 'required|max:16|min:16',
        ], [
            'nik.required' => 'NIK wajib terisi',
            'nik.min' => 'NIK harus berisi 16 digit angka',
            'nik.max' => 'NIK harus berisi 16 digit angka',
        ]);
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
}
