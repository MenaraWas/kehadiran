<?php

namespace Database\Seeders;

use App\Models\User;
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
            'nama_instansi' => 'RSPAD Gatot Soebroto',
            'alamat' => 'Jl. Abdul Rahman Saleh No.24, Senen, Jakarta Pusat',
            'kontak_person' => '021-3441008',
        ]);

        // 1. Buat User Admin Bawaan
        \App\Models\User::create([
            'name' => 'Admin E-Apel',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
        ]);

        // 2. Buat Data Anggota Personel Contoh
        $fotoPath = 'anggota-fotos/placeholder.png';

        // Militer / TNI
        \App\Models\Anggota::create([
            'nama' => 'dr. Ford Ance E Aritonang, Sp.JP, FIHA.',
            'foto' => $fotoPath,
            'kategori_pegawai' => 'TNI',
            'jabatan' => 'Letkol Ckm (K)',
        ]);
        \App\Models\Anggota::create([
            'nama' => 'Dian Endah Pamurtiani, A.Md.Keb',
            'foto' => $fotoPath,
            'kategori_pegawai' => 'TNI',
            'jabatan' => 'Letda Ckm (K)',
        ]);

        // PNS
        \App\Models\Anggota::create([
            'nama' => 'Eny Nurfriyanti, AMK',
            'foto' => $fotoPath,
            'kategori_pegawai' => 'PNS',
            'jabatan' => 'Penata Tk.I III/d',
        ]);
        \App\Models\Anggota::create([
            'nama' => 'Ns. Umi Duwi Amanah, S.Kep',
            'foto' => $fotoPath,
            'kategori_pegawai' => 'PNS',
            'jabatan' => 'Penata III/c',
        ]);

        // PPPK
        \App\Models\Anggota::create([
            'nama' => 'Ns. Fajar Adhie Sulistyo, S.Kep',
            'foto' => $fotoPath,
            'kategori_pegawai' => 'PPPK',
            'jabatan' => 'PPPK Gol. X',
        ]);
        \App\Models\Anggota::create([
            'nama' => 'Bresna Mayanti, S.K.M.',
            'foto' => $fotoPath,
            'kategori_pegawai' => 'PPPK',
            'jabatan' => 'PPPK Gol. IX',
        ]);

        // BLU
        \App\Models\Anggota::create([
            'nama' => 'Rosi Agus Setiawan, S.E.',
            'foto' => $fotoPath,
            'kategori_pegawai' => 'BLU',
            'jabatan' => 'Pegawai BLU Non ASN',
        ]);
        \App\Models\Anggota::create([
            'nama' => 'Elsa Maghfira Paramesti, S.I.kom',
            'foto' => $fotoPath,
            'kategori_pegawai' => 'BLU',
            'jabatan' => 'Pegawai BLU Non ASN',
        ]);
    }
}
