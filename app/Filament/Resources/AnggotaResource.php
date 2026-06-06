<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnggotaResource\Pages;
use App\Models\Anggota;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AnggotaResource extends Resource
{
    protected static ?string $model = Anggota::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Anggota Personel';

    protected static ?string $modelLabel = 'Anggota';

    protected static ?string $pluralModelLabel = 'Anggota Personel';

    protected static ?string $navigationGroup = 'Data Master';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('kategori_pegawai')
                            ->label('Kategori Pegawai')
                            ->options([
                                'TNI' => 'TNI',
                                'PNS' => 'PNS',
                                'PPPK' => 'PPPK',
                                'BLU' => 'BLU',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('jabatan')
                            ->label('Pangkat / Korps / Gol')
                            ->placeholder('Cth: Letkol Ckm (K), Penata Tk.I III/d, PPPK Gol. V')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('nrp_nip')
                            ->label('NRP / NIP / NIPPPK / NPPB')
                            ->placeholder('Nomor induk pegawai')
                            ->maxLength(50),
                        Forms\Components\FileUpload::make('foto')
                            ->label('Foto Anggota')
                            ->image()
                            ->directory('anggota-fotos')
                            ->visibility('public')
                            ->required() // Sesuai instruksi user, foto wajib
                            ->imageEditor()
                            ->maxSize(2048), // 2MB max
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto')
                    ->label('Foto')
                    ->circular()
                    ->size(50),
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jabatan')
                    ->label('Pangkat / Korps / Gol')
                    ->placeholder('-')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nrp_nip')
                    ->label('NRP / NIP')
                    ->placeholder('-')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('kategori_pegawai')
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
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kategori_pegawai')
                    ->label('Kategori Pegawai')
                    ->options([
                        'TNI' => 'TNI',
                        'PNS' => 'PNS',
                        'PPPK' => 'PPPK',
                        'BLU' => 'BLU',
                    ]),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnggotas::route('/'),
            'create' => Pages\CreateAnggota::route('/create'),
            'edit' => Pages\EditAnggota::route('/{record}/edit'),
        ];
    }
}
