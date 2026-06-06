<?php

namespace App\Filament\Widgets;

use App\Models\Kegiatan;
use Filament\Widgets\ChartWidget;

class KekuatanApelChart extends ChartWidget
{
    protected static ?int $sort = 2;

    public ?string $filter = 'all';

    public function getHeading(): string
    {
        $latestKegiatan = Kegiatan::orderBy('tanggal', 'desc')
            ->orderBy('waktu', 'desc')
            ->first();

        if ($latestKegiatan) {
            return 'Kekuatan Kehadiran Terbaru: ' . $latestKegiatan->nama_kegiatan . ' (' . $latestKegiatan->tanggal->format('d M Y') . ')';
        }

        return 'Kekuatan Kehadiran Terbaru';
    }

    protected function getFilters(): ?array
    {
        return [
            'all' => 'Semua Kategori',
            'TNI' => 'TNI',
            'PNS' => 'PNS',
            'PPPK' => 'PPPK',
            'BLU' => 'BLU',
        ];
    }

    protected function getData(): array
    {
        $latestKegiatan = Kegiatan::orderBy('tanggal', 'desc')
            ->orderBy('waktu', 'desc')
            ->first();

        if (!$latestKegiatan) {
            return [
                'datasets' => [
                    [
                        'data' => [0],
                        'backgroundColor' => ['#e2e8f0'],
                    ]
                ],
                'labels' => ['Belum ada data kegiatan (0 Orang)'],
            ];
        }

        $activeFilter = $this->filter;

        $query = $latestKegiatan->kehadirans();
        if ($activeFilter && $activeFilter !== 'all') {
            $query->whereHas('anggota', fn ($q) => $q->where('kategori_pegawai', $activeFilter));
        }

        $kehadirans = $query->get();

        // Hitung masing-masing status kehadiran
        $hadir = $kehadirans->where('status', 'Hadir')->count();
        $belumAbsen = $kehadirans->where('status', 'Belum Absen')->count();
        $sakit = $kehadirans->where('status', 'Sakit')->count();
        $izin = $kehadirans->where('status', 'Izin')->count();
        
        $dinas = $kehadirans->whereIn('status', ['Dinas Dalam', 'Dinas Sore', 'Dinas Luar', 'Dinas Khusus'])->count();
        $pelayanan = $kehadirans->where('status', 'Pelayanan Teknis')->count();
        $cuti = $kehadirans->whereIn('status', ['Cuti Tahunan', 'Cuti Bersalin'])->count();
        $lepas = $kehadirans->whereIn('status', ['Lepas Libur', 'Lepas Piket', 'Lepas Jaga'])->count();
        $lainnya = $kehadirans->whereIn('status', ['BP', 'Izin Tidak Apel', 'Terlambat', 'Pendidikan'])->count();

        // Peta data
        $dataMap = [
            'Hadir' => $hadir,
            'Belum Diabsen' => $belumAbsen,
            'Sakit' => $sakit,
            'Izin' => $izin,
            'Dinas Resmi' => $dinas,
            'Pelayanan Teknis' => $pelayanan,
            'Cuti' => $cuti,
            'Lepas Tugas' => $lepas,
            'Lainnya' => $lainnya,
        ];

        // Peta warna premium
        $colorMap = [
            'Hadir' => '#10b981',
            'Belum Diabsen' => '#f59e0b',
            'Sakit' => '#ef4444',
            'Izin' => '#3b82f6',
            'Dinas Resmi' => '#06b6d4',
            'Pelayanan Teknis' => '#8b5cf6',
            'Cuti' => '#ec4899',
            'Lepas Tugas' => '#64748b',
            'Lainnya' => '#a8a29e',
        ];

        $labels = [];
        $values = [];
        $backgroundColors = [];

        foreach ($dataMap as $label => $val) {
            if ($val > 0) {
                $labels[] = "{$label}: {$val} Orang";
                $values[] = $val;
                $backgroundColors[] = $colorMap[$label];
            }
        }

        if (empty($values)) {
            $labels[] = 'Tidak Ada Anggota (0 Orang)';
            $values[] = 0;
            $backgroundColors[] = '#e2e8f0';
        }

        return [
            'datasets' => [
                [
                    'data' => $values,
                    'backgroundColor' => $backgroundColors,
                    'borderWidth' => 1,
                ]
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
