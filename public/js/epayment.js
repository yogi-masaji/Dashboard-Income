$(document).ready(function() {

    const formatRupiah = (number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    };

    function formatQuantity(quantity) {
        return new Intl.NumberFormat().format(quantity);
    }
    const table = $('#dailyE-Payment').DataTable({
        searching: false,
        paging: false,
        autoWidth: false,
        ordering: false,
        info: false,
        data: [],
        columns: [{
                data: 'no'
            },
            {
                data: 'payment'
            },
            {
                data: 'yesterday'
            },
            {
                data: 'today'
            }
        ]
    });

    const weeklyTable = $('#weeklyE-Payment').DataTable({
        searching: false,
        paging: false,
        autoWidth: false,
        ordering: false,
        info: false,
        data: [],
        columns: [{
                data: 'no'
            },
            {
                data: 'payment'
            },
            {
                data: 'last_week'
            },
            {
                data: 'this_week'
            }
        ]
    });

    const monthlyTable = $('#monthlyE-Payment').DataTable({
        searching: false,
        paging: false,
        autoWidth: false,
        ordering: false,
        info: false,
        data: [],
        columns: [{
                data: 'no'
            },
            {
                data: 'payment'
            },
            {
                data: 'last_month'
            },
            {
                data: 'this_month'
            }
        ]
    });

    function fetchDailyEPayment() {
    $.ajax({
        url: dailyEpaymentChart,
        method: 'GET',
        success: function(response) {
            const today = response.this_week.data;

            const labels = today.map(item => {
                const date = new Date(item.tanggal);
                return date.toLocaleDateString("id-ID", { day: "numeric", month: "short" });
            });

            const dataChart = {
                labels: labels,
                datasets: [
                    {
                        label: 'E Money',
                        data: today.map(item => item.emoneypayment),
                        backgroundColor: '#0D61E2',
                        borderColor: '#0D61E2',
                        borderWidth: 1,
                        datalabels: { anchor: 'end', align: 'end' }
                    },
                    {
                        label: 'Flazz',
                        data: today.map(item => item.flazzpayment),
                        backgroundColor: '#FFB800',
                        borderColor: '#FFB800',
                        borderWidth: 1,
                        hidden: true,
                        datalabels: { anchor: 'end', align: 'end' }
                    },
                    {
                        label: 'Brizzi',
                        data: today.map(item => item.brizzipayment),
                        backgroundColor: '#FF4D4D',
                        borderColor: '#FF4D4D',
                        borderWidth: 1,
                        hidden: true,
                        datalabels: { anchor: 'end', align: 'end' }
                    },
                    {
                        label: 'Tap Cash',
                        data: today.map(item => item.tapcashpayment),
                        backgroundColor: '#00C9A7',
                        borderColor: '#00C9A7',
                        borderWidth: 1,
                        hidden: true,
                        datalabels: { anchor: 'end', align: 'end' }
                    },
                    {
                        label: 'Parkee',
                        data: today.map(item => item.parkeepayment),
                        backgroundColor: '#9C27B0',
                        borderColor: '#9C27B0',
                        borderWidth: 1,
                        hidden: true,
                        datalabels: { anchor: 'end', align: 'end' }
                    },
                    {
                        label: 'Cash',
                        data: today.map(item => item.cashpayment),
                        backgroundColor: '#795548',
                        borderColor: '#795548',
                        borderWidth: 1,
                        hidden: true
                    }
                ]
            };

            const barConfig = {
                type: 'bar',
                data: dataChart,
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: { color: '#000' }
                        },
                        datalabels: {
                            backgroundColor: ctx => ctx.dataset.backgroundColor,
                            borderRadius: 4,
                            color: 'white',
                            font: { weight: 'bold', size: 9 },
                            formatter: value => new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 0
                            }).format(value),
                            padding: 6,
                            offset: 8
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0, color: '#000' },
                            grace: '10%'
                        },
                        x: {
                            ticks: { color: '#000' }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            };

            const lineConfig = {
                type: 'line',
                data: dataChart,
                options: barConfig.options,
                plugins: [ChartDataLabels]
            };

            // Hancurkan chart lama jika ada
            if (window.dailyEPaymentBarChart) {
                window.dailyEPaymentBarChart.destroy();
            }
            if (window.dailyEPaymentLineChart) {
                window.dailyEPaymentLineChart.destroy();
            }

            const ctx = document.getElementById('dailyE-PaymentBar')?.getContext('2d');
            const ctxLine = document.getElementById('dailyE-PaymentLine')?.getContext('2d');

            if (ctx) {
                window.dailyEPaymentBarChart = new Chart(ctx, barConfig);
            }
            if (ctxLine) {
                window.dailyEPaymentLineChart = new Chart(ctxLine, lineConfig);
            }
        }
    });
}
    fetchDailyEPayment();
    setInterval(fetchDailyEPayment, 5000);

    function fetchDailyEPaymentSummary() {
    $.ajax({
        url: dailyEpaymentURL,
        method: 'GET',
        success: function(response) {
            const today = response.data[0].today[0];
            const yesterday = response.data[0].yesterday[0];
            const compare = response.table_data;

            const container = $('#daily-epayment-comparison');
            container.empty();

            const colClasses = ['col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-12'];

            compare.forEach((payment, index) => {
                const html = `
                    <div class="${colClasses[index]}">
                        <div class="dashboard-card">
                            <div class="card-title" style="color: #000 !important">${payment.method}</div>
                            <div class="d-flex align-items-baseline">
                                <h6 class="card-value" style="color: #000 !important">${formatRupiah(payment.today)}</h6>
                                <span class="ms-2" style="color: ${payment.color}">
                                    ${payment.percent_change}
                                    ${payment.direction}
                                </span>
                            </div>
                            <div class="yesterday" style="color: #000 !important">Yesterday: ${formatRupiah(payment.yesterday)}</div>
                        </div>
                    </div>
                `;
                container.append(html);
            });

            const rows = [
                { type: 'Emoney', yesterday: yesterday.emoneypayment, today: today.emoneypayment },
                { type: 'Flazz', yesterday: yesterday.flazzpayment, today: today.flazzpayment },
                { type: 'Brizzi', yesterday: yesterday.brizzipayment, today: today.brizzipayment },
                { type: 'Tap Cash', yesterday: yesterday.tapcashpayment, today: today.tapcashpayment },
                { type: 'Parkee', yesterday: yesterday.parkeepayment, today: today.parkeepayment },
                { type: 'Cash', yesterday: yesterday.cashpayment, today: today.cashpayment }
            ];

            const formattedRows = rows.map((item, index) => ({
                no: index + 1,
                payment: item.type,
                yesterday: formatRupiah(item.yesterday),
                today: formatRupiah(item.today)
            }));

            table.clear().rows.add(formattedRows).draw();

            $('#dailyE-Payment tfoot').html(`
                <tr>
                    <th colspan="2" style="text-align:left">All E-Payment</th>
                    <th id="totalEpaymentYesterday">${formatRupiah(yesterday.grandtotal)}</th>
                    <th id="totalEpaymentToday">${formatRupiah(today.grandtotal)}</th>
                </tr>
            `);
        }
    });
}
    fetchDailyEPaymentSummary();
    setInterval(fetchDailyEPaymentSummary, 5000);

    function fetchWeeklyEpayment() {
    $.ajax({
        url: weeklyEpaymentURL,
        method: 'GET',
        success: function(response) {
            const thisWeekEpayment = response.this_week.totals;
            const lastWeekEpayment = response.last_week.totals;

            const compare = response.table_data;
            const container = $('#weekly-epayment-comparison');
            container.empty();
            const colClasses = ['col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-12'];

            compare.forEach((payment, index) => {
                const html = `
                    <div class="${colClasses[index]}">
                        <div class="dashboard-card">
                            <div class="card-title" style="color: #000 !important">${payment.method}</div>
                            <div class="d-flex align-items-baseline">
                                <h6 class="card-value" style="color: #000 !important">${formatRupiah(payment.this_week)}</h6>
                                <span class="ms-2" style="color: ${payment.color}">
                                    ${payment.percent_change}${payment.direction}
                                </span>
                            </div>
                            <div class="yesterday"  style="color: #000 !important">Two Weeks Ago: ${formatRupiah(payment.last_week)}</div>
                        </div>
                    </div>
                `;
                container.append(html);
            });

            const rows = [
                { type: 'E Money', last_week: lastWeekEpayment.emoneypayment, this_week: thisWeekEpayment.emoneypayment },
                { type: 'Flazz', last_week: lastWeekEpayment.flazzpayment, this_week: thisWeekEpayment.flazzpayment },
                { type: 'Brizzi', last_week: lastWeekEpayment.brizzipayment, this_week: thisWeekEpayment.brizzipayment },
                { type: 'Tap Cash', last_week: lastWeekEpayment.tapcashpayment, this_week: thisWeekEpayment.tapcashpayment },
                { type: 'Parkee', last_week: lastWeekEpayment.parkeepayment, this_week: thisWeekEpayment.parkeepayment },
                { type: 'Cash', last_week: lastWeekEpayment.cashpayment, this_week: thisWeekEpayment.cashpayment }
            ];

            const formattedRows = rows.map((item, index) => ({
                no: index + 1,
                payment: item.type,
                last_week: formatRupiah(item.last_week),
                this_week: formatRupiah(item.this_week)
            }));

            weeklyTable.clear().rows.add(formattedRows).draw();

            $('#weeklyE-Payment tfoot').html(`
                <tr>
                    <th colspan="2" style="text-align:left">All E-Payment</th>
                    <th id="totalEpaymentLastWeek">${formatRupiah(lastWeekEpayment.allpayment)}</th>
                    <th id="totalEpaymentThisWeek">${formatRupiah(thisWeekEpayment.allpayment)}</th>
                </tr>
            `);

            const thisWeekEpaymentChart = response.this_week.data;
            const labels = thisWeekEpaymentChart.map(item => {
                const date = new Date(item.tanggal);
                return date.toLocaleDateString('en-GB', { day: '2-digit', month: 'short' });
            });

            const weeklyData = [
                thisWeekEpaymentChart.map(item => item.emoneypayment),
                thisWeekEpaymentChart.map(item => item.flazzpayment),
                thisWeekEpaymentChart.map(item => item.brizzipayment),
                thisWeekEpaymentChart.map(item => item.tapcashpayment),
                thisWeekEpaymentChart.map(item => item.parkeepayment),
                thisWeekEpaymentChart.map(item => item.cashpayment),
                thisWeekEpaymentChart.map(item => item.allpayment)
            ];

            const chartLabels = ['E Money', 'Flazz', 'Brizzi', 'Tap Cash', 'Parkee', 'Cash', 'All'];
            const chartColors = ['#0D61E2', '#FFB800', '#FF4D4D', '#00C9A7', '#9C27B0', '#795548', '#CCCCCC'];

            const createDatasets = (fill = true) => chartLabels.map((label, i) => ({
                label: label,
                data: weeklyData[i],
                backgroundColor: chartColors[i],
                borderColor: chartColors[i],
                borderWidth: 1,
                fill: fill,
                hidden: i !== 0,
                datalabels: { anchor: 'end', align: 'end' }
            }));

            // Destroy previous charts if needed
            if (window.weeklyBarChart) window.weeklyBarChart.destroy();
            if (window.weeklyLineChart) window.weeklyLineChart.destroy();

            const commonOptions = {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'top', labels: { color: '#000' } },
                    datalabels: {
                        backgroundColor: ctx => ctx.dataset.backgroundColor,
                        borderRadius: 4,
                        color: 'white',
                        font: { weight: 'bold', size: 9 },
                        formatter: value => new Intl.NumberFormat('id-ID', {
                            style: 'currency', currency: 'IDR', minimumFractionDigits: 0
                        }).format(value),
                        padding: 6,
                        offset: 8
                    }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0, color: '#000' }, grace: '10%' },
                    x: { ticks: { color: '#000' } }
                }
            };

            const ctxBar = document.getElementById('weeklyE-PaymentBar')?.getContext('2d');
            const ctxLine = document.getElementById('weeklyE-PaymentLine')?.getContext('2d');

            if (ctxBar) {
                window.weeklyBarChart = new Chart(ctxBar, {
                    type: 'bar',
                    data: { labels, datasets: createDatasets(true) },
                    options: commonOptions,
                    plugins: [ChartDataLabels]
                });
            }

            if (ctxLine) {
                window.weeklyLineChart = new Chart(ctxLine, {
                    type: 'line',
                    data: { labels, datasets: createDatasets(false) },
                    options: commonOptions,
                    plugins: [ChartDataLabels]
                });
            }
        }
    });
}

fetchWeeklyEpayment();
setInterval(fetchWeeklyEpayment, 5000);


   let monthlyBarChart, monthlyLineChart;

function fetchMonthlyEpayment() {
    $.ajax({
        url: monthlyEpaymentURL,
        method: 'GET',
        success: function(response) {
            const thisMonthEpayment = response.this_Month.totals;
            const lastMonthEpayment = response.last_Month.totals;

            const compare = response.table_data;
            const container = $('#monthly-epayment-comparison');
            container.empty();

            const colClasses = ['col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-12'];
            compare.forEach((payment, index) => {
                const html = `
                    <div class="${colClasses[index]}">
                        <div class="dashboard-card">
                            <div class="card-title" style="color: #000 !important">${payment.method}</div>
                            <div class="d-flex align-items-baseline">
                                <h6 class="card-value" style="color: #000 !important">${formatRupiah(payment.this_month)}</h6>
                                <span class="ms-2" style="color: ${payment.color}">
                                    ${payment.percent_change} ${payment.direction}
                                </span>
                            </div>
                            <div class="yesterday" style="color: #000 !important">Two Months Ago: ${formatRupiah(payment.last_month)}</div>
                        </div>
                    </div>
                `;
                container.append(html);
            });

            const rows = [
                { type: 'E Money', last_month: lastMonthEpayment.emoneypayment, this_month: thisMonthEpayment.emoneypayment },
                { type: 'Flazz', last_month: lastMonthEpayment.flazzpayment, this_month: thisMonthEpayment.flazzpayment },
                { type: 'Brizzi', last_month: lastMonthEpayment.brizzipayment, this_month: thisMonthEpayment.brizzipayment },
                { type: 'Tap Cash', last_month: lastMonthEpayment.tapcashpayment, this_month: thisMonthEpayment.tapcashpayment },
                { type: 'Parkee', last_month: lastMonthEpayment.parkeepayment, this_month: thisMonthEpayment.parkeepayment },
                { type: 'Cash', last_month: lastMonthEpayment.cashpayment, this_month: thisMonthEpayment.cashpayment }
            ];

            const formattedRows = rows.map((item, index) => ({
                no: index + 1,
                payment: item.type,
                last_month: formatRupiah(item.last_month),
                this_month: formatRupiah(item.this_month)
            }));

            monthlyTable.clear().rows.add(formattedRows).draw();

            $('#monthlyE-Payment tfoot').html(`
                <tr>
                    <th colspan="2" style="text-align:left">All E-Payment</th>
                    <th id="totalEpaymentLastMonth">${formatRupiah(lastMonthEpayment.allpayment)}</th>
                    <th id="totalEpaymentThisMonth">${formatRupiah(thisMonthEpayment.allpayment)}</th>
                </tr>
            `);

            const thisMonthEpaymentChart = response.this_Month.weekly_totals;
            const labels = Object.keys(thisMonthEpaymentChart);

            const monthlyData = [
                Object.values(thisMonthEpaymentChart).map(item => item.emoneypayment),
                Object.values(thisMonthEpaymentChart).map(item => item.flazzpayment),
                Object.values(thisMonthEpaymentChart).map(item => item.brizzipayment),
                Object.values(thisMonthEpaymentChart).map(item => item.tapcashpayment),
                Object.values(thisMonthEpaymentChart).map(item => item.parkeepayment),
                Object.values(thisMonthEpaymentChart).map(item => item.cashpayment),
                Object.values(thisMonthEpaymentChart).map(item => item.allpayment)
            ];

            const chartLabels = ['E Money', 'Flazz', 'Brizzi', 'Tap Cash', 'Parkee', 'Cash', 'All'];
            const chartColors = ['#0D61E2', '#FFB800', '#FF4D4D', '#00C9A7', '#9C27B0', '#795548', '#CCCCCC'];

            const buildDatasets = (fill = true) => chartLabels.map((label, i) => ({
                label,
                data: monthlyData[i],
                backgroundColor: chartColors[i],
                borderColor: chartColors[i],
                borderWidth: 1,
                hidden: i !== 0,
                fill,
                datalabels: { anchor: 'end', align: 'end' }
            }));

            const commonOptions = {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'top', labels: { color: '#000' } },
                    datalabels: {
                        backgroundColor: ctx => ctx.dataset.backgroundColor,
                        borderRadius: 4,
                        color: 'white',
                        font: { weight: 'bold', size: 9 },
                        formatter: value => new Intl.NumberFormat('id-ID', {
                            style: 'currency', currency: 'IDR', minimumFractionDigits: 0
                        }).format(value),
                        padding: 6,
                        offset: 8
                    }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0, color: '#000' }, grace: '10%' },
                    x: { ticks: { color: '#000' } }
                }
            };

            // Destroy previous chart instances if they exist
            if (monthlyBarChart) monthlyBarChart.destroy();
            if (monthlyLineChart) monthlyLineChart.destroy();

            const ctxBar = document.getElementById('monthlyE-PaymentBar')?.getContext('2d');
            const ctxLine = document.getElementById('monthlyE-PaymentLine')?.getContext('2d');

            if (ctxBar) {
                monthlyBarChart = new Chart(ctxBar, {
                    type: 'bar',
                    data: { labels, datasets: buildDatasets(true) },
                    options: commonOptions,
                    plugins: [ChartDataLabels]
                });
            }

            if (ctxLine) {
                monthlyLineChart = new Chart(ctxLine, {
                    type: 'line',
                    data: { labels, datasets: buildDatasets(false) },
                    options: commonOptions,
                    plugins: [ChartDataLabels]
                });
            }
        }
    });
}

// ✅ Panggilan pertama
fetchMonthlyEpayment();

// 🔁 Refresh otomatis tiap 5 detik
setInterval(fetchMonthlyEpayment, 5000);

});