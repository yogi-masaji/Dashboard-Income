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
        .content-custom {
            padding: 10px !important;
            background-color: #092953 !important;
            border: #001a4e 1px solid !important;
            height: auto;
        }

        table.dataTable tbody th,
        table.dataTable tbody td {
            padding: 2px 0px;
        }
    </style>
    <div class="container">
        <div class="row">
            <div class="col-md-4 content-custom">
                <p>Daily Quantity</p>
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
                            data-bs-target="#nav-weekly-line" type="button" role="tab" aria-controls="nav-weekly-line"
                            aria-selected="false">Line</button>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-weekly" role="tabpanel" aria-labelledby="nav-weekly-tab"
                        tabindex="0">
                        <canvas id="weeklyQuantityBar" height="150" width="auto"></canvas>
                    </div>
                    <div class="tab-pane fade" id="nav-weekly-line" role="tabpanel" aria-labelledby="nav-weekly-line-tab"
                        tabindex="0">
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
                <canvas id="dailyIncomedonut" height="5"></canvas>
            </div>
            <div class="col-md-4 content-custom">
                <p>Weekly Income</p>
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
    </div>

    <script>
        const dailyTransactionURL = "{{ route('getDailyTransaction') }}";
        const weeklyTransactionURL = "{{ route('weeklyTransaction') }}";
        const monthlyTransactionURL = "{{ route('monthlyTransaction') }}";
        const dailyIncomeURL = "{{ route('getDailyIncome') }}";
        const weeklyIncomeURL = "{{ route('weeklyIncome') }}";
        const monthlyIncomeURL = "{{ route('monthlyIncome') }}";
    </script>
    <script src="js/transaction.js"></script>
    <script src="js/income.js"></script>
@endsection
