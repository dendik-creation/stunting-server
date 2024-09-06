<?php

namespace App\Filament\Widgets;

use App\Models\Keluarga;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class KeluargaCountStat extends BaseWidget
{
    protected static ?int $sort = -2;
    protected int | string | array $columnSpan = '1/4';
    protected function getStats(): array
    {
        return $this->dataByUserRole();
    }

    private function dataByUserRole(): array {
        $keluarga = Keluarga::query();
        if(auth()->user()->role == 'operator'){
            $keluarga_count = $keluarga->where('puskesmas_id', auth()->user()->puskesmas_id)->count();
            $success_count = $keluarga->where('puskesmas_id', auth()->user()->puskesmas_id)->where('is_free_stunting', 1)->count();
            $operator_count = User::where('puskesmas_id', auth()->user()->puskesmas_id)->where('role', 'operator')->count();
            return [
                Stat::make('Jumlah Keluarga', $keluarga_count),
                Stat::make('Jumlah Tes Berhasil', $success_count),
                Stat::make('Jumlah Operator', $operator_count),
            ];
        }else if(auth()->user()->role == 'dinas'){
            $keluarga_count = $keluarga->count();
            $success_count = $keluarga->where('is_free_stunting', 1)->count();
            return [
                Stat::make('Jumlah Keluarga', $keluarga_count),
                Stat::make('Jumlah Tes Berhasil', $success_count),
            ];
        }else{
            $keluarga_count = $keluarga->count();
            $user_count = User::count();
            return [
                Stat::make('Jumlah User', $user_count),
                Stat::make('Jumlah Keluarga', $keluarga_count),
            ];
        }
    }
}
