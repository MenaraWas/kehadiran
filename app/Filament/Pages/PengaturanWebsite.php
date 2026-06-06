<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class PengaturanWebsite extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Pengaturan Website';

    protected static ?string $title = 'Pengaturan Website';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static string $view = 'filament.pages.pengaturan-website';

    public ?array $data = [];

    public function mount(): void
    {
        $setting = Setting::first();

        if ($setting) {
            $this->form->fill($setting->toArray());
        } else {
            $this->form->fill([]);
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Identitas Instansi')
                    ->description('Sesuaikan identitas resmi instansi untuk kop laporan dan metadata website.')
                    ->schema([
                        TextInput::make('nama_instansi')
                            ->label('Nama Instansi / Lembaga')
                            ->placeholder('Contoh: Rumah Sakit Tk. II dr. Soedjono')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('alamat')
                            ->label('Alamat Lengkap')
                            ->placeholder('Contoh: Jl. Jend. Gatot Subroto No.96, Magelang')
                            ->nullable()
                            ->rows(3),
                        TextInput::make('kontak_person')
                            ->label('Kontak Person Admin (WhatsApp/Telp)')
                            ->placeholder('Contoh: 081234567890 (Humas Admin)')
                            ->nullable()
                            ->maxLength(255),
                    ])->columns(1)
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Perubahan')
                ->submit('save')
                ->color('primary'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $setting = Setting::first();

        if ($setting) {
            $setting->update($data);
        } else {
            Setting::create($data);
        }

        Notification::make()
            ->title('Pengaturan Berhasil Disimpan')
            ->body('Data identitas instansi telah diperbarui di database.')
            ->success()
            ->send();
    }
}
