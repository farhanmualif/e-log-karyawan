@extends('layouts.main')

@section('page-content')
<div class="p-6 pb-8">
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex items-center justify-between gap-4 mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tambah Log Aktivitas</h1>
                <p class="text-sm text-gray-600 mt-1">Input aktivitas harian Anda</p>
            </div>
            <a href="{{ route('log-aktivitas.index') }}" class="flex items-center text-white px-3 py-2  hover:no-underline border rounded-lg bg-gray-600 hover:bg-gray-900">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Kembali
            </a>
        </div>
    </div>

    <!-- Alert jika departemen/unit belum ditentukan -->
    @if(!Auth::user()->departemen_id || !Auth::user()->unit_id)
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-lg">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i data-lucide="alert-triangle" class="h-5 w-5 text-yellow-400"></i>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-medium text-yellow-800 mb-2">
                    Departemen dan Unit Belum Di atur
                </h3>
                <div class="text-sm text-yellow-700">
                    <p class="mb-2">Anda harus menentukan <strong>Departemen</strong> dan <strong>Unit</strong> terlebih dahulu sebelum dapat menambahkan log aktivitas.</p>
                    <a href="{{ route('profile.show') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors font-medium">
                        <i data-lucide="user" class="w-4 h-4"></i>
                        Update Profile Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Error Message dari Session -->
    @if(session('profile_required'))
    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-lg">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i data-lucide="x-circle" class="h-5 w-5 text-red-400"></i>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-medium text-red-800 mb-2">
                    Tidak Dapat Menambahkan Log Aktivitas
                </h3>
                <div class="text-sm text-red-700">
                    @if(!Auth::user()->departemen_id && !Auth::user()->unit_id)
                    <p class="mb-2">Departemen dan Unit Anda belum ditentukan.</p>
                    @elseif(!Auth::user()->departemen_id)
                    <p class="mb-2">Departemen Anda belum ditentukan.</p>
                    @else
                    <p class="mb-2">Unit Anda belum ditentukan.</p>
                    @endif
                    <p class="mb-3">Silakan tentukan <strong>Departemen</strong> dan <strong>Unit</strong> di menu Profile terlebih dahulu.</p>
                    <a href="{{ route('profile.show') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                        <i data-lucide="user" class="w-4 h-4"></i>
                        Buka Menu Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Error dari validation -->
    @error('profile')
    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-lg">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i data-lucide="x-circle" class="h-5 w-5 text-red-400"></i>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-medium text-red-800 mb-2">
                    Tidak Dapat Menambahkan Log Aktivitas
                </h3>
                <div class="text-sm text-red-700">
                    <p class="mb-3">{{ $message }}</p>
                    <a href="{{ route('profile.show') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                        <i data-lucide="user" class="w-4 h-4"></i>
                        Buka Menu Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
    @enderror

    <!-- Form Section -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <form method="POST" action="{{ route('log-aktivitas.store') }}" @if(!Auth::user()->departemen_id || !Auth::user()->unit_id) onsubmit="event.preventDefault(); alert('Silakan update Departemen dan Unit di menu Profile terlebih dahulu.'); window.location.href='{{ route('profile.show') }}'; return false;" @endif>
            @csrf

            <!-- Tanggal Field -->
            <div class="mb-6">
                <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                    <div class="flex items-center">
                        <i data-lucide="calendar" class="w-5 h-5 text-teal-500 mr-2"></i>
                        Tanggal
                    </div>
                </label>
                <input type="date"
                    id="tanggal"
                    name="tanggal"
                    value="{{ old('tanggal', $today) }}"
                    max="{{ $today }}"
                    required
                    class="w-full px-4 py-3 border @error('tanggal') border-red-300 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition duration-150 ease-in-out @error('tanggal') bg-red-50 @else bg-gray-50 @enderror">
                @error('tanggal')
                <p class="mt-2 text-sm text-red-600 flex items-center">
                    <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                    {{ $message }}
                </p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Tidak dapat memilih tanggal yang akan datang</p>
            </div>


            <!-- Aktivitas Harian Section -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-4">
                    <label class="block text-sm font-medium text-gray-700">
                        <div class="flex items-center">
                            <i data-lucide="file-text" class="w-5 h-5 text-teal-500 mr-2"></i>
                            Aktivitas Harian
                        </div>
                    </label>
                    <button type="button" id="tambahAktivitas" class="flex items-center gap-2 px-4 py-2 bg-teal-600 text-white text-sm rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors font-medium">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        Tambah Aktivitas
                    </button>
                </div>

                <!-- Container untuk multiple aktivitas -->
                <div id="aktivitasContainer" class="space-y-4">
                    <!-- Aktivitas Item Template (akan di-clone) -->
                    <div class="aktivitas-item border border-gray-200 rounded-lg p-4 bg-gray-50">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-2">
                                <span class="aktivitas-number text-sm font-medium text-gray-600">#1</span>
                                <span class="text-sm text-gray-500">Aktivitas</span>
                            </div>
                            <button type="button" class="hapus-aktivitas hidden text-red-600 hover:text-red-800 transition-colors" title="Hapus aktivitas">
                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <!-- Waktu Awal Field -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <div class="flex items-center">
                                        <i data-lucide="clock" class="w-4 h-4 text-teal-500 mr-2"></i>
                                        Waktu Awal (WIB)
                                    </div>
                                </label>
                                <input
                                    type="text"
                                    name="aktivitas[0][waktu_awal]"
                                    class="waktu-awal-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition duration-150 ease-in-out bg-gray-50 time-picker cursor-pointer"
                                    placeholder="00:00"
                                    readonly />
                            </div>

                            <!-- Waktu Akhir Field -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <div class="flex items-center">
                                        <i data-lucide="clock" class="w-4 h-4 text-teal-500 mr-2"></i>
                                        Waktu Akhir (WIB)
                                    </div>
                                </label>
                                <input
                                    type="text"
                                    name="aktivitas[0][waktu_akhir]"
                                    class="waktu-akhir-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition duration-150 ease-in-out bg-gray-50 time-picker cursor-pointer"
                                    placeholder="00:00"
                                    readonly />
                            </div>
                        </div>

                        <!-- Aktivitas Textarea -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi Aktivitas
                            </label>
                            <textarea
                                name="aktivitas[0][aktivitas]"
                                rows="4"
                                required
                                minlength="10"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition duration-150 ease-in-out bg-white"
                                placeholder="Jelaskan aktivitas, tugas, atau kegiatan kerja yang Anda lakukan (minimal 10 karakter)..."></textarea>
                            <p class="mt-1 text-xs text-gray-500">Minimal 10 karakter</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="bg-teal-50 border border-teal-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <i data-lucide="info" class="w-5 h-5 text-teal-600 mr-2 mt-0.5"></i>
                    <div class="text-sm text-teal-800">
                        <p class="font-medium mb-1">Tips Menulis Log Aktivitas:</p>
                        <ul class="list-disc list-inside space-y-1 text-teal-700">
                            <li>Jelaskan aktivitas dengan detail dan jelas</li>
                            <li>Sertakan progres atau hasil yang dicapai</li>
                            <li>Gunakan bahasa yang profesional</li>
                            <li>Pastikan log sesuai dengan tanggal yang dipilih</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Button Actions -->
            <div class="flex items-center gap-3">
                <button type="submit" class="flex items-center gap-2 px-3 py-1.5 bg-teal-600 text-sm text-white rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors font-medium">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Simpan Log
                </button>
                <a href="{{ route('log-aktivitas.index') }}" class="px-3 py-1.5 text-sm bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('js/scripts/log-aktifitas/create.js') }}"></script>

@endsection
