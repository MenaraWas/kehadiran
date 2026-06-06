<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kehadiran & Kekuatan Apel</title>
    <style>
        @page {
            size: A4;
            margin: 15mm 15mm 15mm 15mm;
        }
        body {
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
            color: #1e293b;
            line-height: 1.5;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
            font-size: 12px;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #334155;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .title {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 5px 0;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .subtitle {
            font-size: 12px;
            color: #475569;
            margin: 0;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 4px 8px;
            vertical-align: top;
        }
        .meta-label {
            font-weight: bold;
            color: #475569;
            width: 15%;
        }
        .meta-value {
            width: 35%;
        }
        .section-title {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
            border-bottom: 1.5px solid #cbd5e1;
            padding-bottom: 4px;
            margin-top: 20px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .rekap-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .rekap-table th, .rekap-table td {
            border: 1px solid #cbd5e1;
            padding: 6px 10px;
            text-align: center;
        }
        .rekap-table th {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }
        .rekap-table td.total-col {
            background-color: #f8fafc;
            font-weight: bold;
        }
        .detail-group {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .detail-group-title {
            font-weight: bold;
            color: #1e40af;
            background-color: #eff6ff;
            padding: 4px 8px;
            border-left: 3px solid #2563eb;
            margin-bottom: 6px;
            font-size: 12px;
        }
        .detail-table {
            width: 100%;
            border-collapse: collapse;
        }
        .detail-table th, .detail-table td {
            border: 1px solid #e2e8f0;
            padding: 6px 10px;
            text-align: left;
        }
        .detail-table th {
            background-color: #f8fafc;
            color: #64748b;
            font-weight: 600;
            font-size: 11px;
        }
        .detail-table td {
            color: #334155;
        }
        .signature-area {
            margin-top: 40px;
            width: 100%;
            page-break-inside: avoid;
        }
        .signature-box {
            width: 300px;
            float: right;
            text-align: center;
        }
        .signature-title {
            margin-bottom: 50px;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }
        .text-center {
            text-align: center !important;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1 class="title">Laporan Kekuatan Apel & Kehadiran</h1>
        <p class="subtitle">Sistem Kehadiran Elektronik (E-Apel)</p>
    </div>

    <!-- Informasi Kegiatan -->
    <table class="meta-table">
        <tr>
            <td class="meta-label">Nama Kegiatan</td>
            <td class="meta-value">: <strong>{{ $kegiatan->nama_kegiatan }}</strong></td>
            <td class="meta-label">Tanggal</td>
            <td class="meta-value">: {{ $kegiatan->tanggal->format('d F Y') }}</td>
        </tr>
        <tr>
            <td class="meta-label">Unit Kerja</td>
            <td class="meta-value">: {{ $kegiatan->bagian ? $kegiatan->bagian->nama_bagian : 'Semua Bagian / Lintas Unit' }}</td>
            <td class="meta-label">Waktu Mulai</td>
            <td class="meta-value">: {{ $kegiatan->waktu ? \Carbon\Carbon::createFromFormat('H:i:s', $kegiatan->waktu)->format('H:i') . ' WIB' : '-' }}</td>
        </tr>
        @if($kegiatan->keterangan)
        <tr>
            <td class="meta-label">Keterangan</td>
            <td class="meta-value" colspan="3">: {{ $kegiatan->keterangan }}</td>
        </tr>
        @endif
    </table>

    <!-- Ringkasan Angka Rekapitulasi -->
    <div class="section-title">Rekapitulasi Kekuatan Personel</div>
    <table class="rekap-table">
        <thead>
            <tr>
                <th rowspan="2">Kekuatan<br>Terdaftar</th>
                <th rowspan="2" style="background-color: #d1fae5; color: #065f46;">Hadir</th>
                <th colspan="4">Dinas Resmi</th>
                <th rowspan="2">Pelayanan<br>Teknis</th>
                <th colspan="3">Absen / Izin</th>
                <th rowspan="2">Cuti</th>
                <th rowspan="2">Lepas<br>Tugas</th>
            </tr>
            <tr>
                <th>DD</th>
                <th>DS</th>
                <th>DL</th>
                <th>DK</th>
                <th>Sakit</th>
                <th>Izin</th>
                <th>Lainnya</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="total-col" style="font-size: 13px;">{{ $stats['total'] }}</td>
                <td style="font-weight: bold; background-color: #ecfdf5; color: #047857; font-size: 13px;">{{ $stats['hadir'] }}</td>
                <td>{{ $stats['dinas_dalam'] }}</td>
                <td>{{ $stats['dinas_sore'] }}</td>
                <td>{{ $stats['dinas_luar'] }}</td>
                <td>{{ $stats['dinas_khusus'] }}</td>
                <td>{{ $stats['pelayanan_teknis'] }}</td>
                <td>{{ $stats['sakit'] }}</td>
                <td>{{ $stats['izin'] }}</td>
                <td>{{ $stats['bp'] + $stats['izin_tidak_apel'] + $stats['terlambat'] + $stats['pendidikan'] }}</td>
                <td>{{ $stats['cuti'] }}</td>
                <td>{{ $stats['lepas'] }}</td>
            </tr>
        </tbody>
    </table>

    <div style="font-size: 10.5px; color: #64748b; margin-top: -10px; margin-bottom: 20px;">
        * Keterangan Singkatan: <strong>DD</strong> (Dinas Dalam), <strong>DS</strong> (Dinas Sore), <strong>DL</strong> (Dinas Luar), <strong>DK</strong> (Dinas Khusus), <strong>Lainnya</strong> (BP, Izin Tidak Apel, Terlambat, Pendidikan).
    </div>

    <!-- Rincian Nama per Status Ketidakhadiran -->
    <div class="section-title">Rincian Personel yang Berhalangan Hadir / Dinas Khusus</div>

    @forelse ($grouped as $status => $records)
        <div class="detail-group">
            <div class="detail-group-title">{{ $status }} ({{ $records->count() }} Orang)</div>
            <table class="detail-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 25%;">Nama Personel</th>
                        <th style="width: 20%;">Unit Kerja</th>
                        <th style="width: 15%;" class="text-center">Kategori</th>
                        <th style="width: 15%;">Sub Status</th>
                        <th style="width: 20%;">Keterangan / Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($records as $index => $record)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td><strong>{{ $record->anggota->nama }}</strong></td>
                            <td>{{ $record->anggota->bagian->nama_bagian }}</td>
                            <td class="text-center">{{ $record->anggota->kategori_pegawai }}</td>
                            <td>{{ $record->sub_status ?? '-' }}</td>
                            <td>{{ $record->keterangan ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @empty
        <div style="padding: 15px; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; text-align: center; color: #64748b;">
            Seluruh personel yang terdaftar hadir lengkap. Tidak ada rincian ketidakhadiran.
        </div>
    @endforelse

    <!-- Tanda Tangan -->
    <div class="signature-area">
        <div class="signature-box">
            <div class="signature-title">
                Yogyakarta, {{ now()->format('d F Y') }}<br>
                Petugas Absensi / Admin Apel,
            </div>
            <br><br><br>
            <div class="signature-name">{{ auth()->user()->name ?? 'Administrator' }}</div>
            <div style="font-size: 11px; color: #64748b;">E-Apel Digital Attendance</div>
        </div>
        <div style="clear: both;"></div>
    </div>
</body>
</html>
