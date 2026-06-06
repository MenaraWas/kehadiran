<?php

namespace App\Filament\Resources\KegiatanResource\Widgets;

use App\Models\Kegiatan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class KekuatanApelWidget extends BaseWidget
{
    public ?Kegiatan $record = null;

    protected $listeners = [
        'refreshKekuatanApel' => '$refresh',
    ];

    protected function getStats(): array
    {
        if (!$this->record) {
            return [];
        }

        $total = $this->record->kehadirans()->count();
        $hadir = $this->record->kehadirans()->where('status', 'Hadir')->count();
        $kurang = $this->record->kehadirans()->where('status', '!=', 'Hadir')->count();

        // Hitung persentase kehadiran
        $persenHadir = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;

        return [
            Stat::make('Kekuatan Apel (Hadir)', $hadir . ' / ' . $total)
                ->description("Tingkat Kehadiran: {$persenHadir}%")
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),
            Stat::make('Kurang (Absen/Dinas/Cuti)', $kurang)
                ->description('Personel berhalangan hadir / dinas luar')
                ->descriptionIcon('heroicon-m-user-minus')
                ->color($kurang > 0 ? 'warning' : 'success'),
            Stat::make('Total Personel Terdaftar', $total)
                ->description('Jumlah personel untuk kegiatan ini')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('info'),
        ];
    }
}
