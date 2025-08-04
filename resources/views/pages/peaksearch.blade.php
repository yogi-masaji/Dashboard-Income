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
            background-color: #ffffff !important;
            border-radius: 10px !important;
            box-shadow: 1px -2px 15px -1px rgba(0, 0, 0, 0.28);
            color: #000000 !important;
        }





        .search-wrapper {
            width: 40%;
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
    </style>
    <p class="text-dark"> peak search</p>
    <div class="search-wrapper content-custom mb-3">
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

        <div class="d-flex align-items-end gap-3 mb-3">
            <div>
                <label for="start-date-2" class="form-label text-dark">Start Date</label>
                <input type="text" name="start2" id="start-date-2" class="form-control"
                    placeholder="Select start date" />
            </div>
            <div class="pb-3 fw-semibold">to</div>
            <div>
                <label for="end-date-2" class="form-label text-dark">End Date</label>
                <input type="text" name="end2" id="end-date-2" class="form-control" placeholder="Select end date" />
            </div>
        </div>

        <div class="mt-3">
            <label for="jamType" class="form-label text-dark">Pilih Tipe Jam</label>
            <select id="jamType" class="form-select">
                <option value="entry">Jam Masuk</option>
                <option value="exit">Jam Keluar</option>
            </select>
        </div>

        <div class="mt-3">
            <button type="button" class="btn btn-submit" id="cari">Cari</button>
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
        $(function() {
            const dateInputs = [{
                    start: '#start-date-1',
                    end: '#end-date-1'
                },
                {
                    start: '#start-date-2',
                    end: '#end-date-2'
                }
            ];

            // Init all datepickers
            dateInputs.forEach(pair => {
                $(pair.start).daterangepicker({
                    singleDatePicker: true,
                    autoApply: true,
                    locale: {
                        format: 'YYYY-MM-DD'
                    }
                }, function(start) {
                    $(pair.start).val(start.format('YYYY-MM-DD'));
                });

                $(pair.end).daterangepicker({
                    singleDatePicker: true,
                    autoApply: true,
                    locale: {
                        format: 'YYYY-MM-DD'
                    }
                }, function(end) {
                    $(pair.end).val(end.format('YYYY-MM-DD'));
                });

                $(pair.start).val('').attr("placeholder", "Select Start Date");
                $(pair.end).val('').attr("placeholder", "Select End Date");
            });

            let savedHours = null; // variabel global buat simpan data hasil fetch

            const TableCarFirstPeriod = $('#carFirstPeriod').DataTable({
                searching: false,
                paging: false,
                autoWidth: false,
                ordering: false,
                info: false,
                layout: {
                    topStart: {
                        buttons: [

                            {
                                extend: 'excelHtml5',
                                titleAttr: 'Export to Excel',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Peak Search | Car Data',
                            },
                            {
                                extend: 'pdfHtml5',
                                titleAttr: 'Export to PDF',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Peak Search | Car Data',
                            },
                        ]
                    }
                },
                data: [],
                columns: [{
                        data: 'no'
                    },
                    {
                        data: 'kondisi'
                    },
                    {
                        data: 'jumlah'
                    },
                ]
            })

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
                                title: 'Peak Search | Car Data',
                            },
                            {
                                extend: 'pdfHtml5',
                                titleAttr: 'Export to PDF',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Peak Search | Car Data',
                            },
                        ]
                    }
                },
                data: [],
                columns: [{
                        data: 'no'
                    },
                    {
                        data: 'kondisi'
                    },
                    {
                        data: 'jumlah'
                    },
                ]
            })
            const TableMotorFirstPeriod = $('#motorFirstPeriod').DataTable({
                searching: false,
                paging: false,
                autoWidth: false,
                ordering: false,
                layout: {
                    topStart: {
                        buttons: [

                            {
                                extend: 'excelHtml5',
                                titleAttr: 'Export to Excel',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Peak Search | Motorbike Data',
                            },
                            {
                                extend: 'pdfHtml5',
                                titleAttr: 'Export to PDF',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Peak Search | Motorbike Data',
                            },
                        ]
                    }
                },
                info: false,
                data: [],
                columns: [{
                        data: 'no'
                    },
                    {
                        data: 'kondisi'
                    },
                    {
                        data: 'jumlah'
                    },
                ]
            })
            const TableMotorSecondPeriod = $('#motorSecondPeriod').DataTable({
                searching: false,
                paging: false,
                autoWidth: false,
                ordering: false,
                layout: {
                    topStart: {
                        buttons: [

                            {
                                extend: 'excelHtml5',
                                titleAttr: 'Export to Excel',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Peak Search | Motorbike Data',
                            },
                            {
                                extend: 'pdfHtml5',
                                titleAttr: 'Export to PDF',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Peak Search | Motorbike Data',
                            },
                        ]
                    }
                },
                info: false,
                data: [],
                columns: [{
                        data: 'no'
                    },
                    {
                        data: 'kondisi'
                    },
                    {
                        data: 'jumlah'
                    },
                ]
            })

            const TableTruckFirstPeriod = $('#truckFirstPeriod').DataTable({
                searching: false,
                paging: false,
                autoWidth: false,
                ordering: false,
                layout: {
                    topStart: {
                        buttons: [

                            {
                                extend: 'excelHtml5',
                                titleAttr: 'Export to Excel',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Peak Search | Truck Data',
                            },
                            {
                                extend: 'pdfHtml5',
                                titleAttr: 'Export to PDF',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Peak Search | Truck Data',
                            },
                        ]
                    }
                },
                info: false,
                data: [],
                columns: [{
                        data: 'no'
                    },
                    {
                        data: 'kondisi'
                    },
                    {
                        data: 'jumlah'
                    },
                ]
            })

            const TableTruckSecondPeriod = $('#truckSecondPeriod').DataTable({
                searching: false,
                paging: false,
                autoWidth: false,
                ordering: false,
                layout: {
                    topStart: {
                        buttons: [

                            {
                                extend: 'excelHtml5',
                                titleAttr: 'Export to Excel',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Peak Search | Truck Data',
                            },
                            {
                                extend: 'pdfHtml5',
                                titleAttr: 'Export to PDF',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Peak Search | Truck Data',
                            },
                        ]
                    }
                },
                info: false,
                data: [],
                columns: [{
                        data: 'no'
                    },
                    {
                        data: 'kondisi'
                    },
                    {
                        data: 'jumlah'
                    },
                ]
            })

            const TableTaxiFirstPeriod = $('#taxiFirstPeriod').DataTable({
                searching: false,
                paging: false,
                autoWidth: false,
                ordering: false,
                layout: {
                    topStart: {
                        buttons: [

                            {
                                extend: 'excelHtml5',
                                titleAttr: 'Export to Excel',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Peak Search | Taxi Data',
                            },
                            {
                                extend: 'pdfHtml5',
                                titleAttr: 'Export to PDF',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Peak Search | Taxi Data',
                            },
                        ]
                    }
                },
                info: false,
                data: [],
                columns: [{
                        data: 'no'
                    },
                    {
                        data: 'kondisi'
                    },
                    {
                        data: 'jumlah'
                    },
                ]
            })

            const TableTaxiSecondPeriod = $('#taxiSecondPeriod').DataTable({
                searching: false,
                paging: false,
                autoWidth: false,
                ordering: false,
                info: false,
                layout: {
                    topStart: {
                        buttons: [

                            {
                                extend: 'excelHtml5',
                                titleAttr: 'Export to Excel',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Peak Search | Taxi Data',
                            },
                            {
                                extend: 'pdfHtml5',
                                titleAttr: 'Export to PDF',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                title: 'Peak Search | Taxi Data',
                            },
                        ]
                    }
                },
                data: [],
                columns: [{
                        data: 'no'
                    },
                    {
                        data: 'kondisi'
                    },
                    {
                        data: 'jumlah'
                    },
                ]
            })

            $(function() {
                const formatDate = (dateStr) => {
                    const [year, month, day] = dateStr.split("-");
                    return `${day}-${month}-${year}`;
                };

                $('#cari').on('click', function() {
                    const data = {
                        first_start_date: formatDate($('#start-date-1').val()),
                        first_end_date: formatDate($('#end-date-1').val()),
                        second_start_date: formatDate($('#start-date-2').val()),
                        second_end_date: formatDate($('#end-date-2').val()),
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

                            // setelah berhasil fetch, tampilkan jam pertama kali sesuai jamType yang sekarang
                            showJamBasedOnType();
                        },
                        error: function(xhr, status, error) {
                            console.error("Error from Laravel:", error);
                        }
                    });
                });




                // trigger saat dropdown diubah
                $('#jamType').on('change', function() {
                    showJamBasedOnType();
                });

                function formatTimeInterval(interval) {
                    const [start, end] = interval.split("-");
                    const startFormatted = start.slice(0, 5); // ambil jam dan menit
                    const endFormatted = end.slice(0, 5); // ambil jam dan menit
                    return `${startFormatted}-${endFormatted}`;
                }
                let mobilFirstPeriodChart = null;
                let mobilSecondPeriodChart = null;
                let motorbikeFirstPeriodChart = null;
                let motorbikeSecondPeriodChart = null;
                let truckFirstPeriodChart = null;
                let truckSecondPeriodChart = null;
                let taxiFirstPeriodChart = null;
                let taxiSecondPeriodChart = null;

                function showJamBasedOnType() {
                    if (!savedHours) {
                        console.warn("Belum ada data, klik tombol 'Cari' dulu.");
                        return;
                    }

                    const selectedJamType = $('#jamType').val();
                    const jamList = selectedJamType === 'entry' ?
                        savedHours.parking_entry_hours[0] :
                        savedHours.parking_exit_hours[0];

                    console.log("Selected Jam Type:", selectedJamType);
                    console.log("Jam List:", jamList);

                    if (!jamList || !jamList.first_period || jamList.first_period.length === 0) {
                        console.warn("Data first_period kosong.");
                        TableCarFirstPeriod.clear().draw();
                        return;
                    }

                    const mobilJamList = jamList.first_period[0].car || [];
                    const motorJamList = jamList.first_period[1].motorbike || [];
                    const truckJamList = jamList.first_period[2].truck || [];
                    const taxiJamList = jamList.first_period[3].taxi || [];

                    const mobilJamListSecondPeriod = jamList.second_period[0].car || [];
                    const motorJamListSecondPeriod = jamList.second_period[1].motorbike || [];
                    const truckJamListSecondPeriod = jamList.second_period[2].truck || [];
                    const taxiJamListSecondPeriod = jamList.second_period[3].taxi || [];
                    console.log("motor Jam List:", motorJamList);

                    const formattedCarFirstPeriodData = mobilJamList.map((jam, index) => ({
                        no: index + 1,
                        kondisi: formatTimeInterval(jam.time_interval),
                        jumlah: jam.vehicle_quantity
                    }));

                    TableCarFirstPeriod.clear().rows.add(formattedCarFirstPeriodData).draw();

                    const formattedCarSecondPeriodData = mobilJamListSecondPeriod.map((jam, index) => ({
                        no: index + 1,
                        kondisi: formatTimeInterval(jam.time_interval),
                        jumlah: jam.vehicle_quantity
                    }));

                    TableCarSecondPeriod.clear().rows.add(formattedCarSecondPeriodData).draw();

                    const formattedMotorFirstPeriodData = motorJamList.map((jam, index) => ({
                        no: index + 1,
                        kondisi: formatTimeInterval(jam.time_interval),
                        jumlah: jam.vehicle_quantity
                    }));

                    TableMotorFirstPeriod.clear().rows.add(formattedMotorFirstPeriodData).draw();

                    const formattedMotorSecondPeriodData = motorJamListSecondPeriod.map((jam, index) => ({
                        no: index + 1,
                        kondisi: formatTimeInterval(jam.time_interval),
                        jumlah: jam.vehicle_quantity
                    }));

                    TableMotorSecondPeriod.clear().rows.add(formattedMotorSecondPeriodData).draw();

                    const formattedTruckFirstPeriodData = truckJamList.map((jam, index) => ({
                        no: index + 1,
                        kondisi: formatTimeInterval(jam.time_interval),
                        jumlah: jam.vehicle_quantity
                    }));

                    TableTruckFirstPeriod.clear().rows.add(formattedTruckFirstPeriodData).draw();

                    const formattedTruckSecondPeriodData = truckJamListSecondPeriod.map((jam, index) => ({
                        no: index + 1,
                        kondisi: formatTimeInterval(jam.time_interval),
                        jumlah: jam.vehicle_quantity
                    }));

                    TableTruckSecondPeriod.clear().rows.add(formattedTruckSecondPeriodData).draw();

                    const formattedTaxiFirstPeriodData = taxiJamList.map((jam, index) => ({
                        no: index + 1,
                        kondisi: formatTimeInterval(jam.time_interval),
                        jumlah: jam.vehicle_quantity
                    }));

                    TableTaxiFirstPeriod.clear().rows.add(formattedTaxiFirstPeriodData).draw();

                    const formattedTaxiSecondPeriodData = taxiJamListSecondPeriod.map((jam, index) => ({
                        no: index + 1,
                        kondisi: formatTimeInterval(jam.time_interval),
                        jumlah: jam.vehicle_quantity
                    }));

                    TableTaxiSecondPeriod.clear().rows.add(formattedTaxiSecondPeriodData).draw();


                    const labels = mobilJamList.map(jam => formatTimeInterval(jam.time_interval));
                    const dataMobilFirstPeriod = mobilJamList.map(jam => jam.vehicle_quantity);
                    const dataMobilSecondPeriod = mobilJamListSecondPeriod.map(jam => jam.vehicle_quantity);
                    const dataMotorbikeFirstPeriod = motorJamList.map(jam => jam.vehicle_quantity);
                    const dataMotorbikeSecondPeriod = motorJamListSecondPeriod.map(jam => jam
                        .vehicle_quantity);
                    const dataTruckFirstPeriod = truckJamList.map(jam => jam.vehicle_quantity);
                    const dataTruckSecondPeriod = truckJamListSecondPeriod.map(jam => jam.vehicle_quantity);
                    const dataTaxiFirstPeriod = taxiJamList.map(jam => jam.vehicle_quantity);
                    const dataTaxiSecondPeriod = taxiJamListSecondPeriod.map(jam => jam.vehicle_quantity);

                    console.log("dataMotorbikeSecondPeriod  :", dataMotorbikeSecondPeriod);
                    const colors = [
                        '#D10300', '#9966FF', '#7E00F5', '#36A2EB', '#00FFFF', '#FF6347',
                        '#E67E00', '#F4A460', '#FFCE56', '#90EE90', '#148F49', '#708090',
                        '#DAB370', '#F8AD2B', '#DFC639', '#E3E32A', '#00943E', '#0E17C4',
                        '#057385', '#101A9F', '#4F236E', '#634E32', '#C233EE', '#BC8F8F'
                    ];
                    const backgroundColors = labels.map((_, index) => colors[index % colors.length]);
                    const mobilFirstPeriod = {
                        labels: labels,
                        datasets: [{
                            label: 'Car First Period',
                            data: dataMobilFirstPeriod,
                            backgroundColor: backgroundColors,
                            borderWidth: 1
                        }]
                    }

                    const mobilFirstPeriodConfig = {
                        type: 'bar',
                        data: mobilFirstPeriod,
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
                    const ctxMobilFirstPeriodBar = document.getElementById('CarFirstPeriodBar')?.getContext(
                        '2d');
                    if (ctxMobilFirstPeriodBar) {
                        if (mobilFirstPeriodChart) {
                            mobilFirstPeriodChart.destroy(); // Destroy the old chart instance if it exists
                        }
                        mobilFirstPeriodChart = new Chart(ctxMobilFirstPeriodBar,
                            mobilFirstPeriodConfig); // Create a new chart instance
                    }

                    // Refresh the chart with new data
                    mobilFirstPeriodChart.update();

                    const mobilSecondPeriod = {
                        labels: labels,
                        datasets: [{
                            label: 'Car Second Period',
                            data: dataMobilSecondPeriod,
                            backgroundColor: backgroundColors,
                            borderWidth: 1
                        }]
                    }
                    const mobilSecondPeriodConfig = {
                        type: 'bar',
                        data: mobilSecondPeriod,
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

                    const ctxMobilSecondPeriodBar = document.getElementById('CarSecondPeriodBar')
                        ?.getContext(
                            '2d');
                    if (ctxMobilSecondPeriodBar) {
                        if (mobilSecondPeriodChart) {
                            mobilSecondPeriodChart.destroy(); // Destroy the old chart instance if it exists
                        }
                        mobilSecondPeriodChart = new Chart(ctxMobilSecondPeriodBar,
                            mobilSecondPeriodConfig); // Create a new chart instance
                    }

                    // Refresh the chart with new data
                    mobilSecondPeriodChart.update();


                    const motorFirstPeriod = {
                        labels: labels,
                        datasets: [{
                            label: 'Motorbike First Period',
                            data: dataMotorbikeFirstPeriod,
                            backgroundColor: backgroundColors,
                            borderWidth: 1
                        }]
                    }

                    const motorFirstPeriodConfig = {
                        type: 'bar',
                        data: motorFirstPeriod,
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
                    const ctxMotorFirstPeriodBar = document.getElementById('MotorbikeFirstPeriodBar')
                        ?.getContext(
                            '2d');
                    if (ctxMotorFirstPeriodBar) {
                        if (motorbikeFirstPeriodChart) {
                            motorbikeFirstPeriodChart
                                .destroy(); // Destroy the old chart instance if it exists
                        }
                        motorbikeFirstPeriodChart = new Chart(ctxMotorFirstPeriodBar,
                            motorFirstPeriodConfig); // Create a new chart instance
                    }
                    // Refresh the chart with new data  
                    motorbikeFirstPeriodChart.update();


                    const motorSecondPeriod = {
                        labels: labels,
                        datasets: [{
                            label: 'Motorbike Second Period',
                            data: dataMotorbikeSecondPeriod,
                            backgroundColor: backgroundColors,
                            borderWidth: 1
                        }]
                    }
                    const motorSecondPeriodConfig = {
                        type: 'bar',
                        data: motorSecondPeriod,
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

                    const ctxMotorSecondPeriodBar = document.getElementById('motorSecondPeriodBar')
                        ?.getContext(
                            '2d');
                    if (ctxMotorSecondPeriodBar) {
                        if (motorbikeSecondPeriodChart) {
                            motorbikeSecondPeriodChart
                                .destroy(); // Destroy the old chart instance if it exists
                        }
                        motorbikeSecondPeriodChart = new Chart(ctxMotorSecondPeriodBar,
                            motorSecondPeriodConfig); // Create a new chart instance
                    }
                    // Refresh the chart with new data
                    motorbikeSecondPeriodChart.update();


                    const truckFirstPeriod = {
                        labels: labels,
                        datasets: [{
                            label: 'Truck First Period',
                            data: dataTruckFirstPeriod,
                            backgroundColor: backgroundColors,
                            borderWidth: 1
                        }]
                    }

                    const truckFirstPeriodConfig = {
                        type: 'bar',
                        data: truckFirstPeriod,
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

                    const ctxTruckFirstPeriodBar = document.getElementById('truckFirstPeriodBar')
                        ?.getContext(
                            '2d');
                    if (ctxTruckFirstPeriodBar) {
                        if (truckFirstPeriodChart) {
                            truckFirstPeriodChart
                                .destroy(); // Destroy the old chart instance if it exists
                        }
                        truckFirstPeriodChart = new Chart(ctxTruckFirstPeriodBar,
                            truckFirstPeriodConfig); // Create a new chart instance
                    }

                    // Refresh the chart with new data
                    truckFirstPeriodChart.update();


                    const truckSecondPeriod = {
                        labels: labels,
                        datasets: [{
                            label: 'Truck Second Period',
                            data: dataTruckSecondPeriod,
                            backgroundColor: backgroundColors,
                            borderWidth: 1
                        }]
                    }

                    const truckSecondPeriodConfig = {
                        type: 'bar',
                        data: truckSecondPeriod,
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

                    const ctxTruckSecondPeriodBar = document.getElementById('truckSecondPeriodBar')
                        ?.getContext(
                            '2d');
                    if (ctxTruckSecondPeriodBar) {
                        if (truckSecondPeriodChart) {
                            truckSecondPeriodChart
                                .destroy(); // Destroy the old chart instance if it exists
                        }
                        truckSecondPeriodChart = new Chart(ctxTruckSecondPeriodBar,
                            truckSecondPeriodConfig); // Create a new chart instance
                    }

                    // Refresh the chart with new data
                    truckSecondPeriodChart.update();

                    const taxiFirstPeriod = {
                        labels: labels,
                        datasets: [{
                            label: 'Taxi First Period',
                            data: dataTaxiFirstPeriod,
                            backgroundColor: backgroundColors,
                            borderWidth: 1
                        }]
                    }

                    const taxiFirstPeriodConfig = {
                        type: 'bar',
                        data: taxiFirstPeriod,
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

                    const ctxTaxiFirstPeriodBar = document.getElementById('taxiFirstPeriodBar')
                        ?.getContext(
                            '2d');
                    if (ctxTaxiFirstPeriodBar) {
                        if (taxiFirstPeriodChart) {
                            taxiFirstPeriodChart
                                .destroy(); // Destroy the old chart instance if it exists
                        }
                        taxiFirstPeriodChart = new Chart(ctxTaxiFirstPeriodBar,
                            taxiFirstPeriodConfig); // Create a new chart instance
                    }

                    // Refresh the chart with new data
                    taxiFirstPeriodChart.update();

                    const taxiSecondPeriod = {
                        labels: labels,
                        datasets: [{
                            label: 'Taxi Second Period',
                            data: dataTaxiSecondPeriod,
                            backgroundColor: backgroundColors,
                            borderWidth: 1
                        }]
                    }

                    const taxiSecondPeriodConfig = {
                        type: 'bar',
                        data: taxiSecondPeriod,
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


                    const ctxTaxiSecondPeriodBar = document.getElementById('taxiSecondPeriodBar')
                        ?.getContext(
                            '2d');
                    if (ctxTaxiSecondPeriodBar) {
                        if (taxiSecondPeriodChart) {
                            taxiSecondPeriodChart
                                .destroy(); // Destroy the old chart instance if it exists
                        }
                        taxiSecondPeriodChart = new Chart(ctxTaxiSecondPeriodBar,
                            taxiSecondPeriodConfig); // Create a new chart instance
                    }


                    // Refresh the chart with new data
                    taxiSecondPeriodChart.update();


                }

            });
        });
    </script>
@endsection
