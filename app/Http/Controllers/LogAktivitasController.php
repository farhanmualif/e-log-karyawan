<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LogAktivitasController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = DB::table('log_aktivitas')
            ->join('users', 'log_aktivitas.user_id', '=', 'users.id')
            ->join('tb_departemen', 'users.departemen_id', '=', 'tb_departemen.id')
            ->join('tb_unit', 'users.unit_id', '=', 'tb_unit.id')
            ->select(
                'log_aktivitas.*',
                'users.name as nama_karyawan',
                'users.username as nik',
                'tb_departemen.nama as nama_departemen',
                'tb_unit.nama as nama_unit'
            );

        if ($user->role === 'karyawan') {
            $query->where('log_aktivitas.user_id', $user->id);
        } elseif ($user->role === 'spv') {
            // SPV: melihat log semua karyawan di unitnya (berdasarkan unit_id di log_aktivitas)
            if ($user->unit_id) {
                $query->where('log_aktivitas.unit_id', $user->unit_id);
            } else {
                // Jika SPV tidak punya unit_id, hanya lihat log sendiri
                $query->where('log_aktivitas.user_id', $user->id);
            }
        } elseif ($user->role === 'manager') {
            // Manager: melihat log semua unit dalam departemennya (berdasarkan departemen_id di log_aktivitas)
            if ($user->departemen_id) {
                // Ambil semua unit dalam departemen yang sama
                $unitIds = DB::table('tb_unit')
                    ->where('departemen_id', $user->departemen_id)
                    ->whereNull('deleted_at')
                    ->pluck('id');

                $query->whereIn('log_aktivitas.unit_id', $unitIds);
            } else {
                // Jika Manager tidak punya departemen_id, hanya lihat log sendiri
                $query->where('log_aktivitas.user_id', $user->id);
            }
        }
        // SDM (superadmin): melihat semua log, tidak perlu filter
        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_dari')) {
            $query->where('log_aktivitas.tanggal', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->where('log_aktivitas.tanggal', '<=', $request->tanggal_sampai);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('log_aktivitas.status', $request->status);
        }

        // Filter berdasarkan nama karyawan
        if ($request->filled('nama_karyawan')) {
            $query->where('users.name', 'like', '%' . $request->nama_karyawan . '%');
        }

        $allLogs = $query->orderBy('log_aktivitas.tanggal', 'desc')
            ->orderBy('log_aktivitas.waktu_awal', 'desc')
            ->orderBy('log_aktivitas.created_at', 'desc')
            ->get();

        $groupedLogs = [];
        foreach ($allLogs as $log) {
            $key = $log->tanggal . '_' . $log->user_id;
            if (!isset($groupedLogs[$key])) {
                $groupedLogs[$key] = [
                    'tanggal' => $log->tanggal,
                    'user_id' => $log->user_id,
                    'nama_karyawan' => $log->nama_karyawan,
                    'nik' => $log->nik,
                    'nama_departemen' => $log->nama_departemen ?? 'Belum Ditentukan',
                    'nama_unit' => $log->nama_unit ?? 'Belum Ditentukan',
                    'status' => $log->status,
                    'logs' => [],
                    'total_aktivitas' => 0,
                    'status_count' => [
                        'menunggu' => 0,
                        'tervalidasi' => 0,
                        'ditolak' => 0
                    ]
                ];
            }
            $groupedLogs[$key]['logs'][] = $log;
            $groupedLogs[$key]['total_aktivitas']++;

            // Hitung jumlah per status
            if (isset($log->status) && in_array($log->status, ['menunggu', 'tervalidasi', 'ditolak'])) {
                $groupedLogs[$key]['status_count'][$log->status]++;
            }

            if ($log->status == 'menunggu') {
                $groupedLogs[$key]['status'] = 'menunggu';
            }
        }

        $groupedCollection = collect($groupedLogs)->values();
        $perPage = 15;
        $currentPage = $request->get('page', 1);
        $items = $groupedCollection->slice(($currentPage - 1) * $perPage, $perPage)->map(function ($item) {
            return (object) $item;
        })->all();

        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $groupedCollection->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('log-aktivitas.index', ['logs' => $paginated]);
    }

    public function myActivity(Request $request)
    {
        $user = Auth::user();
        $query = DB::table('log_aktivitas')
            ->join('users', 'log_aktivitas.user_id', '=', 'users.id')
            ->select(
                'log_aktivitas.*',
                'users.name as nama_karyawan',
                'users.username as nik'
            )->where('users.id', $user->id);


        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_dari')) {
            $query->where('log_aktivitas.tanggal', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->where('log_aktivitas.tanggal', '<=', $request->tanggal_sampai);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('log_aktivitas.status', $request->status);
        }

        $allLogs = $query->orderBy('log_aktivitas.tanggal', 'desc')
            ->orderBy('log_aktivitas.waktu_awal', 'desc')
            ->orderBy('log_aktivitas.created_at', 'desc')
            ->get();

        $groupedLogs = [];
        foreach ($allLogs as $log) {
            $key = $log->tanggal . '_' . $log->user_id;
            if (!isset($groupedLogs[$key])) {
                $groupedLogs[$key] = [
                    'tanggal' => $log->tanggal,
                    'user_id' => $log->user_id,
                    'nama_karyawan' => $log->nama_karyawan,
                    'nik' => $log->nik,
                    'status' => $log->status,
                    'logs' => [],
                    'total_aktivitas' => 0,
                    'status_count' => [
                        'menunggu' => 0,
                        'tervalidasi' => 0,
                        'ditolak' => 0
                    ]
                ];
            }
            $groupedLogs[$key]['logs'][] = $log;
            $groupedLogs[$key]['total_aktivitas']++;

            // Hitung jumlah per status
            if (isset($log->status) && in_array($log->status, ['menunggu', 'tervalidasi', 'ditolak'])) {
                $groupedLogs[$key]['status_count'][$log->status]++;
            }

            if ($log->status == 'menunggu') {
                $groupedLogs[$key]['status'] = 'menunggu';
            }
        }

        $groupedCollection = collect($groupedLogs)->values();
        $perPage = 15;
        $currentPage = $request->get('page', 1);
        $items = $groupedCollection->slice(($currentPage - 1) * $perPage, $perPage)->map(function ($item) {
            return (object) $item;
        })->all();

        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $groupedCollection->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('log-aktivitas.my-activity', ['logs' => $paginated]);
    }

    /**
     * Menampilkan form input log aktivitas
     */
    public function create()
    {
        $today = Carbon::today()->format('Y-m-d');
        return view('log-aktivitas.create', compact('today'));
    }

    /**
     * Menyimpan log aktivitas baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date|before_or_equal:today',
            'aktivitas' => 'required|array|min:1',
            'aktivitas.*.waktu_awal' => 'required|date_format:H:i',
            'aktivitas.*.waktu_akhir' => 'required|date_format:H:i',
            'aktivitas.*.aktivitas' => 'required|string|min:10',
        ]);

        foreach ($request->aktivitas as $index => $aktivitas) {
            if (strtotime($aktivitas['waktu_akhir']) <= strtotime($aktivitas['waktu_awal'])) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['aktivitas.' . $index . '.waktu_akhir' => 'Waktu akhir harus setelah waktu awal untuk aktivitas #' . ($index + 1)]);
            }
        }

        // Ambil departemen_id dan unit_id dari user saat ini
        $user = Auth::user();

        // Validasi: User harus sudah memiliki departemen dan unit
        if (!$user->departemen_id || !$user->unit_id) {
            $errorMessage = 'Anda harus menentukan ';
            $missing = [];
            if (!$user->departemen_id) {
                $missing[] = 'Departemen';
            }
            if (!$user->unit_id) {
                $missing[] = 'Unit';
            }
            $errorMessage .= implode(' dan ', $missing) . ' terlebih dahulu sebelum dapat menambahkan log aktivitas. ';
            $errorMessage .= 'Silakan update Profile Anda di menu Profile.';

            return redirect()->back()
                ->withInput()
                ->withErrors(['profile' => $errorMessage])
                ->with('profile_required', true);
        }

        $insertData = [];
        foreach ($request->aktivitas as $aktivitas) {
            $insertData[] = [
                'user_id' => $user->id,
                'tanggal' => $request->tanggal,
                'waktu_awal' => $aktivitas['waktu_awal'],
                'waktu_akhir' => $aktivitas['waktu_akhir'],
                'aktivitas' => $aktivitas['aktivitas'],
                'departemen_id' => $user->departemen_id,
                'unit_id' => $user->unit_id,
                'status' => 'menunggu',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('log_aktivitas')->insert($insertData);

        $jumlahAktivitas = count($insertData);
        $message = $jumlahAktivitas > 1
            ? $jumlahAktivitas . ' log aktivitas berhasil ditambahkan.'
            : 'Log aktivitas berhasil ditambahkan.';

        return redirect()->route('log-aktivitas.index')
            ->with('success', $message);
    }

    /**
     * Menampilkan detail log aktivitas
     */
    public function show(Request $request)
    {
        $tanggal = $request->input('tanggal');
        $userId  = $request->input('user_id');

        if (!$tanggal || !$userId) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi Kesalahan pada parameter tanggal dan user'
                ]);
            }
            return redirect()->back()->with('error', 'Terjadi Kesalahan pada parameter tanggal dan user');
        }

        $karyawan = DB::table('users')
            ->where('id', $userId)
            ->first();

        if (!$karyawan) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Karyawan tidak ditemukan'
                ]);
            }
            return redirect()->back()->with('error', 'Karyawan tidak ditemukan');
        }

        $logs = DB::table('log_aktivitas')
            ->leftJoin('users as validator', 'log_aktivitas.validated_by', '=', 'validator.id')
            ->leftJoin('tb_departemen', 'log_aktivitas.departemen_id', '=', 'tb_departemen.id')
            ->leftJoin('tb_unit', 'log_aktivitas.unit_id', '=', 'tb_unit.id')
            ->select(
                'log_aktivitas.*',
                'validator.name as nama_validator',
                'tb_departemen.nama as nama_departemen',
                'tb_unit.nama as nama_unit'
            )
            ->where('log_aktivitas.tanggal', $tanggal)
            ->where('log_aktivitas.user_id', $userId)
            ->orderBy('log_aktivitas.waktu_awal', 'asc')
            ->get();

        $user = Auth::user();

        $firstLog        = $logs->first();
        $logUnitId       = $firstLog->unit_id        ?? null;
        $logDepartemenId = $firstLog->departemen_id  ?? null;

        //  1. PERMISSION: BOLEH LIHAT HALAMAN?
        $canView = false;

        if ($user->role === 'karyawan') {
            // Karyawan: hanya log miliknya sendiri
            $canView = ($userId == $user->id);
        } elseif ($user->role === 'spv') {
            // SPV: boleh lihat log sendiri ATAU karyawan satu unit
            $canView =
                ($userId == $user->id) ||
                ($user->unit_id && $logUnitId && $user->unit_id == $logUnitId);
        } elseif ($user->role === 'manager') {
            // Manager: boleh lihat log sendiri ATAU semua unit di departemennya
            $canView =
                ($userId == $user->id) ||
                ($user->departemen_id && $logDepartemenId && $user->departemen_id == $logDepartemenId);
        } elseif (in_array($user->role, ['sdm', 'superadmin', 'admin'])) {
            // Role global: boleh lihat semua
            $canView = true;
        }

        if (!$canView) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Akses ditolak'
                ]);
            }
            return redirect()->back()->with('error', 'Akses ditolak');
        }

        // 2. RINGKASAN STATUS HARI ITU
        $status = 'tervalidasi';
        foreach ($logs as $log) {
            if ($log->status === 'menunggu') {
                $status = 'menunggu';
                break;
            } elseif ($log->status === 'ditolak') {
                $status = 'ditolak';
            }
        }

        // 3. PERMISSION: BOLEH EDIT / BULK ?
        $canEdit       = false;
        $canBulkAction = false;

        $isOwner = ($user->id == $userId);

        if ($status === 'menunggu' && $logs->isNotEmpty()) {
            foreach ($logs as $log) {

                if ($log->status !== 'menunggu') continue;

                if ($isOwner) {
                    $canEdit = true;
                    continue;
                }

                if (in_array($user->role, ['admin', 'sdm', 'superadmin'])) {
                    $canEdit       = true;
                    $canBulkAction = true;
                    break;
                }

                if ($user->role === 'spv') {
                    $isAtasan = in_array($karyawan->role, ['spv', 'manager']);
                    if (!$isAtasan && $user->unit_id == $logUnitId) {
                        $canEdit       = true;
                        $canBulkAction = true;
                    }
                    break;
                }

                if ($user->role === 'manager') {
                    $isAtasan = ($karyawan->role === 'manager' && $karyawan->id !== $user->id);

                    if (!$isAtasan && $user->departemen_id == $logDepartemenId) {
                        $canEdit       = true;
                        $canBulkAction = true;
                    }
                    break;
                }
            }
        }

        $departemenNama = $firstLog->nama_departemen ?? 'Belum Ditentukan';
        $unitNama       = $firstLog->nama_unit       ?? 'Belum Ditentukan';

        return view('log-aktivitas.show', [
            'logs'            => $logs,
            'karyawan'        => $karyawan,
            'tanggal'         => $tanggal,
            'status'          => $status,
            'departemen_nama' => $departemenNama,
            'unit_nama'       => $unitNama,
            'karyawan_role'   => $karyawan->role ?? 'karyawan',
            'karyawan_id'     => $karyawan->id,
            'canEdit'         => $canEdit,
            'canBulkAction'   => $canBulkAction,
        ]);
    }


    /**
     * Validasi log aktivitas (Approve)
     */
    public function approve($id)
    {
        $log = DB::table('log_aktivitas')->where('id', $id)->first();

        if (!$log) {
            abort(404);
        }

        $user = Auth::user();
        if (!in_array($user->role, ['spv', 'manager', 'sdm', 'superadmin'])) {
            abort(403);
        }

        $karyawan = DB::table('users')->where('id', $log->user_id)->first();
        $karyawanRole = $karyawan->role ?? 'karyawan';

        $hasAccess = false;

        if ($user->role === 'spv') {
            if ($karyawanRole === 'manager') {
                $hasAccess = false;
            } elseif ($user->unit_id && $log->unit_id) {
                $hasAccess = ($user->unit_id == $log->unit_id);
            }
        } elseif ($user->role === 'manager') {
            if ($user->departemen_id && $log->departemen_id) {
                $hasAccess = ($user->departemen_id == $log->departemen_id);
            }
        } elseif (in_array($user->role, ['sdm', 'superadmin', 'admin'])) {
            $hasAccess = true;
        }

        if (!$hasAccess) {
            abort(403);
        }

        DB::table('log_aktivitas')
            ->where('id', $id)
            ->update([
                'status' => 'tervalidasi',
                'validated_by' => $user->id,
                'validated_at' => now(),
                'updated_at' => now(),
            ]);

        return redirect()->back()->with('success', 'Log aktivitas berhasil divalidasi.');
    }

    /**
     * Tolak log aktivitas (Reject)
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan_validasi' => 'required|string|min:5',
        ]);

        $log = DB::table('log_aktivitas')->where('id', $id)->first();

        if (!$log) {
            abort(404);
        }

        $user = Auth::user();
        if (!in_array($user->role, ['spv', 'manager', 'sdm', 'superadmin'])) {
            abort(403);
        }

        $karyawan = DB::table('users')->where('id', $log->user_id)->first();
        $karyawanRole = $karyawan->role ?? 'karyawan';
        $hasAccess = false;

        if ($user->role === 'spv') {
            // SPV: bisa tolak log karyawan di unitnya, TIDAK bisa tolak manager
            if ($karyawanRole === 'manager') {
                $hasAccess = false;
            } elseif ($user->unit_id && $karyawan->unit_id) {
                $hasAccess = ($user->unit_id == $karyawan->unit_id);
            }
        } elseif ($user->role === 'manager') {
            // Manager: bisa tolak log semua unit dalam departemennya (karyawan dan SPV)
            if ($user->departemen_id && $karyawan->unit_id) {
                $unit = DB::table('tb_unit')
                    ->where('id', $karyawan->unit_id)
                    ->where('departemen_id', $user->departemen_id)
                    ->first();
                $hasAccess = ($unit !== null);
            }
        } elseif (in_array($user->role, ['sdm', 'superadmin', 'admin'])) {
            // SDM/Superadmin/Admin: bisa tolak semua log
            $hasAccess = true;
        }

        if (!$hasAccess) {
            abort(403);
        }

        DB::table('log_aktivitas')
            ->where('id', $id)
            ->update([
                'status' => 'ditolak',
                'validated_by' => $user->id,
                'validated_at' => now(),
                'catatan_validasi' => $request->catatan_validasi,
                'updated_at' => now(),
            ]);

        return redirect()->back()->with('success', 'Log aktivitas ditolak.');
    }

    /**
     * Validasi massal log aktivitas (Approve)
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'selected_items' => 'required|array|min:1',
            'selected_items.*' => 'required|string',
        ]);

        $user = Auth::user();
        if (!in_array($user->role, ['spv', 'manager', 'sdm', 'superadmin', 'admin'])) {
            return back()->with('error', 'Role anda tidak memiliki izin untuk melakukan bulk');
        }

        $updated = 0;
        foreach ($request->selected_items as $item) {
            list($tanggal, $user_id) = explode('_', $item);

            // Ambil log pertama untuk cek unit_id dan departemen_id (menggunakan unit_id dan departemen_id dari log_aktivitas)
            $firstLog = DB::table('log_aktivitas')
                ->where('tanggal', $tanggal)
                ->where('user_id', $user_id)
                ->where('status', 'menunggu')
                ->first();

            if (!$firstLog) {
                continue;
            }

            // Ambil role karyawan yang punya log
            $karyawan = DB::table('users')->where('id', $user_id)->first();
            $karyawanRole = $karyawan->role ?? 'karyawan';

            // Cek akses berdasarkan hierarki dan role
            $hasAccess = false;

            if ($user->role === 'spv') {
                // SPV: bisa validasi log di unitnya, TIDAK bisa validasi manager
                if ($karyawanRole === 'manager') {
                    $hasAccess = false;
                } elseif ($user->unit_id && $firstLog->unit_id) {
                    $hasAccess = ($user->unit_id == $firstLog->unit_id);
                }
            } elseif ($user->role === 'manager') {
                // Manager: bisa validasi log di departemennya (karyawan dan SPV)
                if ($user->departemen_id && $firstLog->departemen_id) {
                    $hasAccess = ($user->departemen_id == $firstLog->departemen_id);
                }
            } elseif (in_array($user->role, ['sdm', 'superadmin', 'admin'])) {
                $hasAccess = true;
            }

            if ($hasAccess) {
                $result = DB::table('log_aktivitas')
                    ->where('tanggal', $tanggal)
                    ->where('user_id', $user_id)
                    ->where('status', 'menunggu')
                    ->update([
                        'status' => 'tervalidasi',
                        'validated_by' => $user->id,
                        'validated_at' => now(),
                        'updated_at' => now(),
                    ]);

                $updated += $result;
            }
        }

        return redirect()->route('log-aktivitas.index')
            ->with('success', $updated . ' log aktivitas berhasil divalidasi.');
    }

    public function bulkReject(Request $request)
    {
        try {
            $request->validate([
                'selected_items' => 'required|array|min:1',
                'selected_items.*' => 'required|string',
                'catatan_validasi' => 'required|string|min:5',
            ]);

            $user = Auth::user();
            if (!$user) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'User tidak terauthentikasi'
                    ]);
                }

                return redirect()->back()->with('error', 'Anda tidak memiliki izin akses untuk melakukan ini');
            }

            if (!in_array($user->role, ['spv', 'manager', 'sdm', 'superadmin', 'admin'])) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'User tidak terauthentikasi'
                    ]);
                }

                return redirect()->back()->with('error', 'Anda tidak memiliki izin akses untuk melakukan ini');
            }

            $updated = 0;
            foreach ($request->selected_items as $item) {
                list($tanggal, $user_id) = explode('_', $item);

                // Cek akses berdasarkan hierarki dan role
                $karyawan = DB::table('users')->where('id', $user_id)->first();
                $karyawanRole = $karyawan->role ?? 'karyawan';
                $hasAccess = false;

                if ($user->role === 'spv') {
                    // SPV: bisa tolak log di unitnya, TIDAK bisa tolak manager
                    if ($karyawanRole === 'manager') {
                        $hasAccess = false;
                    } elseif ($user->unit_id && $karyawan->unit_id) {
                        $hasAccess = ($user->unit_id == $karyawan->unit_id);
                    }
                } elseif ($user->role === 'manager') {
                    // Manager: bisa tolak log di departemennya (karyawan dan SPV)
                    if ($user->departemen_id && $karyawan->unit_id) {
                        $unit = DB::table('tb_unit')
                            ->where('id', $karyawan->unit_id)
                            ->where('departemen_id', $user->departemen_id)
                            ->first();
                        $hasAccess = ($unit !== null);
                    }
                } elseif (in_array($user->role, ['sdm', 'superadmin', 'admin'])) {
                    $hasAccess = true;
                }

                if ($hasAccess) {
                    $result = DB::table('log_aktivitas')
                        ->where('tanggal', $tanggal)
                        ->where('user_id', $user_id)
                        ->where('status', 'menunggu')
                        ->update([
                            'status' => 'ditolak',
                            'validated_by' => $user->id,
                            'validated_at' => now(),
                            'catatan_validasi' => $request->catatan_validasi,
                            'updated_at' => now(),
                        ]);

                    $updated += $result;
                }
            }

            return redirect()->route('log-aktivitas.index')
                ->with('success', $updated . ' log aktivitas ditolak.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Bulk reject error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Validasi massal berdasarkan ID log (untuk halaman show)
     */
    public function bulkApproveByIds(Request $request)
    {
        $request->validate([
            'log_ids' => 'required|array|min:1',
            'log_ids.*' => 'required|integer|exists:log_aktivitas,id',
        ]);

        $user = Auth::user();
        if (!in_array($user->role, ['spv', 'manager', 'sdm', 'superadmin', 'admin'])) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Anda tidak memiliki izin akses untuk melakukan aksi ini'
                ]);
            }

            return redirect()->back()->with('error', 'Anda tidak memiliki izin akses untuk melakukan ini');
        }

        // Filter log berdasarkan akses hierarki
        $logs = DB::table('log_aktivitas')
            ->whereIn('id', $request->log_ids)
            ->where('status', 'menunggu')
            ->get();

        $allowedLogIds = [];
        foreach ($logs as $log) {
            // Ambil role karyawan yang punya log
            $karyawan = DB::table('users')->where('id', $log->user_id)->first();
            $karyawanRole = $karyawan->role ?? 'karyawan';

            // Cek akses berdasarkan unit_id, departemen_id, dan role
            $hasAccess = false;

            if ($user->role === 'spv') {
                // SPV: bisa validasi log di unitnya, TIDAK bisa validasi manager
                if ($karyawanRole === 'manager') {
                    $hasAccess = false;
                } elseif ($user->unit_id && $log->unit_id) {
                    $hasAccess = ($user->unit_id == $log->unit_id);
                }
            } elseif ($user->role === 'manager') {
                // Manager: bisa validasi log di departemennya (karyawan dan SPV)
                if ($user->departemen_id && $log->departemen_id) {
                    $hasAccess = ($user->departemen_id == $log->departemen_id);
                }
            } elseif (in_array($user->role, ['sdm', 'superadmin', 'admin'])) {
                $hasAccess = true;
            }

            if ($hasAccess) {
                $allowedLogIds[] = $log->id;
            }
        }

        $updated = DB::table('log_aktivitas')
            ->whereIn('id', $allowedLogIds)
            ->update([
                'status' => 'tervalidasi',
                'validated_by' => $user->id,
                'validated_at' => now(),
                'updated_at' => now(),
            ]);

        $tanggal = $request->get('tanggal');
        $user_id = $request->get('user_id');

        if ($tanggal && $user_id) {
            return redirect()->route('log-aktivitas.show', ['tanggal' => $tanggal, 'user_id' => $user_id])
                ->with('success', $updated . ' log aktivitas berhasil divalidasi.');
        }

        return redirect()->route('log-aktivitas.index')
            ->with('success', $updated . ' log aktivitas berhasil divalidasi.');
    }

    /**
     * Reject massal berdasarkan ID log (untuk halaman show)
     */
    public function bulkRejectByIds(Request $request)
    {
        $request->validate([
            'log_ids' => 'required|array|min:1',
            'log_ids.*' => 'required|integer|exists:log_aktivitas,id',
            'catatan_validasi' => 'required|string|min:5',
        ]);

        $user = Auth::user();
        if (!$user) {
            abort(403, 'User tidak terautentikasi');
        }

        if (!in_array($user->role, ['spv', 'manager', 'sdm', 'superadmin', 'admin'])) {
            abort(403, 'Anda tidak memiliki izin akses untuk melakukan aksi ini');
        }

        // Filter log berdasarkan akses hierarki
        $logs = DB::table('log_aktivitas')
            ->whereIn('id', $request->log_ids)
            ->where('status', 'menunggu')
            ->get();

        $allowedLogIds = [];
        foreach ($logs as $log) {
            // Ambil role karyawan yang punya log
            $karyawan = DB::table('users')->where('id', $log->user_id)->first();
            $karyawanRole = $karyawan->role ?? 'karyawan';

            // Cek akses berdasarkan unit_id, departemen_id, dan role
            $hasAccess = false;

            if ($user->role === 'spv') {
                // SPV: bisa tolak log di unitnya, TIDAK bisa tolak manager
                if ($karyawanRole === 'manager') {
                    $hasAccess = false;
                } elseif ($user->unit_id && $log->unit_id) {
                    $hasAccess = ($user->unit_id == $log->unit_id);
                }
            } elseif ($user->role === 'manager') {
                // Manager: bisa tolak log di departemennya (karyawan dan SPV)
                if ($user->departemen_id && $log->departemen_id) {
                    $hasAccess = ($user->departemen_id == $log->departemen_id);
                }
            } elseif (in_array($user->role, ['sdm', 'superadmin', 'admin'])) {
                $hasAccess = true;
            }

            if ($hasAccess) {
                $allowedLogIds[] = $log->id;
            }
        }

        $updated = DB::table('log_aktivitas')
            ->whereIn('id', $allowedLogIds)
            ->update([
                'status' => 'ditolak',
                'validated_by' => $user->id,
                'validated_at' => now(),
                'catatan_validasi' => $request->catatan_validasi,
                'updated_at' => now(),
            ]);

        $tanggal = $request->get('tanggal');
        $user_id = $request->get('user_id');

        if ($tanggal && $user_id) {
            return redirect()->route('log-aktivitas.show', ['tanggal' => $tanggal, 'user_id' => $user_id])
                ->with('success', $updated . ' log aktivitas ditolak.');
        }

        return redirect()->route('log-aktivitas.index')
            ->with('success', $updated . ' log aktivitas ditolak.');
    }

    /**
     * Edit log aktivitas (hanya untuk karyawan dan hanya yang status menunggu)
     */
    public function edit($id)
    {
        $log = DB::table('log_aktivitas')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'menunggu')
            ->first();

        if (!$log) {
            abort(404);
        }

        return view('log-aktivitas.edit', compact('log'));
    }

    /**
     * Update log aktivitas
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $log = DB::table('log_aktivitas')
            ->where('id', $id)
            ->where('status', 'menunggu')
            ->first();

        if (!$log) {
            abort(404);
        }

        // Ambil role karyawan yang punya log
        $karyawan = DB::table('users')->where('id', $log->user_id)->first();
        $karyawanRole = $karyawan->role ?? 'karyawan';

        // Cek permission berdasarkan role
        if ($user->role === 'karyawan') {
            // Karyawan hanya bisa edit log sendiri
            if ($log->user_id != $user->id) {
                abort(403);
            }
        } elseif ($user->role === 'spv') {
            // SPV tidak bisa edit log manager
            if ($karyawanRole === 'manager') {
                abort(403);
            }
            // SPV bisa edit log karyawan di unitnya (sudah di-handle di akses view)
        } elseif ($user->role === 'manager') {
            // Manager bisa edit log karyawan dan SPV di departemennya (sudah di-handle di akses view)
        } elseif (!in_array($user->role, ['sdm', 'superadmin', 'admin'])) {
            // Hanya admin, sdm, superadmin yang bisa edit semua
            abort(403);
        }

        $request->validate([
            'waktu_awal' => 'required|date_format:H:i',
            'waktu_akhir' => 'required|date_format:H:i|after:waktu_awal',
            'aktivitas' => 'required|string|min:10',
        ]);

        $log = DB::table('log_aktivitas')
            ->where('id', $id)
            ->first();

        DB::table('log_aktivitas')
            ->where('id', $id)
            ->update([
                'waktu_awal' => $request->waktu_awal,
                'waktu_akhir' => $request->waktu_akhir,
                'aktivitas' => $request->aktivitas,
                'updated_at' => now(),
            ]);

        // Jika ada parameter redirect, redirect ke show page
        if ($request->has('redirect_to_show') && $request->redirect_to_show) {
            return redirect()->route('log-aktivitas.show', [
                'tanggal' => $log->tanggal,
                'user_id' => $log->user_id
            ])->with('success', 'Log aktivitas berhasil diperbarui.');
        }

        return redirect()->route('log-aktivitas.index')
            ->with('success', 'Log aktivitas berhasil diperbarui.');
    }

    /**
     * Hapus log aktivitas (hanya untuk karyawan dan hanya yang status menunggu)
     */
    public function destroy($id)
    {
        $log = DB::table('log_aktivitas')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'menunggu')
            ->first();

        if (!$log) {
            return redirect()->back()->with('error', 'Log aktivitas Gagal dihapus.');
        }

        DB::table('log_aktivitas')->where('id', $id)->delete();

        return redirect()->route('log-aktivitas.index')
            ->with('success', 'Log aktivitas berhasil dihapus.');
    }

    public function detailActivity(Request $request, $user_id)
    {
        try {
            // Get user information
            $user = DB::table('users')
                ->leftJoin('tb_departemen', 'users.departemen_id', '=', 'tb_departemen.id')
                ->leftJoin('tb_unit', 'users.unit_id', '=', 'tb_unit.id')
                ->where('users.id', $user_id)
                ->select(
                    'users.id',
                    'users.name',
                    'users.username as nik',
                    'tb_departemen.nama as nama_departemen',
                    'tb_unit.nama as nama_unit'
                )
                ->first();

            if (!$user) {
                return redirect()->back()->with('error', 'Karyawan tidak ditemukan.');
            }

            // Get all activities for this user
            $activities = DB::table('log_aktivitas')
                ->leftJoin('tb_departemen', 'log_aktivitas.departemen_id', '=', 'tb_departemen.id')
                ->leftJoin('tb_unit', 'log_aktivitas.unit_id', '=', 'tb_unit.id')
                ->leftJoin('users as validator', 'log_aktivitas.validated_by', '=', 'validator.id')
                ->where('log_aktivitas.user_id', $user_id)
                ->select(
                    'log_aktivitas.*',
                    'tb_departemen.nama as nama_departemen',
                    'tb_unit.nama as nama_unit',
                    'validator.name as nama_validator'
                )
                ->orderBy('log_aktivitas.tanggal', 'desc')
                ->orderBy('log_aktivitas.waktu_awal', 'asc')
                ->get();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Berhasil mengambil data detail activity',
                    'data' => $activities
                ]);
            }

            return view('log-aktivitas.detail-activity', compact('user', 'activities'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal mengambil data detail activity: ' . $th->getMessage());
        }
    }

    public function detailActivityByDepId(Request $request, $departemen_id)
    {
        try {

            $departemen = DB::table('tb_departemen')
                ->where('id', $departemen_id)
                ->whereNull('deleted_at')
                ->select('id', 'nama')
                ->first();

            if (!$departemen) {
                return redirect()->back()->with('error', 'Departemen tidak ditemukan.');
            }

            $activities = DB::table('log_aktivitas')
                ->leftJoin('tb_departemen', 'log_aktivitas.departemen_id', '=', 'tb_departemen.id')
                ->leftJoin('tb_unit', 'log_aktivitas.unit_id', '=', 'tb_unit.id')
                ->leftJoin('users', 'log_aktivitas.user_id', '=', 'users.id')
                ->leftJoin('users as validator', 'log_aktivitas.validated_by', '=', 'validator.id')
                ->where('log_aktivitas.departemen_id', $departemen_id)
                ->whereNull('tb_departemen.deleted_at')
                ->select(
                    'log_aktivitas.*',
                    'tb_departemen.nama as nama_departemen',
                    'tb_unit.nama as nama_unit',
                    'users.name as nama_karyawan',
                    'users.username as nik_karyawan',
                    'validator.name as nama_validator'
                )
                ->orderBy('log_aktivitas.tanggal', 'desc')
                ->orderBy('log_aktivitas.waktu_awal', 'asc')
                ->get();

            $totalActivities = $activities->count();
            $totalKaryawan = $activities->pluck('user_id')->unique()->count();
            $statusCounts = $activities->groupBy('status')->map->count();


            $groupedActivities = $activities->groupBy('user_id')->map(function ($karyawanActivities, $userId) use ($request) {
                $karyawan = $karyawanActivities->first();
                $totalAktivitas = $karyawanActivities->count();
                $statusCounts = $karyawanActivities->groupBy('status')->map->count();

                $activityPerPage = $request->get('activity_per_page', 5);
                $activityPage = $request->get("activity_page_{$userId}", 1);

                $activityItems = $karyawanActivities->values()->all();
                $activityTotal = count($activityItems);
                $activityOffset = ($activityPage - 1) * $activityPerPage;
                $activityItemsForPage = array_slice($activityItems, $activityOffset, $activityPerPage);

                $queryParams = $request->query();
                $queryParams["activity_page_{$userId}"] = $activityPage;

                $paginatedActivities = new LengthAwarePaginator(
                    collect($activityItemsForPage),
                    $activityTotal,
                    $activityPerPage,
                    $activityPage,
                    [
                        'path' => $request->url(),
                        'query' => $queryParams,
                        'pageName' => "activity_page_{$userId}",
                    ]
                );

                return [
                    'karyawan' => $karyawan,
                    'activities' => $paginatedActivities,
                    'activities_all' => $karyawanActivities,
                    'total_aktivitas' => $totalAktivitas,
                    'status_counts' => $statusCounts
                ];
            });

            $perPage = $request->get('per_page', 10);
            $currentPage = $request->get('page', 1);
            $items = $groupedActivities->values();
            $total = $items->count();
            $offset = ($currentPage - 1) * $perPage;
            $itemsForCurrentPage = $items->slice($offset, $perPage)->values();

            $paginatedGroupedActivities = new LengthAwarePaginator(
                $itemsForCurrentPage,
                $total,
                $perPage,
                $currentPage,
                [
                    'path' => $request->url(),
                    'query' => $request->query(),
                ]
            );


            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Berhasil mengambil data detail activity',
                    'data' => $activities,
                    'grouped_data' => $groupedActivities,
                    'statistics' => [
                        'total_activities' => $totalActivities,
                        'total_karyawan' => $totalKaryawan,
                        'status_counts' => $statusCounts
                    ]
                ]);
            }

            return view('log-aktivitas.detail-activity-by-departemen', compact('departemen', 'activities', 'totalActivities', 'totalKaryawan', 'statusCounts', 'paginatedGroupedActivities', 'departemen_id'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal mengambil data detail activity: ' . $th->getMessage());
        }
    }


    public function getActivitiesByUser(Request $request, $departemen_id, $user_id)
    {
        try {
            $activities = DB::table('log_aktivitas')
                ->leftJoin('tb_departemen', 'log_aktivitas.departemen_id', '=', 'tb_departemen.id')
                ->leftJoin('tb_unit', 'log_aktivitas.unit_id', '=', 'tb_unit.id')
                ->leftJoin('users', 'log_aktivitas.user_id', '=', 'users.id')
                ->leftJoin('users as validator', 'log_aktivitas.validated_by', '=', 'validator.id')
                ->where('log_aktivitas.departemen_id', $departemen_id)
                ->where('log_aktivitas.user_id', $user_id)
                ->whereNull('tb_departemen.deleted_at')
                ->select(
                    'log_aktivitas.*',
                    'tb_departemen.nama as nama_departemen',
                    'tb_unit.nama as nama_unit',
                    'users.name as nama_karyawan',
                    'users.username as nik_karyawan',
                    'validator.name as nama_validator'
                )
                ->orderBy('log_aktivitas.tanggal', 'desc')
                ->orderBy('log_aktivitas.waktu_awal', 'asc')
                ->get();

            $activityPerPage = $request->get('activity_per_page', 5);
            $activityPage = $request->get("activity_page_{$user_id}", 1);

            $activityItems = $activities->values()->all();
            $activityTotal = count($activityItems);
            $activityOffset = ($activityPage - 1) * $activityPerPage;
            $activityItemsForPage = array_slice($activityItems, $activityOffset, $activityPerPage);

            $queryParams = $request->query();
            $queryParams["activity_page_{$user_id}"] = $activityPage;

            $paginatedActivities = new LengthAwarePaginator(
                collect($activityItemsForPage),
                $activityTotal,
                $activityPerPage,
                $activityPage,
                [
                    'path' => $request->url(),
                    'query' => $queryParams,
                    'pageName' => "activity_page_{$user_id}",
                ]
            );

            $paginationLinks = $this->buildPaginationLinks($paginatedActivities, "activity_page_{$user_id}");

            return response()->json([
                'status' => true,
                'data' => [
                    'data'          => $paginatedActivities->items(),
                    'current_page'  => $paginatedActivities->currentPage(),
                    'last_page'     => $paginatedActivities->lastPage(),
                    'from'          => $paginatedActivities->firstItem(),
                    'to'            => $paginatedActivities->lastItem(),
                    'total'         => $paginatedActivities->total(),
                    'links'         => $paginationLinks,
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data activities: ' . $th->getMessage()
            ], 500);
        }
    }

    private function buildPaginationLinks(LengthAwarePaginator $paginator, string $pageName)
    {
        $links = [];

        // Previous Page
        $links[] = [
            'url' => $paginator->previousPageUrl(),
            'label' => '&laquo; Sebelumnya',
            'active' => false,
        ];

        // Numbered Pages
        for ($i = 1; $i <= $paginator->lastPage(); $i++) {
            $links[] = [
                'url' => $paginator->url($i),
                'label' => (string) $i,
                'active' => $i === $paginator->currentPage(),
            ];
        }

        // Next Page
        $links[] = [
            'url' => $paginator->nextPageUrl(),
            'label' => 'Berikutnya &raquo;',
            'active' => false,
        ];

        return $links;
    }
}
