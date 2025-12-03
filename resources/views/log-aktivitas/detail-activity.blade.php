@extends('layouts.main')

@section('page-content')
<div class="p-6 pb-8">
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex items-center justify-between gap-4 mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Aktivitas Karyawan</h1>
                <p class="text-sm text-gray-600 mt-1">Daftar lengkap log aktivitas karyawan</p>
            </div>
            <a href="{{ route('home') }}" class="flex items-center text-white px-3 py-2 hover:no-underline border rounded-lg bg-gray-600 hover:bg-gray-900">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Kembali
            </a>
        </div>
    </div>

    <!-- Info Karyawan Section -->
    <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
        <h3 class="text-sm font-medium text-gray-500 mb-4">Informasi Karyawan</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <p class="text-xs text-gray-500 mb-1">Nama</p>
                <p class="text-sm font-medium text-gray-900">{{ $user->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">NIK</p>
                <p class="text-sm font-medium text-gray-900">{{ $user->nik ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Departemen</p>
                <p class="text-sm font-medium text-gray-900">{{ $user->nama_departemen ?? 'Belum Ditentukan' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Unit</p>
                <p class="text-sm font-medium text-gray-900">{{ $user->nama_unit ?? 'Belum Ditentukan' }}</p>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-sm font-medium text-gray-900">Daftar Log Aktivitas</h3>
            <p class="text-xs text-gray-500 mt-1">Total: {{ $activities->count() }} aktivitas</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktivitas</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Departemen</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Validasi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($activities as $activity)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-900">
                            <i data-lucide="chevron-down"></i>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-900">
                            {{ \Carbon\Carbon::parse($activity->tanggal)->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-900">
                            <div class="flex items-center gap-1">
                                <i data-lucide="clock" class="w-3 h-3 text-teal-500"></i>
                                <span>{{ \Carbon\Carbon::parse($activity->waktu_awal)->format('H:i') }} - {{ \Carbon\Carbon::parse($activity->waktu_akhir)->format('H:i') }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-900">
                            <div class="max-w-md">
                                <p class="line-clamp-2">{{ Str::limit($activity->aktivitas, 100) }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-900">
                            {{ $activity->nama_departemen ?? 'Belum Ditentukan' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-900">
                            {{ $activity->nama_unit ?? 'Belum Ditentukan' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @if($activity->status == 'menunggu')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Menunggu
                            </span>
                            @elseif($activity->status == 'tervalidasi')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Tervalidasi
                            </span>
                            @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                Ditolak
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-600">
                            @if($activity->validated_by && $activity->validated_at)
                            <div class="space-y-1">
                                <div class="flex items-center gap-1">
                                    <i data-lucide="user-check" class="w-3 h-3"></i>
                                    <span>{{ $activity->nama_validator ?? 'N/A' }}</span>
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($activity->validated_at)->format('d/m/Y H:i') }}
                                </div>
                                @if($activity->catatan_validasi)
                                <div class="text-xs text-gray-600 bg-gray-50 rounded p-1 mt-1">
                                    <strong>Catatan:</strong> {{ Str::limit($activity->catatan_validasi, 50) }}
                                </div>
                                @endif
                            </div>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-xs text-gray-500">
                            <div class="flex flex-col items-center gap-2">
                                <i data-lucide="inbox" class="w-8 h-8 text-gray-400"></i>
                                <span>Tidak ada data log aktivitas</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        lucide.createIcons();
    });
</script>

@endsection
