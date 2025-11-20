<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartemenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departemens = [
            "ADMINISTRASI",
            "ADUM",
            "ANALIS KESEHATAN LAB",
            "ANESTESI",
            "ASPER",
            "BELUM DITENTUKAN",
            "BIDAN",
            "BISDEV",
            "CLEANING SERVICE",
            "CS",
            "DIREKTUR",
            "DOKTER",
            "DOKTER SPESIALIS",
            "FARMASI",
            "FISIOTERAPI",
            "GENERAL KASIR",
            "GIZI",
            "GUDANG FARMASI",
            "IT",
            "JAN MED",
            "KAMAR BEDAH DAN CSSD",
            "KASIR",
            "KEBIDANAN",
            "KEPERAWATAN",
            "KERUMAHTANGGAAN, LOGISTIK",
            "KESEKRETARIATAN",
            "KESLING",
            "KEUANGAN DAN AKUTANSI",
            "KOMITE ETIK HUKUM",
            "KOMITE K3 RS",
            "KOMITE KEPERAWATAN",
            "KOMITE MEDIS",
            "KOMITE MUTU DAN KESELAMAT",
            "KOMITE NAKES LAIN",
            "KOMITE PPI",
            "KOMITE PPRA",
            "LABORATORIUM",
            "LAUNDRY",
            "LAYANAN UNGGULAN",
            "MARKETING",
            "PANITIA FARMASI DAN TERAP",
            "PANITIA PKRS",
            "PANITIA REKAM MEDIS",
            "PELAPORAN",
            "PENATA ANESTESI",
            "PENDAFTARAN",
            "PENGEMUDI",
            "PENJAMINAN ASURANSI",
            "PENJAMINAN BPJS",
            "PERBENDAHARAAN DAN MOBILI",
            "PERENCANAAN ANGGARAN, AKU",
            "PHM",
            "PSRS",
            "QUALITY CONTROL",
            "RADIOLOGI",
            "RAWAT INAP DAN HCU",
            "RAWAT JALAN DAN HD",
            "REHABILITASI MEDIS",
            "REKAM MEDIS",
            "SATPAM",
            "SDM, DIKLAT",
            "SPI",
            "TIM CASEMIX",
            "TIM KORDIK",
            "TIM PONEX",
            "TIM TB-HIV",
            "UGD",
            "UNGGULAN (LANSIA, ON CALL",
            "UNGGULAN DOKTER",
            "YANMED",
        ];

        $count = 0;
        foreach ($departemens as $nama) {
            // Cek apakah departemen sudah ada
            $exists = DB::table('tb_departemen')->where('nama', $nama)->exists();

            if (!$exists) {
                DB::table('tb_departemen')->insert([
                    'nama' => $nama,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $count++;
            }
        }

        if ($this->command) {
            $this->command->info("DepartemenSeeder: {$count} departemen baru ditambahkan dari " . count($departemens) . " total departemen.");
        }
    }
}
