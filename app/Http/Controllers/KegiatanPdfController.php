<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Barryvdh\DomPDF\Facade\Pdf;

class KegiatanPdfController extends Controller
{
    public function generate(Kegiatan $kegiatan)
    {
        // Load data relasi yang diperlukan
        $kegiatan->load(['kehadirans.anggota']);

        $kehadirans = $kegiatan->kehadirans;
        $total = $kehadirans->count();

        // Hitung statistik
        $stats = [
            'total' => $total,
            'hadir' => $kehadirans->where('status', 'Hadir')->count(),
            'dinas_dalam' => $kehadirans->where('status', 'Dinas Dalam')->count(),
            'dinas_sore' => $kehadirans->where('status', 'Dinas Sore')->count(),
            'dinas_luar' => $kehadirans->where('status', 'Dinas Luar')->count(),
            'dinas_khusus' => $kehadirans->where('status', 'Dinas Khusus')->count(),
            'pelayanan_teknis' => $kehadirans->where('status', 'Pelayanan Teknis')->count(),
            'sakit' => $kehadirans->where('status', 'Sakit')->count(),
            'izin' => $kehadirans->where('status', 'Izin')->count(),
            'bp' => $kehadirans->where('status', 'BP')->count(),
            'izin_tidak_apel' => $kehadirans->where('status', 'Izin Tidak Apel')->count(),
            'terlambat' => $kehadirans->where('status', 'Terlambat')->count(),
            'cuti' => $kehadirans->whereIn('status', ['Cuti Tahunan', 'Cuti Bersalin'])->count(),
            'lepas' => $kehadirans->whereIn('status', ['Lepas Libur', 'Lepas Piket', 'Lepas Jaga'])->count(),
            'pendidikan' => $kehadirans->where('status', 'Pendidikan')->count(),
        ];

        // Kelompokkan anggota yang TIDAK HADIR berdasarkan status untuk ditampilkan rinciannya di bawah
        $grouped = $kehadirans->where('status', '!=', 'Hadir')
            ->groupBy('status');

        $filename = 'Laporan_Kekuatan_Apel_' . str_replace(' ', '_', $kegiatan->nama_kegiatan) . '_' . $kegiatan->tanggal->format('Y-m-d') . '.pdf';

        $pdf = Pdf::loadView('pdf.kegiatan', compact('kegiatan', 'stats', 'grouped'))
            ->setPaper('a4', 'portrait');

        return $pdf->download($filename);
    }
}
