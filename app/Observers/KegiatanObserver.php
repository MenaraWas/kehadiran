<?php

namespace App\Observers;

use App\Models\Kegiatan;
use App\Models\Anggota;
use App\Models\Kehadiran;

class KegiatanObserver
{
    /**
     * Handle the Kegiatan "created" event.
     * Auto-populate kehadiran records for all anggota.
     */
    public function created(Kegiatan $kegiatan): void
    {
        $anggotas = Anggota::all();

        foreach ($anggotas as $anggota) {
            Kehadiran::create([
                'kegiatan_id' => $kegiatan->id,
                'anggota_id' => $anggota->id,
                'status' => 'Belum Absen',
                'sub_status' => null,
                'keterangan' => null,
            ]);
        }
    }
}
