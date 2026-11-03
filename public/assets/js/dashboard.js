/* 
========================================================================
   BOOTSTRAP 5 ADMIN TEMPLATE - SPARK ADMIN
   DASHBOARD CORE JAVASCRIPT MODULE
   Developed with premium UI/UX standards

   Template Name: Spark Admin
   Version: 1.0 
   Author: Spark Admin Team 
   Email: hello.sparkadmin@gmail.com
   URL: https://sparkadmin.web.id
========================================================================
*/

document.addEventListener('DOMContentLoaded', function () {
    // -----------------------------------------------------------------
    // 1. Mobile Sidebar Toggle & Backdrop Overlay
    // -----------------------------------------------------------------
    const sidebar = document.querySelector('.sidebar-wrapper');
    const toggleBtn = document.querySelector('.sidebar-toggle-btn');
    
    // Create and append backdrop overlay for mobile sidebar
    let overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    document.body.appendChild(overlay);

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show', sidebar.classList.contains('show'));
        });

        // Close sidebar when clicking on backdrop overlay
        overlay.addEventListener('click', function () {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
    }


    // -----------------------------------------------------------------
    // 2. Revenue Chart (Vertical Bar Chart - Income vs Expenses)
    // -----------------------------------------------------------------
    const revenueChartEl = document.querySelector('#revenue-chart');
    if (revenueChartEl) {
        const chartData = revenueChartEl.dataset.chart ? JSON.parse(revenueChartEl.dataset.chart) : null;
        const selectedMetric = chartData && chartData.metric ? chartData.metric : 'income';
        const fallbackSeries = [
            { name: 'Pemasukan video', data: [12, 18, 15, 21, 19, 26, 22, 30] },
            { name: 'Pasien', data: [5, 6, 7, 8, 7, 9, 8, 10] },
            { name: 'Janji temu', data: [4, 5, 6, 5, 7, 6, 8, 9] }
        ];
        const rawSeries = chartData && Array.isArray(chartData.series) && chartData.series.length ? chartData.series : fallbackSeries;
        const finalSeries = rawSeries.map(function (series) {
            return {
                ...series,
            type: selectedMetric === 'income' ? 'column' : 'line',
            yAxisIndex: 0
            };
        });
        const categories = (chartData && Array.isArray(chartData.labels) && chartData.labels.length) ? chartData.labels : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'];

        const revenueChartOptions = {
            series: finalSeries,
            chart: {
                type: selectedMetric === 'income' ? 'bar' : 'line',
                height: 220,
                stacked: false,
                toolbar: { show: false },
                zoom: { enabled: false },
                fontFamily: 'Plus Jakarta Sans, sans-serif'
            },
            colors: ['#072F1F', '#B4F105', '#F97316'],
            states: {
                hover: { filter: { type: 'none' } }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '48%',
                    borderRadius: 0
                },
            },
            dataLabels: { enabled: false },
            stroke: { show: true, width: selectedMetric === 'income' ? 0 : 3, curve: 'smooth' },
            legend: { show: false },
            grid: {
                borderColor: '#E9EFEF',
                strokeDashArray: 4,
                yaxis: { lines: { show: true } },
                xaxis: { lines: { show: false } },
                padding: { top: 0, right: 0, bottom: 0, left: 0 }
            },
            xaxis: {
                categories: categories,
                tickAmount: categories.length > 12 ? 10 : undefined,
                labels: { style: { colors: '#6C7E75', fontSize: '11px', fontWeight: 500 } },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: { labels: { show: false }, title: { text: selectedMetric === 'income' ? 'Pemasukan (Rp)' : 'Jumlah' }, min: 0 },
            fill: { opacity: 1 },
            markers: {
                size: selectedMetric === 'income' ? 0 : 4,
                hover: { size: 6 }
            },
            tooltip: {
                enabled: true,
                theme: 'dark',
                shared: false,
                intersect: true,
                marker: { show: true },
                x: { show: true },
                y: { formatter: function (val, { seriesIndex, w }) {
                    const seriesName = w.config.series[seriesIndex] ? w.config.series[seriesIndex].name : '';
                    if (seriesName === 'Pemasukan video') {
                        return 'Rp ' + val.toLocaleString('id-ID');
                    }
                    return Number(val).toLocaleString('id-ID') + ' data';
                } }
            }
        };

        const revenueChart = new ApexCharts(revenueChartEl, revenueChartOptions);
        revenueChart.render();

        const chartFilter = document.querySelector('#dashboard-chart-filter');
        if (chartFilter) {
            const periodSelect = chartFilter.querySelector('select[name="period"]');
            const monthSelect = chartFilter.querySelector('select[name="month"]');
            const yearSelect = chartFilter.querySelector('select[name="year"]');

            function syncFilterVisibility() {
                const isYear = periodSelect && periodSelect.value === 'year';

                if (monthSelect) {
                    monthSelect.style.display = isYear ? 'none' : '';
                }

                if (yearSelect) {
                    yearSelect.style.display = isYear ? '' : 'none';
                }
            }

            if (periodSelect) {
                periodSelect.addEventListener('change', function () {
                    syncFilterVisibility();
                    chartFilter.requestSubmit();
                });
            }

            if (monthSelect || yearSelect) {
                [monthSelect, yearSelect].filter(Boolean).forEach(function (select) {
                    select.addEventListener('change', function () {
                        chartFilter.requestSubmit();
                    });
                });
            }

            syncFilterVisibility();

            chartFilter.addEventListener('submit', function (event) {
                event.preventDefault();

                const params = new URLSearchParams(new FormData(chartFilter));
                const targetUrl = chartFilter.action + '?' + params.toString();

                window.location.href = targetUrl;
            });
        }
    }

    // -----------------------------------------------------------------
    // 3. Total View Performance Chart (Donut Chart)
    // -----------------------------------------------------------------
    const viewsChartEl = document.querySelector('#views-chart');
    if (viewsChartEl) {
        const viewsChartOptions = {
            series: [68, 23, 16], // View Count (68%), Percentage (23%), Sales (16%)
            chart: {
                type: 'donut',
                height: 250,
                fontFamily: 'Plus Jakarta Sans, sans-serif'
            },
            labels: ['View Count', 'Percentage', 'Sales'],
            colors: ['#B4F105', '#051C12', '#F97316'], // Lime, Forest Dark, Orange
            states: {
                hover: {
                    filter: {
                        type: 'none'
                    }
                }
            },
            legend: {
                show: false // Custom HTML legend used below the chart
            },
            dataLabels: {
                enabled: false
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '72%',
                        background: 'transparent',
                        labels: {
                            show: true,
                            name: {
                                show: true,
                                fontSize: '12px',
                                fontWeight: 500,
                                color: '#6C7E75',
                                offsetY: -8
                            },
                            value: {
                                show: true,
                                fontSize: '26px',
                                fontWeight: 800,
                                color: '#0B130F',
                                offsetY: 8,
                                formatter: function (val) {
                                    return val + "%";
                                }
                            },
                            total: {
                                show: true,
                                label: 'Total Count',
                                fontSize: '11px',
                                fontWeight: 500,
                                color: '#6C7E75',
                                formatter: function (w) {
                                    return '565K';
                                }
                            }
                        }
                    }
                }
            },
            tooltip: {
                theme: 'dark'
            }
        };

        const viewsChart = new ApexCharts(viewsChartEl, viewsChartOptions);
        viewsChart.render();
    }

    // -----------------------------------------------------------------
    // 4. Sparkline Charts (Net Income & Total Return)
    // -----------------------------------------------------------------
    const incomeSparkOptions = {
        series: [{
            name: 'Net Income',
            data: [45, 51, 46, 58, 50, 62, 55, 72, 65, 79, 70, 85]
        }],
        chart: {
            type: 'area',
            height: 45,
            sparkline: {
                enabled: true
            },
            fontFamily: 'Plus Jakarta Sans, sans-serif'
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        fill: {
            opacity: 0.1,
            type: 'solid'
        },
        colors: ['#22C55E'], // Green color matching trend-up
        tooltip: {
            fixed: {
                enabled: false
            },
            x: {
                show: false
            },
            y: {
                title: {
                    formatter: function (seriesName) {
                        return '';
                    }
                }
            },
            marker: {
                show: false
            }
        }
    };

    const returnSparkOptions = {
        series: [{
            name: 'Total Return',
            data: [50, 48, 55, 45, 40, 38, 42, 35, 30, 28, 32, 24]
        }],
        chart: {
            type: 'area',
            height: 45,
            sparkline: {
                enabled: true
            },
            fontFamily: 'Plus Jakarta Sans, sans-serif'
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        fill: {
            opacity: 0.1,
            type: 'solid'
        },
        colors: ['#EF4444'], // Red color matching trend-down
        tooltip: {
            fixed: {
                enabled: false
            },
            x: {
                show: false
            },
            y: {
                title: {
                    formatter: function (seriesName) {
                        return '';
                    }
                }
            },
            marker: {
                show: false
            }
        }
    };

    const incomeSparkEl = document.querySelector('#income-sparkline');
    if (incomeSparkEl) {
        const incomeSpark = new ApexCharts(incomeSparkEl, incomeSparkOptions);
        incomeSpark.render();
    }

    const returnSparkEl = document.querySelector('#return-sparkline');
    if (returnSparkEl) {
        const returnSpark = new ApexCharts(returnSparkEl, returnSparkOptions);
        returnSpark.render();
    }

    // -----------------------------------------------------------------
    // 5. Flatpickr Date Range Picker Initialization
    // -----------------------------------------------------------------
    const datePickerTrigger = document.querySelector('#date-picker-trigger');
    const selectedRangeText = document.querySelector('#selected-date-range');
    
    if (datePickerTrigger && selectedRangeText) {
        flatpickr(datePickerTrigger, {
            mode: 'range',
            dateFormat: 'Y-m-d',
            defaultDate: ['2026-01-12', '2026-01-23'],
            onValueUpdate: function (selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    const startStr = instance.formatDate(selectedDates[0], 'F j, Y');
                    const endStr = instance.formatDate(selectedDates[1], 'F j, Y');
                    selectedRangeText.textContent = `${startStr} - ${endStr}`;
                } else if (selectedDates.length === 1) {
                    const startStr = instance.formatDate(selectedDates[0], 'F j, Y');
                    selectedRangeText.textContent = startStr;
                }
            }
        });
    }

    // -----------------------------------------------------------------
    // 6. Desktop Sidebar Minimize Interaction
    // -----------------------------------------------------------------
    const desktopToggleBtn = document.querySelector('#desktop-sidebar-toggle');
    if (desktopToggleBtn) {
        desktopToggleBtn.addEventListener('click', function () {
            document.body.classList.toggle('sidebar-minimized');
            
            // Toggle icon direction
            const icon = desktopToggleBtn.querySelector('i');
            if (icon) {
                if (document.body.classList.contains('sidebar-minimized')) {
                    icon.className = 'bi bi-chevron-bar-right';
                } else {
                    icon.className = 'bi bi-chevron-bar-left';
                }
            }
            
            // Trigger a window resize event so that charts (ApexCharts) redraw correctly
            setTimeout(() => {
                window.dispatchEvent(new Event('resize'));
            }, 300);
        });
    }

    // -----------------------------------------------------------------
    // 7. Fullscreen Toggle Interaction
    // -----------------------------------------------------------------
    const fullscreenBtn = document.querySelector('#btn-fullscreen');
    if (fullscreenBtn) {
        fullscreenBtn.addEventListener('click', function () {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().then(() => {
                    updateFullscreenIcon(true);
                }).catch(err => {
                    console.error(`Error attempting to enable fullscreen mode: ${err.message}`);
                });
            } else {
                document.exitFullscreen().then(() => {
                    updateFullscreenIcon(false);
                }).catch(err => {
                    console.error(`Error attempting to exit fullscreen mode: ${err.message}`);
                });
            }
        });

        function updateFullscreenIcon(isFullscreen) {
            const icon = fullscreenBtn.querySelector('i');
            if (icon) {
                if (isFullscreen) {
                    icon.className = 'bi bi-fullscreen-exit';
                } else {
                    icon.className = 'bi bi-arrows-fullscreen';
                }
            }
        }

        // Listen for browser native fullscreen changes (e.g. Esc key)
        document.addEventListener('fullscreenchange', () => {
            if (document.fullscreenElement) {
                updateFullscreenIcon(true);
            } else {
                updateFullscreenIcon(false);
            }
        });
    }
});
