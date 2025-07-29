$(document).ready(function() {
    const formatRupiah = (number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    };
    const table = $('#dailyIncome').DataTable({
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
                data: 'vehicle'
            },
            {
                data: 'yesterday'
            },
            {
                data: 'today'
            }
        ]
    });
    const weeklyIncomeTable = $('#weeklyIncome').DataTable({
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
                data: 'vehicle'
            },
            {
                data: 'last_week'
            },
            {
                data: 'this_week'
            }
        ]
    });
    const monthlyIncomeTable = $('#monthlyIncome').DataTable({
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
                data: 'vehicle'
            },
            {
                data: 'last_month'
            },
            {
                data: 'this_month'
            }
        ]
    });

    function loadDailyIncome() {
    $.ajax({
        url: dailyIncomeURL,
        method: 'GET',
        success: function(response) {
            const today = response.data[0].today[0];
            const yesterday = response.data[0].yesterday[0];
            const compare = response.data[0].table_data;

            const container = $('#daily-income-comparison');
            container.empty();

            const colClasses = ['col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6'];
            compare.forEach((vehicle, index) => {
                const html = `
                    <div class="${colClasses[index]}">
                        <div class="dashboard-card">
                            <div class="card-title" style="color: #000 !important;">${vehicle.vehicle}</div>
                            <div class="d-flex align-items-baseline">
                                <h6 class="card-value" style="color: #000 !important;">${formatRupiah(vehicle.today)}</h6>
                                <span class="ms-2" style="color: ${vehicle.color}; font-size:12px;">
                                    ${vehicle.percent_change}
                                    ${vehicle.direction}
                                </span>
                            </div>
                            <div class="yesterday" style="color: #000 !important;">Yesterday: ${formatRupiah(vehicle.yesterday)}</div>
                        </div>
                    </div>
                `;
                container.append(html);
            });

            const rows = [
                { type: 'Car', yesterday: yesterday.carincome, today: today.carincome },
                { type: 'Motorbike', yesterday: yesterday.motorbikeincome, today: today.motorbikeincome },
                { type: 'Truck', yesterday: yesterday.truckincome, today: today.truckincome },
                { type: 'Taxi', yesterday: yesterday.taxiincome, today: today.taxiincome },
                { type: 'Lost Ticket', yesterday: yesterday.ticketincome, today: today.ticketincome },
                { type: 'Other', yesterday: yesterday.otherincome, today: today.otherincome },
            ];

            const formattedRows = rows.map((item, index) => ({
                no: index + 1,
                vehicle: item.type,
                yesterday: formatRupiah(item.yesterday),
                today: formatRupiah(item.today)
            }));

            table.clear().rows.add(formattedRows).draw();

            $('#dailyIncome tfoot').html(`
                <tr>
                    <th colspan="2" style="text-align:left">All Vehicle</th>
                    <th id="totalYesterday">${formatRupiah(yesterday.grandtotal)}</th>
                    <th id="totalToday">${formatRupiah(today.grandtotal)}</th>
                </tr>
                <tr>
                    <th colspan="2" style="text-align:left">All Sticker Income</th>
                    <th id="totalYesterday">${formatRupiah(yesterday.stickerincome)}</th>
                    <th id="totalToday">${formatRupiah(today.stickerincome)}</th>
                </tr>
            `);

            const donutData = {
                labels: ['Car', 'Motorbike', 'Truck', 'Taxi'],
                datasets: [{
                    label: 'Income by Vehicle',
                    data: [today.carincome, today.motorbikeincome, today.truckincome, today.taxiincome],
                    backgroundColor: ['#0D61E2', '#EF0F51', '#FFCD56', '#32CD7D'],
                    borderColor: ['#0D61E2', '#EF0F51', '#FFCD56', '#32CD7D'],
                    borderWidth: 1
                }]
            };

            const donutConfig = {
                type: 'doughnut',
                data: donutData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: '#000',
                            }
                        },
                        datalabels: {
                            color: '#000',
                            borderColor: '#fff',
                            backgroundColor: 'rgba(255, 255, 255, 0.63)',
                            formatter: function(value) {
                                return new Intl.NumberFormat('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR',
                                    minimumFractionDigits: 0
                                }).format(value);
                            },
                            font: { weight: 'bold' },
                            padding: 6,
                            borderRadius: 25,
                            borderWidth: 3,
                        }
                    }
                },
                plugins: [ChartDataLabels]
            };

            const ctx = document.getElementById('dailyIncomedonut')?.getContext('2d');
            if (ctx) {
                if (window.dailyIncomeChart) {
                    window.dailyIncomeChart.destroy();
                }
                window.dailyIncomeChart = new Chart(ctx, donutConfig);
            }
        }
    });
}

// Pertama kali load
loadDailyIncome();

// Refresh setiap 5 detik (5000 ms)
setInterval(loadDailyIncome, 5000);

   $.ajax({
        url: weeklyIncomeURL,
        method: 'GET',
        success: function(response) {
            const thisWeekIncome = response.this_week.totals;
            const lastWeekIncome = response.last_week.totals;
            const compare = response.table_data;

            // console.log(compare);

            const container = $('#weekly-income-comparison');

        container.empty(); // Biar gak dobel kalau dipanggil ulang
        const colClasses = ['col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6'];
        compare.forEach((vehicle, index) => {
            const html = `
                <div class="${colClasses[index]}">
                    <div class="dashboard-card">
                        <div class="card-title" style="color: #000 !important;">${vehicle.vehicle}</div>
                        <div class="d-flex align-items-baseline">
                            <h6 class="card-value" style="color: #000 !important;">${formatRupiah(vehicle.this_week)}</h6>
                            <span class="ms-2" style="color: ${vehicle.color}; font-size:12px;">
                                ${vehicle.percent_change}
                                ${vehicle.direction}
                            </span>
                        </div>
                        <div class="yesterday" style="color: #000 !important;">Two Weeks Ago: ${formatRupiah(vehicle.last_week)}</div>
                    </div>
                </div>
            `;
            container.append(html);
        });
            const rows = [{
                    type: 'Car',
                    thisWeekIncome: thisWeekIncome.carincome,
                    lastWeekIncome: lastWeekIncome.carincome
                },
                {
                    type: 'Motorbike',
                    thisWeekIncome: thisWeekIncome.motorbikeincome,
                    lastWeekIncome: lastWeekIncome.motorbikeincome
                },
                {
                    type: 'Truck',
                    thisWeekIncome: thisWeekIncome.truckincome,
                    lastWeekIncome: lastWeekIncome.truckincome
                },
                {
                    type: 'Taxi',
                    thisWeekIncome: thisWeekIncome.taxiincome,
                    lastWeekIncome: lastWeekIncome.taxiincome
                },
                {
                    type: 'lost ticket',
                    thisWeekIncome: thisWeekIncome.ticketincome,
                    lastWeekIncome: lastWeekIncome.ticketincome
                },
                {
                    type: 'Other',
                    thisWeekIncome: thisWeekIncome.otherincome,
                    lastWeekIncome: lastWeekIncome.otherincome
                },
            ];

            const formattedWeeklyRows = rows.map((item, index) => ({
                no: index + 1,
                vehicle: item.type,
                this_week: formatRupiah(item.thisWeekIncome),
                last_week: formatRupiah(item.lastWeekIncome)
            }));


            weeklyIncomeTable.rows.add(formattedWeeklyRows).draw();

            $('#weeklyIncome tfoot').html(`
                    <tr>
                        <th colspan="2" style="text-align:left">All Casual Income</th>
                        <th id="totallastWeekIncome" style="font-size:12px;">${formatRupiah(lastWeekIncome.vehicleincome)}</th>
                        <th id="totalthisWeekIncome" style="font-size:12px;">${formatRupiah(thisWeekIncome.vehicleincome)}</th>
                    </tr>
                    <tr>
                        <th colspan="2" style="text-align:left">All Sticker Income</th>
                        <th id="totallastWeekIncome" style="font-size:12px;">${formatRupiah(lastWeekIncome.stickerincome)}</th>
                        <th id="totalthisWeekIncome" style="font-size:12px;">${formatRupiah(thisWeekIncome.stickerincome)}</th>
                    </tr>
                `);

            const thisWeekIncomeBar = response.this_week.data;


            const labels = thisWeekIncomeBar.map(item => {
                const date = new Date(item.tanggal);
                return date.toLocaleDateString('en-GB', {
                    day: '2-digit',
                    month: 'short'
                }); // hasilnya: 14 Apr, 15 Apr, dst.
            });

            const casualData = [
                thisWeekIncomeBar.map(item => item.carincome),
                thisWeekIncomeBar.map(item => item.motorbikeincome),
                thisWeekIncomeBar.map(item => item.taxiincome),
                thisWeekIncomeBar.map(item => item.truckincome),
                thisWeekIncomeBar.map(item => item.otherincome),
                thisWeekIncomeBar.map(item => item.vehicleincome),
                thisWeekIncomeBar.map(item => item.lostticketincome),
            ];

            const barData = {
                labels: labels,
                datasets: [{
                        label: 'Car',
                        data: casualData[0],
                        backgroundColor: '#0D61E2',
                        borderColor: '#0D61E2',
                        borderWidth: 1,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    }, {
                        label: 'Motorbike',
                        data: casualData[1],
                        backgroundColor: '#E60045',
                        borderColor: '#E60045',
                        borderWidth: 1,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        },
                        hidden: true,
                    }, {
                        label: 'Truck',
                        data: casualData[2],
                        backgroundColor: '#FFCD56',
                        borderColor: '#FFCD56',
                        borderWidth: 1,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        },
                        hidden: true,
                    }, {
                        label: 'Taxi',
                        data: casualData[3],
                        backgroundColor: '#32CD7D',
                        borderColor: '#32CD7D',
                        borderWidth: 1,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        },
                        hidden: true,
                    }, {
                        label: 'Vehicle',
                        data: casualData[5],
                        backgroundColor: '#E69500',
                        borderColor: '#E69500',
                        borderWidth: 1,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        },
                        hidden: true,
                    }

                ]
            };

            const barConfig = {
                type: 'bar',
                data: barData,
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: '#000',

                            }
                        },

                        datalabels: {
                            backgroundColor: (context) => context.dataset
                                .backgroundColor,
                            borderRadius: 4,
                            color: 'white',
                            font: {
                                weight: 'bold'
                            },
                            formatter: function(value, context) {
                                return new Intl.NumberFormat('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR',
                                    minimumFractionDigits: 0
                                }).format(value);
                            },
                            padding: 6,
                            offset: 8
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                               color: '#000',
                            },
                            grace: '10%'
                        },
                        x: {
                            ticks: {
                               color: '#000',
                            }
                        }
                    }
                },
                plugins: [
                    ChartDataLabels
                ] // make sure to include this if you're using CDN
            };

            const lineData = {
                labels: labels,
                datasets: [{
                        label: 'Car',
                        data: casualData[0],
                        backgroundColor: '#0D61E2',
                        borderColor: '#0D61E2',
                        borderWidth: 3,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        },
                        tension: 0.5,

                    }, {
                        label: 'Motorbike',
                        data: casualData[1],
                        backgroundColor: '#E60045',
                        borderColor: '#E60045',
                        borderWidth: 3,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        },
                        tension: 0.5,
                        hidden: true,
                    }, {
                        label: 'Truck',
                        data: casualData[2],
                        backgroundColor: '#FFCD56',
                        borderColor: '#FFCD56',
                        borderWidth: 3,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        },
                        tension: 0.5,
                        hidden: true,
                    }, {
                        label: 'Taxi',
                        data: casualData[3],
                        backgroundColor: '#32CD7D',
                        borderColor: '#32CD7D',
                        borderWidth: 3,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        },
                        tension: 0.5,
                        hidden: true,

                    }, {
                        label: 'Vehicle',
                        data: casualData[5],
                        backgroundColor: '#E69500',
                        borderColor: '#E69500',
                        borderWidth: 3,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        },
                        tension: 0.5,
                        hidden: true,
                    }

                ]
            };

            const lineConfig = {
                type: 'line',
                data: lineData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: '#000',

                            }
                        },

                        datalabels: {
                            backgroundColor: (context) => context.dataset
                                .backgroundColor,
                            borderRadius: 4,
                            color: 'white',
                            font: {
                                weight: 'bold'
                            },
                            formatter: function(value, context) {
                                return new Intl.NumberFormat('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR',
                                    minimumFractionDigits: 0
                                }).format(value);
                            },
                            padding: 3,
                            offset: 4
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                               color: '#000',
                            },
                            grace: '10%'
                        },
                        x: {
                            ticks: {
                               color: '#000',
                            }
                        }
                    }
                },
                plugins: [
                    ChartDataLabels
                ] // make sure to include this if you're using CDN
            }

            const ctxLine = document.getElementById('weeklyIncomeLine')?.getContext('2d');
            if (ctxLine) {
                new Chart(ctxLine, lineConfig);
            }

            const ctx = document.getElementById('weeklyIncomeBar')?.getContext('2d');
            if (ctx) {
                new Chart(ctx, barConfig);
            }
        }
    });
    function fetchMonthlyIncome() {
    $.ajax({
        url: monthlyIncomeURL,
        method: 'GET',
        success: function(response) {

            const thisMonthIncome = response.this_Month.totals;
            const lastMonthIncome = response.last_Month.totals;
            const compare = response.table_data;

            const container = $('#monthly-income-comparison');
            container.empty();

            const colClasses = Array(compare.length).fill('col-md-6');
            compare.forEach((vehicle, index) => {
                const html = `
                    <div class="${colClasses[index]}">
                        <div class="dashboard-card">
                            <div class="card-title" style="color: #000 !important;">${vehicle.vehicle}</div>
                            <div class="d-flex align-items-baseline">
                                <h6 class="card-value" style="color: #000 !important;">${formatRupiah(vehicle.last_month)}</h6>
                                <span class="ms-2" style="color: ${vehicle.color}; font-size:12px;">
                                    ${vehicle.percent_change}
                                    ${vehicle.direction}
                                </span>
                            </div>
                            <div class="yesterday" style="color: #000 !important;">Two Months Ago: ${formatRupiah(vehicle.last_month)}</div>
                        </div>
                    </div>
                `;
                container.append(html);
            });

            const rows = [
                { type: 'Car', thisMonthIncome: thisMonthIncome.carincome, lastMonthIncome: lastMonthIncome.carincome },
                { type: 'Motorbike', thisMonthIncome: thisMonthIncome.motorbikeincome, lastMonthIncome: lastMonthIncome.motorbikeincome },
                { type: 'Truck', thisMonthIncome: thisMonthIncome.truckincome, lastMonthIncome: lastMonthIncome.truckincome },
                { type: 'Taxi', thisMonthIncome: thisMonthIncome.taxiincome, lastMonthIncome: lastMonthIncome.taxiincome },
                { type: 'Lost Ticket', thisMonthIncome: thisMonthIncome.ticketincome, lastMonthIncome: lastMonthIncome.ticketincome },
                { type: 'Other', thisMonthIncome: thisMonthIncome.otherincome, lastMonthIncome: lastMonthIncome.otherincome },
            ];

            const formattedMonthlyRows = rows.map((item, index) => ({
                no: index + 1,
                vehicle: item.type,
                this_month: formatRupiah(item.thisMonthIncome),
                last_month: formatRupiah(item.lastMonthIncome)
            }));

            // Clear and reload data table
            monthlyIncomeTable.clear().rows.add(formattedMonthlyRows).draw();

            $('#monthlyIncome tfoot').html(`
                <tr>
                    <th colspan="2" style="text-align:left">All Casual Income</th>
                    <th style="font-size:12px;">${formatRupiah(lastMonthIncome.vehicleincome)}</th>
                    <th style="font-size:12px;">${formatRupiah(thisMonthIncome.vehicleincome)}</th>
                </tr>
                <tr>
                    <th colspan="2" style="text-align:left">All Sticker Income</th>
                    <th style="font-size:12px;">${formatRupiah(lastMonthIncome.stickerincome)}</th>
                    <th style="font-size:12px;">${formatRupiah(thisMonthIncome.stickerincome)}</th>
                </tr>
            `);

            const thisMonthIncomeChart = response.this_Month.weekly_totals;
            const labels = Object.keys(thisMonthIncomeChart);
            const totalCars = labels.map(label => thisMonthIncomeChart[label].carincome);
            const totalMotorbikes = labels.map(label => thisMonthIncomeChart[label].motorbikeincome);
            const totalTrucks = labels.map(label => thisMonthIncomeChart[label].truckincome);
            const totalTaxis = labels.map(label => thisMonthIncomeChart[label].taxiincome);
            const totalVehicles = labels.map(label => thisMonthIncomeChart[label].vehicleincome);

            const barData = {
                labels,
                datasets: [
                    { label: 'Car', data: totalCars, backgroundColor: '#51AA20', borderColor: '#51AA20', borderWidth: 1 },
                    { label: 'Motorbike', data: totalMotorbikes, backgroundColor: '#DB6715', borderColor: '#DB6715', borderWidth: 1, hidden: true },
                    { label: 'Truck', data: totalTrucks, backgroundColor: '#8D60ED', borderColor: '#8D60ED', borderWidth: 1, hidden: true },
                    { label: 'Taxi', data: totalTaxis, backgroundColor: '#C46EA6', borderColor: '#C46EA6', borderWidth: 1, hidden: true },
                    { label: 'Vehicle', data: totalVehicles, backgroundColor: '#D3D6DD', borderColor: '#D3D6DD', borderWidth: 1, hidden: true }
                ]
            };

            const lineData = {
                labels,
                datasets: barData.datasets.map(ds => ({
                    ...ds,
                    borderWidth: 3,
                    tension: 0.5,
                    fill: false
                }))
            };

            const barOptions = {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'top', labels: { color: '#000' } },
                    datalabels: {
                        backgroundColor: ctx => ctx.dataset.backgroundColor,
                        borderRadius: 4,
                        color: 'white',
                        font: { weight: 'bold' },
                        formatter: val => formatRupiah(val),
                        padding: 6,
                        offset: 8
                    }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { color: '#000', precision: 0 }, grace: '10%' },
                    x: { ticks: { color: '#000' } }
                }
            };

            const lineOptions = {
                responsive: true,
                plugins: {
                    legend: { position: 'top', labels: { color: '#000' } },
                    datalabels: {
                        backgroundColor: ctx => ctx.dataset.backgroundColor,
                        borderRadius: 4,
                        color: 'white',
                        font: { weight: 'bold' },
                        formatter: val => formatRupiah(val),
                        padding: 3,
                        offset: 4
                    }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { color: '#000', precision: 0 }, grace: '10%' },
                    x: { ticks: { color: '#000' } }
                }
            };

            const ctxBar = document.getElementById('monthlyIncomeBar')?.getContext('2d');
            const ctxLine = document.getElementById('monthlyIncomeLine')?.getContext('2d');

            // Destroy existing charts if exist
            if (window.monthlyBarChart) window.monthlyBarChart.destroy();
            if (window.monthlyLineChart) window.monthlyLineChart.destroy();

            // Create new charts
            if (ctxBar) window.monthlyBarChart = new Chart(ctxBar, { type: 'bar', data: barData, options: barOptions, plugins: [ChartDataLabels] });
            if (ctxLine) window.monthlyLineChart = new Chart(ctxLine, { type: 'line', data: lineData, options: lineOptions, plugins: [ChartDataLabels] });
        }
    });
}

// Jalankan saat halaman load
fetchMonthlyIncome();

// Jalankan setiap 5 detik
setInterval(fetchMonthlyIncome, 5000);

});