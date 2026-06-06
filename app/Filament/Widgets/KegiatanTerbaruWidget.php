<?php

namespace App\Filament\Widgets;

use App\Models\Kegiatan;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class KegiatanTerbaruWidget extends BaseWidget
{
    protected static ?int $sort = 6;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Kegiatan Terbaru';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Kegiatan::query()
                    ->withCount([
                        'kehadirans',
                        'kehadirans as hadir_count' => fn ($q) => $q->where('status', 'Hadir'),
                        'kehadirans as belum_count' => fn ($q) => $q->where('status', 'Belum Absen'),
                    ])
                    ->orderBy('tanggal', 'desc')
                    ->orderBy('waktu', 'desc')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('nama_kegiatan')
                    ->label('Kegiatan')
                    ->weight('bold')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('waktu')
                    ->label('Waktu')
                    ->time('H:i'),
                Tables\Columns\TextColumn::make('kehadirans_count')
                    ->label('Total')
                    ->badge()
                    ->color('gray')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('hadir_count')
                    ->label('Hadir')
                    ->badge()
                    ->color('success')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('belum_count')
                    ->label('Belum')
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'warning' : 'success')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('persen')
                    ->label('% Hadir')
                    ->getStateUsing(function ($record) {
                        if ($record->kehadirans_count === 0) return '0%';
                        return round(($record->hadir_count / $record->kehadirans_count) * 100) . '%';
                    })
                    ->badge()
                    ->color(function ($record) {
                        if ($record->kehadirans_count === 0) return 'gray';
                        $persen = ($record->hadir_count / $record->kehadirans_count) * 100;
                        return $persen >= 80 ? 'success' : ($persen >= 50 ? 'warning' : 'danger');
                    })
                    ->alignCenter(),
            ])
            ->paginated(false)
            ->actions([
                Tables\Actions\Action::make('lihat')
                    ->label('Kelola')
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn (Kegiatan $record) => route('filament.admin.resources.kegiatans.edit', $record))
                    ->color('primary')
                    ->size('sm'),
            ]);
    }
}
