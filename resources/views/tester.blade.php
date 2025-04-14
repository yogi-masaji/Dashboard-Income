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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            border: 2px solid #9C9C9C;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease-in-out;
            overflow: hidden;
            padding: 10px;
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
                    <canvas id="dailyQuantityBar"></canvas>
                </div>
                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab"
                    tabindex="0"></div>
            </div>
        </div>
        <div id="dataB">
            <h5>Weekly Quantity</h5>
            <table id="weeklyQuantity" class="table table-striped table-bordered">
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

                        const data = {
                            labels: labels,
                            datasets: [{
                                label: 'Casual',
                                data: casualData,
                                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            }, {
                                label: 'Pass',
                                data: passData,
                                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }]
                        };

                        const config = {
                            type: 'bar',
                            data: data,
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'top'
                                    },
                                    title: {
                                        display: true,
                                        text: 'Today\'s Vehicle Quantity (Casual vs Pass)'
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            precision: 0
                                        }
                                    }
                                }
                            }
                        };

                        const ctx = document.getElementById('dailyQuantityBar')?.getContext('2d');
                        if (ctx) {
                            new Chart(ctx, config);
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
                mainContent.innerHTML = '';
                sideContent1.innerHTML = '';
                sideContent2.innerHTML = '';

                const first = contentData[order[0]].cloneNode(true);
                const second = contentData[order[1]].cloneNode(true);
                const third = contentData[order[2]].cloneNode(true);

                first.classList.add('fade-in');
                second.classList.add('fade-in');
                third.classList.add('fade-in');

                mainContent.appendChild(first);
                sideContent1.appendChild(second);
                sideContent2.appendChild(third);
                setTimeout(() => {
                    if (order[0] === 'A') {
                        renderDailyChart(); // fungsi render chart kamu ekstrak ke sini
                    }
                }, 0);
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
    <script>
        $(document).ready(function() {
            const kodeLokasi = @json($kodeLokasi);

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

                    const data = {
                        labels: labels,
                        datasets: [{
                                label: 'Casual',
                                data: casualData,
                                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Pass',
                                data: passData,
                                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }
                        ]
                    };

                    const config = {
                        type: 'bar',
                        data: data,
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top'
                                },
                                title: {
                                    display: true,
                                    text: 'Today\'s Vehicle Quantity (Casual vs Pass)'
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0
                                    }
                                }
                            }
                        }
                    };

                    const ctx = document.getElementById('dailyQuantityBar')?.getContext('2d');
                    if (ctx) {
                        new Chart(ctx, config);
                    } else {
                        console.error("Canvas untuk Chart tidak ditemukan.");
                    }
                }
            });
        });
    </script>
@endsection
