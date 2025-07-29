$(document).ready(function() {
    function formatQuantity(quantity) {
        return new Intl.NumberFormat().format(quantity);
    }
        const table = $('#dailyQuantity').DataTable({
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
                    data: 'type'
                },
                {
                    data: 'yesterday'
                },
                {
                    data: 'today'
                }
            ]
        });
        const weeklyTable = $('#weeklyQuantity').DataTable({
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

        const monthlyTable = $('#monthlyQuantity').DataTable({
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


        const weeklyPassTable = $('#weeklyQuantityPass').DataTable({
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

        const monthlyPassTable = $('#monthlyQuantityPass').DataTable({
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

        function fetchDailyTransaction() {
    $.ajax({
        url: dailyTransactionURL,
        method: 'GET',
        success: function(response) {
            const today = response.data[0].today[0];
            const yesterday = response.data[0].yesterday[0];
            const compare = response.vehicle_comparison;
            const container = $('#daily-transaction-comparison');
            container.empty();

            const colClasses = ['col-md-6', 'col-md-6', 'col-md-12'];
            compare.forEach((vehicle, index) => {
                const col = colClasses[index] || 'col-md-4';
                const html = `
                    <div class="${col}">
                        <div class="dashboard-card">
                            <div class="card-title" style="color: #000 !important;">${vehicle.type}</div>
                            <div class="d-flex align-items-baseline">
                                <h2 class="card-value" style="color: #000 !important;">${formatQuantity(vehicle.today)}</h2>
                                <span class="ms-2" style="color: ${vehicle.color}">
                                    ${vehicle.percent_change} ${vehicle.direction}
                                </span>
                            </div>
                            <div class="yesterday" style="color: #000 !important;">Yesterday: ${formatQuantity(vehicle.yesterday)}</div>
                        </div>
                    </div>
                `;
                container.append(html);
            });

            const rows = [
                { type: 'Total Casual', yesterday: formatQuantity(yesterday.grandcasual), today: formatQuantity(today.grandcasual) },
                { type: 'Total Pass', yesterday: formatQuantity(yesterday.grandpass), today: formatQuantity(today.grandpass) }
            ];

            const formattedRows = rows.map((item, index) => ({
                no: index + 1,
                type: item.type,
                yesterday: item.yesterday,
                today: item.today
            }));

            table.clear().rows.add(formattedRows).draw();

            $('#dailyQuantity tfoot').html(`
                <tr>
                    <th colspan="2" style="text-align:left">All Vehicle</th>
                    <th id="totalYesterday">${formatQuantity(yesterday.grandtotal)}</th>
                    <th id="totalToday">${formatQuantity(today.grandtotal)}</th>
                </tr>
            `);

            const labels = ['Car', 'Motorbike', 'Truck', 'Taxi'];
            const casualData = [
                today.carcasual,
                today.motorbikecasual,
                today.truckcasual,
                today.taxicasual
            ];
            const passData = [
                today.carpass,
                today.motorbikepass,
                today.truckpass,
                today.taxipass
            ];

            // Destroy chart sebelumnya jika ada
            if (window.dailyBarChart) window.dailyBarChart.destroy();
            if (window.dailyLineChart) window.dailyLineChart.destroy();

            const barConfig = {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Casual',
                            data: casualData,
                            backgroundColor: 'rgba(255, 0, 0, 1)',
                            borderColor: 'rgba(255, 0, 0, 1)',
                            borderWidth: 1,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            }
                        },
                        {
                            label: 'Pass',
                            data: passData,
                            backgroundColor: 'rgba(0, 132, 247, 1)',
                            borderColor: 'rgba(0, 132, 247, 1)',
                            borderWidth: 1,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            }
                        }
                    ]
                },
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
                            font: { weight: 'bold' },
                            formatter: Math.round,
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
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Casual',
                            data: casualData,
                            backgroundColor: 'rgba(255, 0, 0, 1)',
                            borderColor: 'rgba(255, 0, 0, 1)',
                            tension: 0.5,
                            borderWidth: 3,
                            datalabels: { anchor: 'end', align: 'end' }
                        },
                        {
                            label: 'Pass',
                            data: passData,
                            backgroundColor: 'rgba(0, 132, 247, 1)',
                            borderColor: 'rgba(0, 132, 247, 1)',
                            tension: 0.5,
                            borderWidth: 3,
                            datalabels: { anchor: 'end', align: 'end' }
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: { color: '#000' }
                        },
                        datalabels: {
                            backgroundColor: ctx => ctx.dataset.backgroundColor,
                            borderRadius: 4,
                            color: 'white',
                            font: { weight: 'bold' },
                            formatter: Math.round,
                            padding: 3,
                            offset: 4
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

            const ctxBar = document.getElementById('dailyQuantityBar')?.getContext('2d');
            const ctxLine = document.getElementById('dailyQuantityLine')?.getContext('2d');

            if (ctxBar) window.dailyBarChart = new Chart(ctxBar, barConfig);
            if (ctxLine) window.dailyLineChart = new Chart(ctxLine, lineConfig);
        }
    });
}
    fetchDailyTransaction();
        setInterval(fetchDailyTransaction,5000); 

        function fetchWeeklyTransactionData() {
    $.ajax({
        url: weeklyTransactionURL,
        method: 'GET',
        success: function(response) {
            // === Extract Data ===
            const thisWeek = response.this_week.totals.casual;
            const lastWeek = response.last_week.totals.casual;
            const thisWeekPass = response.this_week.totals.pass;
            const lastWeekPass = response.last_week.totals.pass;
            const thisWeekChart = response.this_week.casual;
            const thisWeekPassChart = response.this_week.pass;
            const compare = response.vehicle_comparison;

            // === Update Card UI ===
            const container = $('#weekly-transaction-comparison').empty();
            const colClasses = ['col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-12'];
            compare.forEach((vehicle, index) => {
                container.append(`
                    <div class="${colClasses[index] || 'col-md-4'} d-flex">
                        <div class="dashboard-card flex-fill">
                            <div class="card-title" style="color: #000 !important;">${vehicle.vehicle}</div>
                            <div class="d-flex align-items-baseline">
                                <h2 class="card-value" style="color: #000 !important;">${formatQuantity(vehicle.this_week)}</h2>
                                <span class="ms-2" style="color: ${vehicle.color}">${vehicle.percent_change} ${vehicle.direction}</span>
                            </div>
                            <div class="yesterday" style="color: #000 !important;">Two Weeks Ago: ${formatQuantity(vehicle.two_weeks_ago)}</div>
                        </div>
                    </div>
                `);
            });

            // === Update Weekly Table ===
            const casualRows = ['car', 'motorbike', 'truck', 'taxi'].map((type, i) => ({
                no: i + 1,
                vehicle: type.charAt(0).toUpperCase() + type.slice(1),
                this_week: formatQuantity(thisWeek[`total_${type}`]),
                last_week: formatQuantity(lastWeek[`total_${type}`])
            }));
            weeklyTable.clear().rows.add(casualRows).draw();

            const passRows = ['car', 'motorbike', 'truck', 'taxi'].map((type, i) => ({
                no: i + 1,
                vehicle: type.charAt(0).toUpperCase() + type.slice(1),
                this_week: formatQuantity(thisWeekPass[`total_${type}`]),
                last_week: formatQuantity(lastWeekPass[`total_${type}`])
            }));
            weeklyPassTable.clear().rows.add(passRows).draw();

            // === Footer Total ===
            $('#weeklyQuantity tfoot').html(`
                <tr>
                    <th colspan="2" style="text-align:left">All Vehicle</th>
                    <th id="totalLastWeek">${formatQuantity(lastWeek.total_vehicle)}</th>
                    <th id="totalThisWeek">${formatQuantity(thisWeek.total_vehicle)}</th>
                </tr>
            `);

            // === Chart Labels ===
            const labels = thisWeekChart.map(item => {
                const date = new Date(item.tanggal);
                return date.toLocaleDateString('en-GB', { day: '2-digit', month: 'short' });
            });

            // === Build Dataset Generator Function ===
            const buildDataset = (data, type = 'bar') => {
                const colors = ['#0D61E2', '#E60045', '#FFCD56', '#32CD7D', '#E69500'];
                const keys = ['car', 'motorbike', 'truck', 'taxi', 'vehicle'];
                return keys.map((key, i) => ({
                    label: key.charAt(0).toUpperCase() + key.slice(1),
                    data: data[i],
                    backgroundColor: colors[i],
                    borderColor: colors[i],
                    borderWidth: type === 'bar' ? 1 : 3,
                    datalabels: { anchor: 'end', align: 'end' },
                    tension: 0.5,
                    hidden: i !== 0 // hanya tampilkan satu dataset default
                }));
            };

            const casualData = [
                thisWeekChart.map(item => item.carcasual),
                thisWeekChart.map(item => item.motorbikecasual),
                thisWeekChart.map(item => item.truckcasual),
                thisWeekChart.map(item => item.taxicasual),
                thisWeekChart.map(item => item.vehiclecasual)
            ];

            const passData = [
                thisWeekPassChart.map(item => item.carpass),
                thisWeekPassChart.map(item => item.motorbikepass),
                thisWeekPassChart.map(item => item.truckpass),
                thisWeekPassChart.map(item => item.taxipass),
                thisWeekPassChart.map(item => item.vehiclepass)
            ];

            // === Chart Config Base ===
            const baseOptions = {
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
                        font: { weight: 'bold' },
                        formatter: Math.round,
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
            };

            const buildChart = (canvasId, type, data) => {
                const ctx = document.getElementById(canvasId)?.getContext('2d');
                if (ctx) {
                    new Chart(ctx, {
                        type,
                        data: {
                            labels,
                            datasets: buildDataset(data, type)
                        },
                        options: baseOptions,
                        plugins: [ChartDataLabels]
                    });
                }
            };

            // === Render All 4 Charts ===
            buildChart('weeklyQuantityBar', 'bar', casualData);
            buildChart('weeklyPassQuantityBar', 'bar', passData);
            buildChart('weeklyQuantityLine', 'line', casualData);
            buildChart('weeklyPassQuantityLine', 'line', passData);
        }
    });
}


fetchWeeklyTransactionData();

setInterval(fetchWeeklyTransactionData, 5000); // 5 seconds


        function loadMonthlyTransactionData() {
    $.ajax({
        url: monthlyTransactionURL,
        method: 'GET',
        success: function(response) {
            const thisMonth = response.this_month.totals.casual;
            const lastMonth = response.last_month.totals.casual;
            const thisMonthPass = response.this_month.totals.pass;
            const lastMonthPass = response.last_month.totals.pass;
            const compare = response.vehicle_comparison;

            // Render card comparison
            const container = $('#monthly-transaction-comparison').empty();
            const colClasses = ['col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-12'];
            compare.forEach((vehicle, index) => {
                container.append(`
                    <div class="${colClasses[index]}">
                        <div class="dashboard-card">
                            <div class="card-title" style="color: #000 !important;">${vehicle.vehicle}</div>
                            <div class="d-flex align-items-baseline">
                                <h2 class="card-value" style="color: #000 !important;">${formatQuantity(vehicle.this_month)}</h2>
                                <span class="ms-2" style="color: ${vehicle.color}">
                                    ${vehicle.percent_change} ${vehicle.direction}
                                </span>
                            </div>
                            <div class="yesterday" style="color: #000 !important;">Two Months Ago: ${formatQuantity(vehicle.two_months_ago)}</div>
                        </div>
                    </div>
                `);
            });

            // Table for casual
            const casualRows = ['car', 'motorbike', 'truck', 'taxi'].map((type, index) => ({
                no: index + 1,
                vehicle: type.charAt(0).toUpperCase() + type.slice(1),
                this_month: formatQuantity(thisMonth[`total_${type}`]),
                last_month: formatQuantity(lastMonth[`total_${type}`]),
            }));
            monthlyTable.clear().rows.add(casualRows).draw();

            // Table for pass
            const passRows = ['car', 'motorbike', 'truck', 'taxi'].map((type, index) => ({
                no: index + 1,
                vehicle: type.charAt(0).toUpperCase() + type.slice(1),
                this_month: formatQuantity(thisMonthPass[`total_${type}`]),
                last_month: formatQuantity(lastMonthPass[`total_${type}`]),
            }));
            monthlyPassTable.clear().rows.add(passRows).draw();

            // Render footer
            $('#monthlyQuantity tfoot').html(`
                <tr>
                    <th colspan="2" style="text-align:left">All Vehicle</th>
                    <th id="totalLastMonth">${formatQuantity(lastMonth.total_vehicle)}</th>
                    <th id="totalThisMonth">${formatQuantity(thisMonth.total_vehicle)}</th>
                </tr>
            `);

            // Chart data prep
            const labels = Object.keys(response.this_month.weekly_totals.casual);
            const extract = (data, key) => labels.map(l => data[l][key]);
            const chartTypes = ['car', 'motorbike', 'truck', 'taxi', 'vehicle'];
            const colors = {
                car: '#51AA20',
                motorbike: '#DB6715',
                truck: '#8D60ED',
                taxi: '#C46EA6',
                vehicle: '#D3D6DD'
            };

            // Chart destroy to prevent overlap
            ['monthlyBar', 'monthlyBarPass', 'monthlyLine', 'monthlyLinePass'].forEach(name => {
                if (window[name]) {
                    window[name].destroy();
                }
            });

            // Bar & Line charts (casual & pass)
            const buildDataset = (source, isLine = false) =>
                chartTypes.map(type => ({
                    label: type.charAt(0).toUpperCase() + type.slice(1),
                    data: extract(source, `total_${type}`),
                    backgroundColor: colors[type],
                    borderColor: colors[type],
                    borderWidth: isLine ? 3 : 1,
                    datalabels: { anchor: 'end', align: 'end' },
                    tension: isLine ? 0.5 : undefined,
                    hidden: type !== 'car' // show only 'Car' by default
                }));

            const chartOptions = (isLine = false) => ({
                type: isLine ? 'line' : 'bar',
                data: {}, // to be set below
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
                            font: { weight: 'bold' },
                            formatter: Math.round,
                            padding: isLine ? 3 : 6,
                            offset: isLine ? 4 : 8
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
            });

            // Create charts
            const chartMap = [
                { ctx: 'monthlyQuantityBar', data: response.this_month.weekly_totals.casual, line: false, name: 'monthlyBar' },
                { ctx: 'monthlyPassQuantityBar', data: response.this_month.weekly_totals.pass, line: false, name: 'monthlyBarPass' },
                { ctx: 'monthlyQuantityLine', data: response.this_month.weekly_totals.casual, line: true, name: 'monthlyLine' },
                { ctx: 'monthlyPassQuantityLine', data: response.this_month.weekly_totals.pass, line: true, name: 'monthlyLinePass' },
            ];

            chartMap.forEach(({ ctx, data, line, name }) => {
                const canvas = document.getElementById(ctx)?.getContext('2d');
                if (canvas) {
                    const config = chartOptions(line);
                    config.data = {
                        labels,
                        datasets: buildDataset(data, line)
                    };
                    window[name] = new Chart(canvas, config);
                }
            });
        }
    });
}

// Run first immediately
loadMonthlyTransactionData();

// Refresh every 5 seconds
setInterval(loadMonthlyTransactionData, 5000);

    });

