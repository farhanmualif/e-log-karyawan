<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // filter log berdasarkan role (menggunakan unit_id dan departemen_id dari log_aktivitas)
        $filterLogQuery = function ($query) use ($user) {
            if ($user->role === 'karyawan') {
                $query->where('log_aktivitas.user_id', $user->id);
            } elseif ($user->role === 'spv') {
                if ($user->unit_id) {
                    $query->where('log_aktivitas.unit_id', $user->unit_id);
                } else {
                    $query->where('log_aktivitas.user_id', $user->id);
                }
            } elseif ($user->role === 'manager') {
                if ($user->departemen_id) {
                    $unitIds = DB::table('tb_unit')
                        ->where('departemen_id', $user->departemen_id)
                        ->whereNull('deleted_at')
                        ->pluck('id');
                    $query->whereIn('log_aktivitas.unit_id', $unitIds);
                } else {
                    $query->where('log_aktivitas.user_id', $user->id);
                }
            }
        };

        $statusLogQuery = DB::table('log_aktivitas');
        $filterLogQuery($statusLogQuery);
        $statusLogRaw = $statusLogQuery->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $statusLabels = [];
        $statusData = [];
        foreach (['menunggu', 'tervalidasi', 'ditolak'] as $status) {
            $statusLabels[] = ucfirst($status);
            $statusData[] = isset($statusLogRaw[$status]) ? $statusLogRaw[$status] : 0;
        }

        // Trend Log Aktivitas 7 Hari Terakhir (Line Chart) - Format data siap pakai
        $sevenDaysAgo = Carbon::now()->subDays(6)->startOfDay();
        $trendLogQuery = DB::table('log_aktivitas')->where('tanggal', '>=', $sevenDaysAgo);
        $filterLogQuery($trendLogQuery);
        $trendLogRaw = $trendLogQuery->select(
            DB::raw('DATE(tanggal) as date'),
            DB::raw('COUNT(*) as total')
        )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

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

        //  Top 5 Log Aktivitas per Departemen (Bar Chart) - Format data siap pakai
        $logPerDepartemenQuery = DB::table('log_aktivitas')
            ->join('tb_departemen', 'log_aktivitas.departemen_id', '=', 'tb_departemen.id')
            ->whereNull('tb_departemen.deleted_at')
            ->whereNotNull('log_aktivitas.departemen_id');

        $filterLogQuery($logPerDepartemenQuery);

        // Query untuk Top 5
        $logPerDepartemenRaw = $logPerDepartemenQuery->select('tb_departemen.nama', DB::raw('COUNT(log_aktivitas.id) as total'))
            ->groupBy('tb_departemen.nama')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        $deptNames = $logPerDepartemenRaw->pluck('nama')->toArray();
        $deptCounts = $logPerDepartemenRaw->pluck('total')->toArray();

        // Query untuk semua departemen (detail)
        $logPerDepartemenDetailQuery = DB::table('log_aktivitas')
            ->join('tb_departemen', 'log_aktivitas.departemen_id', '=', 'tb_departemen.id')
            ->whereNull('tb_departemen.deleted_at')
            ->whereNotNull('log_aktivitas.departemen_id');

        $filterLogQuery($logPerDepartemenDetailQuery);

        $logPerDepartemenDetailRaw = $logPerDepartemenDetailQuery->select('tb_departemen.id as departemen_id', 'tb_departemen.nama', DB::raw('COUNT(log_aktivitas.id) as total'))
            ->groupBy('tb_departemen.id', 'tb_departemen.nama')
            ->orderBy('total', 'desc')
            ->get();

        $deptNamesDetail = $logPerDepartemenDetailRaw->pluck('nama')->toArray();
        $deptTotalDetail = $logPerDepartemenDetailRaw->pluck('total')->toArray();
        $deptIdsDetail = $logPerDepartemenDetailRaw->pluck('departemen_id')->toArray();

        // Top 5 Karyawan Paling Aktif (Bulan ini) - Format data siap pakai
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

        $activityTimelineQuery = DB::table('log_aktivitas')
            ->leftJoin('users', 'log_aktivitas.user_id', '=', 'users.id')
            ->leftJoin('tb_departemen', 'log_aktivitas.departemen_id', '=', 'tb_departemen.id')
            ->where('tanggal', '>=', $sevenDaysAgo)
            ->whereNotNull('log_aktivitas.user_id');
        $filterLogQuery($activityTimelineQuery);

        $activityTimelineData = $activityTimelineQuery->select(
            'users.id as user_id',
            'users.name as nama_karyawan',
            'users.username as nik_karyawan',
            'tb_departemen.nama as nama_departemen',
            'log_aktivitas.aktivitas',
            'log_aktivitas.tanggal',
            'log_aktivitas.waktu_awal',
            'log_aktivitas.waktu_akhir',
            'log_aktivitas.id as activity_id'
        )
            ->orderBy('users.name', 'asc')
            ->orderBy('log_aktivitas.tanggal', 'desc')
            ->orderBy('log_aktivitas.waktu_awal', 'asc')
            ->get();

        // Group by karyawan dan format untuk timeline chart
        $timelineData = [];
        $karyawanList = [];
        $karyawanIndexMap = [];

        foreach ($activityTimelineData as $activity) {
            $userId = $activity->user_id;
            $karyawanName = $activity->nama_karyawan ?? 'Unknown';

            if (!isset($karyawanIndexMap[$userId])) {
                $karyawanIndexMap[$userId] = count($karyawanList);
                $karyawanList[] = [
                    'id' => $userId,
                    'name' => $karyawanName,
                    'nik' => $activity->nik_karyawan ?? '-',
                    'departemen' => $activity->nama_departemen ?? '-',
                ];
            }

            $waktuAwal = $activity->waktu_awal;
            $waktuAkhir = $activity->waktu_akhir;

            if ($waktuAwal && $waktuAkhir) {
                $startParts = explode(':', $waktuAwal);
                $endParts = explode(':', $waktuAkhir);

                if (count($startParts) >= 2 && count($endParts) >= 2) {
                    $startMinutes = (int)$startParts[0] * 60 + (int)$startParts[1];
                    $endMinutes = (int)$endParts[0] * 60 + (int)$endParts[1];

                    $startFrom0 = $startMinutes;
                    $endFrom0 = $endMinutes;

                    if ($endFrom0 > $startFrom0) {
                        if (!isset($timelineData[$userId])) {
                            $timelineData[$userId] = [];
                        }

                        $timelineData[$userId][] = [
                            'x' => $karyawanName,
                            'y' => [$startFrom0, $endFrom0],
                            'aktivitas' => $activity->aktivitas,
                            'tanggal' => $activity->tanggal,
                            'waktu_awal' => $waktuAwal,
                            'waktu_akhir' => $waktuAkhir,
                            'activity_id' => $activity->activity_id,
                        ];
                    }
                }
            }
        }

        $timelineSeries = [];
        foreach ($karyawanList as $karyawan) {
            $userId = $karyawan['id'];
            if (isset($timelineData[$userId]) && count($timelineData[$userId]) > 0) {
                $timelineSeries = array_merge($timelineSeries, $timelineData[$userId]);
            }
        }

        // Create hour labels for x-axis (8:00 to 17:00 in minutes from 8:00)
        $hourLabels = [];
        for ($hour = 8; $hour <= 17; $hour++) {
            $hourLabels[] = $hour . ':00';
        }

        // Get unique karyawan names for y-axis
        $karyawanNames = array_column($karyawanList, 'name');

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
            'timelineSeries',
            'karyawanList',
            'deptNamesDetail',
            'deptTotalDetail',
            'deptIdsDetail'
        ));
    }
}
