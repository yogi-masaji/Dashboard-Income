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
    </style>
    <h5>membership search</h5>
    <div class="row">
        <div class="col-md-6">
            <div class="row">

                <div class="col-md-6">
                    <select class="form-select" id="memberStatus" aria-label="Default select example">
                        <option selected disabled>--Member Status--</option>
                        <option value="aktif">Member Aktif</option>
                        <option value="nonaktif">Member Nonaktif</option>
                        <option value="period">Member By Period</option>
                    </select>

                </div>
                <div class="col-md-6" id="periodInput" style="display: none;"> <!-- hidden by default -->
                    <input type="text" name="start1" id="start-date-1" class="form-control start-date"
                        placeholder="Select start date" />
                </div>

            </div>
            <div class="mt-3">
                <button type="button" class="btn btn-submit" id="cari">Cari</button>
            </div>
        </div>
    </div>

    <div class="result mt-5">
        <div class="text-center">
            <h5>BCA Membership</h5>
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
                pageLength: 50,
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

                // ✅ Kalau period berubah, harus fetch ulang
                if (aktifData.length > 0 && nonaktifData.length > 0 && period === lastPeriod) {
                    updateTableBySelection(selection);
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
