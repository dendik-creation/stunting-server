<?php

namespace App\Http\Controllers;

use App\Models\Keluarga;
use Illuminate\Http\Request;

class OperatorController extends Controller
{
    public function home()
    {
        // Bisa saja ada chart yang ditampilkan, sementara hanya approval_request
        $approval_request = Keluarga::with('puskesmas')->where('is_approved', 0)
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

    public function detailRequest($keluarga_id){
        $keluarga = Keluarga::with('puskesmas')->findOrFail($keluarga_id);
        if(filter_var($keluarga['is_approved'], FILTER_VALIDATE_BOOLEAN)){
            return response()->json([
                'status' => false,
                'message' => 'Data keluarga telah disetujui sebelumnya',
            ], 401);
        }else{
            return response()->json([
                'status' => true,
                'message' => 'Data keluarga ditemukan',
                'data' => $keluarga
            ], 200);
        }
    }

    public function approveKeluarga($keluarga_id){
        $keluarga = Keluarga::with('puskesmas')->findOrFail($keluarga_id);
        if($keluarga && !filter_var($keluarga['is_approved'], FILTER_VALIDATE_BOOLEAN)){
            $keluarga->update(['is_approved' => 1]);
            return response()->json([
                'status' => true,
                'message' => 'Data keluarga disetujui',
            ], 200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Data keluarga telah disetujui sebelumnya',
            ], 401);
        }
    }
}
