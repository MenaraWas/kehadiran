<?php

namespace App\Observers;

use App\Models\Kegiatan;
use App\Models\Anggota;
use App\Models\Kehadiran;

class KegiatanObserver
{
    /**
     * Handle the Kegiatan "created" event.
     */
    public function created(Kegiatan $kegiatan): void
    {
        // Dapatkan anggota untuk di-input secara otomatis
        $query = Anggota::query();

        // Jika kegiatan dibatasi pada bagian tertentu
        if ($kegiatan->bagian_id) {
            $query->where('bagian_id', $kegiatan->bagian_id);
        }

        $anggotas = $query->get();

        foreach ($anggotas as $anggota) {
            Kehadiran::create([
                'kegiatan_id' => $kegiatan->id,
                'anggota_id' => $anggota->id,
                'status' => 'Belum Absen', // Default: Belum Absen
                'sub_status' => null,
                'keterangan' => null,
            ]);
        }
    }
}
