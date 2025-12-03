$(document).ready(function () {
    // 1. Status Log Aktivitas Karyawan
    let statusLogChart = null;
    let activityDepartemenChart = null;

    function initializeStatusLogChart() {
        const statusLogOptions = createPieChart(window.statusData, window.statusLabels);
        statusLogChart = new ApexCharts(document.querySelector('#statusLogChart'), statusLogOptions);
        statusLogChart.render();
    }

    initializeStatusLogChart();

    // 2. Trend Log Aktivitas 7 Hari Terakhir (Line Chart)
    var series = [
        {
            name: 'Jumlah Log',
            data: window.trendCounts,
        },
    ];

    var xaxis = {
        categories: window.trendDates,
    };

    var trendLogOptions = createLineChart(series, xaxis);
    var trendLogChart = new ApexCharts(document.querySelector('#trendLogChart'), trendLogOptions);
    trendLogChart.render();

    // 3. Log Aktivitas per Departemen (Bar Chart)
    var series = [
        {
            name: 'Jumlah Log',
            data: window.deptCounts,
        },
    ];
    var xaxis = {
        categories: window.deptNames,
    };

    var logPerDepartemenOptions = createBarOption(series, xaxis);
    var logPerDepartemenChart = new ApexCharts(document.querySelector('#logPerDepartemenChart'), logPerDepartemenOptions);
    logPerDepartemenChart.render();

    // 3. Top 5 Karyawan Paling Aktif (Horizontal Bar Chart)
    var topKeryawanSeries = [
        {
            name: 'Jumlah Log',
            data: window.karyawanCounts,
        },
    ];
    var topKeryawanXaxis = {
        categories: window.karyawanNames,
    };
    var topKaryawanOptions = createBarOption(topKeryawanSeries, topKeryawanXaxis, { isHorizontalChart: true });
    var topKaryawanChart = new ApexCharts(document.querySelector('#topKaryawanChart'), topKaryawanOptions);
    topKaryawanChart.render();

    // 4. Distribusi Aktivitas per Jam (Gantt/Timeline Chart - 1 bar = 1 karyawan)
    const karyawanNames = window.karyawanList ? window.karyawanList.map((k) => k.name) : [];

    const colorPalette = ['#14b8a6', '#3b82f6', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981', '#ef4444', '#06b6d4', '#f97316', '#6366f1', '#84cc16', '#eab308', '#22c55e', '#a855f7', '#f43f5e'];

    // Assign warna ke setiap data point
    const timelineDataWithColors = (window.timelineSeries || []).map((item, index) => {
        return {
            ...item,
            fillColor: colorPalette[index % colorPalette.length],
        };
    });

    const timelineSeries = [
        {
            name: 'Aktivitas',
            data: timelineDataWithColors,
        },
    ];

    var activityPerHourOptions = createGanttChart(timelineSeries, karyawanNames, {
        chartHeight: Math.max(400, karyawanNames.length * 40),
    });
    var activityPerHourChart = new ApexCharts(document.querySelector('#activityPerHourChart'), activityPerHourOptions);
    activityPerHourChart.render();

    // 5. Distribusi Aktivitas per Departemen Detail (Bar Chart)
    const activityPerDepartemenSeries = [
        {
            name: 'Jumlah Aktivitas',
            data: window.deptTotalDetail,
        },
    ];

    const activityPerDepartemenXaxis = {
        categories: window.deptNamesDetail,
    };

    var activityPerDepartemenOptions = createBarOption(activityPerDepartemenSeries, activityPerDepartemenXaxis, {
        isHorizontalChart: true,
        onclick: function (event, chartContext, config) {
            const dataPointIndex = config.dataPointIndex !== undefined ? config.dataPointIndex : config.seriesIndex;
            if (window.deptIdsDetail && window.deptIdsDetail.length > 0 && dataPointIndex !== undefined && dataPointIndex !== null) {
                const departemenId = window.deptIdsDetail[dataPointIndex];
                const pathname = window.location.pathname.split('/')[1];

                if (departemenId) {
                    const baseUrl = window.location.origin;
                    window.location.href = `${baseUrl}/${pathname}/log-aktivitas/detail-activity-by-departemen/${departemenId}`;
                }
            }
        },
    });
    activityDepartemenChart = new ApexCharts(document.querySelector('#activityDepartemenChart'), activityPerDepartemenOptions);
    activityDepartemenChart.render();

    function updateStatusLogChart(labels, data) {
        const $chartContainer = $('#statusLogChart');

        $chartContainer.empty();

        if (statusLogChart) {
            statusLogChart.updateOptions(
                {
                    labels: labels,
                    series: data,
                },
                false,
                true,
                true
            );
        } else {
            const options = createPieChart(data, labels);
            statusLogChart = new ApexCharts(document.querySelector('#statusLogChart'), options);
            statusLogChart.render();
        }
    }

    function loadStatusLogData(startDate, endDate) {
        if (!window.routeGetLogStatus) {
            console.error('Route getLogStatus tidak ditemukan');
            return;
        }

        const $chartContainer = $('#statusLogChart');

        $.ajax({
            url: window.routeGetLogStatus,
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                Accept: 'application/json',
            },
            data: {
                status_log_start: startDate || null,
                status_log_end: endDate || null,
            },
            beforeSend: function () {
                if (!statusLogChart) {
                    $chartContainer.html('<div class="flex items-center justify-center h-64"><div class="text-gray-500">Memuat data...</div></div>');
                } else {
                    $chartContainer.css('opacity', '0.5');
                }
            },
            success: function (response) {
                $chartContainer.css('opacity', '1');

                if (response.status === 'success' && response.data && response.data.labels && response.data.data) {
                    updateStatusLogChart(response.data.labels, response.data.data);
                } else {
                    console.error('Response tidak valid:', response);
                    $chartContainer.html('<div class="flex items-center justify-center h-64"><div class="text-red-500">Error memuat data</div></div>');
                }
            },
            error: function (xhr, status, error) {
                $chartContainer.css('opacity', '1');
                console.error('Error loading status log data:', error);
                $chartContainer.html('<div class="flex items-center justify-center h-64"><div class="text-red-500">Error: ' + error + '</div></div>');
            },
        });
    }

    // Handle date filter changes
    $('#status_log_start, #status_log_end').on('change', function () {
        const statusLogStart = $('#status_log_start').val();
        const statusLogEnd = $('#status_log_end').val();

        if (statusLogStart && statusLogEnd && statusLogEnd < statusLogStart) {
            alert('Tanggal akhir harus setelah atau sama dengan tanggal awal');
            $('#status_log_end').val('');
            return;
        }

        if (statusLogStart || statusLogEnd) {
            loadStatusLogData(statusLogStart, statusLogEnd);
        } else {
            updateStatusLogChart(window.statusLabels, window.statusData);
        }
    });

    // activityDepartemenChart
    function updateActivityDepartemenChart(deptNames, deptTotals, deptIds) {
        const $chartContainer = $('#activityDepartemenChart');

        if (!deptNames || !deptTotals || deptNames.length === 0) {
            $chartContainer.html('<div class="flex items-center justify-center h-64"><div class="text-gray-500">Tidak ada data</div></div>');
            return;
        }

        const activityPerDepartemenSeries = [
            {
                name: 'Jumlah Aktivitas',
                data: deptTotals,
            },
        ];

        const activityPerDepartemenXaxis = {
            categories: deptNames,
        };

        // Calculate dynamic height based on number of departments
        const categoryCount = deptNames.length;
        const dynamicHeight = Math.max(200, Math.min(600, categoryCount * 40));

        const activityPerDepartemenOptions = createBarOption(activityPerDepartemenSeries, activityPerDepartemenXaxis, {
            chartHeight: dynamicHeight,
            isHorizontalChart: true,
            onclick: function (event, chartContext, config) {
                const dataPointIndex = config.dataPointIndex !== undefined ? config.dataPointIndex : config.seriesIndex;
                if (deptIds && deptIds.length > 0 && dataPointIndex !== undefined && dataPointIndex !== null) {
                    const departemenId = deptIds[dataPointIndex];
                    const pathname = window.location.pathname.split('/')[1];

                    if (departemenId) {
                        const baseUrl = window.location.origin;
                        window.location.href = `${baseUrl}/${pathname}/log-aktivitas/detail-activity-by-departemen/${departemenId}`;
                    }
                }
            },
        });

        if (activityDepartemenChart) {
            activityDepartemenChart.updateOptions(activityPerDepartemenOptions, false, true, true);
            activityDepartemenChart.updateSeries(activityPerDepartemenSeries);
        } else {
            $chartContainer.empty();
            activityDepartemenChart = new ApexCharts(document.querySelector('#activityDepartemenChart'), activityPerDepartemenOptions);
            activityDepartemenChart.render();
        }

        // Handle window resize for responsive chart
        if (!window.activityDepartemenChartResizeHandler) {
            window.activityDepartemenChartResizeHandler = function () {
                if (activityDepartemenChart) {
                    activityDepartemenChart.update();
                }
            };
            window.addEventListener('resize', window.activityDepartemenChartResizeHandler);
        }
    }

    function loadActivityDepartemenData(startDate, endDate) {
        if (!window.routeGetActivityDepartemen) {
            console.error('Route getActivityDepartemen tidak ditemukan');
            return;
        }

        const $chartContainer = $('#activityDepartemenChart');

        $.ajax({
            url: window.routeGetActivityDepartemen,
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                Accept: 'application/json',
            },
            data: {
                dept_activity_start: startDate || null,
                dept_activity_end: endDate || null,
            },
            beforeSend: function () {
                if (!activityDepartemenChart) {
                    $chartContainer.html('<div class="flex items-center justify-center h-64"><div class="text-gray-500">Memuat data...</div></div>');
                } else {
                    $chartContainer.css('opacity', '0.5');
                }
            },
            success: function (response) {
                $chartContainer.css('opacity', '1');

                if (response.status === 'success' && response.data) {
                    updateActivityDepartemenChart(response.data.deptNamesDetail || [], response.data.deptTotalDetail || [], response.data.deptIdsDetail || []);
                } else {
                    console.error('Response tidak valid:', response);
                    $chartContainer.html('<div class="flex items-center justify-center h-64"><div class="text-red-500">Error memuat data</div></div>');
                }
            },
            error: function (xhr, status, error) {
                $chartContainer.css('opacity', '1');
                console.error('Error loading activity departemen data:', error);
                $chartContainer.html('<div class="flex items-center justify-center h-64"><div class="text-red-500">Error: ' + error + '</div></div>');
            },
        });
    }

    // Handle distribusi aktifitas perdepartemen date filter change
    $('#dept_activity_start, #dept_activity_end').on('change', function () {
        const startDate = $('#dept_activity_start').val();
        const endDate = $('#dept_activity_end').val();

        if (endDate && startDate && endDate < startDate) {
            alert('Tanggal akhir harus setelah atau sama dengan tanggal awal');
            $('#dept_activity_end').val('');
            return;
        }

        if (startDate || endDate) {
            loadActivityDepartemenData(startDate, endDate);
        } else {
            updateActivityDepartemenChart(window.deptNamesDetail || [], window.deptTotalDetail || [], window.deptIdsDetail || []);
        }
    });

    $('#resstBtnDistActivity').on('click', function () {
        // Reset date inputs
        $('#dept_activity_start').val('');
        $('#dept_activity_end').val('');

        // Reset chart to initial data
        updateActivityDepartemenChart(window.deptNamesDetail || [], window.deptTotalDetail || [], window.deptIdsDetail || []);
    });
});
