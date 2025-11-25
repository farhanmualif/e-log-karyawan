@extends('layouts.main')

@section('page-content')
<div class="p-6 pb-8">
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <!-- Title -->
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Log Aktivitas Saya</h1>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">Kelola log aktivitas harian saya</p>
            </div>

            <a href="{{ route('log-aktivitas.create') }}" class="flex items-center gap-2 px-3 py-1.5 bg-teal-600 text-white rounded-md hover:bg-teal-700 hover:no-underline transition-colors text-xs sm:text-sm font-medium whitespace-nowrap">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Tambah Log
            </a>
        </div>

        <!-- Filter Section -->
        <form method="GET" action="{{ route('log-aktivitas.index') }}" class="bg-white p-3 rounded-lg border border-gray-200 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Filter Tanggal Dari -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal Dari</label>
                    <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" class="w-full px-2 py-1.5 border border-gray-300 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                </div>

                <!-- Filter Tanggal Sampai -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal Sampai</label>
                    <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" class="w-full px-2 py-1.5 border border-gray-300 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                </div>

                <!-- Filter Status -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-2 py-1.5 border border-gray-300 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent bg-white">
                        <option value="">Semua Status</option>
                        <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu Validasi</option>
                        <option value="tervalidasi" {{ request('status') == 'tervalidasi' ? 'selected' : '' }}>Tervalidasi</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-2 mt-4">
                <button type="submit" class="px-2.5 py-1.5 bg-teal-600 text-white rounded-md hover:bg-teal-700 transition-colors text-xs font-medium">
                    <i data-lucide="filter" class="w-3 h-3 inline mr-1"></i>
                    Filter
                </button>
                <a href="{{ route('log-aktivitas.index') }}" class="px-2.5 py-1.5 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 hover:no-underline transition-colors text-xs font-medium">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Bulk Action Section -->
    <!-- @if(in_array(Auth::user()->role, ['spv', 'manager', 'sdm', 'superadmin']))
    <div id="bulkActionBar" class="hidden bg-teal-50 border border-teal-200 rounded-lg p-4 mb-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-sm font-medium text-teal-900">
                    <span id="selectedCount">0</span> item dipilih
                </span>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" id="bulkApproveBtn" class="px-4 py-2 bg-teal-600 text-white text-sm rounded-lg hover:bg-teal-700 transition-colors font-medium">
                    <i data-lucide="check-circle" class="w-4 h-4 inline mr-1"></i>
                    Setujui Massal
                </button>
                <button type="button" id="bulkRejectBtn" class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition-colors font-medium">
                    <i data-lucide="x-circle" class="w-4 h-4 inline mr-1"></i>
                    Tolak Massal
                </button>
                <button type="button" id="cancelBulkAction" class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-lg hover:bg-gray-300 transition-colors font-medium">
                    Batal
                </button>
            </div>
        </div>
    </div>
    @endif -->

    <!-- Table Section -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <!-- @if(in_array(Auth::user()->role, ['spv', 'manager', 'sdm', 'superadmin']))
                        <th class="px-4 py-2 text-left">
                            <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                        </th>
                        @endif -->
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Karyawan</th>
                        @if(Auth::user()->role != 'karyawan')
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIK</th>
                        @endif
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Aktivitas</th>
                        <!-- <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th> -->
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50">
                        <!-- @if(in_array(Auth::user()->role, ['spv', 'manager', 'sdm', 'superadmin']))
                        <td class="px-4 py-2 whitespace-nowrap">
                            @if(isset($log->status) && $log->status == 'menunggu')
                            <input type="checkbox" name="selected_items[]" value="{{ $log->tanggal }}_{{ $log->user_id }}" class="item-checkbox rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                            @endif
                        </td>
                        @endif -->
                        <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-900">
                            {{ isset($log->tanggal) ? \Carbon\Carbon::parse($log->tanggal)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-900">
                            <div class="font-medium">{{ $log->nama_karyawan ?? '-' }}</div>
                        </td>
                        @if(Auth::user()->role != 'karyawan')
                        <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-900">
                            {{ $log->nik ?? '-' }}
                        </td>
                        @endif
                        <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-900">
                            <div class="flex flex-col gap-1">
                                <div class="text-xs font-medium">{{ $log->total_aktivitas ?? 0 }} aktivitas</div>
                                @if(isset($log->status_count))
                                <div class="flex flex-wrap gap-1.5 text-xs">
                                    @if(($log->status_count['menunggu'] ?? 0) > 0)
                                    <span class="px-1.5 py-0.5 rounded bg-yellow-100 text-yellow-800 text-xs">
                                        Menunggu: {{ $log->status_count['menunggu'] }}
                                    </span>
                                    @endif
                                    @if(($log->status_count['tervalidasi'] ?? 0) > 0)
                                    <span class="px-1.5 py-0.5 rounded bg-green-100 text-green-800 text-xs">
                                        Tervalidasi: {{ $log->status_count['tervalidasi'] }}
                                    </span>
                                    @endif
                                    @if(($log->status_count['ditolak'] ?? 0) > 0)
                                    <span class="px-1.5 py-0.5 rounded bg-red-100 text-red-800 text-xs">
                                        Ditolak: {{ $log->status_count['ditolak'] }}
                                    </span>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </td>
                        <!-- <td class="px-4 py-2 whitespace-nowrap">
                            @if(isset($log->status) && $log->status == 'menunggu')
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Menunggu Validasi
                            </span>
                            @elseif(isset($log->status) && $log->status == 'tervalidasi')
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Tervalidasi
                            </span>
                            @else
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                Ditolak
                            </span>
                            @endif
                        </td> -->
                        <td class="px-4 py-2 whitespace-nowrap text-xs font-medium">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('log-aktivitas.show', ['tanggal' => $log->tanggal ?? '', 'user_id' => $log->user_id ?? '']) }}" class="text-teal-600 hover:text-teal-900" title="Detail">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ in_array(Auth::user()->role, ['spv', 'manager', 'sdm', 'superadmin']) ? (Auth::user()->role != 'karyawan' ? '7' : '6') : (Auth::user()->role != 'karyawan' ? '6' : '5') }}" class="px-4 py-2 text-center text-xs text-gray-500">
                            Tidak ada data log aktivitas
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $logs->links() }}
        </div>
    </div>
</div>

<!-- Bulk Reject Modal -->
@if(in_array(Auth::user()->role, ['spv', 'manager', 'sdm', 'superadmin']))
<div id="bulkRejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-4">Tolak Log Aktivitas (Massal)</h3>
            <form id="bulkRejectForm" method="POST" action="{{ route('log-aktivitas.bulk-reject') }}">
                @csrf
                <div class="mb-4">
                    <label for="catatan_validasi" class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Catatan Penolakan</label>
                    <textarea id="catatan_validasi" name="catatan_validasi" rows="4" required minlength="5" class="w-full px-3 py-2 text-xs sm:text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent" placeholder="Berikan alasan penolakan..."></textarea>
                    <p class="mt-1 text-xs text-gray-500">Minimal 5 karakter</p>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white text-xs sm:text-sm rounded-lg hover:bg-red-700 transition-colors font-medium">
                        Tolak
                    </button>
                    <button type="button" onclick="closeBulkRejectModal()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 text-xs sm:text-sm rounded-lg hover:bg-gray-300 transition-colors font-medium">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script>
    $(document).ready(function() {

        const $selectAllCheckbox = $('#selectAll');
        const $itemCheckboxes = $('.item-checkbox');
        const $bulkActionBar = $('#bulkActionBar');
        const $selectedCountSpan = $('#selectedCount');
        const $bulkApproveBtn = $('#bulkApproveBtn');
        const $bulkRejectBtn = $('#bulkRejectBtn');
        const $cancelBulkActionBtn = $('#cancelBulkAction');
        const $bulkRejectForm = $('#bulkRejectForm');

        if ($selectAllCheckbox.length) {
            $selectAllCheckbox.on('change', function() {
                $itemCheckboxes.prop('checked', $(this).prop('checked'));
                updateBulkActionBar();
            });
        }

        $itemCheckboxes.on('change', function() {
            updateBulkActionBar();
            if ($selectAllCheckbox.length) {
                $selectAllCheckbox.prop(
                    'checked',
                    $itemCheckboxes.length === $itemCheckboxes.filter(':checked').length
                );
            }
        });

        function updateBulkActionBar() {
            const selectedCount = $itemCheckboxes.filter(':checked').length;

            if (selectedCount > 0) {
                $bulkActionBar.removeClass('hidden');
                $selectedCountSpan.text(selectedCount);
            } else {
                $bulkActionBar.addClass('hidden');
            }
        }

        $bulkApproveBtn.on('click', function() {
            const selected = $itemCheckboxes.filter(':checked');

            if (selected.length === 0) {
                alert('Pilih minimal 1 item untuk divalidasi');
                return;
            }

            if (confirm('Apakah Anda yakin ingin menyetujui ' + selected.length + ' log aktivitas?')) {
                const $form = $('<form>', {
                    method: 'POST',
                    action: '{{ route("log-aktivitas.bulk-approve") }}'
                });

                $form.append($('<input>', {
                    type: 'hidden',
                    name: '_token',
                    value: '{{ csrf_token() }}'
                }));

                selected.each(function() {
                    $form.append($('<input>', {
                        type: 'hidden',
                        name: 'selected_items[]',
                        value: $(this).val()
                    }));
                });

                $('body').append($form);
                $form.submit();
            }
        });

        $bulkRejectBtn.on('click', function() {
            const selected = $itemCheckboxes.filter(':checked');

            if (selected.length === 0) {
                alert('Pilih minimal 1 item untuk ditolak');
                return;
            }

            $bulkRejectForm.find('input[name="selected_items[]"]').remove();

            selected.each(function() {
                $bulkRejectForm.append($('<input>', {
                    type: 'hidden',
                    name: 'selected_items[]',
                    value: $(this).val()
                }));
            });

            openBulkRejectModal();
        });

        $cancelBulkActionBtn.on('click', function() {
            $itemCheckboxes.prop('checked', false);
            $selectAllCheckbox.prop('checked', false);
            updateBulkActionBar();
        });

        function openBulkRejectModal() {
            $('#bulkRejectModal').removeClass('hidden');
        }

        window.closeBulkRejectModal = function() {
            $('#bulkRejectModal').addClass('hidden');
        };

    });
</script>

@endsection
