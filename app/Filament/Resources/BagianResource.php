<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BagianResource\Pages;
use App\Filament\Resources\BagianResource\RelationManagers;
use App\Models\Bagian;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BagianResource extends Resource
{
    protected static ?string $model = Bagian::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'Bagian / Unit Kerja';

    protected static ?string $modelLabel = 'Bagian';

    protected static ?string $pluralModelLabel = 'Bagian / Unit Kerja';

    protected static ?string $navigationGroup = 'Data Master';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_bagian')
                    ->label('Nama Bagian / Unit Kerja')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_bagian')
                    ->label('Nama Bagian / Unit Kerja')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('anggotas_count')
                    ->label('Jumlah Anggota')
                    ->counts('anggotas')
                    ->badge()
                    ->color('info')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Bagian $record, Tables\Actions\DeleteAction $action) {
                        if ($record->anggotas()->count() > 0) {
                            \Filament\Notifications\Notification::make()
                                ->title('Gagal Menghapus')
                                ->body("Bagian \"{$record->nama_bagian}\" masih memiliki {$record->anggotas()->count()} anggota. Pindahkan atau hapus anggota terlebih dahulu.")
                                ->danger()
                                ->send();
                            $action->cancel();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageBagians::route('/'),
        ];
    }
}
