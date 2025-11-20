<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 10;

        $listKaryawan = DB::connection('mysql_khanza')
            ->table('pegawai')
            ->join('departemen', 'pegawai.departemen', '=', 'departemen.dep_id')
            ->select(['nik as id', 'pegawai.nama', 'jk', 'bidang', 'departemen.nama as departemen', 'stts_kerja', 'mulai_kerja'])
            ->paginate($perPage);

        // Ambil semua NIK yang sudah terdaftar di users
        $registeredNiks = DB::table('users')
            ->whereNotNull('username')
            ->pluck('username')
            ->toArray();

        // Ambil semua user yang terdaftar beserta departemen dan unit mereka
        $registeredUsers = DB::table('users')
            ->whereNotNull('username')
            ->select('username', 'id', 'departemen_id', 'unit_id', 'password_changed')
            ->get()
            ->keyBy('username');

        // Process each item in the paginated results
        $items = $listKaryawan->items();
        foreach ($items as $item) {
            $data = (array) $item;
            $name = trim($data['nama']);
            $parts = explode(' ', $name);
            $titles = ['dr', 'dr.', 'prof', 'prof.', 'drs', 'drs.', 'h', 'hj'];
            $parts = array_filter($parts, function ($p) use ($titles) {
                return !in_array(strtolower($p), $titles);
            });

            $parts = array_values($parts);

            if (count($parts) >= 2) {
                $initial = strtoupper($parts[0][0] . $parts[1][0]);
            } else {
                $initial = strtoupper($parts[0][0]);
            }

            $processedItem = [];
            foreach ($data as $key => $value) {
                $processedItem[$key] = is_string($value)
                    ? ucwords(strtolower($value))
                    : $value;
            }

            $processedItem['inisial_nama'] = $initial;

            // Cek apakah sudah terdaftar dan ambil data user
            $isRegistered = in_array($item->id, $registeredNiks);
            $processedItem['is_registered'] = $isRegistered;

            if ($isRegistered && isset($registeredUsers[$item->id])) {
                $user = $registeredUsers[$item->id];
                $processedItem['user_id'] = $user->id;
                $processedItem['departemen_id'] = $user->departemen_id;
                $processedItem['unit_id'] = $user->unit_id;
                $processedItem['password_changed'] = $user->password_changed;

                // Ambil nama departemen dari database lokal
                $departemenNama = 'Belum Ditentukan';
                if ($user->departemen_id) {
                    $dept = DB::table('tb_departemen')->where('id', $user->departemen_id)->first();
                    if ($dept) {
                        $departemenNama = $dept->nama;
                    }
                }
                $processedItem['departemen_nama'] = $departemenNama;

                // Ambil nama unit dari database lokal
                $unitNama = 'Belum Ditentukan';
                if ($user->unit_id) {
                    $unit = DB::table('tb_unit')->where('id', $user->unit_id)->first();
                    if ($unit) {
                        $unitNama = $unit->nama;
                    }
                }
                $processedItem['unit_nama'] = $unitNama;
            } else {
                $processedItem['user_id'] = null;
                $processedItem['departemen_id'] = null;
                $processedItem['unit_id'] = null;
                $processedItem['password_changed'] = false;
                $processedItem['departemen_nama'] = 'Belum Ditentukan';
                $processedItem['unit_nama'] = 'Belum Ditentukan';
            }

            // Update item dengan data yang sudah diproses
            foreach ($processedItem as $key => $value) {
                $item->$key = $value;
            }
        }

        // Ambil departemen dari database lokal (tb_departemen)
        $listDepartemen = DB::table('tb_departemen')
            ->whereNull('deleted_at')
            ->orderBy('nama')
            ->get();

        // Ambil unit dari database lokal (tb_unit)
        $listUnit = DB::table('tb_unit')
            ->orderBy('nama')
            ->get();

        return view('karyawan.index', compact('listKaryawan', 'listDepartemen', 'listUnit'));
    }

    /**
     * Aktifkan karyawan (register ke sistem)
     */
    public function activate(Request $request, $nik)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['superadmin', 'admin', 'sdm'])) {
            abort(403);
        }

        $existingUser = User::where('username', $nik)->first();
        if ($existingUser) {
            return redirect()->route('karyawan')
                ->with('error', 'Karyawan dengan NIK ' . $nik . ' sudah terdaftar di sistem.');
        }

        $pegawai = DB::connection('mysql_khanza')
            ->table('pegawai')
            ->where('nik', $nik)
            ->first();

        if (!$pegawai) {
            return redirect()->route('karyawan')
                ->with('error', 'Data pegawai dengan NIK ' . $nik . ' tidak ditemukan.');
        }

        User::create([
            'name' => $pegawai->nama ?? 'User',
            'username' => $nik,
            'email' => null,
            'password' => Hash::make('12345'),
            'password_changed' => false,
            'role' => 'karyawan',
            'unit_id' => null,
            'departemen_id' => null,
        ]);

        return redirect()->route('karyawan')
            ->with('success', 'Karyawan dengan NIK ' . $nik . ' berhasil diaktifkan. Password default: 12345');
    }

    /**
     * Update user
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $targetUser = User::findOrFail($id);

        if ($user->id != $targetUser->id && !in_array($user->role, ['superadmin', 'admin', 'sdm'])) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            abort(403);
        }

        if ($user->id == $targetUser->id) {
            try {
                $request->validate([
                    'password' => 'required|min:6|confirmed',
                ]);

                $targetUser->password = Hash::make($request->password);
                $targetUser->password_changed = true;
                $targetUser->save();

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Password berhasil diubah.',
                        'password_changed' => true
                    ]);
                }

                return redirect()->route('karyawan')
                    ->with('success', 'Password berhasil diubah.');
            } catch (\Illuminate\Validation\ValidationException $e) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validasi gagal',
                        'errors' => $e->errors()
                    ], 422);
                }
                throw $e;
            }
        }

        $rules = [
            'name' => 'required|string|max:150',
            'departemen_id' => 'nullable|exists:tb_departemen,id',
            'unit_id' => 'nullable|exists:tb_unit,id',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|min:6|confirmed';
        }

        try {
            $request->validate($rules);

            if ($request->filled('unit_id') && $request->filled('departemen_id')) {
                $unit = DB::table('tb_unit')
                    ->where('id', $request->unit_id)
                    ->where('departemen_id', $request->departemen_id)
                    ->first();

                if (!$unit) {
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Unit yang dipilih tidak sesuai dengan departemen.'
                        ], 422);
                    }
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Unit yang dipilih tidak sesuai dengan departemen.');
                }
            }

            $targetUser->name = $request->name;

            if ($request->filled('departemen_id')) {
                $targetUser->departemen_id = $request->departemen_id;
            }

            if ($request->filled('unit_id')) {
                $targetUser->unit_id = $request->unit_id;
            }

            $passwordChanged = false;
            if ($request->filled('password')) {
                $targetUser->password = Hash::make($request->password);
                $targetUser->password_changed = true;
                $passwordChanged = true;
            }

            $targetUser->save();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $passwordChanged ? 'Password dan data berhasil diperbarui.' : 'Data user berhasil diperbarui.',
                    'password_changed' => $passwordChanged
                ]);
            }

            return redirect()->route('karyawan')
                ->with('success', 'Data user berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }
    }

    /**
     * Update password user
     */
    public function updatePassword(Request $request, $id)
    {
        $user = Auth::user();
        $targetUser = User::findOrFail($id);

        // Cek akses: user sendiri atau admin/superadmin/sdm
        if ($user->id != $targetUser->id && !in_array($user->role, ['superadmin', 'admin', 'sdm'])) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
            abort(403);
        }

        try {
            // Validasi password sesuai standar Laravel Auth
            $request->validate([
                'password' => 'required|min:6|confirmed',
            ]);

            // Update password
            $targetUser->password = Hash::make($request->password);
            $targetUser->password_changed = true;
            $targetUser->save();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Password berhasil diubah.',
                    'password_changed' => true
                ]);
            }

            return redirect()->route('karyawan')
                ->with('success', 'Password berhasil diubah.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            throw $e;
        }
    }
}
