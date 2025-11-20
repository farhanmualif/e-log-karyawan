@extends('layouts.main')

@section('page-content')
<div class="p-6 pb-8">
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex items-center justify-between gap-4 mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Profile Saya</h1>
                <p class="text-sm text-gray-600 mt-1">Kelola informasi profile Anda</p>
            </div>
        </div>
    </div>

    <!-- Profile Card -->
    <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
        <!-- Avatar Section -->
        <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-200">
            <div class="w-20 h-20 rounded-full bg-teal-500 flex items-center justify-center text-white text-2xl font-bold">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                <p class="text-sm text-gray-600">Role: {{ $user->username }}</p>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800 mt-1">
                    {{ ucfirst($user->role) }}
                </span>
            </div>
        </div>

        <!-- Form Section -->
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Field -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        <div class="flex items-center">
                            <i data-lucide="user" class="w-5 h-5 text-teal-500 mr-2"></i>
                            Nama Lengkap <span class="text-red-500">*</span>
                        </div>
                    </label>
                    <input type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        required
                        maxlength="150"
                        class="w-full px-4 py-3 border @error('name') border-red-300 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition duration-150 ease-in-out @error('name') bg-red-50 @else bg-gray-50 @enderror"
                        placeholder="Masukkan nama lengkap">
                    @error('name')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Username/NIK Field (Readonly) -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                        <div class="flex items-center">
                            <i data-lucide="id-card" class="w-5 h-5 text-teal-500 mr-2"></i>
                            Username / NIK
                        </div>
                    </label>
                    <input type="text"
                        id="username"
                        value="{{ $user->username }}"
                        readonly
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed">
                    <p class="mt-1 text-xs text-gray-500">Username tidak dapat diubah</p>
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <div class="flex items-center">
                            <i data-lucide="mail" class="w-5 h-5 text-teal-500 mr-2"></i>
                            Email
                        </div>
                    </label>
                    <input type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        maxlength="150"
                        class="w-full px-4 py-3 border @error('email') border-red-300 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition duration-150 ease-in-out @error('email') bg-red-50 @else bg-gray-50 @enderror"
                        placeholder="Masukkan email (opsional)">
                    @error('email')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Role Field (Readonly) -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                        <div class="flex items-center">
                            <i data-lucide="shield" class="w-5 h-5 text-teal-500 mr-2"></i>
                            Role
                        </div>
                    </label>
                    <input type="text"
                        id="role"
                        value="{{ ucfirst($user->role) }}"
                        readonly
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed">
                    <p class="mt-1 text-xs text-gray-500">Role tidak dapat diubah</p>
                </div>

                <!-- Departemen Field -->
                <div>
                    <label for="departemen_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <div class="flex items-center">
                            <i data-lucide="building" class="w-5 h-5 text-teal-500 mr-2"></i>
                            Departemen
                        </div>
                    </label>
                    <select id="departemen_id"
                        name="departemen_id"
                        class="w-full px-4 py-3 border @error('departemen_id') border-red-300 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition duration-150 ease-in-out @error('departemen_id') bg-red-50 @else bg-gray-50 @enderror">
                        <option value="">Belum Ditentukan</option>
                        @foreach($departemenList as $dept)
                        <option value="{{ $dept->id }}" {{ old('departemen_id', $user->departemen_id) == $dept->id ? 'selected' : '' }}>
                            {{ $dept->nama }}
                        </option>
                        @endforeach
                    </select>
                    @error('departemen_id')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                        {{ $message }}
                    </p>
                    @enderror
                    @if(!$user->departemen_id)
                    <p class="mt-1 text-xs text-gray-500">Saat ini: <span class="font-medium text-gray-700">Belum Ditentukan</span></p>
                    @else
                    <p class="mt-1 text-xs text-gray-500">Saat ini: <span class="font-medium text-gray-700">{{ $departemenNama }}</span></p>
                    @endif
                </div>

                <!-- Unit Field -->
                <div>
                    <label for="unit_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <div class="flex items-center">
                            <i data-lucide="briefcase" class="w-5 h-5 text-teal-500 mr-2"></i>
                            Unit
                        </div>
                    </label>
                    <select id="unit_id"
                        name="unit_id"
                        class="w-full px-4 py-3 border @error('unit_id') border-red-300 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition duration-150 ease-in-out @error('unit_id') bg-red-50 @else bg-gray-50 @enderror">
                        <option value="">Belum Ditentukan</option>
                        @foreach($unitList as $unit)
                        <option value="{{ $unit->id }}"
                            data-departemen="{{ $unit->departemen_id }}"
                            {{ old('unit_id', $user->unit_id) == $unit->id ? 'selected' : '' }}>
                            {{ $unit->nama }} ({{ $unit->nama_departemen }})
                        </option>
                        @endforeach
                    </select>
                    @error('unit_id')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                        {{ $message }}
                    </p>
                    @enderror
                    @if(!$user->unit_id)
                    <p class="mt-1 text-xs text-gray-500">Saat ini: <span class="font-medium text-gray-700">Belum Ditentukan</span></p>
                    @else
                    <p class="mt-1 text-xs text-gray-500">Saat ini: <span class="font-medium text-gray-700">{{ $unitNama }}</span></p>
                    @endif
                </div>
            </div>

            <!-- Button Actions -->
            <div class="flex items-center gap-3 mt-6 pt-6 border-t border-gray-200">
                <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors font-medium">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Simpan Perubahan
                </button>
                <a href="{{ route('home') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        lucide.createIcons();

        // Function untuk filter unit berdasarkan departemen
        function loadUnitsForDepartemen(departemenId, selectedUnitId = null) {
            const $unitSelect = $('#unit_id');
            const currentValue = $unitSelect.val();

            // Sembunyikan semua option kecuali "Belum Ditentukan"
            $unitSelect.find('option').each(function() {
                if ($(this).val() === '') {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });

            // Tampilkan unit yang sesuai dengan departemen yang dipilih
            if (departemenId) {
                $unitSelect.find('option[data-departemen="' + departemenId + '"]').show();
            } else {
                // Jika tidak ada departemen dipilih, tampilkan semua unit
                $unitSelect.find('option').show();
            }

            // Reset unit jika unit yang dipilih tidak sesuai dengan departemen
            if (departemenId && currentValue) {
                const selectedUnit = $unitSelect.find('option[value="' + currentValue + '"]');
                if (selectedUnit.length && selectedUnit.data('departemen') != departemenId) {
                    $unitSelect.val('');
                }
            }

            // Set selected unit jika ada
            if (selectedUnitId) {
                $unitSelect.val(selectedUnitId);
            }
        }

        // Event handler untuk perubahan departemen
        $('#departemen_id').on('change', function() {
            const departemenId = $(this).val();
            const currentUnitId = $('#unit_id').val();
            loadUnitsForDepartemen(departemenId, currentUnitId);
        });

        // Load unit saat halaman pertama kali dimuat
        const initialDepartemenId = $('#departemen_id').val();
        const initialUnitId = $('#unit_id').val();
        loadUnitsForDepartemen(initialDepartemenId, initialUnitId);
    });
</script>

@endsection
