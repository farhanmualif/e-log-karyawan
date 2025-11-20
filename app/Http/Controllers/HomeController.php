<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Helper function untuk filter log berdasarkan role (menggunakan unit_id dan departemen_id dari log_aktivitas)
        $filterLogQuery = function ($query) use ($user) {
            if ($user->role === 'karyawan') {
                // Karyawan: hanya melihat data sendiri (tidak punya akses ke data orang lain)
                $query->where('log_aktivitas.user_id', $user->id);
            } elseif ($user->role === 'spv') {
                // SPV: melihat data seluruh karyawan di unitnya
                if ($user->unit_id) {
                    $query->where('log_aktivitas.unit_id', $user->unit_id);
                } else {
                    // Jika tidak punya unit, hanya lihat data sendiri
                    $query->where('log_aktivitas.user_id', $user->id);
                }
            } elseif ($user->role === 'manager') {
                // Manager: melihat data seluruh karyawan di departemennya
                if ($user->departemen_id) {
                    $unitIds = DB::table('tb_unit')
                        ->where('departemen_id', $user->departemen_id)
                        ->whereNull('deleted_at')
                        ->pluck('id');
                    $query->whereIn('log_aktivitas.unit_id', $unitIds);
                } else {
                    // Jika tidak punya departemen, hanya lihat data sendiri
                    $query->where('log_aktivitas.user_id', $user->id);
                }
            }
            // SDM/Superadmin/Admin: melihat data seluruh karyawan (tidak perlu filter)
        };

        // 1. Status Log Aktivitas (Donut Chart) - Format data siap pakai
        $statusLogQuery = DB::table('log_aktivitas');
        $filterLogQuery($statusLogQuery);
        $statusLogRaw = $statusLogQuery->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Format untuk chart
        $statusLabels = [];
        $statusData = [];
        foreach (['menunggu', 'tervalidasi', 'ditolak'] as $status) {
            $statusLabels[] = ucfirst($status);
            $statusData[] = isset($statusLogRaw[$status]) ? $statusLogRaw[$status] : 0;
        }

        // 2. Trend Log Aktivitas 7 Hari Terakhir (Line Chart) - Format data siap pakai
        $sevenDaysAgo = Carbon::now()->subDays(6)->startOfDay();
        $trendLogQuery = DB::table('log_aktivitas')
            ->where('tanggal', '>=', $sevenDaysAgo);
        $filterLogQuery($trendLogQuery);
        $trendLogRaw = $trendLogQuery->select(
            DB::raw('DATE(tanggal) as date'),
            DB::raw('COUNT(*) as total')
        )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Format untuk chart
        $trendDates = [];
        $trendCounts = [];
        $dateMap = [];
        foreach ($trendLogRaw as $log) {
            $dateMap[$log->date] = $log->total;
        }

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dateLabel = Carbon::now()->subDays($i)->format('d/m');
            $trendDates[] = $dateLabel;
            $trendCounts[] = isset($dateMap[$date]) ? $dateMap[$date] : 0;
        }

        // 3. Log Aktivitas per Departemen (Bar Chart) - Format data siap pakai
        $logPerDepartemenQuery = DB::table('log_aktivitas')
            ->join('tb_departemen', 'log_aktivitas.departemen_id', '=', 'tb_departemen.id')
            ->whereNull('tb_departemen.deleted_at')
            ->whereNotNull('log_aktivitas.departemen_id');
        $filterLogQuery($logPerDepartemenQuery);
        $logPerDepartemenRaw = $logPerDepartemenQuery->select('tb_departemen.nama', DB::raw('COUNT(log_aktivitas.id) as total'))
            ->groupBy('tb_departemen.nama')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Format untuk chart
        $deptNames = $logPerDepartemenRaw->pluck('nama')->toArray();
        $deptCounts = $logPerDepartemenRaw->pluck('total')->toArray();

        // 4. Top 5 Karyawan Paling Aktif (Bulan ini) - Format data siap pakai
        $topKaryawanQuery = DB::table('log_aktivitas')
            ->join('users', 'log_aktivitas.user_id', '=', 'users.id')
            ->whereMonth('log_aktivitas.tanggal', Carbon::now()->month)
            ->whereYear('log_aktivitas.tanggal', Carbon::now()->year);
        $filterLogQuery($topKaryawanQuery);
        $topKaryawanRaw = $topKaryawanQuery->select(
            'users.name',
            DB::raw('COUNT(log_aktivitas.id) as total')
        )
            ->groupBy('users.name')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Format untuk chart
        $karyawanNames = $topKaryawanRaw->pluck('name')->toArray();
        $karyawanCounts = $topKaryawanRaw->pluck('total')->toArray();

        // 5. Distribusi Aktivitas per Jam (Jam kerja 8-17) - Format data siap pakai
        $activityPerHourQuery = DB::table('log_aktivitas')
            ->where('tanggal', '>=', $sevenDaysAgo)
            ->whereBetween(DB::raw('HOUR(waktu_awal)'), [8, 17]);
        $filterLogQuery($activityPerHourQuery);
        $activityPerHourRaw = $activityPerHourQuery->select(
            DB::raw('HOUR(waktu_awal) as hour'),
            DB::raw('COUNT(*) as total')
        )
            ->groupBy('hour')
            ->orderBy('hour', 'asc')
            ->get();

        // Format untuk chart
        $hourLabels = [];
        $hourCounts = [];
        $hourMap = [];
        foreach ($activityPerHourRaw as $item) {
            $hourMap[$item->hour] = $item->total;
        }

        for ($hour = 8; $hour <= 17; $hour++) {
            $hourLabels[] = $hour . ':00';
            $hourCounts[] = isset($hourMap[$hour]) ? $hourMap[$hour] : 0;
        }

        return view('home', compact(
            'statusLabels',
            'statusData',
            'trendDates',
            'trendCounts',
            'deptNames',
            'deptCounts',
            'karyawanNames',
            'karyawanCounts',
            'hourLabels',
            'hourCounts'
        ));
    }
}
