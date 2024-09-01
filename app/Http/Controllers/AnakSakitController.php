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
            ], 404);
        }
        return response()->json([
            'status' => true,
            'message' => 'Data anak sakit ditemukan',
            'data' => $anak_sakit,
        ], 200);
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
        ], 200);
    }

    private function storePenyakitAnak(array $penyerta, array $komplikasi, int $anak_sakit_id)
    {
        // Penyerta
        foreach ($penyerta as $item) {
            if (isset($item['selected']) && $item['selected'] == true) {
                PenyakitAnak::create([
                    'anak_sakit_id' => $anak_sakit_id,
                    'penyakit_id' => $item['id'],
                ]);
            }
        }
        // Komplikasi
        foreach ($komplikasi as $item) {
            if (isset($item['selected']) && $item['selected'] == true) {
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
        ], 200);
    }

    private function updatePenyakitAnak(array $penyerta, array $komplikasi, int $anak_sakit_id){
        if(count($penyerta) > 0 && count($komplikasi) > 0){
            $penyakitAnak = PenyakitAnak::where('anak_sakit_id', $anak_sakit_id);
            if($penyakitAnak){
                // Remove All
                $penyakitAnak->delete();
            }
            // Create New value penyakit
            $this->storePenyakitAnak($penyerta, $komplikasi, $anak_sakit_id);
        }
    }

    public function updateAnakSakit($keluarga_id, Request $request){
        $anak_sakit = AnakSakit::where('keluarga_id', $keluarga_id)->first();
        if($anak_sakit){
            $anak_sakit->update($request->all());
            $this->updatePenyakitAnak($request->penyakit_penyerta, $request->penyakit_komplikasi, $anak_sakit['id']);
            return response()->json([
                'status' => true,
                'message' => 'Data anak sakit berhasil diperbarui',
            ], 200);
        }
    }
}
