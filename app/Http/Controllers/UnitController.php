<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UnitController extends Controller
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
     * Menampilkan daftar unit
     */
    public function index(Request $request)
    {
        $unit = DB::table('tb_unit')
            ->join('tb_departemen', 'tb_unit.departemen_id', '=', 'tb_departemen.id')
            ->whereNull('tb_departemen.deleted_at')
            ->whereNull('tb_unit.deleted_at')
            ->select(
                'tb_unit.*',
                'tb_departemen.nama as nama_departemen'
            )
            ->orderBy('tb_departemen.nama', 'asc')
            ->orderBy('tb_unit.nama', 'asc')
            ->get();

        // Ambil daftar departemen untuk filter
        $departemen = DB::table('tb_departemen')
            ->whereNull('deleted_at')
            ->orderBy('nama', 'asc')
            ->get();

        return view('data-master.unit.index', compact('unit', 'departemen'));
    }

    /**
     * Menampilkan form tambah unit
     */
    public function create()
    {
        $departemen = DB::table('tb_departemen')
            ->whereNull('deleted_at')
            ->orderBy('nama', 'asc')
            ->get();

        return view('data-master.unit.create', compact('departemen'));
    }

    /**
     * Menyimpan unit baru
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'departemen_id' => 'required|exists:tb_departemen,id',
            'nama' => [
                'required',
                'string',
                'max:150',
                Rule::unique('tb_unit', 'nama')->where(function ($query) use ($request) {
                    return $query->where('departemen_id', $request->departemen_id);
                }),
            ],
        ], [
            'departemen_id.required' => 'Departemen harus dipilih.',
            'departemen_id.exists' => 'Departemen yang dipilih tidak valid.',
            'nama.required' => 'Nama unit harus diisi.',
            'nama.max' => 'Nama unit maksimal 150 karakter.',
            'nama.unique' => 'Nama unit sudah ada di departemen yang dipilih.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::table('tb_unit')->insert([
            'departemen_id' => $request->departemen_id,
            'nama' => $request->nama,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('unit.index')
            ->with('success', 'Unit berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit unit
     */
    public function edit($id)
    {
        $unit = DB::table('tb_unit')
            ->join('tb_departemen', 'tb_unit.departemen_id', '=', 'tb_departemen.id')
            ->where('tb_unit.id', $id)
            ->whereNull('tb_departemen.deleted_at')
            ->whereNull('tb_unit.deleted_at')
            ->select(
                'tb_unit.*',
                'tb_departemen.nama as nama_departemen'
            )
            ->first();

        if (!$unit) {
            abort(404);
        }

        $departemen = DB::table('tb_departemen')
            ->whereNull('deleted_at')
            ->orderBy('nama', 'asc')
            ->get();

        return view('data-master.unit.edit', compact('unit', 'departemen'));
    }

    /**
     * Update unit
     */
    public function update(Request $request, $id)
    {
        $unit = DB::table('tb_unit')
            ->join('tb_departemen', 'tb_unit.departemen_id', '=', 'tb_departemen.id')
            ->where('tb_unit.id', $id)
            ->whereNull('tb_departemen.deleted_at')
            ->whereNull('tb_unit.deleted_at')
            ->first();

        if (!$unit) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'departemen_id' => 'required|exists:tb_departemen,id',
            'nama' => [
                'required',
                'string',
                'max:150',
                Rule::unique('tb_unit', 'nama')->where(function ($query) use ($request) {
                    return $query->where('departemen_id', $request->departemen_id);
                })->ignore($id),
            ],
        ], [
            'departemen_id.required' => 'Departemen harus dipilih.',
            'departemen_id.exists' => 'Departemen yang dipilih tidak valid.',
            'nama.required' => 'Nama unit harus diisi.',
            'nama.max' => 'Nama unit maksimal 150 karakter.',
            'nama.unique' => 'Nama unit sudah ada di departemen yang dipilih.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::table('tb_unit')
            ->where('id', $id)
            ->update([
                'departemen_id' => $request->departemen_id,
                'nama' => $request->nama,
                'updated_at' => now(),
            ]);

        return redirect()->route('unit.index')
            ->with('success', 'Unit berhasil diperbarui.');
    }

    /**
     * Hapus unit
     */
    public function destroy($id)
    {
        $unit = DB::table('tb_unit')
            ->join('tb_departemen', 'tb_unit.departemen_id', '=', 'tb_departemen.id')
            ->where('tb_unit.id', $id)
            ->whereNull('tb_departemen.deleted_at')
            ->whereNull('tb_unit.deleted_at')
            ->first();

        if (!$unit) {
            abort(404);
        }

        // Cek apakah ada user yang menggunakan unit ini
        $userCount = DB::table('users')
            ->where('unit_id', $id)
            ->count();

        if ($userCount > 0) {
            return redirect()->route('unit.index')
                ->with('error', 'Unit tidak dapat dihapus karena masih memiliki ' . $userCount . ' karyawan.');
        }

        DB::table('tb_unit')
            ->where('id', $id)
            ->update([
                'deleted_at' => now(),
                'updated_at' => now(),
            ]);

        return redirect()->route('unit.index')
            ->with('success', 'Unit berhasil dihapus.');
    }
}
