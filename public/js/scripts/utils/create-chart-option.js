function createPieChart(series, labels, colors = ['#fbbf24', '#10b981', '#ef4444'], chartHeight = 350, size = '65%') {
    return {
        series: series,
        chart: {
            type: 'donut',
            height: chartHeight,
        },
        labels: labels,
        colors: colors,
        legend: {
            position: 'bottom',
        },
        plotOptions: {
            pie: {
                donut: {
                    size: size,
                },
            },
        },
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return val.toFixed(1) + '%';
            },
        },
    };
}

function createLineChart(series, xaxis, height = 350, colors = ['#14b8a6']) {
    return {
        series: series,
        chart: {
            type: 'line',
            height: height,
            toolbar: {
                show: false,
            },
        },
        stroke: {
            curve: 'smooth',
            width: 3,
        },
        xaxis: xaxis,
        colors: colors,
        markers: {
            size: 5,
            hover: {
                size: 7,
            },
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + ' log';
                },
            },
        },
    };
}

function createBarOption(series, xaxis, { chartHeight = 350, isHorizontalChart = false, colors = ['#14b8a6'], onclick = null, customTooltip = null } = {}) {
    // Calculate dynamic height for horizontal charts based on number of categories
    let dynamicHeight = chartHeight;
    if (isHorizontalChart && xaxis && xaxis.categories) {
        const categoryCount = xaxis.categories.length;
        // Minimum 40px per category, with min 200px and max 600px
        dynamicHeight = Math.max(200, Math.min(600, categoryCount * 40));
    }

    const options = {
        series: series,
        chart: {
            type: 'bar',
            height: dynamicHeight,
            toolbar: {
                show: false,
            },
            events: {
                dataPointSelection: onclick,
            },
        },
        plotOptions: {
            bar: {
                borderRadius: 4,
                horizontal: isHorizontalChart,
                barHeight: isHorizontalChart ? '70%' : undefined,
            },
        },
        dataLabels: {
            enabled: true,
            style: {
                fontSize: '12px',
                fontWeight: 600,
            },
        },
        xaxis: xaxis,
        yaxis: isHorizontalChart
            ? {
                  labels: {
                      style: {
                          fontSize: '11px',
                      },
                      maxWidth: 150,
                  },
              }
            : undefined,
        colors: colors,
        responsive: [
            {
                breakpoint: 1024,
                options: {
                    chart: {
                        height: isHorizontalChart ? Math.max(200, Math.min(500, (xaxis?.categories?.length || 0) * 35)) : 300,
                    },
                    dataLabels: {
                        style: {
                            fontSize: '11px',
                        },
                    },
                    yaxis: isHorizontalChart
                        ? {
                              labels: {
                                  style: {
                                      fontSize: '10px',
                                  },
                                  maxWidth: 120,
                              },
                          }
                        : undefined,
                },
            },
            {
                breakpoint: 768,
                options: {
                    chart: {
                        height: isHorizontalChart ? Math.max(200, Math.min(400, (xaxis?.categories?.length || 0) * 30)) : 250,
                    },
                    dataLabels: {
                        style: {
                            fontSize: '10px',
                        },
                    },
                    yaxis: isHorizontalChart
                        ? {
                              labels: {
                                  style: {
                                      fontSize: '9px',
                                  },
                                  maxWidth: 100,
                              },
                          }
                        : undefined,
                },
            },
            {
                breakpoint: 640,
                options: {
                    chart: {
                        height: isHorizontalChart ? Math.max(200, Math.min(350, (xaxis?.categories?.length || 0) * 28)) : 200,
                    },
                    dataLabels: {
                        style: {
                            fontSize: '9px',
                        },
                    },
                    yaxis: isHorizontalChart
                        ? {
                              labels: {
                                  style: {
                                      fontSize: '8px',
                                  },
                                  maxWidth: 80,
                              },
                          }
                        : undefined,
                },
            },
        ],
    };

    // Add custom tooltip if provided
    if (customTooltip) {
        options.tooltip = customTooltip;
    }

    return options;
}

function createGanttChart(series, karyawanNames, { chartHeight = 400, colors = ['#14b8a6'], customTooltip = null } = {}) {
    const options = {
        series: series,
        chart: {
            type: 'rangeBar',
            height: chartHeight,
            toolbar: {
                show: false,
            },
        },
        plotOptions: {
            bar: {
                horizontal: true,
                barHeight: '70%',
                rangeBarGroupRows: true,
                distributed: true,
            },
        },
        dataLabels: {
            enabled: true,
            formatter: function (val, opts) {
                const data = opts.w.config.series[opts.seriesIndex].data[opts.dataPointIndex];
                if (data && data.waktu_awal && data.waktu_akhir) {
                    const start = data.waktu_awal.substring(0, 5);
                    const end = data.waktu_akhir.substring(0, 5);
                    return start + ' - ' + end;
                }
                return '';
            },
            style: {
                fontSize: '10px',
                fontWeight: 600,
                colors: ['#fff'],
            },
        },
        xaxis: {
            type: 'numeric',
            min: 0,
            max: 1440,
            tickAmount: 12,
            decimalsInFloat: 0,
            labels: {
                formatter: function (val) {
                    const roundedMinutes = Math.round(val / 30) * 30;
                    const hours = Math.floor(roundedMinutes / 60);
                    const minutes = roundedMinutes % 60;

                    if (minutes === 0 || minutes === 30) {
                        return String(hours).padStart(2, '0') + ':' + String(minutes).padStart(2, '0');
                    }
                    return '';
                },
                style: {
                    fontSize: '11px',
                    fontWeight: 500,
                    colors: '#6b7280',
                },
                rotate: -45,
                rotateAlways: false,
            },
            title: {
                text: 'Waktu (24 Jam)',
                style: {
                    fontSize: '12px',
                    fontWeight: 600,
                    color: '#6b7280',
                },
            },
        },
        yaxis: {
            categories: karyawanNames,
            labels: {
                style: {
                    fontSize: '11px',
                    fontWeight: 500,
                    colors: '#6b7280',
                },
            },
        },
        grid: {
            borderColor: '#e5e7eb',
            strokeDashArray: 4,
            xaxis: {
                lines: {
                    show: true,
                },
            },
            yaxis: {
                lines: {
                    show: true,
                },
            },
            padding: {
                top: 10,
                right: 10,
                bottom: 10,
                left: 10,
            },
        },

        colors: colors,
        tooltip: {
            custom: function ({ series, seriesIndex, dataPointIndex, w }) {
                const data = w.globals.initialSeries[seriesIndex].data[dataPointIndex];
                if (!data) return '';

                const tanggal = data.tanggal ? new Date(data.tanggal).toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' }) : '-';
                const waktuAwal = data.waktu_awal ? data.waktu_awal.substring(0, 5) : '-';
                const waktuAkhir = data.waktu_akhir ? data.waktu_akhir.substring(0, 5) : '-';
                const aktivitas = data.aktivitas || data.activity || '-';

                return `
                    <div class="bg-white rounded-lg shadow-xl border border-gray-200 p-4 z-40" style="font-family: system-ui, -apple-system, sans-serif; max-width: 400px; word-wrap: break-word; overflow-wrap: break-word;">
                        <div class="flex items-center gap-2 mb-3 pb-3 border-b border-gray-200">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-teal-500 to-teal-600 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-gray-900">${data.x || 'Karyawan'}</h3>
                                <p class="text-xs text-gray-600">${tanggal}</p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 text-xs">
                                <span class="font-semibold text-gray-700">Waktu:</span>
                                <span class="text-teal-600">${waktuAwal} - ${waktuAkhir}</span>
                            </div>
                            <div class="pt-2 border-t border-gray-200">
                                <p class="text-xs font-semibold text-gray-700 mb-1">Aktivitas:</p>
                                <p class="text-xs text-gray-600" style="white-space: normal; word-break: break-word; overflow-wrap: break-word;">${aktivitas}</p>
                            </div>
                        </div>
                    </div>
                `;
            },
        },
    };

    // Override custom tooltip if provided
    if (customTooltip) {
        options.tooltip = customTooltip;
    }

    return options;
}

function createTimelineChart(series, xaxis, { chartHeight = 350, colors = ['#14b8a6'], customTooltip = null } = {}) {
    const options = {
        series: series,
        chart: {
            type: 'area',
            height: chartHeight,
            toolbar: {
                show: false,
            },
            zoom: {
                enabled: false,
            },
        },
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return val > 0 ? val : '';
            },
            style: {
                fontSize: '11px',
                fontWeight: 600,
                colors: ['#fff'],
            },
            offsetY: -5,
        },
        stroke: {
            curve: 'smooth',
            width: 3,
            colors: colors,
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.3,
                stops: [0, 90, 100],
                colorStops: [
                    {
                        offset: 0,
                        color: colors[0],
                        opacity: 0.8,
                    },
                    {
                        offset: 100,
                        color: colors[0],
                        opacity: 0.1,
                    },
                ],
            },
        },
        markers: {
            size: 6,
            strokeWidth: 3,
            strokeColors: colors,
            fillColors: '#fff',
            hover: {
                size: 8,
            },
        },
        xaxis: xaxis,
        yaxis: {
            labels: {
                formatter: function (val) {
                    return Math.floor(val);
                },
            },
            title: {
                text: 'Jumlah Aktivitas',
                style: {
                    fontSize: '12px',
                    fontWeight: 600,
                    color: '#6b7280',
                },
            },
        },
        grid: {
            borderColor: '#e5e7eb',
            strokeDashArray: 4,
            xaxis: {
                lines: {
                    show: true,
                },
            },
            yaxis: {
                lines: {
                    show: true,
                },
            },
            padding: {
                top: 0,
                right: 0,
                bottom: 0,
                left: 0,
            },
        },
        colors: colors,
        tooltip: {
            shared: true,
            intersect: false,
        },
    };

    if (customTooltip) {
        options.tooltip = customTooltip;
    }

    return options;
}
