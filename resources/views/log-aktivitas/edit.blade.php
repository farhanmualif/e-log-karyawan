@extends('layouts.main')

@section('page-content')
<div class="p-6 pb-8">
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('log-aktivitas.index') }}" class="text-gray-600 hover:text-gray-900">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Log Aktivitas</h1>
                <p class="text-sm text-gray-600 mt-1">Ubah aktivitas harian Anda</p>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <form method="POST" action="{{ route('log-aktivitas.update', $log->id) }}">
            @csrf
            @method('PUT')

            <!-- Tanggal Field (Read Only) -->
            <div class="mb-6">
                <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                    <div class="flex items-center">
                        <i data-lucide="calendar" class="w-5 h-5 text-teal-500 mr-2"></i>
                        Tanggal
                    </div>
                </label>
                <input type="date"
                    id="tanggal"
                    value="{{ \Carbon\Carbon::parse($log->tanggal)->format('Y-m-d') }}"
                    disabled
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed">
                <p class="mt-1 text-xs text-gray-500">Tanggal tidak dapat diubah</p>
            </div>

            <!-- Waktu Awal & Akhir Field -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <!-- Waktu Awal Field -->
                <div>
                    <label for="waktu_awal" class="block text-sm font-medium text-gray-700 mb-2">
                        <div class="flex items-center">
                            <i data-lucide="clock" class="w-5 h-5 text-teal-500 mr-2"></i>
                            Waktu Awal (WIB)
                        </div>
                    </label>
                    <input type="time"
                        id="waktu_awal"
                        name="waktu_awal"
                        value="{{ old('waktu_awal', \Carbon\Carbon::parse($log->waktu_awal)->format('H:i')) }}"
                        required
                        step="60"
                        class="w-full px-4 py-3 border @error('waktu_awal') border-red-300 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition duration-150 ease-in-out @error('waktu_awal') bg-red-50 @else bg-gray-50 @enderror">
                    @error('waktu_awal')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                        {{ $message }}
                    </p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Format 24 jam (00:00 - 23:59)</p>
                </div>

                <!-- Waktu Akhir Field -->
                <div>
                    <label for="waktu_akhir" class="block text-sm font-medium text-gray-700 mb-2">
                        <div class="flex items-center">
                            <i data-lucide="clock" class="w-5 h-5 text-teal-500 mr-2"></i>
                            Waktu Akhir (WIB)
                        </div>
                    </label>
                    <input type="time"
                        id="waktu_akhir"
                        name="waktu_akhir"
                        value="{{ old('waktu_akhir', \Carbon\Carbon::parse($log->waktu_akhir)->format('H:i')) }}"
                        required
                        step="60"
                        class="w-full px-4 py-3 border @error('waktu_akhir') border-red-300 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition duration-150 ease-in-out @error('waktu_akhir') bg-red-50 @else bg-gray-50 @enderror">
                    @error('waktu_akhir')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                        {{ $message }}
                    </p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Format 24 jam (00:00 - 23:59)</p>
                </div>
            </div>

            <!-- Aktivitas Field -->
            <div class="mb-6">
                <label for="aktivitas" class="block text-sm font-medium text-gray-700 mb-2">
                    <div class="flex items-center">
                        <i data-lucide="file-text" class="w-5 h-5 text-teal-500 mr-2"></i>
                        Aktivitas Harian
                    </div>
                </label>
                <textarea id="aktivitas"
                    name="aktivitas"
                    rows="8"
                    required
                    minlength="10"
                    class="w-full px-4 py-3 border @error('aktivitas') border-red-300 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition duration-150 ease-in-out @error('aktivitas') bg-red-50 @else bg-gray-50 @enderror"
                    placeholder="Jelaskan aktivitas, tugas, atau kegiatan kerja yang Anda lakukan pada hari tersebut (minimal 10 karakter)...">{{ old('aktivitas', $log->aktivitas) }}</textarea>
                @error('aktivitas')
                <p class="mt-2 text-sm text-red-600 flex items-center">
                    <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                    {{ $message }}
                </p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Minimal 10 karakter</p>
            </div>

            <!-- Info Box -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-yellow-600 mr-2 mt-0.5"></i>
                    <div class="text-sm text-yellow-800">
                        <p class="font-medium mb-1">Perhatian:</p>
                        <p class="text-yellow-700">Anda hanya dapat mengedit log yang statusnya masih "Menunggu Validasi". Setelah divalidasi, log tidak dapat diubah lagi.</p>
                    </div>
                </div>
            </div>

            <!-- Button Actions -->
            <div class="flex items-center gap-3">
                <button type="submit" class="flex items-center gap-2 px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors font-medium">
                    <i data-lucide="save" class="w-5 h-5"></i>
                    Simpan Perubahan
                </button>
                <a href="{{ route('log-aktivitas.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<!-- <script>
    $(document).ready(function() {
        $('#waktu_awal, #waktu_akhir').attr('lang', 'id');
        $('#waktu_awal').on('change', function() {
            const value = $(this).val();
            if (value) {
                const [hours, minutes] = value.split(':');
                if (parseInt(hours) >= 24) {
                    $(this).val('23:59');
                }
            }
        });

    });
</script> -->
<script src="{{ asset('js/scripts/log-aktivitas/log-aktivitas.edit.js') }}"></script>
@endsection
