@extends('layouts.main')

@section('page-content')
<div class="p-6 pb-8">
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <!-- Title -->
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Data Karyawan</h1>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">Kelola data karyawan perusahaan Anda</p>
            </div>

            <!-- Search, Filters & Export -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <!-- Search Bar -->
                <div class="relative flex-1 sm:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                    </div>
                    <input type="text"
                        id="searchInput"
                        placeholder="Search Karyawan"
                        class="block w-full pl-10 pr-3 py-2 text-xs sm:text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent bg-white">
                </div>

                <!-- Status Filter -->
                <select id="filterDepartemen" class="px-3 sm:px-4 py-2 text-xs sm:text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent bg-white cursor-pointer">
                    <option>Semua Departemen</option>
                    @foreach ($listDepartemen as $departemen )
                    <option value="{{ $departemen->nama }}">{{ $departemen->nama }}</option>
                    @endforeach

                </select>

                <!-- Role Filter -->
                <!-- <select class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent bg-white cursor-pointer">
                    <option>All Role</option>
                    <option>Administrator</option>
                    <option>Sr UIUX Designer</option>
                    <option>Lead Product Designer</option>
                    <option>Sr UX Designer</option>
                    <option>Mid UI Designer</option> -->
                </select>

                <!-- Export Button -->
                <!-- <button class="flex items-center gap-2 px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors text-sm font-medium whitespace-nowrap">
                    <i data-lucide="arrow-up" class="w-4 h-4"></i>
                    Export
                </button> -->
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <!-- Table Header -->
                <thead class="border-b border-gray-200">
                    <tr>
                        @if (in_array(Auth::user()->role, ['admin','spv','sdm','superadmin']))
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            ID
                        </th>
                        @endif

                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer hover:text-gray-900 transition-colors">
                            <div class="flex items-center gap-2">
                                Nama karyawan
                                <div class="flex flex-col">
                                    <i data-lucide="chevron-up" class="w-3 h-3 text-gray-400"></i>
                                    <i data-lucide="chevron-down" class="w-3 h-3 text-gray-400 -mt-1"></i>
                                </div>
                            </div>
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Unit
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Status Sistem
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Departemen
                        </th>
                        @if(Auth::user()->role != 'karyawan')
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Aksi
                        </th>
                        @endif
                    </tr>
                </thead>

                <!-- Table Body -->
                <tbody id="karyawanTableBody" class="bg-white divide-y divide-gray-100">

                    @foreach ( $listKaryawan as $karyawan )

                    <tr class="hover:bg-gray-50 transition-colors">
                        @if (in_array(Auth::user()->role, ['admin','spv','sdm','superadmin']))
                        <td class="px-4 py-2 whitespace-nowrap">
                            <span class="text-xs text-gray-600">{{ $karyawan->id }}</span>
                        </td>
                        @endif
                        <td class="px-4 py-2 whitespace-nowrap">
                            <div class="flex items-center gap-2 sm:gap-3">
                                <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-green-500 flex items-center justify-center text-white font-semibold text-xs">
                                    AT
                                </div>
                                <div>
                                    <div class="text-xs sm:text-sm font-medium text-gray-900">{{ $karyawan->nama }}</div>
                                </div>
                            </div>
                        </td>

                        <td class="px-4 py-2 whitespace-nowrap">
                            <span class="text-xs text-gray-900">{{ $karyawan->unit_nama ?? 'Belum Di tentukan'}}</span>
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap">
                            @if(isset($karyawan->is_registered) && $karyawan->is_registered)
                            <span class="inline-flex items-center px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Terdaftar
                            </span>
                            @else
                            <span class="inline-flex items-center px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Belum Terdaftar
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap">
                            <span class="text-xs text-gray-600">{{ $karyawan->departemen_nama ?? 'Belum ditentukan'}}</span>
                        </td>
                        @if(Auth::user()->role != 'karyawan')
                        <td class="px-4 py-2 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button
                                    type="button"
                                    class="open-employee-detail-btn text-gray-400 hover:text-gray-600 transition-colors"
                                    title="Detail"
                                    data-employee-id="{{ $karyawan->id }}"
                                    data-name="{{ $karyawan->nama }}"
                                    data-email="{{ $karyawan->jk }}"
                                    data-role="{{ $karyawan->bidang }}"
                                    data-department="{{ $karyawan->stts_kerja }}"
                                    data-joined-date="{{ $karyawan->mulai_kerja }}"
                                    data-initials="{{ $karyawan->inisial_nama }}"
                                    data-user-id="{{ isset($karyawan->user_id) ? $karyawan->user_id : '' }}"
                                    data-departemen-id="{{ isset($karyawan->departemen_id) ? $karyawan->departemen_id : '' }}"
                                    data-departemen-nama="{{ isset($karyawan->departemen_nama) ? $karyawan->departemen_nama : 'Belum Ditentukan' }}"
                                    data-unit-id="{{ isset($karyawan->unit_id) ? $karyawan->unit_id : '' }}"
                                    data-unit-nama="{{ isset($karyawan->unit_nama) ? $karyawan->unit_nama : 'Belum Ditentukan' }}"
                                    data-user-role="{{ isset($karyawan->user_role) ? $karyawan->user_role : '' }}"
                                    data-password-changed="{{ (isset($karyawan->password_changed) && $karyawan->password_changed) ? 'true' : 'false' }}"
                                    data-is-registered="{{ (isset($karyawan->is_registered) && $karyawan->is_registered) ? 'true' : 'false' }}">
                                    <i data-lucide="eye" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                                </button>

                                @if(isset($karyawan->is_registered) && $karyawan->is_registered && isset($karyawan->user_id))
                                <!-- Dropdown Menu -->
                                <div class="relative inline-block">
                                    <button
                                        type="button"
                                        class="menu-toggle-btn text-gray-400 hover:text-gray-600 transition-colors"
                                        title="Menu"
                                        data-user-id="{{ $karyawan->user_id }}"
                                        data-user-name="{{ $karyawan->nama }}">
                                        <i data-lucide="more-vertical" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                                    </button>
                                    <!-- Dropdown Content -->
                                    <div class="menu-dropdown hidden absolute right-0 mt-2 w-40 sm:w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                        <div class="py-1">
                                            <button
                                                type="button"
                                                class="change-password-btn w-full text-left px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm text-gray-700 hover:bg-gray-100 transition-colors flex items-center gap-2"
                                                data-user-id="{{ $karyawan->user_id }}"
                                                data-user-name="{{ $karyawan->nama }}">
                                                <i data-lucide="key" class="w-4 h-4"></i>
                                                <span>Ubah Password</span>
                                            </button>
                                            <button
                                                type="button"
                                                class="edit-role-btn w-full text-left px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm text-gray-700 hover:bg-gray-100 transition-colors flex items-center gap-2"
                                                data-user-id="{{ $karyawan->user_id }}"
                                                data-user-name="{{ $karyawan->nama }}"
                                                data-user-role="{{ isset($karyawan->user_role) ? $karyawan->user_role : '' }}">
                                                <i data-lucide="shield" class="w-4 h-4"></i>
                                                <span>Edit Role</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- @if(in_array(Auth::user()->role, ['superadmin', 'admin', 'sdm']) && (!isset($karyawan->is_registered) || !$karyawan->is_registered))
                                <form action="{{ route('karyawan.activate', $karyawan->id) }}" method="POST" class="inline ml-2" onsubmit="return confirm('Apakah Anda yakin ingin menambahakn karyawan ke sistem ini {{ $karyawan->nama }}? Password default: 12345');">
                                    @csrf
                                    <button type="submit" class="text-teal-600 hover:text-teal-900 transition-colors" title="Aktifkan Karyawan">
                                        <i data-lucide="user-plus" class="w-5 h-5"></i>
                                    </button>
                                </form>
                                @endif -->
                            </div>
                        </td>
                        @endif
                    </tr>
                    @endforeach


                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-end m-3">
            {{ $listKaryawan->links() }}
        </div>
    </div>
</div>

<!-- Employee Detail Slideout Modal -->
<div id="employeeDetailModal" class="fixed inset-0 z-50 overflow-hidden hidden">

    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="closeEmployeeDetail()"></div>

    <!-- Slideout Panel -->
    <div class="fixed right-0 top-0 bottom-0 w-full sm:w-4/5 md:w-1/2 lg:w-1/3 max-w-2xl bg-white shadow-2xl transform transition-transform duration-300 ease-in-out translate-x-full" id="slideoutPanel">
        <div class="flex flex-col h-full">
            <!-- Header dengan Gradient Background -->
            <div class="relative bg-gradient-to-br from-teal-500 via-teal-600 to-teal-700 px-4 md:px-6 pt-6 md:pt-8 pb-12 md:pb-16">
                <!-- Close Button -->
                <button onclick="closeEmployeeDetail()" class="absolute top-3 right-3 md:top-4 md:right-4 text-white hover:text-teal-100 transition-colors z-10">
                    <i data-lucide="x" class="w-5 h-5 md:w-6 md:h-6"></i>
                </button>

                <!-- Avatar Large -->
                <div class="flex justify-center mb-3 md:mb-4">
                    <div class="relative">
                        <div class="w-16 h-16 md:w-24 md:h-24 rounded-full bg-white/20 backdrop-blur-sm border-2 md:border-4 border-white/30 flex items-center justify-center text-white font-bold text-xl md:text-3xl" id="employeeAvatar">
                            AT
                        </div>
                        <div class="absolute bottom-0 right-0 w-6 h-6 md:w-8 md:h-8 bg-green-500 rounded-full border-2 md:border-4 border-white flex items-center justify-center">
                            <i data-lucide="check" class="w-3 h-3 md:w-4 md:h-4 text-white"></i>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <!-- <div class="flex gap-3 justify-center mt-4">
                    <button class="px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-lg hover:bg-white/30 transition-colors text-sm font-medium">
                        Archive
                    </button>
                    <button class="px-4 py-2 bg-white text-teal-600 rounded-lg hover:bg-teal-50 transition-colors text-sm font-medium">
                        View Profile
                    </button>
                </div> -->
            </div>

            <!-- Content Area -->
            <div class="flex-1 overflow-y-auto px-4 md:px-6 py-4 md:py-6 -mt-6 md:-mt-8">
                <!-- Employee Summary Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 md:p-6 mb-4 md:mb-6 relative z-10">
                    <h2 class="text-base sm:text-lg md:text-xl font-bold text-gray-900 mb-1 break-words" id="employeeName"></h2>
                    <div class="flex items-center gap-2 mb-3 md:mb-4">
                        <span class="inline-flex items-center gap-1.5 px-2 md:px-2.5 py-0.5 md:py-1 rounded-full text-xs" id="employeeStatus">
                            <span class="w-1.5 h-1.5 rounded-full" id="statusDot"></span>
                            <span id="statusText"></span>
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-gray-600 mb-4 md:mb-6 break-words" id="employeeEmail"></p>

                    <!-- Key Metrics -->
                    <div class="grid grid-cols-3 gap-2 md:gap-4 pt-3 md:pt-4 border-t border-gray-200">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">User ID</p>
                            <p class="text-xs font-semibold text-gray-900 break-words" id="employeeId">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Departemen</p>
                            <p class="text-xs font-semibold text-gray-900 break-words" id="employeeDepartment">Belum Ditentukan</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Unit</p>
                            <p class="text-xs font-semibold text-gray-900 break-words" id="employeeUnit">Belum Ditentukan</p>
                        </div>
                    </div>
                </div>

                <!-- Edit Form -->
                <form id="employeeForm" action="" method="POST" class="space-y-4 md:space-y-6">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="userId" name="user_id" value="">
                    <input type="hidden" id="employeeIdForForm" name="employee_id" value="">
                    <!-- Name Field -->
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5 md:mb-2">Nama</label>
                        <input type="text"
                            id="employeeNameInput"
                            name="name"
                            class="w-full px-3 md:px-4 py-2 text-xs sm:text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent bg-white"
                            placeholder="Nama lengkap"
                            required>
                    </div>

                    <!-- Departemen Field -->
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5 md:mb-2">Departemen</label>
                        <select id="employeeDepartemenSelect"
                            name="departemen_id"
                            class="w-full px-3 md:px-4 py-2 text-xs sm:text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent bg-white cursor-pointer">
                            <option value="">Pilih Departemen</option>
                            @foreach($listDepartemen as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Unit Field -->
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5 md:mb-2">Unit</label>
                        <select id="employeeUnitSelect"
                            name="unit_id"
                            class="w-full px-3 md:px-4 py-2 text-xs sm:text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent bg-white cursor-pointer">
                            <option value="">Pilih Unit</option>
                            @foreach($listUnit as $unit)
                            <option value="{{ $unit->id }}" data-departemen="{{ $unit->departemen_id }}">{{ $unit->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Password Field -->


                    <!-- Password Confirmation -->
                    <!-- <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5 md:mb-2">Konfirmasi Password</label>
                        <div class="relative">
                            <input type="password"
                                id="employeePasswordConfirmation"
                                name="password_confirmation"
                                class="w-full px-3 md:px-4 py-2 pr-10 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent bg-white"
                                placeholder="Konfirmasi password baru">
                            <button type="button"
                                onclick="togglePasswordVisibility('employeePasswordConfirmation', 'passwordToggleIcon2')"
                                class="absolute inset-y-0 right-0 pr-2 md:pr-3 flex items-center text-gray-400 hover:text-gray-600 touch-manipulation">
                                <i data-lucide="eye" id="passwordToggleIcon2" class="w-4 h-4 md:w-5 md:h-5"></i>
                            </button>
                        </div>
                    </div> -->

                    <!-- Password Changed Status -->
                    <div>
                        <label class="flex items-center gap-2">
                            <input type="checkbox"
                                id="employeePasswordChanged"
                                name="password_changed"
                                class="w-4 h-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500"
                                disabled>
                            <span class="text-xs md:text-sm font-medium text-gray-700">Password sudah diubah</span>
                        </label>
                        <p class="text-xs text-gray-500 mt-1" id="passwordChangedHint">Status password dari database</p>
                    </div>

                    <!-- Footer -->
                    <div class="border-t border-gray-200 px-4 md:px-6 py-3 md:py-4 bg-gray-50 flex flex-col sm:flex-row justify-end gap-2 md:gap-3">
                        <button onclick="closeEmployeeDetail()" class="w-full sm:w-auto px-3 sm:px-4 md:px-6 py-1.5 sm:py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-xs sm:text-sm font-medium touch-manipulation">
                            Cancel
                        </button>
                        <button type="submit" class="w-full sm:w-auto px-3 sm:px-4 md:px-6 py-1.5 sm:py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors text-xs sm:text-sm font-medium touch-manipulation">
                            Save changes
                        </button>
                    </div>
                </form>
            </div>


        </div>
    </div>
</div>

<!-- Change Role Modal -->
<div id="changeRoleModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeChangeRoleModal()"></div>

        <!-- Modal Panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <!-- Header -->
            <div class="bg-gradient-to-r from-teal-500 to-teal-600 px-4 sm:px-6 py-3 sm:py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-base sm:text-lg font-semibold text-white" id="changeRoleModalTitle">Ubah Role</h3>
                    <button type="button" onclick="closeChangeRoleModal()" class="text-white hover:text-teal-100 transition-colors">
                        <i data-lucide="x" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <form id="changeRoleForm" action="" method="POST" class="px-4 sm:px-6 py-4">
                @csrf
                @method('PUT')
                <input type="hidden" id="changeRoleUserId" name="user_id" value="">

                <div class="mb-4">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Role Lama</label>
                    <div class="relative">
                        <input type="text"
                            id="oldRole"
                            class="w-full px-3 sm:px-4 py-2 pr-10 text-xs sm:text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent bg-gray-100"
                            disabled>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Role Baru</label>
                    <div class="relative">
                        <select id="newRole" name="role" class="w-full px-3 sm:px-4 py-2 text-xs sm:text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent bg-white cursor-pointer" required>
                            <option value="">Pilih Role</option>
                            <option value="admin">Admin</option>
                            <option value="sdm">SDM</option>
                            <option value="manager">Manager</option>
                            <option value="spv">Supervisor</option>
                            <option value="karyawan">Karyawan</option>
                        </select>
                    </div>
                </div>
            </form>

            <!-- Footer -->
            <div class="bg-gray-50 px-4 sm:px-6 py-3 sm:py-4 flex flex-col sm:flex-row justify-end gap-2 sm:gap-3">
                <button type="button" onclick="closeChangeRoleModal()" class="w-full sm:w-auto px-3 sm:px-4 py-1.5 sm:py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-xs sm:text-sm font-medium">
                    Batal
                </button>
                <button type="submit" form="changeRoleForm" class="w-full sm:w-auto px-3 sm:px-4 py-1.5 sm:py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors text-xs sm:text-sm font-medium">
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/scripts/karyawan/karyawan.index.js') }}"></script>
@endsection
