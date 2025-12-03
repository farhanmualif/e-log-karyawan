<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LogAktivitasSeeder extends Seeder
{
    public function run()
    {
        $userId = 4;
        $unitId = 1;
        $departemenId = 68;

        $user = DB::table('users')->where('id', $userId)->first();
        if (!$user) {
            if ($this->command) {
                $this->command->error("User dengan ID {$userId} (Wasiran) tidak ditemukan!");
            }
            return;
        }

        $logAktivitas = [
            [
                'user_id' => $userId,
                'tanggal' => Carbon::today()->format('Y-m-d'),
                'waktu_awal' => '08:00:00',
                'waktu_akhir' => '10:00:00',
                'aktivitas' => 'Melakukan pengecekan dan pemeliharaan peralatan medis di unit kerja. Memastikan semua peralatan dalam kondisi baik dan siap digunakan untuk pelayanan pasien.',
                'departemen_id' => $departemenId,
                'unit_id' => $unitId,
                'status' => 'menunggu',
                'validated_by' => null,
                'validated_at' => null,
                'catatan_validasi' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => $userId,
                'tanggal' => Carbon::today()->format('Y-m-d'),
                'waktu_awal' => '10:30:00',
                'waktu_akhir' => '12:00:00',
                'aktivitas' => 'Mengikuti rapat koordinasi dengan tim untuk membahas rencana kerja minggu ini. Membahas target dan prioritas kegiatan yang akan dilaksanakan.',
                'departemen_id' => $departemenId,
                'unit_id' => $unitId,
                'status' => 'menunggu',
                'validated_by' => null,
                'validated_at' => null,
                'catatan_validasi' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => $userId,
                'tanggal' => Carbon::today()->format('Y-m-d'),
                'waktu_awal' => '13:00:00',
                'waktu_akhir' => '15:30:00',
                'aktivitas' => 'Melakukan dokumentasi dan pelaporan kegiatan harian. Menyusun laporan aktivitas dan mempersiapkan data untuk evaluasi kinerja.',
                'departemen_id' => $departemenId,
                'unit_id' => $unitId,
                'status' => 'menunggu',
                'validated_by' => null,
                'validated_at' => null,
                'catatan_validasi' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => $userId,
                'tanggal' => Carbon::yesterday()->format('Y-m-d'),
                'waktu_awal' => '06:00:00',
                'waktu_akhir' => '08:00:00',
                'aktivitas' => 'Melakukan persiapan awal kegiatan kerja. Memeriksa jadwal dan menyiapkan peralatan yang diperlukan untuk aktivitas hari ini.',
                'departemen_id' => $departemenId,
                'unit_id' => $unitId,
                'status' => 'tervalidasi',
                'validated_by' => 4, // Eka Iswanta (SPV)
                'validated_at' => Carbon::yesterday()->setTime(16, 0, 0),
                'catatan_validasi' => null,
                'created_at' => Carbon::yesterday()->setTime(8, 30, 0),
                'updated_at' => Carbon::yesterday()->setTime(16, 0, 0),
            ],
            [
                'user_id' => $userId,
                'tanggal' => Carbon::yesterday()->format('Y-m-d'),
                'waktu_awal' => '08:30:00',
                'waktu_akhir' => '11:00:00',
                'aktivitas' => 'Melakukan pelayanan kepada pasien dan melakukan koordinasi dengan tim medis. Memastikan proses pelayanan berjalan dengan baik dan sesuai prosedur.',
                'departemen_id' => $departemenId,
                'unit_id' => $unitId,
                'status' => 'tervalidasi',
                'validated_by' => 4,
                'validated_at' => Carbon::yesterday()->setTime(16, 0, 0),
                'catatan_validasi' => null,
                'created_at' => Carbon::yesterday()->setTime(11, 30, 0),
                'updated_at' => Carbon::yesterday()->setTime(16, 0, 0),
            ],
            [
                'user_id' => $userId,
                'tanggal' => Carbon::yesterday()->format('Y-m-d'),
                'waktu_awal' => '13:30:00',
                'waktu_akhir' => '16:00:00',
                'aktivitas' => 'Melakukan evaluasi dan review kegiatan yang telah dilaksanakan. Menyusun catatan dan dokumentasi untuk keperluan pelaporan.',
                'departemen_id' => $departemenId,
                'unit_id' => $unitId,
                'status' => 'tervalidasi',
                'validated_by' => 4,
                'validated_at' => Carbon::yesterday()->setTime(16, 0, 0),
                'catatan_validasi' => null,
                'created_at' => Carbon::yesterday()->setTime(16, 30, 0),
                'updated_at' => Carbon::yesterday()->setTime(16, 0, 0),
            ],

            [
                'user_id' => $userId,
                'tanggal' => Carbon::yesterday()->subDay()->format('Y-m-d'),
                'waktu_awal' => '07:00:00',
                'waktu_akhir' => '09:30:00',
                'aktivitas' => 'Melakukan pengecekan rutin dan pemeliharaan fasilitas kerja. Memastikan lingkungan kerja dalam kondisi bersih dan aman.',
                'departemen_id' => $departemenId,
                'unit_id' => $unitId,
                'status' => 'tervalidasi',
                'validated_by' => 4,
                'validated_at' => Carbon::yesterday()->subDay()->setTime(17, 0, 0),
                'catatan_validasi' => null,
                'created_at' => Carbon::yesterday()->subDay()->setTime(10, 0, 0),
                'updated_at' => Carbon::yesterday()->subDay()->setTime(17, 0, 0),
            ],
            [
                'user_id' => $userId,
                'tanggal' => Carbon::yesterday()->subDay()->format('Y-m-d'),
                'waktu_awal' => '10:00:00',
                'waktu_akhir' => '12:30:00',
                'aktivitas' => 'Mengikuti pelatihan dan pengembangan kompetensi. Meningkatkan pengetahuan dan keterampilan dalam bidang pekerjaan.',
                'departemen_id' => $departemenId,
                'unit_id' => $unitId,
                'status' => 'tervalidasi',
                'validated_by' => 4,
                'validated_at' => Carbon::yesterday()->subDay()->setTime(17, 0, 0),
                'catatan_validasi' => null,
                'created_at' => Carbon::yesterday()->subDay()->setTime(13, 0, 0),
                'updated_at' => Carbon::yesterday()->subDay()->setTime(17, 0, 0),
            ],
            [
                'user_id' => $userId,
                'tanggal' => Carbon::yesterday()->subDay()->format('Y-m-d'),
                'waktu_awal' => '14:00:00',
                'waktu_akhir' => '17:00:00',
                'aktivitas' => 'Melakukan koordinasi dengan rekan kerja dan atasan. Membahas progress kerja dan rencana kegiatan selanjutnya.',
                'departemen_id' => $departemenId,
                'unit_id' => $unitId,
                'status' => 'tervalidasi',
                'validated_by' => 4,
                'validated_at' => Carbon::yesterday()->subDay()->setTime(17, 0, 0),
                'catatan_validasi' => null,
                'created_at' => Carbon::yesterday()->subDay()->setTime(17, 30, 0),
                'updated_at' => Carbon::yesterday()->subDay()->setTime(17, 0, 0),
            ],

            [
                'user_id' => $userId,
                'tanggal' => Carbon::yesterday()->subDays(2)->format('Y-m-d'),
                'waktu_awal' => '08:00:00',
                'waktu_akhir' => '10:00:00',
                'aktivitas' => 'Melakukan aktivitas rutin harian di unit kerja.',
                'departemen_id' => $departemenId,
                'unit_id' => $unitId,
                'status' => 'ditolak',
                'validated_by' => 4,
                'validated_at' => Carbon::yesterday()->subDays(2)->setTime(16, 0, 0),
                'catatan_validasi' => 'Deskripsi aktivitas terlalu singkat dan kurang detail. Harap lengkapi dengan penjelasan yang lebih rinci mengenai kegiatan yang dilakukan.',
                'created_at' => Carbon::yesterday()->subDays(2)->setTime(10, 30, 0),
                'updated_at' => Carbon::yesterday()->subDays(2)->setTime(16, 0, 0),
            ],
            [
                'user_id' => $userId,
                'tanggal' => Carbon::yesterday()->subDays(2)->format('Y-m-d'),
                'waktu_awal' => '10:30:00',
                'waktu_akhir' => '12:00:00',
                'aktivitas' => 'Melakukan koordinasi dengan tim.',
                'departemen_id' => $departemenId,
                'unit_id' => $unitId,
                'status' => 'ditolak',
                'validated_by' => 4,
                'validated_at' => Carbon::yesterday()->subDays(2)->setTime(16, 0, 0),
                'catatan_validasi' => 'Deskripsi aktivitas kurang jelas. Mohon jelaskan lebih detail tentang topik koordinasi dan hasil yang dicapai.',
                'created_at' => Carbon::yesterday()->subDays(2)->setTime(12, 30, 0),
                'updated_at' => Carbon::yesterday()->subDays(2)->setTime(16, 0, 0),
            ],

            [
                'user_id' => $userId,
                'tanggal' => Carbon::yesterday()->subDays(3)->format('Y-m-d'),
                'waktu_awal' => '08:00:00',
                'waktu_akhir' => '11:00:00',
                'aktivitas' => 'Melakukan pengecekan dan verifikasi data pasien. Memastikan kelengkapan dokumen dan akurasi informasi yang tercatat dalam sistem.',
                'departemen_id' => $departemenId,
                'unit_id' => $unitId,
                'status' => 'tervalidasi',
                'validated_by' => 4,
                'validated_at' => Carbon::yesterday()->subDays(3)->setTime(17, 0, 0),
                'catatan_validasi' => null,
                'created_at' => Carbon::yesterday()->subDays(3)->setTime(11, 30, 0),
                'updated_at' => Carbon::yesterday()->subDays(3)->setTime(17, 0, 0),
            ],
            [
                'user_id' => $userId,
                'tanggal' => Carbon::yesterday()->subDays(3)->format('Y-m-d'),
                'waktu_awal' => '13:00:00',
                'waktu_akhir' => '15:00:00',
                'aktivitas' => 'Melakukan pendampingan dan asistensi dalam proses pelayanan. Membantu memastikan proses berjalan lancar dan sesuai standar operasional.',
                'departemen_id' => $departemenId,
                'unit_id' => $unitId,
                'status' => 'tervalidasi',
                'validated_by' => 4,
                'validated_at' => Carbon::yesterday()->subDays(3)->setTime(17, 0, 0),
                'catatan_validasi' => null,
                'created_at' => Carbon::yesterday()->subDays(3)->setTime(15, 30, 0),
                'updated_at' => Carbon::yesterday()->subDays(3)->setTime(17, 0, 0),
            ],
        ];

        $inserted = 0;
        $skipped = 0;

        foreach ($logAktivitas as $log) {
            $exists = DB::table('log_aktivitas')
                ->where('user_id', $log['user_id'])
                ->where('tanggal', $log['tanggal'])
                ->where('waktu_awal', $log['waktu_awal'])
                ->where('waktu_akhir', $log['waktu_akhir'])
                ->exists();

            if (!$exists) {
                DB::table('log_aktivitas')->insert($log);
                $inserted++;
            } else {
                $skipped++;
            }
        }

        if ($this->command) {
            $this->command->info("LogAktivitasWasiranSeeder:");
            $this->command->info("  - {$inserted} log aktivitas baru ditambahkan untuk Wasiran (ID: {$userId})");
            if ($skipped > 0) {
                $this->command->warn("  - {$skipped} log aktivitas dilewati (sudah ada)");
            }
        }
    }
}
