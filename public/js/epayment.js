$(document).ready(function() {

    // --- FUNGSI UTILITAS ---
    const formatRupiah = (number) => {
        const num = parseFloat(number);
        return isNaN(num) ? 'Rp 0' : new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(num);
    };

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
                const colClasses = ['col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-12'];
                compare.forEach((payment, index) => {
                    container.append(`
                        <div class="${colClasses[index]}">
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

                const rows = ['emoney', 'flazz', 'brizzi', 'tapcash', 'parkee', 'cash'].map(p => ({
                    type: p.charAt(0).toUpperCase() + p.slice(1).replace('tapcash', 'Tap Cash'),
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
                
                const paymentTypes = ['emoney', 'flazz', 'brizzi', 'tapcash', 'parkee', 'cash'];
                const colors = ['#0D61E2', '#FFB800', '#FF4D4D', '#00C9A7', '#9C27B0', '#795548'];
                const datasets = paymentTypes.map((p, i) => ({
                    label: p.charAt(0).toUpperCase() + p.slice(1).replace('tapcash', 'Tap Cash'),
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

                if (window.dailyEPaymentBarChart) window.dailyEPaymentBarChart.destroy();
                const ctxBar = document.getElementById('dailyE-PaymentBar')?.getContext('2d');
                if (ctxBar) window.dailyEPaymentBarChart = new Chart(ctxBar, { type: 'bar', data: dataChart, options: commonOptions, plugins: [ChartDataLabels] });

                if (window.dailyEPaymentLineChart) window.dailyEPaymentLineChart.destroy();
                const ctxLine = document.getElementById('dailyE-PaymentLine')?.getContext('2d');
                if (ctxLine) window.dailyEPaymentLineChart = new Chart(ctxLine, { type: 'line', data: dataChart, options: commonOptions, plugins: [ChartDataLabels] });
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
                const colClasses = ['col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-12'];
                table_data.forEach((payment, index) => {
                    container.append(`
                        <div class="${colClasses[index]}">
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

                const paymentTypes = ['emoney', 'flazz', 'brizzi', 'tapcash', 'parkee', 'cash'];
                const rows = paymentTypes.map(p => ({
                    type: p.charAt(0).toUpperCase() + p.slice(1).replace('tapcash', 'Tap Cash'),
                    last_week: last_week.totals[`${p}payment`],
                    this_week: this_week.totals[`${p}payment`]
                }));
                const formattedRows = rows.map((item, index) => ({ no: index + 1, payment: item.type, last_week: formatRupiah(item.last_week), this_week: formatRupiah(item.this_week) }));
                weeklyEpaymentTable.clear().rows.add(formattedRows).draw();
                $('#weeklyE-Payment tfoot').html(`<tr><th colspan="2" style="text-align:left">All E-Payment</th><th>${formatRupiah(last_week.totals.allpayment)}</th><th>${formatRupiah(this_week.totals.allpayment)}</th></tr>`);

                const labels = this_week.data.map(item => new Date(item.tanggal).toLocaleDateString('en-GB', { day: '2-digit', month: 'short' }));
                const chartLabels = ['E Money', 'Flazz', 'Brizzi', 'Tap Cash', 'Parkee', 'Cash', 'All'];
                const chartColors = ['#0D61E2', '#FFB800', '#FF4D4D', '#00C9A7', '#9C27B0', '#795548', '#CCCCCC'];
                const weeklyData = ['emoneypayment', 'flazzpayment', 'brizzipayment', 'tapcashpayment', 'parkeepayment', 'cashpayment', 'allpayment'].map(key => this_week.data.map(item => item[key]));
                
                const createDatasets = (isLine = false) => chartLabels.map((label, i) => ({
                    label: label,
                    data: weeklyData[i],
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

                if (window.weeklyEpaymentBarChart) window.weeklyEpaymentBarChart.destroy();
                const ctxBar = document.getElementById('weeklyE-PaymentBar')?.getContext('2d');
                if (ctxBar) window.weeklyEpaymentBarChart = new Chart(ctxBar, { type: 'bar', data: { labels, datasets: createDatasets(false) }, options: commonOptions, plugins: [ChartDataLabels] });

                if (window.weeklyEpaymentLineChart) window.weeklyEpaymentLineChart.destroy();
                const ctxLine = document.getElementById('weeklyE-PaymentLine')?.getContext('2d');
                if (ctxLine) window.weeklyEpaymentLineChart = new Chart(ctxLine, { type: 'line', data: { labels, datasets: createDatasets(true) }, options: commonOptions, plugins: [ChartDataLabels] });
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
                const colClasses = ['col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-12'];
                table_data.forEach((payment, index) => {
                    container.append(`
                        <div class="${colClasses[index]}">
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

                const paymentTypes = ['emoney', 'flazz', 'brizzi', 'tapcash', 'parkee', 'cash'];
                const rows = paymentTypes.map(p => ({
                    type: p.charAt(0).toUpperCase() + p.slice(1).replace('tapcash', 'Tap Cash'),
                    last_month: last_Month.totals[`${p}payment`],
                    this_month: this_Month.totals[`${p}payment`]
                }));
                const formattedRows = rows.map((item, index) => ({ no: index + 1, payment: item.type, last_month: formatRupiah(item.last_month), this_month: formatRupiah(item.this_month) }));
                monthlyEpaymentTable.clear().rows.add(formattedRows).draw();
                $('#monthlyE-Payment tfoot').html(`<tr><th colspan="2" style="text-align:left">All E-Payment</th><th>${formatRupiah(last_Month.totals.allpayment)}</th><th>${formatRupiah(this_Month.totals.allpayment)}</th></tr>`);

                const labels = Object.keys(this_Month.weekly_totals);
                const chartLabels = ['E Money', 'Flazz', 'Brizzi', 'Tap Cash', 'Parkee', 'Cash', 'All'];
                const chartColors = ['#0D61E2', '#FFB800', '#FF4D4D', '#00C9A7', '#9C27B0', '#795548', '#CCCCCC'];
                const monthlyData = ['emoneypayment', 'flazzpayment', 'brizzipayment', 'tapcashpayment', 'parkeepayment', 'cashpayment', 'allpayment'].map(key => Object.values(this_Month.weekly_totals).map(item => item[key]));
                
                const buildDatasets = (isLine = false) => chartLabels.map((label, i) => ({
                    label,
                    data: monthlyData[i],
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

                if (window.monthlyEpaymentBarChart) window.monthlyEpaymentBarChart.destroy();
                const ctxBar = document.getElementById('monthlyE-PaymentBar')?.getContext('2d');
                if (ctxBar) window.monthlyEpaymentBarChart = new Chart(ctxBar, { type: 'bar', data: { labels, datasets: buildDatasets(false) }, options: commonOptions, plugins: [ChartDataLabels] });

                if (window.monthlyEpaymentLineChart) window.monthlyEpaymentLineChart.destroy();
                const ctxLine = document.getElementById('monthlyE-PaymentLine')?.getContext('2d');
                if (ctxLine) window.monthlyEpaymentLineChart = new Chart(ctxLine, { type: 'line', data: { labels, datasets: buildDatasets(true) }, options: commonOptions, plugins: [ChartDataLabels] });
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
    setInterval(loadAllEpaymentData, 5000);
});
