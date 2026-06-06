<?php

namespace App\Filament\Widgets;

use App\Models\Anggota;
use App\Models\Kegiatan;
use App\Models\Kehadiran;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $totalPersonel = Anggota::count();
        $totalKegiatan = Kegiatan::count();

        // Kegiatan bulan ini
        $kegiatanBulanIni = Kegiatan::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();

        // Kegiatan terbaru
        $latest = Kegiatan::orderBy('tanggal', 'desc')->orderBy('waktu', 'desc')->first();

        $hadirTerakhir = 0;
        $persenHadir = 0;
        $totalTerakhir = 0;

        if ($latest) {
            $totalTerakhir = $latest->kehadirans()->count();
            $hadirTerakhir = $latest->kehadirans()->where('status', 'Hadir')->count();
            $persenHadir = $totalTerakhir > 0 ? round(($hadirTerakhir / $totalTerakhir) * 100, 1) : 0;
        }

        // Kategori breakdown
        $tni = Anggota::where('kategori_pegawai', 'TNI')->count();
        $pns = Anggota::where('kategori_pegawai', 'PNS')->count();
        $pppk = Anggota::where('kategori_pegawai', 'PPPK')->count();
        $blu = Anggota::where('kategori_pegawai', 'BLU')->count();

        // Trend kehadiran: ambil 7 kegiatan terakhir
        $last7 = Kegiatan::orderBy('tanggal', 'desc')
            ->orderBy('waktu', 'desc')
            ->take(7)
            ->get()
            ->reverse();

        $trendChart = $last7->map(function ($k) {
            $total = $k->kehadirans()->count();
            $hadir = $k->kehadirans()->where('status', 'Hadir')->count();
            return $total > 0 ? round(($hadir / $total) * 100) : 0;
        })->values()->toArray();

        return [
            Stat::make('Total Personel', $totalPersonel)
                ->description("TNI: {$tni} | PNS: {$pns} | PPPK: {$pppk} | BLU: {$blu}")
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary')
                ->chart([$tni, $pns, $pppk, $blu]),

            Stat::make('Kehadiran Terakhir', $hadirTerakhir . ' / ' . $totalTerakhir)
                ->description("Tingkat kehadiran: {$persenHadir}%")
                ->descriptionIcon('heroicon-m-check-badge')
                ->color($persenHadir >= 80 ? 'success' : ($persenHadir >= 50 ? 'warning' : 'danger'))
                ->chart($trendChart ?: [0]),

            Stat::make('Kegiatan Bulan Ini', $kegiatanBulanIni)
                ->description("Total sepanjang waktu: {$totalKegiatan}")
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),

            Stat::make('Rata-Rata Kehadiran', $this->getAverageAttendance() . '%')
                ->description('Rata-rata bulan ini')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('success')
                ->chart($this->getMonthlyTrend()),
        ];
    }

    private function getAverageAttendance(): float
    {
        $kegiatans = Kegiatan::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->withCount([
                'kehadirans',
                'kehadirans as hadir_count' => fn ($q) => $q->where('status', 'Hadir'),
            ])
            ->get();

        if ($kegiatans->isEmpty()) return 0;

        $totalHadir = $kegiatans->sum('hadir_count');
        $totalAll = $kegiatans->sum('kehadirans_count');

        return $totalAll > 0 ? round(($totalHadir / $totalAll) * 100, 1) : 0;
    }

    private function getMonthlyTrend(): array
    {
        $trend = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $kegiatans = Kegiatan::whereMonth('tanggal', $date->month)
                ->whereYear('tanggal', $date->year)
                ->withCount([
                    'kehadirans',
                    'kehadirans as hadir_count' => fn ($q) => $q->where('status', 'Hadir'),
                ])
                ->get();

            $totalAll = $kegiatans->sum('kehadirans_count');
            $totalHadir = $kegiatans->sum('hadir_count');
            $trend[] = $totalAll > 0 ? round(($totalHadir / $totalAll) * 100) : 0;
        }
        return $trend;
    }
}
