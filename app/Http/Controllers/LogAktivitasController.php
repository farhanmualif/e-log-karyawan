<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
            ->select(
                'log_aktivitas.*',
                'users.name as nama_karyawan',
                'users.username as nik'
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
        $tanggal = $request->get('tanggal');
        $user_id = $request->get('user_id');

        if (!$tanggal || !$user_id) {
            abort(404);
        }

        // Ambil info karyawan
        $karyawan = DB::table('users')
            ->where('id', $user_id)
            ->first();

        if (!$karyawan) {
            abort(404);
        }

        // Ambil semua log untuk tanggal dan user tersebut
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
            ->where('log_aktivitas.user_id', $user_id)
            ->orderBy('log_aktivitas.waktu_awal', 'asc')
            ->get();

        // Cek akses berdasarkan hierarki (menggunakan unit_id dan departemen_id dari log_aktivitas)
        $user = Auth::user();
        $hasAccess = false;

        // Ambil unit_id dan departemen_id dari log pertama (semua log dalam satu hari seharusnya sama)
        $firstLog = $logs->first();
        $logUnitId = $firstLog ? $firstLog->unit_id : null;
        $logDepartemenId = $firstLog ? $firstLog->departemen_id : null;

        if ($user->role === 'karyawan') {
            // Karyawan: hanya bisa lihat log sendiri
            $hasAccess = ($user_id == $user->id);
        } elseif ($user->role === 'spv') {
            // SPV: bisa lihat log semua karyawan di unitnya (berdasarkan unit_id di log_aktivitas)
            if ($user->unit_id && $logUnitId) {
                $hasAccess = ($user->unit_id == $logUnitId);
            } else {
                $hasAccess = ($user_id == $user->id);
            }
        } elseif ($user->role === 'manager') {
            // Manager: bisa lihat log semua unit dalam departemennya (berdasarkan departemen_id di log_aktivitas)
            if ($user->departemen_id && $logDepartemenId) {
                $hasAccess = ($user->departemen_id == $logDepartemenId);
            } else {
                $hasAccess = ($user_id == $user->id);
            }
        } elseif (in_array($user->role, ['sdm', 'superadmin', 'admin'])) {
            // SDM/Superadmin/Admin: bisa lihat semua log
            $hasAccess = true;
        }

        if (!$hasAccess) {
            abort(403);
        }

        // Tentukan status (jika ada yang menunggu, status = menunggu)
        $status = 'tervalidasi';
        foreach ($logs as $log) {
            if ($log->status == 'menunggu') {
                $status = 'menunggu';
                break;
            } elseif ($log->status == 'ditolak') {
                $status = 'ditolak';
            }
        }

        // Ambil nama departemen dan unit dari log pertama
        $firstLog = $logs->first();
        $departemenNama = $firstLog ? ($firstLog->nama_departemen ?? 'Belum Ditentukan') : 'Belum Ditentukan';
        $unitNama = $firstLog ? ($firstLog->nama_unit ?? 'Belum Ditentukan') : 'Belum Ditentukan';

        return view('log-aktivitas.show', [
            'logs' => $logs,
            'karyawan' => $karyawan,
            'tanggal' => $tanggal,
            'status' => $status,
            'departemen_nama' => $departemenNama,
            'unit_nama' => $unitNama
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

        // Cek akses berdasarkan hierarki (menggunakan unit_id dan departemen_id dari log_aktivitas)
        $hasAccess = false;

        if ($user->role === 'spv') {
            // SPV: bisa validasi log karyawan di unitnya (berdasarkan unit_id di log_aktivitas)
            if ($user->unit_id && $log->unit_id) {
                $hasAccess = ($user->unit_id == $log->unit_id);
            }
        } elseif ($user->role === 'manager') {
            // Manager: bisa validasi log semua unit dalam departemennya (berdasarkan departemen_id di log_aktivitas)
            if ($user->departemen_id && $log->departemen_id) {
                $hasAccess = ($user->departemen_id == $log->departemen_id);
            }
        } elseif (in_array($user->role, ['sdm', 'superadmin', 'admin'])) {
            // SDM/Superadmin/Admin: bisa validasi semua log
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

        // Cek akses berdasarkan hierarki
        $karyawan = DB::table('users')->where('id', $log->user_id)->first();
        $hasAccess = false;

        if ($user->role === 'spv') {
            // SPV: bisa tolak log karyawan di unitnya
            if ($user->unit_id && $karyawan->unit_id) {
                $hasAccess = ($user->unit_id == $karyawan->unit_id);
            }
        } elseif ($user->role === 'manager') {
            // Manager: bisa tolak log semua unit dalam departemennya
            if ($user->departemen_id && $karyawan->unit_id) {
                $unit = DB::table('tb_unit')
                    ->where('id', $karyawan->unit_id)
                    ->where('departemen_id', $user->departemen_id)
                    ->first();
                $hasAccess = ($unit !== null);
            }
        } elseif (in_array($user->role, ['sdm', 'superadmin'])) {
            // SDM/Superadmin: bisa tolak semua log
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
        if (!in_array($user->role, ['spv', 'manager', 'sdm', 'superadmin'])) {
            abort(403);
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

            // Cek akses berdasarkan hierarki
            $hasAccess = false;

            if ($user->role === 'spv') {
                // SPV: bisa validasi log di unitnya (berdasarkan unit_id di log_aktivitas)
                if ($user->unit_id && $firstLog->unit_id) {
                    $hasAccess = ($user->unit_id == $firstLog->unit_id);
                }
            } elseif ($user->role === 'manager') {
                // Manager: bisa validasi log di departemennya (berdasarkan departemen_id di log_aktivitas)
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
        $request->validate([
            'selected_items' => 'required|array|min:1',
            'selected_items.*' => 'required|string',
            'catatan_validasi' => 'required|string|min:5',
        ]);

        $user = Auth::user();
        if (!in_array($user->role, ['spv', 'manager', 'sdm', 'superadmin'])) {
            abort(403);
        }

        $updated = 0;
        foreach ($request->selected_items as $item) {
            list($tanggal, $user_id) = explode('_', $item);

            // Cek akses berdasarkan hierarki
            $karyawan = DB::table('users')->where('id', $user_id)->first();
            $hasAccess = false;

            if ($user->role === 'spv') {
                if ($user->unit_id && $karyawan->unit_id) {
                    $hasAccess = ($user->unit_id == $karyawan->unit_id);
                }
            } elseif ($user->role === 'manager') {
                if ($user->departemen_id && $karyawan->unit_id) {
                    $unit = DB::table('tb_unit')
                        ->where('id', $karyawan->unit_id)
                        ->where('departemen_id', $user->departemen_id)
                        ->first();
                    $hasAccess = ($unit !== null);
                }
            } elseif (in_array($user->role, ['sdm', 'superadmin'])) {
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
        if (!in_array($user->role, ['spv', 'manager', 'sdm', 'superadmin'])) {
            abort(403);
        }

        // Filter log berdasarkan akses hierarki
        $logs = DB::table('log_aktivitas')
            ->whereIn('id', $request->log_ids)
            ->where('status', 'menunggu')
            ->get();

        $allowedLogIds = [];
        foreach ($logs as $log) {
            // Cek akses berdasarkan unit_id dan departemen_id dari log_aktivitas
            $hasAccess = false;

            if ($user->role === 'spv') {
                // SPV: bisa validasi log di unitnya (berdasarkan unit_id di log_aktivitas)
                if ($user->unit_id && $log->unit_id) {
                    $hasAccess = ($user->unit_id == $log->unit_id);
                }
            } elseif ($user->role === 'manager') {
                // Manager: bisa validasi log di departemennya (berdasarkan departemen_id di log_aktivitas)
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
        if (!in_array($user->role, ['spv', 'manager', 'sdm', 'superadmin'])) {
            abort(403);
        }

        // Filter log berdasarkan akses hierarki
        $logs = DB::table('log_aktivitas')
            ->whereIn('id', $request->log_ids)
            ->where('status', 'menunggu')
            ->get();

        $allowedLogIds = [];
        foreach ($logs as $log) {
            // Cek akses berdasarkan unit_id dan departemen_id dari log_aktivitas
            $hasAccess = false;

            if ($user->role === 'spv') {
                // SPV: bisa validasi log di unitnya (berdasarkan unit_id di log_aktivitas)
                if ($user->unit_id && $log->unit_id) {
                    $hasAccess = ($user->unit_id == $log->unit_id);
                }
            } elseif ($user->role === 'manager') {
                // Manager: bisa validasi log di departemennya (berdasarkan departemen_id di log_aktivitas)
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

        if ($user->role === 'karyawan' && $log->user_id != $user->id) {
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
            abort(404);
        }

        DB::table('log_aktivitas')->where('id', $id)->delete();

        return redirect()->route('log-aktivitas.index')
            ->with('success', 'Log aktivitas berhasil dihapus.');
    }
}
