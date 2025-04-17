@php
    $lokasiName = session('selected_location_name', 'Lokasi Default');
    $ipLokasi = session('selected_location_ip_lokasi', 'IP Tidak Diketahui');
    $lokasiId = session('selected_location_id', 0);
    $lokasiGrup = session('selected_location_id_grup', 'Group Tidak Diketahui');
    $kodeLokasi = session('selected_location_kode_lokasi', 'Kode Tidak Diketahui');
    $navdonutTitle = $lokasiName;
@endphp

<style>
    .custom-nav {
        background-color: #175390 !important;
        padding: 10px;
        border-radius: 10px;
        width: 40% !important;
    }

    .nav-pills~.tab-content {
        box-shadow: none !important;
    }

    .tab-content {
        background-color: transparent !important;
        width: 100% !important;
    }

    .dashboard-card {
        background-color: #2A3A5A;
        border-radius: 10px;
        border: 2px solid #D9D9D9;
        padding: 20px;
        margin-bottom: 15px;
    }

    .card-title {
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 5px;
        color: #FFFFFF !important;
    }

    .card-value {
        font-size: 42px;
        font-weight: 700;
        margin-bottom: 0;
        color: #FFFFFF;
    }

    .percentage {
        color: #ff4d4d;
        font-size: 14px;
        font-weight: 600;
    }

    .yesterday {
        font-size: 14px;
        opacity: 0.8;
        margin-top: 5px;
    }

    .tab-content:not(.doc-example-content) {
        padding: 0.5rem;
    }
</style>
<div class="nav nav-tabs custom-nav" id="inner-tab" role="tablist">
    <button class="nav-link active" id="inner-daily-tab" data-bs-toggle="tab" data-bs-target="#inner-daily" type="button"
        role="tab">Daily</button>
    <button class="nav-link" id="inner-weekly-tab" data-bs-toggle="tab" data-bs-target="#inner-weekly" type="button"
        role="tab">Weekly</button>
    <button class="nav-link" id="inner-monthly-tab" data-bs-toggle="tab" data-bs-target="#inner-monthly" type="button"
        role="tab">Monthly</button>
</div>

<div class="tab-content">
    <div class="tab-pane fade show active mt-5" id="inner-daily" role="tabpanel" aria-labelledby="inner-daily-tab">
        <h5>Daily Income</h5>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-md-6">
                        <table id="dailyIncome" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Vehicle</th>
                                    <th>Yesterday</th>
                                    <th>Today</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th colspan="2" style="text-align:left">All Vehicle</th>
                                    <th id="totalYesterday"></th>
                                    <th id="totalToday"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <canvas id="dailyIncomedonut" height="70"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="inner-weekly" role="tabpanel" aria-labelledby="inner-weekly-tab">
        <h5>Weekly Income</h5>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-md-6">
                        <table id="weeklyIncome" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Vehicle</th>
                                    <th>Last Week</th>
                                    <th>This Week</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th colspan="2" style="text-align:left">All Vehicle</th>
                                    <th id="totalLastWeek"></th>
                                    <th id="totalThisWeek"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-weeklyIncome-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-weeklyIncome" type="button" role="tab"
                                    aria-controls="nav-weeklyIncome" aria-selected="true">Bar</button>
                                <button class="nav-link" id="nav-weeklyIncome-line-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-weeklyIncome-line" type="button" role="tab"
                                    aria-controls="nav-weeklyIncome-line" aria-selected="false">Line</button>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-weeklyIncome" role="tabpanel"
                                aria-labelledby="nav-weeklyIncome-tab" tabindex="0">
                                <canvas id="weeklyIncomeBar" height="200" width="auto"></canvas>
                            </div>
                            <div class="tab-pane fade" id="nav-weeklyIncome-line" role="tabpanel"
                                aria-labelledby="nav-weeklyIncome-line-tab" tabindex="0">
                                <canvas id="weeklyIncomeLine" height="200" width="auto"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="inner-monthly" role="tabpanel" aria-labelledby="inner-monthly-tab">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-md-6">
                        <table id="monthlyIncome" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Vehicle</th>
                                    <th>Last Month</th>
                                    <th>This Month</th>
                                </tr>
                            </thead>
                            <tfoot></tfoot>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-monthlyIncome-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-monthlyIncome" type="button" role="tab"
                                    aria-controls="nav-monthlyIncome" aria-selected="true">Bar</button>
                                <button class="nav-link" id="nav-monthlyIncome-line-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-monthlyIncome-line" type="button" role="tab"
                                    aria-controls="nav-monthlyIncome-line" aria-selected="false">Line</button>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-monthlyIncome" role="tabpanel"
                                aria-labelledby="nav-monthlyIncome-tab" tabindex="0">
                                <canvas id="monthlyIncomeBar" height="200" width="auto"></canvas>1
                            </div>
                            <div class="tab-pane fade" id="nav-monthlyIncome-line" role="tabpanel"
                                aria-labelledby="nav-monthlyIncome-line-tab" tabindex="0">
                                <canvas id="monthlyIncomeLine" height="200" width="auto"></canvas>2
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        const kodeLokasi = @json($kodeLokasi);
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

        $.ajax({
            url: `http://110.0.100.70:8080/v3/api/daily-income?location_code=${kodeLokasi}`,
            method: 'GET',
            success: function(response) {
                const today = response.data[0].today[0];
                const yesterday = response.data[0].yesterday[0];

                const rows = [{
                        type: 'Car',
                        yesterday: yesterday.carincome,
                        today: today.carincome
                    },
                    {
                        type: 'Motorbike',
                        yesterday: yesterday.motorbikeincome,
                        today: today.motorbikeincome
                    },
                    {
                        type: 'Truck',
                        yesterday: yesterday.truckincome,
                        today: today.truckincome
                    },
                    {
                        type: 'Taxi',
                        yesterday: yesterday.taxiincome,
                        today: today.taxiincome
                    },
                    {
                        type: 'Lost Ticket',
                        yesterday: yesterday.ticketincome,
                        today: today.ticketincome
                    },
                    {
                        type: 'Other',
                        yesterday: yesterday.otherincome,
                        today: today.otherincome
                    },
                ];

                const formattedRows = rows.map((item, index) => ({
                    no: index + 1,
                    vehicle: item.type,
                    yesterday: formatRupiah(item.yesterday),
                    today: formatRupiah(item.today)
                }));

                table.rows.add(formattedRows).draw();

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

                const labels = ['Car', 'Motorbike', 'Truck', 'Taxi'];


                const donutData = {
                    labels: ['Car', 'Motorbike', 'Truck', 'Taxi'],
                    datasets: [{
                        label: 'Income by Vehicle',
                        data: [today.carincome, today.motorbikeincome, today
                            .truckincome, today.taxiincome
                        ],

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
                        plugins: {
                            legend: {
                                position: 'top'
                            },
                            datalabels: {
                                color: '#000',
                                borderColor: '#fff',
                                backgroundColor: 'rgba(255, 255, 255, 0.63)',
                                formatter: Math.round,
                                font: {
                                    weight: 'bold'
                                },
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
                    new Chart(ctx, donutConfig);
                }


            }
        });
        $.ajax({
            url: "{{ route('weeklyIncome') }}",
            method: 'GET',
            success: function(response) {
                const thisWeekIncome = response.this_week.totals;
                const lastWeekIncome = response.last_week.totals;
                console.log(thisWeekIncome);
                console.log(lastWeekIncome.carincome);
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

                console.log(formattedWeeklyRows);

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

                console.log(thisWeekIncomeBar);
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


                console.log(thisWeekIncomeBar.map(item => item.carincome));





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
                                formatter: Math.round,
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
                                formatter: Math.round,
                                padding: 3,
                                offset: 4
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
        $.ajax({
            url: "{{ route('monthlyIncome') }}",
            method: 'GET',
            success: function(response) {

                const thisMonthIncome = response.this_Month.totals;
                const lastMonthIncome = response.last_Month.totals;

                const rows = [{
                        type: 'Car',
                        thisMonthIncome: thisMonthIncome.carincome,
                        lastMonthIncome: lastMonthIncome.carincome
                    },
                    {
                        type: 'Motorbike',
                        thisMonthIncome: thisMonthIncome.motorbikeincome,
                        lastMonthIncome: lastMonthIncome.motorbikeincome
                    },
                    {
                        type: 'Truck',
                        thisMonthIncome: thisMonthIncome.truckincome,
                        lastMonthIncome: lastMonthIncome.truckincome
                    },
                    {
                        type: 'Taxi',
                        thisMonthIncome: thisMonthIncome.taxiincome,
                        lastMonthIncome: lastMonthIncome.taxiincome
                    },
                    {
                        type: 'lost ticket',
                        thisMonthIncome: thisMonthIncome.ticketincome,
                        lastMonthIncome: lastMonthIncome.ticketincome
                    },
                    {
                        type: 'Other',
                        thisMonthIncome: thisMonthIncome.otherincome,
                        lastMonthIncome: lastMonthIncome.otherincome
                    },
                ];

                const formattedMonthlyRows = rows.map((item, index) => ({
                    no: index + 1,
                    vehicle: item.type,
                    this_month: formatRupiah(item.thisMonthIncome),
                    last_month: formatRupiah(item.lastMonthIncome)
                }));

                monthlyIncomeTable.rows.add(formattedMonthlyRows).draw();

                $('#monthlyIncome tfoot').html(`
                        <tr>
                            <th colspan="2" style="text-align:left">All Casual Income</th>
                            <th id="totallastMonthIncome" style="font-size:12px;">${formatRupiah(lastMonthIncome.vehicleincome)}</th>
                            <th id="totalthisMonthIncome" style="font-size:12px;">${formatRupiah(thisMonthIncome.vehicleincome)}</th>
                        </tr>
                        <tr>
                            <th colspan="2" style="text-align:left">All Sticker Income</th>
                            <th id="totallastMonthIncome" style="font-size:12px;">${formatRupiah(lastMonthIncome.stickerincome)}</th>
                            <th id="totalthisMonthIncome" style="font-size:12px;">${formatRupiah(thisMonthIncome.stickerincome)}</th>
                        </tr>
                    `);




            }
        })
    });
</script>
