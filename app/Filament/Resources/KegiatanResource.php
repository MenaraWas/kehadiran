<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KegiatanResource\Pages;
use App\Filament\Resources\KegiatanResource\RelationManagers;
use App\Models\Kegiatan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\KegiatanResource\Widgets;

class KegiatanResource extends Resource
{
    protected static ?string $model = Kegiatan::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Kegiatan / Apel';

    protected static ?string $modelLabel = 'Kegiatan';

    protected static ?string $pluralModelLabel = 'Kegiatan / Apel';

    protected static ?string $navigationGroup = 'Data Kehadiran';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Informasi Kegiatan')
                    ->description('Detail informasi tentang nama, tanggal, waktu, dan keterangan kegiatan.')
                    ->collapsible()
                    ->collapsed(fn (string $context): bool => $context === 'edit') // Collapsed hanya saat edit
                    ->schema([
                        Forms\Components\TextInput::make('nama_kegiatan')
                            ->label('Nama Kegiatan / Apel')
                            ->default('Apel Pagi')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('bagian_id')
                            ->label('Bagian / Unit Kerja')
                            ->relationship('bagian', 'nama_bagian')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->helperText('Kosongkan jika kegiatan ini diikuti oleh semua bagian.'),
                        Forms\Components\DatePicker::make('tanggal')
                            ->label('Tanggal Kegiatan')
                            ->default(now())
                            ->required(),
                        Forms\Components\TimePicker::make('waktu')
                            ->label('Waktu Mulai')
                            ->default('07:30')
                            ->required(),
                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan / Deskripsi')
                            ->nullable()
                            ->columnSpanFull(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('tanggal', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('nama_kegiatan')
                    ->label('Nama Kegiatan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bagian.nama_bagian')
                    ->label('Bagian / Unit Kerja')
                    ->placeholder('Semua Bagian')
                    ->badge()
                    ->color(fn ($state) => $state ? 'info' : 'success')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('waktu')
                    ->label('Waktu')
                    ->time('H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('kehadirans_count')
                    ->label('Total Anggota')
                    ->counts('kehadirans')
                    ->badge()
                    ->color('gray')
                    ->alignCenter(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('bagian_id')
                    ->label('Bagian / Unit Kerja')
                    ->relationship('bagian', 'nama_bagian')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\KehadiransRelationManager::class,
        ];
    }

    public static function getWidgets(): array
    {
        return [
            Widgets\KekuatanApelWidget::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKegiatans::route('/'),
            'create' => Pages\CreateKegiatan::route('/create'),
            'edit' => Pages\EditKegiatan::route('/{record}/edit'),
        ];
    }
}
