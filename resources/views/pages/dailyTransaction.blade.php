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
    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://code.highcharts.com/stock/highstock.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <style>

    </style>

    <h2>{{ $navbarTitle }} - {{ $ipLokasi }} - {{ $lokasiId }} - {{ $lokasiGrup }} - {{ $kodeLokasi }}</h2>
    <div class="p-1 p-sm-5">
        <div class="col-12">
            <div class="row g-4">
                <div class="col-8">
                    <div class="p-3 rounded shadow border">
                        <h5 class="px-5">Daily Quantity</h5>
                        <div class="table-responsive px-5">
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
                            <div class="d-flex custom-pagination mt-2">
                                {{-- {{ $tagTable->links('pagination::bootstrap-5') }} --}}
                            </div>
                        </div>
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                                    aria-selected="true">Bar</button>
                                <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile"
                                    aria-selected="false">Line</button>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                                aria-labelledby="nav-home-tab" tabindex="0">
                                <canvas id="dailyQuantityBar"></canvas>
                            </div>
                            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab"
                                tabindex="0">...</div>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-3 rounded shadow border" style="height: 200px;">
                        <h5 class="px-5">Casual Weekly Quantity</h5>
                        <div class="table-responsive p-2">
                            <table id="casualWeeklyQuantity" class="table table-striped table-bordered">
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
                                        <th id="totalYesterday"></th>
                                        <th id="totalToday"></th>
                                    </tr>
                                </tfoot>

                            </table>
                            <div class="d-flex custom-pagination mt-2">
                                {{-- {{ $tagTable->links('pagination::bootstrap-5') }} --}}
                            </div>
                        </div>

                    </div>
                    <div class="p-3 rounded shadow border mt-5" style="height: 200px;">
                        <h5>Casual Monthly Quantity</h5>
                    </div>
                </div>



            </div>


        </div>
    </div>
    {{-- Daily --}}
    <script>
        const kodeLokasi = @json($kodeLokasi);
        $(document).ready(function() {
            const table = $('#dailyQuantity').DataTable({
                searching: false,
                paging: false,
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
                            type: 'Motorbike Casual',
                            yesterday: yesterday.grandpass,
                            today: today.grandpass
                        },

                    ];

                    const formattedRows = rows.map((item, index) => ({
                        no: index + 1,
                        type: item.type,
                        yesterday: item.yesterday,
                        today: item.today
                    }));

                    table.rows.add(formattedRows).draw();
                    $('#totalYesterday').text(yesterday.grandtotal);
                    $('#totalToday').text(today.grandtotal);
                }
            });


        });
    </script>
    <script>
        $(document).ready(function() {
            $.ajax({
                url: `http://110.0.100.70:8080/v3/api/daily-quantity?location_code=${kodeLokasi}`,
                method: 'GET',
                success: function(response) {
                    const today = response.data[0].today[0];

                    // Labels untuk x-axis
                    const labels = ['Car', 'Motorbike', 'Truck', 'Taxi'];

                    // Ambil data dari API
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

                    // Konfigurasi chart
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
                                },

                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0,

                                    }
                                }
                            }
                        }
                    };

                    const ctx = document.getElementById('dailyQuantityBar').getContext('2d');
                    new Chart(ctx, config);
                }
            });
        });
    </script>

    {{-- Weekly --}}
    <script>
        const kodeLokasi = @json($kodeLokasi);
        $(document).ready(function() {
            const table = $('#casualWeeklyQuantity').DataTable({
                searching: false,
                paging: false,
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
                        data: 'last week'
                    },
                    {
                        data: 'this week'
                    }
                ]
            });

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
                            type: 'Motorbike Casual',
                            yesterday: yesterday.grandpass,
                            today: today.grandpass
                        },

                    ];

                    const formattedRows = rows.map((item, index) => ({
                        no: index + 1,
                        type: item.type,
                        yesterday: item.yesterday,
                        today: item.today
                    }));

                    table.rows.add(formattedRows).draw();
                    $('#totalYesterday').text(yesterday.grandtotal);
                    $('#totalToday').text(today.grandtotal);
                }
            });
        });
    </script>
@endsection
