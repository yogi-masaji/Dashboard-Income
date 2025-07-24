@extends('layout.nav')
@section('content')
    @php
        $lokasiName = session('selected_location_name', 'Lokasi Default');
        $ipLokasi = session('selected_location_ip_lokasi', 'IP Tidak Diketahui');
        $lokasiId = session('selected_location_id', 0);
        $lokasiGrup = session('selected_location_id_grup', 'Group Tidak Diketahui');
        $kodeLokasi = session('selected_location_kode_lokasi', 'Kode Tidak Diketahui');
        $chiselVersion = session('selected_location_chisel_Version', 'Chisel Version Tidak Diketahui');
        $navbarTitle = $lokasiName;
    @endphp

    <style>
        .content-custom {
            padding: 10px !important;
            background-color: #092953 !important;
            border-radius: 10px !important;
        }

        .content-picker {
            padding: 15px !important;
            background-color: #092953 !important;
            border-radius: 10px !important;
        }

        .nav-pills .nav-item .nav-link:not(.active) {
            border: 2px solid #ffffff41;
            padding-bottom: 0.5435rem;
            background-color: rgb(9 41 83 / 69%);
        }

        .dashboard-card {
            background-color: #c2d7ff;
            border-radius: 10px;
            padding: 13px;
            margin-bottom: 15px;
            width: 100%;
        }

        .row {
            --bs-gutter-x: 1.625rem;
            --bs-gutter-y: 0;
            display: flex;
            flex-wrap: wrap;
            margin-top: 0 !important;
            margin-right: 0 !important;
            margin-left: 0 !important;
        }
    </style>
    <style>
        /* Card specific styles that Bootstrap doesn't provide */
        .revenue-card {
            background-color: #e6f7ee;
        }

        .revenue-icon {
            background-color: #d1f0e0;
            color: #10b981;
        }

        .vehicle-card {
            background-color: #e6f1ff;
        }

        .vehicle-icon {
            background-color: #d1e3ff;
            color: #3b82f6;
        }

        .payment-card {
            background-color: #f3e6ff;
        }

        .payment-icon {
            background-color: #e6d1ff;
            color: #a855f7;
        }

        .vip-card {
            background-color: #fff8e6;
        }

        .vip-icon {
            background-color: #ffefd1;
            color: #f59e0b;
        }

        .icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-body {
            flex: 1 1 auto;
            padding: 10px;
        }
    </style>
    <style>
        /* Apply flextruck to the wrapper that contains the search and buttons */
        #membershipTable_wrapper .dt-top {
            display: flex;
            justify-content: flex-start;
            /* Align buttons and search to the left */
            gap: 20px;
            /* Add space between the buttons and search */
            align-items: center;
        }

        table.dataTable thead th,
        table.dataTable thead td {
            padding: 16px;
            border-bottom: 1px solid #111
        }

        tbody {
            white-space: normal;
            word-break: break-all;
        }

        /* Ensure the buttons are inline and spaced correctly */
        .dt-buttons {
            display: inline-flex;
            gap: 10px;
            /* Space between individual buttons */
        }

        /* Make sure the search input aligns properly */
        .dt-search input {
            display: inline-block;
            margin-right: 10px;
            /* Space between the search input and buttons */
        }

        .dt-search {
            float: right !important;
            margin-bottom: 5px;
        }

        button.dt-paging-button {
            background-color: #ffffff !important;
            padding: 10px;
            width: 30px;
            border-radius: 10px;
            border: none !important;
            margin-right: 2px;
            margin-left: 2px;
        }

        .dt-button {
            background-color: #FCB900 !important;
            padding: 10px;
            border-radius: 10px;
            border: none !important;
            margin-bottom: 10px;
        }

        #dt-search-0 {
            height: 40px;
            border-radius: 10px;
            margin-left: 10px;
        }

        span.dt-column-title {
            font-size: 11px;
        }

        .col-md-2 {
            flex: 0 0 auto;
            width: 24.25%;
        }

        th.text-center.dt-orderable-none {
            padding: 7px;
        }
    </style>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            border: 2px solid #fff;
            table-layout: fixed;
            font-size: 11px;
        }

        .table> :not(caption)>*>* {
            padding: 0.782rem 0.5rem;
        }

        .col-md-4 {
            flex: 0 0 auto;
            width: 33%;
        }

        .col-md-6 {
            flex: 0 0 auto;
            width: 49%;
        }
    </style>
    <div class="search-wrapper content-custom">
        <h5>Custom Search</h5>
        <div class="d-flex align-items-end gap-3 mb-3">
            <div>
                <label for="start-date-1" class="form-label">First Period</label>
                <input type="text" name="start1" id="start-date-1" class="form-control" placeholder="Select start date" />
            </div>
            <div class="pb-3 fw-semibold">to</div>
            <div>

                <input type="text" name="end1" id="end-date-1" class="form-control" placeholder="Select end date" />
            </div>
        </div>

        <div class="d-flex align-items-end gap-3 mb-3">
            <div>
                <label for="start-date-2" class="form-label">Second Period</label>
                <input type="text" name="start2" id="start-date-2" class="form-control"
                    placeholder="Select start date" />
            </div>
            <div class="pb-3 fw-semibold">to</div>
            <div>

                <input type="text" name="end2" id="end-date-2" class="form-control" placeholder="Select end date" />
            </div>
        </div>

        <div class="mt-3">
            <button type="button" class="btn btn-submit" id="cari">Cari</button>
        </div>

        <!-- Alert Message -->
        <div id="alertMessage" class="alert alert-danger mt-3" role="alert" style="display: none;">
            Please fill in all the date fields before submitting.
        </div>
    </div>



    <div class="result mt-3" style="display: none;">
        <div class="content-picker d-flex align-items-center gap-5 mb-3">
            <div class="align-self-center">
                <span style="display: flex; align-items: center; gap: 8px;">
                    <i class="bi bi-calendar"></i>
                    <h5 style="margin: 0;">Period:</h5>
                </span>
            </div>
            <div class="align-self-center">
                <ul class="nav nav-pills mb-3 gap-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                            aria-selected="true"><span class="date-firstperiod"></span></button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile"
                            aria-selected="false"><span class="date-secondperiod"></span></button>
                    </li>
                </ul>
            </div>
        </div>



        <div class="isi-custom-search mt-3">
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                    <div class="row gap-3 mt-5 mb-5">
                        <div class="col-md-2">
                            <div class="card shadow-sm border-0">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="text-muted mb-1">Total Revenue</h6>
                                            <h4 class="fw-bold mb-0 total-revenue"></h4>
                                            <small class="text-muted tgl_row1"></small>
                                        </div>
                                        <div class="text-success fs-4"><i class="bi bi-currency-dollar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card shadow-sm border-0">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="text-muted mb-1">Total Quantity Pass</h6>
                                            <h4 class="fw-bold mb-0 total-pass"></h4>
                                            <small class="text-muted tgl_row1"></small>
                                        </div>
                                        {{-- <div class="text-success fs-4"><i class="bi bi-car-front-fill"></i></div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card shadow-sm border-0">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="text-muted mb-1">Total Quantity Casual</h6>
                                            <h4 class="fw-bold mb-0 total-casual">Rp 388.532.000</h4>
                                            <small class="text-muted tgl_row1"></small>
                                        </div>
                                        {{-- <div class="text-success fs-4">$</div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card shadow-sm border-0">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="text-muted mb-1">Top Payment Method</h6>
                                            <h4 class="fw-bold mb-0 top-payment"></h4>
                                            <small class="text-muted top-payment-income"></small>
                                        </div>
                                        <div class="text-primary fs-4"><i class="bi bi-credit-card"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row gap-3 mb-3">
                        <div class="col-md-6">
                            <div class="content-custom">
                                <h5>Daily Revenue</h5>
                                <div class="" style="height: 300px;">
                                    <canvas id="dailyRevenueFirstBar" height="300px"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="content-custom">
                                <h5>Vehicle Distribution</h5>
                                <div class="" style="height: 300px;">
                                    <canvas id="firstVehicleDistribution" height="300px"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                    <div class="row gap-3 mt-5 mb-5">
                        <div class="col-md-2">
                            <div class="card shadow-sm border-0">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="text-muted mb-1">Total Revenue</h6>
                                            <h4 class="fw-bold mb-0 total-revenue-second"></h4>
                                            <small class="text-muted tgl_row2"></small>
                                        </div>
                                        <div class="text-success fs-4"><i class="bi bi-currency-dollar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card shadow-sm border-0">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="text-muted mb-1">Total Pass</h6>
                                            <h4 class="fw-bold mb-0 total-pass-second"></h4>
                                            <small class="text-muted tgl_row2"></small>
                                        </div>
                                        <div class="text-success fs-4"><i class="bi bi-car-front-fill"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card shadow-sm border-0">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="text-muted mb-1">Total Casual</h6>
                                            <h4 class="fw-bold mb-0 total-casual-second">Rp 388.532.000</h4>
                                            <small class="text-muted tgl_row2"></small>
                                        </div>
                                        <div class="text-success fs-4">$</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card shadow-sm border-0">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="text-muted mb-1">Top Payment Method</h6>
                                            <h4 class="fw-bold mb-0 top-payment-second"></h4>
                                            <small class="text-muted top-payment-income-second"></small>
                                        </div>
                                        <div class="text-success fs-4"><i class="bi bi-credit-card"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row gap-3 mb-3">
                        <div class="col-md-6">
                            <div class="content-custom">
                                <h5>Daily Revenue</h5>
                                <div class="" style="height: 300px;">
                                    <canvas id="dailyRevenueSecondBar" height="300px"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="content-custom">
                                <h5>Vehicle Distribution</h5>
                                <div class="" style="height: 300px;">
                                    <canvas id="SecondVehicleDistribution" height="300px"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-custom mt-5 mb-5">
            <div class="d-flex justify-content-between">
                {{-- <div class="">
                    <h3>Period Comparison</h3>
                </div> --}}
                <div class="">
                    <ul class="nav nav-pills mb-3 gap-2" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-incomepayment-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-incomepayment" type="button" role="tab"
                                aria-controls="pills-incomepayment" aria-selected="true">Income By Payment</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-quantity-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-quantity" type="button" role="tab"
                                aria-controls="pills-quantity" aria-selected="false">Quantity By
                                Vehicle</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-incomevehicle-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-incomevehicle" type="button" role="tab"
                                aria-controls="pills-incomevehicle" aria-selected="false">Income By
                                Vehicle</button>
                        </li>

                    </ul>
                </div>
            </div>

            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-incomepayment" role="tabpanel"
                    aria-labelledby="pills-incomepayment-tab" tabindex="0">
                    <div class="mb-3" style="height: 300px;">
                        <canvas id="incomePayment" height="300"></canvas>

                    </div>
                    <div class="row gap-3 mt-5 mb-5" id="compare-result">
                        <div class="col-md-2">
                            <div class="card shadow-sm border-0">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="text-muted mb-1">Total Revenue</h6>
                                            <h4 class="fw-bold mb-0">Rp 388.532.000</h4>
                                            <small class="text-muted tgl_row1"></small>
                                        </div>
                                        <div class="text-success fs-4">9%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-quantity" role="tabpanel" aria-labelledby="pills-quantity-tab"
                    tabindex="0">
                    <div class="" style="height: 300px;">
                        <canvas id="quantityVehicle" height="300"></canvas>
                    </div>
                    <div class="row gap-3 mt-5 mb-5" id="compare-quantity">
                        <div class="col-md-2">
                            <div class="card shadow-sm border-0">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="text-muted mb-1">Total Revenue</h6>
                                            <h4 class="fw-bold mb-0">Rp 388.532.000</h4>
                                            <small class="text-muted tgl_row1"></small>
                                        </div>
                                        <div class="text-success fs-4">9%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-incomevehicle" role="tabpanel"
                    aria-labelledby="pills-incomevehicle-tab" tabindex="0">
                    <div class="" style="height: 300px;">
                        <canvas id="incomeVehicle" height="300"></canvas>
                    </div>
                    <div class="row gap-3 mt-5 mb-5" id="compare-income-vehicle">
                        <div class="col-md-2">
                            <div class="card shadow-sm border-0">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="text-muted mb-1">Total Revenue</h6>
                                            <h4 class="fw-bold mb-0">Rp 388.532.000</h4>
                                            <small class="text-muted tgl_row1"></small>
                                        </div>
                                        <div class="text-success fs-4">9%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-custom">
            <div class="-">
                <table class="table table-striped" id="table-custom">
                    <thead>
                        <tr>
                            <th scope="col" rowspan="3" class="text-center"
                                style="width:40px;border-left: 1px solid white; border-top: 1px solid white; border-right: 1px solid white;">
                                No
                            </th>
                            <th scope="col" colspan="3" rowspan="1" class="text-center"
                                style="border-top: 1px solid white; border-right: 1px solid white;">INCOME BY PAYMENT</th>
                            <th scope="col" colspan="5" rowspan="1" class="text-center"
                                style="border-top: 1px solid white; border-right: 1px solid white;">QUANTITY BY VEHICLE
                            </th>
                            <th scope="col" colspan="3" rowspan="1" class="text-center"
                                style="border-top: 1px solid white; border-right: 1px solid white;">INCOME BY VEHICLE</th>
                        </tr>

                        <tr>
                            <th scope="col" rowspan="2" style="border-right: 1px solid white;">Payment</th>
                            <th scope="col" rowspan="2" style="border-right: 1px solid white;"
                                class="tgl_row1 text-center">
                            </th>
                            <th scope="col" rowspan="2" class="tgl_row2 text-center"
                                style="border-right: 1px solid white;">
                            </th>
                            <th scope="col" rowspan="2"
                                style="border-left: 1px solid white; border-right: 1px solid white;">
                                Vehicle</th>
                            <th scope="col" colspan="2" style="border-right: 1px solid white;"
                                class="tgl_row1 text-center">
                            </th>
                            <th scope="col" colspan="2" class="tgl_row2 text-center"
                                style="border-right: 1px solid white;">
                            </th>
                            <th scope="col" rowspan="2"
                                style="border-right: 1px solid white; border-left: 1px solid white;">
                                Vehicle</th>
                            <th scope="col" rowspan="2" style="border-right: 1px solid white;"
                                class="tgl_row1 text-center">
                            </th>
                            <th scope="col" rowspan="2" class="tgl_row2 text-center"
                                style="border-right: 1px solid white;">
                            </th>

                        </tr>
                        <tr>
                            <th scope="col" class="text-center" style="border-right: 1px solid white;">Pass</th>
                            <th scope="col" class="text-center" style="border-right: 1px solid white;">Casual</th>
                            <th scope="col" class="text-center" style="border-right: 1px solid white;">Pass</th>
                            <th scope="col" class="text-center" style="border-right: 1px solid white;">Casual</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-custom">
                    </tbody>
                </table>
            </div>
        </div>
        <div class="content-custom mt-5 mb-5">
            <div class="content-picker d-flex align-items-center gap-5 ">
                <div class="align-self-center">
                    <span style="display: flex; align-items: center; gap: 8px;">
                        <i class="bi bi-calendar"></i>
                        <h5 style="margin: 0;">Income by Payment</h5>
                    </span>
                </div>
                <div class="align-self-center">
                    <ul class="nav nav-pills mb-3 gap-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-table-1-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-table-1" type="button" role="tab"
                                aria-controls="pills-table-1" aria-selected="true"><span
                                    class="date-firstperiod"></span></button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-table-1-secondPeriod-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-table-1-secondPeriod" type="button" role="tab"
                                aria-controls="pills-table-1-secondPeriod" aria-selected="false"><span
                                    class="date-secondperiod"></span></button>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-table-1" role="tabpanel"
                    aria-labelledby="pills-table-1-tab">
                    <div class="row gap-3 mt-5 mb-5">
                        <table id="IncomePaymentFirst" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center" style=" width: 20px;">No</th>
                                    <th scope="col" class="text-center">Date</th>
                                    <th scope="col" class="text-center">Cash</th>
                                    <th scope="col" class="text-center">Parkee</th>
                                    <th scope="col" class="text-center">Emoney</th>
                                    <th scope="col" class="text-center">Flazz</th>
                                    <th scope="col" class="text-center">Brizzi</th>
                                    <th scope="col" class="text-center">Tapcash</th>
                                    <th scope="col" class="text-center">Qris</th>
                                    <th scope="col" class="text-center">Dki Jakcard</th>
                                    <th scope="col" class="text-center">Total</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-table-1-secondPeriod" role="tabpanel"
                    aria-labelledby="pills-table-1-secondPeriod-tab">
                    <div class="row gap-3 mt-5 mb-5">
                        <table id="IncomePaymentSecond" class="table table-striped table-bordered" style="width: 100%">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center" style=" width: 20px;">No</th>
                                    <th scope="col" class="text-center">Date</th>
                                    <th scope="col" class="text-center">Cash</th>
                                    <th scope="col" class="text-center">Parkee</th>
                                    <th scope="col" class="text-center">Emoney</th>
                                    <th scope="col" class="text-center">Flazz</th>
                                    <th scope="col" class="text-center">Brizzi</th>
                                    <th scope="col" class="text-center">Tapcash</th>
                                    <th scope="col" class="text-center">Qris</th>
                                    <th scope="col" class="text-center">Dki Jakcard</th>
                                    <th scope="col" class="text-center">Total</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-custom mt-5 mb-5">
            <div class="content-picker d-flex align-items-center gap-5 ">
                <div class="align-self-center">
                    <span style="display: flex; align-items: center; gap: 8px;">
                        <i class="bi bi-calendar"></i>
                        <h5 style="margin: 0;">Quantity by Vehicle</h5>
                    </span>
                </div>
                <div class="align-self-center">
                    <ul class="nav nav-pills mb-3 gap-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-table-2-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-table-2" type="button" role="tab"
                                aria-controls="pills-table-2" aria-selected="true"><span
                                    class="date-firstperiod"></span></button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-table-2-secondPeriod-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-table-2-secondPeriod" type="button" role="tab"
                                aria-controls="pills-table-2-secondPeriod" aria-selected="false"><span
                                    class="date-secondperiod"></span></button>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="tab-content " id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-table-2" role="tabpanel"
                    aria-labelledby="pills-table-2-tab">
                    <div class="row gap-3 mt-5 mb-5">
                        <table id="QuantityVehicleFirst" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center" style=" width: 20px;">No</th>
                                    <th scope="col" class="text-center">Date</th>
                                    <th scope="col" class="text-center">Mobil</th>
                                    <th scope="col" class="text-center">Motor</th>
                                    <th scope="col" class="text-center">Loading</th>
                                    <th scope="col" class="text-center">Taxi</th>
                                    <th scope="col" class="text-center">Lost Ticket</th>
                                    <th scope="col" class="text-center">Other</th>
                                    <th scope="col" class="text-center">Valet Lobby</th>
                                    <th scope="col" class="text-center">Valet Non-Lobby</th>
                                    <th scope="col" class="text-center">VIP</th>
                                    <th scope="col" class="text-center">Preferred Car</th>
                                    <th scope="col" class="text-center">Preferred motorbike</th>
                                    <th scope="col" class="text-center">E-Vip</th>
                                    <th scope="col" class="text-center">Extend Charging</th>
                                    <th scope="col" class="text-center">Total</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-table-2-secondPeriod" role="tabpanel"
                    aria-labelledby="pills-table-2-secondPeriod-tab">
                    <div class="row gap-3 mt-5 mb-5">
                        <table id="QuantityVehicleSecond" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center" style=" width: 20px;">No</th>
                                    <th scope="col" class="text-center">Date</th>
                                    <th scope="col" class="text-center">Mobil</th>
                                    <th scope="col" class="text-center">Motor</th>
                                    <th scope="col" class="text-center">Loading</th>
                                    <th scope="col" class="text-center">Taxi</th>
                                    <th scope="col" class="text-center">Lost Ticket</th>
                                    <th scope="col" class="text-center">Other</th>
                                    <th scope="col" class="text-center">Valet Lobby</th>
                                    <th scope="col" class="text-center">Valet Non-Lobby</th>
                                    <th scope="col" class="text-center">VIP</th>
                                    <th scope="col" class="text-center">Preferred Car</th>
                                    <th scope="col" class="text-center">Preferred motorbike</th>
                                    <th scope="col" class="text-center">E-Vip</th>
                                    <th scope="col" class="text-center">Extend Charging</th>
                                    <th scope="col" class="text-center">Total</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-custom mt-5 mb-5">
            <div class="content-picker d-flex align-items-center gap-5 ">
                <div class="align-self-center">
                    <span style="display: flex; align-items: center; gap: 8px;">
                        <i class="bi bi-calendar"></i>
                        <h5 style="margin: 0;">Income by Vehicle</h5>
                    </span>
                </div>
                <div class="align-self-center">
                    <ul class="nav nav-pills mb-3 gap-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-table-3-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-table-3" type="button" role="tab"
                                aria-controls="pills-table-3" aria-selected="true"><span
                                    class="date-firstperiod"></span></button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-table-3-secondPeriod-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-table-3-secondPeriod" type="button" role="tab"
                                aria-controls="pills-table-3-secondPeriod" aria-selected="false"><span
                                    class="date-secondperiod"></span></button>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-table-3" role="tabpanel"
                    aria-labelledby="pills-table-3-tab">
                    <div class="row gap-3 mt-5 mb-5">
                        <table id="IncomeVehicleFirst" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center" style=" width: 20px;">No</th>
                                    <th scope="col" class="text-center">Date</th>
                                    <th scope="col" class="text-center">Mobil</th>
                                    <th scope="col" class="text-center">Motor</th>
                                    <th scope="col" class="text-center">Loading</th>
                                    <th scope="col" class="text-center">Taxi</th>
                                    <th scope="col" class="text-center">Lost Ticket</th>
                                    <th scope="col" class="text-center">Other</th>
                                    <th scope="col" class="text-center">Valet Lobby</th>
                                    <th scope="col" class="text-center">Valet Non-Lobby</th>
                                    <th scope="col" class="text-center">VIP</th>
                                    <th scope="col" class="text-center">Preferred Car</th>
                                    <th scope="col" class="text-center">Preferred motorbike</th>
                                    <th scope="col" class="text-center">E-Vip</th>
                                    <th scope="col" class="text-center">Extend Charging</th>
                                    <th scope="col" class="text-center">Total</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-table-3-secondPeriod" role="tabpanel"
                    aria-labelledby="pills-table-3-secondPeriod-tab">
                    <div class="row gap-3 mt-5 mb-5">
                        <table id="IncomeVehicleSecond" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center" style=" width: 20px;">No</th>
                                    <th scope="col" class="text-center">Date</th>
                                    <th scope="col" class="text-center">Mobil</th>
                                    <th scope="col" class="text-center">Motor</th>
                                    <th scope="col" class="text-center">Loading</th>
                                    <th scope="col" class="text-center">Taxi</th>
                                    <th scope="col" class="text-center">Lost Ticket</th>
                                    <th scope="col" class="text-center">Other</th>
                                    <th scope="col" class="text-center">Valet Lobby</th>
                                    <th scope="col" class="text-center">Valet Non-Lobby</th>
                                    <th scope="col" class="text-center">VIP</th>
                                    <th scope="col" class="text-center">Preferred Car</th>
                                    <th scope="col" class="text-center">Preferred motorbike</th>
                                    <th scope="col" class="text-center">E-Vip</th>
                                    <th scope="col" class="text-center">Extend Charging</th>
                                    <th scope="col" class="text-center">Total</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('cari').addEventListener('click', function() {
            var start1 = document.getElementById('start-date-1').value;
            var end1 = document.getElementById('end-date-1').value;
            var start2 = document.getElementById('start-date-2').value;
            var end2 = document.getElementById('end-date-2').value;

            // Check if all date fields are filled
            if (!start1 || !end1 || !start2 || !end2) {
                // Show the alert message if any field is empty
                document.getElementById('alertMessage').style.display = 'block';
            } else {
                // Hide the alert message if all fields are filled
                document.getElementById('alertMessage').style.display = 'none';
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            // Initialize date input pairs for start and end date pickers
            const dateInputs = [{
                    start: '#start-date-1',
                    end: '#end-date-1'
                },
                {
                    start: '#start-date-2',
                    end: '#end-date-2'
                }
            ];

            // Set up date range pickers for each pair
            dateInputs.forEach(pair => {
                $(pair.start).daterangepicker({
                    singleDatePicker: true,
                    autoApply: true,
                    locale: {
                        format: 'DD-MM-YYYY'
                    }
                }, function(start) {
                    $(pair.start).val(start.format('DD-MM-YYYY'));
                });

                $(pair.end).daterangepicker({
                    singleDatePicker: true,
                    autoApply: true,
                    locale: {
                        format: 'DD-MM-YYYY'
                    }
                }, function(end) {
                    $(pair.end).val(end.format('DD-MM-YYYY'));
                });

                // Set placeholders for the input fields
                $(pair.start).val('').attr("placeholder", "Select Start Date");
                $(pair.end).val('').attr("placeholder", "Select End Date");
            });

            const tableIncomePayment = $('#IncomePaymentFirst').DataTable({
                searching: false,
                layout: {
                    topStart: {
                        buttons: [{
                                extend: 'copyHtml5',
                                titleAttr: 'Copy to Clipboard',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Custom Search | Income by Payment',
                            },
                            {
                                extend: 'excelHtml5',
                                titleAttr: 'Export to Excel',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Custom Search | Income by Payment',
                            },


                            {
                                extend: 'print',
                                titleAttr: 'Print',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Custom Search | Income by Payment',
                            },
                            {
                                extend: 'pdfHtml5',
                                titleAttr: 'Export to PDF',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Custom Search | Income by Payment',
                                customize: function(doc) {
                                    doc.pageMargins = [20, 30, 20,
                                        30
                                    ]; // [left, top, right, bottom]
                                    doc.defaultStyle.fontSize = 8; // Adjust font size if too large
                                    doc.styles.tableHeader.alignment = 'center';
                                    doc.styles.tableHeader.fillColor = '#eeeeee';
                                },
                            },
                        ]
                    }
                },
                paging: true,
                autoWidth: false,
                ordering: false,
                pageLength: 5,
                lengthChange: false,
                info: false,
                data: [],
                columns: [{
                        data: 'no'
                    },
                    {
                        data: 'date'
                    },
                    {
                        data: 'cash'
                    },
                    {
                        data: 'parkee'
                    },
                    {
                        data: 'emoney'
                    },
                    {
                        data: 'flazz'
                    },
                    {
                        data: 'brizzi'
                    },
                    {
                        data: 'tapcash'
                    },
                    {
                        data: 'qris'
                    },
                    {
                        data: 'dkijackpayment'
                    },
                    {
                        data: 'total'
                    }
                ],
            });

            const tableIncomePaymentSecond = $('#IncomePaymentSecond').DataTable({
                searching: false,
                paging: true,
                layout: {
                    topStart: {
                        buttons: [{
                                extend: 'copyHtml5',
                                titleAttr: 'Copy to Clipboard',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Custom Search | Income by Payment',
                            },
                            {
                                extend: 'excelHtml5',
                                titleAttr: 'Export to Excel',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Custom Search | Income by Payment',
                            },


                            {
                                extend: 'print',
                                titleAttr: 'Print',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Custom Search | Income by Payment',
                            },
                            {
                                extend: 'pdfHtml5',
                                titleAttr: 'Export to PDF',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Custom Search | Income by Payment',
                            },
                        ]
                    }
                },
                autoWidth: false,
                ordering: false,
                pageLength: 5,
                lengthChange: false,
                info: false,
                data: [],
                columns: [{
                        data: 'no'
                    },
                    {
                        data: 'date'
                    },
                    {
                        data: 'cash'
                    },
                    {
                        data: 'parkee'
                    },
                    {
                        data: 'emoney'
                    },
                    {
                        data: 'flazz'
                    },
                    {
                        data: 'brizzi'
                    },
                    {
                        data: 'tapcash'
                    },
                    {
                        data: 'qris'
                    },
                    {
                        data: 'dkijackpayment'
                    },
                    {
                        data: 'total'
                    }
                ],
            });

            const tableQuantityVehicleFirst = $('#QuantityVehicleFirst').DataTable({
                searching: false,
                paging: true,
                layout: {
                    topStart: {
                        buttons: [{
                                extend: 'copyHtml5',
                                titleAttr: 'Copy to Clipboard',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Custom Search | Quantity by Vehicle',
                            },
                            {
                                extend: 'excelHtml5',
                                titleAttr: 'Export to Excel',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Custom Search | Quantity by Vehicle',
                            },


                            {
                                extend: 'print',
                                titleAttr: 'Print',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Custom Search | Quantity by Vehicle',
                            },
                            {
                                extend: 'pdfHtml5',
                                titleAttr: 'Export to PDF',
                                title: 'Custom Search | Quantity by Vehicle',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                customize: function(doc) {
                                    // Ukuran halaman dan orientasi
                                    doc.pageSize = 'A4';
                                    doc.pageOrientation = 'landscape';

                                    // Margin halaman [left, top, right, bottom]
                                    doc.pageMargins = [10, 10, 10, 10];

                                    // Ukuran font konten dan header
                                    doc.defaultStyle.fontSize = 6;
                                    doc.styles.tableHeader.fontSize = 7;
                                    doc.styles.tableHeader.alignment = 'center';

                                    // Atur semua kolom agar lebar fleksibel
                                    const table = doc.content[1].table;
                                    const colCount = table.body[0].length;
                                    table.widths = Array(colCount).fill('*');
                                }
                            },

                        ]
                    }
                },
                autoWidth: false,
                ordering: false,
                pageLength: 5,
                lengthChange: false,
                info: false,
                data: [],
                columns: [{
                        data: 'no'
                    },
                    {
                        data: 'date'
                    },
                    {
                        data: 'carqty'
                    },
                    {
                        data: 'motorbikeqty'
                    },
                    {
                        data: 'truckqty'
                    },
                    {
                        data: 'taxiqty'
                    },
                    {
                        data: 'lostticketqty'
                    },
                    {
                        data: 'otherqty'
                    },
                    {
                        data: 'valetlobbyqty'
                    },
                    {
                        data: 'valetnonlobbyqty'
                    },
                    {
                        data: 'vipqty'
                    },
                    {
                        data: 'carpreferredqty'
                    },
                    {
                        data: 'motorbikepreferredqty'
                    },
                    {
                        data: 'evipqty'
                    },
                    {
                        data: 'extendchargingqty'
                    },
                    {
                        data: 'totalvehicle'
                    }
                ],
            });

            const tableQuantityVehicleSecond = $('#QuantityVehicleSecond').DataTable({
                searching: false,
                paging: true,
                layout: {
                    topStart: {
                        buttons: [{
                                extend: 'copyHtml5',
                                titleAttr: 'Copy to Clipboard',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Custom Search | Quantity by Vehicle',
                            },
                            {
                                extend: 'excelHtml5',
                                titleAttr: 'Export to Excel',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Custom Search | Quantity by Vehicle',
                            },


                            {
                                extend: 'print',
                                titleAttr: 'Print',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Custom Search | Quantity by Vehicle',
                            },
                            {
                                extend: 'pdfHtml5',
                                titleAttr: 'Export to PDF',
                                title: 'Custom Search | Quantity by Vehicle',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                customize: function(doc) {
                                    // Ukuran halaman dan orientasi
                                    doc.pageSize = 'A4';
                                    doc.pageOrientation = 'landscape';

                                    // Margin halaman [left, top, right, bottom]
                                    doc.pageMargins = [10, 10, 10, 10];

                                    // Ukuran font konten dan header
                                    doc.defaultStyle.fontSize = 6;
                                    doc.styles.tableHeader.fontSize = 7;
                                    doc.styles.tableHeader.alignment = 'center';

                                    // Atur semua kolom agar lebar fleksibel
                                    const table = doc.content[1].table;
                                    const colCount = table.body[0].length;
                                    table.widths = Array(colCount).fill('*');
                                }
                            },
                        ]
                    }
                },
                autoWidth: false,
                ordering: false,
                pageLength: 5,
                lengthChange: false,
                info: false,
                data: [],
                columns: [{
                        data: 'no'
                    },
                    {
                        data: 'date'
                    },
                    {
                        data: 'carqty'
                    },
                    {
                        data: 'motorbikeqty'
                    },
                    {
                        data: 'truckqty'
                    },
                    {
                        data: 'taxiqty'
                    },
                    {
                        data: 'lostticketqty'
                    },
                    {
                        data: 'otherqty'
                    },
                    {
                        data: 'valetlobbyqty'
                    },
                    {
                        data: 'valetnonlobbyqty'
                    },
                    {
                        data: 'vipqty'
                    },
                    {
                        data: 'carpreferredqty'
                    },
                    {
                        data: 'motorbikepreferredqty'
                    },
                    {
                        data: 'evipqty'
                    },
                    {
                        data: 'extendchargingqty'
                    },
                    {
                        data: 'totalvehicle'
                    }
                ],
            });

            const tableIncomeVehicleFirst = $('#IncomeVehicleFirst').DataTable({
                searching: false,
                paging: true,
                autoWidth: false,
                layout: {
                    topStart: {
                        buttons: [{
                                extend: 'copyHtml5',
                                titleAttr: 'Copy to Clipboard',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Custom Search | Income by Vehicle',
                            },
                            {
                                extend: 'excelHtml5',
                                titleAttr: 'Export to Excel',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Custom Search | Income by Vehicle',
                            },


                            {
                                extend: 'print',
                                titleAttr: 'Print',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Custom Search | Income by Vehicle',
                            },
                            {
                                extend: 'pdfHtml5',
                                titleAttr: 'Export to PDF',
                                title: 'Custom Search | Income by Vehicle',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                customize: function(doc) {
                                    // Ukuran halaman dan orientasi
                                    doc.pageSize = 'A4';
                                    doc.pageOrientation = 'landscape';

                                    // Margin halaman [left, top, right, bottom]
                                    doc.pageMargins = [10, 10, 10, 10];

                                    // Ukuran font konten dan header
                                    doc.defaultStyle.fontSize = 6;
                                    doc.styles.tableHeader.fontSize = 7;
                                    doc.styles.tableHeader.alignment = 'center';

                                    // Atur semua kolom agar lebar fleksibel
                                    const table = doc.content[1].table;
                                    const colCount = table.body[0].length;
                                    table.widths = Array(colCount).fill('*');
                                }
                            },
                        ]
                    }
                },
                ordering: false,
                pageLength: 5,
                lengthChange: false,
                info: false,
                data: [],
                columns: [{
                        data: 'no'
                    },
                    {
                        data: 'date'
                    },
                    {
                        data: 'carincome'
                    },
                    {
                        data: 'motorbikeincome'
                    },
                    {
                        data: 'truckincome'
                    },
                    {
                        data: 'taxiincome'
                    },
                    {
                        data: 'lostticketincome'
                    },
                    {
                        data: 'otherincome'
                    },
                    {
                        data: 'valetlobbyincome'
                    },
                    {
                        data: 'valetnonlobbyincome'
                    },
                    {
                        data: 'vipincome'
                    },
                    {
                        data: 'carpreferredincome'
                    },
                    {
                        data: 'motorbikepreferredincome'
                    },
                    {
                        data: 'evipincome'
                    },
                    {
                        data: 'extendchargingincome'
                    },
                    {
                        data: 'totalvehicleincome'
                    }
                ],
            });

            const tableIncomeVehicleSecond = $('#IncomeVehicleSecond').DataTable({
                searching: false,
                paging: true,
                layout: {
                    topStart: {
                        buttons: [{
                                extend: 'copyHtml5',
                                titleAttr: 'Copy to Clipboard',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Custom Search | Income by Vehicle',
                            },
                            {
                                extend: 'excelHtml5',
                                titleAttr: 'Export to Excel',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Custom Search | Income by Vehicle',
                            },


                            {
                                extend: 'print',
                                titleAttr: 'Print',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Custom Search | Income by Vehicle',
                            },
                            {
                                extend: 'pdfHtml5',
                                titleAttr: 'Export to PDF',
                                title: 'Custom Search | Income by Vehicle',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                customize: function(doc) {
                                    // Ukuran halaman dan orientasi
                                    doc.pageSize = 'A4';
                                    doc.pageOrientation = 'landscape';

                                    // Margin halaman [left, top, right, bottom]
                                    doc.pageMargins = [10, 10, 10, 10];

                                    // Ukuran font konten dan header
                                    doc.defaultStyle.fontSize = 6;
                                    doc.styles.tableHeader.fontSize = 7;
                                    doc.styles.tableHeader.alignment = 'center';

                                    // Atur semua kolom agar lebar fleksibel
                                    const table = doc.content[1].table;
                                    const colCount = table.body[0].length;
                                    table.widths = Array(colCount).fill('*');
                                }
                            },
                        ]
                    }
                },
                autoWidth: false,
                ordering: false,
                pageLength: 5,
                lengthChange: false,
                info: false,
                data: [],
                columns: [{
                        data: 'no'
                    },
                    {
                        data: 'date'
                    },
                    {
                        data: 'carincome'
                    },
                    {
                        data: 'motorbikeincome'
                    },
                    {
                        data: 'truckincome'
                    },
                    {
                        data: 'taxiincome'
                    },
                    {
                        data: 'lostticketincome'
                    },
                    {
                        data: 'otherincome'
                    },
                    {
                        data: 'valetlobbyincome'
                    },
                    {
                        data: 'valetnonlobbyincome'
                    },
                    {
                        data: 'vipincome'
                    },
                    {
                        data: 'carpreferredincome'
                    },
                    {
                        data: 'motorbikepreferredincome'
                    },
                    {
                        data: 'evipincome'
                    },
                    {
                        data: 'extendchargingincome'
                    },
                    {
                        data: 'totalvehicleincome'
                    }
                ],
            });

            // Handle the search button click
            $('#cari').on('click', function(e) {
                e.preventDefault();

                // Get values from the date pickers
                const startDate1 = $('#start-date-1').val();
                const endDate1 = $('#end-date-1').val();
                const startDate2 = $('#start-date-2').val();
                const endDate2 = $('#end-date-2').val();

                // Validate the form before submitting
                if (!startDate1 || !endDate1 || !startDate2 || !endDate2) {
                    $('#alertMessage').show(); // Show an alert message if validation fails
                    return;
                }

                // Log data to console before making the request
                console.log('Sending data:', {
                    first_start_date: startDate1,
                    first_end_date: endDate1,
                    second_start_date: startDate2,
                    second_end_date: endDate2,
                    _token: '{{ csrf_token() }}' // CSRF token for security
                });

                function formatReadableDate(dateStr) {
                    const [day, month, year] = dateStr.split('-');
                    const date = new Date(`${year}-${month}-${day}`);
                    return date.toLocaleDateString('en-GB', {
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric'
                    });
                }

                function formatQuantity(quantity) {
                    return new Intl.NumberFormat().format(quantity);
                }

                const formatRupiah = (number) => {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(number);
                };

                const formattedStartDate1 = formatReadableDate(startDate1);
                const formattedEndDate1 = formatReadableDate(endDate1);
                const formattedStartDate2 = formatReadableDate(startDate2);
                const formattedEndDate2 = formatReadableDate(endDate2);
                const tgl_row1 = formattedStartDate1 + ' - ' +
                    formattedEndDate1;
                const tgl_row2 = formattedStartDate2 + ' - ' + formattedEndDate2;

                $('.date-firstperiod').text(formattedStartDate1 + ' - ' + formattedEndDate1);
                $('.date-secondperiod').text(formattedStartDate2 + ' - ' + formattedEndDate2);
                $('.tgl_row1').text(formattedStartDate1 + ' - ' + formattedEndDate1);
                $('.tgl_row2').text(formattedStartDate2 + ' - ' + formattedEndDate2);
                $.ajax({
                    url: '{{ route('customSearch') }}',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        first_start_date: startDate1,
                        first_end_date: endDate1,
                        second_start_date: startDate2,
                        second_end_date: endDate2,
                        _token: '{{ csrf_token() }}'
                    }),
                    success: function(response) {
                        console.log('Success:', response);


                        const firstPeriodTotals = response
                            .totals.first_period;
                        const secondPeriodTotals = response.totals.second_period;
                        // console.log('Result:', firstPeriodTotals);
                        const GrandTotals = response.grand_totals;


                        const incomePaymentDataFirstPeriod = [
                            firstPeriodTotals.cash,
                            firstPeriodTotals.parkeepayment,
                            firstPeriodTotals.emoney,
                            firstPeriodTotals.flazz,
                            firstPeriodTotals.brizzi,
                            firstPeriodTotals.dkijackpayment,
                            firstPeriodTotals.tapcash,
                            firstPeriodTotals.qrisepayment
                        ]

                        const incomePaymentDataSecondPeriod = [
                            secondPeriodTotals.cash,
                            secondPeriodTotals.parkeepayment,
                            secondPeriodTotals.emoney,
                            secondPeriodTotals.flazz,
                            secondPeriodTotals.brizzi,
                            secondPeriodTotals.dkijackpayment,
                            secondPeriodTotals.tapcash,
                            secondPeriodTotals.qrisepayment
                        ]
                        const vipsum1 = firstPeriodTotals.valetlobbyqty + firstPeriodTotals
                            .vipqty + firstPeriodTotals.vipramayanaqty + firstPeriodTotals
                            .vipnonramayanaqty;

                        const vipsum2 = secondPeriodTotals.valetlobbyqty + secondPeriodTotals
                            .vipqty + secondPeriodTotals.vipramayanaqty + secondPeriodTotals
                            .vipnonramayanaqty;
                        const vipincome1 = firstPeriodTotals.vipincome + firstPeriodTotals
                            .vipramayanaincome + firstPeriodTotals
                            .vipnonramayanaincome;

                        const vipincome2 = secondPeriodTotals.vipincome + secondPeriodTotals
                            .vipramayanaincome +
                            secondPeriodTotals.vipnonramayanaincome;
                        const quantityVehicleDataFirstPeriod = [
                            firstPeriodTotals.carqty,
                            firstPeriodTotals.motorbikeqty,
                            firstPeriodTotals.truckqty,
                            firstPeriodTotals.taxiqty,
                            vipsum1,
                            firstPeriodTotals.carpreferredqty,
                            firstPeriodTotals.motorbikepreferredqty,
                            firstPeriodTotals.evipqty,
                            firstPeriodTotals.extendchargingqty,
                            firstPeriodTotals.lostticketqty,
                        ]

                        const quantityVehicleDataSecondPeriod = [
                            secondPeriodTotals.carqty,
                            secondPeriodTotals.motorbikeqty,
                            secondPeriodTotals.truckqty,
                            secondPeriodTotals.taxiqty,
                            vipsum2,
                            secondPeriodTotals.carpreferredqty,
                            secondPeriodTotals.motorbikepreferredqty,
                            secondPeriodTotals.evipqty,
                            secondPeriodTotals.extendchargingqty,
                            secondPeriodTotals.lostticketqty,
                        ]

                        const incomeVehicleDataFirstPeriod = [
                            firstPeriodTotals.carincome,
                            firstPeriodTotals.motorbikeincome,
                            firstPeriodTotals.truckincome,
                            firstPeriodTotals.taxiincome,
                            vipincome1,
                            firstPeriodTotals.carpreferredincome,
                            firstPeriodTotals.motorbikepreferredincome,
                            firstPeriodTotals.evipincome,
                            firstPeriodTotals.extendchargingincome,
                            firstPeriodTotals.lostticketincome,
                        ]

                        const incomeVehicleDataSecondPeriod = [
                            secondPeriodTotals.carincome,
                            secondPeriodTotals.motorbikeincome,
                            secondPeriodTotals.truckincome,
                            secondPeriodTotals.taxiincome,
                            vipincome2,
                            secondPeriodTotals.carpreferredincome,
                            secondPeriodTotals.motorbikepreferredincome,
                            secondPeriodTotals.evipincome,
                            secondPeriodTotals.extendchargingincome,
                            secondPeriodTotals.lostticketincome,
                        ]

                        const labels = [tgl_row1, tgl_row2];


                        const incomePaymentBarData = {
                            labels: labels,
                            datasets: [{
                                    label: 'cash',
                                    data: [incomePaymentDataFirstPeriod[0],
                                        incomePaymentDataSecondPeriod[0]
                                    ],
                                    backgroundColor: 'rgba(255, 99, 132, 10)',
                                },
                                {
                                    label: 'parkee',
                                    data: [incomePaymentDataFirstPeriod[1],
                                        incomePaymentDataSecondPeriod[1]
                                    ],
                                    backgroundColor: 'rgba(54, 162, 235, 1)',
                                },
                                {
                                    label: 'emoney',
                                    data: [incomePaymentDataFirstPeriod[2],
                                        incomePaymentDataSecondPeriod[2]
                                    ],
                                    backgroundColor: 'rgba(255, 206, 86, 1)',

                                },
                                {
                                    label: 'flazz',
                                    data: [incomePaymentDataFirstPeriod[3],
                                        incomePaymentDataSecondPeriod[3]
                                    ],
                                    backgroundColor: 'rgba(75, 192, 192, 1)',
                                },
                                {
                                    label: 'brizzi',
                                    data: [incomePaymentDataFirstPeriod[4],
                                        incomePaymentDataSecondPeriod[4]
                                    ],
                                    backgroundColor: 'rgba(153, 102, 255, 1)',
                                },
                                {
                                    label: 'dkijackpayment',
                                    data: [incomePaymentDataFirstPeriod[5],
                                        incomePaymentDataSecondPeriod[5]
                                    ],
                                    backgroundColor: 'rgba(255, 159, 64, 1)',
                                },
                                {
                                    label: 'tapcash',
                                    data: [incomePaymentDataFirstPeriod[6],
                                        incomePaymentDataSecondPeriod[6]
                                    ],
                                    backgroundColor: 'rgba(188, 4, 4, 1)',
                                },
                                {
                                    label: 'qrisepayment',
                                    data: [incomePaymentDataFirstPeriod[7],
                                        incomePaymentDataSecondPeriod[7]
                                    ],
                                    backgroundColor: 'rgba(12, 99, 132, 1)',
                                }
                            ]
                        };

                        const incomePaymentBarOptions = {
                            responsive: true,
                            maintainAspectRatio: false,

                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return formatRupiah(value);
                                        },
                                        color: '#fff',
                                    }
                                },
                                x: {
                                    ticks: {
                                        autoSkip: false,
                                        maxRotation: 0,
                                        minRotation: 0,
                                        color: '#fff',
                                        font: {
                                            size: 10,
                                        }
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    position: 'top',
                                    labels: {
                                        color: '#fff',

                                    }
                                },
                                title: {
                                    display: true,
                                    text: 'Income by Payment',
                                    color: '#fff',
                                }
                            }
                        };
                        // Cek dan destroy chart sebelumnya (jika sudah ada)
                        if (window.incomePaymentBarChart) {
                            window.incomePaymentBarChart.destroy();
                        }

                        // Inisialisasi ulang chart
                        const incomePaymentBarCtx = document.getElementById('incomePayment')
                            .getContext('2d');

                        window.incomePaymentBarChart = new Chart(incomePaymentBarCtx, {
                            type: 'bar',
                            data: incomePaymentBarData,
                            options: incomePaymentBarOptions
                        });


                        const quantityVehicleBarData = {
                            labels: labels,
                            datasets: [{
                                    label: 'Car',
                                    data: [quantityVehicleDataFirstPeriod[0],
                                        quantityVehicleDataSecondPeriod[0]
                                    ],
                                    backgroundColor: 'rgba(231, 76, 60, 1)',
                                },
                                {
                                    label: 'Motorbike',
                                    data: [quantityVehicleDataFirstPeriod[1],
                                        quantityVehicleDataSecondPeriod[1]
                                    ],
                                    backgroundColor: 'rgba(52, 152, 219, 1)',
                                },
                                {
                                    label: 'Truck',
                                    data: [quantityVehicleDataFirstPeriod[2],
                                        quantityVehicleDataSecondPeriod[2]
                                    ],
                                    backgroundColor: 'rgba(241, 196, 15, 1)',

                                },
                                {
                                    label: 'Taxi',
                                    data: [quantityVehicleDataFirstPeriod[3],
                                        quantityVehicleDataSecondPeriod[3]
                                    ],
                                    backgroundColor: 'rgba(46, 204, 113, 1)',
                                },
                                {
                                    label: 'Valet',
                                    data: [quantityVehicleDataFirstPeriod[4],
                                        quantityVehicleDataSecondPeriod[4]
                                    ],
                                    backgroundColor: 'rgba(155, 89, 182, 1)',
                                },
                                {
                                    label: 'Car Preferred',
                                    data: [quantityVehicleDataFirstPeriod[5],
                                        quantityVehicleDataSecondPeriod[5]
                                    ],
                                    backgroundColor: 'rgba(230, 126, 34, 1)',
                                },
                                {
                                    label: 'Motorbike Preferred',
                                    data: [quantityVehicleDataFirstPeriod[6],
                                        quantityVehicleDataSecondPeriod[6]
                                    ],
                                    backgroundColor: 'rgba(26, 188, 156, 1)',
                                },
                                {
                                    label: 'EVIP',
                                    data: [quantityVehicleDataFirstPeriod[7],
                                        quantityVehicleDataSecondPeriod[7]
                                    ],
                                    backgroundColor: 'rgba(149, 165, 166, 1)',

                                },
                                {
                                    label: 'Extend Charging',
                                    data: [quantityVehicleDataFirstPeriod[8],
                                        quantityVehicleDataSecondPeriod[8]
                                    ],
                                    backgroundColor: 'rgba(234, 345, 10, 1)',
                                },
                                {
                                    label: 'Lost Ticket',
                                    data: [quantityVehicleDataFirstPeriod[9],
                                        quantityVehicleDataSecondPeriod[9]
                                    ],
                                    backgroundColor: 'rgba(52, 73, 94, 1)',
                                }
                            ]
                        };

                        const quantityVehicleBarOptions = {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return formatQuantity(value);
                                        },
                                        color: '#fff',
                                    }
                                },
                                x: {
                                    ticks: {
                                        autoSkip: false,
                                        maxRotation: 0,
                                        minRotation: 0,
                                        color: '#fff',
                                        font: {
                                            size: 10,
                                        }
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    position: 'top',
                                    labels: {
                                        color: '#fff',

                                    }
                                },
                                title: {
                                    display: true,
                                    text: 'Quantity by Vehicle',
                                    color: '#fff',
                                }
                            }
                        };

                        // Destroy dulu kalau chart sebelumnya sudah ada
                        if (window.quantityVehicleBarChart) {
                            window.quantityVehicleBarChart.destroy();
                        }

                        // Bikin ulang chart dengan data terbaru
                        const quantityVehicleBarCtx = document.getElementById('quantityVehicle')
                            .getContext('2d');

                        window.quantityVehicleBarChart = new Chart(quantityVehicleBarCtx, {
                            type: 'bar',
                            data: quantityVehicleBarData,
                            options: quantityVehicleBarOptions
                        });


                        const doughnutLabelsLine = {
                            id: 'doughnutLabelsLine',
                            afterDraw(chart, args, options) {
                                const {
                                    ctx,
                                    chartArea: {
                                        top,
                                        bottom,
                                        left,
                                        right,
                                        width,
                                        height
                                    }
                                } = chart;
                                chart.data.datasets.forEach((dataset, i) => {
                                    // if (chart.getDatasetMeta(i).hidden) return;
                                    chart.getDatasetMeta(i).data.forEach((datapoint,
                                        index) => {
                                        const {
                                            x,
                                            y
                                        } = datapoint.tooltipPosition();

                                        ctx.fillStyle = dataset
                                            .backgroundColor[index];

                                        const halfwidth = width / 2;
                                        const halfheight = height / 2;

                                        const xLine = x >= halfwidth ? x +
                                            40 : x - 40;
                                        const yLine = y >= halfheight ? y +
                                            40 : y - 40;
                                        const extraLine = x >= halfwidth ?
                                            10 : -10;

                                        // Line
                                        ctx.beginPath();
                                        ctx.moveTo(x, y);
                                        ctx.lineTo(xLine, yLine);
                                        ctx.lineTo(xLine + extraLine,
                                            yLine);
                                        ctx.strokeStyle = dataset
                                            .backgroundColor[index];
                                        ctx.stroke();

                                        // Text
                                        const total = dataset.data.reduce((
                                                acc, val) => acc + val,
                                            0);
                                        const percentage = ((dataset.data[
                                                index] / total) * 100)
                                            .toFixed(1) + '%';
                                        ctx.font = 'bold 17px Arial';

                                        const textXPosition = x >=
                                            halfwidth ? 'left' : 'right';
                                        const newPx = x >= halfwidth ? 5 : -
                                            5;
                                        ctx.textAlign = textXPosition;
                                        ctx.textBaseline = 'middle';
                                        ctx.fillStyle = dataset
                                            .backgroundColor[index];
                                        ctx.fillText(percentage, xLine +
                                            extraLine + newPx, yLine);
                                    })
                                })
                            }
                        }

                        const distanceLegend = {
                            beforeInit(chart) {
                                const originalFit = chart.legend.fit;
                                chart.legend.fit = function fit() {
                                    originalFit.bind(chart.legend)();
                                    this.height += 20;
                                }
                            }
                        }

                        const firstVehicleDistributionData = {
                            labels: ['Car', 'Motorbike', 'Truck', 'Taxi', 'Valet',
                                'Car Preferred',
                                'Motorbike Preferred', 'EVIP', 'Extend Charging',
                                'Lost Ticket'
                            ],
                            datasets: [{
                                label: 'First Period',
                                data: quantityVehicleDataFirstPeriod,
                                backgroundColor: [
                                    'rgba(231, 76, 60, 1)',
                                    'rgba(52, 152, 219, 1)',
                                    'rgba(241, 196, 15, 1)',
                                    'rgba(46, 204, 113, 1)',
                                    'rgba(155, 89, 182, 1)',
                                    'rgba(230, 126, 34, 1)',
                                    'rgba(26, 188, 156, 1)',
                                    'rgba(149, 165, 166, 1)',
                                    'rgba(234, 345, 10, 1)',
                                    'rgba(52, 73, 94, 1)'
                                ]

                            }]

                        }




                        // pie chart 
                        const firstVehicleDistributionOptions = {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'right',
                                    labels: {
                                        color: '#fff',

                                    }
                                },
                                title: {
                                    display: true,
                                    text: 'First Period Vehicle Distribution',
                                    color: '#fff',
                                    padding: {
                                        top: 10,
                                        bottom: 30
                                    }
                                },
                                datalabels: {
                                    color: '#fff',
                                    // formatter: (value, context) => {
                                    //     const total = context.chart.data.datasets[0]
                                    //         .data.reduce((sum, val) => sum + val, 0);
                                    //     const percentage = ((value / total) * 100)
                                    //         .toFixed(1); // 1 decimal
                                    //     const label = context.chart.data.labels[context
                                    //         .dataIndex];
                                    //     // return `${label}: (${formatQuantity(value)})`;
                                    // },

                                    // anchor: 'center',
                                    // align: 'center',
                                    // font: {
                                    //     size: 10,
                                    //     weight: 'bold',
                                    // }
                                }
                            },

                        };

                        // Destroy dulu kalau chart sebelumnya sudah ada
                        if (window.firstVehicleDistributionChart) {
                            window.firstVehicleDistributionChart.destroy();
                        }

                        // Bikin ulang chart dengan data terbaru
                        const firstVehicleDistributionCtx = document.getElementById(
                                'firstVehicleDistribution')
                            .getContext('2d');

                        window.firstVehicleDistributionChart = new Chart(
                            firstVehicleDistributionCtx, {
                                type: 'doughnut',
                                data: firstVehicleDistributionData,
                                options: firstVehicleDistributionOptions,
                                plugins: [
                                    ChartDataLabels, doughnutLabelsLine, distanceLegend
                                ]
                            });


                        const secondVehicleDistributionData = {
                            labels: ['Car', 'Motorbike', 'Truck', 'Taxi', 'Valet',
                                'Car Preferred',
                                'Motorbike Preferred', 'EVIP', 'Extend Charging',
                                'Lost Ticket'
                            ],
                            datasets: [{
                                label: 'Second Period',
                                data: quantityVehicleDataSecondPeriod,
                                backgroundColor: [
                                    'rgba(231, 76, 60, 1)',
                                    'rgba(52, 152, 219, 1)',
                                    'rgba(241, 196, 15, 1)',
                                    'rgba(46, 204, 113, 1)',
                                    'rgba(155, 89, 182, 1)',
                                    'rgba(230, 126, 34, 1)',
                                    'rgba(26, 188, 156, 1)',
                                    'rgba(149, 165, 166, 1)',
                                    'rgba(234, 345, 10, 1)',
                                    'rgba(52, 73, 94, 1)'
                                ]

                            }]

                        }
                        // pie chart
                        const secondVehicleDistributionOptions = {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'right',
                                    labels: {
                                        color: '#fff',

                                    }
                                },
                                title: {
                                    display: true,
                                    text: 'Second Period Vehicle Distribution',
                                    color: '#fff',
                                },
                                datalabels: {
                                    color: '#fff',
                                    // formatter: (value, context) => {
                                    //     const total = context.chart.data.datasets[0]
                                    //         .data.reduce((sum, val) => sum + val, 0);
                                    //     const percentage = ((value / total) * 100)
                                    //         .toFixed(1); // 1 decimal
                                    //     const label = context.chart.data.labels[context
                                    //         .dataIndex];
                                    //     return `${label}: ${percentage}% (${formatQuantity(value)})`;
                                    // },
                                    // anchor: 'center',
                                    // align: 'center',
                                    // font: {
                                    //     size: 10,
                                    //     weight: 'bold',
                                    // }
                                }
                            },

                        };

                        // Destroy dulu kalau chart sebelumnya sudah ada
                        if (window.secondVehicleDistributionChart) {
                            window.secondVehicleDistributionChart.destroy();
                        }
                        // Bikin ulang chart dengan data terbaru
                        const secondVehicleDistributionCtx = document.getElementById(
                                'SecondVehicleDistribution')
                            .getContext('2d');
                        window.secondVehicleDistributionChart = new Chart(
                            secondVehicleDistributionCtx, {
                                type: 'doughnut',
                                data: secondVehicleDistributionData,
                                options: secondVehicleDistributionOptions,
                                plugins: [
                                    ChartDataLabels, doughnutLabelsLine, distanceLegend
                                ]
                            });




                        const incomeVehicleBarData = {
                            labels: labels,
                            datasets: [{
                                    label: 'Car',
                                    data: [incomeVehicleDataFirstPeriod[0],
                                        incomeVehicleDataSecondPeriod[0]
                                    ],
                                    backgroundColor: 'rgba(231, 76, 60, 1)',
                                },
                                {
                                    label: 'Motorbike',
                                    data: [incomeVehicleDataFirstPeriod[1],
                                        incomeVehicleDataSecondPeriod[1]
                                    ],
                                    backgroundColor: 'rgba(52, 152, 219, 1)',
                                },
                                {
                                    label: 'Truck',
                                    data: [incomeVehicleDataFirstPeriod[2],
                                        incomeVehicleDataSecondPeriod[2]
                                    ],
                                    backgroundColor: 'rgba(241, 196, 15, 1)',

                                },
                                {
                                    label: 'Taxi',
                                    data: [incomeVehicleDataFirstPeriod[3],
                                        incomeVehicleDataSecondPeriod[3]
                                    ],
                                    backgroundColor: 'rgba(46, 204, 113, 1)',
                                },
                                {
                                    label: 'Valet',
                                    data: [incomeVehicleDataFirstPeriod[4],
                                        incomeVehicleDataSecondPeriod[4]
                                    ],
                                    backgroundColor: 'rgba(155, 89, 182, 1)',
                                },
                                {
                                    label: 'Car Preferred',
                                    data: [incomeVehicleDataFirstPeriod[5],
                                        incomeVehicleDataSecondPeriod[5]
                                    ],
                                    backgroundColor: 'rgba(230, 126, 34, 1)',
                                },
                                {
                                    label: 'Motorbike Preferred',
                                    data: [incomeVehicleDataFirstPeriod[6],
                                        incomeVehicleDataSecondPeriod[6]
                                    ],
                                    backgroundColor: 'rgba(26, 188, 156, 1)',
                                },
                                {
                                    label: 'EVIP',
                                    data: [incomeVehicleDataFirstPeriod[7],
                                        incomeVehicleDataSecondPeriod[7]
                                    ],
                                    backgroundColor: 'rgba(149, 165, 166, 1)',
                                },
                                {
                                    label: 'Extend Charging',
                                    data: [incomeVehicleDataFirstPeriod[8],
                                        incomeVehicleDataSecondPeriod[8]
                                    ],
                                    backgroundColor: 'rgba(234, 345, 10, 1)',
                                },
                                {
                                    label: 'Lost Ticket',
                                    data: [incomeVehicleDataFirstPeriod[9],
                                        incomeVehicleDataSecondPeriod[9]
                                    ],
                                    backgroundColor: 'rgba(52, 73, 94, 1)',
                                }
                            ]
                        };

                        const incomeVehicleBarOptions = {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return formatRupiah(value);
                                        },
                                        color: '#fff',
                                    }
                                },
                                x: {
                                    ticks: {
                                        autoSkip: false,
                                        maxRotation: 0,
                                        minRotation: 0,
                                        color: '#fff',
                                        font: {
                                            size: 10,
                                        }
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    position: 'top',
                                    labels: {
                                        color: '#fff',

                                    }
                                },
                                title: {
                                    display: true,
                                    text: 'Income by Vehicle',
                                    color: '#fff',
                                }
                            }
                        };

                        // Destroy dulu kalau chart sebelumnya sudah ada
                        if (window.incomeVehicleBarChart) {
                            window.incomeVehicleBarChart.destroy();
                        }

                        // Buat ulang chart dengan data terbaru
                        const incomeVehicleBarCtx = document.getElementById('incomeVehicle')
                            .getContext('2d');

                        window.incomeVehicleBarChart = new Chart(incomeVehicleBarCtx, {
                            type: 'bar',
                            data: incomeVehicleBarData,
                            options: incomeVehicleBarOptions
                        });

                        const dataFirstPeriodIncomePayment = response.data.first_period;
                        const dataSecondPeriodIncomePayment = response.data.second_period;

                        const rows = dataFirstPeriodIncomePayment.map((item, index) => {
                            const total = [
                                item.cash,
                                item.parkeepayment,
                                item.emoney,
                                item.flazz,
                                item.brizzi,
                                item.tapcash,
                                item.qrisepayment,
                                item.dkijackpayment
                            ].reduce((sum, value) => sum + Number(value), 0);

                            return {
                                no: index + 1,
                                date: item.periode,
                                cash: formatRupiah(item.cash),
                                parkee: formatRupiah(item.parkeepayment),
                                emoney: formatRupiah(item.emoney),
                                flazz: formatRupiah(item.flazz),
                                brizzi: formatRupiah(item.brizzi),
                                tapcash: formatRupiah(item.tapcash),
                                qris: formatRupiah(item.qrisepayment),
                                dkijackpayment: formatRupiah(item.dkijackpayment),
                                total: formatRupiah(total)
                            };
                        });

                        const rowsIncomePaymentSecond = dataSecondPeriodIncomePayment.map((item,
                            index) => {
                            const total = [
                                item.cash,
                                item.parkeepayment,
                                item.emoney,
                                item.flazz,
                                item.brizzi,
                                item.tapcash,
                                item.qrisepayment,
                                item.dkijackpayment
                            ].reduce((sum, value) => sum + Number(value), 0);

                            return {
                                no: index + 1,
                                date: item.periode,
                                cash: formatRupiah(item.cash),
                                parkee: formatRupiah(item.parkeepayment),
                                emoney: formatRupiah(item.emoney),
                                flazz: formatRupiah(item.flazz),
                                brizzi: formatRupiah(item.brizzi),
                                tapcash: formatRupiah(item.tapcash),
                                qris: formatRupiah(item.qrisepayment),
                                dkijackpayment: formatRupiah(item.dkijackpayment),
                                total: formatRupiah(total)
                            };
                        });

                        const rowsQuantityVehicle = dataFirstPeriodIncomePayment.map((item,
                            index) => {
                            const total = [
                                item.carqty,
                                item.motorbikeqty,
                                item.truckqty,
                                item.taxiqty,
                                item.lostticketqty,
                                item.otherqty,
                                item.valetlobbyqty,
                                item.valetnonlobbyqty,
                                item.vipqty,
                                item.carpreferredqty,
                                item.motorbikepreferredqty,
                                item.evipqty,
                                item.extendchargingqty
                            ].reduce((sum, val) => sum + Number(val), 0);

                            return {
                                no: index + 1,
                                date: item.periode,
                                carqty: formatQuantity(item.carqty),
                                motorbikeqty: formatQuantity(item.motorbikeqty),
                                truckqty: formatQuantity(item.truckqty),
                                taxiqty: formatQuantity(item.taxiqty),
                                lostticketqty: formatQuantity(item.lostticketqty),
                                otherqty: formatQuantity(item.otherqty),
                                valetlobbyqty: formatQuantity(item.valetlobbyqty),
                                valetnonlobbyqty: formatQuantity(item.valetnonlobbyqty),
                                vipqty: formatQuantity(item.vipqty),
                                carpreferredqty: formatQuantity(item.carpreferredqty),
                                motorbikepreferredqty: formatQuantity(item
                                    .motorbikepreferredqty),
                                evipqty: formatQuantity(item.evipqty),
                                extendchargingqty: formatQuantity(item
                                    .extendchargingqty),
                                totalvehicle: formatQuantity(total)
                            };
                        });

                        const rowsQuantityVehicleSecond = dataSecondPeriodIncomePayment.map((
                            item,
                            index) => {
                            const total = [
                                item.carqty,
                                item.motorbikeqty,
                                item.truckqty,
                                item.taxiqty,
                                item.lostticketqty,
                                item.otherqty,
                                item.valetlobbyqty,
                                item.valetnonlobbyqty,
                                item.vipqty,
                                item.carpreferredqty,
                                item.motorbikepreferredqty,
                                item.evipqty,
                                item.extendchargingqty
                            ].reduce((sum, val) => sum + Number(val), 0);

                            return {
                                no: index + 1,
                                date: item.periode,
                                carqty: formatQuantity(item.carqty),
                                motorbikeqty: formatQuantity(item.motorbikeqty),
                                truckqty: formatQuantity(item.truckqty),
                                taxiqty: formatQuantity(item.taxiqty),
                                lostticketqty: formatQuantity(item.lostticketqty),
                                otherqty: formatQuantity(item.otherqty),
                                valetlobbyqty: formatQuantity(item.valetlobbyqty),
                                valetnonlobbyqty: formatQuantity(item.valetnonlobbyqty),
                                vipqty: formatQuantity(item.vipqty),
                                carpreferredqty: formatQuantity(item.carpreferredqty),
                                motorbikepreferredqty: formatQuantity(item
                                    .motorbikepreferredqty),
                                evipqty: formatQuantity(item.evipqty),
                                extendchargingqty: formatQuantity(item
                                    .extendchargingqty),
                                totalvehicle: formatQuantity(total)
                            };
                        });

                        const rowIncomeVehicleFirst = dataFirstPeriodIncomePayment.map((item,
                            index) => {
                            const total = [
                                item.carincome,
                                item.motorbikeincome,
                                item.truckincome,
                                item.taxiincome,
                                item.lostticket,
                                item.otherincome,
                                item.valetlobbyincome,
                                item.valetnonlobbyincome,
                                item.vipincome,
                                item.carpreferredincome,
                                item.motorbikepreferredincome,
                                item.evipincome,
                                item.extendchargingincome,
                            ].reduce((sum, val) => sum + Number(val || 0),
                                0); // safe against null/undefined

                            return {
                                no: index + 1,
                                date: item.periode,
                                carincome: formatRupiah(item.carincome),
                                motorbikeincome: formatRupiah(item.motorbikeincome),
                                truckincome: formatRupiah(item.truckincome),
                                taxiincome: formatRupiah(item.taxiincome),
                                lostticketincome: formatRupiah(item.lostticket),
                                otherincome: formatRupiah(item.otherincome),
                                valetlobbyincome: formatRupiah(item.valetlobbyincome),
                                valetnonlobbyincome: formatRupiah(item
                                    .valetnonlobbyincome),
                                vipincome: formatRupiah(item.vipincome),
                                carpreferredincome: formatRupiah(item
                                    .carpreferredincome),
                                motorbikepreferredincome: formatRupiah(item
                                    .motorbikepreferredincome),
                                evipincome: formatRupiah(item.evipincome),
                                extendchargingincome: formatRupiah(item
                                    .extendchargingincome),
                                totalvehicleincome: formatRupiah(total)
                            };
                        });

                        const rowIncomeVehicleSecond = dataSecondPeriodIncomePayment.map((item,
                            index) => {
                            const total = [
                                item.carincome,
                                item.motorbikeincome,
                                item.truckincome,
                                item.taxiincome,
                                item.lostticket,
                                item.otherincome,
                                item.valetlobbyincome,
                                item.valetnonlobbyincome,
                                item.vipincome,
                                item.carpreferredincome,
                                item.motorbikepreferredincome,
                                item.evipincome,
                                item.extendchargingincome,
                            ].reduce((sum, val) => sum + Number(val || 0),
                                0); // safe against null/undefined

                            return {
                                no: index + 1,
                                date: item.periode,
                                carincome: formatRupiah(item.carincome),
                                motorbikeincome: formatRupiah(item.motorbikeincome),
                                truckincome: formatRupiah(item.truckincome),
                                taxiincome: formatRupiah(item.taxiincome),
                                lostticketincome: formatRupiah(item.lostticket),
                                otherincome: formatRupiah(item.otherincome),
                                valetlobbyincome: formatRupiah(item.valetlobbyincome),
                                valetnonlobbyincome: formatRupiah(item
                                    .valetnonlobbyincome),
                                vipincome: formatRupiah(item.vipincome),
                                carpreferredincome: formatRupiah(item
                                    .carpreferredincome),
                                motorbikepreferredincome: formatRupiah(item
                                    .motorbikepreferredincome),
                                evipincome: formatRupiah(item.evipincome),
                                extendchargingincome: formatRupiah(item
                                    .extendchargingincome),
                                totalvehicleincome: formatRupiah(total)
                            };
                        });


                        tableIncomePayment.clear().rows.add(rows).draw();
                        tableIncomePaymentSecond.clear().rows.add(rowsIncomePaymentSecond)
                            .draw();
                        tableQuantityVehicleFirst.clear().rows.add(rowsQuantityVehicle).draw();
                        tableQuantityVehicleSecond.clear().rows.add(rowsQuantityVehicleSecond)
                            .draw();
                        tableIncomeVehicleFirst.clear().rows.add(rowIncomeVehicleFirst).draw();
                        tableIncomeVehicleSecond.clear().rows.add(rowIncomeVehicleSecond)
                            .draw();

                        const topPaymentFirst = response.top_payment.first_period;
                        const topPaymentSecond = response.top_payment.second_period;
                        console.log(topPaymentFirst)

                        function formatDate(dateString) {
                            const date = new Date(dateString);
                            const options = {
                                day: 'numeric',
                                month: 'long',
                                year: 'numeric'
                            };
                            return date.toLocaleDateString('en-GB', options);
                        }


                        const DailyRevenueFirst = response.data.first_period.map(
                            item => item.allpayment
                        );
                        console.log('ini daily revenue first', DailyRevenueFirst)
                        const dailyRevenueFirstLabel = response.data.first_period.map(
                            item => formatDate(item.periode));
                        console.log('ini daily revenue first label', dailyRevenueFirstLabel)

                        const DailyRevenueSecond = response.data.second_period.map(
                            item => item.allpayment
                        );

                        const dailyRevenueSecondLabel = response.data.second_period.map(
                            item => formatDate(item.periode));



                        function createOrUpdateDailyRevenueChart(canvasId, periodData,
                            chartTitle, chartInstanceName) {
                            const labels = [];
                            const weekdayData = [];
                            const weekendData = [];

                            // Process data to separate weekday and weekend revenue
                            periodData.forEach(item => {
                                const formattedDate = new Date(item.periode)
                                    .toLocaleDateString('en-GB', {
                                        day: 'numeric',
                                        month: 'long',
                                        year: 'numeric'
                                    });
                                labels.push(formattedDate);

                                const dayOfWeek = new Date(item.periode)
                                    .getDay(); // 0=Sun, 6=Sat
                                if (dayOfWeek === 0 || dayOfWeek === 6) {
                                    weekendData.push(item.allpayment);
                                    weekdayData.push(null);
                                } else {
                                    weekdayData.push(item.allpayment);
                                    weekendData.push(null);
                                }
                            });

                            // Chart.js data configuration
                            const chartDataConfig = {
                                labels: labels,
                                datasets: [{
                                    label: 'Weekday',
                                    data: weekdayData,
                                    backgroundColor: 'green',
                                }, {
                                    label: 'Weekend',
                                    data: weekendData,
                                    backgroundColor: 'rgba(188, 4, 4, 1)',
                                }]
                            };

                            // Chart.js options configuration
                            const chartOptionsConfig = {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        stacked: true,
                                        beginAtZero: true,
                                        ticks: {
                                            callback: value => formatRupiah(value),
                                            color: '#fff',
                                        }
                                    },
                                    x: {
                                        stacked: true,
                                        ticks: {
                                            autoSkip: false,
                                            maxRotation: 90,
                                            minRotation: 0,
                                            color: '#fff',
                                            font: {
                                                size: 10
                                            }
                                        }
                                    }
                                },
                                plugins: {
                                    legend: {
                                        position: 'top',
                                        labels: {
                                            color: '#fff'
                                        }
                                    },
                                    title: {
                                        display: true,
                                        text: chartTitle,
                                        color: '#fff',
                                    }
                                }
                            };

                            // Destroy existing chart instance if it exists
                            if (window[chartInstanceName]) {
                                window[chartInstanceName].destroy();
                            }

                            // Create and render the new chart
                            const ctx = document.getElementById(canvasId).getContext('2d');
                            window[chartInstanceName] = new Chart(ctx, {
                                type: 'bar',
                                data: chartDataConfig,
                                options: chartOptionsConfig
                            });
                        }

                        // --- END OF REUSABLE FUNCTION ---

                        // --- UPDATE YOUR CHARTS USING THE NEW FUNCTION ---
                        // Chart for the First Period
                        createOrUpdateDailyRevenueChart(
                            'dailyRevenueFirstBar',
                            response.data.first_period,
                            'Daily Revenue First Period',
                            'dailyRevenueFirstChart'
                        );

                        // Chart for the Second Period
                        createOrUpdateDailyRevenueChart(
                            'dailyRevenueSecondBar',
                            response.data.second_period,
                            'Daily Revenue Second Period',
                            'dailyRevenueSecondChart'
                        );















                        $('#hasil-test').text(formatRupiah(GrandTotals.income_payment
                            .first_period))
                        $('.top-payment').text(topPaymentFirst.source)
                        $('.top-payment-income').text(formatRupiah(topPaymentFirst.value))
                        $('.total-pass').text(formatQuantity(GrandTotals.quantity_pass
                            .first_period))
                        $('.total-casual').text(formatQuantity(GrandTotals.quantity_casual
                            .first_period))
                        $('.total-revenue').text(formatRupiah(GrandTotals.income_payment
                            .first_period))
                        $('.top-payment-second').text(topPaymentSecond.source)
                        $('.top-payment-income-second').text(formatRupiah(
                            topPaymentSecond.value))
                        $('.total-pass-second').text(formatQuantity(GrandTotals
                            .quantity_pass.second_period))
                        $('.total-casual-second').text(formatQuantity(GrandTotals
                            .quantity_casual.second_period))
                        $('.total-revenue-second').text(formatRupiah(GrandTotals
                            .income_payment.second_period))
                        $('.result').show();
                        $('#tbody-custom').empty();
                        $('#tbody-custom').append(`
        <tr>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white; border-right: 1px solid white;">1</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">Cash</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(firstPeriodTotals.cash)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(secondPeriodTotals.cash)}</td>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white;border-right: 1px solid white;">Mobil</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatQuantity(firstPeriodTotals.carpassqty)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatQuantity(firstPeriodTotals.carqty)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatQuantity(secondPeriodTotals.carpassqty)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatQuantity(secondPeriodTotals.carqty)}</td>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white;border-right: 1px solid white;">Mobil</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(firstPeriodTotals.carincome)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(secondPeriodTotals.carincome)}</td>
        </tr>

        <tr>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white; border-right: 1px solid white;">2</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">Parkee</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(firstPeriodTotals.parkeepayment)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(secondPeriodTotals.parkeepayment)}</td>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white;border-right: 1px solid white;">Motor</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatQuantity(firstPeriodTotals.motorbikepassqty)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatQuantity(firstPeriodTotals.motorbikeqty)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatQuantity(secondPeriodTotals.motorbikepassqty)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatQuantity(secondPeriodTotals.motorbikeqty)}</td>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white;border-right: 1px solid white;">Motor</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(firstPeriodTotals.motorbikeincome)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(secondPeriodTotals.motorbikeincome)}</td>
        </tr>

        <tr>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white; border-right: 1px solid white;">3</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">Emoney</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(firstPeriodTotals.emoney)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(secondPeriodTotals.emoney)}</td>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white;border-right: 1px solid white;">Truck/Loading</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatQuantity(firstPeriodTotals.truckpassqty)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatQuantity(firstPeriodTotals.truckqty)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatQuantity(secondPeriodTotals.truckpassqty)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatQuantity(secondPeriodTotals.truckqty)}</td>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white;border-right: 1px solid white;">Truck/Loading</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(firstPeriodTotals.truckincome)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(secondPeriodTotals.truckincome)}</td>
        </tr>

        <tr>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white; border-right: 1px solid white;">4</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">Flazz</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(firstPeriodTotals.flazz)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(secondPeriodTotals.flazz)}</td>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white;border-right: 1px solid white;">Taxi</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatQuantity(firstPeriodTotals.taxipassqty)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatQuantity(firstPeriodTotals.taxiqty)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatQuantity(secondPeriodTotals.taxipassqty)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatQuantity(secondPeriodTotals.taxiqty)}</td>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white;border-right: 1px solid white;">Taxi</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(firstPeriodTotals.taxiincome)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(secondPeriodTotals.taxiincome)}</td>
        </tr>

        <tr>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white; border-right: 1px solid white;">5</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">Brizzi</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(firstPeriodTotals.brizzi)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(secondPeriodTotals.brizzi)}</td>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white;border-right: 1px solid white;">Valet Lobby</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">-</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatQuantity(firstPeriodTotals.valetlobbyqty)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">-</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatQuantity(secondPeriodTotals.valetlobbyqty)}</td>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white;border-right: 1px solid white;">Valet Lobby</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(firstPeriodTotals.valetlobbyincome)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(secondPeriodTotals.valetlobbyincome)}</td>
        </tr>

         <tr>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white; border-right: 1px solid white;">6</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">Tapcash</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(firstPeriodTotals.tapcash)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(secondPeriodTotals.tapcash)}</td>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white;border-right: 1px solid white;">Valet Non-Lobby</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">-</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatQuantity(firstPeriodTotals.valetnonlobbyqty)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">-</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatQuantity(secondPeriodTotals.valetnonlobbyqty)}</td>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white;border-right: 1px solid white;">Valet Non-Lobby</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(firstPeriodTotals.valetnonlobbyincome)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(secondPeriodTotals.valetnonlobbyincome)}</td>
        </tr>

        <tr>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white; border-right: 1px solid white;">7</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">Qris</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(firstPeriodTotals.qrisepayment)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(secondPeriodTotals.qrisepayment)}</td>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white;border-right: 1px solid white;">VIP</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">-</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatQuantity(firstPeriodTotals.vipqty)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">-</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatQuantity(secondPeriodTotals.vipqty)}</td>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white;border-right: 1px solid white;">VIP</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(firstPeriodTotals.vipincome)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(secondPeriodTotals.vipincome)}</td>
        </tr>

        <tr>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white; border-right: 1px solid white;">8</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">DKI Jakcard</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(firstPeriodTotals.dkijackpayment)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(secondPeriodTotals.dkijackpayment)}</td>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white;border-right: 1px solid white;">Preferred Car</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">-</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatQuantity(firstPeriodTotals.carpreferredqty)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">-</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatQuantity(secondPeriodTotals.carpreferredqty)}</td>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white;border-right: 1px solid white;">Preferred Car</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(firstPeriodTotals.carpreferredincome)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(secondPeriodTotals.carpreferredincome)}</td>
        </tr>

        <tr>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white; border-right: 1px solid white;">9</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">-</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">-</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">-</td>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white;border-right: 1px solid white;">Preferred Motorbike</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">-</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatQuantity(firstPeriodTotals.motorbikepreferredqty)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">-</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatQuantity(secondPeriodTotals.motorbikepreferredqty)}</td>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white;border-right: 1px solid white;">Preferred Motorbike</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(firstPeriodTotals.motorbikepreferredincome)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(secondPeriodTotals.motorbikepreferredincome)}</td>
        </tr>

        <tr>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white; border-right: 1px solid white;">10</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">-</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">-</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">-</td>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white;border-right: 1px solid white;">E-VIP</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">-</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatQuantity(firstPeriodTotals.evipqty)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">-</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatQuantity(secondPeriodTotals.evipqty)}</td>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white;border-right: 1px solid white;">E-VIP</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(firstPeriodTotals.evipincome)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(secondPeriodTotals.evipincome)}</td>
        </tr>

        <tr>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white; border-right: 1px solid white;">11</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">-</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">-</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">-</td>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white;border-right: 1px solid white;">Extend Charging</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">-</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatQuantity(firstPeriodTotals.extendchargingqty)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">-</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatQuantity(secondPeriodTotals.extendchargingqty)}</td>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white;border-right: 1px solid white;">Extend Charging</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(firstPeriodTotals.extendchargingincome)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(secondPeriodTotals.extendchargingincome)}</td>
        </tr>

        <tr>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white; border-right: 1px solid white;">12</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">-</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">-</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">-</td>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white;border-right: 1px solid white;">Lost Ticket</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">-</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatQuantity(firstPeriodTotals.lostticketqty)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">-</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatQuantity(secondPeriodTotals.lostticketqty)}</td>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white;border-right: 1px solid white;">Lost Ticket</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(firstPeriodTotals.lostticket)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(secondPeriodTotals.lostticket)}</td>
        </tr>

        <tr>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white; border-right: 1px solid white;">13</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">Grand Total</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(GrandTotals.income_payment.first_period)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(GrandTotals.income_payment.second_period)}</td>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white;border-right: 1px solid white;">Grand Total</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatQuantity(GrandTotals.quantity_pass.first_period)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatQuantity(GrandTotals.quantity_casual.first_period)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatQuantity(GrandTotals.quantity_pass.second_period)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatQuantity(GrandTotals.quantity_casual.second_period)}</td>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white;border-right: 1px solid white;">Grand Total</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(GrandTotals.income_payment.first_period)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(GrandTotals.income_payment.second_period)}</td>
        </tr>

        <tr>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white; border-right: 1px solid white;">-</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">-</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">-</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">-</td>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white;border-right: 1px solid white;">-</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">-</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">-</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">-</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">-</td>
            <td class="text-center" style="border-left: 1px solid white; border-top: 1px solid white;border-right: 1px solid white;">Sticker Income</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(firstPeriodTotals.stickerincome)}</td>
            <td class="text-center" style="border-top: 1px solid white; border-right: 1px solid white;">${formatRupiah(firstPeriodTotals.stickerincome)}</td>
        </tr>
    `);

                        if ($.fn.DataTable.isDataTable('#table-custom')) {
                            $('#table-custom').DataTable().clear().destroy();
                        }


                        $('#table-custom').DataTable({
                            searching: false,
                            paging: false,
                            ordering: false,
                            lengthChange: false,
                            info: false,
                            autoWidth: false,
                            layout: {
                                topStart: {
                                    buttons: [{
                                            extend: 'copyHtml5',
                                            titleAttr: 'Copy to Clipboard',
                                            exportOptions: {
                                                columns: ':visible'
                                            },
                                            title: 'Custom Search ',
                                        },
                                        {
                                            extend: 'excelHtml5',
                                            titleAttr: 'Export to Excel',
                                            exportOptions: {
                                                columns: ':visible'
                                            },
                                            title: 'Custom Search ',
                                        },


                                        {
                                            extend: 'print',
                                            titleAttr: 'Print',
                                            exportOptions: {
                                                columns: ':visible'
                                            },
                                            title: 'Custom Search ',
                                        },
                                        {
                                            extend: 'pdfHtml5',
                                            titleAttr: 'Export to PDF',
                                            exportOptions: {
                                                columns: ':visible'
                                            },
                                            title: 'Custom Search ',
                                            customize: function(doc) {
                                                doc.pageMargins = [20, 30, 20,
                                                    30
                                                ]; // [left, top, right, bottom]
                                                doc.defaultStyle.fontSize =
                                                    8; // Adjust font size if too large
                                                doc.styles.tableHeader
                                                    .alignment = 'center';
                                                doc.styles.tableHeader
                                                    .fillColor =
                                                    '#007BFF'; // Biru
                                                doc.styles.tableHeader.color =
                                                    '#FFFFFF'; // Putih
                                                doc.styles.tableHeader
                                                    .lineWidth =
                                                    1; // Tambahkan stroke
                                                doc.styles.tableHeader
                                                    .lineColor =
                                                    '#000000'; // Warna garis (hitam)
                                            },
                                        },
                                    ]
                                }
                            },
                        });


                        const comparePaymentLabel = [{
                                label: 'Cash',
                                firstPeriod: firstPeriodTotals.cash,
                                secondPeriod: secondPeriodTotals.cash
                            },
                            {
                                label: 'Parkee',
                                firstPeriod: firstPeriodTotals.parkeepayment,
                                secondPeriod: secondPeriodTotals.parkeepayment
                            },
                            {
                                label: 'Emoney',
                                firstPeriod: firstPeriodTotals.emoney,
                                secondPeriod: secondPeriodTotals.emoney
                            },
                            {
                                label: 'Flazz',
                                firstPeriod: firstPeriodTotals.flazz,
                                secondPeriod: secondPeriodTotals.flazz
                            },
                            {
                                label: 'Brizzi',
                                firstPeriod: firstPeriodTotals.brizzi,
                                secondPeriod: secondPeriodTotals.brizzi
                            },
                            {
                                label: 'Tapcash',
                                firstPeriod: firstPeriodTotals.tapcash,
                                secondPeriod: secondPeriodTotals.tapcash
                            },
                            {
                                label: 'Qris',
                                firstPeriod: firstPeriodTotals.qrisepayment,
                                secondPeriod: secondPeriodTotals.qrisepayment
                            },
                            {
                                label: 'DKI Jakcard',
                                firstPeriod: firstPeriodTotals.dkijackpayment,
                                secondPeriod: secondPeriodTotals.dkijackpayment
                            },
                        ]

                        const paymentComparisonResult = comparePaymentLabel.map(item => {
                            const {
                                label,
                                firstPeriod,
                                secondPeriod
                            } = item;

                            // Menghitung persentase perubahan
                            const change = firstPeriod === 0 ?
                                0 :
                                ((secondPeriod - firstPeriod) / firstPeriod) * 100;

                            // Menentukan arah perubahan
                            const arrow = secondPeriod > firstPeriod ? '↓' :
                                secondPeriod < firstPeriod ? '↑' : '→';

                            // Menghitung teks persentase dan menambahkan simbol perubahan
                            const percentageText =
                                `${arrow} ${Math.abs(change).toFixed(1)}%`;

                            // Menentukan kelas warna (merah untuk penurunan, hijau untuk kenaikan, abu-abu untuk tidak ada perubahan)
                            const colorClass = secondPeriod > firstPeriod ?
                                'text-danger' : secondPeriod < firstPeriod ?
                                'text-success' : 'text-muted';


                            // Membuat HTML string untuk setiap perbandingan
                            return `
        <div class="col-md-2">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted mb-1">${label}</h6>
                            <h4 class="fw-bold mb-0">${formatRupiah(firstPeriod)}</h4>
                            <small class="text-muted tgl_row1">Total second period :</small>
                            <p class="text-muted tgl_row1">${formatRupiah(secondPeriod)}</p>
                        </div>
                        <div class="${colorClass}">${percentageText}</div>
                    </div>
                </div>
            </div>
        </div>
    `;
                        });

                        console.log(paymentComparisonResult);

                        const compareVehicleLabel = [{
                            label: 'Car',
                            firstPeriod: firstPeriodTotals.carqty,
                            secondPeriod: secondPeriodTotals.carqty,
                        }, {
                            label: 'Motorbike',
                            firstPeriod: firstPeriodTotals.motorbikeqty,
                            secondPeriod: secondPeriodTotals.motorbikeqty,
                        }, {
                            label: 'Truck',
                            firstPeriod: firstPeriodTotals.truckqty,
                            secondPeriod: secondPeriodTotals.truckqty,
                        }, {
                            label: 'Taxi',
                            firstPeriod: firstPeriodTotals.taxiqty,
                            secondPeriod: secondPeriodTotals.taxiqty,
                        }, {
                            label: 'VIP',
                            firstPeriod: vipsum1,
                            secondPeriod: vipsum2,
                        }, {
                            label: 'Preferred Car',
                            firstPeriod: firstPeriodTotals.carpreferredqty,
                            secondPeriod: secondPeriodTotals.carpreferredqty,
                        }, {
                            label: 'Preferred Motorbike',
                            firstPeriod: firstPeriodTotals.motorbikepreferredqty,
                            secondPeriod: secondPeriodTotals.motorbikepreferredqty,
                        }, {
                            label: 'E-VIP',
                            firstPeriod: firstPeriodTotals.evipqty,
                            secondPeriod: secondPeriodTotals.evipqty,
                        }, {
                            label: 'Extend Charging',
                            firstPeriod: firstPeriodTotals.extendchargingqty,
                            secondPeriod: secondPeriodTotals.extendchargingqty,
                        }, {
                            label: 'Lost Ticket',
                            firstPeriod: firstPeriodTotals.lostticketqty,
                            secondPeriod: secondPeriodTotals.lostticketqty,
                        }]

                        const vehicleComparisonResult = compareVehicleLabel.map(item => {
                            const {
                                label,
                                firstPeriod,
                                secondPeriod
                            } = item;

                            // Menghitung persentase perubahan
                            const change = firstPeriod === 0 ?
                                0 :
                                ((secondPeriod - firstPeriod) / firstPeriod) * 100;

                            // Menentukan arah perubahan
                            const arrow = secondPeriod > firstPeriod ? '↓' :
                                secondPeriod < firstPeriod ? '↑' : '→';

                            // Menghitung teks persentase dan menambahkan simbol perubahan
                            const percentageText =
                                `${arrow} ${Math.abs(change).toFixed(1)}%`;

                            // Menentukan kelas warna (merah untuk penurunan, hijau untuk kenaikan, abu-abu untuk tidak ada perubahan)
                            const colorClass = secondPeriod > firstPeriod ?
                                'text-danger' : secondPeriod < firstPeriod ?
                                'text-success' : 'text-muted';


                            // Membuat HTML string untuk setiap perbandingan
                            return `
        <div class="col-md-2">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted mb-1">${label}</h6>
                            <h4 class="fw-bold mb-0">${formatQuantity(firstPeriod)}</h4>
                            <small class="text-muted tgl_row1">Total second period :</small>
                            <p class="text-muted tgl_row1">${formatQuantity(secondPeriod)}</p>
                        </div>
                        <div class="${colorClass}">${percentageText}</div>
                    </div>
                </div>
            </div>
        </div>
    `;
                        });


                        const labelIncomeVehicle = [{
                                label: 'Car',
                                firstPeriod: firstPeriodTotals.carincome,
                                secondPeriod: secondPeriodTotals.carincome,
                            },
                            {
                                label: 'Motorbike',
                                firstPeriod: firstPeriodTotals.motorbikeincome,
                                secondPeriod: secondPeriodTotals.motorbikeincome,
                            },
                            {
                                label: 'Truck',
                                firstPeriod: firstPeriodTotals.truckincome,
                                secondPeriod: secondPeriodTotals.truckincome,
                            },
                            {
                                label: 'Taxi',
                                firstPeriod: firstPeriodTotals.taxiincome,
                                secondPeriod: secondPeriodTotals.taxiincome,
                            },
                            {
                                label: 'VIP',
                                firstPeriod: vipincome1,
                                secondPeriod: vipincome2,
                            },
                            {
                                label: 'Preferred Car',
                                firstPeriod: firstPeriodTotals.carpreferredincome,
                                secondPeriod: secondPeriodTotals.carpreferredincome,
                            },
                            {
                                label: 'Preferred Motorbike',
                                firstPeriod: firstPeriodTotals.motorbikepreferredincome,
                                secondPeriod: secondPeriodTotals.motorbikepreferredincome,
                            },
                            {
                                label: 'E-VIP',
                                firstPeriod: firstPeriodTotals.evipincome,
                                secondPeriod: secondPeriodTotals.evipincome,
                            },
                            {
                                label: 'Extend Charging',
                                firstPeriod: firstPeriodTotals.extendchargingincome,
                                secondPeriod: secondPeriodTotals.extendchargingincome,
                            },
                            {
                                label: 'Lost Ticket',
                                firstPeriod: firstPeriodTotals.lostticket,
                                secondPeriod: secondPeriodTotals.lostticket,
                            }
                        ]

                        const incomeVehicleComparisonResult = labelIncomeVehicle.map(item => {
                            const {
                                label,
                                firstPeriod,
                                secondPeriod
                            } = item;

                            // Menghitung persentase perubahan
                            const change = firstPeriod === 0 ?
                                0 :
                                ((secondPeriod - firstPeriod) / firstPeriod) * 100;

                            // Menentukan arah perubahan
                            const arrow = secondPeriod > firstPeriod ? '↓' :
                                secondPeriod < firstPeriod ? '↑' : '→';

                            // Menghitung teks persentase dan menambahkan simbol perubahan
                            const percentageText =
                                `${arrow} ${Math.abs(change).toFixed(1)}%`;

                            // Menentukan kelas warna (merah untuk penurunan, hijau untuk kenaikan, abu-abu untuk tidak ada perubahan)
                            const colorClass = secondPeriod > firstPeriod ?
                                'text-danger' : secondPeriod < firstPeriod ?
                                'text-success' : 'text-muted';


                            // Membuat HTML string untuk setiap perbandingan
                            return `
        <div class="col-md-2">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted mb-1">${label}</h6>
                            <h4 class="fw-bold mb-0">${formatRupiah(firstPeriod)}</h4>
                            <small class="text-muted tgl_row1">Total second period :</small>
                            <p class="text-muted tgl_row1">${formatRupiah(secondPeriod)}</p>
                        </div>
                        <div class="${colorClass}">${percentageText}</div>
                    </div>
                </div>
            </div>
        </div>
    `;
                        });

                        console.log(vehicleComparisonResult);
                        document.getElementById('compare-result').innerHTML =
                            paymentComparisonResult.join('');
                        document.getElementById('compare-quantity').innerHTML =
                            vehicleComparisonResult.join('');
                        document.getElementById('compare-income-vehicle').innerHTML =
                            incomeVehicleComparisonResult.join('');

                    },
                    error: function(xhr) {
                        console.error('Error:', xhr.responseJSON);
                        alert('Something went wrong: ' + xhr.responseJSON.error);
                    }
                });
            });
        });
    </script>
@endsection
