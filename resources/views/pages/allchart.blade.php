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
    <style>
        /* Hide content until everything is loaded by dashboard.js */
        #main-content {
            display: none;
        }

        .content-custom {
            padding: 10px !important;
            background-color: #ffffff !important;
            border: #d9d9d9 1px solid !important;
            height: auto;
        }

        table.dataTable tbody th,
        table.dataTable tbody td {
            padding: 2px 5px;
        }

        table.dataTable td {
            font-size: 0.9em;
        }

        table.dataTable tfoot th {
            font-size: 0.7em;
            font-weight: 600;
        }

        table.dataTable tfoot th[colspan="2"] {
            font-size: 0.7em;
            font-weight: 700;
        }


        table.dataTable {
            width: 100%;
        }

        .table thead tr th {
            padding-block: 5px;
        }

        table.dataTable thead th,
        table.dataTable thead td {
            padding: 10px 5px;
            border-bottom: 1px solid #111;
        }

        #monthlyE-Payment th,
        #monthlyE-Payment td {
            word-break: break-all;
            word-break: break-word;
        }

        #dailyQuantity {
            height: 23vh !important;
        }

        p {
            color: #000;
        }

        .nav-tabs .nav-link {
            color: #000000;
            border: 1px solid;
            background-color: #FFF;
            border-radius: 10px;
            padding: 10px 20px;
            margin: 0 5px;
        }

        .nav-tabs .nav-link.active {
            background-color: #ffc107;
            color: #000;
            font-weight: bold;
        }
    </style>

    <!-- Main Content (will be shown by dashboard.js after loading) -->
    <div id="main-content">
        <div class="">
            <div class="row">
                <div class="col-md-4 content-custom ">
                    <p>Daily Quantity</p>
                    <div class="" style="height: 25vh">
                        <table id="dailyQuantity" class="table table-striped" style="flex: 1; height: 100%;">
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

                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                                aria-selected="true">Bar</button>
                            <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile"
                                type="button" role="tab" aria-controls="nav-profile"
                                aria-selected="false">Line</button>
                        </div>
                    </nav>

                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab"
                            tabindex="0">
                            <canvas id="dailyQuantityBar" height="150"></canvas>
                        </div>
                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab"
                            tabindex="0">
                            <canvas id="dailyQuantityLine" height="150"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 content-custom">
                    <p>Casual Weekly Quantity</p>
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
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-weekly-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-weekly" type="button" role="tab" aria-controls="nav-weekly"
                                aria-selected="true">Bar</button>
                            <button class="nav-link" id="nav-weekly-line-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-weekly-line" type="button" role="tab"
                                aria-controls="nav-weekly-line" aria-selected="false">Line</button>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-weekly" role="tabpanel"
                            aria-labelledby="nav-weekly-tab" tabindex="0">
                            <canvas id="weeklyQuantityBar" height="150" width="auto"></canvas>
                        </div>
                        <div class="tab-pane fade" id="nav-weekly-line" role="tabpanel"
                            aria-labelledby="nav-weekly-line-tab" tabindex="0">
                            <canvas id="weeklyQuantityLine" height="150" width="auto"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 content-custom">
                    <p>Monthly Casual Quantity</p>
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
                            <canvas id="monthlyQuantityBar" height="150" width="auto"></canvas>

                        </div>
                        <div class="tab-pane fade" id="nav-monthly-line" role="tabpanel"
                            aria-labelledby="nav-monthly-line-tab" tabindex="0">
                            <canvas id="monthlyQuantityLine" height="150" width="auto"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 content-custom">
                    <p>Daily Income</p>

                    <table id="dailyIncome" class="table table-striped ">
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

                    <div class="" style="height: 220px;">
                        <canvas id="dailyIncomedonut"></canvas>
                    </div>
                </div>
                <div class="col-md-4 content-custom">
                    <p>Weekly Income</p>
                    <table id="weeklyIncome" class="table table-striped ">
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
                            <canvas id="weeklyIncomeBar" height="150" width="auto"></canvas>
                        </div>
                        <div class="tab-pane fade" id="nav-weeklyIncome-line" role="tabpanel"
                            aria-labelledby="nav-weeklyIncome-line-tab" tabindex="0">
                            <canvas id="weeklyIncomeLine" height="150" width="auto"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 content-custom">
                    <p>Monthly Income</p>
                    <table id="monthlyIncome" class="table table-striped ">
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
                            <canvas id="monthlyIncomeBar" height="150" width="auto"></canvas>
                        </div>
                        <div class="tab-pane fade" id="nav-monthlyIncome-line" role="tabpanel"
                            aria-labelledby="nav-monthlyIncome-line-tab" tabindex="0">
                            <canvas id="monthlyIncomeLine" height="150" width="auto"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 content-custom">
                    <p>Daily E-Payment</p>

                    <table id="dailyE-Payment" class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Payment</th>
                                <th>Yesterday</th>
                                <th>Today</th>
                            </tr>
                        </thead>
                        <tfoot></tfoot>
                    </table>
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-dailyE-Payment-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-dailyE-Payment" type="button" role="tab"
                                aria-controls="nav-dailyE-Payment" aria-selected="true">Bar</button>
                            <button class="nav-link" id="nav-dailyE-Payment-line-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-dailyE-Payment-line" type="button" role="tab"
                                aria-controls="nav-dailyE-Payment-line" aria-selected="false">Line</button>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-dailyE-Payment" role="tabpanel"
                            aria-labelledby="nav-dailyE-Payment-tab" tabindex="0">
                            <canvas id="dailyE-PaymentBar" height="200" width="auto"></canvas>
                        </div>
                        <div class="tab-pane fade" id="nav-dailyE-Payment-line" role="tabpanel"
                            aria-labelledby="nav-dailyE-Payment-line-tab" tabindex="0">
                            <canvas id="dailyE-PaymentLine" height="200" width="auto"></canvas>
                        </div>
                    </div>


                </div>
                <div class="col-md-4 content-custom">
                    <p>Weekly E-Payment</p>
                    <table id="weeklyE-Payment" class="table table-striped ">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Payment</th>
                                <th>Last Week</th>
                                <th>This Week</th>
                            </tr>
                        </thead>
                        <tfoot>

                        </tfoot>
                    </table>
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-weeklyE-Payment-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-weeklyE-Payment" type="button" role="tab"
                                aria-controls="nav-weeklyE-Payment" aria-selected="true">Bar</button>
                            <button class="nav-link" id="nav-weeklyE-Payment-line-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-weeklyE-Payment-line" type="button" role="tab"
                                aria-controls="nav-weeklyE-Payment-line" aria-selected="false">Line</button>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-weeklyE-Payment" role="tabpanel"
                            aria-labelledby="nav-weeklyE-Payment-tab" tabindex="0">
                            <canvas id="weeklyE-PaymentBar" height="200" width="auto"></canvas>
                        </div>
                        <div class="tab-pane fade" id="nav-weeklyE-Payment-line" role="tabpanel"
                            aria-labelledby="nav-weeklyE-Payment-line-tab" tabindex="0">
                            <canvas id="weeklyE-PaymentLine" height="200" width="auto"></canvas>
                        </div>
                    </div>

                </div>
                <div class="col-md-4 content-custom">
                    <p>Monthly E-Payment</p>
                    <table id="monthlyE-Payment" class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Payment</th>
                                <th>Last Month</th>
                                <th>This Month</th>
                            </tr>
                        </thead>
                        <tfoot></tfoot>
                    </table>
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-monthlyE-Payment-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-monthlyE-Payment" type="button" role="tab"
                                aria-controls="nav-monthlyE-Payment" aria-selected="true">Bar</button>
                            <button class="nav-link" id="nav-monthlyE-Payment-line-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-monthlyE-Payment-line" type="button" role="tab"
                                aria-controls="nav-monthlyE-Payment-line" aria-selected="false">Line</button>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-monthlyE-Payment" role="tabpanel"
                            aria-labelledby="nav-monthlyE-Payment-tab" tabindex="0">
                            <canvas id="monthlyE-PaymentBar" height="200" width="auto"></canvas>
                        </div>
                        <div class="tab-pane fade" id="nav-monthlyE-Payment-line" role="tabpanel"
                            aria-labelledby="nav-monthlyE-Payment-line-tab" tabindex="0">
                            <canvas id="monthlyE-PaymentLine" height="200" width="auto"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 content-custom">
                    <p>Weekly Pass Quantity</p>
                    <table id="weeklyQuantityPass" class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Vehicle</th>
                                <th>Last Week</th>
                                <th>This Week</th>
                            </tr>
                        </thead>
                    </table>
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-weekly-pass-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-weekly-pass" type="button" role="tab"
                                aria-controls="nav-weekly-pass" aria-selected="true">Bar</button>
                            <button class="nav-link" id="nav-weekly-pass-line-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-weekly-pass-line" type="button" role="tab"
                                aria-controls="nav-weekly-line-pass" aria-selected="false">Line</button>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-weekly-pass" role="tabpanel"
                            aria-labelledby="nav-weekly-pass-tab" tabindex="0">
                            <canvas id="weeklyPassQuantityBar" height="150" width="auto"></canvas>
                        </div>
                        <div class="tab-pane fade" id="nav-weekly-pass-line" role="tabpanel"
                            aria-labelledby="nav-weekly-pass-line-tab" tabindex="0">
                            <canvas id="weeklyPassQuantityLine" height="150" width="auto"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 content-custom">
                    <p>Monthly Pass Quantity</p>
                    <table id="monthlyQuantityPass" class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Vehicle</th>
                                <th>Last Month</th>
                                <th>This Month</th>
                            </tr>
                        </thead>
                    </table>
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-monthly-pass-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-monthly-pass" type="button" role="tab"
                                aria-controls="nav-monthly-pass" aria-selected="true">Bar</button>
                            <button class="nav-link" id="nav-monthly-pass-line-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-monthly-pass-line" type="button" role="tab"
                                aria-controls="nav-monthly-line-pass" aria-selected="false">Line</button>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-monthly-pass" role="tabpanel"
                            aria-labelledby="nav-monthly-pass-tab" tabindex="0">
                            <canvas id="monthlyPassQuantityBar" height="150" width="auto"></canvas>
                        </div>
                        <div class="tab-pane fade" id="nav-monthly-pass-line" role="tabpanel"
                            aria-labelledby="nav-monthly-pass-line-tab" tabindex="0">
                            <canvas id="monthlyPassQuantityLine" height="150" width="auto"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        const dailyTransactionURL = "{{ route('getDailyTransaction') }}";
        const weeklyTransactionURL = "{{ route('weeklyTransaction') }}";
        const monthlyTransactionURL = "{{ route('monthlyTransaction') }}";
        const dailyIncomeURL = "{{ route('getDailyIncome') }}";
        const weeklyIncomeURL = "{{ route('weeklyIncome') }}";
        const monthlyIncomeURL = "{{ route('monthlyIncome') }}";
        const dailyEpaymentURL = "{{ route('dailyEpayment') }}";
        const weeklyEpaymentURL = "{{ route('weeklyEpayment') }}";
        const monthlyEpaymentURL = "{{ route('monthlyEpayment') }}";
        const dailyEpaymentChartURL = "{{ route('dailyEpaymentChart') }}";
    </script>

    <script src="{{ asset('js/dashboard.js') }}"></script>
    {{-- @vite('resources/js/dashboard.js') --}}
    {{-- @vite('resources/js/dailyQuantity.js')
    {{-- @vite('resources/js/testasync.js')
    @vite('resources/js/income.js')
    @vite('resources/js/epayment.js') --}}
@endsection
