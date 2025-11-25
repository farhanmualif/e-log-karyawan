@extends('layouts.main')

@section('page-content')
@php
$currentUser = Auth::user();
$karyawanRole = $karyawan_role ?? 'karyawan';

$canPerformAction = function() use ($currentUser, $karyawanRole) {
if (in_array($currentUser->role, ['admin', 'sdm', 'superadmin'])) {
return true;
}
if ($currentUser->role === 'spv' && $karyawanRole === 'manager') {
return false;
}
if ($currentUser->role === 'manager' && in_array($karyawanRole, ['karyawan', 'spv'])) {
return true;
}
if ($currentUser->role === 'spv' && $karyawanRole === 'karyawan') {
return true;
}
return false;
};

$hasActionPermission = $canPerformAction();
@endphp
<div class="p-6 pb-8">
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex items-center justify-between gap-4 mb-4">

            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Log Aktivitas</h1>
                <p class="text-sm text-gray-600 mt-1">Informasi lengkap log aktivitas harian Unit </p>
            </div>
            <a href="{{ route('log-aktivitas.index') }}" class="flex items-center text-white px-3 py-2  hover:no-underline border rounded-lg bg-gray-600 hover:bg-gray-900">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Kembali
            </a>
        </div>
    </div>

    <!-- Detail Section -->
    <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
        <!-- Info Karyawan -->
        <div class="mb-6 pb-6 border-b border-gray-200">
            <h3 class="text-sm font-medium text-gray-500 mb-3">Informasi Karyawan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Nama</p>
                    <p class="text-sm font-medium text-gray-900">{{ $karyawan->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">NIK</p>
                    <p class="text-sm font-medium text-gray-900">{{ $karyawan->username }}</p>
                </div>
            </div>
        </div>

        <!-- Info Tanggal & Status -->
        <div class="mb-6 pb-6 border-b border-gray-200">
            <h3 class="text-sm font-medium text-gray-500 mb-3">Informasi Log</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Tanggal</p>
                    <p class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}</p>
                </div>
                <!-- <div>
                    <p class="text-xs text-gray-500 mb-1">Status</p>
                    <div>
                        @if($status == 'menunggu')
                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Menunggu Validasi
                        </span>
                        @elseif($status == 'tervalidasi')
                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            Tervalidasi
                        </span>
                        @else
                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                            Ditolak
                        </span>
                        @endif
                    </div>
                </div> -->
                <div>
                    <p class="text-xs text-gray-500 mb-1">Departemen</p>
                    <p class="text-sm font-medium text-gray-900">{{ $departemen_nama }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Unit</p>
                    <p class="text-sm font-medium text-gray-900">{{ $unit_nama }}</p>
                </div>
            </div>
        </div>

        <!-- Bulk Action Section -->
        @if($hasActionPermission && $status == 'menunggu')
        <div id="bulkActionBar" class="hidden bg-teal-50 border border-teal-200 rounded-lg p-4 mb-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-sm font-medium text-teal-900">
                        <span id="selectedCount">0</span> aktivitas dipilih
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" id="bulkApproveBtn" class="px-4 py-2 bg-teal-600 text-white text-sm rounded-lg hover:bg-teal-700 transition-colors font-medium">
                        <i data-lucide="check-circle" class="w-4 h-4 inline mr-1"></i>
                        Terima
                    </button>
                    <button type="button" id="bulkRejectBtn" class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition-colors font-medium">
                        <i data-lucide="x-circle" class="w-4 h-4 inline mr-1"></i>
                        Tolak
                    </button>
                    <button type="button" id="cancelBulkAction" class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-lg hover:bg-gray-300 transition-colors font-medium">
                        Batal
                    </button>
                </div>
            </div>
        </div>
        @endif

        <!-- List Aktivitas Per Jam -->
        <div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-gray-500">Daftar Aktivitas Per Jam</h3>
                @if($hasActionPermission && $status == 'menunggu')
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="selectAllLogs" class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                    <label for="selectAllLogs" class="text-xs text-gray-600">Pilih Semua</label>
                </div>
                @endif
            </div>
            <div class="space-y-2"></div>
        </div>
        @forelse($logs as $log)
        <div class="border border-gray-200 rounded-lg p-2 bg-gray-50 m-4" data-log-id="{{ $log->id }}" data-waktu-awal="{{ \Carbon\Carbon::parse($log->waktu_awal)->format('H:i') }}" data-waktu-akhir="{{ \Carbon\Carbon::parse($log->waktu_akhir)->format('H:i') }}" data-aktivitas="{{ htmlspecialchars($log->aktivitas, ENT_QUOTES, 'UTF-8') }}">
            <div class="flex items-start justify-between mb-2">
                <div class="flex items-center gap-3">
                    @if($hasActionPermission && $log->status == 'menunggu')
                    <input type="checkbox" name="log_ids[]" value="{{ $log->id }}" class="log-checkbox rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                    @endif
                    <div class="flex items-center gap-2">
                        <i data-lucide="clock" class="w-4 h-4 text-teal-500"></i>
                        <span class="text-sm font-medium text-gray-900">
                            {{ \Carbon\Carbon::parse($log->waktu_awal)->format('H:i') }} - {{ \Carbon\Carbon::parse($log->waktu_akhir)->format('H:i') }} WIB
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @if($log->status == 'menunggu')
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        Menunggu
                    </span>
                    @elseif($log->status == 'tervalidasi')
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                        Tervalidasi
                    </span>
                    @else
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                        Ditolak
                    </span>
                    @endif

                    @if($log->status == 'menunggu' && ((Auth::user()->role === 'karyawan' && $log->user_id == Auth::id()) || ($hasActionPermission && $log->user_id != Auth::id())))
                    <button type="button" onclick="openEditModal('{{ $log->id }}')" class="text-blue-600 hover:text-blue-900 transition-colors" title="Edit">
                        <i data-lucide="pencil" class="w-4 h-4"></i>
                    </button>
                    @else
                    <button type="button" disabled class="text-gray-400 cursor-not-allowed" title="Tidak dapat diedit">
                        <i data-lucide="pencil" class="w-4 h-4"></i>
                    </button>
                    @endif
                </div>
            </div>
            <div class="bg-white rounded-lg p-2">
                <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $log->aktivitas }}</p>
            </div>
            @if($log->status != 'menunggu' && $log->validated_by)
            <div class="mt-2 pt-2 border-t border-gray-200">
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <i data-lucide="user-check" class="w-3 h-3"></i>
                    <span>Divalidasi oleh {{ $log->nama_validator ?? 'N/A' }} pada {{ \Carbon\Carbon::parse($log->validated_at)->format('d/m/Y H:i') }}</span>
                </div>
                @if($log->catatan_validasi)
                <div class="mt-1 text-xs text-gray-600 bg-gray-50 rounded p-1.5">
                    <strong>Catatan:</strong> {{ $log->catatan_validasi }}
                </div>
                @endif
            </div>
            @endif
        </div>
        @empty
        <div class="text-center py-8 text-sm text-gray-500">
            Tidak ada aktivitas untuk tanggal ini
        </div>
        @endforelse
    </div>
</div>

<!-- Action Buttons -->
<div class="flex items-center gap-3 mt-6 ml-6">
    @if($hasActionPermission && $status == 'menunggu')
    <form action="{{ route('log-aktivitas.bulk-approve') }}" method="POST" class="inline">
        @csrf
        <input type="hidden" name="selected_items[]" value="{{ $tanggal }}_{{ $karyawan->id }}">
        <button type="submit" class="flex items-center gap-2 px-4 py-2 text-sm bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-medium" onclick="return confirm('Apakah Anda yakin ingin menyetujui semua log aktivitas ini?');">
            <i data-lucide="check-circle" class="w-4 h-4"></i>
            Terima Semua
        </button>
    </form>
    <button type="button" onclick="openRejectAllModal()" class="flex items-center gap-2 px-4 py-2 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
        <i data-lucide="x-circle" class="w-4 h-4"></i>
        Tolak Semua
    </button>
    @endif
    <a href="{{ route('log-aktivitas.index') }}" class="px-4 py-2 text-sm bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
        Kembali
    </a>
</div>



<!-- Reject All Modal -->
@if($hasActionPermission && $status == 'menunggu')
<div id="rejectAllModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Tolak Semua Log Aktivitas</h3>
            <form action="{{ route('log-aktivitas.bulk-reject') }}" method="POST">
                @csrf
                <input type="hidden" name="selected_items[]" value="{{ $tanggal }}_{{ $karyawan->id }}">
                <div class="mb-4">
                    <label for="catatan_validasi_all" class="block text-sm font-medium text-gray-700 mb-2">Catatan Penolakan</label>
                    <textarea id="catatan_validasi_all" name="catatan_validasi" rows="4" required minlength="5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent" placeholder="Berikan alasan penolakan..."></textarea>
                    <p class="mt-1 text-xs text-gray-500">Minimal 5 karakter</p>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                        Tolak Semua
                    </button>
                    <button type="button" onclick="closeRejectAllModal()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Bulk Reject Modal (untuk validasi massal) -->
@if($hasActionPermission && $status == 'menunggu')
<div id="bulkRejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Tolak Log Aktivitas (Massal)</h3>
            <form id="bulkRejectForm" method="POST" action="{{ route('log-aktivitas.bulk-reject-ids') }}">
                @csrf
                <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                <input type="hidden" name="user_id" value="{{ $karyawan->id }}">
                <div class="mb-4">
                    <label for="catatan_validasi" class="block text-sm font-medium text-gray-700 mb-2">Catatan Penolakan</label>
                    <textarea id="catatan_validasi" name="catatan_validasi" rows="4" required minlength="5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent" placeholder="Berikan alasan penolakan..."></textarea>
                    <p class="mt-1 text-xs text-gray-500">Minimal 5 karakter</p>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                        Tolak
                    </button>
                    <button type="button" onclick="closeBulkRejectModal()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Edit Modal -->
@if(Auth::user()->role === 'karyawan' || $hasActionPermission)
<div id="editModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Edit Log Aktivitas</h3>
                <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="redirect_to_show" value="1">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <!-- Waktu Awal Field -->
                    <div>
                        <label for="edit_waktu_awal" class="block text-sm font-medium text-gray-700 mb-2">
                            <div class="flex items-center">
                                <i data-lucide="clock" class="w-4 h-4 text-teal-500 mr-2"></i>
                                Waktu Awal (WIB)
                            </div>
                        </label>
                        <input
                            type="text"
                            id="edit_waktu_awal"
                            name="waktu_awal"
                            class="edit-waktu-awal w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition duration-150 ease-in-out bg-gray-50 time-picker cursor-pointer"
                            placeholder="00:00"
                            readonly
                            required />
                    </div>

                    <!-- Waktu Akhir Field -->
                    <div>
                        <label for="edit_waktu_akhir" class="block text-sm font-medium text-gray-700 mb-2">
                            <div class="flex items-center">
                                <i data-lucide="clock" class="w-4 h-4 text-teal-500 mr-2"></i>
                                Waktu Akhir (WIB)
                            </div>
                        </label>
                        <input
                            type="text"
                            id="edit_waktu_akhir"
                            name="waktu_akhir"
                            class="edit-waktu-akhir w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition duration-150 ease-in-out bg-gray-50 time-picker cursor-pointer"
                            placeholder="00:00"
                            readonly
                            required />
                    </div>
                </div>

                <!-- Aktivitas Textarea -->
                <div class="mb-4">
                    <label for="edit_aktivitas" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi Aktivitas
                    </label>
                    <textarea
                        id="edit_aktivitas"
                        name="aktivitas"
                        rows="6"
                        required
                        minlength="10"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition duration-150 ease-in-out bg-white"
                        placeholder="Jelaskan aktivitas, tugas, atau kegiatan kerja yang Anda lakukan ..."></textarea>
                    <!-- <p class="mt-1 text-xs text-gray-500">Minimal 10 karakter</p> -->
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="flex-1 px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-medium">
                        <i data-lucide="save" class="w-4 h-4 inline mr-1"></i>
                        Simpan Perubahan
                    </button>
                    <button type="button" onclick="closeEditModal()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
</div>

<script>
    $(document).ready(function() {
        lucide.createIcons();

        const $selectAll = $('#selectAllLogs');
        const $logCheckboxes = $('.log-checkbox');
        const $bulkActionBar = $('#bulkActionBar');
        const $selectedCount = $('#selectedCount');
        const $bulkApproveBtn = $('#bulkApproveBtn');
        const $bulkRejectBtn = $('#bulkRejectBtn');
        const $cancelBulkActionBtn = $('#cancelBulkAction');

        // == SELECT ALL ==
        $selectAll.on('change', function() {
            $logCheckboxes.prop('checked', $(this).is(':checked'));
            updateBulkActionBar();
        });

        // == ITEM CHECKBOX ==
        $logCheckboxes.on('change', function() {
            updateBulkActionBar();
            $selectAll.prop(
                'checked',
                $logCheckboxes.length === $logCheckboxes.filter(':checked').length
            );
        });

        function updateBulkActionBar() {
            const count = $logCheckboxes.filter(':checked').length;

            if (count > 0) {
                $bulkActionBar.removeClass('hidden');
                $selectedCount.text(count);
            } else {
                $bulkActionBar.addClass('hidden');
            }
        }

        // == BULK APPROVE ==
        $bulkApproveBtn.on('click', function() {
            const selected = $logCheckboxes.filter(':checked');

            if (selected.length === 0) {
                alert('Pilih minimal 1 aktivitas untuk divalidasi');
                return;
            }

            if (confirm('Apakah Anda yakin ingin menyetujui ' + selected.length + ' aktivitas yang dipilih?')) {
                const $form = $('<form>', {
                    method: 'POST',
                    action: '{{ route("log-aktivitas.bulk-approve-ids") }}'
                });

                $form.append(csrfInput());
                $form.append(hiddenInput('tanggal', '{{ $tanggal }}'));
                $form.append(hiddenInput('user_id', '{{ $karyawan->id }}'));

                selected.each(function() {
                    $form.append(hiddenInput('log_ids[]', $(this).val()));
                });

                $('body').append($form);
                $form.submit();
            }
        });

        // == BULK REJECT ==
        $bulkRejectBtn.on('click', function() {
            const selected = $logCheckboxes.filter(':checked');

            if (selected.length === 0) {
                alert('Pilih minimal 1 aktivitas untuk ditolak');
                return;
            }

            const $form = $('#bulkRejectForm');
            $form.find('input[name="log_ids[]"]').remove();

            selected.each(function() {
                $form.append(hiddenInput('log_ids[]', $(this).val()));
            });

            openModal('#bulkRejectModal');
        });

        // == CANCEL ==
        $cancelBulkActionBtn.on('click', function() {
            $logCheckboxes.prop('checked', false);
            $selectAll.prop('checked', false);
            updateBulkActionBar();
        });

        // == HELPER: input hidden ==
        function csrfInput() {
            return $('<input>', {
                type: 'hidden',
                name: '_token',
                value: '{{ csrf_token() }}'
            });
        }

        function hiddenInput(name, value) {
            return $('<input>', {
                type: 'hidden',
                name: name,
                value: value
            });
        }

        // == MODAL HELPERS ==
        function openModal(id) {
            $(id).removeClass('hidden');
        }

        window.closeBulkRejectModal = function() {
            $('#bulkRejectModal').addClass('hidden');
        };

        window.openRejectAllModal = function() {
            $('#rejectAllModal').removeClass('hidden');
        };

        window.closeRejectAllModal = function() {
            $('#rejectAllModal').addClass('hidden');
        };

        // == OPEN EDIT MODAL ==
        window.openEditModal = function(logId) {
            const $el = $(`[data-log-id="${logId}"]`);

            if ($el.length === 0) {
                alert('Data log tidak ditemukan');
                return;
            }

            const updateUrl = '{{ route("log-aktivitas.update", ":id") }}'.replace(':id', logId);

            $('#editForm').attr('action', updateUrl);
            $('#edit_waktu_awal').val($el.data('waktuAwal'));
            $('#edit_waktu_akhir').val($el.data('waktuAkhir'));
            $('#edit_aktivitas').val($el.data('aktivitas'));

            initEditTimePickers();

            $('#editModal').removeClass('hidden');
        };

        // == CLOSE EDIT MODAL ==
        window.closeEditModal = function() {
            $('#editModal').addClass('hidden');

            destroyTimepicker('#edit_waktu_awal');
            destroyTimepicker('#edit_waktu_akhir');
        };

        // == FORM SUBMIT VALIDATION ==
        $('#editForm').on('submit', function(e) {
            const waktuAwal = $('#edit_waktu_awal').val();
            const waktuAkhir = $('#edit_waktu_akhir').val();

            if (waktuAwal && waktuAkhir && waktuAkhir <= waktuAwal) {
                e.preventDefault();
                alert('Waktu akhir harus setelah waktu awal');
                return false;
            }
        });

        function destroyTimepicker(selector) {
            const fp = $(selector)[0]?._flatpickr;
            if (fp) fp.destroy();
        }

        function initEditTimePickers() {
            if (typeof flatpickr === 'undefined') return;

            initTimepicker('#edit_waktu_awal');
            initTimepicker('#edit_waktu_akhir');
        }

        function initTimepicker(selector, onChange = null) {
            destroyTimepicker(selector);

            flatpickr($(selector)[0], {
                enableTime: true,
                noCalendar: true,
                dateFormat: 'H:i',
                time_24hr: true,
                minuteIncrement: 15,
                disableMobile: true,
                allowInput: false,
                clickOpens: true,
                appendTo: document.body,
                onOpen: function(selectedDates, dateStr, instance) {
                    positionTimepicker(instance, $(selector)[0]);
                },
                onReady: function(selectedDates, dateStr, instance) {
                    $(instance.calendarContainer).css({
                        position: 'fixed',
                        zIndex: 999999
                    });
                },
                onChange: onChange
            });
        }

        function positionTimepicker(instance, input) {
            setTimeout(function() {
                const calendar = instance.calendarContainer;
                if (!calendar) return;

                if (calendar.parentElement !== document.body) {
                    document.body.appendChild(calendar);
                }

                const rect = input.getBoundingClientRect();
                calendar.style.position = 'fixed';
                calendar.style.left = rect.left + 'px';
                calendar.style.top = rect.bottom + 5 + 'px';
                calendar.style.zIndex = 999999;

                const cRect = calendar.getBoundingClientRect();
                if (cRect.right > window.innerWidth) {
                    calendar.style.left = (window.innerWidth - cRect.width - 10) + 'px';
                }
                if (cRect.bottom > window.innerHeight) {
                    calendar.style.top = (rect.top - cRect.height - 5) + 'px';
                }
            }, 50);
        }
    });
</script>

@endsection
