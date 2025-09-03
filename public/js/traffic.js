$(document).ready(function() {
    // --- CHART INSTANCES ---
    let dailyPmTodayChart, dailyPmYesterdayChart, dailyPkTodayChart, dailyPkYesterdayChart;
    let weeklyPmTodayChart, weeklyPmYesterdayChart, weeklyPkTodayChart, weeklyPkYesterdayChart;
    let monthlyPmTodayChart, monthlyPmYesterdayChart, monthlyPkTodayChart, monthlyPkYesterdayChart;

    // --- UTILITY FUNCTIONS ---
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

    // --- DATATABLE INITIALIZATION ---
    const dtOptions = {
        searching: false,
        paging: false,
        autoWidth: false,
        ordering: false,
        info: false,
        data: []
    };
    
    // Daily Tables
    const tableDailyTrafficPMToday = $('#DailyPintuMasukTodayTable').DataTable({...dtOptions, columns: [{data: 'no'},{data: 'kodepm'},{data: 'namapm'},{data: 'quantity'}]});
    const tableDailyTrafficPMYesterday = $('#DailyPintuMasukYesterdayTable').DataTable({...dtOptions, columns: [{data: 'no'},{data: 'kodepm'},{data: 'namapm'},{data: 'quantity'}]});
    const tableDailyTrafficPKToday = $('#DailyPintuKeluarTodayTable').DataTable({...dtOptions, columns: [{data: 'no'},{data: 'kodepm'},{data: 'namapm'},{data: 'quantity'}]});
    const tableDailyTrafficPKYesterday = $('#DailyPintuKeluarYesterdayTable').DataTable({...dtOptions, columns: [{data: 'no'},{data: 'kodepm'},{data: 'namapm'},{data: 'quantity'}]});
    
    // Weekly Tables
    const tableWeeklyTrafficPM = $('#weeklyPintuMasukTable').DataTable({...dtOptions, columns: [{data: 'no'},{data: 'kodepm'},{data: 'namapm'},{data: 'quantity'}]});
    const tableLastWeeklyTrafficPM = $('#LastweeklyPintuMasukTable').DataTable({...dtOptions, columns: [{data: 'no'},{data: 'kodepm'},{data: 'namapm'},{data: 'quantity'}]});
    const tableWeeklyTrafficPK = $('#weeklyPintuKeluarTable').DataTable({...dtOptions, columns: [{data: 'no'},{data: 'kodepm'},{data: 'namapm'},{data: 'quantity'}]});
    const tableLastWeeklyTrafficPK = $('#LastweeklyPintuKeluarTable').DataTable({...dtOptions, columns: [{data: 'no'},{data: 'kodepm'},{data: 'namapm'},{data: 'quantity'}]});

    // Monthly Tables
    const tableMonthlyTrafficPM = $('#monthlyPintuMasukTable').DataTable({...dtOptions, columns: [{data: 'no'},{data: 'kodepm'},{data: 'namapm'},{data: 'quantity'}]});
    const tableLastMonthlyTrafficPM = $('#LastmonthlyPintuMasukTable').DataTable({...dtOptions, columns: [{data: 'no'},{data: 'kodepm'},{data: 'namapm'},{data: 'quantity'}]});
    const tableMonthlyTrafficPK = $('#monthlyPintuKeluarTable').DataTable({...dtOptions, columns: [{data: 'no'},{data: 'kodepm'},{data: 'namapm'},{data: 'quantity'}]});
    const tableLastMonthlyTrafficPK = $('#LastmonthlyPintuKeluarTable').DataTable({...dtOptions, columns: [{data: 'no'},{data: 'kodepm'},{data: 'namapm'},{data: 'quantity'}]});


    function fetchDailyTraffic() {
        return $.ajax({
            url: dailyTrafficURL,
            method: 'GET',
            success: function(response) {
                const pintumasukToday = response.data[0].pm_current_period;
                const pintumasukYesterday = response.data[0].pm_last_period;
                const pintukeluarToday = response.data[0].pk_current_period;
                const pintuKeluarYesterday = response.data[0].pk_last_period;

                // Update tables
                tableDailyTrafficPMToday.clear().rows.add(pintumasukToday.map((item, index) => ({ no: index + 1, kodepm: item.kodeposin, namapm: item.namapos, quantity: item.qty }))).draw();
                tableDailyTrafficPMYesterday.clear().rows.add(pintumasukYesterday.map((item, index) => ({ no: index + 1, kodepm: item.kodeposin, namapm: item.namapos, quantity: item.qty }))).draw();
                tableDailyTrafficPKToday.clear().rows.add(pintukeluarToday.map((item, index) => ({ no: index + 1, kodepm: item.kodeposout, namapm: item.namapos, quantity: item.qty }))).draw();
                tableDailyTrafficPKYesterday.clear().rows.add(pintuKeluarYesterday.map((item, index) => ({ no: index + 1, kodepm: item.kodeposout, namapm: item.namapos, quantity: item.qty }))).draw();

                // Prepare chart data
                const labelsPM = pintumasukToday.map(item => item.namapos);
                const labelsPK = pintukeluarToday.map(item => item.namapos);
                const dataPMToday = pintumasukToday.map(item => item.qty);
                const dataPMYesterday = pintumasukYesterday.map(item => item.qty);
                const dataPKToday = pintukeluarToday.map(item => item.qty);
                const dataPKYesterday = pintuKeluarYesterday.map(item => item.qty);
                
                function getRandomColor() {
                    const vibrantColors = ['#E6194B','#3CB44B','#0082C8','#FFD700','#911EB4','#F58231','#46F0F0','#F032E6','#008080','#FFE119','#4363D8','#DC143C'];
                    return vibrantColors[Math.floor(Math.random() * vibrantColors.length)];
                }

                const createBarConfig = (labels, data, label) => ({
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: label,
                            data: data,
                            backgroundColor: data.map(() => getRandomColor()),
                            borderWidth: 1,
                            datalabels: { anchor: 'end', align: 'end' }
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'top', labels: { color: '#000' } },
                            datalabels: { backgroundColor: (ctx) => ctx.dataset.backgroundColor, borderRadius: 4, color: 'white', font: { weight: 'bold' }, padding: 6, offset: 8 }
                        },
                        scales: { y: { beginAtZero: true, ticks: { precision: 0 }, grace: '10%' } }
                    },
                    plugins: [ChartDataLabels]
                });

                // Create or update charts
                dailyPmTodayChart = createOrUpdateChart(dailyPmTodayChart, 'DailyPintuMasukToday', createBarConfig(labelsPM, dataPMToday, 'Pintu Masuk Today'));
                dailyPmYesterdayChart = createOrUpdateChart(dailyPmYesterdayChart, 'DailyPintuMasukYesterday', createBarConfig(labelsPM, dataPMYesterday, 'Pintu Masuk Yesterday'));
                dailyPkTodayChart = createOrUpdateChart(dailyPkTodayChart, 'DailyPintuKeluarToday', createBarConfig(labelsPK, dataPKToday, 'Pintu Keluar Today'));
                dailyPkYesterdayChart = createOrUpdateChart(dailyPkYesterdayChart, 'DailyPintuKeluarYesterday', createBarConfig(labelsPK, dataPKYesterday, 'Pintu Keluar Yesterday'));
            },
            error: function(xhr, status, error) { console.error('Error fetching daily traffic data:', error); }
        });
    }

    function fetchWeeklyTraffic() {
         return $.ajax({
            url: weeklyTrafficURL,
            method: 'GET',
            success: function(response) {
                const { pm_current_period, pk_current_period, pm_last_period, pk_last_period } = response.data[0];

                // Update Tables
                tableWeeklyTrafficPM.clear().rows.add(pm_current_period.map((item, i) => ({no: i+1, kodepm: item.kodeposin, namapm: item.namapos, quantity: item.qty}))).draw();
                tableLastWeeklyTrafficPM.clear().rows.add(pm_last_period.map((item, i) => ({no: i+1, kodepm: item.kodeposin, namapm: item.namapos, quantity: item.qty}))).draw();
                tableWeeklyTrafficPK.clear().rows.add(pk_current_period.map((item, i) => ({no: i+1, kodepm: item.kodeposout, namapm: item.namapos, quantity: item.qty}))).draw();
                tableLastWeeklyTrafficPK.clear().rows.add(pk_last_period.map((item, i) => ({no: i+1, kodepm: item.kodeposout, namapm: item.namapos, quantity: item.qty}))).draw();

                // Prepare Chart Data
                const labelsPM = pm_current_period.map(item => item.namapos);
                const labelsPK = pk_current_period.map(item => item.namapos);
                
                const createBarConfig = (labels, data, label) => ({
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{ label: label, data: data, backgroundColor: data.map(() => '#'+(Math.random().toString(16)+'000000').slice(2, 8)), borderWidth: 1, datalabels: { anchor: 'end', align: 'end' } }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'top', labels: { color: '#000'}}, datalabels: { backgroundColor: (ctx) => ctx.dataset.backgroundColor, borderRadius: 4, color: 'white', font: { weight: 'bold' }, padding: 6, offset: 8}}, scales: { y: { beginAtZero: true, ticks: { precision: 0 }, grace: '10%' }}},
                    plugins: [ChartDataLabels]
                });

                weeklyPmTodayChart = createOrUpdateChart(weeklyPmTodayChart, 'WeeklyPintuMasukToday', createBarConfig(labelsPM, pm_current_period.map(item => item.qty), 'Pintu Masuk This Week'));
                weeklyPmYesterdayChart = createOrUpdateChart(weeklyPmYesterdayChart, 'WeeklyPintuMasukYesterday', createBarConfig(labelsPM, pm_last_period.map(item => item.qty), 'Pintu Masuk Last Week'));
                weeklyPkTodayChart = createOrUpdateChart(weeklyPkTodayChart, 'WeeklyPintuKeluarToday', createBarConfig(labelsPK, pk_current_period.map(item => item.qty), 'Pintu Keluar This Week'));
                weeklyPkYesterdayChart = createOrUpdateChart(weeklyPkYesterdayChart, 'WeeklyPintuKeluarYesterday', createBarConfig(labelsPK, pk_last_period.map(item => item.qty), 'Pintu Keluar Last Week'));
            },
            error: function(xhr, status, error) { console.error('Error fetching weekly traffic data:', error); }
        });
    }

    function fetchMonthlyTraffic() {
        return $.ajax({
            url: monthlyTrafficURL,
            method: 'GET',
            success: function(response) {
                const { pm_current_period, pk_current_period, pm_last_period, pk_last_period } = response.data[0];

                // Update Tables
                tableMonthlyTrafficPM.clear().rows.add(pm_current_period.map((item, i) => ({no: i+1, kodepm: item.kodeposin, namapm: item.namapos, quantity: item.qty}))).draw();
                tableLastMonthlyTrafficPM.clear().rows.add(pm_last_period.map((item, i) => ({no: i+1, kodepm: item.kodeposin, namapm: item.namapos, quantity: item.qty}))).draw();
                tableMonthlyTrafficPK.clear().rows.add(pk_current_period.map((item, i) => ({no: i+1, kodepm: item.kodeposout, namapm: item.namapos, quantity: item.qty}))).draw();
                tableLastMonthlyTrafficPK.clear().rows.add(pk_last_period.map((item, i) => ({no: i+1, kodepm: item.kodeposout, namapm: item.namapos, quantity: item.qty}))).draw();

                // Prepare Chart Data
                const labelsPM = pm_current_period.map(item => item.namapos);
                const labelsPK = pk_current_period.map(item => item.namapos);
                
                const createBarConfig = (labels, data, label) => ({
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{ label: label, data: data, backgroundColor: data.map(() => '#'+(Math.random().toString(16)+'000000').slice(2, 8)), borderWidth: 1, datalabels: { anchor: 'end', align: 'end' } }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'top', labels: { color: '#000'}}, datalabels: { backgroundColor: (ctx) => ctx.dataset.backgroundColor, borderRadius: 4, color: 'white', font: { weight: 'bold' }, padding: 6, offset: 8}}, scales: { y: { beginAtZero: true, ticks: { precision: 0 }, grace: '10%' }}},
                    plugins: [ChartDataLabels]
                });

                monthlyPmTodayChart = createOrUpdateChart(monthlyPmTodayChart, 'MonthlyPintuMasukToday', createBarConfig(labelsPM, pm_current_period.map(item => item.qty), 'Pintu Masuk This Month'));
                monthlyPmYesterdayChart = createOrUpdateChart(monthlyPmYesterdayChart, 'MonthlyPintuMasukYesterday', createBarConfig(labelsPM, pm_last_period.map(item => item.qty), 'Pintu Masuk Last Month'));
                monthlyPkTodayChart = createOrUpdateChart(monthlyPkTodayChart, 'MonthlyPintuKeluarToday', createBarConfig(labelsPK, pk_current_period.map(item => item.qty), 'Pintu Keluar This Month'));
                monthlyPkYesterdayChart = createOrUpdateChart(monthlyPkYesterdayChart, 'MonthlyPintuKeluarYesterday', createBarConfig(labelsPK, pk_last_period.map(item => item.qty), 'Pintu Keluar Last Month'));
            },
            error: function(xhr, status, error) { console.error('Error fetching monthly traffic data:', error); }
        });
    }
    
    async function loadAllTrafficData() {
        console.log("Refreshing all traffic data...");
        try {
            await Promise.all([
                fetchDailyTraffic(),
                fetchWeeklyTraffic(),
                fetchMonthlyTraffic()
            ]);
            console.log("All traffic data refreshed successfully.");
        } catch (error) {
            console.error("An error occurred while refreshing traffic data:", error);
        }
    }

    // --- SCRIPT INITIALIZATION ---
    loadAllTrafficData();
    setInterval(loadAllTrafficData, 15000); // Refresh data every 15 seconds
});
