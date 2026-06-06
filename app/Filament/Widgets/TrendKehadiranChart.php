<?php

namespace App\Filament\Widgets;

use App\Models\Kegiatan;
use Filament\Widgets\ChartWidget;

class TrendKehadiranChart extends ChartWidget
{
    protected static ?string $heading = 'Tren Kehadiran 7 Kegiatan Terakhir';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $kegiatans = Kegiatan::orderBy('tanggal', 'desc')
            ->orderBy('waktu', 'desc')
            ->take(7)
            ->get()
            ->reverse()
            ->values();

        if ($kegiatans->isEmpty()) {
            return [
                'datasets' => [['data' => [0], 'label' => 'Hadir']],
                'labels' => ['Belum ada data'],
            ];
        }

        $labels = [];
        $hadirData = [];
        $tidakHadirData = [];

        foreach ($kegiatans as $k) {
            $total = $k->kehadirans()->count();
            $hadir = $k->kehadirans()->where('status', 'Hadir')->count();
            $tidakHadir = $total - $hadir;

            $labels[] = $k->tanggal->format('d/m');
            $hadirData[] = $hadir;
            $tidakHadirData[] = $tidakHadir;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Hadir',
                    'data' => $hadirData,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                    'borderWidth' => 2,
                    'pointRadius' => 4,
                    'pointBackgroundColor' => '#10b981',
                ],
                [
                    'label' => 'Tidak Hadir',
                    'data' => $tidakHadirData,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                    'borderWidth' => 2,
                    'pointRadius' => 4,
                    'pointBackgroundColor' => '#f59e0b',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
