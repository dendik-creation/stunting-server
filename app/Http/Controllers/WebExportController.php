<?php

namespace App\Http\Controllers;

use App\Models\AnakSakit;
use App\Models\Keluarga;
use App\Models\Puskesmas;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WebExportController extends Controller
{
    public function exportKeluargaBulk(Request $request)
    {
        $raw_filters = $request->all();
        $query = Keluarga::query();
        $clean_filters = null;

        if (isset($raw_filters['is_free_stunting'])) {
            $query->where('is_free_stunting', $raw_filters['is_free_stunting']['value']);
            $clean_filters['is_free_stunting'] = filter_var($raw_filters['is_free_stunting']['value'], FILTER_VALIDATE_BOOLEAN);
        }

        if (isset($raw_filters['is_test_done'])) {
            $query->where('is_test_done', $raw_filters['is_test_done']['value']);
            $clean_filters['is_test_done'] = filter_var($raw_filters['is_test_done']['value'], FILTER_VALIDATE_BOOLEAN);
        }

        if (isset($raw_filters['puskesmas_id'])) {
            $query->where('puskesmas_id', $raw_filters['puskesmas_id']['value']);
            $clean_filters['puskesmas'] = Puskesmas::findOrFail($raw_filters['puskesmas_id']['value'])->nama_puskesmas;
        }

        if (isset($raw_filters['created_at'])) {
            $dates = explode('-', $raw_filters['created_at']['created_at']);
            $startDate = Carbon::createFromFormat('d/m/Y', trim($dates[0]))->startOfDay();
            $endDate = Carbon::createFromFormat('d/m/Y', trim($dates[1]))->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
            $clean_filters['date_range'] = ['start' => $startDate->format('d F Y'), 'end' => $endDate->format('d F Y')];
        }
        // From Operator
        if(auth()->user()->role == 'operator'){
            $clean_filters['puskesmas'] = auth()->user()->puskesmas->nama_puskesmas;
        }
        $keluarga = $query->with('puskesmas')->latest()->get();
        $test_status = $this->countKeluargaTestStatus($keluarga);
        return view('export.keluarga.filter', [
            'title' => 'Cetak Data Keluarga',
            'data' => $keluarga,
            'filters' => $clean_filters,
            'test_status' => $test_status,
            'print_at' => Carbon::now()->format('d F Y'),
        ]);
    }

    public function exportKeluargaById($keluarga_id){
        $keluarga = Keluarga::with('puskesmas', 'anak_sakit', 'kesehatan_lingkungan', 'tingkat_kemandirian')->find($keluarga_id);
        $anak_sakit = AnakSakit::where('keluarga_id', $keluarga_id)->first();
        return view('export.keluarga.single', [
            'title' => 'Cetak Data Keluarga ' . $keluarga->nama_lengkap,
            'data' => $keluarga,
            'anak_sakit' => $anak_sakit
        ]);
    }

    private function countKeluargaTestStatus($keluarga){
        $result = [
            'success' => 0,
            'failed' => 0,
            'running' => 0
        ];
        if(count($keluarga) > 0){
            foreach($keluarga as $item){
                if($item->is_test_done){
                    if($item->is_free_stunting){
                        $result['success']++;
                    }else{
                        $result['failed']++;
                    }
                }else{
                    $result['running']++;
                }
            }
            return $result;
        }
    }
}
