<?php

namespace App\Filament\Resources\KegiatanResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class KehadiransRelationManager extends RelationManager
{
    protected static string $relationship = 'kehadirans';

    protected static ?string $title = 'Daftar Kehadiran Personel';

    protected static ?string $modelLabel = 'Kehadiran';

    protected static ?string $pluralModelLabel = 'Kehadiran';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('status')
                    ->label('Status Kehadiran')
                    ->options([
                        'Hadir' => 'Hadir (Dinas Pagi)',
                        'Dinas Dalam' => 'Dinas Dalam (DD)',
                        'Dinas Sore' => 'Dinas Sore (DS)',
                        'Dinas Luar' => 'Dinas Luar (DL)',
                        'Dinas Khusus' => 'Dinas Khusus (DK)',
                        'Pelayanan Teknis' => 'Pelayanan Teknis',
                        'Pendidikan' => 'Pendidikan',
                        'Sakit' => 'Sakit',
                        'Izin' => 'Izin',
                        'BP' => 'BP',
                        'Izin Tidak Apel' => 'Izin Tidak Apel',
                        'Terlambat' => 'Terlambat',
                        'Cuti Tahunan' => 'Cuti Tahunan',
                        'Cuti Bersalin' => 'Cuti Bersalin',
                        'Lepas Libur' => 'Lepas Libur (LL)',
                        'Lepas Piket' => 'Lepas Piket',
                        'Lepas Jaga' => 'Lepas Jaga',
                    ])
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set) => $set('sub_status', null))
                    ->required(),
                Forms\Components\Select::make('sub_status')
                    ->label('Sub Status')
                    ->options(fn ($get) => match ($get('status')) {
                        'Dinas Khusus' => [
                            'Adc' => 'Adc',
                            'Sopir' => 'Sopir',
                        ],
                        'Pelayanan Teknis' => [
                            'Dokter' => 'Dokter',
                            'Perawat' => 'Perawat',
                            'FO' => 'FO',
                            'Kasir' => 'Kasir',
                            'Administrasi' => 'Administrasi',
                            'BP DSA' => 'BP DSA',
                        ],
                        default => [],
                    })
                    ->visible(fn ($get) => in_array($get('status'), ['Dinas Khusus', 'Pelayanan Teknis'])),
                Forms\Components\TextInput::make('keterangan')
                    ->label('Keterangan / Catatan')
                    ->maxLength(255),
            ])->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\ImageColumn::make('anggota.foto')
                    ->label('Foto')
                    ->circular()
                    ->size(40),
                Tables\Columns\TextColumn::make('anggota.nama')
                    ->label('Nama Personel')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('anggota.bagian.nama_bagian')
                    ->label('Bagian / Unit Kerja')
                    ->sortable(),
                Tables\Columns\TextColumn::make('anggota.kategori_pegawai')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'TNI' => 'danger',
                        'PNS' => 'success',
                        'PPPK' => 'warning',
                        'BLU' => 'info',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status Kehadiran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Hadir' => 'success',
                        'Belum Absen' => 'warning',
                        'Dinas Dalam', 'Dinas Sore', 'Dinas Luar', 'Dinas Khusus', 'Pelayanan Teknis' => 'info',
                        'Pendidikan', 'Izin', 'BP', 'Izin Tidak Apel', 'Terlambat', 'Cuti Tahunan', 'Cuti Bersalin' => 'warning',
                        'Sakit' => 'danger',
                        'Lepas Libur', 'Lepas Piket', 'Lepas Jaga' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('sub_status')
                    ->label('Sub Status')
                    ->badge()
                    ->color('secondary')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->placeholder('-')
                    ->limit(30),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status Kehadiran')
                    ->options([
                        'Hadir' => 'Hadir (Dinas Pagi)',
                        'Dinas Dalam' => 'Dinas Dalam (DD)',
                        'Dinas Sore' => 'Dinas Sore (DS)',
                        'Dinas Luar' => 'Dinas Luar (DL)',
                        'Dinas Khusus' => 'Dinas Khusus (DK)',
                        'Pelayanan Teknis' => 'Pelayanan Teknis',
                        'Pendidikan' => 'Pendidikan',
                        'Sakit' => 'Sakit',
                        'Izin' => 'Izin',
                        'BP' => 'BP',
                        'Izin Tidak Apel' => 'Izin Tidak Apel',
                        'Terlambat' => 'Terlambat',
                        'Cuti Tahunan' => 'Cuti Tahunan',
                        'Cuti Bersalin' => 'Cuti Bersalin',
                        'Lepas Libur' => 'Lepas Libur (LL)',
                        'Lepas Piket' => 'Lepas Piket',
                        'Lepas Jaga' => 'Lepas Jaga',
                    ]),
                Tables\Filters\SelectFilter::make('kategori_pegawai')
                    ->label('Kategori Pegawai')
                    ->options([
                        'TNI' => 'TNI',
                        'PNS' => 'PNS',
                        'PPPK' => 'PPPK',
                        'BLU' => 'BLU',
                    ])
                    ->query(fn (Builder $query, array $data): Builder => 
                        $query->when(
                            $data['value'],
                            fn (Builder $query, $value): Builder => $query->whereHas('anggota', fn ($q) => $q->where('kategori_pegawai', $value))
                        )
                    ),
            ])
            ->headerActions([
                // Kita tidak mengizinkan penambahan anggota manual di sini karena otomatis menggunakan Exception-Based Entry
            ])
            ->actions([
                Tables\Actions\Action::make('setHadir')
                    ->label('Hadir')
                    ->icon('heroicon-m-check')
                    ->color('success')
                    ->button()
                    ->action(function ($record, $livewire) {
                        $record->update([
                            'status' => 'Hadir',
                            'sub_status' => null,
                            'keterangan' => null,
                        ]);
                        $livewire->dispatch('refreshKekuatanApel');
                    })
                    ->visible(fn ($record) => $record->status === 'Belum Absen'),
                
                Tables\Actions\Action::make('setTidakHadir')
                    ->label('Tidak Hadir')
                    ->icon('heroicon-m-x-mark')
                    ->color('danger')
                    ->button()
                    ->modalWidth('lg')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label('Alasan Tidak Hadir')
                            ->options([
                                'Sakit' => 'Sakit',
                                'Izin' => 'Izin',
                                'Dinas Dalam' => 'Dinas Dalam (DD)',
                                'Dinas Sore' => 'Dinas Sore (DS)',
                                'Dinas Luar' => 'Dinas Luar (DL)',
                                'Dinas Khusus' => 'Dinas Khusus (DK)',
                                'Pelayanan Teknis' => 'Pelayanan Teknis',
                                'Pendidikan' => 'Pendidikan',
                                'BP' => 'BP',
                                'Izin Tidak Apel' => 'Izin Tidak Apel',
                                'Terlambat' => 'Terlambat',
                                'Cuti Tahunan' => 'Cuti Tahunan',
                                'Cuti Bersalin' => 'Cuti Bersalin',
                                'Lepas Libur' => 'Lepas Libur (LL)',
                                'Lepas Piket' => 'Lepas Piket',
                                'Lepas Jaga' => 'Lepas Jaga',
                            ])
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) => $set('sub_status', null))
                            ->required(),
                        Forms\Components\Select::make('sub_status')
                            ->label('Sub Status')
                            ->options(fn ($get) => match ($get('status')) {
                                'Dinas Khusus' => [
                                    'Adc' => 'Adc',
                                    'Sopir' => 'Sopir',
                                ],
                                'Pelayanan Teknis' => [
                                    'Dokter' => 'Dokter',
                                    'Perawat' => 'Perawat',
                                    'FO' => 'FO',
                                    'Kasir' => 'Kasir',
                                    'Administrasi' => 'Administrasi',
                                    'BP DSA' => 'BP DSA',
                                ],
                                default => [],
                            })
                            ->visible(fn ($get) => in_array($get('status'), ['Dinas Khusus', 'Pelayanan Teknis'])),
                        Forms\Components\TextInput::make('keterangan')
                            ->label('Keterangan / Catatan')
                            ->maxLength(255),
                        Forms\Components\ViewField::make('ocr_camera')
                            ->label('Pindai Surat Keterangan / Izin (Opsional)')
                            ->view('filament.forms.components.ocr-camera')
                    ])
                    ->action(function ($record, array $data, $livewire) {
                        $record->update([
                            'status' => $data['status'],
                            'sub_status' => $data['sub_status'] ?? null,
                            'keterangan' => $data['keterangan'] ?? null,
                        ]);
                        $livewire->dispatch('refreshKekuatanApel');
                    })
                    ->visible(fn ($record) => $record->status === 'Belum Absen'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('ubahStatusMassal')
                        ->label('Ubah Status Massal')
                        ->icon('heroicon-o-pencil-square')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->label('Status Baru')
                                ->options([
                                    'Hadir' => 'Hadir (Dinas Pagi)',
                                    'Dinas Dalam' => 'Dinas Dalam (DD)',
                                    'Dinas Sore' => 'Dinas Sore (DS)',
                                    'Dinas Luar' => 'Dinas Luar (DL)',
                                    'Dinas Khusus' => 'Dinas Khusus (DK)',
                                    'Pelayanan Teknis' => 'Pelayanan Teknis',
                                    'Pendidikan' => 'Pendidikan',
                                    'Sakit' => 'Sakit',
                                    'Izin' => 'Izin',
                                    'BP' => 'BP',
                                    'Izin Tidak Apel' => 'Izin Tidak Apel',
                                    'Terlambat' => 'Terlambat',
                                    'Cuti Tahunan' => 'Cuti Tahunan',
                                    'Cuti Bersalin' => 'Cuti Bersalin',
                                    'Lepas Libur' => 'Lepas Libur (LL)',
                                    'Lepas Piket' => 'Lepas Piket',
                                    'Lepas Jaga' => 'Lepas Jaga',
                                ])
                                ->reactive()
                                ->afterStateUpdated(fn ($state, callable $set) => $set('sub_status', null))
                                ->required(),
                            Forms\Components\Select::make('sub_status')
                                ->label('Sub Status Baru')
                                ->options(fn ($get) => match ($get('status')) {
                                    'Dinas Khusus' => [
                                        'Adc' => 'Adc',
                                        'Sopir' => 'Sopir',
                                    ],
                                    'Pelayanan Teknis' => [
                                        'Dokter' => 'Dokter',
                                        'Perawat' => 'Perawat',
                                        'FO' => 'FO',
                                        'Kasir' => 'Kasir',
                                        'Administrasi' => 'Administrasi',
                                        'BP DSA' => 'BP DSA',
                                    ],
                                    default => [],
                                })
                                ->visible(fn ($get) => in_array($get('status'), ['Dinas Khusus', 'Pelayanan Teknis'])),
                            Forms\Components\TextInput::make('keterangan')
                                ->label('Keterangan Baru')
                                ->maxLength(255),
                        ])
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records, array $data) {
                            foreach ($records as $record) {
                                $record->update([
                                    'status' => $data['status'],
                                    'sub_status' => $data['sub_status'] ?? null,
                                    'keterangan' => $data['keterangan'] ?? $record->keterangan,
                                ]);
                            }
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }
}
