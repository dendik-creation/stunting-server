<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnakSakitRequest;
use App\Models\AnakSakit;
use App\Models\Penyakit;
use App\Models\PenyakitAnak;
use Illuminate\Http\Request;

class AnakSakitController extends Controller
{
    public function getAnakSakit($keluarga_id)
    {
        $anak_sakit = AnakSakit::where('keluarga_id', $keluarga_id)->first();
        if (empty($anak_sakit)) {
            return response()->json([
                'status' => false,
                'message' => 'Data anak sakit tidak ditemukan',
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'Data anak sakit ditemukan',
            'data' => $anak_sakit,
        ]);
    }

    public function getPenyakitList()
    {
        $data = Penyakit::all()->groupBy('jenis_penyakit');
        if (!empty($data)) {
            foreach ($data['penyerta'] as $item) {
                $item['selected'] = false;
            }
            foreach ($data['komplikasi'] as $item) {
                $item['selected'] = false;
            }
        }
        return response()->json([
            'status' => true,
            'message' => 'Data penyakit ditemukan',
            'data' => $data,
        ]);
    }

    private function storePenyakitAnak(array $penyerta, array $komplikasi, int $anak_sakit_id)
    {
        // Penyerta
        foreach ($penyerta as $item) {
            if ($item['selected'] == true) {
                PenyakitAnak::create([
                    'anak_sakit_id' => $anak_sakit_id,
                    'penyakit_id' => $item['id'],
                ]);
            }
        }
        // Komplikasi
        foreach ($komplikasi as $item) {
            if ($item['selected'] == true) {
                PenyakitAnak::create([
                    'anak_sakit_id' => $anak_sakit_id,
                    'penyakit_id' => $item['id'],
                ]);
            }
        }
    }

    public function storeAnakSakit(AnakSakitRequest $request, $keluarga_id)
    {
        $validated = $request->validated();
        $anak_sakit = AnakSakit::where('keluarga_id', $keluarga_id)->first();
        if (!empty($anak_sakit)) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal, Anda sudah memiliki data anak sakit sebelumnya',
            ]);
        }
        // Create Anak Sakit
        $validated['keluarga_id'] = $keluarga_id;
        $anak_sakit = AnakSakit::create($validated);

        // Create Penyakit (Disease)
        $this->storePenyakitAnak($validated['penyakit_penyerta'], $validated['penyakit_komplikasi'], $anak_sakit['id']);

        return response()->json([
            'status' => true,
            'message' => 'Data anak sakit berhasil ditambahkan',
        ]);
    }
}
