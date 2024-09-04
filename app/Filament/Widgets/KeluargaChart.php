<?php

namespace App\Filament\Widgets;

use App\Models\Keluarga;
use Filament\Widgets\ChartWidget;

class KeluargaChart extends ChartWidget
{
    protected static ?string $heading = 'Data keluarga';
    protected static ?string $description = 'Jumlah keluarga yang terdaftar';
    public ?string $filter = '1';
    protected static ?string $maxHeight = "300px";

     protected function getData(): array
    {
        $filter = $this->filter;
        $startMonth = $filter === '1' ? 1 : ($filter === '2' ? 4 : ($filter === '3' ? 7 : 10));
        $endMonth = $startMonth + 2;
        if(auth()->user()->role == "operator"){
            $familyData = Keluarga::where('puskesmas_id', auth()->user()->puskesmas_id)->whereBetween('created_at', [
                \Carbon\Carbon::now()->startOfYear()->month($startMonth),
                \Carbon\Carbon::now()->startOfYear()->month($endMonth)->endOfMonth(),
            ])->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
              ->groupBy('month')
              ->get();
            }else{
            $familyData = Keluarga::whereBetween('created_at', [
                \Carbon\Carbon::now()->startOfYear()->month($startMonth),
                \Carbon\Carbon::now()->startOfYear()->month($endMonth)->endOfMonth(),
            ])->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
              ->groupBy('month')
              ->get();
        }

        $labels = [];
        for ($i = $startMonth; $i <= $endMonth; $i++) {
            $labels[] = \Carbon\Carbon::create()->month($i)->format('F');
        }
        $data = [];
        foreach (range($startMonth, $endMonth) as $month) {
            $data[] = $familyData->firstWhere('month', $month)->total ?? 0;
        }
        return [
            'datasets' => [
                [
                    'label' => "Total keluarga",
                    'data' => $data,
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
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

    // Mengubah filter saat pengguna memilih
    public function applyFilter(string $filter): void
    {
        $this->filter = $filter;
    }

    protected function getType(): string
    {
        return 'line';
    }
}
