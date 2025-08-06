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
        .table> :not(caption)>*>* {
            padding: 10px 2px;
            color: var(--bs-table-color-state, var(--bs-table-color-type, var(--bs-table-color)));
            background-color: var(--bs-table-bg);
            border-bottom-width: var(--bs-border-width);
            box-shadow: inset 0 0 0 9999px var(--bs-table-bg-state, var(--bs-table-bg-type, var(--bs-table-accent-bg)));
        }

        .sub-total-row {
            --bs-table-accent-bg: #050e1d !important;
        }

        table.dataTable tbody th,
        table.dataTable tbody td {
            padding: 8px 0px;
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

        /* table.dataTable thead th,
                                                                                                                                                                                                                                                                                                    table.dataTable thead td {
                                                                                                                                                                                                                                                                                                        padding: 16px;
                                                                                                                                                                                                                                                                                                        border-bottom: 1px solid #111
                                                                                                                                                                                                                                                                                                    } */

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
        }

        #dt-search-0 {
            height: 40px;
            border-radius: 10px;
            margin-left: 10px;
        }
    </style>
    <div class="search-wrapper">
        <div class="d-flex align-items-end gap-3 mb-3">
            <div>
                <label for="start-date-1" class="form-label text-dark">Start Date</label>
                <input type="text" name="start1" id="start-date-1" class="form-control" placeholder="Select start date" />
            </div>
            <div class="pb-3 fw-semibold">to</div>
            <div>
                <label for="end-date-1" class="form-label text-dark">End Date</label>
                <input type="text" name="end1" id="end-date-1" class="form-control" placeholder="Select end date" />
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
    <table class="table table-striped" id="table-custom">
        <br>
        <br>
        <thead>

            <tr>
                <!-- <th scope="col" rowspan="2" class="text-center" style="border-left: 1px solid white; border-top: 1px solid white; vertical-align: middle; width:4%;">NO</th> -->
                <th scope="col" rowspan="2" class="text-center"
                    style="border-left: 1px solid white; border-top: 1px solid white; border-right: 1px solid white; vertical-align: middle; width:13%;">
                    POS</th>
                <th scope="col" rowspan="2" class="text-center"
                    style=" border-top: 1px solid white; border-right: 1px solid white; vertical-align: middle; width:6%;">
                    LANE</th>
                <th scope="col" colspan="3" class="text-center"
                    style="border-top: 1px solid white; border-right: 1px solid white;">MOTOR</th>
                <th scope="col" colspan="3" class="text-center"
                    style="border-top: 1px solid white; border-right: 1px solid white;">MOBIL</th>
                <th scope="col" colspan="3" class="text-center"
                    style="border-top: 1px solid white; border-right: 1px solid white;">TRUK</th>
                <th scope="col" rowspan="2" class="text-center"
                    style="border-top: 1px solid white; border-right: 1px solid white; width:7%; vertical-align: middle;">
                    TOTAL PENDAPATAN</th>

            </tr>


            <tr>
                <th scope="col" class="text-center" style="border-right: 1px solid white;">PRODUKSI</th>
                <th scope="col" class="text-center" style="border-right: 1px solid white;">TARIF</th>
                <th scope="col" class="text-center" style="border-right: 1px solid white;">PENDAPATAN</th>
                <th scope="col" class="text-center" style="border-right: 1px solid white;">PRODUKSI</th>
                <th scope="col" class="text-center" style="border-right: 1px solid white;">TARIF</th>
                <th scope="col" class="text-center" style="border-right: 1px solid white;">PENDAPATAN</th>
                <th scope="col" class="text-center" style="border-right: 1px solid white;">PRODUKSI</th>
                <th scope="col" class="text-center" style="border-right: 1px solid white;">TARIF</th>
                <th scope="col" class="text-center" style="border-right: 1px solid white;">PENDAPATAN</th>
            </tr>
        </thead>
        <tbody id="tbody-custom">


        </tbody>
    </table>



    <script>
        const dateInputs = [{
            start: '#start-date-1',
            end: '#end-date-1'
        }];

        dateInputs.forEach(pair => {
            $(pair.start).daterangepicker({
                singleDatePicker: true,
                autoApply: true,
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            }).on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD'));
            });

            $(pair.end).daterangepicker({
                singleDatePicker: true,
                autoApply: true,
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            }).on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD'));
            });
        });
    </script>


    <script>
        const tableCustom = $('#table-custom').DataTable({
            lengthChange: false,
            searching: false,
            paging: false,
            ordering: false,
            info: false,
            layout: {
                topEnd: {
                    buttons: ['excelHtml5',
                        {
                            extend: 'pdfHtml5',
                            customize: function(doc) {

                                var bodyHtml = $('#table-custom').html();
                                doc.content[1].table.body = [];

                            }
                        }
                    ],
                },
            },
        });
        $('#cari').click(function() {
            const startDate = $('#start-date-1').val();
            const endDate = $('#end-date-1').val();
            const $cariButton = $(this);

            if (!startDate || !endDate) {
                $('#alertMessage').show();
                return;
            } else {
                $('#alertMessage').hide();
            }

            // Disable button and show loading text
            $cariButton.prop('disabled', true).html('Loading...');



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
            $.ajax({
                url: '{{ route('incomePelindoSearchAPI') }}',
                method: 'POST',
                data: {
                    start1: startDate,
                    end1: endDate,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        console.log(response.data);
                        const buffer = response.data.buffer.buffer_line[0];
                        const pos1Lane1 = response.data.pos1.pos1_line1[0];
                        const pos1Lane2 = response.data.pos1.pos1_line2[0];
                        const pos1Lane3 = response.data.pos1.pos1_line3[0];
                        const pos8Lane1 = response.data.pos8.pos8_line1[0];
                        const pos8Lane2 = response.data.pos8.pos8_line2[0];
                        const pos8Lane3 = response.data.pos8.pos8_line3[0];
                        const pos8Lane4 = response.data.pos8.pos8_line4[0];
                        const pos8Lane5 = response.data.pos8.pos8_line5[0];
                        const pos8Lane6 = response.data.pos8.pos8_line6[0];
                        const pos9Lane1 = response.data.pos9.pos9_line1[0];
                        const pos9Lane2 = response.data.pos9.pos9_line2[0];
                        const pos9Lane3 = response.data.pos9.pos9_line3[0];
                        const pos9Lane4 = response.data.pos9.pos9_line4[0];


                        console.log(pos1Lane1);

                        const subtotalbuffer = buffer.prod_motor +
                            console.log(buffer.prod_motor);
                        $('#tbody-custom').empty();
                        $('#tbody-custom').append(`
                                <tr>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">Baffer Area</td>
                <td class="text-center" style="border-right: 1px solid white;">1</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(buffer.prod_motor)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(buffer.tarif_motor)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(buffer.pend_motor)}</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(buffer.prod_mobil)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(buffer.tarif_mobil)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(buffer.pend_mobil)}</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(buffer.prod_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(buffer.tarif_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(buffer.pend_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(buffer.total)}</td>
            </tr>

            <tr class="sub-total-row">
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;" colspan="2">SUB TOTAL BUFFER AREA</td>
               
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(buffer.prod_motor)}</td>
                <td class="text-center" style="border-right: 1px solid white;">-</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(buffer.pend_motor)}</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(buffer.prod_mobil)}</td>
                <td class="text-center" style="border-right: 1px solid white;">-</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(buffer.pend_mobil)}</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(buffer.prod_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">-</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(buffer.pend_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(buffer.total)}</td>
            </tr>
                
            <tr>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">POS 1</td>
                <td class="text-center" style="border-right: 1px solid white;">1</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos1Lane1.prod_motor)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos1Lane1.tarif_motor)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos1Lane1.pend_motor)}</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos1Lane1.prod_mobil)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos1Lane1.tarif_mobil)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos1Lane1.pend_mobil)}</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos1Lane1.prod_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos1Lane1.tarif_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos1Lane1.pend_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos1Lane1.total)}</td>
            </tr>
            <tr>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">POS 1</td>
                <td class="text-center" style="border-right: 1px solid white;">2</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos1Lane2.prod_motor)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos1Lane2.tarif_motor)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos1Lane2.pend_motor)}</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos1Lane2.prod_mobil)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos1Lane2.tarif_mobil)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos1Lane2.pend_mobil)}</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos1Lane2.prod_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos1Lane2.tarif_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos1Lane2.pend_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos1Lane2.total)}</td>
            </tr>
            <tr>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">POS 1</td>
                <td class="text-center" style="border-right: 1px solid white;">3</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos1Lane3.prod_motor)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos1Lane3.tarif_motor)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos1Lane3.pend_motor)}</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos1Lane3.prod_mobil)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos1Lane3.tarif_mobil)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos1Lane3.pend_mobil)}</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos1Lane3.prod_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos1Lane3.tarif_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos1Lane3.pend_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos1Lane3.total)}</td>
            </tr>

            <tr class="sub-total-row">
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;" colspan="2">Sub Total Pos 1</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos1Lane1.prod_motor + pos1Lane2.prod_motor + pos1Lane3.prod_motor)}</td>
                <td class="text-center" style="border-right: 1px solid white;">-</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos1Lane1.pend_motor + pos1Lane2.pend_motor + pos1Lane3.pend_motor)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos1Lane1.prod_mobil + pos1Lane2.prod_mobil + pos1Lane3.prod_mobil)}</td>
                <td class="text-center" style="border-right: 1px solid white;">-</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos1Lane1.pend_mobil + pos1Lane2.pend_mobil + pos1Lane3.pend_mobil)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos1Lane1.prod_truck + pos1Lane2.prod_truck + pos1Lane3.prod_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">-</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos1Lane1.pend_truck + pos1Lane2.pend_truck + pos1Lane3.pend_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos1Lane1.total + pos1Lane2.total + pos1Lane3.total)}</td>
            </tr>
            
            <tr>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">POS 8</td>
                <td class="text-center" style="border-right: 1px solid white;">1</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos8Lane1.prod_motor)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos8Lane1.tarif_motor)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos8Lane1.pend_motor)}</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos8Lane1.prod_mobil)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos8Lane1.tarif_mobil)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos8Lane1.pend_mobil)}</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos8Lane1.prod_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos8Lane1.tarif_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos8Lane1.pend_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos8Lane1.total)}</td>
            </tr>

            <tr>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">POS 8</td>
                <td class="text-center" style="border-right: 1px solid white;">2</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos8Lane2.prod_motor)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos8Lane2.tarif_motor)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos8Lane2.pend_motor)}</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos8Lane2.prod_mobil)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos8Lane2.tarif_mobil)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos8Lane2.pend_mobil)}</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos8Lane2.prod_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos8Lane2.tarif_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos8Lane2.pend_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos8Lane2.total)}</td>
            </tr>

            <tr>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">POS 8</td>
                <td class="text-center" style="border-right: 1px solid white;">3</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos8Lane3.prod_motor)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos8Lane3.tarif_motor)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos8Lane3.pend_motor)}</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos8Lane3.prod_mobil)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos8Lane3.tarif_mobil)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos8Lane3.pend_mobil)}</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos8Lane3.prod_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos8Lane3.tarif_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos8Lane3.pend_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos8Lane3.total)}</td>
            </tr>

            <tr>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">POS 8</td>
                <td class="text-center" style="border-right: 1px solid white;">4</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos8Lane4.prod_motor)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos8Lane4.tarif_motor)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos8Lane4.pend_motor)}</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos8Lane4.prod_mobil)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos8Lane4.tarif_mobil)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos8Lane4.pend_mobil)}</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos8Lane4.prod_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos8Lane4.tarif_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos8Lane4.pend_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos8Lane4.total)}</td>
            </tr>

            <tr>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">POS 8</td>
                <td class="text-center" style="border-right: 1px solid white;">5</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos8Lane5.prod_motor)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos8Lane5.tarif_motor)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos8Lane5.pend_motor)}</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos8Lane5.prod_mobil)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos8Lane5.tarif_mobil)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos8Lane5.pend_mobil)}</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos8Lane5.prod_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos8Lane5.tarif_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos8Lane5.pend_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos8Lane5.total)}</td>
            </tr>

            <tr>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">POS 8</td>
                <td class="text-center" style="border-right: 1px solid white;">6</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos8Lane6.prod_motor)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos8Lane6.tarif_motor)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos8Lane6.pend_motor)}</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos8Lane6.prod_mobil)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos8Lane6.tarif_mobil)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos8Lane6.pend_mobil)}</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos8Lane6.prod_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos8Lane6.tarif_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos8Lane6.pend_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos8Lane6.total)}</td>
            </tr>

            <tr class="sub-total-row">
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;" colspan="2">Sub Total Pos 8</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos8Lane1.prod_motor + pos8Lane2.prod_motor + pos8Lane3.prod_motor + pos8Lane4.prod_motor + pos8Lane5.prod_motor + pos8Lane6.prod_motor)}</td>
                <td class="text-center" style="border-right: 1px solid white;">-</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos8Lane1.pend_motor + pos8Lane2.pend_motor + pos8Lane3.pend_motor + pos8Lane4.pend_motor + pos8Lane5.pend_motor + pos8Lane6.pend_motor)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos8Lane1.prod_mobil + pos8Lane2.prod_mobil + pos8Lane3.prod_mobil + pos8Lane4.prod_mobil + pos8Lane5.prod_mobil + pos8Lane6.prod_mobil)}</td>
                <td class="text-center" style="border-right: 1px solid white;">-</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos8Lane1.pend_mobil + pos8Lane2.pend_mobil + pos8Lane3.pend_mobil + pos8Lane4.pend_mobil + pos8Lane5.pend_mobil + pos8Lane6.pend_mobil)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos8Lane1.prod_truck + pos8Lane2.prod_truck + pos8Lane3.prod_truck + pos8Lane4.prod_truck + pos8Lane5.prod_truck + pos8Lane6.prod_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">-</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos8Lane1.pend_truck + pos8Lane2.pend_truck + pos8Lane3.pend_truck + pos8Lane4.pend_truck + pos8Lane5.pend_truck + pos8Lane6.pend_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos8Lane1.total + pos8Lane2.total + pos8Lane3.total + pos8Lane4.total + pos8Lane5.total + pos8Lane6.total)}</td>
            </tr>

            <tr>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">POS 9</td>
                <td class="text-center" style="border-right: 1px solid white;">1</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos9Lane1.prod_motor)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos9Lane1.tarif_motor)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos9Lane1.pend_motor)}</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos9Lane1.prod_mobil)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos9Lane1.tarif_mobil)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos9Lane1.pend_mobil)}</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos9Lane1.prod_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos9Lane1.tarif_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos9Lane1.pend_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos9Lane1.total)}</td>
            </tr>
            <tr>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">POS 9</td>
                <td class="text-center" style="border-right: 1px solid white;">2</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos9Lane2.prod_motor)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos9Lane2.tarif_motor)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos9Lane2.pend_motor)}</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos9Lane2.prod_mobil)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos9Lane2.tarif_mobil)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos9Lane2.pend_mobil)}</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos9Lane2.prod_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos9Lane2.tarif_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos9Lane2.pend_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos9Lane2.total)}</td>
            </tr>
            <tr>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">POS 9</td>
                <td class="text-center" style="border-right: 1px solid white;">3</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos9Lane3.prod_motor)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos9Lane3.tarif_motor)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos9Lane3.pend_motor)}</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos9Lane3.prod_mobil)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos9Lane3.tarif_mobil)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos9Lane3.pend_mobil)}</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos9Lane3.prod_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos9Lane3.tarif_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos9Lane3.pend_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos9Lane3.total)}</td>
            </tr>
            <tr>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">POS 9</td>
                <td class="text-center" style="border-right: 1px solid white;">4</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos9Lane4.prod_motor)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos9Lane4.tarif_motor)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos9Lane4.pend_motor)}</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos9Lane4.prod_mobil)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos9Lane4.tarif_mobil)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos9Lane4.pend_mobil)}</td>
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">${formatQuantity(pos9Lane4.prod_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos9Lane4.tarif_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos9Lane4.pend_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos9Lane4.total)}</td>
            </tr>
            <tr class="sub-total-row">
                <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;" colspan="2">Sub Total Pos 9</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos9Lane1.prod_motor + pos9Lane2.prod_motor + pos9Lane3.prod_motor + pos9Lane4.prod_motor)}</td>
                <td class="text-center" style="border-right: 1px solid white;">-</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos9Lane1.pend_motor + pos9Lane2.pend_motor + pos9Lane3.pend_motor + pos9Lane4.pend_motor)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos9Lane1.prod_mobil + pos9Lane2.prod_mobil + pos9Lane3.prod_mobil + pos9Lane4.prod_mobil)}</td>
                <td class="text-center" style="border-right: 1px solid white;">-</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos9Lane1.pend_mobil + pos9Lane2.pend_mobil + pos9Lane3.pend_mobil + pos9Lane4.pend_mobil)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatQuantity(pos9Lane1.prod_truck + pos9Lane2.prod_truck + pos9Lane3.prod_truck + pos9Lane4.prod_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">-</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos9Lane1.pend_truck + pos9Lane2.pend_truck + pos9Lane3.pend_truck + pos9Lane4.pend_truck)}</td>
                <td class="text-center" style="border-right: 1px solid white;">${formatRupiah(pos9Lane1.total + pos9Lane2.total + pos9Lane3.total + pos9Lane4.total)}</td>
            </tr>
            <tr class="sub-total-row">
    <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;" colspan="2">Grand Total</td>
                    <td class="text-center" style="border-right: 1px solid white;">
                        ${formatQuantity(
                            pos1Lane1.prod_motor + pos1Lane2.prod_motor + pos1Lane3.prod_motor +
                            pos8Lane1.prod_motor + pos8Lane2.prod_motor + pos8Lane3.prod_motor + 
                            pos8Lane4.prod_motor + pos8Lane5.prod_motor + pos8Lane6.prod_motor + 
                            buffer.prod_motor
                        )}
                    </td>
                    <td class="text-center" style="border-right: 1px solid white;">-</td>
                    <td class="text-center" style="border-right: 1px solid white;">
                        ${formatRupiah(
                            pos1Lane1.pend_motor + pos1Lane2.pend_motor + pos1Lane3.pend_motor + 
                            pos8Lane1.pend_motor + pos8Lane2.pend_motor + pos8Lane3.pend_motor + 
                            pos8Lane4.pend_motor + pos8Lane5.pend_motor + pos8Lane6.pend_motor + 
                            buffer.pend_motor
                        )}
                    </td>
                    <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">
                        ${formatQuantity(
                            pos1Lane1.prod_mobil + pos1Lane2.prod_mobil + pos1Lane3.prod_mobil +
                            pos8Lane1.prod_mobil + pos8Lane2.prod_mobil + pos8Lane3.prod_mobil + 
                            pos8Lane4.prod_mobil + pos8Lane5.prod_mobil + pos8Lane6.prod_mobil + 
                            buffer.prod_mobil
                        )}
                    </td>
                    <td class="text-center" style="border-right: 1px solid white;">-</td>
                    <td class="text-center" style="border-right: 1px solid white;">
                        ${formatRupiah(
                            pos1Lane1.pend_mobil + pos1Lane2.pend_mobil + pos1Lane3.pend_mobil + 
                            pos8Lane1.pend_mobil + pos8Lane2.pend_mobil + pos8Lane3.pend_mobil + 
                            pos8Lane4.pend_mobil + pos8Lane5.pend_mobil + pos8Lane6.pend_mobil + 
                            buffer.pend_mobil
                        )}
                    </td>
                    <td class="text-center" style="border-left: 1px solid white; border-right: 1px solid white;">
                        ${formatQuantity(
                            pos1Lane1.prod_truck + pos1Lane2.prod_truck + pos1Lane3.prod_truck + 
                            pos8Lane1.prod_truck + pos8Lane2.prod_truck + pos8Lane3.prod_truck + 
                            pos8Lane4.prod_truck + pos8Lane5.prod_truck + pos8Lane6.prod_truck + 
                            buffer.prod_truck
                        )}
                    </td>
                    <td class="text-center" style="border-right: 1px solid white;">-</td>
                    <td class="text-center" style="border-right: 1px solid white;">
                        ${formatRupiah(
                            pos1Lane1.pend_truck + pos1Lane2.pend_truck + pos1Lane3.pend_truck + 
                            pos8Lane1.pend_truck + pos8Lane2.pend_truck + pos8Lane3.pend_truck + 
                            pos8Lane4.pend_truck + pos8Lane5.pend_truck + pos8Lane6.pend_truck + 
                            buffer.pend_truck
                        )}
                    </td>
                    <td class="text-center" style="border-right: 1px solid white;">
                        ${formatRupiah(
                            pos1Lane1.total + pos1Lane2.total + pos1Lane3.total +
                            pos8Lane1.total + pos8Lane2.total + pos8Lane3.total + 
                            pos8Lane4.total + pos8Lane5.total + pos8Lane6.total + 
                            buffer.total
                        )}
                    </td>
                </tr>




                        `);
                    } else {
                        alert('No data found!');
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('An error occurred while fetching data.');
                },
                complete: function() {
                    // Re-enable button and reset text after AJAX (success or error)
                    $cariButton.prop('disabled', false).html('Cari');
                }
            });
        });
    </script>
@endsection
