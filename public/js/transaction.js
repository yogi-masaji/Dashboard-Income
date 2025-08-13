$(document).ready(function() {
    // --- UTILITY FUNCTIONS ---
    function formatQuantity(quantity) {
        // Handles null, undefined, or non-numeric values gracefully
        const num = parseInt(quantity, 10);
        return isNaN(num) ? '0' : new Intl.NumberFormat('id-ID').format(num);
    }

    /**
     * Injects theme-based colors into a chart options object.
     * @param {object} options - The chart options object to modify.
     */
    function applyThemeToChartOptions(options) {
        const isDarkMode = localStorage.getItem('theme') === 'dark';
        const textColor = isDarkMode ? '#ecf0f1' : '#000000';
        const gridColor = isDarkMode ? 'rgba(236, 240, 241, 0.2)' : 'rgba(0, 0, 0, 0.1)';

        if (options.plugins && options.plugins.legend && options.plugins.legend.labels) {
            options.plugins.legend.labels.color = textColor;
        }

        if (options.scales) {
            Object.values(options.scales).forEach(scale => {
                if (scale.ticks) {
                    scale.ticks.color = textColor;
                }
                if (scale.grid) {
                    scale.grid.color = gridColor;
                }
            });
        }
    }


    // --- DATATABLE INITIALIZATION ---
    // It's good practice to initialize all DataTables at once.
    const dtOptions = {
        searching: false,
        paging: false,
        autoWidth: false,
        ordering: false,
        info: false,
        data: []
    };

    const table = $('#dailyQuantity').DataTable({
        ...dtOptions,
        columns: [{ data: 'no' }, { data: 'type' }, { data: 'yesterday' }, { data: 'today' }]
    });

    const weeklyTable = $('#weeklyQuantity').DataTable({
        ...dtOptions,
        columns: [{ data: 'no' }, { data: 'vehicle' }, { data: 'last_week' }, { data: 'this_week' }]
    });

    const monthlyTable = $('#monthlyQuantity').DataTable({
        ...dtOptions,
        columns: [{ data: 'no' }, { data: 'vehicle' }, { data: 'last_month' }, { data: 'this_month' }]
    });

    const weeklyPassTable = $('#weeklyQuantityPass').DataTable({
        ...dtOptions,
        columns: [{ data: 'no' }, { data: 'vehicle' }, { data: 'last_week' }, { data: 'this_week' }]
    });

    const monthlyPassTable = $('#monthlyQuantityPass').DataTable({
        ...dtOptions,
        columns: [{ data: 'no' }, { data: 'vehicle' }, { data: 'last_month' }, { data: 'this_month' }]
    });


    // --- DATA FETCHING FUNCTIONS ---

    /**
     * Fetches and displays daily transaction data.
     * @returns {Promise} The jQuery AJAX promise.
     */
    function fetchDailyTransaction() {
        // Return the AJAX promise
        return $.ajax({
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

                const formattedRows = rows.map((item, index) => ({ no: index + 1, ...item }));
                table.clear().rows.add(formattedRows).draw();

                $('#dailyQuantity tfoot').html(`
                    <tr>
                        <th colspan="2" style="text-align:left">All Vehicle</th>
                        <th>${formatQuantity(yesterday.grandtotal)}</th>
                        <th>${formatQuantity(today.grandtotal)}</th>
                    </tr>
                `);

                const labels = ['Car', 'Motorbike', 'Truck', 'Taxi', 'Other'];
                const casualData = [today.carcasual, today.motorbikecasual, today.truckcasual, today.taxicasual, today.othercasual];
                const passData = [today.carpass, today.motorbikepass, today.truckpass, today.taxipass, today.otherpass];

                if (window.dailyBarChart) window.dailyBarChart.destroy();
                if (window.dailyLineChart) window.dailyLineChart.destroy();

                const commonOptions = {
                    responsive: true, maintainAspectRatio: true,
                    plugins: {
                        legend: { position: 'top', labels: {} },
                        datalabels: { backgroundColor: ctx => ctx.dataset.backgroundColor, borderRadius: 4, color: 'white', font: { weight: 'bold' }, formatter: Math.round, padding: 6, offset: 8 }
                    },
                    scales: {
                        y: { beginAtZero: true, ticks: { precision: 0 }, grid: {}, grace: '10%' },
                        x: { ticks: {}, grid: {} }
                    }
                };
                
                applyThemeToChartOptions(commonOptions);

                const barData = {
                    labels,
                    datasets: [
                        { label: 'Casual', data: casualData, backgroundColor: 'rgba(255, 0, 0, 1)', datalabels: { anchor: 'end', align: 'end' } },
                        { label: 'Pass', data: passData, backgroundColor: 'rgba(0, 132, 247, 1)', datalabels: { anchor: 'end', align: 'end' } }
                    ]
                };

                const lineData = {
                    labels,
                    datasets: [
                        { label: 'Casual', data: casualData, borderColor: 'rgba(255, 0, 0, 1)', backgroundColor: 'rgba(255, 0, 0, 1)', tension: 0.5, borderWidth: 3, datalabels: { anchor: 'end', align: 'end' } },
                        { label: 'Pass', data: passData, borderColor: 'rgba(0, 132, 247, 1)', backgroundColor: 'rgba(0, 132, 247, 1)', tension: 0.5, borderWidth: 3, datalabels: { anchor: 'end', align: 'end' } }
                    ]
                };
                
                const ctxBar = document.getElementById('dailyQuantityBar')?.getContext('2d');
                if (ctxBar) window.dailyBarChart = new Chart(ctxBar, { type: 'bar', data: barData, options: commonOptions, plugins: [ChartDataLabels] });

                const lineOptions = { ...commonOptions, plugins: { ...commonOptions.plugins, datalabels: { ...commonOptions.plugins.datalabels, padding: 3, offset: 4 }}};

                const ctxLine = document.getElementById('dailyQuantityLine')?.getContext('2d');
                if (ctxLine) window.dailyLineChart = new Chart(ctxLine, { type: 'line', data: lineData, options: lineOptions, plugins: [ChartDataLabels] });
            }
        });
    }

    /**
     * Fetches and displays weekly transaction data.
     * @returns {Promise} The jQuery AJAX promise.
     */
    function fetchWeeklyTransactionData() {
        // Return the AJAX promise
        return $.ajax({
            url: weeklyTransactionURL,
            method: 'GET',
            success: function(response) {
                const thisWeek = response.this_week.totals.casual;
                const lastWeek = response.last_week.totals.casual;
                const thisWeekPass = response.this_week.totals.pass;
                const lastWeekPass = response.last_week.totals.pass;
                const thisWeekChart = response.this_week.casual;
                const thisWeekPassChart = response.this_week.pass;
                const compare = response.vehicle_comparison;

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

                const casualRows = ['car', 'motorbike', 'truck', 'taxi', 'other'].map((type, i) => ({ no: i + 1, vehicle: type.charAt(0).toUpperCase() + type.slice(1), this_week: formatQuantity(thisWeek[`total_${type}`]), last_week: formatQuantity(lastWeek[`total_${type}`]) }));
                weeklyTable.clear().rows.add(casualRows).draw();

                const passRows = ['car', 'motorbike', 'truck', 'taxi', 'other'].map((type, i) => ({ no: i + 1, vehicle: type.charAt(0).toUpperCase() + type.slice(1), this_week: formatQuantity(thisWeekPass[`total_${type}`]), last_week: formatQuantity(lastWeekPass[`total_${type}`]) }));
                weeklyPassTable.clear().rows.add(passRows).draw();

                $('#weeklyQuantity tfoot').html(`<tr><th colspan="2" style="text-align:left">All Vehicle</th><th>${formatQuantity(lastWeek.total_vehicle)}</th><th>${formatQuantity(thisWeek.total_vehicle)}</th></tr>`);

                const labels = thisWeekChart.map(item => new Date(item.tanggal).toLocaleDateString('en-GB', { day: '2-digit', month: 'short' }));

                const buildDataset = (data, type = 'bar') => {
                    const colors = ['#0D61E2', '#E60045', '#FFCD56', '#32CD7D', '#E69500', '#09533dff'];
                    const keys = ['car', 'motorbike', 'truck', 'taxi', 'vehicle', 'other'];
                    return keys.map((key, i) => ({
                        label: key.charAt(0).toUpperCase() + key.slice(1),
                        data: data[i],
                        backgroundColor: colors[i],
                        borderColor: colors[i],
                        borderWidth: type === 'bar' ? 1 : 3,
                        datalabels: { anchor: 'end', align: 'end' },
                        tension: 0.5,
                        hidden: i !== 0
                    }));
                };

                const casualData = [thisWeekChart.map(item => item.carcasual), thisWeekChart.map(item => item.motorbikecasual), thisWeekChart.map(item => item.truckcasual), thisWeekChart.map(item => item.taxicasual), thisWeekChart.map(item => item.vehiclecasual), thisWeekChart.map(item => item.othercasual)];
                const passData = [thisWeekPassChart.map(item => item.carpass), thisWeekPassChart.map(item => item.motorbikepass), thisWeekPassChart.map(item => item.truckpass), thisWeekPassChart.map(item => item.taxipass), thisWeekPassChart.map(item => item.vehiclepass), thisWeekPassChart.map(item => item.otherpass)];

                const baseOptions = {
                    responsive: true, maintainAspectRatio: true,
                    plugins: {
                        legend: { position: 'top', labels: {} },
                        datalabels: { backgroundColor: ctx => ctx.dataset.backgroundColor, borderRadius: 4, color: 'white', font: { weight: 'bold' }, formatter: formatQuantity, padding: 6, offset: 8 }
                    },
                    scales: {
                        y: { beginAtZero: true, ticks: { precision: 0, callback: formatQuantity }, grid: {}, grace: '10%' },
                        x: { ticks: {}, grid: {} }
                    }
                };
                
                applyThemeToChartOptions(baseOptions);

                const buildChart = (canvasId, type, data) => {
                    const chartInstanceName = `${canvasId}Chart`;
                    if (window[chartInstanceName]) window[chartInstanceName].destroy();
                    
                    const ctx = document.getElementById(canvasId)?.getContext('2d');
                    if (ctx) {
                        window[chartInstanceName] = new Chart(ctx, {
                            type,
                            data: { labels, datasets: buildDataset(data, type) },
                            options: baseOptions,
                            plugins: [ChartDataLabels]
                        });
                    }
                };

                buildChart('weeklyQuantityBar', 'bar', casualData);
                buildChart('weeklyPassQuantityBar', 'bar', passData);
                buildChart('weeklyQuantityLine', 'line', casualData);
                buildChart('weeklyPassQuantityLine', 'line', passData);
            }
        });
    }

    /**
     * Fetches and displays monthly transaction data.
     * @returns {Promise} The jQuery AJAX promise.
     */
    function loadMonthlyTransactionData() {
        // Return the AJAX promise
        return $.ajax({
            url: monthlyTransactionURL,
            method: 'GET',
            success: function(response) {
                const thisMonth = response.this_month.totals.casual;
                const lastMonth = response.last_month.totals.casual;
                const thisMonthPass = response.this_month.totals.pass;
                const lastMonthPass = response.last_month.totals.pass;
                const compare = response.vehicle_comparison;

                const container = $('#monthly-transaction-comparison').empty();
                const colClasses = ['col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-12'];
                compare.forEach((vehicle, index) => {
                    container.append(`
                        <div class="${colClasses[index]}">
                            <div class="dashboard-card">
                                <div class="card-title" style="color: #000 !important;">${vehicle.vehicle}</div>
                                <div class="d-flex align-items-baseline">
                                    <h2 class="card-value" style="color: #000 !important;">${formatQuantity(vehicle.this_month)}</h2>
                                    <span class="ms-2" style="color: ${vehicle.color}">${vehicle.percent_change} ${vehicle.direction}</span>
                                </div>
                                <div class="yesterday" style="color: #000 !important;">Two Months Ago: ${formatQuantity(vehicle.two_months_ago)}</div>
                            </div>
                        </div>
                    `);
                });

                const casualRows = ['car', 'motorbike', 'truck', 'taxi', 'other'].map((type, index) => ({ no: index + 1, vehicle: type.charAt(0).toUpperCase() + type.slice(1), this_month: formatQuantity(thisMonth[`total_${type}`]), last_month: formatQuantity(lastMonth[`total_${type}`]) }));
                monthlyTable.clear().rows.add(casualRows).draw();

                const passRows = ['car', 'motorbike', 'truck', 'taxi', 'other'].map((type, index) => ({ no: index + 1, vehicle: type.charAt(0).toUpperCase() + type.slice(1), this_month: formatQuantity(thisMonthPass[`total_${type}`]), last_month: formatQuantity(lastMonthPass[`total_${type}`]) }));
                monthlyPassTable.clear().rows.add(passRows).draw();

                $('#monthlyQuantity tfoot').html(`<tr><th colspan="2" style="text-align:left">All Vehicle</th><th>${formatQuantity(lastMonth.total_vehicle)}</th><th>${formatQuantity(thisMonth.total_vehicle)}</th></tr>`);

                const labels = Object.keys(response.this_month.weekly_totals.casual);
                const extract = (data, key) => labels.map(l => data[l][key]);
                const chartTypes = ['car', 'motorbike', 'truck', 'taxi', 'other', 'vehicle'];
                const colors = { car: '#51AA20', motorbike: '#DB6715', truck: '#8D60ED', taxi: '#C46EA6', other: '#5a0e0eff', vehicle: '#D3D6DD' };

                const buildDataset = (source, isLine = false) => chartTypes.map(type => ({
                    label: type.charAt(0).toUpperCase() + type.slice(1),
                    data: extract(source, `total_${type}`),
                    backgroundColor: colors[type],
                    borderColor: colors[type],
                    borderWidth: isLine ? 3 : 1,
                    datalabels: { anchor: 'end', align: 'end' },
                    tension: isLine ? 0.5 : undefined,
                    hidden: type !== 'car'
                }));

                const chartOptions = (isLine = false) => {
                    const options = {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: { position: 'top', labels: {} },
                            datalabels: { backgroundColor: ctx => ctx.dataset.backgroundColor, borderRadius: 4, color: 'white', font: { weight: 'bold' }, formatter: value => formatQuantity(value), padding: isLine ? 3 : 6, offset: isLine ? 4 : 8 }
                        },
                        scales: {
                            y: { beginAtZero: true, ticks: { precision: 0, callback: value => formatQuantity(value) }, grid: {}, grace: '10%' },
                            x: { ticks: {}, grid: {} }
                        }
                    };
                    applyThemeToChartOptions(options);
                    return {
                        type: isLine ? 'line' : 'bar',
                        options: options,
                        plugins: [ChartDataLabels]
                    };
                };

                const chartMap = [
                    { ctx: 'monthlyQuantityBar', data: response.this_month.weekly_totals.casual, line: false, name: 'monthlyBar' },
                    { ctx: 'monthlyPassQuantityBar', data: response.this_month.weekly_totals.pass, line: false, name: 'monthlyBarPass' },
                    { ctx: 'monthlyQuantityLine', data: response.this_month.weekly_totals.casual, line: true, name: 'monthlyLine' },
                    { ctx: 'monthlyPassQuantityLine', data: response.this_month.weekly_totals.pass, line: true, name: 'monthlyLinePass' },
                ];
                
                chartMap.forEach(({ ctx, data, line, name }) => {
                    if (window[name]) window[name].destroy();
                    
                    const canvas = document.getElementById(ctx)?.getContext('2d');
                    if (canvas) {
                        const config = chartOptions(line);
                        config.data = { labels, datasets: buildDataset(data, line) };
                        window[name] = new Chart(canvas, config);
                    }
                });
            }
        });
    }

    /**
     * Main function to load all transaction data efficiently.
     * It runs all fetch operations in parallel.
     */
    async function loadAllTransactionData() {
        console.log("Refreshing all transaction data...");
        // Show a loader if you have one
        const promises = [
            fetchDailyTransaction(),
            fetchWeeklyTransactionData(),
            loadMonthlyTransactionData()
        ];

        try {
            // Wait for all ajax calls to complete
            await Promise.all(promises);
            console.log("All transaction data refreshed successfully.");
        } catch (error) {
            // Log any error that occurs during the fetch
            console.error("An error occurred while refreshing transaction data:", error);
        } finally {
            // Hide loader if you have one
        }
    }

    // --- SCRIPT INITIALIZATION ---
    
    // Perform the initial data load when the page is ready
    loadAllTransactionData();

    // Set a single interval to refresh all data periodically
    setInterval(loadAllTransactionData, 5000); // Refresh every 5 seconds
});