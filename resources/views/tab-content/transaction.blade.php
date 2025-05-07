{{-- tabs/home.blade.php --}}




<div class="nav nav-tabs custom-nav" id="inner-tab" role="tablist">
    <button class="nav-link active" id="inner-one-tab" data-bs-toggle="tab" data-bs-target="#inner-one" type="button"
        role="tab">Daily</button>
    <button class="nav-link" id="inner-two-tab" data-bs-toggle="tab" data-bs-target="#inner-two" type="button"
        role="tab">Weekly</button>
    <button class="nav-link" id="inner-three-tab" data-bs-toggle="tab" data-bs-target="#inner-three" type="button"
        role="tab">Monthly</button>
</div>

<div class="tab-content">

    <div class="tab-pane fade show active mt-5" id="inner-one" role="tabpanel" aria-labelledby="inner-one-tab">
        <div class="row">
            <div class="col-12">
                <div class="row" id="dashboardRow">
                    <div class="row" id="daily-transaction-comparison"></div>



                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="content-custom">

                            <table id="dailyQuantity" class="table table-striped">
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
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="content-custom">
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-home" type="button" role="tab"
                                        aria-controls="nav-home" aria-selected="true">Bar</button>
                                    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-profile" type="button" role="tab"
                                        aria-controls="nav-profile" aria-selected="false">Line</button>
                                </div>
                            </nav>

                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                                    aria-labelledby="nav-home-tab" tabindex="0">
                                    <canvas id="dailyQuantityBar" height="200"></canvas>
                                </div>
                                <div class="tab-pane fade" id="nav-profile" role="tabpanel"
                                    aria-labelledby="nav-profile-tab" tabindex="0">
                                    <canvas id="dailyQuantityLine" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="inner-two" role="tabpanel" aria-labelledby="inner-two-tab">
        <div class="row">
            <div class="col-12">
                <h5>Weekly Quantity</h5>

                <div class="row" id="dashboardRow">
                    {{-- @foreach ($processedData as $data)
                        <!-- Total Casual Card -->
                        <div class="col-md-4">
                            <div class="dashboard-card">
                                <div class="card-title">{{ $data['label'] }}</div>
                                <div class="d-flex align-items-baseline">
                                    <h2 class="card-value">{{ $data['today'] }}</h2>
                                    <span class=" ms-2" style="color: {{ $data['color'] }}">
                                        {{ $data['percent_change'] }}%
                                        {{ $data['direction'] }}</span>
                                </div>
                                <div class="yesterday">Yesterday: {{ $data['yesterday'] }}</div>
                            </div>
                        </div>
                    @endforeach --}}
                    <div class="row" id="weekly-transaction-comparison"></div>


                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="content-custom">
                            <table id="weeklyQuantity" class="table table-striped">
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
                    </div>
                    <div class="col-md-6">
                        <div class="content-custom">

                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <button class="nav-link active" id="nav-weekly-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-weekly" type="button" role="tab"
                                        aria-controls="nav-weekly" aria-selected="true">Bar</button>
                                    <button class="nav-link" id="nav-weekly-line-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-weekly-line" type="button" role="tab"
                                        aria-controls="nav-weekly-line" aria-selected="false">Line</button>
                                </div>
                            </nav>
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-weekly" role="tabpanel"
                                    aria-labelledby="nav-weekly-tab" tabindex="0">
                                    <canvas id="weeklyQuantityBar" height="200" width="auto"></canvas>
                                </div>
                                <div class="tab-pane fade" id="nav-weekly-line" role="tabpanel"
                                    aria-labelledby="nav-weekly-line-tab" tabindex="0">
                                    <canvas id="weeklyQuantityLine" height="200" width="auto"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="inner-three" role="tabpanel" aria-labelledby="inner-three-tab">
        <div class="row">
            <div class="col-12">
                <h5>Monthly Quantity</h5>
                <div class="row" id="dashboardRow">
                    <div class="row" id="monthly-transaction-comparison"></div>


                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="content-custom">

                            <table id="monthlyQuantity" class="table table-striped">
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
                    <div class="col-md-6">
                        <div class="content-custom">
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <button class="nav-link active" id="nav-monthly-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-monthly" type="button" role="tab"
                                        aria-controls="nav-monthly" aria-selected="true">Bar</button>
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
                                <div class="tab-pane fade" id="nav-monthly-line" role="tabpanel"
                                    aria-labelledby="nav-monthly-line-tab" tabindex="0">
                                    <canvas id="monthlyQuantityLine" height="200" width="auto"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <script>
    const dailyTransactionURL = "{{ route('getDailyTransaction') }}";
    const weeklyTransactionURL = "{{ route('weeklyTransaction') }}";
    const monthlyTransactionURL = "{{ route('monthlyTransaction') }}";
</script>
<script src="js/transaction.js"></script> --}}

{{-- 
<script>    
    $(document).ready(function() {
        const kodeLokasi = @json($kodeLokasi);
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

        $.ajax({
            url: "{{ route('weeklyTransaction') }}",
            method: 'GET',
            success: function(response) {
                const thisWeek = response.this_week.totals.casual;
                const lastWeek = response.last_week.totals.casual;
                const thisWeekChart = response.this_week.casual;

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

                const labels = thisWeekChart.map(item => {
                    const date = new Date(item.tanggal);
                    return date.toLocaleDateString('en-GB', {
                        day: '2-digit',
                        month: 'short'
                    }); // hasilnya: 14 Apr, 15 Apr, dst.
                });

                const casualData = [
                    thisWeekChart.map(item => item.carcasual),
                    thisWeekChart.map(item => item.motorbikecasual),
                    thisWeekChart.map(item => item.taxicasual),
                    thisWeekChart.map(item => item.truckcasual),
                    thisWeekChart.map(item => item.othercasual),
                    thisWeekChart.map(item => item.vehiclecasual),
                    thisWeekChart.map(item => item.lostticketcasual),
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

                const thisMonthChart = response.this_month.weekly_totals.casual;

                const labels = Object.keys(thisMonthChart);

                const totalCars = Object.values(thisMonthChart).map(week => week
                    .total_car);
                const totalMotorbikes = Object.values(thisMonthChart).map(week => week
                    .total_motorbike);
                const totalTrucks = Object.values(thisMonthChart).map(week => week
                    .total_truck);
                const totalTaxis = Object.values(thisMonthChart).map(week => week
                    .total_taxi);
                const totalVehicles = Object.values(thisMonthChart).map(week => week
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
                            },
                            hidden: true,
                        }, {
                            label: 'Truck',
                            data: totalTrucks,
                            backgroundColor: '#8D60ED',
                            borderColor: '#8D60ED',
                            borderWidth: 1,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            },
                            hidden: true,
                        }, {
                            label: 'Taxi',
                            data: totalTaxis,
                            backgroundColor: '#C46EA6',
                            borderColor: '#C46EA6',
                            borderWidth: 1,
                            datalabels: {
                                anchor: 'end',
                                align: 'end'
                            },
                            hidden: true,
                        }, {
                            label: 'Vehicle',
                            data: totalVehicles,
                            backgroundColor: '#D3D6DD',
                            borderColor: '#D3D6DD',
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
                        hidden: true,
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
                        hidden: true,
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
                        hidden: true,

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
                        hidden: true,
                    }]
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
    });
</script> --}}
