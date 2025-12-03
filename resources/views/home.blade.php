@extends('layouts.main')

@section('page-content')
<div class="p-3 sm:p-4 md:p-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 mb-4">
        <div class="welcome-section">
            <h2 class="text-xl sm:text-2xl font-semibold text-gray-900">
                {{ greetings() }}, {{ Auth::user()->name }}
            </h2>
        </div>
    </div>

    <!-- Cards Grid - Responsive -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-3 sm:gap-4 mb-4 sm:mb-6">

        <!-- <div class="flex flex-col justify-start bg-gradient-to-br from-teal-50/30 via-teal-50/20 to-white border-t-4 border-teal-400 border-r border-b border-l rounded-lg shadow-sm p-3.5 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-lg font-semibold text-gray-600">Total Karyawan</h3>
            </div>

            <div class="space-y-2">
                <div class="flex flex-col gap-3 justify-between items-start">
                    <div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-10">
                            </div>

                        </div>
                    </div>
                    <span class="text-sm text-gray-500">Karyawan Fulltime</span>
                </div>
            </div>
        </div> -->

        <!-- Overview Karyawan Izin / Cuti Card -->
        <!-- <div class="flex flex-col justify-between bg-gradient-to-br from-teal-50/30 via-teal-50/20 to-white border-t-4 border-teal-400 border-r border-b border-l rounded-lg shadow-sm p-3.5 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-center mb-1">
                <h3 class="text-base font-semibold text-gray-600">Overview Karyawan Izin / Cuti</h3>
            </div>
            <div>
                <div class="flex items-center gap-1.5 mb-1.5">
                    <span class="flex items-center bg-green-100 text-green-600 text-sm font-bold px-1.5 py-0.5 rounded">
                    </span>
                    <span class="text-sm text-gray-500">dibandingkan bulan lalu</span>
                </div>
            </div>
        </div> -->

        <!-- Overview Status Karyawan Card -->
        <!-- <div class="flex flex-col justify-between bg-gradient-to-br from-teal-50/30 via-teal-50/20 to-white border-t-4 border-teal-400 border-r border-b border-l rounded-lg shadow-sm p-3.5 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-center mb-1">
                <h3 class="text-base font-semibold text-gray-600">Overview Status Karyawan</h3>
            </div>

            <div>
                <div class="text-2xl font-bold text-gray-900 mb-1">90</div>
                <div class="flex items-center gap-1.5 mb-1.5">
                    <span class="bg-green-100 text-green-600 text-sm font-semibold px-1.5 py-0.5 rounded">
                        ↑ 20%
                    </span>
                    <span class="text-sm text-gray-500">dibandingkan bulan lalu</span>
                </div>

                <div class="flex gap-2 flex-wrap text-sm">
                    <div class="flex items-center gap-1">
                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                        <span class="text-gray-600">Cuti Sakit</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="w-2 h-2 bg-purple-500 rounded-full"></span>
                        <span class="text-gray-600">Izin</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="w-2 h-2 bg-pink-500 rounded-full"></span>
                        <span class="text-gray-600">On time</span>
                    </div>
                </div>
            </div>
        </div> -->

        <!-- Karyawan Hadir Hari ini Card -->
        <!-- <div class="flex flex-col justify-between bg-gradient-to-br from-teal-50/30 via-teal-50/20 to-white border-t-4 border-teal-400 border-r border-b border-l rounded-lg shadow-sm p-3.5 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-base font-semibold text-gray-600">Karyawan Hadir Hari ini</h3>
                <button class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="ellipsis-vertical" class="h-4"></i>
                </button>
            </div>

            <div class="text-2xl font-bold text-gray-900 mb-1">90%</div>
            <div>
                <div class="flex items-center gap-1.5 mb-1.5">
                    <span class="bg-green-100 text-green-600 text-xs font-semibold px-1.5 py-0.5 rounded">
                        ↑ 20%
                    </span>
                    <span class="text-xs text-gray-500">dibandingkan hari lalu</span>
                </div>
            </div>
        </div> -->
        <div></div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-4 sm:mb-6">
        <!-- Status Log Aktivitas (Donut Chart) -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Status Log Aktivitas Karyawan</h3>
            <p class="text-xs text-gray-500 mb-3 sm:mb-4">Distribusi status log aktivitas seluruh karyawan</p>
            <div class="flex flex-col sm:flex-row gap-2 justify-end mb-3 sm:mb-0">
                <input type="date" name="status_log_start" id="status_log_start" class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full sm:w-auto">
                <input type="date" name="status_log_end" id="status_log_end" class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full sm:w-auto">
            </div>
            <div id="statusLogChart" class="mt-3"></div>
        </div>

        <!-- Trend Log Aktivitas 7 Hari Terakhir (Line Chart) -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Trend Log Aktivitas (7 Hari Terakhir)</h3>
            <p class="text-xs text-gray-500 mb-3 sm:mb-4">Perkembangan jumlah log aktivitas seluruh karyawan</p>
            <div id="trendLogChart"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-4 sm:mb-6">
        <!-- Log Aktivitas per Departemen (Bar Chart) -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Log Aktivitas per Departemen</h3>
            <p class="text-xs text-gray-500 mb-3 sm:mb-4">Top 5 departemen dengan aktivitas terbanyak</p>
            <div id="logPerDepartemenChart"></div>
        </div>

        <!-- Top 5 Karyawan Paling Aktif (Horizontal Bar Chart) -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Top 5 Karyawan Paling Aktif (Bulan Ini)</h3>
            <p class="text-xs text-gray-500 mb-3 sm:mb-4">Karyawan dengan jumlah log aktivitas terbanyak</p>
            <div id="topKaryawanChart"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-4 sm:mb-6">
        <!-- Distribusi Aktivitas per Jam  (Timeline Chart) -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Distribusi Aktivitas per Jam</h3>
            <p class="text-xs text-gray-500 mb-3 sm:mb-4">Pola waktu aktivitas seluruh karyawan dalam jam kerja</p>
            <div class="flex flex-col sm:flex-row gap-2 justify-end mb-3 sm:mb-4">
                <input type="date" name="activity_perjam_date" id="activity_perjam_date" class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full sm:w-auto focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                <button type="button" id="resetBtnActivityPerjam" class="px-3 py-2 bg-gray-500 border border-gray-500 text-white hover:bg-gray-600 focus:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 rounded-md text-sm font-medium transition-colors w-full sm:w-auto">Reset</button>
            </div>
            <div id="activityPerHourChart" class="w-full overflow-x-auto"></div>
        </div>

        <!-- Distribusi Aktivitas per Departemen -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-2">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900">Distribusi Aktivitas per Departemen</h3>

                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 whitespace-nowrap">
                    <i data-lucide="info" class="w-3 h-3"></i>
                    <span class="hidden sm:inline">Klik batang untuk detail</span>
                    <span class="sm:hidden">Klik untuk detail</span>
                </span>
            </div>
            <p class="text-xs text-gray-500 mb-3 sm:mb-4">Pola waktu aktivitas seluruh karyawan dalam jam kerja</p>
            <div class="flex flex-col sm:flex-row gap-2 justify-end mb-3 sm:mb-4">
                <input type="date" name="dept_activity_start" id="dept_activity_start" class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full sm:w-auto focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                <input type="date" name="dept_activity_end" id="dept_activity_end" class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full sm:w-auto focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                <button type="button" id="resstBtnDistActivity" class="px-3 py-2 bg-gray-500 border border-gray-500 text-white hover:bg-gray-600 focus:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 rounded-md text-sm font-medium transition-colors w-full sm:w-auto">Reset</button>
            </div>
            <div id="activityDepartemenChart" class="w-full overflow-x-auto"></div>
        </div>
    </div>
</div>

<script>
    // Lempar ke js/scripts/home.js
    window.statusData = @json($statusData);
    window.statusLabels = @json($statusLabels);
    window.trendCounts = @json($trendCounts);
    window.trendDates = @json($trendDates);
    window.deptCounts = @json($deptCounts);
    window.deptNames = @json($deptNames);
    window.karyawanCounts = @json($karyawanCounts);
    window.karyawanNames = @json($karyawanNames);
    window.hourLabels = @json($hourLabels);
    window.timelineSeries = @json($timelineSeries);
    window.karyawanList = @json($karyawanList);
    window.deptTotalDetail = @json($deptTotalDetail);
    window.deptNamesDetail = @json($deptNamesDetail);
    window.deptIdsDetail = @json($deptIdsDetail);
    window.routeGetLogStatus = '{{ route("log-activity.status") }}';
    window.routeGetActivityDepartemen = '{{ route("log-activity.departemen") }}';
    window.routeGetActivityPerjam = '{{ route("log-activity.perjam") }}';
</script>
<script src="{{ asset('js/scripts/home.js') }}"></script>
<script src="{{ asset('js/scripts/utils/create-chart-option.js') }}"></script>

@endsection
