<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Panel Filter -->
        <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-900 dark:border-white/10">
            <h2 class="text-sm font-semibold text-gray-500 uppercase dark:text-gray-400 mb-4">Filter Pencarian</h2>
            <form wire:submit.prevent="submit">
                {{ $this->form }}
            </form>
        </div>

        <!-- Tabel Hasil Rekapitulasi -->
        <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-900 dark:border-white/10">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Rekapitulasi Kehadiran Pegawai</h2>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Bulan: <span class="font-semibold text-primary-600 dark:text-primary-400">
                        {{ [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ][$this->data['month'] ?? now()->month] }} 
                        {{ $this->data['year'] ?? now()->year }}
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse table-auto">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-white/5 border-b border-gray-200 dark:border-white/10 text-xs font-semibold text-gray-500 uppercase dark:text-gray-400">
                            <th class="px-4 py-3">Nama Anggota</th>
                            <th class="px-4 py-3">Unit Kerja</th>
                            <th class="px-4 py-3 text-center">Kat.</th>
                            <th class="px-4 py-3 text-center">Hadir</th>
                            <th class="px-4 py-3 text-center">Belum Absen</th>
                            <th class="px-4 py-3 text-center">Dinas</th>
                            <th class="px-4 py-3 text-center">Pelayanan</th>
                            <th class="px-4 py-3 text-center">Izin/Sakit</th>
                            <th class="px-4 py-3 text-center">Cuti</th>
                            <th class="px-4 py-3 text-center">Lepas</th>
                            <th class="px-4 py-3 text-center bg-gray-100/50 dark:bg-white/10 font-bold">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-white/5 text-sm text-gray-700 dark:text-gray-300">
                        @php
                            $rekapData = $this->getRekapData();
                        @endphp
                        @forelse ($rekapData as $row)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-white/5">
                                <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $row['nama'] }}</td>
                                <td class="px-4 py-3">{{ $row['bagian'] }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold
                                        {{ $row['kategori'] === 'TNI' ? 'bg-danger-50 text-danger-700 dark:bg-danger-900/30 dark:text-danger-400' : '' }}
                                        {{ $row['kategori'] === 'PNS' ? 'bg-success-50 text-success-700 dark:bg-success-900/30 dark:text-success-400' : '' }}
                                        {{ $row['kategori'] === 'PPPK' ? 'bg-warning-50 text-warning-700 dark:bg-warning-900/30 dark:text-warning-400' : '' }}
                                        {{ $row['kategori'] === 'BLU' ? 'bg-info-50 text-info-700 dark:bg-info-900/30 dark:text-info-400' : '' }}
                                    ">
                                        {{ $row['kategori'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-1 rounded bg-success-50 text-success-700 dark:bg-success-900/20 dark:text-success-400 font-bold">{{ $row['counts']['Hadir'] }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($row['counts']['Belum Absen'] > 0)
                                        <span class="px-2 py-1 rounded bg-warning-50 text-warning-700 dark:bg-warning-900/20 dark:text-warning-400 font-bold">{{ $row['counts']['Belum Absen'] }}</span>
                                    @else
                                        <span class="text-gray-300 dark:text-gray-600">0</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center text-info-600 dark:text-info-400 font-medium">{{ $row['counts']['Dinas'] }}</td>
                                <td class="px-4 py-3 text-center text-primary-600 dark:text-primary-400 font-medium">{{ $row['counts']['Pelayanan'] }}</td>
                                <td class="px-4 py-3 text-center text-warning-600 dark:text-warning-400 font-medium">{{ $row['counts']['Izin'] }}</td>
                                <td class="px-4 py-3 text-center text-amber-600 dark:text-amber-400 font-medium">{{ $row['counts']['Cuti'] }}</td>
                                <td class="px-4 py-3 text-center text-gray-500 dark:text-gray-400 font-medium">{{ $row['counts']['Lepas'] }}</td>
                                <td class="px-4 py-3 text-center font-bold bg-gray-50 dark:bg-white/5">{{ $row['counts']['Total'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="px-4 py-8 text-center text-gray-400">
                                    Tidak ada data anggota atau kegiatan yang tercatat untuk kriteria filter ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>
