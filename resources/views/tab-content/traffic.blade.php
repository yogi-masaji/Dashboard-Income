<div class="nav nav-tabs custom-nav" id="inner-tab" role="tablist">
    <button class="nav-link active" id="inner-daily-traffic-tab" data-bs-toggle="tab" data-bs-target="#inner-daily-traffic"
        type="button" role="tab">daily-traffic</button>
    <button class="nav-link" id="inner-weekly-traffic-tab" data-bs-toggle="tab" data-bs-target="#inner-weekly-traffic"
        type="button" role="tab">weekly-traffic</button>
    <button class="nav-link" id="inner-monthly-traffic-tab" data-bs-toggle="tab" data-bs-target="#inner-monthly-traffic"
        type="button" role="tab">monthly-traffic</button>
</div>

<div class="tab-content mt-4">
    {{-- DAILY TAB --}}
    <div class="tab-pane fade show active" id="inner-daily-traffic" role="tabpanel"
        aria-labelledby="inner-daily-traffic-tab">
        <ul class="nav nav-pills nav-custom-tab mb-3 w-100" id="pills-tab-daily" role="tablist">
            <li class="nav-item flex-fill text-center" role="presentation">
                <button class="nav-link active w-100" id="pills-pintumasuk-tab-daily" data-bs-toggle="pill"
                    data-bs-target="#pills-pintumasuk-daily" type="button" role="tab"
                    aria-controls="pills-pintumasuk-daily" aria-selected="true">
                    Pintu Masuk
                </button>
            </li>
            <li class="nav-item flex-fill text-center" role="presentation">
                <button class="nav-link w-100" id="pills-pintukeluar-tab-daily" data-bs-toggle="pill"
                    data-bs-target="#pills-pintukeluar-daily" type="button" role="tab"
                    aria-controls="pills-pintukeluar-daily" aria-selected="false">
                    Pintu Keluar
                </button>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent-daily">
            <div class="tab-pane fade show active" id="pills-pintumasuk-daily" role="tabpanel"
                aria-labelledby="pills-pintumasuk-tab-daily">
                @include('nested-tab.dailyMasuk')
            </div>
            <div class="tab-pane fade" id="pills-pintukeluar-daily" role="tabpanel"
                aria-labelledby="pills-pintukeluar-tab-daily">
                @include('nested-tab.dailyKeluar')
            </div>
        </div>
    </div>

    {{-- WEEKLY TAB --}}
    <div class="tab-pane fade" id="inner-weekly-traffic" role="tabpanel" aria-labelledby="inner-weekly-traffic-tab">
        <ul class="nav nav-pills nav-custom-tab mb-3 w-100" id="pills-tab-weekly" role="tablist">
            <li class="nav-item flex-fill text-center" role="presentation">
                <button class="nav-link active w-100" id="pills-pintumasuk-tab-weekly" data-bs-toggle="pill"
                    data-bs-target="#pills-pintumasuk-weekly" type="button" role="tab"
                    aria-controls="pills-pintumasuk-weekly" aria-selected="true">
                    Pintu Masuk
                </button>
            </li>
            <li class="nav-item flex-fill text-center" role="presentation">
                <button class="nav-link w-100" id="pills-pintukeluar-tab-weekly" data-bs-toggle="pill"
                    data-bs-target="#pills-pintukeluar-weekly" type="button" role="tab"
                    aria-controls="pills-pintukeluar-weekly" aria-selected="false">
                    Pintu Keluar
                </button>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent-weekly">
            <div class="tab-pane fade show active" id="pills-pintumasuk-weekly" role="tabpanel"
                aria-labelledby="pills-pintumasuk-tab-weekly">
                @include('nested-tab.weeklyMasuk')
            </div>
            <div class="tab-pane fade" id="pills-pintukeluar-weekly" role="tabpanel"
                aria-labelledby="pills-pintukeluar-tab-weekly">
                @include('nested-tab.weeklyKeluar')
            </div>
        </div>
    </div>

    {{-- MONTHLY TAB --}}
    <div class="tab-pane fade" id="inner-monthly-traffic" role="tabpanel" aria-labelledby="inner-monthly-traffic-tab">
        <ul class="nav nav-pills nav-custom-tab mb-3 w-100" id="pills-tab-monthly" role="tablist">
            <li class="nav-item flex-fill text-center" role="presentation">
                <button class="nav-link active w-100" id="pills-pintumasuk-tab-monthly" data-bs-toggle="pill"
                    data-bs-target="#pills-pintumasuk-monthly" type="button" role="tab"
                    aria-controls="pills-pintumasuk-monthly" aria-selected="true">
                    Pintu Masuk
                </button>
            </li>
            <li class="nav-item flex-fill text-center" role="presentation">
                <button class="nav-link w-100" id="pills-pintukeluar-tab-monthly" data-bs-toggle="pill"
                    data-bs-target="#pills-pintukeluar-monthly" type="button" role="tab"
                    aria-controls="pills-pintukeluar-monthly" aria-selected="false">
                    Pintu Keluar
                </button>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent-monthly">
            <div class="tab-pane fade show active" id="pills-pintumasuk-monthly" role="tabpanel"
                aria-labelledby="pills-pintumasuk-tab-monthly">
                @include('nested-tab.monthlyMasuk')
            </div>
            <div class="tab-pane fade" id="pills-pintukeluar-monthly" role="tabpanel"
                aria-labelledby="pills-pintukeluar-tab-monthly">
                @include('nested-tab.monthlyKeluar')
            </div>
        </div>
    </div>
</div>

<script>
    const dailyTrafficURL = "{{ route('dailyTraffic') }}";
    const weeklyTrafficURL = "{{ route('weeklyTraffic') }}";
    const monthlyTrafficURL = "{{ route('monthlyTraffic') }}";
</script>

<script>
    $(document).ready(function() {
        // Initialize the DataTable for daily traffic
        const tableDailyTrafficPMToday = $('#DailyPintuMasukTodayTable').DataTable({
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
                    data: 'kodepm'
                },
                {
                    data: 'namapm'
                },
                {
                    data: 'quantity'
                }
            ]
        });
        const tableDailyTrafficPMYesterday = $('#DailyPintuMasukYesterdayTable').DataTable({
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
                    data: 'kodepm'
                },
                {
                    data: 'namapm'
                },
                {
                    data: 'quantity'
                }
            ]
        });
        const tableDailyTrafficPKToday = $('#DailyPintuKeluarTodayTable').DataTable({
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
                    data: 'kodepm'
                },
                {
                    data: 'namapm'
                },
                {
                    data: 'quantity'
                }
            ]
        });
        const tableDailyTrafficPKYesterday = $('#DailyPintuKeluarYesterdayTable').DataTable({
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
                    data: 'kodepm'
                },
                {
                    data: 'namapm'
                },
                {
                    data: 'quantity'
                }
            ]
        });

        const tableWeeklyTrafficPM = $('#weeklyPintuMasukTable').DataTable({
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
                    data: 'kodepm'
                },
                {
                    data: 'namapm'
                },
                {
                    data: 'quantity'
                }
            ]
        });
        const tableLastWeeklyTrafficPM = $('#LastweeklyPintuMasukTable').DataTable({
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
                    data: 'kodepm'
                },
                {
                    data: 'namapm'
                },
                {
                    data: 'quantity'
                }
            ]
        });
        const tableWeeklyTrafficPK = $('#weeklyPintuKeluarTable').DataTable({
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
                    data: 'kodepm'
                },
                {
                    data: 'namapm'
                },
                {
                    data: 'quantity'
                }
            ]
        });
        const tableLastWeeklyTrafficPK = $('#LastweeklyPintuKeluarTable').DataTable({
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
                    data: 'kodepm'
                },
                {
                    data: 'namapm'
                },
                {
                    data: 'quantity'
                }
            ]
        });

        const tableMonthlyTrafficPM = $('#monthlyPintuMasukTable').DataTable({
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
                    data: 'kodepm'
                },
                {
                    data: 'namapm'
                },
                {
                    data: 'quantity'
                }
            ]
        });
        const tableLastMonthlyTrafficPM = $('#LastmonthlyPintuMasukTable').DataTable({
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
                    data: 'kodepm'
                },
                {
                    data: 'namapm'
                },
                {
                    data: 'quantity'
                }
            ]
        });
        const tableMonthlyTrafficPK = $('#monthlyPintuKeluarTable').DataTable({
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
                    data: 'kodepm'
                },
                {
                    data: 'namapm'
                },
                {
                    data: 'quantity'
                }
            ]
        });
        const tableLastMonthlyTrafficPK = $('#LastmonthlyPintuKeluarTable').DataTable({
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
                    data: 'kodepm'
                },
                {
                    data: 'namapm'
                },
                {
                    data: 'quantity'
                }
            ]
        });

        $.ajax({
            url: dailyTrafficURL,
            method: 'GET',
            success: function(response) {
                const pintumasukToday = response.data[0].pm_current_period;
                const pintumasukYesterday = response.data[0].pm_last_period;
                const pintukeluarToday = response.data[0].pk_current_period;
                const pintuKeluarYesterday = response.data[0].pk_last_period;



                const formattedRowsTodayPM = pintumasukToday.map((item, index) => ({
                    no: index + 1,
                    kodepm: item.kodeposin,
                    namapm: item.namapos,
                    quantity: item.qty
                }));

                tableDailyTrafficPMToday.rows.add(formattedRowsTodayPM).draw();

                const formattedRowsYesterdayPM = pintumasukYesterday.map((item, index) => ({
                    no: index + 1,
                    kodepm: item.kodeposin,
                    namapm: item.namapos,
                    quantity: item.qty
                }));

                tableDailyTrafficPMYesterday.rows.add(formattedRowsYesterdayPM).draw();


                const formattedRowsTodayPK = pintukeluarToday.map((item, index) => ({
                    no: index + 1,
                    kodepm: item.kodeposout,
                    namapm: item.namapos,
                    quantity: item.qty
                }));
                tableDailyTrafficPKToday.rows.add(formattedRowsTodayPK).draw();

                const formattedRowsYesterdayPK = pintuKeluarYesterday.map((item, index) => ({
                    no: index + 1,
                    kodepm: item.kodeposout,
                    namapm: item.namapos,
                    quantity: item.qty
                }));

                tableDailyTrafficPKYesterday.rows.add(formattedRowsYesterdayPK).draw();


                const labels = pintumasukToday.map(item => item.namapos);
                const labelsPK = pintukeluarToday.map(item => item.namapos);
                const pintumasukTodayMap = pintumasukToday.map(item => item.qty);
                const pintumasukYesterdayMap = pintumasukYesterday.map(item => item.qty);
                const pintukeluarTodayMap = pintukeluarToday.map(item => item.qty);
                const pintukeluarYesterdayMap = pintuKeluarYesterday.map(item => item.qty);
                // console.log(pintumasukYesterdayMap);
                // console.log(labels);


                function getRandomColor() {
                    const vibrantColors = [
                        '#FF0000', // Red
                        '#00FF00', // Green
                        '#0000FF', // Blue
                        '#FFFF00', // Yellow
                        '#800080', // Purple
                        '#FFA500', // Orange
                        '#00FFFF', // Cyan
                        '#FFC0CB', // Pink
                        '#00FF7F', // Spring Green
                        '#FF1493', // Deep Pink
                        '#1E90FF', // Dodger Blue
                        '#FF6347' // Tomato
                    ];
                    const randomIndex = Math.floor(Math.random() * vibrantColors.length);
                    return vibrantColors[randomIndex];
                }

                const barDataPMToday = {
                    labels: labels, // Corrected this line to use the actual labels array
                    datasets: [{
                        label: 'Statistik', // You can set the label here
                        data: pintumasukTodayMap,
                        backgroundColor: pintumasukTodayMap.map(() => getRandomColor()),
                        borderWidth: 1,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    }]
                };
                const barConfig = {
                    type: 'bar',
                    data: barDataPMToday,
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'top'
                            },

                            datalabels: {
                                backgroundColor: (context) => context.dataset
                                    .backgroundColor,
                                borderRadius: 4,
                                color: 'white',
                                font: {
                                    weight: 'bold'
                                },

                                padding: 6,
                                offset: 8
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                },
                                grace: '10%'
                            }
                        }
                    },
                    plugins: [
                        ChartDataLabels
                    ] // make sure to include this if you're using CDN
                };

                const barDataPMYesterday = {
                    labels: labels, // Corrected this line to use the actual labels array
                    datasets: [{
                        label: 'Statistik', // You can set the label here
                        data: pintumasukYesterdayMap,
                        backgroundColor: pintumasukYesterdayMap.map(() =>
                            getRandomColor()),
                        borderWidth: 1,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    }]
                };
                const barConfigYesterdayPM = {
                    type: 'bar',
                    data: barDataPMYesterday,
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'top'
                            },

                            datalabels: {
                                backgroundColor: (context) => context.dataset
                                    .backgroundColor,
                                borderRadius: 4,
                                color: 'white',
                                font: {
                                    weight: 'bold'
                                },

                                padding: 6,
                                offset: 8
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                },
                                grace: '10%'
                            }
                        }
                    },
                    plugins: [
                        ChartDataLabels
                    ] // make sure to include this if you're using CDN
                };

                const barDataPKToday = {
                    labels: labelsPK, // Corrected this line to use the actual labels array
                    datasets: [{
                        label: 'Statistik', // You can set the label here
                        data: pintukeluarTodayMap,
                        backgroundColor: pintukeluarTodayMap.map(() =>
                            getRandomColor()),
                        borderWidth: 1,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    }]
                };
                const barConfigPKToday = {
                    type: 'bar',
                    data: barDataPKToday,
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'top'
                            },

                            datalabels: {
                                backgroundColor: (context) => context.dataset
                                    .backgroundColor,
                                borderRadius: 4,
                                color: 'white',
                                font: {
                                    weight: 'bold'
                                },

                                padding: 6,
                                offset: 8
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                },
                                grace: '10%'
                            }
                        }
                    },
                    plugins: [
                        ChartDataLabels
                    ] // make sure to include this if you're using CDN
                };

                const barDataPKYesterday = {
                    labels: labelsPK, // Corrected this line to use the actual labels array
                    datasets: [{
                        label: 'Statistik', // You can set the label here
                        data: pintukeluarYesterdayMap,
                        backgroundColor: pintukeluarYesterdayMap.map(() =>
                            getRandomColor()),
                        borderWidth: 1,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    }]
                };

                const barConfigPKYesterday = {
                    type: 'bar',
                    data: barDataPKYesterday,
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'top'
                            },

                            datalabels: {
                                backgroundColor: (context) => context.dataset
                                    .backgroundColor,
                                borderRadius: 4,
                                color: 'white',
                                font: {
                                    weight: 'bold'
                                },

                                padding: 6,
                                offset: 8
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                },
                                grace: '10%'
                            }
                        }
                    },
                    plugins: [
                        ChartDataLabels
                    ] // make sure to include this if you're using CDN
                };
                const ctxTodayPK = document.getElementById('DailyPintuKeluarToday')
                    ?.getContext('2d');
                if (ctxTodayPK) {
                    new Chart(ctxTodayPK, barConfigPKToday);
                }
                const ctxYesterdayPK = document.getElementById('DailyPintuKeluarYesterday')
                    ?.getContext('2d');
                if (ctxYesterdayPK) {
                    new Chart(ctxYesterdayPK, barConfigPKYesterday);
                }

                const ctxTodayPM = document.getElementById('DailyPintuMasukToday')?.getContext(
                    '2d');
                if (ctxTodayPM) {
                    new Chart(ctxTodayPM, barConfig);
                }
                const ctxYesterdayPM = document.getElementById('DailyPintuMasukYesterday')
                    ?.getContext('2d');
                if (ctxYesterdayPM) {
                    new Chart(ctxYesterdayPM, barConfigYesterdayPM);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching daily traffic data:', error);
            }
        });

        $.ajax({
            url: weeklyTrafficURL,
            method: 'GET',
            success: function(response) {
                const pintumasuk = response.data[0].pm_current_period;
                const pintukeluar = response.data[0].pk_current_period;
                const pintumasukLast = response.data[0].pm_last_period;
                const pintukeluarLast = response.data[0].pk_last_period;

                const formattedRowsPM = pintumasuk.map((item, index) => ({
                    no: index + 1,
                    kodepm: item.kodeposin,
                    namapm: item.namapos,
                    quantity: item.qty
                }));
                tableWeeklyTrafficPM.rows.add(formattedRowsPM).draw();

                const formattedRowsLastPM = pintumasukLast.map((item, index) => ({
                    no: index + 1,
                    kodepm: item.kodeposin,
                    namapm: item.namapos,
                    quantity: item.qty
                }));
                tableLastWeeklyTrafficPM.rows.add(formattedRowsLastPM).draw();

                const formattedRowsPK = pintukeluar.map((item, index) => ({
                    no: index + 1,
                    kodepm: item.kodeposout,
                    namapm: item.namapos,
                    quantity: item.qty
                }));
                tableWeeklyTrafficPK.rows.add(formattedRowsPK).draw();

                const formattedRowsLastPK = pintukeluarLast.map((item, index) => ({
                    no: index + 1,
                    kodepm: item.kodeposout,
                    namapm: item.namapos,
                    quantity: item.qty
                }));

                tableLastWeeklyTrafficPK.rows.add(formattedRowsLastPK).draw();

                const labels = pintumasuk.map(item => item.namapos);
                const labelsPK = pintukeluar.map(item => item.namapos);
                const pintumasukMap = pintumasuk.map(item => item.qty);
                const pintumasukLastMap = pintumasukLast.map(item => item.qty);
                const pintukeluarMap = pintukeluar.map(item => item.qty);
                const pintukeluarLastMap = pintukeluarLast.map(item => item.qty);
                // console.log(pintumasukLastMap);
                // console.log(labels);
                // console.log(pintumasukMap);
                // console.log(pintumasukLastMap);
                // console.log(pintukeluarMap);
                // console.log(pintukeluarLastMap);

                function getRandomColor() {
                    const vibrantColors = [
                        '#FF0000', // Red
                        '#00FF00', // Green
                        '#0000FF', // Blue
                        '#FFFF00', // Yellow
                        '#800080', // Purple
                        '#FFA500', // Orange
                        '#00FFFF', // Cyan
                        '#FFC0CB', // Pink
                        '#00FF7F', // Spring Green
                        '#FF1493', // Deep Pink
                        '#1E90FF', // Dodger Blue
                        '#FF6347' // Tomato
                    ];
                    const randomIndex = Math.floor(Math.random() * vibrantColors.length);
                    return vibrantColors[randomIndex];
                }

                const barDataPM = {
                    labels: labels, // Corrected this line to use the actual labels array
                    datasets: [{
                        label: 'Statistik', // You can set the label here
                        data: pintumasukMap,
                        backgroundColor: pintumasukMap.map(() => getRandomColor()),
                        borderWidth: 1,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    }]
                };

                const barConfigPM = {
                    type: 'bar',
                    data: barDataPM,
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'top'
                            },

                            datalabels: {
                                backgroundColor: (context) => context.dataset
                                    .backgroundColor,
                                borderRadius: 4,
                                color: 'white',
                                font: {
                                    weight: 'bold'
                                },

                                padding: 6,
                                offset: 8
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                },
                                grace: '10%'
                            }
                        }
                    },
                    plugins: [
                        ChartDataLabels
                    ] // make sure to include this if you're using CDN
                };

                const barDataLastPM = {
                    labels: labels, // Corrected this line to use the actual labels array
                    datasets: [{
                        label: 'Statistik', // You can set the label here
                        data: pintumasukLastMap,
                        backgroundColor: pintumasukLastMap.map(() => getRandomColor()),
                        borderWidth: 1,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    }]
                };

                const barConfigLastPM = {
                    type: 'bar',
                    data: barDataLastPM,
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'top'
                            },

                            datalabels: {
                                backgroundColor: (context) => context.dataset
                                    .backgroundColor,
                                borderRadius: 4,
                                color: 'white',
                                font: {
                                    weight: 'bold'
                                },

                                padding: 6,
                                offset: 8
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                },
                                grace: '10%'
                            }
                        }
                    },
                    plugins: [
                        ChartDataLabels
                    ] // make sure to include this if you're using CDN
                };

                const barDataPK = {
                    labels: labelsPK, // Corrected this line to use the actual labels array
                    datasets: [{
                        label: 'Statistik', // You can set the label here
                        data: pintukeluarMap,
                        backgroundColor: pintukeluarMap.map(() => getRandomColor()),
                        borderWidth: 1,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    }]
                };

                const barConfigPK = {
                    type: 'bar',
                    data: barDataPK,
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'top'
                            },

                            datalabels: {
                                backgroundColor: (context) => context.dataset
                                    .backgroundColor,
                                borderRadius: 4,
                                color: 'white',
                                font: {
                                    weight: 'bold'
                                },

                                padding: 6,
                                offset: 8
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                },
                                grace: '10%'
                            }
                        }
                    },
                    plugins: [
                        ChartDataLabels
                    ] // make sure to include this if you're using CDN
                };

                const barDataLastPK = {
                    labels: labelsPK, // Corrected this line to use the actual labels array
                    datasets: [{
                        label: 'Statistik', // You can set the label here
                        data: pintukeluarLastMap,
                        backgroundColor: pintukeluarLastMap.map(() => getRandomColor()),
                        borderWidth: 1,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    }]
                };

                const barConfigLastPK = {
                    type: 'bar',
                    data: barDataLastPK,
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'top'
                            },

                            datalabels: {
                                backgroundColor: (context) => context.dataset
                                    .backgroundColor,
                                borderRadius: 4,
                                color: 'white',
                                font: {
                                    weight: 'bold'
                                },

                                padding: 6,
                                offset: 8
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                },
                                grace: '10%'
                            }
                        }
                    },
                    plugins: [
                        ChartDataLabels
                    ] // make sure to include this if you're using CDN
                };

                const ctxPM = document.getElementById('WeeklyPintuMasukToday')?.getContext(
                    '2d');
                if (ctxPM) {
                    new Chart(ctxPM, barConfigPM);
                }
                const ctxLastPM = document.getElementById('WeeklyPintuMasukYesterday')
                    ?.getContext('2d');
                if (ctxLastPM) {
                    new Chart(ctxLastPM, barConfigLastPM);
                }
                const ctxPK = document.getElementById('WeeklyPintuKeluarToday')?.getContext(
                    '2d');
                if (ctxPK) {
                    new Chart(ctxPK, barConfigPK);
                }
                const ctxLastPK = document.getElementById('WeeklyPintuKeluarYesterday')
                    ?.getContext('2d');
                if (ctxLastPK) {
                    new Chart(ctxLastPK, barConfigLastPK);
                }


            },
            error: function(xhr, status, error) {
                console.error('Error fetching daily traffic data:', error);
            }
        })

        $.ajax({
            url: monthlyTrafficURL,
            method: 'GET',
            success: function(response) {
                const pintumasuk = response.data[0].pm_current_period;
                const pintukeluar = response.data[0].pk_current_period;
                const pintumasukLast = response.data[0].pm_last_period;
                const pintukeluarLast = response.data[0].pk_last_period;
                // console.log('ini monthly traffic', pintumasukLast);
                const formattedRowsPM = pintumasuk.map((item, index) => ({
                    no: index + 1,
                    kodepm: item.kodeposin,
                    namapm: item.namapos,
                    quantity: item.qty
                }));
                tableMonthlyTrafficPM.rows.add(formattedRowsPM).draw();

                const formattedRowsLastPM = pintumasukLast.map((item, index) => ({
                    no: index + 1,
                    kodepm: item.kodeposin,
                    namapm: item.namapos,
                    quantity: item.qty
                }));
                tableLastMonthlyTrafficPM.rows.add(formattedRowsLastPM).draw();

                const formattedRowsPK = pintukeluar.map((item, index) => ({
                    no: index + 1,
                    kodepm: item.kodeposout,
                    namapm: item.namapos,
                    quantity: item.qty
                }));
                tableMonthlyTrafficPK.rows.add(formattedRowsPK).draw();

                const formattedRowsLastPK = pintukeluarLast.map((item, index) => ({
                    no: index + 1,
                    kodepm: item.kodeposout,
                    namapm: item.namapos,
                    quantity: item.qty
                }));
                tableLastMonthlyTrafficPK.rows.add(formattedRowsLastPK).draw();

                const labels = pintumasuk.map(item => item.namapos);
                const labelsPK = pintukeluar.map(item => item.namapos);
                const pintumasukMap = pintumasuk.map(item => item.qty);
                const pintumasukLastMap = pintumasukLast.map(item => item.qty);
                const pintukeluarMap = pintukeluar.map(item => item.qty);
                const pintukeluarLastMap = pintukeluarLast.map(item => item.qty);



                // console.log(labels);
                // console.log(pintumasukMap);
                // console.log(pintumasukLastMap);
                //
                // console.log(pintukeluarMap);
                // console.log(pintukeluarLastMap);
                function getRandomColor() {
                    const vibrantColors = [
                        '#FF0000', // Red
                        '#00FF00', // Green
                        '#0000FF', // Blue
                        '#FFFF00', // Yellow
                        '#800080', // Purple
                        '#FFA500', // Orange
                        '#00FFFF', // Cyan
                        '#FFC0CB', // Pink
                        '#00FF7F', // Spring Green
                        '#FF1493', // Deep Pink
                        '#1E90FF', // Dodger Blue
                        '#FF6347' // Tomato
                    ];
                    const randomIndex = Math.floor(Math.random() * vibrantColors.length);
                    return vibrantColors[randomIndex];
                }

                const barDataPM = {
                    labels: labels, // Corrected this line to use the actual labels array
                    datasets: [{
                        label: 'Statistik', // You can set the label here
                        data: pintumasukMap,
                        backgroundColor: pintumasukMap.map(() => getRandomColor()),
                        borderWidth: 1,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    }]
                };

                const barConfigPM = {
                    type: 'bar',
                    data: barDataPM,
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'top'
                            },

                            datalabels: {
                                backgroundColor: (context) => context.dataset
                                    .backgroundColor,
                                borderRadius: 4,
                                color: 'white',
                                font: {
                                    weight: 'bold'
                                },

                                padding: 6,
                                offset: 8
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                },
                                grace: '10%'
                            }
                        }
                    },
                    plugins: [
                        ChartDataLabels
                    ] // make sure to include this if you're using CDN
                };

                const barDataLastPM = {
                    labels: labels, // Corrected this line to use the actual labels array
                    datasets: [{
                        label: 'Statistik', // You can set the label here
                        data: pintumasukLastMap,
                        backgroundColor: pintumasukLastMap.map(() => getRandomColor()),
                        borderWidth: 1,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    }]
                };

                const barConfigLastPM = {
                    type: 'bar',
                    data: barDataLastPM,
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'top'
                            },

                            datalabels: {
                                backgroundColor: (context) => context.dataset
                                    .backgroundColor,
                                borderRadius: 4,
                                color: 'white',
                                font: {
                                    weight: 'bold'
                                },

                                padding: 6,
                                offset: 8
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                },
                                grace: '10%'
                            }
                        }
                    },
                    plugins: [
                        ChartDataLabels
                    ] // make sure to include this if you're using CDN
                };

                const barDataPK = {
                    labels: labelsPK, // Corrected this line to use the actual labels array
                    datasets: [{
                        label: 'Statistik', // You can set the label here
                        data: pintukeluarMap,
                        backgroundColor: pintukeluarMap.map(() => getRandomColor()),
                        borderWidth: 1,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    }]
                };

                const barConfigPK = {
                    type: 'bar',
                    data: barDataPK,
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'top'
                            },

                            datalabels: {
                                backgroundColor: (context) => context.dataset
                                    .backgroundColor,
                                borderRadius: 4,
                                color: 'white',
                                font: {
                                    weight: 'bold'
                                },

                                padding: 6,
                                offset: 8
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                },
                                grace: '10%'
                            }
                        }
                    },
                    plugins: [
                        ChartDataLabels
                    ] // make sure to include this if you're using CDN
                };

                const barDataLastPK = {
                    labels: labelsPK, // Corrected this line to use the actual labels array
                    datasets: [{
                        label: 'Statistik', // You can set the label here
                        data: pintukeluarLastMap,
                        backgroundColor: pintukeluarLastMap.map(() => getRandomColor()),
                        borderWidth: 1,
                        datalabels: {
                            anchor: 'end',
                            align: 'end'
                        }
                    }]
                };

                const barConfigLastPK = {
                    type: 'bar',
                    data: barDataLastPK,
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'top'
                            },

                            datalabels: {
                                backgroundColor: (context) => context.dataset
                                    .backgroundColor,
                                borderRadius: 4,
                                color: 'white',
                                font: {
                                    weight: 'bold'
                                },

                                padding: 6,
                                offset: 8
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                },
                                grace: '10%'
                            }
                        }
                    },
                    plugins: [
                        ChartDataLabels
                    ] // make sure to include this if you're using CDN
                };

                const ctxPK = document.getElementById('MonthlyPintuKeluarToday')?.getContext(
                    '2d');
                if (ctxPK) {
                    new Chart(ctxPK, barConfigPK);
                }

                const ctxLastPK = document.getElementById('MonthlyPintuKeluarYesterday')
                    ?.getContext('2d');
                if (ctxLastPK) {
                    new Chart(ctxLastPK, barConfigLastPK);
                }


                const ctxPM = document.getElementById('MonthlyPintuMasukToday')?.getContext(
                    '2d');
                if (ctxPM) {
                    new Chart(ctxPM, barConfigPM);
                }
                const ctxLastPM = document.getElementById('MonthlyPintuMasukYesterday')
                    ?.getContext('2d');
                if (ctxLastPM) {
                    new Chart(ctxLastPM, barConfigLastPM);
                }

            },
        })

    });
</script>
