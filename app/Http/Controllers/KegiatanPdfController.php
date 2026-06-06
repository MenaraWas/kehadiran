<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class KegiatanPdfController extends Controller
{
    public function generate(Kegiatan $kegiatan)
    {
        // Load data relasi yang diperlukan
        $kegiatan->load(['kehadirans.anggota.bagian', 'bagian']);

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

        // Buat folder temp jika belum ada
        $tempDir = storage_path('app/temp');
        if (!File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }

        // Render view HTML
        $html = view('pdf.kegiatan', compact('kegiatan', 'stats', 'grouped'))->render();

        $uniqId = uniqid('kegiatan_');
        $htmlFile = $tempDir . '/' . $uniqId . '.html';
        $pdfFile = $tempDir . '/' . $uniqId . '.pdf';

        File::put($htmlFile, $html);

        // Eksekusi LibreOffice headless
        $command = "libreoffice --headless --convert-to pdf --outdir " . escapeshellarg($tempDir) . " " . escapeshellarg($htmlFile);
        
        exec($command, $output, $returnVar);

        if ($returnVar === 0 && File::exists($pdfFile)) {
            $filename = 'Laporan_Kekuatan_Apel_' . str_replace(' ', '_', $kegiatan->nama_kegiatan) . '_' . $kegiatan->tanggal->format('Y-m-d') . '.pdf';
            
            // Dapatkan isi file
            $content = File::get($pdfFile);

            // Bersihkan file temp
            File::delete($htmlFile);
            File::delete($pdfFile);

            // Response download
            return response($content, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        }

        // Jika gagal, bersihkan file HTML
        if (File::exists($htmlFile)) {
            File::delete($htmlFile);
        }

        return response()->json([
            'message' => 'Gagal membuat file PDF. Error code: ' . $returnVar,
            'details' => $output
        ], 500);
    }
}
