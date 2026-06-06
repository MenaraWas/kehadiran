<?php

namespace App\Filament\Pages;

use App\Models\Anggota;
use App\Models\Kehadiran;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;

class RekapBulanan extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Rekap Bulanan';

    protected static ?string $title = 'Rekapitulasi Kehadiran Bulanan';

    protected static ?string $navigationGroup = 'Data Kehadiran';

    protected static string $view = 'filament.pages.rekap-bulanan';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'month' => now()->month,
            'year' => now()->year,
            'kategori_pegawai' => null,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        Select::make('month')
                            ->label('Bulan')
                            ->options([
                                1 => 'Januari',
                                2 => 'Februari',
                                3 => 'Maret',
                                4 => 'April',
                                5 => 'Mei',
                                6 => 'Juni',
                                7 => 'Juli',
                                8 => 'Agustus',
                                9 => 'September',
                                10 => 'Oktober',
                                11 => 'November',
                                12 => 'Desember',
                            ])
                            ->required()
                            ->reactive(),
                        Select::make('year')
                            ->label('Tahun')
                            ->options(
                                collect(range(now()->year - 5, now()->year + 2))
                                    ->mapWithKeys(fn ($year) => [$year => $year])
                                    ->toArray()
                            )
                            ->required()
                            ->reactive(),
                        Select::make('kategori_pegawai')
                            ->label('Kategori Pegawai')
                            ->options([
                                'TNI' => 'TNI',
                                'PNS' => 'PNS',
                                'PPPK' => 'PPPK',
                                'BLU' => 'BLU',
                            ])
                            ->placeholder('Semua Kategori')
                            ->nullable()
                            ->reactive(),
                    ]),
            ])
            ->statePath('data');
    }

    public function getRekapData(): array
    {
        $filters = $this->data;
        $month = $filters['month'] ?? now()->month;
        $year = $filters['year'] ?? now()->year;
        $kategori = $filters['kategori_pegawai'] ?? null;

        $anggotaQuery = Anggota::with(['kehadirans' => function ($q) use ($month, $year) {
            $q->whereHas('kegiatan', function ($kq) use ($month, $year) {
                $kq->whereMonth('tanggal', $month)
                  ->whereYear('tanggal', $year);
            });
        }]);

        if ($kategori) {
            $anggotaQuery->where('kategori_pegawai', $kategori);
        }

        $anggotas = $anggotaQuery->orderBy('nama')->get();

        $rekap = [];

        foreach ($anggotas as $anggota) {
            $kehadirans = $anggota->kehadirans;

            $counts = [
                'Hadir' => 0,
                'Belum Absen' => 0,
                'Dinas' => 0,
                'Pelayanan' => 0,
                'Izin' => 0,
                'Cuti' => 0,
                'Lepas' => 0,
                'Total' => $kehadirans->count(),
            ];

            foreach ($kehadirans as $k) {
                $status = $k->status;
                if ($status === 'Hadir') {
                    $counts['Hadir']++;
                } elseif ($status === 'Belum Absen') {
                    $counts['Belum Absen']++;
                } elseif (in_array($status, ['Dinas Dalam', 'Dinas Sore', 'Dinas Luar', 'Dinas Khusus'])) {
                    $counts['Dinas']++;
                } elseif ($status === 'Pelayanan Teknis') {
                    $counts['Pelayanan']++;
                } elseif (in_array($status, ['Sakit', 'Izin', 'BP', 'Izin Tidak Apel', 'Terlambat', 'Pendidikan'])) {
                    $counts['Izin']++;
                } elseif (in_array($status, ['Cuti Tahunan', 'Cuti Bersalin'])) {
                    $counts['Cuti']++;
                } elseif (in_array($status, ['Lepas Libur', 'Lepas Piket', 'Lepas Jaga'])) {
                    $counts['Lepas']++;
                }
            }

            $rekap[] = [
                'nama' => $anggota->nama,
                'jabatan' => $anggota->jabatan ?? '-',
                'kategori' => $anggota->kategori_pegawai,
                'counts' => $counts,
            ];
        }

        return $rekap;
    }
}
