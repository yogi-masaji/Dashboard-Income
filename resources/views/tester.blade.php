@extends('layout.nav')
@section('content')
    @php
        $lokasiName = session('selected_location_name', 'Lokasi Default');
        $ipLokasi = session('selected_location_ip_lokasi', 'IP Tidak Diketahui');
        $lokasiId = session('selected_location_id', 0);
        $lokasiGrup = session('selected_location_id_grup', 'Group Tidak Diketahui');
        $kodeLokasi = session('selected_location_kode_lokasi', 'Kode Tidak Diketahui');
        $navbarTitle = $lokasiName;
    @endphp
    <link rel="stylesheet" href="css/main.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.0/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <!-- Toastr CSS -->
    <style>
        .btn-styling {
            background-color: #132248;
            padding-top: 10px;
            padding-bottom: 10px;
            border-radius: 10px;
            height: 60px;
        }



        .btn-custom,
        .btn-custom:hover,
        .btn-custom:active,
        .btn-custom:visited {
            background-color: #FCB900 !important;
            color: #000000 !important;
        }

        .nav-tabs .nav-link.active,
        .nav-tabs .nav-item.show .nav-link {
            color: #FCB900 !important;

            border-color: #FCB900 !important;
        }

        .btn:not(.btn-custom) {
            /* background-color: transparent; */
            /* No background */
            color: #FFFFFF;

        }

        .btn:not(.btn-custom):hover {
            /* background-color: #f0f0f0; */
            /* Light hover background */
            color: #FFFFFF;
            /* White text on hover */
        }

        .content-box {
            background-color: #132248;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease-in-out;
            overflow: hidden;
            padding: 20px;
            border-radius: 10px;
        }

        .main-content {
            height: auto;
        }

        .side-content {
            height: auto;
        }

        .fade-in {
            animation: fadeIn 0.3s forwards;
        }

        .tab-content:not(.doc-example-content) {
            padding: 0px !important;
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

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
    <div class="container py-5">
        <!-- Buttons -->

        <div class="row mb-4 align-items-start">
            <div class="col-8 ">

                <h1>Transaction</h1>
            </div>
            <div class="col-4 d-flex gap-3 btn-styling align-items-end">
                <button id="btnA" class="btn btn-custom px-4 py-2" style="width: 120px;">Daily</button>
                <button id="btnB" class="btn px-4 py-2" style="width: 120px;">Weekly</button>
                <button id="btnC" class="btn px-4 py-2" style="width: 120px;">Monthly</button>
            </div>
        </div>

        <!-- Content Area -->
        <div class="row">
            <!-- Main Content -->
            <div class="col-8 pe-3">
                <div id="mainContent" class="content-box main-content"></div>
            </div>

            <!-- Side Content -->
            <div class="col-4 d-flex flex-column gap-3">
                <div id="sideContent1" class="content-box side-content"></div>
                <div id="sideContent2" class="content-box side-content"></div>
            </div>
        </div>
    </div>

    <!-- Hidden Templates -->
    <div id="templateContainer" class="d-none">
        <div id="dataA">
            <h5>Daily Quantity</h5>
            <div class="row" id="dashboardRow">
                @foreach ($processedData as $data)
                    <!-- Total Casual Card -->
                    <div class="col-md-4">
                        <div class="dashboard-card">
                            <div class="card-title">{{ $data['label'] }}</div>
                            <div class="d-flex align-items-baseline">
                                <h2 class="card-value">{{ $data['today'] }}</h2>
                                <span class=" ms-2" style="color: {{ $data['color'] }}"> {{ $data['percent_change'] }}%
                                    {{ $data['direction'] }}</span>
                            </div>
                            <div class="yesterday">Yesterday: {{ $data['yesterday'] }}</div>
                        </div>
                    </div>
                @endforeach


            </div>
            <table id="dailyQuantity" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Type</th>
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
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home"
                        type="button" role="tab" aria-controls="nav-home" aria-selected="true">Bar</button>
                    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile"
                        type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Line</button>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab"
                    tabindex="0">
                    <canvas id="dailyQuantityBar" height="200"></canvas>
                </div>
                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab"
                    tabindex="0">
                    <canvas id="dailyQuantityLine" height="200"></canvas>
                </div>
            </div>
        </div>
        <div id="dataB">
            <h5>Weekly Quantity</h5>
            <table id="weeklyQuantity" class="table ">
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
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-weekly-tab" data-bs-toggle="tab" data-bs-target="#nav-weekly"
                        type="button" role="tab" aria-controls="nav-weekly" aria-selected="true">Bar</button>
                    <button class="nav-link" id="nav-weekly-line-tab" data-bs-toggle="tab" data-bs-target="#nav-weekly-line"
                        type="button" role="tab" aria-controls="nav-weekly-line"
                        aria-selected="false">Line</button>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-weekly" role="tabpanel" aria-labelledby="nav-weekly-tab"
                    tabindex="0">
                    <canvas id="weeklyQuantityBar" height="200" width="auto"></canvas>
                </div>
                <div class="tab-pane fade" id="nav-weekly-line" role="tabpanel" aria-labelledby="nav-weekly-line-tab"
                    tabindex="0">
                    <canvas id="weeklyQuantityLine" height="200" width="auto"></canvas>
                </div>
            </div>
        </div>
        <div id="dataC">
            <h5>Casual Monthly Quantity</h5>
            <table id="monthlyQuantity" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Vehicle</th>
                        <th>Last Month</th>
                        <th>This Month</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th colspan="2" style="text-align:left">All Vehicle</th>
                        <th id="totalLastMonth"></th>
                        <th id="totalThisMonth"></th>
                    </tr>
                </tfoot>
            </table>
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-monthly-tab" data-bs-toggle="tab"
                        data-bs-target="#nav-monthly" type="button" role="tab" aria-controls="nav-monthly"
                        aria-selected="true">Bar</button>
                    <button class="nav-link" id="nav-monthly-line-tab" data-bs-toggle="tab"
                        data-bs-target="#nav-monthly-line" type="button" role="tab"
                        aria-controls="nav-monthly-line" aria-selected="false">Line</button>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-monthly" role="tabpanel"
                    aria-labelledby="nav-monthly-tab" tabindex="0">
                    <canvas id="monthlyQuantityBar" height="200" width="auto"></canvas>

                </div>
                <div class="tab-pane fade" id="nav-monthly-line" role="tabpanel" aria-labelledby="nav-monthly-line-tab"
                    tabindex="0">
                    <canvas id="monthlyQuantityLine" height="200" width="auto"></canvas>

                </div>
            </div>
        </div>
    </div>

    {{-- Daily Table --}}
    <script>
        $(document).ready(function() {
            const kodeLokasi = @json($kodeLokasi);

            // DataTable initialization
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

            // Fetch and populate daily quantity
            $.ajax({
                url: `http://110.0.100.70:8080/v3/api/daily-quantity?location_code=${kodeLokasi}`,
                method: 'GET',
                success: function(response) {
                    const today = response.data[0].today[0];
                    const yesterday = response.data[0].yesterday[0];

                    const rows = [{
                            type: 'Total Casual',
                            yesterday: yesterday.grandcasual,
                            today: today.grandcasual
                        },
                        {
                            type: 'Total Pass',
                            yesterday: yesterday.grandpass,
                            today: today.grandpass
                        }
                    ];

                    const formattedRows = rows.map((item, index) => ({
                        no: index + 1,
                        type: item.type,
                        yesterday: item.yesterday,
                        today: item.today
                    }));

                    table.rows.add(formattedRows).draw();

                    $('#dailyQuantity tfoot').html(`
                        <tr>
                            <th colspan="2" style="text-align:left">All Vehicle</th>
                            <th id="totalYesterday">${yesterday.grandtotal}</th>
                            <th id="totalToday">${today.grandtotal}</th>
                        </tr>
                    `);

                    renderContent();
                }
            });

            // Fetch and populate weekly quantity
            $.ajax({
                url: "{{ route('weeklyTransaction') }}",
                method: 'GET',
                success: function(response) {
                    const thisWeek = response.this_week.totals.casual;
                    const lastWeek = response.last_week.totals.casual;

                    const rows = [{
                            type: 'Car',
                            thisWeek: thisWeek.total_car,
                            lastWeek: lastWeek.total_car
                        },
                        {
                            type: 'Motorbike',
                            thisWeek: thisWeek.total_motorbike,
                            lastWeek: lastWeek.total_motorbike
                        },
                        {
                            type: 'Truck',
                            thisWeek: thisWeek.total_truck,
                            lastWeek: lastWeek.total_truck
                        },
                        {
                            type: 'Taxi',
                            thisWeek: thisWeek.total_taxi,
                            lastWeek: lastWeek.total_taxi
                        }
                    ];

                    const formattedWeeklyRows = rows.map((item, index) => ({
                        no: index + 1,
                        vehicle: item.type,
                        this_week: item.thisWeek,
                        last_week: item.lastWeek
                    }));

                    weeklyTable.rows.add(formattedWeeklyRows).draw();

                    $('#weeklyQuantity tfoot').html(`
                        <tr>
                            <th colspan="2" style="text-align:left">All Vehicle</th>
                            <th id="totalLastWeek">${lastWeek.total_vehicle}</th>
                            <th id="totalThisWeek">${thisWeek.total_vehicle}</th>
                        </tr>
                    `);

                    renderContent();
                }
            });

            // Fetch and populate monthly quantity
            $.ajax({
                url: "{{ route('monthlyTransaction') }}",
                method: 'GET',
                success: function(response) {
                    const thisMonth = response.this_month.totals.casual;
                    const lastMonth = response.last_month.totals.casual;

                    const rows = [{
                            type: 'Car',
                            thisMonth: thisMonth.total_car,
                            lastMonth: lastMonth.total_car
                        },
                        {
                            type: 'Motorbike',
                            thisMonth: thisMonth.total_motorbike,
                            lastMonth: lastMonth.total_motorbike
                        },
                        {
                            type: 'Truck',
                            thisMonth: thisMonth.total_truck,
                            lastMonth: lastMonth.total_truck
                        },
                        {
                            type: 'Taxi',
                            thisMonth: thisMonth.total_taxi,
                            lastMonth: lastMonth.total_taxi
                        }
                    ];

                    const formattedMonthlyRows = rows.map((item, index) => ({
                        no: index + 1,
                        vehicle: item.type,
                        this_month: item.thisMonth,
                        last_month: item.lastMonth
                    }));

                    monthlyTable.rows.add(formattedMonthlyRows).draw();

                    $('#monthlyQuantity tfoot').html(`
                        <tr>
                            <th colspan="2" style="text-align:left">All Vehicle</th>
                            <th id="totalLastMonth">${lastMonth.total_vehicle}</th>
                            <th id="totalThisMonth">${thisMonth.total_vehicle}</th>
                        </tr>
                    `);

                    renderContent();
                }
            });

            function renderDailyChart() {
                $.ajax({
                    url: `http://110.0.100.70:8080/v3/api/daily-quantity?location_code=${kodeLokasi}`,
                    method: 'GET',
                    success: function(response) {
                        const today = response.data[0].today[0];

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

                        const barData = {
                            labels: labels,
                            datasets: [{
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
                                    label: 'Casual',
                                    data: casualData,
                                    borderColor: 'rgba(255, 0, 0, 1)',
                                    backgroundColor: 'rgba(255, 0, 0, 1)',
                                    borderWidth: 1,
                                    datalabels: {
                                        anchor: 'end',
                                        align: 'end'
                                    },
                                    tension: 0.5,
                                    borderWidth: 3

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
                                    },
                                    tension: 0.5,
                                    borderWidth: 3
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
                        const ctxLine = document.getElementById('dailyQuantityLine')?.getContext('2d');
                        if (ctxLine) {
                            new Chart(ctxLine, lineConfig);
                        }

                        const ctx = document.getElementById('dailyQuantityBar')?.getContext('2d');
                        if (ctx) {
                            new Chart(ctx, barConfig);
                        }
                    }
                });
            }

            function renderWeeklyChart() {
                $.ajax({
                    url: "{{ route('weeklyTransaction') }}",
                    method: 'GET',
                    success: function(response) {
                        const thisWeek = response.this_week.casual;

                        const labels = thisWeek.map(item => {
                            const date = new Date(item.tanggal);
                            return date.toLocaleDateString('en-GB', {
                                day: '2-digit',
                                month: 'short'
                            }); // hasilnya: 14 Apr, 15 Apr, dst.
                        });

                        const casualData = [
                            thisWeek.map(item => item.carcasual),
                            thisWeek.map(item => item.motorbikecasual),
                            thisWeek.map(item => item.taxicasual),
                            thisWeek.map(item => item.truckcasual),
                            thisWeek.map(item => item.othercasual),
                            thisWeek.map(item => item.vehiclecasual),
                            thisWeek.map(item => item.lostticketcasual),
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
                                    }
                                }, {
                                    label: 'Truck',
                                    data: casualData[2],
                                    backgroundColor: '#FFCD56',
                                    borderColor: '#FFCD56',
                                    borderWidth: 1,
                                    datalabels: {
                                        anchor: 'end',
                                        align: 'end'
                                    }
                                }, {
                                    label: 'Taxi',
                                    data: casualData[3],
                                    backgroundColor: '#32CD7D',
                                    borderColor: '#32CD7D',
                                    borderWidth: 1,
                                    datalabels: {
                                        anchor: 'end',
                                        align: 'end'
                                    }
                                }, {
                                    label: 'Vehicle',
                                    data: casualData[5],
                                    backgroundColor: '#E69500',
                                    borderColor: '#E69500',
                                    borderWidth: 1,
                                    datalabels: {
                                        anchor: 'end',
                                        align: 'end'
                                    }
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

                        const ctxLine = document.getElementById('weeklyQuantityLine')?.getContext('2d');
                        if (ctxLine) {
                            new Chart(ctxLine, lineConfig);
                        }

                        const ctx = document.getElementById('weeklyQuantityBar')?.getContext('2d');
                        if (ctx) {
                            new Chart(ctx, barConfig);
                        }
                    }
                });
            }

            function renderMonthlyChart() {
                $.ajax({
                    url: "{{ route('monthlyTransaction') }}",
                    method: 'GET',
                    success: function(response) {
                        const thisMonth = response.this_month.weekly_totals.casual;

                        const labels = Object.keys(thisMonth);

                        const totalCars = Object.values(thisMonth).map(week => week
                            .total_car);
                        const totalMotorbikes = Object.values(thisMonth).map(week => week
                            .total_motorbike);
                        const totalTrucks = Object.values(thisMonth).map(week => week
                            .total_truck);
                        const totalTaxis = Object.values(thisMonth).map(week => week
                            .total_taxi);
                        const totalVehicles = Object.values(thisMonth).map(week => week
                            .total_vehicle);


                        const barData = {
                            labels: labels,
                            datasets: [{
                                    label: 'Car',
                                    data: totalCars,
                                    backgroundColor: '#51AA20',
                                    borderColor: '#51AA20',
                                    borderWidth: 1,
                                    datalabels: {
                                        anchor: 'end',
                                        align: 'end'
                                    }
                                }, {
                                    label: 'Motorbike',
                                    data: totalMotorbikes,
                                    backgroundColor: '#DB6715',
                                    borderColor: '#DB6715',
                                    borderWidth: 1,
                                    datalabels: {
                                        anchor: 'end',
                                        align: 'end'
                                    }
                                }, {
                                    label: 'Truck',
                                    data: totalTrucks,
                                    backgroundColor: '#8D60ED',
                                    borderColor: '#8D60ED',
                                    borderWidth: 1,
                                    datalabels: {
                                        anchor: 'end',
                                        align: 'end'
                                    }
                                }, {
                                    label: 'Taxi',
                                    data: totalTaxis,
                                    backgroundColor: '#C46EA6',
                                    borderColor: '#C46EA6',
                                    borderWidth: 1,
                                    datalabels: {
                                        anchor: 'end',
                                        align: 'end'
                                    }
                                }, {
                                    label: 'Vehicle',
                                    data: totalVehicles,
                                    backgroundColor: '#D3D6DD',
                                    borderColor: '#D3D6DD',
                                    borderWidth: 1,
                                    datalabels: {
                                        anchor: 'end',
                                        align: 'end'
                                    }
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
                                    data: totalCars,
                                    backgroundColor: '#51AA20',
                                    borderColor: '#51AA20',
                                    borderWidth: 3,
                                    datalabels: {
                                        anchor: 'end',
                                        align: 'end'
                                    },
                                    tension: 0.5,

                                }, {
                                    label: 'Motorbike',
                                    data: totalMotorbikes,
                                    backgroundColor: '#DB6715',
                                    borderColor: '#DB6715',
                                    borderWidth: 3,
                                    datalabels: {
                                        anchor: 'end',
                                        align: 'end'
                                    },
                                    tension: 0.5,
                                }, {
                                    label: 'Truck',
                                    data: totalTrucks,
                                    backgroundColor: '#8D60ED',
                                    borderColor: '#8D60ED',
                                    borderWidth: 3,
                                    datalabels: {
                                        anchor: 'end',
                                        align: 'end'
                                    },
                                    tension: 0.5,
                                }, {
                                    label: 'Taxi',
                                    data: totalTaxis,
                                    backgroundColor: '#C46EA6',
                                    borderColor: '#C46EA6',
                                    borderWidth: 3,
                                    datalabels: {
                                        anchor: 'end',
                                        align: 'end'
                                    },
                                    tension: 0.5,

                                }, {
                                    label: 'Vehicle',
                                    data: totalVehicles,
                                    backgroundColor: '#D3D6DD',
                                    borderColor: '#D3D6DD',
                                    borderWidth: 3,
                                    datalabels: {
                                        anchor: 'end',
                                        align: 'end'
                                    },
                                    tension: 0.5,
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


                        const ctxLine = document.getElementById('monthlyQuantityLine')?.getContext(
                            '2d');
                        if (ctxLine) {
                            new Chart(ctxLine, lineConfig);
                        }
                        const ctx = document.getElementById('monthlyQuantityBar')?.getContext('2d');
                        if (ctx) {
                            new Chart(ctx, barConfig);
                        }
                    }
                });
            }

            // Switching layout logic
            const contentData = {
                A: document.getElementById('dataA'),
                B: document.getElementById('dataB'),
                C: document.getElementById('dataC')
            };

            const btnA = document.getElementById('btnA');
            const btnB = document.getElementById('btnB');
            const btnC = document.getElementById('btnC');
            const mainContent = document.getElementById('mainContent');
            const sideContent1 = document.getElementById('sideContent1');
            const sideContent2 = document.getElementById('sideContent2');

            let order = ['A', 'B', 'C'];

            function renderContent() {
                // Clear all content sections
                mainContent.innerHTML = '';
                sideContent1.innerHTML = '';
                sideContent2.innerHTML = '';

                // Clone content based on the current order
                const sections = order.map(idx => contentData[idx].cloneNode(true));

                // Add fade-in class to all sections at once
                sections.forEach(section => section.classList.add('fade-in'));

                // Update the visibility of #dashboardRow for each section
                sections.forEach((section, index) => {
                    const dashboardRow = section.querySelector('#dashboardRow');
                    if (dashboardRow) {
                        dashboardRow.classList.toggle('d-none', index !==
                        0); // Only show on the first section
                    }
                });

                // Append the sections to the appropriate containers
                mainContent.appendChild(sections[0]);
                sideContent1.appendChild(sections[1]);
                sideContent2.appendChild(sections[2]);

                // Use requestAnimationFrame for smoother rendering
                requestAnimationFrame(() => {
                    renderChartsForOrder(order[0]);
                });
            }

            // Optimized chart rendering function
            function renderChartsForOrder(orderType) {
                // Create an array of render functions
                const chartRenderFunctions = [renderDailyChart, renderWeeklyChart, renderMonthlyChart];

                // Ensure the orderType is valid and render the charts
                if (['A', 'B', 'C'].includes(orderType)) {
                    chartRenderFunctions.forEach(renderFunc => renderFunc());
                }
            }


            function updateButtons(active) {
                btnA.classList.remove('btn-custom');
                btnB.classList.remove('btn-custom');
                btnC.classList.remove('btn-custom');

                if (active === 'A') {
                    order = ['A', 'B', 'C'];
                    btnA.classList.add('btn-custom');
                } else if (active === 'B') {
                    order = ['B', 'A', 'C'];
                    btnB.classList.add('btn-custom');
                } else if (active === 'C') {
                    order = ['C', 'A', 'B'];
                    btnC.classList.add('btn-custom');
                }

                localStorage.setItem('activeButton', active);
                renderContent();
            }

            btnA.addEventListener('click', () => updateButtons('A'));
            btnB.addEventListener('click', () => updateButtons('B'));
            btnC.addEventListener('click', () => updateButtons('C'));

            const activeButton = localStorage.getItem('activeButton') || 'A';
            updateButtons(activeButton);
            renderContent();
        });
    </script>

    <!-- Chart JS untuk grafik daily -->
@endsection
