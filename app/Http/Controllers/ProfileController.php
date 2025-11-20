<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\User;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profile user
     */
    public function show()
    {
        $user = Auth::user();

        // Ambil nama departemen dan unit jika ada
        $departemenNama = 'Belum Ditentukan';
        $unitNama = 'Belum Ditentukan';

        if ($user->departemen_id) {
            $departemen = DB::table('tb_departemen')
                ->where('id', $user->departemen_id)
                ->whereNull('deleted_at')
                ->first();
            if ($departemen) {
                $departemenNama = $departemen->nama;
            }
        }

        if ($user->unit_id) {
            $unit = DB::table('tb_unit')
                ->where('id', $user->unit_id)
                ->whereNull('deleted_at')
                ->first();
            if ($unit) {
                $unitNama = $unit->nama;
            }
        }

        // Ambil daftar departemen untuk dropdown
        $departemenList = DB::table('tb_departemen')
            ->whereNull('deleted_at')
            ->orderBy('nama', 'asc')
            ->get();

        // Ambil daftar unit untuk dropdown
        $unitList = DB::table('tb_unit')
            ->join('tb_departemen', 'tb_unit.departemen_id', '=', 'tb_departemen.id')
            ->whereNull('tb_departemen.deleted_at')
            ->whereNull('tb_unit.deleted_at')
            ->select('tb_unit.*', 'tb_departemen.nama as nama_departemen')
            ->orderBy('tb_departemen.nama', 'asc')
            ->orderBy('tb_unit.nama', 'asc')
            ->get();

        return view('profile.show', compact('user', 'departemenNama', 'unitNama', 'departemenList', 'unitList'));
    }

    /**
     * Update profile user
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150',
            'email' => 'nullable|email|max:150',
            'departemen_id' => 'nullable|exists:tb_departemen,id',
            'unit_id' => 'nullable|exists:tb_unit,id',
        ], [
            'name.required' => 'Nama harus diisi.',
            'name.max' => 'Nama maksimal 150 karakter.',
            'email.email' => 'Email harus valid.',
            'email.max' => 'Email maksimal 150 karakter.',
            'departemen_id.exists' => 'Departemen yang dipilih tidak valid.',
            'unit_id.exists' => 'Unit yang dipilih tidak valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
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

        return redirect()->route('profile.show')
            ->with('success', 'Profile berhasil diperbarui.');
    }
}
