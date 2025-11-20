@extends('layouts.main')

@section('page-content')
<div class="p-6">

    <!-- Header Section -->
    <div class="flex justify-between items-center mb-4">
        <div class="welcome-section">
            <h2 class="text-2xl font-semibold text-gray-900">
                Good Morning, {{ Auth::user()->name }}
            </h2>
        </div>
    </div>

    <!-- Cards Grid - Responsive -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-4 mb-4">

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
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Status Log Aktivitas (Donut Chart) -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Log Aktivitas Karyawan</h3>
            <p class="text-xs text-gray-500 mb-4">Distribusi status log aktivitas seluruh karyawan</p>
            <div id="statusLogChart"></div>
        </div>

        <!-- Trend Log Aktivitas 7 Hari Terakhir (Line Chart) -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Trend Log Aktivitas (7 Hari Terakhir)</h3>
            <p class="text-xs text-gray-500 mb-4">Perkembangan jumlah log aktivitas seluruh karyawan</p>
            <div id="trendLogChart"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Log Aktivitas per Departemen (Bar Chart) -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Log Aktivitas per Departemen</h3>
            <p class="text-xs text-gray-500 mb-4">Top 5 departemen dengan aktivitas terbanyak</p>
            <div id="logPerDepartemenChart"></div>
        </div>

        <!-- Top 5 Karyawan Paling Aktif (Horizontal Bar Chart) -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Top 5 Karyawan Paling Aktif (Bulan Ini)</h3>
            <p class="text-xs text-gray-500 mb-4">Karyawan dengan jumlah log aktivitas terbanyak</p>
            <div id="topKaryawanChart"></div>
        </div>
    </div>

    <!-- Distribusi Aktivitas per Jam (Bar Chart) -->
    <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Distribusi Aktivitas per Jam (8:00 - 17:00)</h3>
        <p class="text-xs text-gray-500 mb-4">Pola waktu aktivitas seluruh karyawan dalam jam kerja</p>
        <div id="activityPerHourChart"></div>
    </div>
</div>

<!-- ApexCharts CDN -->

<script>
    $(document).ready(function() {
        lucide.createIcons();

        // 1. Status Log Aktivitas (Donut Chart)
        var statusLogOptions = {
            series: @json($statusData),
            chart: {
                type: 'donut',
                height: 350
            },
            labels: @json($statusLabels),
            colors: ['#fbbf24', '#10b981', '#ef4444'],
            legend: {
                position: 'bottom'
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%'
                    }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return val.toFixed(1) + "%"
                }
            }
        };
        var statusLogChart = new ApexCharts(document.querySelector("#statusLogChart"), statusLogOptions);
        statusLogChart.render();

        // 2. Trend Log Aktivitas 7 Hari Terakhir (Line Chart)
        var trendLogOptions = {
            series: [{
                name: 'Jumlah Log',
                data: @json($trendCounts)
            }],
            chart: {
                type: 'line',
                height: 350,
                toolbar: {
                    show: false
                }
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            xaxis: {
                categories: @json($trendDates)
            },
            colors: ['#14b8a6'],
            markers: {
                size: 5,
                hover: {
                    size: 7
                }
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + " log"
                    }
                }
            }
        };
        var trendLogChart = new ApexCharts(document.querySelector("#trendLogChart"), trendLogOptions);
        trendLogChart.render();

        // 3. Log Aktivitas per Departemen (Bar Chart)
        var logPerDepartemenOptions = {
            series: [{
                name: 'Jumlah Log',
                data: @json($deptCounts)
            }],
            chart: {
                type: 'bar',
                height: 350
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: false
                }
            },
            dataLabels: {
                enabled: true
            },
            xaxis: {
                categories: @json($deptNames)
            },
            colors: ['#14b8a6']
        };
        var logPerDepartemenChart = new ApexCharts(document.querySelector("#logPerDepartemenChart"), logPerDepartemenOptions);
        logPerDepartemenChart.render();

        // 3. Top 5 Karyawan Paling Aktif (Horizontal Bar Chart)
        var topKaryawanOptions = {
            series: [{
                name: 'Jumlah Log',
                data: @json($karyawanCounts)
            }],
            chart: {
                type: 'bar',
                height: 350
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: true
                }
            },
            dataLabels: {
                enabled: true
            },
            xaxis: {
                categories: @json($karyawanNames)
            },
            colors: ['#14b8a6']
        };
        var topKaryawanChart = new ApexCharts(document.querySelector("#topKaryawanChart"), topKaryawanOptions);
        topKaryawanChart.render();

        // 4. Distribusi Aktivitas per Jam (Bar Chart)
        var activityPerHourOptions = {
            series: [{
                name: 'Jumlah Aktivitas',
                data: @json($hourCounts)
            }],
            chart: {
                type: 'bar',
                height: 350
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: false
                }
            },
            dataLabels: {
                enabled: true
            },
            xaxis: {
                categories: @json($hourLabels)
            },
            colors: ['#14b8a6']
        };
        var activityPerHourChart = new ApexCharts(document.querySelector("#activityPerHourChart"), activityPerHourOptions);
        activityPerHourChart.render();
    });
</script>

@endsection
