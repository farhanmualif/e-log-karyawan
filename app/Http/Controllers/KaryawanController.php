<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;
use Illuminate\Support\Facades\Log;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 10;
        $search = $request->get('search', '');
        $filterDepartemen = $request->get('filter_departemen', '');

        $query = DB::connection('mysql_khanza')
            ->table('pegawai')
            ->join('departemen', 'pegawai.departemen', '=', 'departemen.dep_id')
            ->select(['nik as id', 'pegawai.nama', 'jk', 'bidang', 'departemen.nama as departemen', 'stts_kerja', 'mulai_kerja']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('pegawai.nama', 'like', '%' . $search . '%')
                    ->orWhere('pegawai.nik', 'like', '%' . $search . '%');
            });
        }

        if ($filterDepartemen && $filterDepartemen !== 'Semua Departemen') {
            $query->where('departemen.nama', $filterDepartemen);
        }

        $listKaryawan = $query->paginate($perPage)->appends($request->query());

        $registeredNiks = DB::table('users')
            ->whereNotNull('username')
            ->pluck('username')
            ->toArray();

        $registeredUsers = DB::table('users')
            ->whereNotNull('username')
            ->select('username', 'id', 'departemen_id', 'unit_id', 'password_changed', 'role')
            ->get()
            ->keyBy('username');

        $listDepartemen = DB::table('tb_departemen')
            ->whereNull('deleted_at')
            ->orderBy('nama')
            ->get();

        $listDepartemenKeyId = $listDepartemen->keyBy('id');
        $listUnit = DB::table('tb_unit')->orderBy('nama')->get()->keyBy('id');
        $listUnitKeyId = $listUnit->keyBy('id');

        $listSupervisor = DB::table('users')->select('id', 'name', 'username', 'role', 'unit_id', 'departemen_id')->where('role', 'spv')->get()->keyBy('unit_id');
        $listManager = DB::table('users')->select('id', 'name', 'username', 'role', 'unit_id', 'departemen_id')->where('role', 'manager')->get()->keyBy('departemen_id');

        $items = $listKaryawan->items();
        foreach ($items as $item) {
            $data = (array) $item;
            $name = trim($data['nama']);
            $parts = explode(' ', $name);
            $titles = ['dr', 'dr.', 'prof', 'prof.', 'drs', 'drs.', 'h', 'hj'];
            $parts = array_filter($parts, function ($p) use ($titles) {
                return !in_array(strtolower($p), $titles);
            });

            $parts = array_filter($parts, function ($p) {
                return trim($p) !== '';
            });

            $parts = array_values($parts);

            if (count($parts) === 0) {
                $initial = 'NA';
            } elseif (count($parts) === 1) {
                $initial = strtoupper(mb_substr($parts[0], 0, 1));
            } else {
                $initial = strtoupper(
                    mb_substr($parts[0], 0, 1) .
                        mb_substr($parts[1], 0, 1)
                );
            }

            $processedItem = [];
            foreach ($data as $key => $value) {

                if (in_array($key, ['id', 'nik'])) {
                    $processedItem[$key] = $value;
                    continue;
                }

                $processedItem[$key] = is_string($value)
                    ? ucwords(strtolower($value))
                    : $value;
            }

            $processedItem['inisial_nama'] = $initial;

            $isRegistered = in_array($item->id, $registeredNiks);
            $processedItem['is_registered'] = $isRegistered;
            $processedItem['username'] = $item->id;

            if ($isRegistered && isset($registeredUsers[$item->id])) {
                $user = $registeredUsers[$item->id];
                $processedItem['user_id'] = $user->id;
                $processedItem['departemen_id'] = $listDepartemenKeyId[$user->departemen_id]->id ?? "Belum Ditentukan";
                $processedItem['unit_id'] = $listUnitKeyId[$user->unit_id]->id ?? "Belum Ditentukan";
                $processedItem['password_changed'] = $user->password_changed;
                $processedItem['user_role'] = ucwords(strtolower($user->role));
                $processedItem['supervisor'] = $listSupervisor[$user->unit_id]->name ?? '-';
                $processedItem['manager'] = $listManager[$user->departemen_id]->name ?? '-';

                $departemenNama = 'Belum Ditentukan';
                if ($user->departemen_id) {
                    $departemenNama = $listDepartemenKeyId[$user->departemen_id]->nama ?? 'Belum Ditentukan';
                }
                $processedItem['departemen_nama'] = $departemenNama;

                $unitNama = 'Belum Ditentukan';
                if ($user->unit_id) {
                    $unitNama = $listUnitKeyId[$user->unit_id]->nama ?? 'Belum Ditentukan';
                }
                $processedItem['unit_nama'] = $unitNama;
            } else {
                $processedItem['user_id'] = null;
                $processedItem['departemen_id'] = null;
                $processedItem['unit_id'] = null;
                $processedItem['password_changed'] = false;
                $processedItem['user_role'] = '-';
                $processedItem['departemen_nama'] = 'Belum Ditentukan';
                $processedItem['unit_nama'] = 'Belum Ditentukan';
                $processedItem['supervisor'] = null;
                $processedItem['manager'] = null;
            }

            foreach ($processedItem as $key => $value) {
                $item->$key = $value;
            }
        }

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

        // Hanya superadmin, admin, dan sdm yang bisa mengubah role
        if (in_array($user->role, ['superadmin', 'admin', 'sdm'])) {
            $rules['role'] = 'nullable|in:admin,sdm,karyawan,spv,manager';
        }

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

            // Update role jika user memiliki akses dan role diisi
            if (in_array($user->role, ['superadmin', 'admin', 'sdm']) && $request->filled('role')) {
                $targetUser->role = $request->role;
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
            $request->validate(
                [
                    'password' => 'required|min:5|confirmed',
                    'password_confirmation' => 'required'
                ]
            );

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

            return redirect()->back()->with('success', 'Password berhasil diubah.');
        } catch (\Illuminate\Validation\ValidationException $e) {

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()
                ->with('error', 'Password gagal diubah.')
                ->with('validation_errors', $e->errors());
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Password gagal diubah. ' . $e->getMessage());
        }
    }


    /**
     * Update role user
     */
    public function updateRole(Request $request, $id)
    {
        $user = Auth::user();
        $targetUser = User::findOrFail($id);

        // Hanya superadmin, admin, dan sdm yang bisa mengubah role
        if (!in_array($user->role, ['superadmin', 'admin', 'sdm'])) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
            abort(403);
        }

        try {
            // Validasi role
            $request->validate([
                'role' => 'required|in:admin,sdm,karyawan,spv,manager',
            ]);

            // Update role
            $targetUser->role = $request->role;
            $targetUser->save();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Role berhasil diubah.',
                ]);
            }

            return redirect()->route('karyawan')
                ->with('success', 'Role berhasil diubah.');
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


    public function updateKaryawan(Request $request, $username)
    {
        $user = User::where('username', $username)->first();

        if (!$user) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'User tidak ditemukan, Pastikan user sudah terdaftar di sistem ini');
        }
        // Validasi unit harus sesuai dengan departemen
        if ($request->filled('unit_id') && $request->filled('departemen_id')) {
            $unit = DB::table('tb_unit')
                ->where('id', $request->unit_id)
                ->where('departemen_id', $request->departemen_id)
                ->whereNull('deleted_at')
                ->first();

            if (!$unit) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Unit yang dipilih tidak sesuai dengan departemen.');
            }
        }



        // Update user
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('departemen_id')) {
            $user->departemen_id = $request->departemen_id;
        } else {
            $user->departemen_id = null;
        }

        if ($request->filled('unit_id')) {
            $user->unit_id = $request->unit_id;
        } else {
            $user->unit_id = null;
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile berhasil diperbarui.');
    }
}
