$(document).ready(function() {
    // --- CHART INSTANCES ---
    let dailyEPaymentBarChart, dailyEPaymentLineChart;
    let weeklyEpaymentBarChart, weeklyEpaymentLineChart;
    let monthlyEpaymentBarChart, monthlyEpaymentLineChart;

    // --- FUNGSI UTILITAS ---
    const formatRupiah = (number) => {
        const num = parseFloat(number);
        return isNaN(num) ? 'Rp 0' : new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(num);
    };

    const getPaymentLabel = (key) => {
        const labels = {
            'emoney': 'e-Money',
            'flazz': 'Flazz',
            'brizzi': 'Brizzi',
            'tapcash': 'TapCash',
            'dkijack': 'DKI JackCard',
            'parkee': 'Parkee',
            'cash': 'Cash',
            'all': 'All Payments',
            'allpayment': 'All Payments'
        };
        const cleanKey = key.replace('payment', '');
        return labels[cleanKey] || (cleanKey.charAt(0).toUpperCase() + cleanKey.slice(1));
    };

    /**
     * Updates a Chart.js instance with new data while preserving the visibility state of datasets.
     * @param {Chart} chartInstance - The Chart.js instance to update.
     * @param {object} newData - The new data object for the chart, containing labels and datasets.
     */
    function updateChartPreservingLegend(chartInstance, newData) {
        if (!chartInstance || !newData) {
            return;
        }
        chartInstance.data.labels = newData.labels;
        newData.datasets.forEach((newDataset, index) => {
            const existingDataset = chartInstance.data.datasets[index];
            if (existingDataset) {
                existingDataset.data = newDataset.data;
                existingDataset.backgroundColor = newDataset.backgroundColor;
                existingDataset.borderColor = newDataset.borderColor;
            }
        });
        chartInstance.update('none');
    }

     /**
     * Creates a new chart or updates an existing one with new data.
     * @param {Chart} chartInstance - The existing Chart.js instance (can be null).
     * @param {string} canvasId - The ID of the canvas element.
     * @param {object} config - The chart configuration object.
     * @returns {Chart} The new or updated chart instance.
     */
    function createOrUpdateChart(chartInstance, canvasId, config) {
        if (chartInstance) {
            updateChartPreservingLegend(chartInstance, config.data);
            return chartInstance;
        } else {
            const ctx = document.getElementById(canvasId)?.getContext('2d');
            if (ctx) {
                return new Chart(ctx, config);
            }
        }
        return null;
    }


    // --- INISIALISASI DATATABLE ---
    const dtOptions = {
        searching: false,
        paging: false,
        autoWidth: false,
        ordering: false,
        info: false,
        data: []
    };

    const dailyEpaymentTable = $('#dailyE-Payment').DataTable({
        ...dtOptions,
        columns: [{ data: 'no' }, { data: 'payment' }, { data: 'yesterday' }, { data: 'today' }]
    });

    const weeklyEpaymentTable = $('#weeklyE-Payment').DataTable({
        ...dtOptions,
        columns: [{ data: 'no' }, { data: 'payment' }, { data: 'last_week' }, { data: 'this_week' }]
    });

    const monthlyEpaymentTable = $('#monthlyE-Payment').DataTable({
        ...dtOptions,
        columns: [{ data: 'no' }, { data: 'payment' }, { data: 'last_month' }, { data: 'this_month' }]
    });

    // --- FUNGSI PENGAMBILAN DATA ---

    /**
     * Mengambil data ringkasan E-Payment harian (tabel & kartu).
     * @returns {Promise} Promise dari jQuery AJAX.
     */
    function fetchDailyEPaymentSummary() {
        return $.ajax({
            url: dailyEpaymentURL,
            method: 'GET',
            success: function(response) {
                const today = response.data[0].today[0];
                const yesterday = response.data[0].yesterday[0];
                const compare = response.table_data;

                const container = $('#daily-epayment-comparison').empty();
                const colClasses = ['col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6'];

                compare.forEach((payment, index) => {
                    // Make the 'All Payments' card full width if it's the last one
                    let finalClass = colClasses[index] || 'col-md-6';
                    if (payment.method === 'All Payments' && index === compare.length - 1) {
                        finalClass = 'col-md-6';
                    }
                    container.append(`
                        <div class="${finalClass}">
                            <div class="dashboard-card">
                                <div class="card-title" style="color: #000 !important">${payment.method}</div>
                                <div class="d-flex align-items-baseline">
                                    <h6 class="card-value fs-6 fs-md-5" style="color: #000 !important">${formatRupiah(payment.today)}</h6>
                                    <span class="ms-2 fs-6 fs-md-5" style="color: ${payment.color}">${payment.percent_change} ${payment.direction}</span>
                                </div>
                                <div class="yesterday fs-6 fs-md-5" style="color: #000 !important">Yesterday: ${formatRupiah(payment.yesterday)}</div>
                            </div>
                        </div>
                    `);
                });

                const paymentKeys = ['emoney', 'flazz', 'brizzi', 'tapcash', 'dkijack', 'parkee', 'cash'];
                const rows = paymentKeys.map(p => ({
                    type: getPaymentLabel(p),
                    yesterday: yesterday[`${p}payment`],
                    today: today[`${p}payment`]
                }));
                const formattedRows = rows.map((item, index) => ({ no: index + 1, payment: item.type, yesterday: formatRupiah(item.yesterday), today: formatRupiah(item.today) }));
                dailyEpaymentTable.clear().rows.add(formattedRows).draw();
                $('#dailyE-Payment tfoot').html(`<tr><th colspan="2" style="text-align:left">All E-Payment</th><th>${formatRupiah(yesterday.grandtotal)}</th><th>${formatRupiah(today.grandtotal)}</th></tr>`);
            }
        });
    }

    /**
     * Mengambil data chart E-Payment harian.
     * @returns {Promise} Promise dari jQuery AJAX.
     */
    function fetchDailyEPaymentChart() {
        return $.ajax({
            url: dailyEpaymentChart,
            method: 'GET',
            success: function(response) {
                const todayData = response.this_week.data;
                const labels = todayData.map(item => new Date(item.tanggal).toLocaleDateString("id-ID", { day: "numeric", month: "short" }));
                
                const paymentTypes = ['emoney', 'flazz', 'brizzi', 'tapcash', 'dkijack', 'parkee', 'cash'];
                const colors = ['#0D61E2', '#FFB800', '#FF4D4D', '#00C9A7', '#6f42c1', '#9C27B0', '#795548'];
                const datasets = paymentTypes.map((p, i) => ({
                    label: getPaymentLabel(p),
                    data: todayData.map(item => item[`${p}payment`]),
                    backgroundColor: colors[i],
                    borderColor: colors[i],
                    hidden: i > 0,
                    datalabels: { anchor: 'end', align: 'end' }
                }));

                const dataChart = { labels, datasets };
                const commonOptions = {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { position: 'top', labels: { color: '#000' } }, datalabels: { backgroundColor: ctx => ctx.dataset.backgroundColor, borderRadius: 4, color: 'white', font: { weight: 'bold', size: 9 }, formatter: value => formatRupiah(value), padding: 6, offset: 8 } },
                    scales: { y: { beginAtZero: true, ticks: { precision: 0, color: '#000' }, grace: '10%' }, x: { ticks: { color: '#000' } } }
                };
                
                const barConfig = { type: 'bar', data: dataChart, options: commonOptions, plugins: [ChartDataLabels] };
                dailyEPaymentBarChart = createOrUpdateChart(dailyEPaymentBarChart, 'dailyE-PaymentBar', barConfig);

                const lineConfig = { type: 'line', data: dataChart, options: commonOptions, plugins: [ChartDataLabels] };
                dailyEPaymentLineChart = createOrUpdateChart(dailyEPaymentLineChart, 'dailyE-PaymentLine', lineConfig);
            }
        });
    }

    /**
     * Mengambil data E-Payment mingguan.
     * @returns {Promise} Promise dari jQuery AJAX.
     */
    function fetchWeeklyEpayment() {
        return $.ajax({
            url: weeklyEpaymentURL,
            method: 'GET',
            success: function(response) {
                const { this_week, last_week, table_data } = response;
                const container = $('#weekly-epayment-comparison').empty();
                
                const colClasses = ['col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6'];

                table_data.forEach((payment, index) => {
                     let finalClass = colClasses[index] || 'col-md-6';
                    if (payment.method === 'All Payments' && index === table_data.length - 1) {
                        finalClass = 'col-md-6';
                    }
                    container.append(`
                        <div class="${finalClass}">
                            <div class="dashboard-card">
                                <div class="card-title" style="color: #000 !important">${payment.method}</div>
                                <div class="d-flex align-items-baseline">
                                    <h6 class="card-value fs-6 fs-md-5" style="color: #000 !important">${formatRupiah(payment.this_week)}</h6>
                                    <span class="ms-2 fs-6 fs-md-5" style="color: ${payment.color}">${payment.percent_change}${payment.direction}</span>
                                </div>
                                <div class="yesterday fs-6 fs-md-5"  style="color: #000 !important">Two Weeks Ago: ${formatRupiah(payment.last_week)}</div>
                            </div>
                        </div>
                    `);
                });

                const paymentTypes = ['emoney', 'flazz', 'brizzi', 'tapcash', 'dkijack', 'parkee', 'cash'];
                const rows = paymentTypes.map(p => ({
                    type: getPaymentLabel(p),
                    last_week: last_week.totals[`${p}payment`],
                    this_week: this_week.totals[`${p}payment`]
                }));
                const formattedRows = rows.map((item, index) => ({ no: index + 1, payment: item.type, last_week: formatRupiah(item.last_week), this_week: formatRupiah(item.this_week) }));
                weeklyEpaymentTable.clear().rows.add(formattedRows).draw();
                $('#weeklyE-Payment tfoot').html(`<tr><th colspan="2" style="text-align:left">All E-Payment</th><th>${formatRupiah(last_week.totals.allpayment)}</th><th>${formatRupiah(this_week.totals.allpayment)}</th></tr>`);

                const labels = this_week.data.map(item => new Date(item.tanggal).toLocaleDateString('en-GB', { day: '2-digit', month: 'short' }));
                const chartPaymentKeys = ['emoneypayment', 'flazzpayment', 'brizzipayment', 'tapcashpayment', 'dkijackpayment', 'parkeepayment', 'cashpayment', 'allpayment'];
                const chartColors = ['#0D61E2', '#FFB800', '#FF4D4D', '#00C9A7', '#6f42c1', '#9C27B0', '#795548', '#CCCCCC'];
                
                const createDatasets = (isLine = false) => chartPaymentKeys.map((key, i) => ({
                    label: getPaymentLabel(key),
                    data: this_week.data.map(item => item[key]),
                    backgroundColor: chartColors[i],
                    borderColor: chartColors[i],
                    borderWidth: isLine ? 3 : 1,
                    tension: isLine ? 0.5 : undefined,
                    fill: !isLine,
                    hidden: i !== 0,
                    datalabels: { anchor: 'end', align: 'end' }
                }));

                const commonOptions = {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { position: 'top', labels: { color: '#000' } }, datalabels: { backgroundColor: ctx => ctx.dataset.backgroundColor, borderRadius: 4, color: 'white', font: { weight: 'bold', size: 9 }, formatter: value => formatRupiah(value), padding: 6, offset: 8 } },
                    scales: { y: { beginAtZero: true, ticks: { precision: 0, color: '#000' }, grace: '10%' }, x: { ticks: { color: '#000' } } }
                };

                const barConfig = { type: 'bar', data: { labels, datasets: createDatasets(false) }, options: commonOptions, plugins: [ChartDataLabels] };
                weeklyEpaymentBarChart = createOrUpdateChart(weeklyEpaymentBarChart, 'weeklyE-PaymentBar', barConfig);

                const lineConfig = { type: 'line', data: { labels, datasets: createDatasets(true) }, options: commonOptions, plugins: [ChartDataLabels] };
                weeklyEpaymentLineChart = createOrUpdateChart(weeklyEpaymentLineChart, 'weeklyE-PaymentLine', lineConfig);
            }
        });
    }

    /**
     * Mengambil data E-Payment bulanan.
     * @returns {Promise} Promise dari jQuery AJAX.
     */
    function fetchMonthlyEpayment() {
        return $.ajax({
            url: monthlyEpaymentURL,
            method: 'GET',
            success: function(response) {
                const { this_Month, last_Month, table_data } = response;
                const container = $('#monthly-epayment-comparison').empty();
                
                const colClasses = ['col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6'];

                table_data.forEach((payment, index) => {
                     let finalClass = colClasses[index] || 'col-md-6';
                    if (payment.method === 'All Payments' && index === table_data.length - 1) {
                        finalClass = 'col-md-6';
                    }
                    container.append(`
                        <div class="${finalClass}">
                            <div class="dashboard-card">
                                <div class="card-title" style="color: #000 !important">${payment.method}</div>
                                <div class="d-flex align-items-baseline">
                                    <h6 class="card-value fs-6 fs-md-5" style="color: #000 !important">${formatRupiah(payment.this_month)}</h6>
                                    <span class="ms-2 fs-6 fs-md-5" style="color: ${payment.color}">${payment.percent_change} ${payment.direction}</span>
                                </div>
                                <div class="yesterday fs-6 fs-md-5" style="color: #000 !important">Two Months Ago: ${formatRupiah(payment.last_month)}</div>
                            </div>
                        </div>
                    `);
                });

                const paymentTypes = ['emoney', 'flazz', 'brizzi', 'tapcash', 'dkijack', 'parkee', 'cash'];
                const rows = paymentTypes.map(p => ({
                    type: getPaymentLabel(p),
                    last_month: last_Month.totals[`${p}payment`],
                    this_month: this_Month.totals[`${p}payment`]
                }));
                const formattedRows = rows.map((item, index) => ({ no: index + 1, payment: item.type, last_month: formatRupiah(item.last_month), this_month: formatRupiah(item.this_month) }));
                monthlyEpaymentTable.clear().rows.add(formattedRows).draw();
                $('#monthlyE-Payment tfoot').html(`<tr><th colspan="2" style="text-align:left">All E-Payment</th><th>${formatRupiah(last_Month.totals.allpayment)}</th><th>${formatRupiah(this_Month.totals.allpayment)}</th></tr>`);

                const labels = Object.keys(this_Month.weekly_totals);
                const chartPaymentKeys = ['emoneypayment', 'flazzpayment', 'brizzipayment', 'tapcashpayment', 'dkijackpayment', 'parkeepayment', 'cashpayment', 'allpayment'];
                const chartColors = ['#0D61E2', '#FFB800', '#FF4D4D', '#00C9A7', '#6f42c1', '#9C27B0', '#795548', '#CCCCCC'];
                
                const buildDatasets = (isLine = false) => chartPaymentKeys.map((key, i) => ({
                    label: getPaymentLabel(key),
                    data: Object.values(this_Month.weekly_totals).map(item => item[key]),
                    backgroundColor: chartColors[i],
                    borderColor: chartColors[i],
                    borderWidth: isLine ? 3 : 1,
                    tension: isLine ? 0.5 : undefined,
                    hidden: i !== 0,
                    fill: !isLine,
                    datalabels: { anchor: 'end', align: 'end' }
                }));

                const commonOptions = {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { position: 'top', labels: { color: '#000' } }, datalabels: { backgroundColor: ctx => ctx.dataset.backgroundColor, borderRadius: 4, color: 'white', font: { weight: 'bold', size: 9 }, formatter: value => formatRupiah(value), padding: 6, offset: 8 } },
                    scales: { y: { beginAtZero: true, ticks: { precision: 0, color: '#000' }, grace: '10%' }, x: { ticks: { color: '#000' } } }
                };

                const barConfig = { type: 'bar', data: { labels, datasets: buildDatasets(false) }, options: commonOptions, plugins: [ChartDataLabels] };
                monthlyEpaymentBarChart = createOrUpdateChart(monthlyEpaymentBarChart, 'monthlyE-PaymentBar', barConfig);

                const lineConfig = { type: 'line', data: { labels, datasets: buildDatasets(true) }, options: commonOptions, plugins: [ChartDataLabels] };
                monthlyEpaymentLineChart = createOrUpdateChart(monthlyEpaymentLineChart, 'monthlyE-PaymentLine', lineConfig);
            }
        });
    }


    /**
     * Fungsi utama untuk memuat semua data E-Payment secara efisien.
     */
    async function loadAllEpaymentData() {
        console.log("Memuat ulang semua data E-Payment...");
        const promises = [
            fetchDailyEPaymentSummary(),
            fetchDailyEPaymentChart(),
            fetchWeeklyEpayment(),
            fetchMonthlyEpayment()
        ];

        try {
            await Promise.all(promises);
            console.log("Semua data E-Payment berhasil dimuat ulang.");
        } catch (error) {
            console.error("Terjadi kesalahan saat memuat ulang data E-Payment:", error);
        }
    }

    // --- INISIALISASI SKRIP ---
    loadAllEpaymentData();
    setInterval(loadAllEpaymentData, 15000);
});
