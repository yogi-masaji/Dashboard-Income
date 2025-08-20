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
            border-radius: 10px !important;
            box-shadow: 1px -2px 15px -1px rgba(0, 0, 0, 0.28);
            color: #000000 !important;
        }


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
            margin-top: 5px;
            margin-bottom: 5px;
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

        /* CSS tambahan untuk z-index easepick */
        .easepick-wrapper {
            z-index: 1060;
        }

        .form-select {
            color: #000000;
        }

        .mode-gelap .form-select {
            background-color: #ffffff;
            color: #000000;
        }

        /* Style for clickable chart bars */
        .chart canvas {
            cursor: pointer;
        }
    </style>
    <p class="text-dark"> Peak Search</p>
    <div class="search-wrapper content-custom mb-3">
        <div class="row">
            <div class="col-md-4">
                <label for="datepicker1" class="form-label text-dark">First Period</label>
                <input id="datepicker1" class="form-control" placeholder="Select date range..." />
            </div>
            <div class="col-md-4">
                <label for="datepicker2" class="form-label text-dark">Second Period</label>
                <input id="datepicker2" class="form-control" placeholder="Select date range..." />
            </div>
            <div class="col-md-4">
                <label for="jamType" class="form-label text-dark">Pilih Tipe Jam</label>
                <select id="jamType" class="form-select">
                    <option value="entry">Jam Masuk</option>
                    <option value="exit">Jam Keluar</option>
                </select>
            </div>
        </div>
        <div class="mt-3">
            <button type="button" class="btn btn-submit" id="cari" style="width: 150px;">Cari</button>
        </div>

        <!-- Alert Message -->
        <div id="alertMessage" class="alert alert-danger mt-3" role="alert" style="display: none;">
            Please fill in all the date fields before submitting.
        </div>
    </div>

    <div class="row mt-5">
        <div class="text-center">
            <h5>Car Data Comparison</h5>
        </div>
        <div class="col-md-6">
            <div class="chart content-custom">
                <canvas id="CarFirstPeriodBar"></canvas>
            </div>
            <table id="carFirstPeriod" class="table table-striped mt-3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>kondisi</th>
                        <th>Jumlah Kendaraan</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="col-md-6 ">
            <div class="chart content-custom">
                <canvas id="CarSecondPeriodBar"></canvas>
            </div>
            <table id="carSecondPeriod" class="table table-striped mt-3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>kondisi</th>
                        <th>Jumlah Kendaraan</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>


    <div class="row mt-5">
        <div class="text-center">
            <h5>Motorbike Data Comparison</h5>
        </div>
        <div class="col-md-6">
            <div class="chart content-custom">
                <canvas id="MotorbikeFirstPeriodBar"></canvas>
            </div>
            <table id="motorFirstPeriod" class="table table-striped mt-3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>kondisi</th>
                        <th>Jumlah Kendaraan</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="col-md-6">
            <div class="content-custom">
                <canvas id="motorSecondPeriodBar"></canvas>
            </div>
            <table id="motorSecondPeriod" class="table table-striped mt-3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>kondisi</th>
                        <th>Jumlah Kendaraan</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="row mt-5">
        <div class="text-center">
            <h5>Truck Data Comparison</h5>
        </div>
        <div class="col-md-6">
            <div class="content-custom">
                <canvas id="truckFirstPeriodBar"></canvas>
            </div>
            <table id="truckFirstPeriod" class="table table-striped mt-3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>kondisi</th>
                        <th>Jumlah Kendaraan</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="col-md-6">
            <div class="content-custom">
                <canvas id="truckSecondPeriodBar"></canvas>
            </div>
            <table id="truckSecondPeriod" class="table table-striped mt-3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>kondisi</th>
                        <th>Jumlah Kendaraan</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>


    <div class="row mt-5">
        <div class="text-center">
            <h5>Taxi Data Comparison</h5>
        </div>
        <div class="col-md-6">
            <div class="content-custom">
                <canvas id="taxiFirstPeriodBar"></canvas>
            </div>
            <table id="taxiFirstPeriod" class="table table-striped mt-3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>kondisi</th>
                        <th>Jumlah Kendaraan</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="col-md-6">
            <div class="content-custom">
                <canvas id="taxiSecondPeriodBar"></canvas>
            </div>
            <table id="taxiSecondPeriod" class="table table-striped mt-3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>kondisi</th>
                        <th>Jumlah Kendaraan</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- ================================================================= -->
    <!-- MODAL UNTUK MENAMPILKAN DETAIL PER GATE                           -->
    <!-- ================================================================= -->
    <div class="modal fade" id="gateDetailModal" tabindex="-1" aria-labelledby="gateDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="gateDetailModalLabel">Detail Kuantitas per Gate</h5>
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                </div>
                <div class="modal-body">
                    <p class="mb-1"><strong>Vehicle Type :</strong> <span id="modalVehicleType"></span></p>
                    <p><strong>Jam :</strong> <span id="modalJam"></span></p>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th>Gate</th>
                                    <th>Jumlah Kendaraan</th>
                                </tr>
                            </thead>
                            <tbody id="gateDetailTableBody">
                                <!-- Data akan diisi oleh JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ================================================================= -->
    <!-- END OF MODAL                                                      -->
    <!-- ================================================================= -->


    <script src="https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.1/dist/index.umd.min.js"></script>
    <script>
        $(function() {
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
                format: 'YYYY-MM-DD'
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
                format: 'YYYY-MM-DD'
            });

            // =================================================================
            // VARIABEL GLOBAL BARU
            // =================================================================
            let savedHours = null;
            let savedGateDetails = null; // Untuk menyimpan data detail gate yang sudah di-load
            const isSpecialLocation = ['PMBE', 'GACI', 'BMP'].includes("{{ $kodeLokasi }}");


            // Inisialisasi DataTables
            const TableCarFirstPeriod = $('#carFirstPeriod').DataTable({
                searching: false,
                paging: false,
                autoWidth: false,
                ordering: false,
                info: false,
                layout: {
                    topStart: {
                        buttons: [{
                                extend: 'excelHtml5',
                                titleAttr: 'Export to Excel',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Peak Search | Car Data'
                            },
                            {
                                extend: 'pdfHtml5',
                                titleAttr: 'Export to PDF',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Peak Search | Car Data'
                            },
                        ]
                    }
                },
                data: [],
                columns: [{
                    data: 'no'
                }, {
                    data: 'kondisi'
                }, {
                    data: 'jumlah'
                }]
            });

            const TableCarSecondPeriod = $('#carSecondPeriod').DataTable({
                searching: false,
                paging: false,
                autoWidth: false,
                ordering: false,
                info: false,
                layout: {
                    topStart: {
                        buttons: [{
                                extend: 'excelHtml5',
                                titleAttr: 'Export to Excel',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Peak Search | Car Data'
                            },
                            {
                                extend: 'pdfHtml5',
                                titleAttr: 'Export to PDF',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Peak Search | Car Data'
                            },
                        ]
                    }
                },
                data: [],
                columns: [{
                    data: 'no'
                }, {
                    data: 'kondisi'
                }, {
                    data: 'jumlah'
                }]
            });

            const TableMotorFirstPeriod = $('#motorFirstPeriod').DataTable({
                searching: false,
                paging: false,
                autoWidth: false,
                ordering: false,
                info: false,
                layout: {
                    topStart: {
                        buttons: [{
                                extend: 'excelHtml5',
                                titleAttr: 'Export to Excel',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Peak Search | Motorbike Data'
                            },
                            {
                                extend: 'pdfHtml5',
                                titleAttr: 'Export to PDF',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Peak Search | Motorbike Data'
                            },
                        ]
                    }
                },
                data: [],
                columns: [{
                    data: 'no'
                }, {
                    data: 'kondisi'
                }, {
                    data: 'jumlah'
                }]
            });

            const TableMotorSecondPeriod = $('#motorSecondPeriod').DataTable({
                searching: false,
                paging: false,
                autoWidth: false,
                ordering: false,
                info: false,
                layout: {
                    topStart: {
                        buttons: [{
                                extend: 'excelHtml5',
                                titleAttr: 'Export to Excel',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Peak Search | Motorbike Data'
                            },
                            {
                                extend: 'pdfHtml5',
                                titleAttr: 'Export to PDF',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Peak Search | Motorbike Data'
                            },
                        ]
                    }
                },
                data: [],
                columns: [{
                    data: 'no'
                }, {
                    data: 'kondisi'
                }, {
                    data: 'jumlah'
                }]
            });

            const TableTruckFirstPeriod = $('#truckFirstPeriod').DataTable({
                searching: false,
                paging: false,
                autoWidth: false,
                ordering: false,
                info: false,
                layout: {
                    topStart: {
                        buttons: [{
                                extend: 'excelHtml5',
                                titleAttr: 'Export to Excel',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Peak Search | Truck Data'
                            },
                            {
                                extend: 'pdfHtml5',
                                titleAttr: 'Export to PDF',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Peak Search | Truck Data'
                            },
                        ]
                    }
                },
                data: [],
                columns: [{
                    data: 'no'
                }, {
                    data: 'kondisi'
                }, {
                    data: 'jumlah'
                }]
            });

            const TableTruckSecondPeriod = $('#truckSecondPeriod').DataTable({
                searching: false,
                paging: false,
                autoWidth: false,
                ordering: false,
                info: false,
                layout: {
                    topStart: {
                        buttons: [{
                                extend: 'excelHtml5',
                                titleAttr: 'Export to Excel',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Peak Search | Truck Data'
                            },
                            {
                                extend: 'pdfHtml5',
                                titleAttr: 'Export to PDF',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Peak Search | Truck Data'
                            },
                        ]
                    }
                },
                data: [],
                columns: [{
                    data: 'no'
                }, {
                    data: 'kondisi'
                }, {
                    data: 'jumlah'
                }]
            });

            const TableTaxiFirstPeriod = $('#taxiFirstPeriod').DataTable({
                searching: false,
                paging: false,
                autoWidth: false,
                ordering: false,
                info: false,
                layout: {
                    topStart: {
                        buttons: [{
                                extend: 'excelHtml5',
                                titleAttr: 'Export to Excel',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Peak Search | Taxi Data'
                            },
                            {
                                extend: 'pdfHtml5',
                                titleAttr: 'Export to PDF',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Peak Search | Taxi Data'
                            },
                        ]
                    }
                },
                data: [],
                columns: [{
                    data: 'no'
                }, {
                    data: 'kondisi'
                }, {
                    data: 'jumlah'
                }]
            });

            const TableTaxiSecondPeriod = $('#taxiSecondPeriod').DataTable({
                searching: false,
                paging: false,
                autoWidth: false,
                ordering: false,
                info: false,
                layout: {
                    topStart: {
                        buttons: [{
                                extend: 'excelHtml5',
                                titleAttr: 'Export to Excel',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Peak Search | Taxi Data'
                            },
                            {
                                extend: 'pdfHtml5',
                                titleAttr: 'Export to PDF',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Peak Search | Taxi Data'
                            },
                        ]
                    }
                },
                data: [],
                columns: [{
                    data: 'no'
                }, {
                    data: 'kondisi'
                }, {
                    data: 'jumlah'
                }]
            });


            // =================================================================
            // MODIFIKASI: Event handler untuk tombol 'Cari'
            // =================================================================
            $('#cari').on('click', function() {
                const startDate1 = picker1.getStartDate()?.format('YYYY-MM-DD');
                const endDate1 = picker1.getEndDate()?.format('YYYY-MM-DD');
                const startDate2 = picker2.getStartDate()?.format('YYYY-MM-DD');
                const endDate2 = picker2.getEndDate()?.format('YYYY-MM-DD');

                if (!startDate1 || !endDate1 || !startDate2 || !endDate2) {
                    $('#alertMessage').text('Please select a valid date range for both periods.').show();
                    return;
                }
                $('#alertMessage').hide();

                // Fungsi format tanggal untuk API Peak Search
                const formatDateForBackend = (dateStr) => {
                    if (!dateStr) return null;
                    const [year, month, day] = dateStr.split("-");
                    return `${day}-${month}-${year}`;
                };

                // Kondisi untuk lokasi khusus (PMBE, GACI, BMP)
                if (isSpecialLocation) {
                    // 1. Request untuk Peak Search (data chart)
                    const peakSearchRequest = $.ajax({
                        url: '{{ route('peakSearch') }}',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        contentType: 'application/json',
                        data: JSON.stringify({
                            first_start_date: formatDateForBackend(startDate1),
                            first_end_date: formatDateForBackend(endDate1),
                            second_start_date: formatDateForBackend(startDate2),
                            second_end_date: formatDateForBackend(endDate2),
                            location_code: "{{ $kodeLokasi }}"
                        })
                    });

                    // 2. Request untuk Quantity Per Gate - Periode 1, Tipe IN (Entry)
                    const gateDetailsP1_IN = $.ajax({
                        url: '{{ route('quantitypergatePmbeAPI') }}',
                        method: 'POST',
                        data: {
                            tgl_awal: startDate1,
                            tgl_akhir: endDate1,
                            gate_option: 'IN',
                            _token: '{{ csrf_token() }}'
                        }
                    });

                    // 3. Request untuk Quantity Per Gate - Periode 1, Tipe OUT (Exit)
                    const gateDetailsP1_OUT = $.ajax({
                        url: '{{ route('quantitypergatePmbeAPI') }}',
                        method: 'POST',
                        data: {
                            tgl_awal: startDate1,
                            tgl_akhir: endDate1,
                            gate_option: 'OUT',
                            _token: '{{ csrf_token() }}'
                        }
                    });

                    // 4. Request untuk Quantity Per Gate - Periode 2, Tipe IN (Entry)
                    const gateDetailsP2_IN = $.ajax({
                        url: '{{ route('quantitypergatePmbeAPI') }}',
                        method: 'POST',
                        data: {
                            tgl_awal: startDate2,
                            tgl_akhir: endDate2,
                            gate_option: 'IN',
                            _token: '{{ csrf_token() }}'
                        }
                    });

                    // 5. Request untuk Quantity Per Gate - Periode 2, Tipe OUT (Exit)
                    const gateDetailsP2_OUT = $.ajax({
                        url: '{{ route('quantitypergatePmbeAPI') }}',
                        method: 'POST',
                        data: {
                            tgl_awal: startDate2,
                            tgl_akhir: endDate2,
                            gate_option: 'OUT',
                            _token: '{{ csrf_token() }}'
                        }
                    });

                    // Menjalankan semua request secara paralel
                    $.when(peakSearchRequest, gateDetailsP1_IN, gateDetailsP1_OUT, gateDetailsP2_IN,
                            gateDetailsP2_OUT)
                        .done(function(peakRes, p1InRes, p1OutRes, p2InRes, p2OutRes) {
                            // Menyimpan hasil dari semua API
                            savedHours = peakRes[0].data[0];
                            savedGateDetails = {
                                first_period: {
                                    entry: p1InRes[0].data,
                                    exit: p1OutRes[0].data
                                },
                                second_period: {
                                    entry: p2InRes[0].data,
                                    exit: p2OutRes[0].data
                                }
                            };
                            console.log("All data loaded and saved:", {
                                savedHours,
                                savedGateDetails
                            });
                            showJamBasedOnType(); // Render chart setelah semua data siap
                        })
                        .fail(function(xhr, status, error) {
                            console.error("Error during parallel AJAX requests:", error);
                            $('#alertMessage').text('Error fetching data. Please try again.').show();
                        });

                } else {
                    // Logika original untuk lokasi lain
                    $.ajax({
                        url: '{{ route('peakSearch') }}',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        contentType: 'application/json',
                        data: JSON.stringify({
                            first_start_date: formatDateForBackend(startDate1),
                            first_end_date: formatDateForBackend(endDate1),
                            second_start_date: formatDateForBackend(startDate2),
                            second_end_date: formatDateForBackend(endDate2),
                            location_code: "{{ $kodeLokasi }}"
                        }),
                        success: function(response) {
                            console.log("Laravel Controller Response:", response);
                            savedHours = response.data[0];
                            showJamBasedOnType();
                        },
                        error: function(xhr, status, error) {
                            console.error("Error from Laravel:", error);
                            $('#alertMessage').text('Error fetching data. Please try again.')
                                .show();
                        }
                    });
                }
            });


            $('#jamType').on('change', function() {
                showJamBasedOnType();
            });

            let mobilFirstPeriodChart = null;
            let mobilSecondPeriodChart = null;
            let motorbikeFirstPeriodChart = null;
            let motorbikeSecondPeriodChart = null;
            let truckFirstPeriodChart = null;
            let truckSecondPeriodChart = null;
            let taxiFirstPeriodChart = null;
            let taxiSecondPeriodChart = null;

            function formatTimeInterval(interval) {
                if (!interval) return '';
                const [start, end] = interval.split("-");
                const startFormatted = start.slice(0, 5);
                const endFormatted = end.slice(0, 5);
                return `${startFormatted}-${endFormatted}`;
            }

            // =================================================================
            // FUNGSI BARU: Menampilkan detail dari data yang sudah di-load (cache)
            // =================================================================
            function showGateDetailsFromCache(vehicleType, timeInterval, period) {
                $('#modalVehicleType').text(vehicleType);
                $('#modalJam').text(timeInterval);

                const tableBody = $('#gateDetailTableBody');
                tableBody.empty();

                const gateDetailModal = new bootstrap.Modal(document.getElementById('gateDetailModal'));
                gateDetailModal.show();

                const jamType = $('#jamType').val(); // 'entry' or 'exit'

                if (!savedGateDetails) {
                    tableBody.html(
                        '<tr><td colspan="3" class="text-center text-danger">Gate detail data not loaded.</td></tr>'
                    );
                    return;
                }

                const periodData = savedGateDetails[period];
                if (!periodData) {
                    tableBody.html('<tr><td colspan="3" class="text-center">No data for this period.</td></tr>');
                    return;
                }
                const responseData = periodData[jamType];
                if (!responseData) {
                    tableBody.html(
                        '<tr><td colspan="3" class="text-center">No data for this gate type (IN/OUT).</td></tr>'
                    );
                    return;
                }

                const vehicleTypeMap = {
                    'Car': 'car',
                    'Motorbike': 'motorcycle',
                    'Truck': 'truck',
                    'Taxi': 'taxi'
                };
                const apiVehicleKey = vehicleTypeMap[vehicleType] || vehicleType.toLowerCase();

                // =================================================================
                // PERBAIKAN: Logika untuk membuat timeKey yang benar
                // =================================================================
                const startHourStr = timeInterval.substring(0, 2);
                const startHour = parseInt(startHourStr, 10);
                let endHour;

                if (startHour === 23) {
                    endHour = '00';
                } else {
                    endHour = (startHour + 1).toString().padStart(2, '0');
                }
                const timeKey = `${startHourStr}-${endHour}`;
                // =================================================================

                if (responseData[apiVehicleKey]) {
                    const vehicleData = responseData[apiVehicleKey];
                    let counter = 1;
                    let hasData = false;

                    vehicleData.forEach(gateObject => {
                        const gateName = Object.keys(gateObject)[0];
                        const hourlyData = gateObject[gateName];
                        const quantity = hourlyData[timeKey];

                        if (quantity !== undefined) {
                            hasData = true;
                            const row = `<tr>
                                <td>${counter++}</td>
                                <td>${gateName}</td>
                                <td>${quantity}</td>
                            </tr>`;
                            tableBody.append(row);
                        }
                    });

                    if (!hasData) {
                        tableBody.html(
                            '<tr><td colspan="3" class="text-center">No data available for this time slot.</td></tr>'
                        );
                    }
                } else {
                    tableBody.html(
                        '<tr><td colspan="3" class="text-center">No data available for this vehicle type.</td></tr>'
                    );
                }
            }


            // =================================================================
            // MODIFIKASI: Event onClick pada chart
            // =================================================================
            function createOrUpdateChart(canvasId, chartInstance, chartData, chartLabel, vehicleType, period) {
                const ctx = document.getElementById(canvasId)?.getContext('2d');
                if (!ctx) return;

                if (chartInstance) {
                    chartInstance.destroy();
                }

                const colors = [
                    '#D10300', '#9966FF', '#7E00F5', '#36A2EB', '#00FFFF', '#FF6347',
                    '#E67E00', '#F4A460', '#FFCE56', '#90EE90', '#148F49', '#708090',
                    '#DAB370', '#F8AD2B', '#DFC639', '#E3E32A', '#00943E', '#0E17C4',
                    '#057385', '#101A9F', '#4F236E', '#634E32', '#C233EE', '#BC8F8F'
                ];
                const backgroundColors = chartData.labels.map((_, index) => colors[index % colors.length]);

                const config = {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            label: chartLabel,
                            data: chartData.data,
                            backgroundColor: backgroundColors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        onClick: (event, elements) => {
                            // Hanya jalankan jika lokasi spesial dan ada bar yang diklik
                            if (isSpecialLocation && elements.length > 0) {
                                const chartElement = elements[0];
                                const index = chartElement.index;
                                const timeInterval = chartInstance.data.labels[index];

                                showGateDetailsFromCache(vehicleType, timeInterval, period);
                            }
                        },
                        plugins: {
                            legend: {
                                labels: {
                                    color: '#000'
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: '#000'
                                }
                            },
                            x: {
                                ticks: {
                                    color: '#000'
                                }
                            }
                        }
                    }
                };

                const newChartInstance = new Chart(ctx, config);
                chartInstance = newChartInstance;
                return newChartInstance;
            }

            function showJamBasedOnType() {
                if (!savedHours) {
                    console.warn("Belum ada data, klik tombol 'Cari' dulu.");
                    return;
                }

                const selectedJamType = $('#jamType').val();
                const jamList = selectedJamType === 'entry' ?
                    savedHours.parking_entry_hours[0] :
                    savedHours.parking_exit_hours[0];

                if (!jamList) {
                    console.warn("Data jam tidak tersedia.");
                    return;
                }

                const processVehicleData = (vehicleData) => {
                    if (!vehicleData) return {
                        table: [],
                        chart: {
                            labels: [],
                            data: []
                        }
                    };
                    const tableData = vehicleData.map((jam, index) => ({
                        no: index + 1,
                        kondisi: formatTimeInterval(jam.time_interval),
                        jumlah: jam.vehicle_quantity
                    }));
                    const chartData = {
                        labels: vehicleData.map(jam => formatTimeInterval(jam.time_interval)),
                        data: vehicleData.map(jam => jam.vehicle_quantity)
                    };
                    return {
                        table: tableData,
                        chart: chartData
                    };
                };

                const carFirst = processVehicleData(jamList.first_period?.[0]?.car);
                const carSecond = processVehicleData(jamList.second_period?.[0]?.car);
                const motorFirst = processVehicleData(jamList.first_period?.[1]?.motorbike);
                const motorSecond = processVehicleData(jamList.second_period?.[1]?.motorbike);
                const truckFirst = processVehicleData(jamList.first_period?.[2]?.truck);
                const truckSecond = processVehicleData(jamList.second_period?.[2]?.truck);
                const taxiFirst = processVehicleData(jamList.first_period?.[3]?.taxi);
                const taxiSecond = processVehicleData(jamList.second_period?.[3]?.taxi);

                // Update Tables
                TableCarFirstPeriod.clear().rows.add(carFirst.table).draw();
                TableCarSecondPeriod.clear().rows.add(carSecond.table).draw();
                TableMotorFirstPeriod.clear().rows.add(motorFirst.table).draw();
                TableMotorSecondPeriod.clear().rows.add(motorSecond.table).draw();
                TableTruckFirstPeriod.clear().rows.add(truckFirst.table).draw();
                TableTruckSecondPeriod.clear().rows.add(truckSecond.table).draw();
                TableTaxiFirstPeriod.clear().rows.add(taxiFirst.table).draw();
                TableTaxiSecondPeriod.clear().rows.add(taxiSecond.table).draw();

                mobilFirstPeriodChart = createOrUpdateChart('CarFirstPeriodBar', mobilFirstPeriodChart, carFirst
                    .chart, 'Car First Period', 'Car', 'first_period');
                mobilSecondPeriodChart = createOrUpdateChart('CarSecondPeriodBar', mobilSecondPeriodChart, carSecond
                    .chart, 'Car Second Period', 'Car', 'second_period');

                motorbikeFirstPeriodChart = createOrUpdateChart('MotorbikeFirstPeriodBar',
                    motorbikeFirstPeriodChart, motorFirst.chart, 'Motorbike First Period', 'Motorbike',
                    'first_period');
                motorbikeSecondPeriodChart = createOrUpdateChart('motorSecondPeriodBar', motorbikeSecondPeriodChart,
                    motorSecond.chart, 'Motorbike Second Period', 'Motorbike', 'second_period');

                truckFirstPeriodChart = createOrUpdateChart('truckFirstPeriodBar', truckFirstPeriodChart, truckFirst
                    .chart, 'Truck First Period', 'Truck', 'first_period');
                truckSecondPeriodChart = createOrUpdateChart('truckSecondPeriodBar', truckSecondPeriodChart,
                    truckSecond.chart, 'Truck Second Period', 'Truck', 'second_period');

                taxiFirstPeriodChart = createOrUpdateChart('taxiFirstPeriodBar', taxiFirstPeriodChart, taxiFirst
                    .chart, 'Taxi First Period', 'Taxi', 'first_period');
                taxiSecondPeriodChart = createOrUpdateChart('taxiSecondPeriodBar', taxiSecondPeriodChart, taxiSecond
                    .chart, 'Taxi Second Period', 'Taxi', 'second_period');
            }
        });
    </script>
@endsection
