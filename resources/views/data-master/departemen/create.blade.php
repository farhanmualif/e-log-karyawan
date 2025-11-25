@extends('layouts.main')

@section('page-content')
<div class="p-6 pb-8">
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex items-center justify-between gap-4 mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tambah Departemen</h1>
                <p class="text-sm text-gray-600 mt-1">Tambah data departemen baru</p>
            </div>
            <a href="{{ route('departemen.index') }}" class="flex items-center text-white px-3 py-2 hover:no-underline border rounded-lg bg-gray-600 hover:bg-gray-900">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Kembali
            </a>
        </div>
    </div>

    <!-- Form Section -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <form method="POST" action="{{ route('departemen.store') }}">
            @csrf

            <!-- Nama Field -->
            <div class="mb-6">
                <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                    <div class="flex items-center">
                        <i data-lucide="building" class="w-5 h-5 text-teal-500 mr-2"></i>
                        Nama Departemen <span class="text-red-500">*</span>
                    </div>
                </label>
                <input type="text"
                    id="nama"
                    name="nama"
                    value="{{ old('nama') }}"
                    required
                    maxlength="150"
                    class="w-full px-4 py-3 border @error('nama') border-red-300 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition duration-150 ease-in-out @error('nama') bg-red-50 @else bg-gray-50 @enderror"
                    placeholder="Masukkan nama departemen">
                @error('nama')
                <p class="mt-2 text-sm text-red-600 flex items-center">
                    <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                    {{ $message }}
                </p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Maksimal 150 karakter</p>
            </div>

            <!-- Button Actions -->
            <div class="flex items-center gap-3">
                <button type="submit" class="flex items-center gap-2 px-3 py-1.5 bg-teal-600 text-sm text-white rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors font-medium">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Simpan
                </button>
                <a href="{{ route('departemen.index') }}" class="px-3 py-1.5 text-sm bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
