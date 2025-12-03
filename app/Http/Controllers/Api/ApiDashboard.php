<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
}
