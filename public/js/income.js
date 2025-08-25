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

    /**
     * Menerapkan warna berbasis tema ke objek opsi grafik.
     * @param {object} options - Objek opsi grafik yang akan diubah.
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
        
        // Penanganan khusus untuk label data pada doughnut chart di mode gelap
        if (options.plugins && options.plugins.datalabels) {
            if (isDarkMode) {
                options.plugins.datalabels.color = '#fff';
                options.plugins.datalabels.backgroundColor = 'rgba(0, 0, 0, 0.7)';
                options.plugins.datalabels.borderColor = '#444';
            } else {
                options.plugins.datalabels.color = '#000';
                options.plugins.datalabels.backgroundColor = 'rgba(255, 255, 255, 0.7)';
                options.plugins.datalabels.borderColor = '#fff';
            }
        }
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

    const dailyIncomeTable = $('#dailyIncome').DataTable({
        ...dtOptions,
        columns: [{ data: 'no' }, { data: 'vehicle' }, { data: 'yesterday' }, { data: 'today' }]
    });
    const weeklyIncomeTable = $('#weeklyIncome').DataTable({
        ...dtOptions,
        columns: [{ data: 'no' }, { data: 'vehicle' }, { data: 'last_week' }, { data: 'this_week' }]
    });
    const monthlyIncomeTable = $('#monthlyIncome').DataTable({
        ...dtOptions,
        columns: [{ data: 'no' }, { data: 'vehicle' }, { data: 'last_month' }, { data: 'this_month' }]
    });


    // --- FUNGSI PENGAMBILAN DATA ---

    /**
     * Mengambil dan menampilkan data pendapatan harian.
     * @returns {Promise} Promise dari jQuery AJAX.
     */
    function fetchDailyIncome() {
        return $.ajax({
            url: dailyIncomeURL,
            method: 'GET',
            success: function(response) {
                const today = response.data[0].today[0];
                const yesterday = response.data[0].yesterday[0];
                const compare = response.data[0].table_data;

                const container = $('#daily-income-comparison').empty();
                const colClasses = ['col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6'];
                compare.forEach((vehicle, index) => {
                    container.append(`<div class="${colClasses[index]}"><div class="dashboard-card"><div class="card-title" style="color: #000 !important;">${vehicle.vehicle}</div><div class="d-flex align-items-baseline"><h6 class="card-value fs-6 fs-md-5" style="color: #000 !important;">${formatRupiah(vehicle.today)}</h6><span class="ms-2 fs-6 fs-md-5" style="color: ${vehicle.color}; font-size:12px;">${vehicle.percent_change} ${vehicle.direction}</span></div><div class="yesterday fs-6 fs-md-5" style="color: #000 !important;">Yesterday: ${formatRupiah(vehicle.yesterday)}</div></div></div>`);
                });

                const rows = [{ type: 'Car', yesterday: yesterday.carincome, today: today.carincome }, { type: 'Motorbike', yesterday: yesterday.motorbikeincome, today: today.motorbikeincome }, { type: 'Truck', yesterday: yesterday.truckincome, today: today.truckincome }, { type: 'Taxi', yesterday: yesterday.taxiincome, today: today.taxiincome }, { type: 'Lost Ticket', yesterday: yesterday.ticketincome, today: today.ticketincome }, { type: 'Other', yesterday: yesterday.otherincome, today: today.otherincome }, ];
                dailyIncomeTable.clear().rows.add(rows.map((item, index) => ({ no: index + 1, vehicle: item.type, yesterday: formatRupiah(item.yesterday), today: formatRupiah(item.today) }))).draw();
                $('#dailyIncome tfoot').html(`<tr><th colspan="2" style="text-align:left">All Vehicle</th><th>${formatRupiah(yesterday.grandtotal)}</th><th>${formatRupiah(today.grandtotal)}</th></tr><tr><th colspan="2" style="text-align:left">All Sticker Income</th><th>${formatRupiah(yesterday.stickerincome)}</th><th>${formatRupiah(today.stickerincome)}</th></tr>`);

                if (window.dailyIncomeChart) window.dailyIncomeChart.destroy();
                
                const donutData = { labels: ['Car', 'Motorbike', 'Truck', 'Taxi', 'Other'], datasets: [{ label: 'Income by Vehicle', data: [today.carincome, today.motorbikeincome, today.truckincome, today.taxiincome, today.otherincome], backgroundColor: ['#0D61E2', '#EF0F51', '#FFCD56', '#32CD7D', '#5a0e0eff'] }] };
                
                const donutConfig = { 
                    type: 'doughnut', 
                    data: donutData, 
                    options: { 
                        responsive: true, 
                        maintainAspectRatio: false, 
                        plugins: { 
                            legend: { position: 'top', labels: { /* color diatur oleh fungsi tema */ } }, 
                            datalabels: { 
                                formatter: (value) => formatRupiah(value), 
                                font: { weight: 'bold' }, 
                                padding: 6, 
                                borderRadius: 25, 
                                borderWidth: 3 
                            } 
                        } 
                    }, 
                    plugins: [ChartDataLabels] 
                };
                
                applyThemeToChartOptions(donutConfig.options); // Terapkan tema

                const ctx = document.getElementById('dailyIncomedonut')?.getContext('2d');
                if (ctx) window.dailyIncomeChart = new Chart(ctx, donutConfig);
            }
        });
    }

    /**
     * Mengambil dan menampilkan data pendapatan mingguan.
     * @returns {Promise} Promise dari jQuery AJAX.
     */
    function fetchWeeklyIncome() {
        return $.ajax({
            url: weeklyIncomeURL,
            method: 'GET',
            success: function(response) {
                const { this_week, last_week, table_data } = response;
                const container = $('#weekly-income-comparison').empty();
                const colClasses = ['col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6', 'col-md-6'];
                table_data.forEach((vehicle, index) => {
                    container.append(`<div class="${colClasses[index]}"><div class="dashboard-card"><div class="card-title" style="color: #000 !important;">${vehicle.vehicle}</div><div class="d-flex align-items-baseline"><h6 class="card-value fs-6 fs-md-5" style="color: #000 !important;">${formatRupiah(vehicle.this_week)}</h6><span class="ms-2 fs-6 fs-md-5" style="color: ${vehicle.color}; font-size:12px;">${vehicle.percent_change} ${vehicle.direction}</span></div><div class="yesterday fs-6 fs-md-5" style="color: #000 !important;">Two Weeks Ago: ${formatRupiah(vehicle.last_week)}</div></div></div>`);
                });

                const rows = [{ type: 'Car', thisWeekIncome: this_week.totals.carincome, lastWeekIncome: last_week.totals.carincome }, { type: 'Motorbike', thisWeekIncome: this_week.totals.motorbikeincome, lastWeekIncome: last_week.totals.motorbikeincome }, { type: 'Truck', thisWeekIncome: this_week.totals.truckincome, lastWeekIncome: last_week.totals.truckincome }, { type: 'Taxi', thisWeekIncome: this_week.totals.taxiincome, lastWeekIncome: last_week.totals.taxiincome }, { type: 'lost ticket', thisWeekIncome: this_week.totals.ticketincome, lastWeekIncome: last_week.totals.ticketincome }, { type: 'Other', thisWeekIncome: this_week.totals.otherincome, lastWeekIncome: last_week.totals.otherincome }, ];
                weeklyIncomeTable.clear().rows.add(rows.map((item, index) => ({ no: index + 1, vehicle: item.type, this_week: formatRupiah(item.thisWeekIncome), last_week: formatRupiah(item.lastWeekIncome) }))).draw();
                $('#weeklyIncome tfoot').html(`<tr><th colspan="2" style="text-align:left">All Casual Income</th><th style="font-size:12px;">${formatRupiah(last_week.totals.vehicleincome)}</th><th style="font-size:12px;">${formatRupiah(this_week.totals.vehicleincome)}</th></tr><tr><th colspan="2" style="text-align:left">All Sticker Income</th><th style="font-size:12px;">${formatRupiah(last_week.totals.stickerincome)}</th><th style="font-size:12px;">${formatRupiah(this_week.totals.stickerincome)}</th></tr>`);

                if (window.weeklyIncomeBarChart) window.weeklyIncomeBarChart.destroy();
                if (window.weeklyIncomeLineChart) window.weeklyIncomeLineChart.destroy();
                
                const labels = this_week.data.map(item => new Date(item.tanggal).toLocaleDateString('en-GB', { day: '2-digit', month: 'short' }));
                const vehicleTypes = ['car', 'motorbike', 'truck', 'taxi', 'vehicle'];
                const colors = ['#0D61E2', '#E60045', '#FFCD56', '#32CD7D', '#E69500'];
                const datasets = vehicleTypes.map((v, i) => ({ label: v.charAt(0).toUpperCase() + v.slice(1), data: this_week.data.map(item => item[`${v}income`]), backgroundColor: colors[i], borderColor: colors[i], hidden: i > 0, datalabels: { anchor: 'end', align: 'end' } }));
                
                const commonOptions = { 
                    responsive: true, 
                    maintainAspectRatio: false, 
                    plugins: { 
                        legend: { position: 'top', labels: { /* color diatur oleh fungsi tema */ } }, 
                        datalabels: { 
                            backgroundColor: (ctx) => ctx.dataset.backgroundColor, 
                            borderRadius: 4, 
                            color: 'white', 
                            font: { weight: 'bold' }, 
                            formatter: (val) => formatRupiah(val), 
                            padding: 6, 
                            offset: 8 
                        } 
                    }, 
                    scales: { 
                        y: { beginAtZero: true, ticks: { precision: 0 }, grid:{}, grace: '10%' }, 
                        x: { ticks: {}, grid:{} } 
                    } 
                };
                
                applyThemeToChartOptions(commonOptions); // Terapkan tema

                const ctxBar = document.getElementById('weeklyIncomeBar')?.getContext('2d');
                if (ctxBar) window.weeklyIncomeBarChart = new Chart(ctxBar, { type: 'bar', data: { labels, datasets }, options: commonOptions, plugins: [ChartDataLabels] });
                
                const lineOptions = { ...commonOptions, plugins: { ...commonOptions.plugins, datalabels: { ...commonOptions.plugins.datalabels, padding: 3, offset: 4 }}};
                const ctxLine = document.getElementById('weeklyIncomeLine')?.getContext('2d');
                if (ctxLine) window.weeklyIncomeLineChart = new Chart(ctxLine, { type: 'line', data: { labels, datasets: datasets.map(ds => ({ ...ds, borderWidth: 3, tension: 0.5 })) }, options: lineOptions , plugins: [ChartDataLabels] });
            }
        });
    }

    /**
     * Mengambil dan menampilkan data pendapatan bulanan.
     * @returns {Promise} Promise dari jQuery AJAX.
     */
    function fetchMonthlyIncome() {
        return $.ajax({
            url: monthlyIncomeURL,
            method: 'GET',
            success: function(response) {
                const { this_Month, last_Month, table_data } = response;
                const container = $('#monthly-income-comparison').empty();
                const colClasses = Array(table_data.length).fill('col-md-6');
                table_data.forEach((vehicle, index) => {
                    container.append(`<div class="${colClasses[index]}"><div class="dashboard-card"><div class="card-title" style="color: #000 !important;">${vehicle.vehicle}</div><div class="d-flex align-items-baseline"><h6 class="card-value fs-6 fs-md-5" style="color: #000 !important;">${formatRupiah(vehicle.this_month)}</h6><span class="ms-2 fs-6 fs-md-5" style="color: ${vehicle.color}; font-size:12px;">${vehicle.percent_change} ${vehicle.direction}</span></div><div class="yesterday fs-6 fs-md-5" style="color: #000 !important;">Two Months Ago: ${formatRupiah(vehicle.last_month)}</div></div></div>`);
                });

                const rows = [{ type: 'Car', thisMonthIncome: this_Month.totals.carincome, lastMonthIncome: last_Month.totals.carincome }, { type: 'Motorbike', thisMonthIncome: this_Month.totals.motorbikeincome, lastMonthIncome: last_Month.totals.motorbikeincome }, { type: 'Truck', thisMonthIncome: this_Month.totals.truckincome, lastMonthIncome: last_Month.totals.truckincome }, { type: 'Taxi', thisMonthIncome: this_Month.totals.taxiincome, lastMonthIncome: last_Month.totals.taxiincome }, { type: 'Lost Ticket', thisMonthIncome: this_Month.totals.ticketincome, lastMonthIncome: last_Month.totals.ticketincome }, { type: 'Other', thisMonthIncome: this_Month.totals.otherincome, lastMonthIncome: last_Month.totals.otherincome }, ];
                monthlyIncomeTable.clear().rows.add(rows.map((item, index) => ({ no: index + 1, vehicle: item.type, this_month: formatRupiah(item.thisMonthIncome), last_month: formatRupiah(item.lastMonthIncome) }))).draw();
                $('#monthlyIncome tfoot').html(`<tr><th colspan="2" style="text-align:left">All Casual Income</th><th style="font-size:12px;">${formatRupiah(last_Month.totals.vehicleincome)}</th><th style="font-size:12px;">${formatRupiah(this_Month.totals.vehicleincome)}</th></tr><tr><th colspan="2" style="text-align:left">All Sticker Income</th><th style="font-size:12px;">${formatRupiah(last_Month.totals.stickerincome)}</th><th style="font-size:12px;">${formatRupiah(this_Month.totals.stickerincome)}</th></tr>`);
                
                if (window.monthlyIncomeBarChart) window.monthlyIncomeBarChart.destroy();
                if (window.monthlyIncomeLineChart) window.monthlyIncomeLineChart.destroy();
                
                const labels = Object.keys(this_Month.weekly_totals);
                const vehicleTypes = ['car', 'motorbike', 'truck', 'taxi', 'other', 'vehicle'];
                const colors = ['#51AA20', '#DB6715', '#8D60ED', '#C46EA6', '#5a0e0eff', '#D3D6DD'];
                const datasets = vehicleTypes.map((v, i) => ({ label: v.charAt(0).toUpperCase() + v.slice(1), data: labels.map(label => this_Month.weekly_totals[label][`${v}income`]), backgroundColor: colors[i], borderColor: colors[i], hidden: i > 0 }));
                
                const commonOptions = { 
                    responsive: true, 
                    maintainAspectRatio: false, 
                    plugins: { 
                        legend: { position: 'top', labels: { /* color diatur oleh fungsi tema */ } }, 
                        datalabels: { 
                            backgroundColor: ctx => ctx.dataset.backgroundColor, 
                            borderRadius: 4, 
                            color: 'white', 
                            font: { weight: 'bold' }, 
                            formatter: val => formatRupiah(val), 
                            padding: 6, 
                            offset: 8, 
                            anchor: 'end', 
                            align: 'end' 
                        } 
                    }, 
                    scales: { 
                        y: { beginAtZero: true, ticks: { precision: 0 }, grid:{}, grace: '10%' }, 
                        x: { ticks: {}, grid:{} } 
                    } 
                };
                
                applyThemeToChartOptions(commonOptions); // Terapkan tema

                const ctxBar = document.getElementById('monthlyIncomeBar')?.getContext('2d');
                if (ctxBar) window.monthlyIncomeBarChart = new Chart(ctxBar, { type: 'bar', data: { labels, datasets }, options: commonOptions, plugins: [ChartDataLabels] });

                const lineOptions = { ...commonOptions, plugins: { ...commonOptions.plugins, datalabels: { ...commonOptions.plugins.datalabels, padding: 3, offset: 4 }}};
                const ctxLine = document.getElementById('monthlyIncomeLine')?.getContext('2d');
                if (ctxLine) window.monthlyIncomeLineChart = new Chart(ctxLine, { type: 'line', data: { labels, datasets: datasets.map(ds => ({ ...ds, borderWidth: 3, tension: 0.5, fill: false })) }, options: lineOptions, plugins: [ChartDataLabels] });
            }
        });
    }

    /**
     * Fungsi utama untuk memuat semua data pendapatan secara efisien.
     */
    async function loadAllIncomeData() {
        console.log("Memuat ulang semua data pendapatan...");
        const promises = [
            fetchDailyIncome(),
            fetchWeeklyIncome(),
            fetchMonthlyIncome()
        ];

        try {
            await Promise.all(promises);
            console.log("Semua data pendapatan berhasil dimuat ulang.");
        } catch (error) {
            console.error("Terjadi kesalahan saat memuat ulang data pendapatan:", error);
        }
    }

    // --- INISIALISASI SKRIP ---
    loadAllIncomeData();
    setInterval(loadAllIncomeData, 5000);
});
