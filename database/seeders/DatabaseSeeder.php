<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 0. Buat Pengaturan Website Bawaan
        \App\Models\Setting::create([
            'nama_instansi' => 'MAN 2 Bantul',
            'alamat' => 'Jl. Parangtritis No.KM. 11, Manding, Trirenggo, Kec. Bantul, Kabupaten Bantul, Daerah Istimewa Yogyakarta 55714',
            'kontak_person' => '081234567890 (Humas & Tata Usaha)',
        ]);

        // 1. Buat User Admin Bawaan
        \App\Models\User::create([
            'name' => 'Admin E-Apel',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
        ]);

        // 2. Buat Data Bagian / Unit Kerja
        $bagianTU = \App\Models\Bagian::create(['nama_bagian' => 'Tata Usaha']);
        $bagianKurikulum = \App\Models\Bagian::create(['nama_bagian' => 'Kurikulum']);
        $bagianKesiswaan = \App\Models\Bagian::create(['nama_bagian' => 'Kesiswaan']);
        $bagianSarpras = \App\Models\Bagian::create(['nama_bagian' => 'Sarana & Prasarana']);
        $bagianHumas = \App\Models\Bagian::create(['nama_bagian' => 'Humas & Keagamaan']);

        // 3. Buat Data Anggota / Personel Contoh
        $fotoPath = 'anggota-fotos/placeholder.png';

        // Tata Usaha
        \App\Models\Anggota::create([
            'nama' => 'Supriyadi, S.Sos',
            'foto' => $fotoPath,
            'bagian_id' => $bagianTU->id,
            'kategori_pegawai' => 'PNS',
        ]);
        \App\Models\Anggota::create([
            'nama' => 'Sri Wahyuni, A.Md',
            'foto' => $fotoPath,
            'bagian_id' => $bagianTU->id,
            'kategori_pegawai' => 'PPPK',
        ]);

        // Kurikulum
        \App\Models\Anggota::create([
            'nama' => 'Bambang Triyono, S.Pd., M.Pd',
            'foto' => $fotoPath,
            'bagian_id' => $bagianKurikulum->id,
            'kategori_pegawai' => 'PNS',
        ]);
        \App\Models\Anggota::create([
            'nama' => 'Dewi Lestari, S.Pd',
            'foto' => $fotoPath,
            'bagian_id' => $bagianKurikulum->id,
            'kategori_pegawai' => 'PPPK',
        ]);

        // Kesiswaan
        \App\Models\Anggota::create([
            'nama' => 'Ahmad Fauzi, S.Ag., M.Pd.I',
            'foto' => $fotoPath,
            'bagian_id' => $bagianKesiswaan->id,
            'kategori_pegawai' => 'PNS',
        ]);
        \App\Models\Anggota::create([
            'nama' => 'Rian Kurniawan, S.Pd',
            'foto' => $fotoPath,
            'bagian_id' => $bagianKesiswaan->id,
            'kategori_pegawai' => 'PPPK',
        ]);

        // Sarana & Prasarana
        \App\Models\Anggota::create([
            'nama' => 'Rahmat Hidayat, S.T',
            'foto' => $fotoPath,
            'bagian_id' => $bagianSarpras->id,
            'kategori_pegawai' => 'PNS',
        ]);
        \App\Models\Anggota::create([
            'nama' => 'Siti Aminah',
            'foto' => $fotoPath,
            'bagian_id' => $bagianSarpras->id,
            'kategori_pegawai' => 'BLU',
        ]);
    }
}
