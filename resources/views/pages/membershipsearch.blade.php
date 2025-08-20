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


    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
    <div id="membershipTable_wrapper" class="dt-container">
        <div class="dt-buttons"></div>
        <div class="dt-search"></div>
    </div>

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

        .dt-search {
            color: #000;
        }

        .mode-gelap .dt-search {
            color: #fff;
        }

        .form-select {
            color: #000000;
        }

        .mode-gelap .form-select {
            color: #fff;
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

        .dt-info {
            color: #000000;
        }

        .mode-gelap .dt-info {
            color: #ffffff;
        }

        /* Container for the table to position the overlay correctly */
        #table-container {
            position: relative;
        }

        /* Loading Overlay Styles */
        #loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #333;
            font-size: 1.2rem;
            border-radius: 10px;
        }

        /* Spinner Styles */
        .spinner {
            display: flex;
            justify-content: space-around;
            width: 70px;
            margin-bottom: 20px;
        }

        .spinner div {
            width: 18px;
            height: 18px;
            background-color: #FCB900;
            border-radius: 50%;
            animation: bounce 1.4s infinite ease-in-out both;
        }

        .spinner .dot1 {
            animation-delay: -0.32s;
        }

        .spinner .dot2 {
            animation-delay: -0.16s;
        }

        .form-label {
            color: #000;
        }

        .mode-gelap .form-label {
            color: #fff;
        }

        @keyframes bounce {

            0%,
            80%,
            100% {
                transform: scale(0);
            }

            40% {
                transform: scale(1.0);
            }
        }
    </style>
    <h5>Membership Search</h5>
    <div class="row g-3 align-items-end">
        <div class="col-md-4">
            <label for="memberStatus" class="form-label">Member Status</label>
            <select class="form-select" id="memberStatus" aria-label="Default select example">
                <option selected disabled>--Select Status--</option>
                <option value="aktif">Member Aktif</option>
                <option value="nonaktif">Member Nonaktif</option>
                <option value="period">Member By Period</option>
            </select>
        </div>
        <div class="col-md-4" id="periodInput" style="display: none;">
            <label for="start-date-1" class="form-label">Select Period</label>
            <input type="text" name="start1" id="start-date-1" class="form-control start-date"
                placeholder="Select start date" />
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-submit w-100" id="cari">Cari</button>
        </div>
    </div>


    <div class="result mt-5">
        <div class="text-center">
            <h5>BCA Membership</h5>
        </div>
        <div id="table-container">
            <div id="loading-overlay" style="display: none;">
                <div class="spinner">
                    <div class="dot1"></div>
                    <div class="dot2"></div>
                    <div class="dot3"></div>
                </div>
                <p>Loading</p>
            </div>
            <table id="membershipTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Plat</th>
                        <th>Jenis Kendaraan</th>
                        <th>Nama Member</th>
                        <th>Nama Produk</th>
                        <th>Masa Berlaku</th>
                        <th>Masa Akhir Berlaku</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.start-date').datepicker({
                autoclose: true,
                minViewMode: 1,
                format: 'mm/yyyy'
            });

            $('#memberStatus').on('change', function() {
                if ($(this).val() === 'period') {
                    $('#periodInput').show();
                } else {
                    $('#periodInput').hide();
                }
            });

            let aktifData = [];
            let nonaktifData = [];
            let lastPeriod = '';
            const TableMembership = $('#membershipTable').DataTable({
                dom: "Bfltip",
                pageLength: 25,
                ordering: true,
                lengthChange: false,
                layout: {
                    topStart: {
                        buttons: [{
                                extend: 'copyHtml5',
                                titleAttr: 'Copy to Clipboard',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'BCA membership data',
                            },
                            {
                                extend: 'excelHtml5',
                                titleAttr: 'Export to Excel',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'BCA membership data',
                            },


                            {
                                extend: 'print',
                                titleAttr: 'Print',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'BCA membership data',
                            },
                            {
                                extend: 'pdfHtml5',
                                titleAttr: 'Export to PDF',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'BCA membership data',
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
                columns: [{
                        data: 'no'
                    },
                    {
                        data: 'no_plat'
                    },
                    {
                        data: 'jenis_kendaraan'
                    },
                    {
                        data: 'nama_member'
                    },
                    {
                        data: 'nama_produk'
                    },
                    {
                        data: 'masa_berlaku'
                    },
                    {
                        data: 'masa_akhir_berlaku'
                    }
                ]
            });

            function formatDataForTable(dataArray) {
                return dataArray.map((item, index) => ({
                    no: index + 1,
                    no_plat: item.noplat,
                    jenis_kendaraan: item.kodevehicle,
                    nama_member: item.deskripsivehicle,
                    nama_produk: item.namaproduk,
                    masa_berlaku: item.berlaku,
                    masa_akhir_berlaku: item.expired
                }));
            }

            $('#cari').click(function() {
                let selection = $('#memberStatus').val();
                let period = $('#start-date-1').val();

                if (!selection) {
                    alert('Please select a member status.');
                    return;
                }

                $('#loading-overlay').show();

                // ✅ Kalau period berubah, harus fetch ulang
                if (aktifData.length > 0 && nonaktifData.length > 0 && period === lastPeriod) {
                    updateTableBySelection(selection);
                    $('#loading-overlay').hide(); // Hide immediately if no fetch is needed
                } else {
                    $.ajax({
                        url: "{{ route('membershipApi') }}",
                        type: 'POST',
                        data: {
                            selection: selection,
                            period: period,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            aktifData = data.aktif || [];
                            nonaktifData = data.nonaktif || [];
                            lastPeriod = period; // ✅ simpan period terbaru yang di-fetch

                            updateTableBySelection(selection);
                        },
                        error: function(xhr) {
                            alert('Failed to fetch data.');
                        },
                        complete: function() {
                            $('#loading-overlay').hide();
                        }
                    });
                }
            });

            function updateTableBySelection(selection) {
                let resultTable = [];

                if (selection === 'aktif') {
                    resultTable = aktifData;
                } else if (selection === 'nonaktif') {
                    resultTable = nonaktifData;
                } else if (selection === 'period') {
                    resultTable = [...aktifData, ...nonaktifData]; // ✅ period = gabungkan semua
                }

                // console.log('Data to display:', resultTable);

                const formattedResult = formatDataForTable(resultTable);
                TableMembership.clear().rows.add(formattedResult).draw();
            }
        });
    </script>
@endsection
