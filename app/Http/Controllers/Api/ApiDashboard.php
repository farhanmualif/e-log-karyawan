<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ApiDashboard extends Controller
{
    public function getLogActivityStatus(Request $request)
    {
        try {
            $request->validate([
                'status_log_start' => 'nullable|date',
                'status_log_end' => 'nullable|date'
            ]);

            $startDate = $request->input('status_log_start');
            $endDate = $request->input('status_log_end');

            $currentUser = Auth::user();
            $baseQuery = DB::table('log_aktivitas')
                ->join('users', 'log_aktivitas.user_id', '=', 'users.id');

            // Apply date filter if provided
            if ($startDate && $endDate) {
                $baseQuery->whereBetween('log_aktivitas.tanggal', [$startDate, $endDate]);
            } elseif ($startDate) {
                $baseQuery->where('log_aktivitas.tanggal', '>=', $startDate);
            } elseif ($endDate) {
                $baseQuery->where('log_aktivitas.tanggal', '<=', $endDate);
            }

            // Filter berdasarkan role
            if (\in_array($currentUser->role, ['admin', 'superadmin', 'sdm'])) {
                // Admin, superadmin, sdm: bisa lihat semua
            } else if ($currentUser->role == 'manager') {
                $baseQuery
                    ->whereIn('users.role', ['karyawan', 'spv'])
                    ->where('users.departemen_id', $currentUser->departemen_id);
            } else if ($currentUser->role == 'spv') {
                $baseQuery
                    ->where('users.role', 'karyawan')
                    ->where('users.unit_id', $currentUser->unit_id);
            } else {
                // Karyawan: hanya bisa lihat data sendiri
                $baseQuery->where('log_aktivitas.user_id', $currentUser->id);
            }

            // Group by status and count
            $statusLogRaw = $baseQuery
                ->select('log_aktivitas.status', DB::raw('COUNT(*) as total'))
                ->groupBy('log_aktivitas.status')
                ->pluck('total', 'status')
                ->toArray();

            // Format untuk chart
            $statusLabels = [];
            $statusData = [];
            foreach (['menunggu', 'tervalidasi', 'ditolak'] as $status) {
                $statusLabels[] = ucfirst($status);
                $statusData[] = isset($statusLogRaw[$status]) ? $statusLogRaw[$status] : 0;
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mendapatkan data Log aktifitas status',
                'data' => [
                    'labels' => $statusLabels,
                    'data' => $statusData,
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mendapatkan data Log aktifitas status. ' . $th->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function getActivityByDepartemen(Request $request)
    {
        try {
            $request->validate([
                'dept_activity_start' => 'nullable|date',
                'dept_activity_end' => 'nullable|date'
            ]);

            $startDate = $request->input('dept_activity_start');
            $endDate = $request->input('dept_activity_end');

            $currentUser = Auth::user();

            $logPerDepartemenDetailQuery = DB::table('log_aktivitas')
                ->join('tb_departemen', 'log_aktivitas.departemen_id', '=', 'tb_departemen.id')
                ->whereNull('tb_departemen.deleted_at')
                ->whereNotNull('log_aktivitas.departemen_id');

            if ($startDate && $endDate) {
                $logPerDepartemenDetailQuery->whereBetween('log_aktivitas.tanggal', [$startDate, $endDate]);
            } elseif ($startDate) {
                $logPerDepartemenDetailQuery->where('log_aktivitas.tanggal', '>=', $startDate);
            } elseif ($endDate) {
                $logPerDepartemenDetailQuery->where('log_aktivitas.tanggal', '<=', $endDate);
            }

            if (\in_array($currentUser->role, ['admin', 'superadmin', 'sdm'])) {
            } else if ($currentUser->role == 'manager') {
                $logPerDepartemenDetailQuery
                    ->join('users', 'log_aktivitas.user_id', '=', 'users.id')
                    ->whereIn('users.role', ['karyawan', 'spv'])
                    ->where('users.departemen_id', $currentUser->departemen_id);
            } else if ($currentUser->role == 'spv') {
                $logPerDepartemenDetailQuery
                    ->join('users', 'log_aktivitas.user_id', '=', 'users.id')
                    ->where('users.role', 'karyawan')
                    ->where('users.unit_id', $currentUser->unit_id);
            } else {
                $logPerDepartemenDetailQuery->where('log_aktivitas.user_id', $currentUser->id);
            }

            $logPerDepartemenDetailRaw = $logPerDepartemenDetailQuery
                ->select('tb_departemen.id as departemen_id', 'tb_departemen.nama', DB::raw('COUNT(log_aktivitas.id) as total'))
                ->groupBy('tb_departemen.id', 'tb_departemen.nama')
                ->orderBy('total', 'desc')
                ->get();

            $deptNamesDetail = $logPerDepartemenDetailRaw->pluck('nama')->toArray();
            $deptTotalDetail = $logPerDepartemenDetailRaw->pluck('total')->toArray();
            $deptIdsDetail = $logPerDepartemenDetailRaw->pluck('departemen_id')->toArray();

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mendapatkan data aktivitas per departemen',
                'data' => [
                    'deptNamesDetail' => $deptNamesDetail,
                    'deptTotalDetail' => $deptTotalDetail,
                    'deptIdsDetail' => $deptIdsDetail,
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mendapatkan data aktivitas per departemen. ' . $th->getMessage(),
                'data' => null
            ], 500);
        }
    }


    public function getActivityPerjam(Request $request)
    {
        try {
            $request->validate([
                'activity_perjam_date' => 'nullable|date'
            ]);

            $date = $request->input('activity_perjam_date');

            $currentUser = Auth::user();

            $filterLogQuery = function ($query) use ($currentUser) {
                if ($currentUser->role === 'karyawan') {
                    $query->where('log_aktivitas.user_id', $currentUser->id);
                } elseif ($currentUser->role === 'spv') {
                    if ($currentUser->unit_id) {
                        $query->where('log_aktivitas.unit_id', $currentUser->unit_id);
                    } else {
                        $query->where('log_aktivitas.user_id', $currentUser->id);
                    }
                } elseif ($currentUser->role === 'manager') {
                    if ($currentUser->departemen_id) {
                        $unitIds = DB::table('tb_unit')
                            ->where('departemen_id', $currentUser->departemen_id)
                            ->whereNull('deleted_at')
                            ->pluck('id');
                        $query->whereIn('log_aktivitas.unit_id', $unitIds);
                    } else {
                        $query->where('log_aktivitas.user_id', $currentUser->id);
                    }
                }
                // Admin, superadmin, sdm: tidak perlu filter tambahan (lihat semua)
            };

            // Query untuk timeline aktivitas
            $activityTimelineQuery = DB::table('log_aktivitas')
                ->leftJoin('users', 'log_aktivitas.user_id', '=', 'users.id')
                ->leftJoin('tb_departemen', 'log_aktivitas.departemen_id', '=', 'tb_departemen.id')
                ->whereNotNull('log_aktivitas.user_id');

            // Apply role-based filter FIRST (sebelum filter tanggal)
            $filterLogQuery($activityTimelineQuery);

            // Apply date filter (filter berdasarkan tanggal tertentu saja)
            if ($date) {
                // Pastikan format tanggal benar (Y-m-d)
                $dateFormatted = Carbon::parse($date)->format('Y-m-d');
                // Gunakan whereRaw dengan DATE() untuk memastikan perbandingan tanggal benar
                $activityTimelineQuery->whereRaw('DATE(log_aktivitas.tanggal) = ?', [$dateFormatted]);
            }

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

            // Get unique karyawan names for y-axis
            $karyawanNames = array_column($karyawanList, 'name');

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mendapatkan data aktivitas per jam',
                'data' => [
                    'timelineSeries' => $timelineSeries,
                    'karyawanList' => $karyawanList,
                    'karyawanNames' => $karyawanNames,
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mendapatkan data aktivitas per jam. ' . $th->getMessage(),
                'data' => null
            ], 500);
        }
    }
}
