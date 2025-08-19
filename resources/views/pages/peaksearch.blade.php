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

            let savedHours = null; // variabel global buat simpan data hasil fetch

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


            $('#cari').on('click', function() {
                const startDate1 = picker1.getStartDate() ? picker1.getStartDate().format('YYYY-MM-DD') :
                    null;
                const endDate1 = picker1.getEndDate() ? picker1.getEndDate().format('YYYY-MM-DD') : null;
                const startDate2 = picker2.getStartDate() ? picker2.getStartDate().format('YYYY-MM-DD') :
                    null;
                const endDate2 = picker2.getEndDate() ? picker2.getEndDate().format('YYYY-MM-DD') : null;

                if (!startDate1 || !endDate1 || !startDate2 || !endDate2) {
                    $('#alertMessage').text('Please select a valid date range for both periods.').show();
                    return;
                } else {
                    $('#alertMessage').hide();
                }

                const formatDateForBackend = (dateStr) => {
                    if (!dateStr) return null;
                    const [year, month, day] = dateStr.split("-");
                    return `${day}-${month}-${year}`;
                };

                const data = {
                    first_start_date: formatDateForBackend(startDate1),
                    first_end_date: formatDateForBackend(endDate1),
                    second_start_date: formatDateForBackend(startDate2),
                    second_end_date: formatDateForBackend(endDate2),
                    location_code: "{{ $kodeLokasi }}"
                };

                console.log("Sending data:", data);

                $.ajax({
                    url: '{{ route('peakSearch') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    contentType: 'application/json',
                    data: JSON.stringify(data),
                    success: function(response) {
                        console.log("Laravel Controller Response:", response);
                        savedHours = response.data[0]; // simpan di global
                        showJamBasedOnType
                            (); // tampilkan data berdasarkan tipe jam yang dipilih
                    },
                    error: function(xhr, status, error) {
                        console.error("Error from Laravel:", error);
                        $('#alertMessage').text('Error fetching data. Please try again.')
                            .show();
                    }
                });
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

            function createOrUpdateChart(canvasId, chartInstance, chartData, chartLabel) {
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

                return new Chart(ctx, config);
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

                // Data processing for tables and charts
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

                // Update Charts
                mobilFirstPeriodChart = createOrUpdateChart('CarFirstPeriodBar', mobilFirstPeriodChart, carFirst
                    .chart, 'Car First Period');
                mobilSecondPeriodChart = createOrUpdateChart('CarSecondPeriodBar', mobilSecondPeriodChart, carSecond
                    .chart, 'Car Second Period');
                motorbikeFirstPeriodChart = createOrUpdateChart('MotorbikeFirstPeriodBar',
                    motorbikeFirstPeriodChart, motorFirst.chart, 'Motorbike First Period');
                motorbikeSecondPeriodChart = createOrUpdateChart('motorSecondPeriodBar', motorbikeSecondPeriodChart,
                    motorSecond.chart, 'Motorbike Second Period');
                truckFirstPeriodChart = createOrUpdateChart('truckFirstPeriodBar', truckFirstPeriodChart, truckFirst
                    .chart, 'Truck First Period');
                truckSecondPeriodChart = createOrUpdateChart('truckSecondPeriodBar', truckSecondPeriodChart,
                    truckSecond.chart, 'Truck Second Period');
                taxiFirstPeriodChart = createOrUpdateChart('taxiFirstPeriodBar', taxiFirstPeriodChart, taxiFirst
                    .chart, 'Taxi First Period');
                taxiSecondPeriodChart = createOrUpdateChart('taxiSecondPeriodBar', taxiSecondPeriodChart, taxiSecond
                    .chart, 'Taxi Second Period');
            }
        });
    </script>
@endsection
