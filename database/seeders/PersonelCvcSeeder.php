<?php

namespace Database\Seeders;

use App\Models\Anggota;
use Illuminate\Database\Seeder;

class PersonelCvcSeeder extends Seeder
{
    /**
     * Seed data personel Instalasi CVC RSPAD Gatot Soebroto.
     */
    public function run(): void
    {
        $fotoPath = 'anggota-fotos/placeholder.png';

        $personel = [
            // A. MILITER (TNI)
            ['nama' => 'dr. Ford Ance E Aritonang, Sp.JP, FIHA.', 'jabatan' => 'Letkol Ckm (K)', 'kategori_pegawai' => 'TNI', 'nrp_nip' => '11030016060178'],
            ['nama' => 'Dian Endah Pamurtiani, A.Md.Keb', 'jabatan' => 'Letda Ckm (K)', 'kategori_pegawai' => 'TNI', 'nrp_nip' => '21060318141087'],

            // B. PNS
            ['nama' => 'Eny Nurfriyanti, AMK', 'jabatan' => 'Penata Tk.I III/d', 'kategori_pegawai' => 'PNS', 'nrp_nip' => '197310042005012004'],
            ['nama' => 'Ns. Umi Duwi Amanah, S.Kep', 'jabatan' => 'Penata III/c', 'kategori_pegawai' => 'PNS', 'nrp_nip' => '198212142007012001'],
            ['nama' => 'Ns. Norris Natalis Ngadhi, S.Kep', 'jabatan' => 'Penata Muda Tk.I III/b', 'kategori_pegawai' => 'PNS', 'nrp_nip' => '199112092022031002'],
            ['nama' => 'Ns. Anisa Grace Welly, S.Kep', 'jabatan' => 'Penata Muda Tk.I III/b', 'kategori_pegawai' => 'PNS', 'nrp_nip' => '199503302022032007'],
            ['nama' => 'Sri Wahyuningsih, AMK', 'jabatan' => 'Penata Muda Tk.I III/b', 'kategori_pegawai' => 'PNS', 'nrp_nip' => '197512152008122001'],
            ['nama' => 'Fithri Hidayati, AMK', 'jabatan' => 'Penata Muda Tk.I III/b', 'kategori_pegawai' => 'PNS', 'nrp_nip' => '197811242007012001'],
            ['nama' => 'Dona Lydiawati, AMK', 'jabatan' => 'Penata Muda Tk.I III/b', 'kategori_pegawai' => 'PNS', 'nrp_nip' => '198101302008122001'],
            ['nama' => 'Krisfitri Meilana Sari, AMK', 'jabatan' => 'Penata Muda III/a', 'kategori_pegawai' => 'PNS', 'nrp_nip' => '198705302006042001'],
            ['nama' => 'Restu Ariyani, AMK', 'jabatan' => 'Penata Muda Tk.I III/b', 'kategori_pegawai' => 'PNS', 'nrp_nip' => '197908272014102002'],
            ['nama' => 'Afrie Purwoko', 'jabatan' => 'Pengatur Tk.I II/d', 'kategori_pegawai' => 'PNS', 'nrp_nip' => '197912262007011001'],
            ['nama' => 'Nila Rosbalika', 'jabatan' => 'Pengatur II/c', 'kategori_pegawai' => 'PNS', 'nrp_nip' => '197610212014102002'],

            // C. PPPK
            ['nama' => 'Ns. Fajar Adhie Sulistyo, S.Kep', 'jabatan' => 'PPPK Gol. X', 'kategori_pegawai' => 'PPPK', 'nrp_nip' => '198812032025211026'],
            ['nama' => 'Bresna Mayanti, S.K.M.', 'jabatan' => 'PPPK Gol. IX', 'kategori_pegawai' => 'PPPK', 'nrp_nip' => '198706172025212030'],
            ['nama' => 'Grace Cheryl, A.Md.Kep', 'jabatan' => 'PPPK Gol. VII', 'kategori_pegawai' => 'PPPK', 'nrp_nip' => '198411062025212025'],
            ['nama' => 'Ika Juliana S, A.Md.Kep', 'jabatan' => 'PPPK Gol. VII', 'kategori_pegawai' => 'PPPK', 'nrp_nip' => '199707052025212035'],
            ['nama' => 'Yohanita Farida Tirtawati', 'jabatan' => 'PPPK Gol. V', 'kategori_pegawai' => 'PPPK', 'nrp_nip' => '197903302025212009'],
            ['nama' => 'Eldilla Angesti', 'jabatan' => 'PPPK Gol. V', 'kategori_pegawai' => 'PPPK', 'nrp_nip' => '199203052025212045'],
            ['nama' => 'Rina Rosalia', 'jabatan' => 'PPPK Gol. V', 'kategori_pegawai' => 'PPPK', 'nrp_nip' => '199311192025212030'],
            ['nama' => 'Ambarwati', 'jabatan' => 'PPPK Gol. V', 'kategori_pegawai' => 'PPPK', 'nrp_nip' => '199005172025212036'],
            ['nama' => 'Fitri Jayanti', 'jabatan' => 'PPPK Gol. V', 'kategori_pegawai' => 'PPPK', 'nrp_nip' => '199301302025212020'],
            ['nama' => 'Khayadi', 'jabatan' => 'PPPK Gol. V', 'kategori_pegawai' => 'PPPK', 'nrp_nip' => '198909182025211052'],
            ['nama' => 'Irfan Pujianto', 'jabatan' => 'PPPK Gol. V', 'kategori_pegawai' => 'PPPK', 'nrp_nip' => '198405152025211051'],
            ['nama' => 'Wandi', 'jabatan' => 'PPPK Gol. V', 'kategori_pegawai' => 'PPPK', 'nrp_nip' => '199505022025211022'],

            // D. PBLU NON ASN (BLU)
            ['nama' => 'Rosi Agus Setiawan, S.E.', 'jabatan' => 'Pegawai BLU Non ASN', 'kategori_pegawai' => 'BLU', 'nrp_nip' => '210892010109143307'],
            ['nama' => 'Elsa Maghfira Paramesti, S.I.kom', 'jabatan' => 'Pegawai BLU Non ASN', 'kategori_pegawai' => 'BLU', 'nrp_nip' => '180301010108221031'],
            ['nama' => 'Dian Yunita Sari, A.Md.Kep', 'jabatan' => 'Pegawai BLU Non ASN', 'kategori_pegawai' => 'BLU', 'nrp_nip' => '050691010102132021'],
            ['nama' => 'Sri Agung Lestari Ismail, A.Md.kep', 'jabatan' => 'Pegawai BLU Non ASN', 'kategori_pegawai' => 'BLU', 'nrp_nip' => '011087010102132023'],
            ['nama' => 'Ronauly, A.Md.Kep', 'jabatan' => 'Pegawai BLU Non ASN', 'kategori_pegawai' => 'BLU', 'nrp_nip' => '021285010109142246'],
        ];

        foreach ($personel as $data) {
            Anggota::updateOrCreate(
                ['nama' => $data['nama']],
                [
                    'foto' => $fotoPath,
                    'jabatan' => $data['jabatan'],
                    'kategori_pegawai' => $data['kategori_pegawai'],
                    'nrp_nip' => $data['nrp_nip'],
                ]
            );
        }

        $this->command->info('✅ 30 personel Instalasi CVC berhasil di-seed (dengan NRP/NIP).');
    }
}
