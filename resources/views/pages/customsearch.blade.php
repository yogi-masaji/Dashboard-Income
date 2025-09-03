@extends('layout.nav')
@section('content')
    @php
        $lokasiName = session('selected_location_name', 'Lokasi Default');
        $ipLokasi = session('selected_location_ip_lokasi', 'IP Tidak Diketahui');
        $lokasiId = session('selected_location_id', 0);
        $lokasiGrup = session('selected_location_id_grup', 'Group Tidak Diketahui');
        $kodeLokasi = session('selected_location_kode_lokasi', 'Kode Tidak Diketahui');
        $chiselVersion = session('selected_location_chisel_Version', 'Chisel Version Tidak Diketahui');
        $systemCode = session('selected_location_system', 'System Code Tidak Diketahui');
        $navbarTitle = $lokasiName;
    @endphp
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.1/dist/index.css" />
    <style>
        .content-custom {
            padding: 10px !important;
            background-color: #ffffff !important;
            border: #d9d9d9 1px solid !important;
            height: auto;
            border-radius: 10px !important;
            color: #000 !important;
        }

        .mode-gelap .card {
            background-color: #ffffff !important;
            border-color: #424242 !important;
            color: #ffffff;
        }


        .content-picker {
            padding: 15px !important;
            background-color: #ffffff00 !important;
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
            #000-space: normal;
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

        th.text-start.dt-orderable-none {
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
    <!-- CSS tambahan -->
    <style>
        .easepick-wrapper {
            z-index: 1060;
        }
    </style>

    <div class="search-wrapper content-custom">
        <h5>Custom Search</h5>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="datepicker1" class="form-label text-dark" style="color:#000;">First Period</label>
                <input id="datepicker1" class="form-control" placeholder="Select date range..." />
            </div>
            <div class="col-md-6">
                <label for="datepicker2" class="form-label text-dark" style="color:#000;">Second Period</label>
                <input id="datepicker2" class="form-control" placeholder="Select date range..." />
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
                                            <h4 style="color: #000;" class="fw-bold mb-0 total-revenue"></h4>
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
                                            <h4 style="color: #000;" class="fw-bold mb-0 total-pass"></h4>
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
                                            <h4 style="color: #000;" class="fw-bold mb-0 total-casual">Rp 388.532.000</h4>
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
                                            <h4 style="color: #000;" class="fw-bold mb-0 top-payment"></h4>
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
                                            <h4 style="color: #000;" class="fw-bold mb-0 total-revenue-second"></h4>
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
                                            <h4 style="color: #000;" class="fw-bold mb-0 total-pass-second"></h4>
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
                                            <h4 style="color: #000;" class="fw-bold mb-0 total-casual-second">Rp
                                                388.532.000</h4>
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
                                            <h4 style="color: #000;" class="fw-bold mb-0 top-payment-second"></h4>
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
                                            <h4 style="color: #000;" class="fw-bold mb-0">Rp 388.532.000</h4>
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
                                            <h4 style="color: #000;" class="fw-bold mb-0">Rp 388.532.000</h4>
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
                                            <h4 style="color: #000;" class="fw-bold mb-0">Rp 388.532.000</h4>
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
                            <th scope="col" rowspan="3" class="text-start"
                                style="width:40px;border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000;">
                                No
                            </th>
                            <th scope="col" colspan="3" rowspan="1" class="text-center"
                                style="border-top: 1px solid #000; border-right: 1px solid #000;">INCOME BY PAYMENT</th>
                            <th scope="col" colspan="5" rowspan="1" class="text-center"
                                style="border-top: 1px solid #000; border-right: 1px solid #000;">QUANTITY BY VEHICLE
                            </th>
                            <th scope="col" colspan="3" rowspan="1" class="text-center"
                                style="border-top: 1px solid #000; border-right: 1px solid #000;">INCOME BY VEHICLE</th>
                        </tr>

                        <tr>
                            <th scope="col" rowspan="2" style="border-right: 1px solid #000;">Payment</th>
                            <th scope="col" rowspan="2" style="border-right: 1px solid #000;"
                                class="tgl_row1 text-start">
                            </th>
                            <th scope="col" rowspan="2" class="tgl_row2 text-start"
                                style="border-right: 1px solid #000;">
                            </th>
                            <th scope="col" rowspan="2"
                                style="border-left: 1px solid #000; border-right: 1px solid #000;">
                                Vehicle</th>
                            <th scope="col" colspan="2" style="border-right: 1px solid #000;"
                                class="tgl_row1 text-start">
                            </th>
                            <th scope="col" colspan="2" class="tgl_row2 text-start"
                                style="border-right: 1px solid #000;">
                            </th>
                            <th scope="col" rowspan="2"
                                style="border-right: 1px solid #000; border-left: 1px solid #000;">
                                Vehicle</th>
                            <th scope="col" rowspan="2" style="border-right: 1px solid #000;"
                                class="tgl_row1 text-start">
                            </th>
                            <th scope="col" rowspan="2" class="tgl_row2 text-start"
                                style="border-right: 1px solid #000;">
                            </th>

                        </tr>
                        <tr>
                            <th scope="col" class="text-start" style="border-right: 1px solid #000;">Pass</th>
                            <th scope="col" class="text-start" style="border-right: 1px solid #000;">Casual</th>
                            <th scope="col" class="text-start" style="border-right: 1px solid #000;">Pass</th>
                            <th scope="col" class="text-start" style="border-right: 1px solid #000;">Casual</th>
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
                                    <th scope="col" class="text-start" style=" width: 20px;">No</th>
                                    <th scope="col" class="text-start">Date</th>
                                    <th scope="col" class="text-start">Cash</th>
                                    <th scope="col" class="text-start">Parkee</th>
                                    <th scope="col" class="text-start">Emoney</th>
                                    <th scope="col" class="text-start">Flazz</th>
                                    <th scope="col" class="text-start">Brizzi</th>
                                    <th scope="col" class="text-start">Tapcash</th>
                                    <th scope="col" class="text-start">Qris</th>
                                    <th scope="col" class="text-start">Dki Jakcard</th>
                                    <th scope="col" class="text-start">Total</th>
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
                                    <th scope="col" class="text-start" style=" width: 20px;">No</th>
                                    <th scope="col" class="text-start">Date</th>
                                    <th scope="col" class="text-start">Cash</th>
                                    <th scope="col" class="text-start">Parkee</th>
                                    <th scope="col" class="text-start">Emoney</th>
                                    <th scope="col" class="text-start">Flazz</th>
                                    <th scope="col" class="text-start">Brizzi</th>
                                    <th scope="col" class="text-start">Tapcash</th>
                                    <th scope="col" class="text-start">Qris</th>
                                    <th scope="col" class="text-start">Dki Jakcard</th>
                                    <th scope="col" class="text-start">Total</th>
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
                                    <th scope="col" class="text-start" style=" width: 20px;">No</th>
                                    <th scope="col" class="text-start">Date</th>
                                    <th scope="col" class="text-start">Mobil</th>
                                    <th scope="col" class="text-start">Motor</th>
                                    <th scope="col" class="text-start">Loading</th>
                                    <th scope="col" class="text-start">Taxi</th>
                                    <th scope="col" class="text-start">Lost Ticket</th>
                                    <th scope="col" class="text-start">Other</th>
                                    {{-- Conditional Headers based on Location Code --}}
                                    @if ($kodeLokasi == 'GI2')
                                        <th scope="col" class="text-start">Valet Lobby</th>
                                        <th scope="col" class="text-start">Valet Non-Lobby</th>
                                        <th scope="col" class="text-start">VIP</th>
                                        <th scope="col" class="text-start">Preferred Car</th>
                                        <th scope="col" class="text-start">Preferred motorbike</th>
                                        <th scope="col" class="text-start">E-Vip</th>
                                        <th scope="col" class="text-start">Extend Charging</th>
                                    @elseif($kodeLokasi == 'LW')
                                        <th scope="col" class="text-start">Helm</th>
                                        <th scope="col" class="text-start">Valet</th>
                                    @elseif($kodeLokasi == 'JPRO')
                                        <th scope="col" class="text-start">Valet</th>
                                    @endif
                                    <th scope="col" class="text-start">Total</th>
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
                                    <th scope="col" class="text-start" style=" width: 20px;">No</th>
                                    <th scope="col" class="text-start">Date</th>
                                    <th scope="col" class="text-start">Mobil</th>
                                    <th scope="col" class="text-start">Motor</th>
                                    <th scope="col" class="text-start">Loading</th>
                                    <th scope="col" class="text-start">Taxi</th>
                                    <th scope="col" class="text-start">Lost Ticket</th>
                                    <th scope="col" class="text-start">Other</th>
                                    {{-- Conditional Headers based on Location Code --}}
                                    @if ($kodeLokasi == 'GI2')
                                        <th scope="col" class="text-start">Valet Lobby</th>
                                        <th scope="col" class="text-start">Valet Non-Lobby</th>
                                        <th scope="col" class="text-start">VIP</th>
                                        <th scope="col" class="text-start">Preferred Car</th>
                                        <th scope="col" class="text-start">Preferred motorbike</th>
                                        <th scope="col" class="text-start">E-Vip</th>
                                        <th scope="col" class="text-start">Extend Charging</th>
                                    @elseif($kodeLokasi == 'LW')
                                        <th scope="col" class="text-start">Helm</th>
                                        <th scope="col" class="text-start">Valet</th>
                                    @elseif($kodeLokasi == 'JPRO')
                                        <th scope="col" class="text-start">Valet</th>
                                    @endif
                                    <th scope="col" class="text-start">Total</th>
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
                                    <th scope="col" class="text-start" style=" width: 20px;">No</th>
                                    <th scope="col" class="text-start">Date</th>
                                    <th scope="col" class="text-start">Mobil</th>
                                    <th scope="col" class="text-start">Motor</th>
                                    <th scope="col" class="text-start">Loading</th>
                                    <th scope="col" class="text-start">Taxi</th>
                                    <th scope="col" class="text-start">Lost Ticket</th>
                                    <th scope="col" class="text-start">Other</th>
                                    {{-- Conditional Headers based on Location Code --}}
                                    @if ($kodeLokasi == 'GI2')
                                        <th scope="col" class="text-start">Valet Lobby</th>
                                        <th scope="col" class="text-start">Valet Non-Lobby</th>
                                        <th scope="col" class="text-start">VIP</th>
                                        <th scope="col" class="text-start">Preferred Car</th>
                                        <th scope="col" class="text-start">Preferred motorbike</th>
                                        <th scope="col" class="text-start">E-Vip</th>
                                        <th scope="col" class="text-start">Extend Charging</th>
                                    @elseif($kodeLokasi == 'LW')
                                        <th scope="col" class="text-start">Helm</th>
                                        <th scope="col" class="text-start">Valet</th>
                                    @elseif($kodeLokasi == 'JPRO')
                                        <th scope="col" class="text-start">Valet</th>
                                    @endif
                                    <th scope="col" class="text-start">Total</th>
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
                                    <th scope="col" class="text-start" style=" width: 20px;">No</th>
                                    <th scope="col" class="text-start">Date</th>
                                    <th scope="col" class="text-start">Mobil</th>
                                    <th scope="col" class="text-start">Motor</th>
                                    <th scope="col" class="text-start">Loading</th>
                                    <th scope="col" class="text-start">Taxi</th>
                                    <th scope="col" class="text-start">Lost Ticket</th>
                                    <th scope="col" class="text-start">Other</th>
                                    {{-- Conditional Headers based on Location Code --}}
                                    @if ($kodeLokasi == 'GI2')
                                        <th scope="col" class="text-start">Valet Lobby</th>
                                        <th scope="col" class="text-start">Valet Non-Lobby</th>
                                        <th scope="col" class="text-start">VIP</th>
                                        <th scope="col" class="text-start">Preferred Car</th>
                                        <th scope="col" class="text-start">Preferred motorbike</th>
                                        <th scope="col" class="text-start">E-Vip</th>
                                        <th scope="col" class="text-start">Extend Charging</th>
                                    @elseif($kodeLokasi == 'LW')
                                        <th scope="col" class="text-start">Helm</th>
                                        <th scope="col" class="text-start">Valet</th>
                                    @elseif($kodeLokasi == 'JPRO')
                                        <th scope="col" class="text-start">Valet</th>
                                    @endif
                                    <th scope="col" class="text-start">Total</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.1/dist/index.umd.min.js"></script>

    <script>
        $(document).ready(function() {
            // Pass location code from Blade to JavaScript
            const kodeLokasi = '{{ $kodeLokasi }}';

            // Inisialisasi easepick untuk rentang tanggal
            const picker1 = new easepick.create({
                element: document.getElementById('datepicker1'),
                css: [
                    'https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.1/dist/index.css',
                ],
                plugins: ['RangePlugin'],
                RangePlugin: {
                    delimiter: ' to ',
                },
                format: 'DD-MM-YYYY'
            });

            const picker2 = new easepick.create({
                element: document.getElementById('datepicker2'),
                css: [
                    'https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.1/dist/index.css',
                ],
                plugins: ['RangePlugin'],
                RangePlugin: {
                    delimiter: ' to ',
                },
                format: 'DD-MM-YYYY'
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
                pageLength: 10,
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
                pageLength: 10,
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

            // --- Dynamic Column Definitions ---
            // Base columns that are always present
            const baseQuantityColumns = [{
                    data: 'no'
                }, {
                    data: 'date'
                }, {
                    data: 'carqty'
                }, {
                    data: 'motorbikeqty'
                },
                {
                    data: 'truckqty'
                }, {
                    data: 'taxiqty'
                }, {
                    data: 'lostticketqty'
                }, {
                    data: 'otherqty'
                }
            ];
            const baseIncomeColumns = [{
                    data: 'no'
                }, {
                    data: 'date'
                }, {
                    data: 'carincome'
                }, {
                    data: 'motorbikeincome'
                },
                {
                    data: 'truckincome'
                }, {
                    data: 'taxiincome'
                }, {
                    data: 'lostticketincome'
                }, {
                    data: 'otherincome'
                }
            ];

            // Create copies to modify
            let quantityColumns = [...baseQuantityColumns];
            let incomeColumns = [...baseIncomeColumns];

            // Add location-specific columns
            if (kodeLokasi === 'GI2') {
                quantityColumns.push({
                    data: 'valetlobbyqty'
                }, {
                    data: 'valetnonlobbyqty'
                }, {
                    data: 'vipqty'
                }, {
                    data: 'carpreferredqty'
                }, {
                    data: 'motorbikepreferredqty'
                }, {
                    data: 'evipqty'
                }, {
                    data: 'extendchargingqty'
                });
                incomeColumns.push({
                    data: 'valetlobbyincome'
                }, {
                    data: 'valetnonlobbyincome'
                }, {
                    data: 'vipincome'
                }, {
                    data: 'carpreferredincome'
                }, {
                    data: 'motorbikepreferredincome'
                }, {
                    data: 'evipincome'
                }, {
                    data: 'extendchargingincome'
                });
            } else if (kodeLokasi === 'LW') {
                quantityColumns.push({
                    data: 'helmqty'
                }, {
                    data: 'valetvipqty'
                });
                incomeColumns.push({
                    data: 'helmincome'
                }, {
                    data: 'valetvipincome'
                });
            } else if (kodeLokasi === 'JPRO') {
                quantityColumns.push({
                    data: 'valetvipqty'
                });
                incomeColumns.push({
                    data: 'valetvipincome'
                });
            }

            // Add the final 'total' column
            quantityColumns.push({
                data: 'totalvehicle'
            });
            incomeColumns.push({
                data: 'totalvehicleincome'
            });
            // --- End of Dynamic Column Definitions ---


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
                pageLength: 10,
                lengthChange: false,
                info: false,
                data: [],
                columns: quantityColumns, // Use dynamic columns
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
                pageLength: 10,
                lengthChange: false,
                info: false,
                data: [],
                columns: quantityColumns, // Use dynamic columns
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
                pageLength: 10,
                lengthChange: false,
                info: false,
                data: [],
                columns: incomeColumns, // Use dynamic columns
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
                pageLength: 10,
                lengthChange: false,
                info: false,
                data: [],
                columns: incomeColumns, // Use dynamic columns
            });

            // Handle the search button click
            $('#cari').on('click', function(e) {
                e.preventDefault();

                // Get values from the date pickers
                const startDate1 = picker1.getStartDate() ? picker1.getStartDate().format('DD-MM-YYYY') :
                    null;
                const endDate1 = picker1.getEndDate() ? picker1.getEndDate().format('DD-MM-YYYY') : null;
                const startDate2 = picker2.getStartDate() ? picker2.getStartDate().format('DD-MM-YYYY') :
                    null;
                const endDate2 = picker2.getEndDate() ? picker2.getEndDate().format('DD-MM-YYYY') : null;

                // Validate the form before submitting
                if (!startDate1 || !endDate1 || !startDate2 || !endDate2) {
                    $('#alertMessage').text('Please select a valid date range for both periods.').show();
                    return;
                } else {
                    $('#alertMessage').hide();
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

                        // =================== START: DYNAMIC VEHICLE DATA LOGIC ===================
                        const isGI2 = kodeLokasi === 'GI2';

                        // Define labels based on location
                        const baseVehicleLabels = ['Car', 'Motorbike', 'Truck', 'Taxi'];
                        const gi2VehicleLabels = ['VIP', 'Preferred Car', 'Preferred Motorbike',
                            'E-VIP', 'Extend Charging'
                        ];
                        const finalVehicleLabels = isGI2 ? [...baseVehicleLabels, ...
                            gi2VehicleLabels, 'Lost Ticket'
                        ] : [...baseVehicleLabels, 'Lost Ticket'];

                        // Define data arrays based on location
                        const getVehicleData = (periodTotals, type) => {
                            const isQuantity = type === 'quantity';
                            const keySuffix = isQuantity ? 'qty' : 'income';

                            let baseData = [
                                periodTotals[`car${keySuffix}`],
                                periodTotals[`motorbike${keySuffix}`],
                                periodTotals[`truck${keySuffix}`],
                                periodTotals[`taxi${keySuffix}`]
                            ];

                            if (isGI2) {
                                const vipValue = isQuantity ? vipsum1 : vipincome1;
                                if (periodTotals === secondPeriodTotals) {
                                    const vipValue = isQuantity ? vipsum2 : vipincome2;
                                }
                                baseData.push(
                                    vipValue,
                                    periodTotals[`carpreferred${keySuffix}`],
                                    periodTotals[`motorbikepreferred${keySuffix}`],
                                    periodTotals[`evip${keySuffix}`],
                                    periodTotals[`extendcharging${keySuffix}`]
                                );
                            }

                            baseData.push(isQuantity ? periodTotals.lostticketqty :
                                periodTotals.lostticket);
                            return baseData;
                        };

                        const quantityData1 = getVehicleData(firstPeriodTotals, 'quantity');
                        const quantityData2 = getVehicleData(secondPeriodTotals, 'quantity');
                        const incomeData1 = getVehicleData(firstPeriodTotals, 'income');
                        const incomeData2 = getVehicleData(secondPeriodTotals, 'income');

                        // Define colors based on location
                        const baseColors = [
                            'rgba(231, 76, 60, 1)', // Car
                            'rgba(52, 152, 219, 1)', // Motorbike
                            'rgba(241, 196, 15, 1)', // Truck
                            'rgba(46, 204, 113, 1)' // Taxi
                        ];
                        const gi2Colors = [
                            'rgba(155, 89, 182, 1)', // VIP
                            'rgba(230, 126, 34, 1)', // Preferred Car
                            'rgba(26, 188, 156, 1)', // Preferred Motorbike
                            'rgba(149, 165, 166, 1)', // E-VIP
                            'rgba(234, 345, 10, 1)' // Extend Charging
                        ];
                        const finalColor = 'rgba(52, 73, 94, 1)'; // Lost Ticket
                        const vehicleColors = isGI2 ? [...baseColors, ...gi2Colors,
                            finalColor
                        ] : [...baseColors, finalColor];

                        // Generate chart datasets dynamically
                        const quantityVehicleDatasets = finalVehicleLabels.map((label, index) =>
                            ({
                                label: label,
                                data: [quantityData1[index], quantityData2[index]],
                                backgroundColor: vehicleColors[index],
                            }));

                        const incomeVehicleDatasets = finalVehicleLabels.map((label, index) =>
                            ({
                                label: label,
                                data: [incomeData1[index], incomeData2[index]],
                                backgroundColor: vehicleColors[index],
                            }));

                        // =================== END: DYNAMIC VEHICLE DATA LOGIC ===================


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
                                    label: 'jackcard',
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
                                    label: 'qris',
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
                                        color: '#000',
                                    }
                                },
                                x: {
                                    ticks: {
                                        autoSkip: false,
                                        maxRotation: 0,
                                        minRotation: 0,
                                        color: '#000',
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
                                        color: '#000',

                                    }
                                },
                                title: {
                                    display: true,
                                    text: 'Income by Payment',
                                    color: '#000',
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
                            datasets: quantityVehicleDatasets // Use dynamically generated datasets
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
                                        color: '#000',
                                    }
                                },
                                x: {
                                    ticks: {
                                        autoSkip: false,
                                        maxRotation: 0,
                                        minRotation: 0,
                                        color: '#000',
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
                                        color: '#000',

                                    }
                                },
                                title: {
                                    display: true,
                                    text: 'Quantity by Vehicle',
                                    color: '#000',
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
                                    const meta = chart.getDatasetMeta(i);

                                    meta.data.forEach((datapoint, index) => {
                                        const hiddenIndices = chart
                                            ._hiddenIndices || {};

                                        // Cek apakah index ini disembunyikan
                                        if (hiddenIndices[index]) return;

                                        const value = dataset.data[index];
                                        if (!value || value === 0) return;

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
                                            40 + (index * 4) : y - 40 - (
                                                index * 4);
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

                                        // Total hanya dari data yang tidak hidden
                                        const total = dataset.data.reduce((
                                            acc, val, i) => {
                                            return hiddenIndices[
                                                    i] ? acc : acc +
                                                val;
                                        }, 0);
                                        const percentage = ((value /
                                                total) * 100).toFixed(1) +
                                            '%';

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
                                    });

                                });
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

                        const simplifiedLabels = ['Car', 'Motorbike', 'Truck', 'Taxi', 'Other'];

                        // Ambil nilai untuk 4 kategori pertama + jumlah sisanya
                        const simplifiedData = [
                            quantityData1[0], // Car
                            quantityData1[1], // Motorbike
                            quantityData1[2], // Truck
                            quantityData1[3], // Taxi
                            // Other: jumlah dari index 4 sampai akhir
                            quantityData1.slice(4).reduce((sum, val) =>
                                sum + val, 0)
                        ];

                        const firstVehicleDistributionData = {
                            labels: simplifiedLabels,
                            datasets: [{
                                label: 'First Period',
                                data: simplifiedData,
                                backgroundColor: [
                                    'rgba(231, 76, 60, 1)',
                                    'rgba(52, 152, 219, 1)',
                                    'rgba(241, 196, 15, 1)',
                                    'rgba(46, 204, 113, 1)',
                                    'rgba(155, 89, 182, 1)',
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
                                        color: '#000',

                                    }
                                },
                                title: {
                                    display: true,
                                    text: 'First Period Vehicle Distribution',
                                    color: '#000',
                                    padding: {
                                        top: 10,
                                        bottom: 30
                                    }
                                },
                                datalabels: {
                                    color: '#000',

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

                        const simplifiedDataSecondPeriod = [
                            quantityData2[0], // Car 
                            quantityData2[1], // Motorbike
                            quantityData2[2], // Truck
                            quantityData2[3], // Taxi
                            // Other: jumlah dari index 
                            quantityData2.slice(4).reduce((sum, val) =>
                                sum + val, 0)
                        ];

                        const secondVehicleDistributionData = {
                            labels: simplifiedLabels,
                            datasets: [{
                                label: 'Second Period',
                                data: simplifiedDataSecondPeriod,
                                backgroundColor: [
                                    'rgba(231, 76, 60, 1)',
                                    'rgba(52, 152, 219, 1)',
                                    'rgba(241, 196, 15, 1)',
                                    'rgba(46, 204, 113, 1)',
                                    'rgba(155, 89, 182, 1)',
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
                                        color: '#000',

                                    }
                                },
                                title: {
                                    display: true,
                                    text: 'Second Period Vehicle Distribution',
                                    color: '#000',
                                },
                                datalabels: {
                                    color: '#000',
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
                            datasets: incomeVehicleDatasets // Use dynamically generated datasets
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
                                        color: '#000',
                                    }
                                },
                                x: {
                                    ticks: {
                                        autoSkip: false,
                                        maxRotation: 0,
                                        minRotation: 0,
                                        color: '#000',
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
                                        color: '#000',

                                    }
                                },
                                title: {
                                    display: true,
                                    text: 'Income by Vehicle',
                                    color: '#000',
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

                        // --- Dynamic Data Mapping Function ---
                        const mapVehicleData = (item, index, type) => {
                            const isQuantity = type === 'quantity';
                            const formatFunc = isQuantity ? formatQuantity : formatRupiah;
                            const keySuffix = isQuantity ? 'qty' : 'income';
                            const totalKey = isQuantity ? 'totalvehicle' :
                                'totalvehicleincome';

                            // Base data
                            let rowData = {
                                no: index + 1,
                                date: item.periode,
                                car: formatFunc(item[`car${keySuffix}`]),
                                motorbike: formatFunc(item[`motorbike${keySuffix}`]),
                                truck: formatFunc(item[`truck${keySuffix}`]),
                                taxi: formatFunc(item[`taxi${keySuffix}`]),
                                lostticket: formatFunc(isQuantity ? item.lostticketqty :
                                    item.lostticket),
                                other: formatFunc(item[`other${keySuffix}`]),
                            };
                            // Rename keys for datatables
                            rowData[`car${keySuffix}`] = rowData.car;
                            rowData[`motorbike${keySuffix}`] = rowData.motorbike;
                            rowData[`truck${keySuffix}`] = rowData.truck;
                            rowData[`taxi${keySuffix}`] = rowData.taxi;
                            rowData[`lostticket${keySuffix}`] = rowData.lostticket;
                            rowData[`other${keySuffix}`] = rowData.other;


                            // Base items for total calculation
                            let totalItems = [
                                item[`car${keySuffix}`], item[`motorbike${keySuffix}`],
                                item[`truck${keySuffix}`],
                                item[`taxi${keySuffix}`], (isQuantity ? item
                                    .lostticketqty : item.lostticket), item[
                                    `other${keySuffix}`]
                            ];

                            // Location-specific data
                            if (kodeLokasi === 'GI2') {
                                Object.assign(rowData, {
                                    valetlobby: formatFunc(item[
                                        `valetlobby${keySuffix}`]),
                                    valetnonlobby: formatFunc(item[
                                        `valetnonlobby${keySuffix}`]),
                                    vip: formatFunc(item[`vip${keySuffix}`]),
                                    carpreferred: formatFunc(item[
                                        `carpreferred${keySuffix}`]),
                                    motorbikepreferred: formatFunc(item[
                                        `motorbikepreferred${keySuffix}`]),
                                    evip: formatFunc(item[`evip${keySuffix}`]),
                                    extendcharging: formatFunc(item[
                                        `extendcharging${keySuffix}`]),
                                });
                                // Rename keys for datatables
                                rowData[`valetlobby${keySuffix}`] = rowData.valetlobby;
                                rowData[`valetnonlobby${keySuffix}`] = rowData
                                    .valetnonlobby;
                                rowData[`vip${keySuffix}`] = rowData.vip;
                                rowData[`carpreferred${keySuffix}`] = rowData.carpreferred;
                                rowData[`motorbikepreferred${keySuffix}`] = rowData
                                    .motorbikepreferred;
                                rowData[`evip${keySuffix}`] = rowData.evip;
                                rowData[`extendcharging${keySuffix}`] = rowData
                                    .extendcharging;

                                totalItems.push(
                                    item[`valetlobby${keySuffix}`], item[
                                        `valetnonlobby${keySuffix}`], item[
                                        `vip${keySuffix}`],
                                    item[`carpreferred${keySuffix}`], item[
                                        `motorbikepreferred${keySuffix}`], item[
                                        `evip${keySuffix}`],
                                    item[`extendcharging${keySuffix}`]
                                );
                            } else if (kodeLokasi === 'LW') {
                                Object.assign(rowData, {
                                    helm: formatFunc(item[`helm${keySuffix}`]),
                                    valetvip: formatFunc(item[
                                        `valetvip${keySuffix}`])
                                });
                                rowData[`helm${keySuffix}`] = rowData.helm;
                                rowData[`valetvip${keySuffix}`] = rowData.valetvip;
                                totalItems.push(item[`helm${keySuffix}`], item[
                                    `valetvip${keySuffix}`]);
                            } else if (kodeLokasi === 'JPRO') {
                                Object.assign(rowData, {
                                    valetvip: formatFunc(item[
                                        `valetvip${keySuffix}`])
                                });
                                rowData[`valetvip${keySuffix}`] = rowData.valetvip;
                                totalItems.push(item[`valetvip${keySuffix}`]);
                            }

                            const total = totalItems.reduce((sum, val) => sum + Number(
                                val || 0), 0);
                            rowData[totalKey] = formatFunc(total);

                            return rowData;
                        };


                        const rowsQuantityVehicle = dataFirstPeriodIncomePayment.map((item,
                            index) => mapVehicleData(item, index, 'quantity'));
                        const rowsQuantityVehicleSecond = dataSecondPeriodIncomePayment.map((
                            item, index) => mapVehicleData(item, index, 'quantity'));
                        const rowIncomeVehicleFirst = dataFirstPeriodIncomePayment.map((item,
                            index) => mapVehicleData(item, index, 'income'));
                        const rowIncomeVehicleSecond = dataSecondPeriodIncomePayment.map((item,
                            index) => mapVehicleData(item, index, 'income'));


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
                                            color: '#000',
                                        }
                                    },
                                    x: {
                                        stacked: true,
                                        ticks: {
                                            autoSkip: false,
                                            maxRotation: 90,
                                            minRotation: 0,
                                            color: '#000',
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
                                            color: '#000'
                                        }
                                    },
                                    title: {
                                        display: true,
                                        text: chartTitle,
                                        color: '#000',
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
                        $('.total-casual').text(formatQuantity(firstPeriodTotals.vehiclecasual))
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
                        const totalVip = firstPeriodTotals.vipqty + firstPeriodTotals
                            .vipramayanaqty + firstPeriodTotals.vipnonramayanaqty;
                        const totalVipSecond = secondPeriodTotals.vipqty + secondPeriodTotals
                            .vipramayanaqty + secondPeriodTotals.vipnonramayanaqty;
                        const totalVipIncome = firstPeriodTotals.vipincome + firstPeriodTotals
                            .vipramayanaincome + firstPeriodTotals.vipnonramayanaincome;
                        const totalVipIncomeSecond = secondPeriodTotals.vipincome +
                            secondPeriodTotals
                            .vipramayanaincome + secondPeriodTotals.vipnonramayanaincome;
                        const valetLobby = firstPeriodTotals.valetlobbyqty + firstPeriodTotals
                            .valetvoucherqty;
                        const valetLobbySecond = secondPeriodTotals.valetlobbyqty +
                            secondPeriodTotals
                            .valetvoucherqty;
                        const valetlobbyincome = firstPeriodTotals.valetlobbyincome +
                            firstPeriodTotals.valetvoucherincome;
                        const valetlobbyincomeSecond = secondPeriodTotals.valetlobbyincome +
                            secondPeriodTotals.valetvoucherincome;
                        const grandTotalIncome = firstPeriodTotals.allpayment +
                            firstPeriodTotals.lostticket;
                        const grandTotalIncomeSecond = secondPeriodTotals.allpayment +
                            secondPeriodTotals.lostticket;
                        const grandTotalVehicleIncome = firstPeriodTotals.vehicleincome +
                            firstPeriodTotals.lostticket;
                        const grandTotalVehicleIncomeSecond = secondPeriodTotals.vehicleincome +
                            secondPeriodTotals.lostticket;
                        $('.result').show();

                        // =================== START: IF-ELSE LOGIC FOR TABLE ROWS ===================
                        let serviceRows = '';

                        if (kodeLokasi === 'GI2') {
                            serviceRows = `
                                <tr>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000;">5</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">Brizzi</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(firstPeriodTotals.brizzi)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(secondPeriodTotals.brizzi)}</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">Valet Lobby</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(valetLobby)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(valetLobbySecond)}</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">Valet Lobby</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(valetlobbyincome)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(valetlobbyincomeSecond)}</td>
                                </tr>
                                <tr>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000;">6</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">Tapcash</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(firstPeriodTotals.tapcash)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(secondPeriodTotals.tapcash)}</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">Valet Non-Lobby</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(firstPeriodTotals.valetnonlobbyqty)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(secondPeriodTotals.valetnonlobbyqty)}</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">Valet Non-Lobby</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(firstPeriodTotals.valetnonlobbyincome)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(secondPeriodTotals.valetnonlobbyincome)}</td>
                                </tr>
                                <tr>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000;">7</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">Qris</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(firstPeriodTotals.qrisepayment)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(secondPeriodTotals.qrisepayment)}</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">VIP</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(totalVip)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(totalVipSecond)}</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">VIP</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(totalVipIncome)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(totalVipIncomeSecond)}</td>
                                </tr>
                                <tr>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000;">8</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">DKI Jakcard</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(firstPeriodTotals.dkijackpayment)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(secondPeriodTotals.dkijackpayment)}</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">Preferred Car</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(firstPeriodTotals.carpreferredqty)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(secondPeriodTotals.carpreferredqty)}</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">Preferred Car</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(firstPeriodTotals.carpreferredincome)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(secondPeriodTotals.carpreferredincome)}</td>
                                </tr>
                                <tr>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000;">9</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">Preferred Motorbike</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(firstPeriodTotals.motorbikepreferredqty)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(secondPeriodTotals.motorbikepreferredqty)}</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">Preferred Motorbike</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(firstPeriodTotals.motorbikepreferredincome)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(secondPeriodTotals.motorbikepreferredincome)}</td>
                                </tr>
                                <tr>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000;">10</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">E-VIP</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(firstPeriodTotals.evipqty)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(secondPeriodTotals.evipqty)}</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">E-VIP</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(firstPeriodTotals.evipincome)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(secondPeriodTotals.evipincome)}</td>
                                </tr>
                                <tr>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000;">11</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">Extend Charging</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(firstPeriodTotals.extendchargingqty)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(secondPeriodTotals.extendchargingqty)}</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">Extend Charging</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(firstPeriodTotals.extendchargingincome)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(secondPeriodTotals.extendchargingincome)}</td>
                                </tr>
                                <tr>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000;">12</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">Lost Ticket</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(firstPeriodTotals.lostticketqty)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(secondPeriodTotals.lostticketqty)}</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">Lost Ticket</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(firstPeriodTotals.lostticket)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(secondPeriodTotals.lostticket)}</td>
                                </tr>
                            `;
                        } else if (kodeLokasi === 'LW') {
                            serviceRows = `
                                <tr>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000;">5</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">Brizzi</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(firstPeriodTotals.brizzi)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(secondPeriodTotals.brizzi)}</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">Helm</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(firstPeriodTotals.helmqty)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(secondPeriodTotals.helmqty)}</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">Helm</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(firstPeriodTotals.helmincome)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(secondPeriodTotals.helmincome)}</td>
                                </tr>
                                <tr>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000;">6</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">Tapcash</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(firstPeriodTotals.tapcash)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(secondPeriodTotals.tapcash)}</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">Valet</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(firstPeriodTotals.valetvipqty)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(secondPeriodTotals.valetvipqty)}</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">Valet</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(firstPeriodTotals.valetvipincome)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(secondPeriodTotals.valetvipincome)}</td>
                                </tr>
                                <tr>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000;">7</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">Qris</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(firstPeriodTotals.qrisepayment)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(secondPeriodTotals.qrisepayment)}</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">Lost Ticket</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(firstPeriodTotals.lostticketqty)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(secondPeriodTotals.lostticketqty)}</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">Lost Ticket</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(firstPeriodTotals.lostticket)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(secondPeriodTotals.lostticket)}</td>
                                </tr>
                                <tr>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000;">8</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">DKI Jakcard</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(firstPeriodTotals.dkijackpayment)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(secondPeriodTotals.dkijackpayment)}</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                </tr>
                           `;
                        } else if (kodeLokasi === 'JPRO') {
                            serviceRows = `
                                <tr>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000;">5</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">Brizzi</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(firstPeriodTotals.brizzi)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(secondPeriodTotals.brizzi)}</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">Valet</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(firstPeriodTotals.valetvipqty)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(secondPeriodTotals.valetvipqty)}</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">Valet</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(firstPeriodTotals.valetvipincome)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(secondPeriodTotals.valetvipincome)}</td>
                                </tr>
                                <tr>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000;">6</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">Tapcash</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(firstPeriodTotals.tapcash)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(secondPeriodTotals.tapcash)}</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">Lost Ticket</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(firstPeriodTotals.lostticketqty)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(secondPeriodTotals.lostticketqty)}</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">Lost Ticket</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(firstPeriodTotals.lostticket)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(secondPeriodTotals.lostticket)}</td>
                                </tr>
                                <tr>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000;">7</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">Qris</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(firstPeriodTotals.qrisepayment)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(secondPeriodTotals.qrisepayment)}</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                </tr>
                                <tr>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000;">8</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">DKI Jakcard</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(firstPeriodTotals.dkijackpayment)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(secondPeriodTotals.dkijackpayment)}</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                </tr>
                           `;
                        } else {
                            serviceRows = `
                                <tr>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000;">5</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">Brizzi</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(firstPeriodTotals.brizzi)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(secondPeriodTotals.brizzi)}</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">Lost Ticket</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(firstPeriodTotals.lostticketqty)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(secondPeriodTotals.lostticketqty)}</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">Lost Ticket</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(firstPeriodTotals.lostticket)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(secondPeriodTotals.lostticket)}</td>
                                </tr>
                                <tr>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000;">6</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">Tapcash</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(firstPeriodTotals.tapcash)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(secondPeriodTotals.tapcash)}</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">Other</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(firstPeriodTotals.otherqtypass)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(firstPeriodTotals.otherqty)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(secondPeriodTotals.otherqtypass)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(secondPeriodTotals.otherqty)}</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">Other</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(firstPeriodTotals.otherincome)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(secondPeriodTotals.otherincome)}</td>
                                </tr>
                                <tr>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000;">7</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">Qris</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(firstPeriodTotals.qrisepayment)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(secondPeriodTotals.qrisepayment)}</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                </tr>
                                <tr>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000;">8</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">DKI Jakcard</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(firstPeriodTotals.dkijackpayment)}</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(secondPeriodTotals.dkijackpayment)}</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                    <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">-</td>
                                </tr>
                           `;
                        }


                        if ($.fn.DataTable.isDataTable('#table-custom')) {
                            $('#table-custom').DataTable().destroy();
                        }
                        $('#tbody-custom').empty();
                        $('#tbody-custom').append(`
                            <tr>
                                <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000;">1</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">Cash</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(firstPeriodTotals.cash)}</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(secondPeriodTotals.cash)}</td>
                                <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">Mobil</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(firstPeriodTotals.carpassqty)}</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(firstPeriodTotals.carqty)}</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(secondPeriodTotals.carpassqty)}</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(secondPeriodTotals.carqty)}</td>
                                <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">Mobil</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(firstPeriodTotals.carincome)}</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(secondPeriodTotals.carincome)}</td>
                            </tr>
                            <tr>
                                <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000;">2</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">Parkee</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(firstPeriodTotals.parkeepayment)}</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(secondPeriodTotals.parkeepayment)}</td>
                                <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">Motor</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(firstPeriodTotals.motorbikepassqty)}</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(firstPeriodTotals.motorbikeqty)}</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(secondPeriodTotals.motorbikepassqty)}</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(secondPeriodTotals.motorbikeqty)}</td>
                                <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">Motor</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(firstPeriodTotals.motorbikeincome)}</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(secondPeriodTotals.motorbikeincome)}</td>
                            </tr>
                            <tr>
                                <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000;">3</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">Emoney</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(firstPeriodTotals.emoney)}</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(secondPeriodTotals.emoney)}</td>
                                <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">Truck/Loading</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(firstPeriodTotals.truckpassqty)}</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(firstPeriodTotals.truckqty)}</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(secondPeriodTotals.truckpassqty)}</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(secondPeriodTotals.truckqty)}</td>
                                <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">Truck/Loading</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(firstPeriodTotals.truckincome)}</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(secondPeriodTotals.truckincome)}</td>
                            </tr>
                            <tr>
                                <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000;">4</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">Flazz</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(firstPeriodTotals.flazz)}</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(secondPeriodTotals.flazz)}</td>
                                <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">Taxi</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(firstPeriodTotals.taxipassqty)}</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(firstPeriodTotals.taxiqty)}</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(secondPeriodTotals.taxipassqty)}</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatQuantity(secondPeriodTotals.taxiqty)}</td>
                                <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;">Taxi</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(firstPeriodTotals.taxiincome)}</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;">${formatRupiah(secondPeriodTotals.taxiincome)}</td>
                            </tr>
                            ${serviceRows}
                            <tr>
                                <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000; font-weight: bold;"></td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000; font-weight: bold;">Grand Total</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000; font-weight: bold;">${formatRupiah(grandTotalIncome)}</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000; font-weight: bold;">${formatRupiah(grandTotalIncomeSecond)}</td>
                                <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000; font-weight: bold;">Grand Total</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000; font-weight: bold;">${formatQuantity(GrandTotals.quantity_pass.first_period)}</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000; font-weight: bold;">${formatQuantity(firstPeriodTotals.vehiclecasual)}</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000; font-weight: bold;">${formatQuantity(GrandTotals.quantity_pass.second_period)}</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000; font-weight: bold;">${formatQuantity(secondPeriodTotals.vehiclecasual)}</td>
                                <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000; font-weight: bold;">Grand Total</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000; font-weight: bold;">${formatRupiah(grandTotalVehicleIncome)}</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000; font-weight: bold;">${formatRupiah(grandTotalVehicleIncomeSecond)}</td>
                            </tr>
                            <tr>
                                <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000;"></td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;"></td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;"></td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;"></td>
                                <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000;"></td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;"></td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;"></td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;"></td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000;"></td>
                                <td class="text-start" style="border-left: 1px solid #000; border-top: 1px solid #000;border-right: 1px solid #000; font-weight: bold;">Sticker Income</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000; font-weight: bold;">${formatRupiah(firstPeriodTotals.stickerincome)}</td>
                                <td class="text-start" style="border-top: 1px solid #000; border-right: 1px solid #000; font-weight: bold;">${formatRupiah(secondPeriodTotals.stickerincome)}</td>
                            </tr>
                        `);
                        // =================== END: IF-ELSE LOGIC FOR TABLE ROWS ===================



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
                                                    <h4 style="color: #000;" class="fw-bold mb-0">${formatRupiah(firstPeriod)}</h4>
                                                    <small class="text-muted tgl_row1">Total second period :</small>
                                                    <p style="color: #000 !important;" class="text-muted tgl_row1">${formatRupiah(secondPeriod)}</p>
                                                </div>
                                                <div class="${colorClass}">${percentageText}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });

                        // Generate comparison cards dynamically
                        const compareVehicleLabel = finalVehicleLabels.map((label, index) => ({
                            label: label,
                            firstPeriod: quantityData1[index],
                            secondPeriod: quantityData2[index],
                        }));

                        const labelIncomeVehicle = finalVehicleLabels.map((label, index) => ({
                            label: label,
                            firstPeriod: incomeData1[index],
                            secondPeriod: incomeData2[index],
                        }));


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
                                                    <h4 style="color: #000;" class="fw-bold mb-0">${formatQuantity(firstPeriod)}</h4>
                                                    <small class="text-muted tgl_row1">Total second period :</small>
                                                    <p style="color: #000 !important;" class="text-muted tgl_row1">${formatQuantity(secondPeriod)}</p>
                                                </div>
                                                <div class="${colorClass}">${percentageText}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });


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
                                                    <h4 style="color: #000;" class="fw-bold mb-0">${formatRupiah(firstPeriod)}</h4>
                                                    <small class="text-muted tgl_row1">Total second period :</small>
                                                    <p style="color: #000 !important;" class="text-muted tgl_row1">${formatRupiah(secondPeriod)}</p>
                                                </div>
                                                <div class="${colorClass}">${percentageText}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });

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
