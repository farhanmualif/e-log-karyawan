<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class DepartemenController extends Controller
{
    /**
     * Constructor - Cek akses untuk semua method
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            if (!in_array($user->role, ['superadmin', 'admin', 'sdm'])) {
                abort(403, 'Akses ditolak. Hanya admin dan sdm yang dapat mengakses menu Data Master.');
            }
            return $next($request);
        });
    }

    /**
     * Menampilkan daftar departemen
     */
    public function index(Request $request)
    {
        $departemen = DB::table('tb_departemen')
            ->whereNull('deleted_at')
            ->orderBy('nama', 'asc')
            ->get();

        $departemen->map(function ($item) {
            $item->nama = ucwords(strtolower($item->nama));
            return $item;
        });

        return view('data-master.departemen.index', compact('departemen'));
    }

    /**
     * Menampilkan form tambah departemen
     */
    public function create()
    {
        return view('data-master.departemen.create');
    }

    /**
     * Menyimpan departemen baru
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:150|unique:tb_departemen,nama,NULL,id,deleted_at,NULL',
        ], [
            'nama.required' => 'Nama departemen harus diisi.',
            'nama.max' => 'Nama departemen maksimal 150 karakter.',
            'nama.unique' => 'Nama departemen sudah ada.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::table('tb_departemen')->insert([
            'nama' => $request->nama,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('departemen.index')
            ->with('success', 'Departemen berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit departemen
     */
    public function edit($id)
    {
        $departemen = DB::table('tb_departemen')
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->first();

        if (!$departemen) {
            abort(404);
        }

        return view('data-master.departemen.edit', compact('departemen'));
    }

    /**
     * Update departemen
     */
    public function update(Request $request, $id)
    {
        $departemen = DB::table('tb_departemen')
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->first();

        if (!$departemen) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:150|unique:tb_departemen,nama,' . $id . ',id,deleted_at,NULL',
        ], [
            'nama.required' => 'Nama departemen harus diisi.',
            'nama.max' => 'Nama departemen maksimal 150 karakter.',
            'nama.unique' => 'Nama departemen sudah ada.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::table('tb_departemen')
            ->where('id', $id)
            ->update([
                'nama' => $request->nama,
                'updated_at' => now(),
            ]);

        return redirect()->route('departemen.index')
            ->with('success', 'Departemen berhasil diperbarui.');
    }

    /**
     * Hapus departemen (soft delete)
     */
    public function destroy($id)
    {
        $departemen = DB::table('tb_departemen')
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->first();

        if (!$departemen) {
            abort(404);
        }

        $unitCount = DB::table('tb_unit')
            ->where('departemen_id', $id)
            ->count();

        if ($unitCount > 0) {
            return redirect()->route('departemen.index')
                ->with('error', 'Departemen tidak dapat dihapus karena masih memiliki ' . $unitCount . ' unit.');
        }

        DB::table('tb_departemen')
            ->where('id', $id)
            ->update([
                'deleted_at' => now(),
                'updated_at' => now(),
            ]);

        return redirect()->route('departemen.index')
            ->with('success', 'Departemen berhasil dihapus.');
    }
}
