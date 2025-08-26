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
    <!-- DataTables Responsive CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">


    <style>
        /* General Styles */
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Apply flexbox to the wrapper that contains the search and buttons */
        #ParkirMemberTable_wrapper .dt-top {
            display: flex;
            flex-wrap: wrap;
            /* Allow items to wrap on smaller screens */
            justify-content: space-between;
            /* Space out items */
            gap: 15px;
            align-items: center;
            padding: 10px 0;
        }

        table.dataTable thead th,
        table.dataTable thead td {
            padding: 16px;
            border-bottom: 1px solid #dee2e6;
            background-color: #f8f9fa;
        }

        tbody {
            white-space: normal;
            word-break: break-word;
        }

        .dt-buttons {
            display: inline-flex;
            gap: 10px;
        }

        .dt-search {
            margin-bottom: 5px;
        }

        .dt-search input {
            display: inline-block;
            width: auto;
            /* Adjust width */
            min-width: 200px;
            /* Minimum width */
            margin-left: 10px;
        }

        button.dt-paging-button {
            background-color: #ffffff !important;
            padding: 10px;
            width: auto;
            min-width: 30px;
            border-radius: 10px;
            border: 1px solid #dee2e6 !important;
            margin: 0 2px;
            transition: background-color 0.3s;
        }

        button.dt-paging-button:hover {
            background-color: #f1f1f1 !important;
        }

        .dt-button {
            background-color: #FCB900 !important;
            color: #fff !important;
            padding: 10px 20px;
            border-radius: 10px;
            border: none !important;
            transition: background-color 0.3s;
        }

        .dt-button:hover {
            background-color: #e0a800 !important;
        }

        #dt-search-0 {
            height: 40px;
            border-radius: 10px;
        }

        .content-custom {
            padding: 20px !important;
            background-color: #ffffff !important;
            border-radius: 10px !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
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
        .mode-gelap p,
        .mode-gelap .dt-info,
        .mode-gelap .dt-search {
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

        /* Responsive adjustments */
        @media (max-width: 768px) {
            #ParkirMemberTable_wrapper .dt-top {
                flex-direction: column;
                align-items: stretch;
            }

            .dt-search {
                width: 100%;
                text-align: left;
                margin-bottom: 10px;
            }

            .dt-search input {
                width: 100%;
                margin-left: 0;
            }

            .dt-buttons {
                width: 100%;
                justify-content: flex-start;
            }
        }

        /* Style for table loading spinner */
        .spinner-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 50px 0;
        }

        .easepick-wrapper {
            z-index: 1060;
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
    <div class="row mt-5 gap-2 gap-md-0">
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
    <div class="result mt-5 content-custom">
        <!-- Loading Spinner -->
        <div id="table-loading-spinner" class="spinner-container" style="display: none;">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <!-- Table -->
        <div id="table-container" class="table-responsive" style="display: none;">
            <table id="ParkirMemberTable" class="table table-striped table-bordered" style="width:100%">
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
    <!-- DataTables Responsive JS -->
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>


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
                format: 'YYYY-MM-DD'
            });

            // Initialize DataTable once on page load
            const table = $('#ParkirMemberTable').DataTable({
                responsive: true, // Enable responsive extension
                dom: "Bfltip",
                pageLength: 25,
                ordering: true,
                lengthChange: false,
                bDestroy: true,
                layout: {
                    topEnd: {
                        buttons: [{
                                extend: 'excelHtml5',
                                title: 'Parking Member Report',
                                text: 'Excel'
                            },
                            {
                                extend: 'pdfHtml5',
                                title: 'Parking Member Report',
                                text: 'PDF',
                                orientation: 'landscape', // Changed to landscape for better fit
                                pageSize: 'A4', // Standard page size
                                customize: function(doc) {
                                    // Customizations to match parking-member.php style
                                    doc.pageMargins = [40, 60, 40,
                                        60
                                    ]; // [left, top, right, bottom]
                                    doc.defaultStyle.fontSize = 10;
                                    doc.styles.tableHeader.fontSize = 12;
                                    doc.styles.title.fontSize = 15;
                                    doc.styles.title.alignment = 'center';
                                    doc.content[1].table.widths =
                                        Array(doc.content[1].table.body[0].length + 1).join('*')
                                        .split('');
                                }
                            }
                        ],
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
                const startDate = picker.getStartDate() ? picker.getStartDate().format('YYYY-MM-DD') : null;
                const endDate = picker.getEndDate() ? picker.getEndDate().format('YYYY-MM-DD') : null;

                if (!startDate || !endDate) {
                    $('#alertMessage').text('Please select a valid date range.').show();
                    return;
                } else {
                    $('#alertMessage').hide();
                }

                $cariButton.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
                );

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
                            const formattedData = parkingMemberData.map((item) => ({
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
                            // Using a more user-friendly notification instead of alert
                            $('#alertMessage').text(
                                'No data found for the selected date range.').show();
                            table.clear().draw();
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        $('#alertMessage').text('An error occurred while fetching data.')
                            .show();
                    },
                    complete: function() {
                        $('#table-loading-spinner').hide();
                        $('#table-container').show();
                        $cariButton.prop('disabled', false).html('Cari');
                    }
                });
            });
        });
    </script>
@endsection
