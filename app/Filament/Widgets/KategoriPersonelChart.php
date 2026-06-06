<?php

namespace App\Filament\Widgets;

use App\Models\Anggota;
use Filament\Widgets\ChartWidget;

class KategoriPersonelChart extends ChartWidget
{
    protected static ?string $heading = 'Komposisi Personel';

    protected static ?int $sort = 4;

    protected static ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $tni = Anggota::where('kategori_pegawai', 'TNI')->count();
        $pns = Anggota::where('kategori_pegawai', 'PNS')->count();
        $pppk = Anggota::where('kategori_pegawai', 'PPPK')->count();
        $blu = Anggota::where('kategori_pegawai', 'BLU')->count();

        $data = [];
        $labels = [];
        $colors = [];

        $map = [
            'TNI' => ['count' => $tni, 'color' => '#ef4444'],
            'PNS' => ['count' => $pns, 'color' => '#10b981'],
            'PPPK' => ['count' => $pppk, 'color' => '#f59e0b'],
            'BLU' => ['count' => $blu, 'color' => '#3b82f6'],
        ];

        foreach ($map as $label => $info) {
            if ($info['count'] > 0) {
                $labels[] = "{$label}: {$info['count']} Orang";
                $data[] = $info['count'];
                $colors[] = $info['color'];
            }
        }

        if (empty($data)) {
            return [
                'datasets' => [['data' => [1], 'backgroundColor' => ['#e2e8f0']]],
                'labels' => ['Belum ada personel'],
            ];
        }

        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => $colors,
                    'borderWidth' => 0,
                    'hoverOffset' => 8,
                ]
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
            'cutout' => '65%',
        ];
    }
}
