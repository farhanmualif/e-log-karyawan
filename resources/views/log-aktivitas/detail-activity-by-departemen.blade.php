@extends('layouts.main')

@section('page-content')
<div class="p-3 sm:p-4 md:p-6 pb-6 sm:pb-8">
    <!-- Header Section -->
    <div class="mb-4 sm:mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4 mb-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Detail Aktivitas Departemen</h1>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">Daftar lengkap log aktivitas seluruh karyawan di departemen</p>
            </div>
            <a href="{{ route('home') }}" class="flex items-center justify-center text-white px-3 py-2 hover:no-underline border rounded-lg bg-gray-600 hover:bg-gray-900 transition-colors text-sm sm:text-base">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <!-- Info Departemen Section -->
    <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6 mb-4 sm:mb-6">
        <h3 class="text-sm sm:text-base font-medium text-gray-500 mb-3 sm:mb-4">Informasi Departemen</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <div>
                <p class="text-xs sm:text-sm text-gray-500 mb-1">Nama Departemen</p>
                <p class="text-sm sm:text-base font-medium text-gray-900">{{ $departemen->nama ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs sm:text-sm text-gray-500 mb-1">Total Aktivitas</p>
                <p class="text-sm sm:text-base font-medium text-gray-900">{{ $totalActivities ?? 0 }}</p>
            </div>
            <div>
                <p class="text-xs sm:text-sm text-gray-500 mb-1">Total Karyawan</p>
                <p class="text-sm sm:text-base font-medium text-gray-900">{{ $totalKaryawan ?? 0 }}</p>
            </div>
            <div>
                <p class="text-xs sm:text-sm text-gray-500 mb-1">Status</p>
                <div class="flex flex-wrap gap-1.5">
                    @if(isset($statusCounts['menunggu']) && $statusCounts['menunggu'] > 0)
                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        Menunggu: {{ $statusCounts['menunggu'] }}
                    </span>
                    @endif
                    @if(isset($statusCounts['tervalidasi']) && $statusCounts['tervalidasi'] > 0)
                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                        Tervalidasi: {{ $statusCounts['tervalidasi'] }}
                    </span>
                    @endif
                    @if(isset($statusCounts['ditolak']) && $statusCounts['ditolak'] > 0)
                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                        Ditolak: {{ $statusCounts['ditolak'] }}
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <h3 class="text-sm sm:text-base font-medium text-gray-900">Daftar Karyawan</h3>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Total: <span class="font-semibold">{{ $totalKaryawan }}</span> karyawan dengan <span class="font-semibold">{{ $activities->count() }}</span> aktivitas</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider w-10 sm:w-12"></th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Karyawan</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">NIK</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Unit</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Total Aktivitas</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($paginatedGroupedActivities as $karyawanData)
                    @php
                    $karyawan = $karyawanData['karyawan'];
                    $userId = $karyawan->user_id ?? null;
                    $karyawanActivitiesPaginated = $karyawanData['activities'];
                    $karyawanActivities = $karyawanData['activities_all'] ?? $karyawanData['activities'];
                    $totalAktivitas = $karyawanData['total_aktivitas'];
                    $statusCounts = $karyawanData['status_counts'];
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors cursor-pointer accordion-header" data-user-id="{{ $userId }}">
                        <td class="px-2 sm:px-4 py-2 sm:py-3 whitespace-nowrap">
                            <button type="button" class="accordion-toggle-btn text-gray-400 hover:text-gray-600 transition-colors" data-user-id="{{ $userId }}">
                                <i data-lucide="chevron-down" class="w-4 h-4 accordion-icon" data-user-id="{{ $userId }}"></i>
                            </button>
                        </td>
                        <td class="px-2 sm:px-4 py-2 sm:py-3 whitespace-nowrap">
                            <div class="font-medium text-xs sm:text-sm text-gray-900">{{ $karyawan->nama_karyawan ?? '-' }}</div>
                            <div class="text-xs text-gray-500 sm:hidden mt-0.5">{{ $karyawan->nik_karyawan ?? '-' }}</div>
                        </td>
                        <td class="px-2 sm:px-4 py-2 sm:py-3 whitespace-nowrap text-xs sm:text-sm text-gray-900 hidden sm:table-cell">
                            {{ $karyawan->nik_karyawan ?? '-' }}
                        </td>
                        <td class="px-2 sm:px-4 py-2 sm:py-3 whitespace-nowrap text-xs sm:text-sm text-gray-900 hidden md:table-cell">
                            {{ $karyawan->nama_unit ?? 'Belum Ditentukan' }}
                        </td>
                        <td class="px-2 sm:px-4 py-2 sm:py-3 whitespace-nowrap text-xs sm:text-sm text-gray-900">
                            <span class="font-semibold">{{ $totalAktivitas }}</span> aktivitas
                        </td>
                        <td class="px-2 sm:px-4 py-2 sm:py-3 whitespace-nowrap">
                            <div class="flex flex-wrap gap-1">
                                @if(isset($statusCounts['menunggu']) && $statusCounts['menunggu'] > 0)
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Menunggu: {{ $statusCounts['menunggu'] }}
                                </span>
                                @endif
                                @if(isset($statusCounts['tervalidasi']) && $statusCounts['tervalidasi'] > 0)
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Tervalidasi: {{ $statusCounts['tervalidasi'] }}
                                </span>
                                @endif
                                @if(isset($statusCounts['ditolak']) && $statusCounts['ditolak'] > 0)
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Ditolak: {{ $statusCounts['ditolak'] }}
                                </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    <!-- Accordion Content - Aktivitas Karyawan -->
                    <tr class="accordion-content hidden" id="accordion-{{ $userId }}">
                        <td colspan="6" class="px-0 py-0 bg-gray-50">
                            <div class="px-3 sm:px-6 py-3 sm:py-4" id="activities-container-{{ $userId }}" data-departemen-id="{{ $departemen_id }}" data-user-id="{{ $userId }}">
                                <h4 class="text-xs sm:text-sm font-semibold text-gray-700 mb-3">Daftar Aktivitas</h4>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="px-2 sm:px-3 py-2 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                                <th class="px-2 sm:px-3 py-2 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                                <th class="px-2 sm:px-3 py-2 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Aktivitas</th>
                                                <th class="px-2 sm:px-3 py-2 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                <th class="px-2 sm:px-3 py-2 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Validasi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200" id="activities-tbody-{{ $userId }}">
                                            @foreach($karyawanActivitiesPaginated as $activity)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-2 sm:px-3 py-2 whitespace-nowrap text-xs sm:text-sm text-gray-900">
                                                    {{ \Carbon\Carbon::parse($activity->tanggal)->format('d/m/Y') }}
                                                </td>
                                                <td class="px-2 sm:px-3 py-2 whitespace-nowrap text-xs sm:text-sm text-gray-900">
                                                    <div class="flex items-center gap-1">
                                                        <i data-lucide="clock" class="w-3 h-3 text-teal-500"></i>
                                                        <span>{{ \Carbon\Carbon::parse($activity->waktu_awal)->format('H:i') }} - {{ \Carbon\Carbon::parse($activity->waktu_akhir)->format('H:i') }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-2 sm:px-3 py-2 text-xs sm:text-sm text-gray-900">
                                                    <div class="max-w-md">
                                                        <p class="line-clamp-2">{{ Str::limit($activity->aktivitas, 100) }}</p>
                                                    </div>
                                                </td>
                                                <td class="px-2 sm:px-3 py-2 whitespace-nowrap">
                                                    @if($activity->status == 'menunggu')
                                                    <span class="px-2 py-0.5 sm:py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Menunggu
                                                    </span>
                                                    @elseif($activity->status == 'tervalidasi')
                                                    <span class="px-2 py-0.5 sm:py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                        Tervalidasi
                                                    </span>
                                                    @else
                                                    <span class="px-2 py-0.5 sm:py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                        Ditolak
                                                    </span>
                                                    @endif
                                                </td>
                                                <td class="px-2 sm:px-3 py-2 text-xs sm:text-sm text-gray-600">
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
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination untuk activities per karyawan -->
                                <div id="activities-pagination-{{ $userId }}">
                                    @if($karyawanActivitiesPaginated->hasPages())
                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        <div class="flex flex-col sm:flex-row items-center justify-between gap-2">
                                            <div class="text-xs text-gray-600">
                                                Menampilkan {{ $karyawanActivitiesPaginated->firstItem() }} - {{ $karyawanActivitiesPaginated->lastItem() }} dari {{ $karyawanActivitiesPaginated->total() }} aktivitas
                                            </div>
                                            <div class="flex items-center justify-center">
                                                {{ $karyawanActivitiesPaginated->links('components.activities-pagination', ['userId' => $userId]) }}

                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-sm sm:text-base text-gray-500">
                            <div class="flex flex-col items-center gap-2">
                                <i data-lucide="inbox" class="w-8 h-8 text-gray-400"></i>
                                <span>Tidak ada data log aktivitas untuk departemen ini</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($paginatedGroupedActivities->hasPages())
        <div class="border-t border-gray-200">
            {{ $paginatedGroupedActivities->links() }}
        </div>
        @endif
    </div>

    <script>
        window.routeGetActivitiesByUser = "{{ route('log-aktivitas.get-activities-by-user', ['departemen_id' => $departemen_id, 'user_id' => ':user_id']) }}";
    </script>
    <script src="{{ asset('js/scripts/log-aktivitas/detail-activity-perdepart.js') }}"></script>
</div>

@endsection
