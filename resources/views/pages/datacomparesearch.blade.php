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

    <style>
        /* Apply flexbox to the wrapper that contains the search and buttons */
        #membershipTable_wrapper .dt-top {
            display: flex;
            justify-content: flex-start;
            /* Align buttons and search to the left */
            gap: 20px;
            /* Add space between the buttons and search */
            align-items: center;
        }

        .table> :not(caption)>*>* {
            padding: 2px;
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
            margin-bottom: 5px;
        }

        #dt-search-0 {
            height: 40px;
            border-radius: 10px;
            margin-left: 10px;
        }
    </style>
    <style>
        .content-custom {
            padding: 10px !important;
            background-color: #ffffff !important;
            border-radius: 10px !important;
            box-shadow: 1px -2px 15px -1px rgba(0, 0, 0, 0.28);
            color: #000000 !important;
        }

        .search-wrapper {
            width: 40%;
        }
    </style>

    <!-- resources/views/compare.blade.php -->
    <div class="search-wrapper">
        <div class="d-flex align-items-end gap-3 mb-3">
            <div>
                <label for="start-date-1" class="form-label text-dark">Select Month</label>
                <input type="month" name="start1" id="start-date-1" class="form-control" placeholder="Select month" />
            </div>
        </div>

        <div class="mt-3">
            <button type="button" class="btn btn-submit" id="cari">Cari</button>
        </div>

        <!-- Alert Message -->
        <div id="alertMessage" class="alert alert-danger mt-3" role="alert" style="display: none;">
            Please select a month before submitting.
        </div>
    </div>




    <div id="custom-total-search">
        <div class="-">
            <div class="row">
                <div class="col-md-4">
                    <h4 style="color: white; text-align: center; font-weight:bold;">
                        Income By E-Payment
                    </h4>

                </div>
                <div class="col-md-4">
                    <h4 style="color: white; text-align: center; font-weight:bold;">Income By Vehicle </h4>
                    <hr>


                </div>
                <div class="col-md-4">
                    <h4 style="color: white; text-align: center; font-weight:bold;">Quantity By Vehicle</h3>

                </div>
            </div>
        </div>
        <div class="-">
            <div class="row">
                <div class="col-md-4">
                    <table class="table table-striped">
                        <br>
                        <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">CP</th>
                                <th scope="col">Parkee</th>
                                <th scope="col">diff</th>
                            </tr>
                        </thead>
                        <tbody id="table-payment">
                        </tbody>
                    </table>
                </div>
                <div class="col-md-4">
                    <table class="table table-striped">
                        <br>
                        <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">CP</th>
                                <th scope="col">Parkee</th>
                                <th scope="col">diff</th>
                            </tr>
                        </thead>
                        <tbody id="table-income">
                        </tbody>
                    </table>
                </div>
                <div class="col-md-4">
                    <table class="table table-striped">
                        <br>
                        <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">CP</th>
                                <th scope="col">Parkee</th>
                                <th scope="col">diff</th>
                            </tr>
                        </thead>
                        <tbody id="table-quantity">
                        </tbody>
                    </table>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <h4 style="color: white; text-align: center; font-weight:bold;">
                        Income By E-Payment
                    </h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped">
                        <br>
                        <thead>
                            <tr>
                                <th rowspan="2">No.</th>
                                <th rowspan="2">Tanggal</th>
                                <th colspan="3" style="text-align:center;">Cash</th>
                                <th colspan="3" style="text-align:center;">Parkee</th>
                                <th colspan="3" style="text-align:center;">Emoney</th>
                                <th colspan="3" style="text-align:center;">Flazz</th>
                                <th colspan="3" style="text-align:center;">Brizzi</th>
                                <th colspan="3" style="text-align:center;">Tapcash</th>
                                <!-- <th scope="col">DKIJack</th>
                                                                                                                        <th scope="col">EDC</th>
                                                                                                                        <th scope="col">Luminous</th>
                                                                                                                        <th scope="col">Mega</th>
                                                                                                                        <th scope="col">Nabu</th> -->
                            </tr>
                            <tr>
                                <th>CP</th>
                                <th>PARKEE</th>
                                <th>DIFF</th>
                                <th>CP</th>
                                <th>PARKEE</th>
                                <th>DIFF</th>
                                <th>CP</th>
                                <th>PARKEE</th>
                                <th>DIFF</th>
                                <th>CP</th>
                                <th>PARKEE</th>
                                <th>DIFF</th>
                                <th>CP</th>
                                <th>PARKEE</th>
                                <th>DIFF</th>
                                <th>CP</th>
                                <th>PARKEE</th>
                                <th>DIFF</th>
                            </tr>
                        </thead>
                        <tbody id="table-payment-detail">
                        </tbody>
                    </table>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <h4 style="color: white; text-align: center; font-weight:bold;">
                        Income By Vehicle
                    </h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped">
                        <br>
                        <thead>
                            <tr>
                                <th rowspan="2">No.</th>
                                <th rowspan="2">Tanggal</th>
                                <th colspan="3" style="text-align:center;">Mobil</th>
                                <th colspan="3" style="text-align:center;">Motor</th>
                                <th colspan="3" style="text-align:center;">Truck</th>
                                <th colspan="3" style="text-align:center;">Taxi</th>
                                <?php
                                                                            if ($kodeLokasi == 'GI2') {
                                                                            ?>
                                <th colspan="3" style="text-align:center;">Valet Lobby</th>
                                <th colspan="3" style="text-align:center;">Valet Nonlobby</th>
                                <th colspan="3" style="text-align:center;">Preferred Car</th>
                                <th colspan="3" style="text-align:center;">VIP</th>
                                <?php
                                                                            } else if ($kodeLokasi == 'LW') {
                                                                            ?>
                                <th colspan="3" style="text-align:center;">Helm</th>
                                <?php
                                                                            }
                                                                            ?>
                                <th colspan="3" style="text-align:center;">Other</th>
                            </tr>
                            <tr>
                                <th>CP</th>
                                <th>PARKEE</th>
                                <th>DIFF</th>
                                <th>CP</th>
                                <th>PARKEE</th>
                                <th>DIFF</th>
                                <th>CP</th>
                                <th>PARKEE</th>
                                <th>DIFF</th>
                                <th>CP</th>
                                <th>PARKEE</th>
                                <th>DIFF</th>
                                <?php
                                                                            if ($kodeLokasi == 'GI2') {
                                                                            ?>
                                <th>CP</th>
                                <th>PARKEE</th>
                                <th>DIFF</th>
                                <th>CP</th>
                                <th>PARKEE</th>
                                <th>DIFF</th>
                                <th>CP</th>
                                <th>PARKEE</th>
                                <th>DIFF</th>
                                <th>CP</th>
                                <th>PARKEE</th>
                                <th>DIFF</th>
                                <?php
                                                                            } else if ($kodeLokasi == 'LW') {
                                                                            ?>
                                <th>CP</th>
                                <th>PARKEE</th>
                                <th>DIFF</th>
                                <?php
                                                                            }
                                                                            ?>
                                <th>CP</th>
                                <th>PARKEE</th>
                                <th>DIFF</th>
                            </tr>
                        </thead>
                        <tbody id="table-income-detail">
                        </tbody>
                    </table>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <h4 style="color: white; text-align: center; font-weight:bold;">
                        Quantity By Vehicle
                    </h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped">
                        <br>
                        <thead>
                            <tr>
                                <th rowspan="2">No.</th>
                                <th rowspan="2">Tanggal</th>
                                <th colspan="3" style="text-align:center;">Mobil</th>
                                <th colspan="3" style="text-align:center;">Motor</th>
                                <th colspan="3" style="text-align:center;">Truck</th>
                                <th colspan="3" style="text-align:center;">Taxi</th>
                                <?php
                                                                            if ($kodeLokasi == 'GI2') {
                                                                            ?>
                                <th colspan="3" style="text-align:center;">Valet Lobby</th>
                                <th colspan="3" style="text-align:center;">Valet Nonlobby</th>
                                <th colspan="3" style="text-align:center;">Preferred Car</th>
                                <th colspan="3" style="text-align:center;">VIP</th>
                                <?php
                                                                            } else if ($kodeLokasi == 'LW') {
                                                                            ?>
                                <th colspan="3" style="text-align:center;">Helm</th>
                                <?php
                                                                            }
                                                                            ?>
                                <th colspan="3" style="text-align:center;">Other</th>
                            </tr>
                            <tr>
                                <th>CP</th>
                                <th>PARKEE</th>
                                <th>DIFF</th>
                                <th>CP</th>
                                <th>PARKEE</th>
                                <th>DIFF</th>
                                <th>CP</th>
                                <th>PARKEE</th>
                                <th>DIFF</th>
                                <th>CP</th>
                                <th>PARKEE</th>
                                <th>DIFF</th>
                                <?php
                                                                            if ($kodeLokasi == 'GI2') {
                                                                            ?>
                                <th>CP</th>
                                <th>PARKEE</th>
                                <th>DIFF</th>
                                <th>CP</th>
                                <th>PARKEE</th>
                                <th>DIFF</th>
                                <th>CP</th>
                                <th>PARKEE</th>
                                <th>DIFF</th>
                                <th>CP</th>
                                <th>PARKEE</th>
                                <th>DIFF</th>
                                <?php
                                                                            } else if ($kodeLokasi == 'LW') {
                                                                            ?>
                                <th>CP</th>
                                <th>PARKEE</th>
                                <th>DIFF</th>
                                <?php
                                                                            }
                                                                            ?>
                                <th>CP</th>
                                <th>PARKEE</th>
                                <th>DIFF</th>
                            </tr>
                        </thead>
                        <tbody id="table-quantity-detail">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>



    <script>
        $('#cari').click(function() {
            const selectedMonth = $('#start-date-1').val(); // format: YYYY-MM
            const $cariButton = $(this);

            if (!selectedMonth) {
                $('#alertMessage').show();
                return;
            } else {
                $('#alertMessage').hide();
            }

            const [year, month] = selectedMonth.split('-');
            const startDate = `${year}-${month}-01`;

            // Last day of selected month
            const lastDay = new Date(year, month, 0).getDate();
            const endDate = `${year}-${month}-${lastDay.toString().padStart(2, '0')}`;
            console.log('Start Date:', startDate);
            console.log('End Date:', endDate);
            // Disable button and show loading text
            $cariButton.prop('disabled', true).html('Loading...');

            $.ajax({
                url: '{{ route('dataCompareAPI') }}',
                method: 'POST',
                data: {
                    startdate: startDate,
                    enddate: endDate,
                    _token: '{{ csrf_token() }}'
                },
                success: function(r) {
                    var data = r[0];
                    const formatRupiah = (money) => {
                        return new Intl.NumberFormat({
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0
                        }).format(money);
                    }

                    var table_payment = '';
                    var table_income = '';
                    var table_quantity = '';
                    var table_payment_detail = '';
                    var table_income_detail = '';
                    var table_quantity_detail = '';
                    var no_payment_detail = 1;
                    var no_income_detail = 1;
                    var no_quantity_detail = 1;
                    var no_payment = 1;
                    var no_income = 1;
                    var no_quantity = 1;

                    $.each(data, function(index, key) {
                        var diffColorPayment;
                        var diffColorIncome;
                        var diffColorQuantity;

                        if (key.allpaymentdiff == 0 || key.vehicleincomediff == 0 || key
                            .vehiclecasualdiff == 0) {
                            diffColorPayment =
                                `<td style="color:white;"> ${formatRupiah(Math.round(key.allpaymentdiff))} </td>`;
                            diffColorIncome =
                                `<td style="color:white;"> ${formatRupiah(Math.round(key.vehicleincomediff))} </td>`;
                            diffColorQuantity =
                                `<td style="color:white;"> ${formatRupiah(Math.round(key.vehiclecasualdiff))} </td>`;
                        } else {
                            diffColorPayment =
                                `<td style="color:red;"> ${formatRupiah(Math.round(key.allpaymentdiff))} </td>`;
                            diffColorIncome =
                                `<td style="color:red;"> ${formatRupiah(Math.round(key.vehicleincomediff))} </td>`;
                            diffColorQuantity =
                                `<td style="color:red;"> ${formatRupiah(Math.round(key.vehiclecasualdiff))} </td>`;
                        }

                        if (key.cashdiff == 0 || key.parkeediff == 0 || key.emoneydiff == 0 ||
                            key.flazzdiff == 0 || key.brizzidiff == 0 || tapcashdiff == 0) {
                            var diff_cash =
                                `<td style="color:white;"> ${formatRupiah(Math.round(key.cashdiff))} </td>`;
                            var diff_parkee =
                                `<td style="color:white;"> ${formatRupiah(Math.round(key.parkeepaymentdiff))} </td>`;
                            var diff_emoney =
                                `<td style="color:white;"> ${formatRupiah(Math.round(key.emoneydiff))} </td>`;
                            var diff_flazz =
                                `<td style="color:white;"> ${formatRupiah(Math.round(key.flazzdiff))} </td>`;
                            var diff_brizzi =
                                `<td style="color:white;"> ${formatRupiah(Math.round(key.brizzidiff))} </td>`;
                            var diff_tapcash =
                                `<td style="color:white;"> ${formatRupiah(Math.round(key.tapcashdiff))} </td>`;
                        } else {
                            var diff_cash =
                                `<td style="color:red;"> ${formatRupiah(Math.round(key.cashdiff))} </td>`;
                            var diff_parkee =
                                `<td style="color:red;"> ${formatRupiah(Math.round(key.parkeepaymentdiff))} </td>`;
                            var diff_emoney =
                                `<td style="color:red;"> ${formatRupiah(Math.round(key.emoneydiff))} </td>`;
                            var diff_flazz =
                                `<td style="color:red;"> ${formatRupiah(Math.round(key.flazzdiff))} </td>`;
                            var diff_brizzi =
                                `<td style="color:red;"> ${formatRupiah(Math.round(key.brizzidiff))} </td>`;
                            var diff_tapcash =
                                `<td style="color:red;"> ${formatRupiah(Math.round(key.tapcashdiff))} </td>`;
                        }

                        if (key.carincomediff == 0 || key.motorbikeincomediff == 0 || key
                            .truckincomediff == 0 || key.taxiincomediff == 0 || key
                            .otherincomediff == 0) {
                            var diff_carincome =
                                `<td style="color:white;"> ${formatRupiah(Math.round(key.carincomediff))} </td>`;
                            var diff_motorbikeincome =
                                `<td style="color:white;"> ${formatRupiah(Math.round(key.motorbikeincomediff))} </td>`;
                            var diff_truckincome =
                                `<td style="color:white;"> ${formatRupiah(Math.round(key.truckincomediff))} </td>`;
                            var diff_taxiincome =
                                `<td style="color:white;"> ${formatRupiah(Math.round(key.taxiincomediff))} </td>`;
                            var diff_otherincome =
                                `<td style="color:white;"> ${formatRupiah(Math.round(key.otherincomediff))} </td>`;
                        } else {
                            var diff_carincome =
                                `<td style="color:red;"> ${formatRupiah(Math.round(key.carincomediff))} </td>`;
                            var diff_motorbikeincome =
                                `<td style="color:red;"> ${formatRupiah(Math.round(key.motorbikeincomediff))} </td>`;
                            var diff_truckincome =
                                `<td style="color:red;"> ${formatRupiah(Math.round(key.truckincomediff))} </td>`;
                            var diff_taxiincome =
                                `<td style="color:red;"> ${formatRupiah(Math.round(key.taxiincomediff))} </td>`;
                            var diff_otherincome =
                                `<td style="color:red;"> ${formatRupiah(Math.round(key.otherincomediff))} </td>`;
                        }

                        if (key.carquantitydiff == 0 || key.motorbikequantitydiff == 0 || key
                            .truckquantitydiff == 0 || key.taxiquantitydiff == 0 || key
                            .otherquantitydiff == 0) {
                            var diff_carquantity =
                                `<td style="color:white;"> ${formatRupiah(Math.round(key.carqtydiff))} </td>`;
                            var diff_motorbikquantity =
                                `<td style="color:white;"> ${formatRupiah(Math.round(key.motorbikeqtydiff))} </td>`;
                            var diff_truckquantity =
                                `<td style="color:white;"> ${formatRupiah(Math.round(key.truckqtydiff))} </td>`;
                            var diff_taxiquantity =
                                `<td style="color:white;"> ${formatRupiah(Math.round(key.taxiqtydiff))} </td>`;
                            var diff_otherquantity =
                                `<td style="color:white;"> ${formatRupiah(Math.round(key.otherqtydiff))} </td>`;
                        } else {
                            var diff_carquantity =
                                `<td style="color:red;"> ${formatRupiah(Math.round(key.carqtydiff))} </td>`;
                            var diff_motorbikequantity =
                                `<td style="color:red;"> ${formatRupiah(Math.round(key.motorbikeqtydiff))} </td>`;
                            var diff_truckquantity =
                                `<td style="color:red;"> ${formatRupiah(Math.round(key.truckqtydiff))} </td>`;
                            var diff_taxiquantity =
                                `<td style="color:red;"> ${formatRupiah(Math.round(key.taxiqtydiff))} </td>`;
                            var diff_otherquantity =
                                `<td style="color:red;"> ${formatRupiah(Math.round(key.otherqtydiff))} </td>`;
                        }

                        //Payment
                        table_payment += `<tr>
                                            <td class="text-left"> ${no_payment++} </td>
                                            <td class="text-left"> ${key.periode} </td>
                                            <td> ${formatRupiah(Math.round(key.allpaymentcp))} </td>
                                            <td> ${formatRupiah(Math.round(key.allpaymentparkee))} </td>
                                            ${diffColorPayment}
                                          </tr>`;

                        //Income by Vehicle
                        table_income += `<tr>
                                            <td class="text-left"> ${no_income++} </td>
                                            <td class="text-left"> ${key.periode} </td>
                                            <td> ${formatRupiah(Math.round(key.vehicleincomecp))} </td>
                                            <td> ${formatRupiah(Math.round(key.vehicleincomeparkee))} </td>
                                            ${diffColorIncome}
                                          </tr>`;

                        //Quantity by Vehicle
                        table_quantity += `<tr>
                                            <td class="text-left"> ${no_quantity++} </td>
                                            <td class="text-left"> ${key.periode} </td>
                                            <td> ${formatRupiah(Math.round(key.vehiclecasualcp))} </td>
                                            <td> ${formatRupiah(Math.round(key.vehiclecasualparkee))} </td>
                                            ${diffColorQuantity}
                                          </tr>`;

                        table_payment_detail += `<tr>
                                                    <td class="text-left"> ${no_payment_detail++} </td>
                                                    <td class="text-left"> ${key.periode} </td>
                                                    <td> ${formatRupiah(Math.round(key.cashcp))} </td>
                                                    <td> ${formatRupiah(Math.round(key.cashparkee))} </td>
                                                    <td> ${formatRupiah(Math.round(key.cashdiff))} </td>
                                                    <td> ${formatRupiah(Math.round(key.parkeepaymentcp))} </td>
                                                    <td> ${formatRupiah(Math.round(key.parkeepaymentparkee))} </td>
                                                    <td> ${formatRupiah(Math.round(key.parkeepaymentdiff))} </td>
                                                    <td> ${formatRupiah(Math.round(key.emoneycp))} </td>
                                                    <td> ${formatRupiah(Math.round(key.emoneyparkee))} </td>
                                                    <td> ${formatRupiah(Math.round(key.emoneydiff))} </td>
                                                    <td> ${formatRupiah(Math.round(key.flazzcp))} </td>
                                                    <td> ${formatRupiah(Math.round(key.flazzparkee))} </td>
                                                    <td> ${formatRupiah(Math.round(key.flazzdiff))} </td>
                                                    <td> ${formatRupiah(Math.round(key.brizzicp))} </td>
                                                    <td> ${formatRupiah(Math.round(key.brizziparkee))} </td>
                                                    <td> ${formatRupiah(Math.round(key.brizzidiff))} </td>
                                                    <td> ${formatRupiah(Math.round(key.tapcashcp))} </td>
                                                    <td> ${formatRupiah(Math.round(key.tapcashparkee))} </td>
                                                    <td> ${formatRupiah(Math.round(key.tapcashdiff))} </td>
                                                </tr>`;

                        table_income_detail += `<tr>
                                                    <td class="text-left"> ${no_income_detail++} </td>
                                                    <td class="text-left"> ${key.periode} </td>
                                                    <td> ${formatRupiah(Math.round(key.carincomecp))} </td>
                                                    <td> ${formatRupiah(Math.round(key.carincomeparkee))} </td>
                                                    <td> ${formatRupiah(Math.round(key.carincomediff))} </td>
                                                    <td> ${formatRupiah(Math.round(key.motorbikeincomecp))} </td>
                                                    <td> ${formatRupiah(Math.round(key.motorbikeincomeparkee))} </td>
                                                    <td> ${formatRupiah(Math.round(key.motorbikeincomediff))} </td>
                                                    <td> ${formatRupiah(Math.round(key.truckincomecp))} </td>
                                                    <td> ${formatRupiah(Math.round(key.truckincomeparkee))} </td>
                                                    <td> ${formatRupiah(Math.round(key.truckincomediff))} </td>
                                                    <td> ${formatRupiah(Math.round(key.taxiincomecp))} </td>
                                                    <td> ${formatRupiah(Math.round(key.taxiincomeparkee))} </td>
                                                    <td> ${formatRupiah(Math.round(key.taxiincomediff))} </td>
                                                    <td> ${formatRupiah(Math.round(key.valetlobbyincomecp))} </td>
                                                    <td> ${formatRupiah(Math.round(key.valetlobbyincomeparkee))} </td>
                                                    <td> ${formatRupiah(Math.round(key.valetlobbyincomediff))} </td>
                                                    <td> ${formatRupiah(Math.round(key.valetnonlobbyincomecp))} </td>
                                                    <td> ${formatRupiah(Math.round(key.valetnonlobbyincomeparkee))} </td>
                                                    <td> ${formatRupiah(Math.round(key.valetnonlobbyincomediff))} </td>
                                                    <td> ${formatRupiah(Math.round(key.carpreferredincomecp))} </td>
                                                    <td> ${formatRupiah(Math.round(key.carpreferredincomeparkee))} </td>
                                                    <td> ${formatRupiah(Math.round(key.carpreferredincomediff))} </td>
                                                    <td> ${formatRupiah(Math.round(key.vipramayanaincomecp)+(key.vipnonramayanaincomecp))} </td>
                                                    <td> ${formatRupiah(Math.round(key.vipramayanaincomeparkee)+(key.vipnonramayanaincomeparkee))} </td>
                                                    <td> ${formatRupiah(Math.round(key.vipramayanaincomediff)+(key.vipnonramayanaincomediff))} </td>
                                                    <td> ${formatRupiah(Math.round(key.otherincomecp))} </td>
                                                    <td> ${formatRupiah(Math.round(key.otherincomeparkee))} </td>
                                                    <td> ${formatRupiah(Math.round(key.otherincomediff))} </td>
                                                </tr>`;

                        table_quantity_detail += `<tr>
                                                    <td class="text-left"> ${no_quantity_detail++} </td>
                                                    <td class="text-left"> ${key.periode} </td>
                                                    <td> ${formatRupiah(Math.round(key.carqtycp))} </td>
                                                    <td> ${formatRupiah(Math.round(key.carqtyparkee))} </td>
                                                    <td> ${formatRupiah(Math.round(key.carqtydiff))} </td>
                                                    <td> ${formatRupiah(Math.round(key.motorbikeqtycp))} </td>
                                                    <td> ${formatRupiah(Math.round(key.motorbikeqtyparkee))} </td>
                                                    <td> ${formatRupiah(Math.round(key.motorbikeqtydiff))} </td>
                                                    <td> ${formatRupiah(Math.round(key.truckqtycp))} </td>
                                                    <td> ${formatRupiah(Math.round(key.truckqtyparkee))} </td>
                                                    <td> ${formatRupiah(Math.round(key.truckqtydiff))} </td>
                                                    <td> ${formatRupiah(Math.round(key.taxiqtycp))} </td>
                                                    <td> ${formatRupiah(Math.round(key.taxiqtyparkee))} </td>
                                                    <td> ${formatRupiah(Math.round(key.taxiqtydiff))} </td>
                                                    <td> ${formatRupiah(Math.round(key.valetlobbyqtycp))} </td>
                                                    <td> ${formatRupiah(Math.round(key.valetlobbyqtyparkee))} </td>
                                                    <td> ${formatRupiah(Math.round(key.valetlobbyqtydiff))} </td>
                                                    <td> ${formatRupiah(Math.round(key.valetnonlobbyqtycp))} </td>
                                                    <td> ${formatRupiah(Math.round(key.valetnonlobbyqtyparkee))} </td>
                                                    <td> ${formatRupiah(Math.round(key.valetnonlobbyqtydiff))} </td>
                                                    <td> ${formatRupiah(Math.round(key.carpreferredqtycp))} </td>
                                                    <td> ${formatRupiah(Math.round(key.carpreferredqtyparkee))} </td>
                                                    <td> ${formatRupiah(Math.round(key.carpreferredqtydiff))} </td>
                                                    <td> ${formatRupiah(Math.round(key.vipramayanaqtycp)+(key.vipnonramayanaqtycp))} </td>
                                                    <td> ${formatRupiah(Math.round(key.vipramayanaqtyparkee)+(key.vipnonramayanaqtyparkee))} </td>
                                                    <td> ${formatRupiah(Math.round(key.vipramayanaqtydiff)+(key.vipnonramayanaqtydiff))} </td>
                                                    <td> ${formatRupiah(Math.round(key.otherqtycp))} </td>
                                                    <td> ${formatRupiah(Math.round(key.otherqtyparkee))} </td>
                                                    <td> ${formatRupiah(Math.round(key.otherqtydiff))} </td>
                                                </tr>`;
                    })

                    $('#table-payment').html(table_payment);
                    $('#table-income').html(table_income);
                    $('#table-quantity').html(table_quantity);

                    $('#table-payment-detail').html(table_payment_detail);
                    $('#table-income-detail').html(table_income_detail);
                    $('#table-quantity-detail').html(table_quantity_detail);
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('An error occurred while fetching data.');
                },
                complete: function() {
                    $cariButton.prop('disabled', false).html('Cari');
                }
            });
        });
    </script>
@endsection
