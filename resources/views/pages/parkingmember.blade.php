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

    <!-- Easepick CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.1/dist/index.css" />

    <style>
        /* Apply flexbox to the wrapper that contains the search and buttons */
        #ParkirMemberTable_wrapper .dt-top {
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
        }

        #dt-search-0 {
            height: 40px;
            border-radius: 10px;
            margin-left: 10px;
        }

        .content-custom {
            padding: 10px !important;
            background-color: #ffffff !important;
            border-radius: 10px !important;
            box-shadow: 1px -2px 15px -1px rgba(0, 0, 0, 0.28);
            color: #000000 !important;
            border: #d9d9d9 1px solid !important;
        }



        /* Dark Mode Styles */
        .mode-gelap .content-custom,
        .mode-gelap .card {
            background-color: #192e50 !important;
            color: #ffffff !important;
            border-color: #424242 !important;
        }

        .mode-gelap .form-label,
        .mode-gelap h4,
        .mode-gelap h6,
        .mode-gelap small,
        .mode-gelap p {
            color: #ffffff !important;
        }

        .mode-gelap .form-control {
            background-color: #3a3a3a;
            color: #fff;
            border-color: #555;
        }

        .mode-gelap .form-control::placeholder {
            color: #aaa;
        }

        /* Easepick Dark Mode Styles */
        .mode-gelap .easepick-wrapper {
            background-color: #333;
            border-color: #555;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
        }

        .mode-gelap .easepick-wrapper .ep-header {
            background-color: #444;
        }

        .mode-gelap .easepick-wrapper .ep-header button {
            color: #ddd;
        }

        .mode-gelap .easepick-wrapper .ep-header button:hover {
            background-color: #555;
            color: #fff;
        }

        .mode-gelap .easepick-wrapper .ep-month-name {
            color: #fff;
        }

        .mode-gelap .easepick-wrapper .ep-day-name {
            color: #aaa;
        }

        .mode-gelap .easepick-wrapper .ep-day {
            color: #ddd;
        }

        .mode-gelap .easepick-wrapper .ep-day.ep-today {
            color: #FCB900;
            background-color: rgba(252, 185, 0, 0.2);
        }

        .mode-gelap .easepick-wrapper .ep-day.ep-selected {
            background-color: #FCB900;
            color: #000;
        }

        .mode-gelap .easepick-wrapper .ep-day.in-range {
            background-color: rgba(252, 185, 0, 0.3);
        }

        .mode-gelap .easepick-wrapper .ep-day:not(.ep-disabled):hover {
            background-color: rgba(252, 185, 0, 0.5);
            color: #000;
        }

        .mode-gelap .easepick-wrapper .ep-day.ep-disabled {
            color: #666;
        }

        .mode-gelap .easepick-wrapper .ep-footer {
            background-color: #444;
            border-top: 1px solid #555;
        }

        .mode-gelap .easepick-wrapper .ep-footer button {
            background-color: #555;
            color: #ddd;
        }

        .mode-gelap .easepick-wrapper .ep-footer button:hover {
            background-color: #666;
        }

        /* Fix for datepicker overlapping issue */
        .easepick-wrapper {
            z-index: 1060;
        }

        .form-label {
            color: #000;
        }

        .mode-gelap .form-label {
            color: #fff;
        }

        .dt-info,
        .dt-search {
            color: #000;
        }

        .mode-gelap .dt-info,
        .mode-gelap .dt-search {
            color: #fff;
        }

        /* Style for table loading spinner */
        .spinner-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 50px 0;
        }
    </style>

    <div class="search-wrapper content-custom">
        <div class="mb-3">
            <p class="fw-bold">Parking Member Search</p>
            <label for="datepicker" class="form-label">Date Range</label>
            <input id="datepicker" class="form-control" placeholder="Select date range..." />
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
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1">Total Motorbike</h6>
                            <h4 class="fw-bold mb-0 totalMotorbike"></h4>
                            <small class="countMotorbike"></small>
                        </div>
                        <div class="text-success fs-4"><i class="bi bi-scooter"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1">Total Car</h6>
                            <h4 class="fw-bold mb-0 totalCar"></h4>
                            <small class="countCar"></small>
                        </div>
                        <div class="text-success fs-4"><i class="bi bi-car-front-fill"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1">Total Box</h6>
                            <h4 class="fw-bold mb-0 totalBox"></h4>
                            <small class="countBox"></small>
                        </div>
                        <div class="text-success fs-4"><i class="bi bi-truck"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="result mt-5">
        <!-- Loading Spinner -->
        <div id="table-loading-spinner" class="spinner-container" style="display: none;">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <!-- Table -->
        <div id="table-container" style="display: none;">
            <table id="ParkirMemberTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Periode</th>
                        <th>Vehicle Type</th>
                        <th>Tenant Name</th>
                        <th>Plat Number</th>
                        <th>Total Amount</th>
                        <th>Start Date</th>
                        <th>Finish Date</th>
                        <th>Description</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Easepick JS -->
    <script src="https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.1/dist/index.umd.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize the new date range picker
            const picker = new easepick.create({
                element: document.getElementById('datepicker'),
                css: [
                    'https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.1/dist/index.css',
                ],
                plugins: ['RangePlugin'],
                RangePlugin: {
                    delimiter: ' to ',
                },
                format: 'YYYY-MM-DD' // Ensure the format is correct for the backend
            });

            // Initialize DataTable once on page load
            const table = $('#ParkirMemberTable').DataTable({
                dom: "Bfltip",
                pageLength: 25,
                ordering: true,
                lengthChange: false,
                bDestroy: true,
                layout: {
                    topEnd: {
                        buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5'],
                    },
                },
                columns: [{
                        data: 'periode'
                    },
                    {
                        data: 'vehicleType'
                    },
                    {
                        data: 'nameTenant'
                    },
                    {
                        data: 'vehiclePlateNumber'
                    },
                    {
                        data: 'grandTotalAmount'
                    },
                    {
                        data: 'startDate'
                    },
                    {
                        data: 'endDate'
                    },
                    {
                        data: 'description'
                    }
                ],
            });


            $('#cari').click(function() {
                const $cariButton = $(this);
                // Get dates from the easepick instance
                const startDate = picker.getStartDate() ? picker.getStartDate().format('YYYY-MM-DD') : null;
                const endDate = picker.getEndDate() ? picker.getEndDate().format('YYYY-MM-DD') : null;

                if (!startDate || !endDate) {
                    $('#alertMessage').text('Please select a valid date range.').show();
                    return;
                } else {
                    $('#alertMessage').hide();
                }

                // Disable button and show loading spinner
                $cariButton.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
                );

                // Hide table and show spinner
                $('#table-container').hide();
                $('#table-loading-spinner').show();

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
                    url: '{{ route('parkingMemberSearch') }}',
                    method: 'POST',
                    data: {
                        start1: startDate,
                        end1: endDate,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            console.log(response);
                            const totalMotorbike = formatRupiah(response.summary.motor
                                .total_income);
                            const totalCar = formatRupiah(response.summary.mobil.total_income);
                            const totalBox = formatRupiah(response.summary.box.total_income);
                            const countMotorbike = formatQuantity(response.summary.motor.count);
                            const countCar = formatQuantity(response.summary.mobil.count);
                            const countBox = formatQuantity(response.summary.box.count);

                            $('.countMotorbike').text(countMotorbike + ' Motorbike');
                            $('.totalMotorbike').text(totalMotorbike);
                            $('.totalCar').text(totalCar);
                            $('.countCar').text(countCar + ' Car');
                            $('.totalBox').text(totalBox);
                            $('.countBox').text(countBox + ' Box');

                            const parkingMemberData = response.data;
                            const formattedData = parkingMemberData.map((item, index) => ({
                                periode: item.transactionDate,
                                vehicleType: item.vehicleType,
                                nameTenant: item.nameTenant,
                                vehiclePlateNumber: item.vehiclePlateNumber,
                                grandTotalAmount: formatRupiah(item
                                    .grandTotalAmount),
                                startDate: item.startDate,
                                endDate: item.endDate,
                                description: item.description
                            }));
                            table.clear().rows.add(formattedData).draw();

                        } else {
                            alert('No data found!');
                            table.clear().draw(); // Clear table if no data
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert('An error occurred while fetching data.');
                    },
                    complete: function() {
                        // Hide spinner and show table
                        $('#table-loading-spinner').hide();
                        $('#table-container').show();

                        // Re-enable button and restore text
                        $cariButton.prop('disabled', false).html('Cari');
                    }
                });
            });
        });
    </script>
@endsection
