<?php

namespace App\Filament\Resources\KegiatanResource\Pages;

use App\Filament\Resources\KegiatanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKegiatan extends EditRecord
{
    protected static string $resource = KegiatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('cetakPdf')
                ->label('Cetak Laporan PDF')
                ->color('success')
                ->icon('heroicon-o-printer')
                ->url(fn () => route('kegiatan.pdf', ['kegiatan' => $this->record]))
                ->openUrlInNewTab(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            KegiatanResource\Widgets\KekuatanApelWidget::class,
        ];
    }
}
