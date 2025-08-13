$(document).ready(function() {

    // --- GLOBAL CHART AND TABLE VARIABLES ---
    let tableTransaction, weeklyTable, monthlyTable, weeklyPassTable, monthlyPassTable;
    let dailyIncomeTable, weeklyIncomeTable, monthlyIncomeTable;
    let dailyEpaymentTable, weeklyEpaymentTable, monthlyEpaymentTable;

    // Chart instances
    let dailyQuantityBarChart, dailyQuantityLineChart;
    let weeklyQuantityBarChart, weeklyQuantityLineChart, weeklyPassQuantityBarChart, weeklyPassQuantityLineChart;
    let monthlyQuantityBarChart, monthlyQuantityLineChart, monthlyPassQuantityBarChart, monthlyPassQuantityLineChart;
    let dailyIncomeChart;
    let weeklyIncomeBarChart, weeklyIncomeLineChart;
    let monthlyIncomeBarChart, monthlyIncomeLineChart;
    let dailyEPaymentBarChart, dailyEPaymentLineChart;
    let weeklyEPaymentBarChart, weeklyEPaymentLineChart;
    let monthlyEPaymentBarChart, monthlyEPaymentLineChart;


    // --- UTILITY FUNCTIONS ---
    const formatRupiah = (number) => {
        if (isNaN(number)) return 'Rp 0';
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    };

    const formatQuantity = (val) => {
        const num = parseInt(val, 10);
        return isNaN(num) ? '0' : num.toLocaleString('id-ID');
    };

    /**
     * Initializes all DataTable instances.
     * This prevents re-initialization on data refresh.
     */
    function initializeDataTables() {
        const dtOptions = { searching: false, paging: false, autoWidth: false, ordering: false, info: false };

        // Transaction Tables
        tableTransaction = $('#dailyQuantity').DataTable({ ...dtOptions, columns: [{ data: 'no' }, { data: 'type' }, { data: 'yesterday' }, { data: 'today' }] });
        weeklyTable = $('#weeklyQuantity').DataTable({ ...dtOptions, columns: [{ data: 'no' }, { data: 'vehicle' }, { data: 'last_week' }, { data: 'this_week' }] });
        monthlyTable = $('#monthlyQuantity').DataTable({ ...dtOptions, columns: [{ data: 'no' }, { data: 'vehicle' }, { data: 'last_month' }, { data: 'this_month' }] });
        weeklyPassTable = $('#weeklyQuantityPass').DataTable({ ...dtOptions, columns: [{ data: 'no' }, { data: 'vehicle' }, { data: 'last_week' }, { data: 'this_week' }] });
        monthlyPassTable = $('#monthlyQuantityPass').DataTable({ ...dtOptions, columns: [{ data: 'no' }, { data: 'vehicle' }, { data: 'last_month' }, { data: 'this_month' }] });

        // Income Tables
        dailyIncomeTable = $('#dailyIncome').DataTable({ ...dtOptions, columns: [{ data: 'no' }, { data: 'vehicle' }, { data: 'yesterday' }, { data: 'today' }] });
        weeklyIncomeTable = $('#weeklyIncome').DataTable({ ...dtOptions, columns: [{ data: 'no' }, { data: 'vehicle' }, { data: 'last_week' }, { data: 'this_week' }] });
        monthlyIncomeTable = $('#monthlyIncome').DataTable({ ...dtOptions, columns: [{ data: 'no' }, { data: 'vehicle' }, { data: 'last_month' }, { data: 'this_month' }] });

        // E-Payment Tables
        dailyEpaymentTable = $('#dailyE-Payment').DataTable({ ...dtOptions, columns: [{ data: 'no' }, { data: 'payment' }, { data: 'yesterday' }, { data: 'today' }] });
        weeklyEpaymentTable = $('#weeklyE-Payment').DataTable({ ...dtOptions, columns: [{ data: 'no' }, { data: 'payment' }, { data: 'last_week' }, { data: 'this_week' }] });
        monthlyEpaymentTable = $('#monthlyE-Payment').DataTable({ ...dtOptions, columns: [{ data: 'no' }, { data: 'payment' }, { data: 'last_month' }, { data: 'this_month' }] });
    }

    // == FROM testasync.js ==
    function fetchDailyTransactions() {
        return $.ajax({
            url: dailyTransactionURL,
            method: 'GET',
            success: function(response) {
                const today = response.data[0].today[0];
                const yesterday = response.data[0].yesterday[0];

                const rows = [{
                        type: 'Total Casual',
                        yesterday: formatQuantity(yesterday.grandcasual),
                        today: formatQuantity(today.grandcasual)
                    },
                    {
                        type: 'Total Pass',
                        yesterday: formatQuantity(yesterday.grandpass),
                        today: formatQuantity(today.grandpass)
                    }
                ];

                const formattedRows = rows.map((item, index) => ({ no: index + 1, ...item }));
                tableTransaction.clear().rows.add(formattedRows).draw();

                $('#dailyQuantity tfoot').html(`
                    <tr>
                        <th colspan="2" style="text-align:left">All Vehicle</th>
                        <th>${formatQuantity(yesterday.grandtotal)}</th>
                        <th>${formatQuantity(today.grandtotal)}</th>
                    </tr>`);

                const labels = ['Car', 'Motorbike', 'Truck', 'Taxi', 'Other'];
                const casualData = [today.carcasual, today.motorbikecasual, today.truckcasual, today.taxicasual, today.othercasual];
                const passData = [today.carpass, today.motorbikepass, today.truckpass, today.taxipass, today.otherpass];

                const barData = {
                    labels,
                    datasets: [
                        { label: 'Casual', data: casualData, backgroundColor: 'rgba(255, 0, 0, 1)', datalabels: { anchor: 'end', align: 'end' } },
                        { label: 'Pass', data: passData, backgroundColor: 'rgba(0, 132, 247, 1)', datalabels: { anchor: 'end', align: 'end' } }
                    ]
                };

                const commonOptions = {
                    responsive: true, maintainAspectRatio: true,
                    plugins: { 
                        legend: { position: 'top' },
                        datalabels: { backgroundColor: (ctx) => ctx.dataset.backgroundColor, borderRadius: 4, color: 'white', font: { weight: 'bold' }, formatter: formatQuantity, padding: 6, offset: 8 } 
                    },
                    scales: { 
                        y: { beginAtZero: true, ticks: { precision: 0 }, grace: '10%' },
                        x: { }
                    }
                };
                
                // --- Bar Chart Update ---
                if (dailyQuantityBarChart) {
                    dailyQuantityBarChart.data = barData;
                    dailyQuantityBarChart.options = commonOptions;
                    dailyQuantityBarChart.update('none'); // Update without animation
                } else {
                    const ctxBar = document.getElementById('dailyQuantityBar')?.getContext('2d');
                    if (ctxBar) dailyQuantityBarChart = new Chart(ctxBar, { type: 'bar', data: barData, options: commonOptions, plugins: [ChartDataLabels] });
                }


                const lineData = {
                    labels,
                    datasets: [
                        { label: 'Casual', data: casualData, borderColor: 'rgba(255, 0, 0, 1)', backgroundColor: 'rgba(255, 0, 0, 1)', tension: 0.5, borderWidth: 3 },
                        { label: 'Pass', data: passData, borderColor: 'rgba(0, 132, 247, 1)', backgroundColor: 'rgba(0, 132, 247, 1)', tension: 0.5, borderWidth: 3 }
                    ]
                };
                const lineOptions = { ...commonOptions, plugins: { ...commonOptions.plugins, datalabels: { ...commonOptions.plugins.datalabels, padding: 3, offset: 4 }}};

                // --- Line Chart Update ---
                if (dailyQuantityLineChart) {
                    dailyQuantityLineChart.data = lineData;
                    dailyQuantityLineChart.options = lineOptions;
                    dailyQuantityLineChart.update('none'); // Update without animation
                } else {
                    const ctxLine = document.getElementById('dailyQuantityLine')?.getContext('2d');
                    if (ctxLine) dailyQuantityLineChart = new Chart(ctxLine, { type: 'line', data: lineData, options: lineOptions, plugins: [ChartDataLabels] });
                }
            }
        });
    }

    function fetchWeeklyTransactions() {
        return $.ajax({
            url: weeklyTransactionURL,
            method: 'GET',
            success: function(response) {
                const thisWeekCasual = response.this_week.totals.casual;
                const lastWeekCasual = response.last_week.totals.casual;
                const thisWeekPass = response.this_week.totals.pass;
                const lastWeekPass = response.last_week.totals.pass;
                
                const casualRows = ['car', 'motorbike', 'truck', 'taxi', 'other'].map((v, i) => ({ no: i + 1, vehicle: v.charAt(0).toUpperCase() + v.slice(1), last_week: formatQuantity(lastWeekCasual[`total_${v}`]), this_week: formatQuantity(thisWeekCasual[`total_${v}`]) }));
                weeklyTable.clear().rows.add(casualRows).draw();
                $('#weeklyQuantity tfoot').html(`<tr><th colspan="2" style="text-align:left">All Vehicle</th><th>${formatQuantity(lastWeekCasual.total_vehicle)}</th><th>${formatQuantity(thisWeekCasual.total_vehicle)}</th></tr>`);

                const passRows = ['car', 'motorbike', 'truck', 'taxi', 'other'].map((v, i) => ({ no: i + 1, vehicle: v.charAt(0).toUpperCase() + v.slice(1), last_week: formatQuantity(lastWeekPass[`total_${v}`]), this_week: formatQuantity(thisWeekPass[`total_${v}`]) }));
                weeklyPassTable.clear().rows.add(passRows).draw();

                const thisWeekChartCasual = response.this_week.casual;
                const thisWeekChartPass = response.this_week.pass;
                const labels = thisWeekChartCasual.map(item => new Date(item.tanggal).toLocaleDateString('en-GB', { day: '2-digit', month: 'short' }));
                
                const vehicleTypes = ['car', 'motorbike', 'taxi', 'truck', 'other', 'vehicle'];
                const colors = ['#0D61E2', '#E60045', '#32CD7D', '#FFCD56', '#720049ff', '#E69500'];

                const createDataset = (data, type, hidden) => ({ label: type.charAt(0).toUpperCase() + type.slice(1), data: data.map(item => item[`${type}casual`]), backgroundColor: colors[vehicleTypes.indexOf(type)], borderColor: colors[vehicleTypes.indexOf(type)], hidden, datalabels: { anchor: 'end', align: 'end' } });
                const createPassDataset = (data, type, hidden) => ({ label: type.charAt(0).toUpperCase() + type.slice(1), data: data.map(item => item[`${type}pass`]), backgroundColor: colors[vehicleTypes.indexOf(type)], borderColor: colors[vehicleTypes.indexOf(type)], hidden, datalabels: { anchor: 'end', align: 'end' } });

                const barData = { labels, datasets: vehicleTypes.map((v, i) => createDataset(thisWeekChartCasual, v, i > 0)) };
                const barPassData = { labels, datasets: vehicleTypes.map((v, i) => createPassDataset(thisWeekChartPass, v, i > 0)) };

                const commonOptions = {
                    responsive: true, maintainAspectRatio: true,
                    plugins: { 
                        legend: { position: 'top' },
                        datalabels: { backgroundColor: (ctx) => ctx.dataset.backgroundColor, borderRadius: 4, color: 'white', font: { weight: 'bold' }, formatter: formatQuantity, padding: 6, offset: 8 } 
                    },
                    scales: { 
                        y: { beginAtZero: true, ticks: { precision: 0 }, grace: '10%' },
                        x: { }
                    }
                };

                if(weeklyQuantityBarChart) {
                    weeklyQuantityBarChart.data = barData;
                    weeklyQuantityBarChart.options = commonOptions;
                    weeklyQuantityBarChart.update('none');
                } else {
                    const ctxBar = document.getElementById('weeklyQuantityBar')?.getContext('2d');
                    if(ctxBar) weeklyQuantityBarChart = new Chart(ctxBar, { type: 'bar', data: barData, options: commonOptions, plugins: [ChartDataLabels] });
                }
                
                if(weeklyPassQuantityBarChart) {
                    weeklyPassQuantityBarChart.data = barPassData;
                    weeklyPassQuantityBarChart.options = commonOptions;
                    weeklyPassQuantityBarChart.update('none');
                } else {
                    const ctxPassBar = document.getElementById('weeklyPassQuantityBar')?.getContext('2d');
                    if(ctxPassBar) weeklyPassQuantityBarChart = new Chart(ctxPassBar, { type: 'bar', data: barPassData, options: commonOptions, plugins: [ChartDataLabels] });
                }

                const lineData = { labels, datasets: barData.datasets.map(ds => ({ ...ds, borderWidth: 3, tension: 0.5 }))};
                const linePassData = { labels, datasets: barPassData.datasets.map(ds => ({ ...ds, borderWidth: 3, tension: 0.5 }))};
                const lineOptions = { ...commonOptions, plugins: { ...commonOptions.plugins, datalabels: { ...commonOptions.plugins.datalabels, padding: 3, offset: 4 }}};

                if(weeklyQuantityLineChart) {
                    weeklyQuantityLineChart.data = lineData;
                    weeklyQuantityLineChart.options = lineOptions;
                    weeklyQuantityLineChart.update('none');
                } else {
                    const ctxLine = document.getElementById('weeklyQuantityLine')?.getContext('2d');
                    if(ctxLine) weeklyQuantityLineChart = new Chart(ctxLine, { type: 'line', data: lineData, options: lineOptions, plugins: [ChartDataLabels] });
                }

                if(weeklyPassQuantityLineChart) {
                    weeklyPassQuantityLineChart.data = linePassData;
                    weeklyPassQuantityLineChart.options = lineOptions;
                    weeklyPassQuantityLineChart.update('none');
                } else {
                    const ctxPassLine = document.getElementById('weeklyPassQuantityLine')?.getContext('2d');
                    if(ctxPassLine) weeklyPassQuantityLineChart = new Chart(ctxPassLine, { type: 'line', data: linePassData, options: lineOptions, plugins: [ChartDataLabels] });
                }
            }
        });
    }

    function fetchMonthlyTransactions() {
        return $.ajax({
            url: monthlyTransactionURL,
            method: 'GET',
            success: function(response) {
                const thisMonthCasual = response.this_month.totals.casual;
                const lastMonthCasual = response.last_month.totals.casual;
                const thisMonthPass = response.this_month.totals.pass;
                const lastMonthPass = response.last_month.totals.pass;

                const casualRows = ['car', 'motorbike', 'truck', 'taxi', 'other'].map((v, i) => ({ no: i + 1, vehicle: v.charAt(0).toUpperCase() + v.slice(1), last_month: formatQuantity(lastMonthCasual[`total_${v}`]), this_month: formatQuantity(thisMonthCasual[`total_${v}`]) }));
                monthlyTable.clear().rows.add(casualRows).draw();
                $('#monthlyQuantity tfoot').html(`<tr><th colspan="2" style="text-align:left">All Vehicle</th><th>${formatQuantity(lastMonthCasual.total_vehicle)}</th><th>${formatQuantity(thisMonthCasual.total_vehicle)}</th></tr>`);

                const passRows = ['car', 'motorbike', 'truck', 'taxi', 'other'].map((v, i) => ({ no: i + 1, vehicle: v.charAt(0).toUpperCase() + v.slice(1), last_month: formatQuantity(lastMonthPass[`total_${v}`]), this_month: formatQuantity(thisMonthPass[`total_${v}`]) }));
                monthlyPassTable.clear().rows.add(passRows).draw();
                
                const thisMonthChartCasual = response.this_month.weekly_totals.casual;
                const thisMonthChartPass = response.this_month.weekly_totals.pass;
                const labels = Object.keys(thisMonthChartCasual);
                
                const vehicleTypes = ['car', 'motorbike', 'truck', 'taxi', 'other', 'vehicle'];
                const colors = ['#51AA20', '#DB6715', '#8D60ED', '#C46EA6', '#720049ff', '#D3D6DD'];

                const createDataset = (data, type, hidden) => ({ label: type.charAt(0).toUpperCase() + type.slice(1), data: Object.values(data).map(week => week[`total_${type}`]), backgroundColor: colors[vehicleTypes.indexOf(type)], borderColor: colors[vehicleTypes.indexOf(type)], hidden });

                const barData = { labels, datasets: vehicleTypes.map((v, i) => createDataset(thisMonthChartCasual, v, i > 0)) };
                const barPassData = { labels, datasets: vehicleTypes.map((v, i) => createDataset(thisMonthChartPass, v, i > 0)) };

                const commonOptions = {
                    responsive: true, maintainAspectRatio: true,
                    plugins: { 
                        legend: { position: 'top' },
                        datalabels: { backgroundColor: (ctx) => ctx.dataset.backgroundColor, borderRadius: 4, color: 'white', font: { weight: 'bold' }, formatter: formatQuantity, padding: 6, offset: 8, anchor: 'end', align: 'end' } 
                    },
                    scales: { 
                        y: { beginAtZero: true, ticks: { precision: 0 }, grace: '10%' },
                        x: { }
                    }
                };

                if (monthlyQuantityBarChart) {
                    monthlyQuantityBarChart.data = barData;
                    monthlyQuantityBarChart.options = commonOptions;
                    monthlyQuantityBarChart.update('none');
                } else {
                    const ctxBar = document.getElementById('monthlyQuantityBar')?.getContext('2d');
                    if (ctxBar) monthlyQuantityBarChart = new Chart(ctxBar, { type: 'bar', data: barData, options: commonOptions, plugins: [ChartDataLabels] });
                }

                if (monthlyPassQuantityBarChart) {
                    monthlyPassQuantityBarChart.data = barPassData;
                    monthlyPassQuantityBarChart.options = commonOptions;
                    monthlyPassQuantityBarChart.update('none');
                } else {
                    const ctxPassBar = document.getElementById('monthlyPassQuantityBar')?.getContext('2d');
                    if (ctxPassBar) monthlyPassQuantityBarChart = new Chart(ctxPassBar, { type: 'bar', data: barPassData, options: commonOptions, plugins: [ChartDataLabels] });
                }

                const lineData = { labels, datasets: barData.datasets.map(ds => ({ ...ds, borderWidth: 3, tension: 0.5 }))};
                const linePassData = { labels, datasets: barPassData.datasets.map(ds => ({ ...ds, borderWidth: 3, tension: 0.5 }))};
                const lineOptions = { ...commonOptions, plugins: { ...commonOptions.plugins, datalabels: { ...commonOptions.plugins.datalabels, padding: 3, offset: 4 }}};

                if (monthlyQuantityLineChart) {
                    monthlyQuantityLineChart.data = lineData;
                    monthlyQuantityLineChart.options = lineOptions;
                    monthlyQuantityLineChart.update('none');
                } else {
                    const ctxLine = document.getElementById('monthlyQuantityLine')?.getContext('2d');
                    if (ctxLine) monthlyQuantityLineChart = new Chart(ctxLine, { type: 'line', data: lineData, options: lineOptions, plugins: [ChartDataLabels] });
                }

                if (monthlyPassQuantityLineChart) {
                    monthlyPassQuantityLineChart.data = linePassData;
                    monthlyPassQuantityLineChart.options = lineOptions;
                    monthlyPassQuantityLineChart.update('none');
                } else {
                    const ctxPassLine = document.getElementById('monthlyPassQuantityLine')?.getContext('2d');
                    if (ctxPassLine) monthlyPassQuantityLineChart = new Chart(ctxPassLine, { type: 'line', data: linePassData, options: lineOptions, plugins: [ChartDataLabels] });
                }
            }
        });
    }

    // == FROM income.js ==
    function fetchDailyIncome() {
        return $.ajax({
            url: dailyIncomeURL,
            method: 'GET',
            success: function(response) {
                const today = response.data[0].today[0];
                const yesterday = response.data[0].yesterday[0];
                
                const rows = [ { type: 'Car', yesterday: yesterday.carincome, today: today.carincome }, { type: 'Motorbike', yesterday: yesterday.motorbikeincome, today: today.motorbikeincome }, { type: 'Truck', yesterday: yesterday.truckincome, today: today.truckincome }, { type: 'Taxi', yesterday: yesterday.taxiincome, today: today.taxiincome }, { type: 'Lost Ticket', yesterday: yesterday.ticketincome, today: today.ticketincome }, { type: 'Other', yesterday: yesterday.otherincome, today: today.otherincome }, ];
                const formattedRows = rows.map((item, index) => ({ no: index + 1, vehicle: item.type, yesterday: formatRupiah(item.yesterday), today: formatRupiah(item.today) }));
                dailyIncomeTable.clear().rows.add(formattedRows).draw();

                $('#dailyIncome tfoot').html(`<tr><th colspan="2" style="text-align:left">All Vehicle</th><th>${formatRupiah(yesterday.grandtotal)}</th><th>${formatRupiah(today.grandtotal)}</th></tr><tr><th colspan="2" style="text-align:left">All Sticker Income</th><th>${formatRupiah(yesterday.stickerincome)}</th><th>${formatRupiah(today.stickerincome)}</th></tr>`);

                const donutData = {
                    labels: ['Car', 'Motorbike', 'Truck', 'Taxi', 'Other'],
                    datasets: [{ label: 'Income by Vehicle', data: [today.carincome, today.motorbikeincome, today.truckincome, today.taxiincome, today.otherincome], backgroundColor: ['#0D61E2', '#EF0F51', '#FFCD56', '#32CD7D', '#5a0e0eff'], }]
                };

                const donutConfig = {
                    type: 'doughnut', data: donutData,
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'top' },
                            datalabels: { color: '#000', borderColor: '#fff', backgroundColor: 'rgba(255, 255, 255, 0.63)', formatter: (val) => formatRupiah(val), font: { weight: 'bold' }, padding: 6, borderRadius: 25, borderWidth: 3 }
                        }
                    },
                    plugins: [ChartDataLabels]
                };

                if (dailyIncomeChart) {
                    dailyIncomeChart.data = donutData;
                    dailyIncomeChart.options = donutConfig.options;
                    dailyIncomeChart.update('none');
                } else {
                    const ctx = document.getElementById('dailyIncomedonut')?.getContext('2d');
                    if (ctx) dailyIncomeChart = new Chart(ctx, donutConfig);
                }
            }
        });
    }

    function fetchWeeklyIncome() {
        return $.ajax({
            url: weeklyIncomeURL,
            method: 'GET',
            success: function(response) {
                const thisWeekIncome = response.this_week.totals;
                const lastWeekIncome = response.last_week.totals;

                const rows = ['car', 'motorbike', 'truck', 'taxi', 'ticket', 'other'].map((v, i) => ({ no: i + 1, vehicle: v.charAt(0).toUpperCase() + v.slice(1), last_week: formatRupiah(lastWeekIncome[`${v}income`]), this_week: formatRupiah(thisWeekIncome[`${v}income`]) }));
                weeklyIncomeTable.clear().rows.add(rows).draw();
                
                $('#weeklyIncome tfoot').html(`<tr><th colspan="2" style="text-align:left">All Casual Income</th><th style="font-size:12px;">${formatRupiah(lastWeekIncome.vehicleincome)}</th><th style="font-size:12px;">${formatRupiah(thisWeekIncome.vehicleincome)}</th></tr><tr><th colspan="2" style="text-align:left">All Sticker Income</th><th style="font-size:12px;">${formatRupiah(lastWeekIncome.stickerincome)}</th><th style="font-size:12px;">${formatRupiah(thisWeekIncome.stickerincome)}</th></tr>`);

                const thisWeekIncomeBar = response.this_week.data;
                const labels = thisWeekIncomeBar.map(item => new Date(item.tanggal).toLocaleDateString('en-GB', { day: '2-digit', month: 'short' }));
                
                const vehicleTypes = ['car', 'motorbike', 'truck', 'taxi', 'vehicle'];
                const colors = ['#0D61E2', '#E60045', '#FFCD56', '#32CD7D', '#E69500'];

                const datasets = vehicleTypes.map((v, i) => ({ label: v.charAt(0).toUpperCase() + v.slice(1), data: thisWeekIncomeBar.map(item => item[`${v}income`]), backgroundColor: colors[i], borderColor: colors[i], hidden: i > 0, datalabels: { anchor: 'end', align: 'end' } }));

                const barData = { labels, datasets };
                const commonOptions = {
                    responsive: true, maintainAspectRatio: true,
                    plugins: { 
                        legend: { position: 'top' },
                        datalabels: { backgroundColor: (ctx) => ctx.dataset.backgroundColor, borderRadius: 4, color: 'white', font: { weight: 'bold' }, formatter: (val) => formatRupiah(val), padding: 6, offset: 8 } 
                    },
                    scales: { 
                        y: { beginAtZero: true, ticks: { precision: 0 }, grace: '10%' },
                        x: { }
                    }
                };

                if (weeklyIncomeBarChart) {
                    weeklyIncomeBarChart.data = barData;
                    weeklyIncomeBarChart.options = commonOptions;
                    weeklyIncomeBarChart.update('none');
                } else {
                    const ctxBar = document.getElementById('weeklyIncomeBar')?.getContext('2d');
                    if (ctxBar) weeklyIncomeBarChart = new Chart(ctxBar, { type: 'bar', data: barData, options: commonOptions, plugins: [ChartDataLabels] });
                }

                const lineData = { labels, datasets: datasets.map(ds => ({ ...ds, borderWidth: 3, tension: 0.5 }))};
                const lineOptions = { ...commonOptions, plugins: { ...commonOptions.plugins, datalabels: { ...commonOptions.plugins.datalabels, padding: 3, offset: 4 }}};

                if (weeklyIncomeLineChart) {
                    weeklyIncomeLineChart.data = lineData;
                    weeklyIncomeLineChart.options = lineOptions;
                    weeklyIncomeLineChart.update('none');
                } else {
                    const ctxLine = document.getElementById('weeklyIncomeLine')?.getContext('2d');
                    if (ctxLine) weeklyIncomeLineChart = new Chart(ctxLine, { type: 'line', data: lineData, options: lineOptions, plugins: [ChartDataLabels] });
                }
            }
        });
    }

    function fetchMonthlyIncome() {
        return $.ajax({
            url: monthlyIncomeURL,
            method: 'GET',
            success: function(response) {
                const thisMonthIncome = response.this_Month.totals;
                const lastMonthIncome = response.last_Month.totals;
                
                const rows = ['car', 'motorbike', 'truck', 'taxi', 'ticket', 'other'].map((v, i) => ({ no: i + 1, vehicle: v.charAt(0).toUpperCase() + v.slice(1), last_month: formatRupiah(lastMonthIncome[`${v}income`]), this_month: formatRupiah(thisMonthIncome[`${v}income`]) }));
                monthlyIncomeTable.clear().rows.add(rows).draw();

                $('#monthlyIncome tfoot').html(`<tr><th colspan="2" style="text-align:left">All Casual Income</th><th style="font-size:12px;">${formatRupiah(lastMonthIncome.vehicleincome)}</th><th style="font-size:12px;">${formatRupiah(thisMonthIncome.vehicleincome)}</th></tr><tr><th colspan="2" style="text-align:left">All Sticker Income</th><th style="font-size:12px;">${formatRupiah(lastMonthIncome.stickerincome)}</th><th style="font-size:12px;">${formatRupiah(thisMonthIncome.stickerincome)}</th></tr>`);

                const thisMonthIncomeChart = response.this_Month.weekly_totals;
                const labels = Object.keys(thisMonthIncomeChart);
                
                const vehicleTypes = ['car', 'motorbike', 'truck', 'taxi', 'other', 'vehicle'];
                const colors = ['#51AA20', '#DB6715', '#8D60ED', '#C46EA6', '#5a0e0eff', '#D3D6DD'];

                const datasets = vehicleTypes.map((v, i) => ({ label: v.charAt(0).toUpperCase() + v.slice(1), data: labels.map(label => thisMonthIncomeChart[label][`${v}income`]), backgroundColor: colors[i], borderColor: colors[i], hidden: i > 0 }));

                const barData = { labels, datasets };
                const commonOptions = {
                    responsive: true, maintainAspectRatio: true,
                    plugins: { 
                        legend: { position: 'top' },
                        datalabels: { backgroundColor: ctx => ctx.dataset.backgroundColor, borderRadius: 4, color: 'white', font: { weight: 'bold' }, formatter: val => formatRupiah(val), padding: 6, offset: 8, anchor: 'end', align: 'end' } 
                    },
                    scales: { 
                        y: { beginAtZero: true, ticks: { precision: 0 }, grace: '10%' },
                        x: { }
                    }
                };

                if (monthlyIncomeBarChart) {
                    monthlyIncomeBarChart.data = barData;
                    monthlyIncomeBarChart.options = commonOptions;
                    monthlyIncomeBarChart.update('none');
                } else {
                    const ctxBar = document.getElementById('monthlyIncomeBar')?.getContext('2d');
                    if (ctxBar) monthlyIncomeBarChart = new Chart(ctxBar, { type: 'bar', data: barData, options: commonOptions, plugins: [ChartDataLabels] });
                }

                const lineData = { labels, datasets: datasets.map(ds => ({ ...ds, borderWidth: 3, tension: 0.5, fill: false }))};
                const lineOptions = { ...commonOptions, plugins: { ...commonOptions.plugins, datalabels: { ...commonOptions.plugins.datalabels, padding: 3, offset: 4 }}};

                if (monthlyIncomeLineChart) {
                    monthlyIncomeLineChart.data = lineData;
                    monthlyIncomeLineChart.options = lineOptions;
                    monthlyIncomeLineChart.update('none');
                } else {
                    const ctxLine = document.getElementById('monthlyIncomeLine')?.getContext('2d');
                    if (ctxLine) monthlyIncomeLineChart = new Chart(ctxLine, { type: 'line', data: lineData, options: lineOptions, plugins: [ChartDataLabels] });
                }
            }
        });
    }

    // == FROM epayment.js ==
    function fetchDailyEPaymentSummary() {
        return $.ajax({
            url: dailyEpaymentURL,
            method: 'GET',
            success: function(response) {
                const today = response.data[0].today[0];
                const yesterday = response.data[0].yesterday[0];

                const rows = ['emoney', 'flazz', 'brizzi', 'tapcash', 'parkee', 'cash'].map((p, i) => ({ no: i + 1, payment: p.charAt(0).toUpperCase() + p.slice(1).replace('tapcash', 'Tap Cash'), yesterday: formatRupiah(yesterday[`${p}payment`]), today: formatRupiah(today[`${p}payment`]) }));
                dailyEpaymentTable.clear().rows.add(rows).draw();
                
                $('#dailyE-Payment tfoot').html(`<tr><th colspan="2" style="text-align:left">All E-Payment</th><th>${formatRupiah(yesterday.grandtotal)}</th><th>${formatRupiah(today.grandtotal)}</th></tr>`);
            }
        });
    }

    function fetchDailyEPaymentChart() {
        return $.ajax({
            url: dailyEpaymentChartURL,
            method: 'GET',
            success: function(response) {
                const todayData = response.this_week.data;
                const labels = todayData.map(item => new Date(item.tanggal).toLocaleDateString("id-ID", { day: "numeric", month: "short" }));
                
                const paymentTypes = ['emoney', 'flazz', 'brizzi', 'tapcash', 'parkee', 'cash'];
                const colors = ['#0D61E2', '#FFB800', '#FF4D4D', '#00C9A7', '#9C27B0', '#795548'];

                const datasets = paymentTypes.map((p, i) => ({ label: p.charAt(0).toUpperCase() + p.slice(1).replace('tapcash', 'Tap Cash'), data: todayData.map(item => item[`${p}payment`]), backgroundColor: colors[i], borderColor: colors[i], hidden: i > 0, datalabels: { anchor: 'end', align: 'end' } }));

                const dataChart = { labels, datasets };
                const commonOptions = {
                    responsive: true, maintainAspectRatio: true,
                    plugins: { 
                        legend: { position: 'top' },
                        datalabels: { backgroundColor: ctx => ctx.dataset.backgroundColor, borderRadius: 4, color: 'white', font: { weight: 'bold', size: 9 }, formatter: value => formatRupiah(value), padding: 6, offset: 8 } 
                    },
                    scales: { 
                        y: { beginAtZero: true, ticks: { precision: 0 }, grace: '10%' },
                        x: { }
                    }
                };

                if (dailyEPaymentBarChart) {
                    dailyEPaymentBarChart.data = dataChart;
                    dailyEPaymentBarChart.options = commonOptions;
                    dailyEPaymentBarChart.update('none');
                } else {
                    const ctxBar = document.getElementById('dailyE-PaymentBar')?.getContext('2d');
                    if (ctxBar) dailyEPaymentBarChart = new Chart(ctxBar, { type: 'bar', data: dataChart, options: commonOptions, plugins: [ChartDataLabels] });
                }

                if (dailyEPaymentLineChart) {
                    dailyEPaymentLineChart.data = dataChart;
                    dailyEPaymentLineChart.options = commonOptions;
                    dailyEPaymentLineChart.update('none');
                } else {
                    const ctxLine = document.getElementById('dailyE-PaymentLine')?.getContext('2d');
                    if (ctxLine) dailyEPaymentLineChart = new Chart(ctxLine, { type: 'line', data: dataChart, options: commonOptions, plugins: [ChartDataLabels] });
                }
            }
        });
    }

    function fetchWeeklyEpayment() {
        return $.ajax({
            url: weeklyEpaymentURL,
            method: 'GET',
            success: function(response) {
                const thisWeek = response.this_week.totals;
                const lastWeek = response.last_week.totals;

                const rows = ['emoney', 'flazz', 'brizzi', 'tapcash', 'parkee', 'cash'].map((p, i) => ({ no: i + 1, payment: p.charAt(0).toUpperCase() + p.slice(1).replace('tapcash', 'Tap Cash'), last_week: formatRupiah(lastWeek[`${p}payment`]), this_week: formatRupiah(thisWeek[`${p}payment`]) }));
                weeklyEpaymentTable.clear().rows.add(rows).draw();
                $('#weeklyE-Payment tfoot').html(`<tr><th colspan="2" style="text-align:left">All E-Payment</th><th>${formatRupiah(lastWeek.allpayment)}</th><th>${formatRupiah(thisWeek.allpayment)}</th></tr>`);

                const thisWeekChart = response.this_week.data;
                const labels = thisWeekChart.map(item => new Date(item.tanggal).toLocaleDateString('en-GB', { day: '2-digit', month: 'short' }));
                
                const paymentTypes = ['emoney', 'flazz', 'brizzi', 'tapcash', 'parkee', 'cash', 'all'];
                const colors = ['#0D61E2', '#FFB800', '#FF4D4D', '#00C9A7', '#9C27B0', '#795548', '#CCCCCC'];

                const datasets = paymentTypes.map((p, i) => ({ label: p.charAt(0).toUpperCase() + p.slice(1).replace('tapcash', 'Tap Cash'), data: thisWeekChart.map(item => item[`${p}payment`]), backgroundColor: colors[i], borderColor: colors[i], hidden: i > 0, datalabels: { anchor: 'end', align: 'end' } }));
                
                const commonOptions = {
                    responsive: true, maintainAspectRatio: true,
                    plugins: { 
                        legend: { position: 'top' },
                        datalabels: { backgroundColor: ctx => ctx.dataset.backgroundColor, borderRadius: 4, color: 'white', font: { weight: 'bold', size: 9 }, formatter: value => formatRupiah(value), padding: 6, offset: 8 } 
                    },
                    scales: { 
                        y: { beginAtZero: true, ticks: { precision: 0 }, grace: '10%' },
                        x: { }
                    }
                };
                
                const barData = { labels, datasets };
                if (weeklyEPaymentBarChart) {
                    weeklyEPaymentBarChart.data = barData;
                    weeklyEPaymentBarChart.options = commonOptions;
                    weeklyEPaymentBarChart.update('none');
                } else {
                    const ctxBar = document.getElementById('weeklyE-PaymentBar')?.getContext('2d');
                    if (ctxBar) weeklyEPaymentBarChart = new Chart(ctxBar, { type: 'bar', data: barData, options: commonOptions, plugins: [ChartDataLabels] });
                }

                const lineData = { labels, datasets: datasets.map(ds => ({...ds, fill: false})) };
                if (weeklyEPaymentLineChart) {
                    weeklyEPaymentLineChart.data = lineData;
                    weeklyEPaymentLineChart.options = commonOptions;
                    weeklyEPaymentLineChart.update('none');
                } else {
                    const ctxLine = document.getElementById('weeklyE-PaymentLine')?.getContext('2d');
                    if (ctxLine) weeklyEPaymentLineChart = new Chart(ctxLine, { type: 'line', data: lineData, options: commonOptions, plugins: [ChartDataLabels] });
                }
            }
        });
    }

    function fetchMonthlyEpayment() {
        return $.ajax({
            url: monthlyEpaymentURL,
            method: 'GET',
            success: function(response) {
                const thisMonth = response.this_Month.totals;
                const lastMonth = response.last_Month.totals;

                const rows = ['emoney', 'flazz', 'brizzi', 'tapcash', 'parkee', 'cash'].map((p, i) => ({ no: i + 1, payment: p.charAt(0).toUpperCase() + p.slice(1).replace('tapcash', 'Tap Cash'), last_month: formatRupiah(lastMonth[`${p}payment`]), this_month: formatRupiah(thisMonth[`${p}payment`]) }));
                monthlyEpaymentTable.clear().rows.add(rows).draw();
                $('#monthlyE-Payment tfoot').html(`<tr><th colspan="2" style="text-align:left">All E-Payment</th><th>${formatRupiah(lastMonth.allpayment)}</th><th>${formatRupiah(thisMonth.allpayment)}</th></tr>`);

                const thisMonthChart = response.this_Month.weekly_totals;
                const labels = Object.keys(thisMonthChart);

                const paymentTypes = ['emoney', 'flazz', 'brizzi', 'tapcash', 'parkee', 'cash', 'all'];
                const colors = ['#0D61E2', '#FFB800', '#FF4D4D', '#00C9A7', '#9C27B0', '#795548', '#CCCCCC'];

                const datasets = paymentTypes.map((p, i) => ({ label: p.charAt(0).toUpperCase() + p.slice(1).replace('tapcash', 'Tap Cash'), data: Object.values(thisMonthChart).map(item => item[`${p}payment`]), backgroundColor: colors[i], borderColor: colors[i], hidden: i > 0, datalabels: { anchor: 'end', align: 'end' } }));

                const commonOptions = {
                    responsive: true, maintainAspectRatio: true,
                    plugins: { 
                        legend: { position: 'top' },
                        datalabels: { backgroundColor: ctx => ctx.dataset.backgroundColor, borderRadius: 4, color: 'white', font: { weight: 'bold', size: 9 }, formatter: value => formatRupiah(value), padding: 6, offset: 8 } 
                    },
                    scales: { 
                        y: { beginAtZero: true, ticks: { precision: 0 }, grace: '10%' },
                        x: { }
                    }
                };

                const barData = { labels, datasets };
                if (monthlyEPaymentBarChart) {
                    monthlyEPaymentBarChart.data = barData;
                    monthlyEPaymentBarChart.options = commonOptions;
                    monthlyEPaymentBarChart.update('none');
                } else {
                    const ctxBar = document.getElementById('monthlyE-PaymentBar')?.getContext('2d');
                    if (ctxBar) monthlyEPaymentBarChart = new Chart(ctxBar, { type: 'bar', data: barData, options: commonOptions, plugins: [ChartDataLabels] });
                }
                
                const lineData = { labels, datasets: datasets.map(ds => ({...ds, fill: false})) };
                if (monthlyEPaymentLineChart) {
                    monthlyEPaymentLineChart.data = lineData;
                    monthlyEPaymentLineChart.options = commonOptions;
                    monthlyEPaymentLineChart.update('none');
                } else {
                    const ctxLine = document.getElementById('monthlyE-PaymentLine')?.getContext('2d');
                    if (ctxLine) monthlyEPaymentLineChart = new Chart(ctxLine, { type: 'line', data: lineData, options: commonOptions, plugins: [ChartDataLabels] });
                }
            }
        });
    }

    /**
     * Main function to load all dashboard data.
     */
    async function loadAllDashboardData() {
        console.log("Memulai pengambilan semua data dasbor...");

        if (window.updateChartColors) {
            const isDarkMode = localStorage.getItem('theme') === 'dark';
            window.updateChartColors(isDarkMode);
        }

        const promises = [
            fetchDailyTransactions(),
            fetchWeeklyTransactions(),
            fetchMonthlyTransactions(),
            fetchDailyIncome(),
            fetchWeeklyIncome(),
            fetchMonthlyIncome(),
            fetchDailyEPaymentSummary(),
            fetchDailyEPaymentChart(),
            fetchWeeklyEpayment(),
            fetchMonthlyEpayment()
        ];

        try {
            await Promise.all(promises);
            console.log("Semua data dasbor berhasil diambil.");
            hideLoader();
        } catch (error) {
            console.error("Terjadi kesalahan saat mengambil data dasbor:", error);
            hideLoader(true);
        }
    }

    /**
     * Hides the loading overlay.
     */
    let isLoaderVisible = true;
    function hideLoader(isError = false) {
        if (!isLoaderVisible) return;

        const loadingOverlay = $('#loading-overlay');
        const mainContent = $('#main-content');

        if (isError) {
            loadingOverlay.find('p').text('Gagal memuat data. Silakan segarkan halaman.');
            loadingOverlay.find('.spinner').hide();
        } else {
            loadingOverlay.fadeOut(500, function() {
                $(this).remove();
                mainContent.css('visibility', 'visible').hide().fadeIn(500);
            });
        }
        isLoaderVisible = false;
    }

    // --- SCRIPT INITIALIZATION ---
    initializeDataTables();
    loadAllDashboardData();
    setInterval(loadAllDashboardData, 15000); // Refresh every 15 seconds
});
