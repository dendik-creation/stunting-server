<?php

namespace App\Filament\Widgets;

use App\Models\Keluarga;
use Filament\Widgets\ChartWidget;

class TestStatusKeluargaChart extends ChartWidget
{
    protected static ?string $heading = 'Status tes keluarga';
    protected static ?string $description = 'Perkembangan status tes keluarga';
    public ?string $filter = '1';
    protected static ?string $maxHeight = "300px";

    protected function getData(): array
    {
        $startMonth = $this->filter === '1' ? 1 : ($this->filter === '2' ? 4 : ($this->filter === '3' ? 7 : 10));
        $endMonth = $startMonth + 2;
        if(auth()->user()->role == "operator"){
            $families = Keluarga::where('puskesmas_id', auth()->user()->puskesmas_id)->whereBetween('created_at', [
                \Carbon\Carbon::now()->startOfYear()->month($startMonth),
                \Carbon\Carbon::now()->startOfYear()->month($endMonth)->endOfMonth(),
            ])->get();
        }else{
            $families = Keluarga::whereBetween('created_at', [
                \Carbon\Carbon::now()->startOfYear()->month($startMonth),
                \Carbon\Carbon::now()->startOfYear()->month($endMonth)->endOfMonth(),
            ])->get();
        }

        $progressCount = $families->where('is_test_done', false)->count();
        $failedCount = $families->where('is_test_done', true)->where('is_free_stunting', false)->count();
        $successCount = $families->where('is_test_done', true)->where('is_free_stunting', true)->count();

        return [
            'datasets' => [
                [
                    'label' => 'Status Tes Keluarga',
                    'data' => [$progressCount, $failedCount, $successCount],
                    'backgroundColor' => [
                        'rgba(255, 159, 64, 0.25)',
                        'rgba(255, 99, 132, 0.25)',
                        'rgba(75, 192, 192, 0.25)',
                    ],
                    'borderColor' => [
                        'rgba(255, 159, 64, 0.8)',
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                    ],
                    'borderWidth' => 2,
                    'borderRadius' => 12,
                ],
            ],
            'labels' => ['Berjalan', 'Gagal', 'Berhasil'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 5,
                    ]
                ],
            ],
        ];
    }

    protected function getFilters(): ?array
    {
        return [
            '1' => 'Triwulan 1 (Jan - Mar '. date('Y') .')',
            '2' => 'Triwulan 2 (Apr - Jun '. date('Y') .')',
            '3' => 'Triwulan 3 (Jul - Sep '. date('Y') .')',
            '4' => 'Triwulan 4 (Oct - Dec '. date('Y') .')',
        ];
    }

    public function applyFilter(string $filter): void
    {
        $this->filter = $filter;
    }

    protected function getType(): string
    {
        return 'bar';
    }
}

