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

    {{-- CDN for DataTables Responsive and Buttons CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">

    <style>
        /* General table and layout styles */
        #summaryReportTable_wrapper .dt-top {
            display: flex;
            flex-wrap: wrap;
            /* Allow items to wrap on smaller screens */
            justify-content: space-between;
            /* Space out items */
            gap: 15px;
            align-items: center;
            padding-bottom: 1rem;
        }

        table.dataTable thead th,
        table.dataTable thead td {
            padding: 16px;
            border-bottom: 1px solid #dee2e6;
        }

        tbody {
            white-space: normal;
            word-break: break-all;
        }

        .dt-buttons {
            display: inline-flex;
            gap: 8px;
        }

        .dt-search {
            margin-bottom: 5px;
        }

        button.dt-paging-button {
            background-color: #ffffff !important;
            padding: 10px;
            width: 35px;
            height: 35px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            border-radius: 8px;
            border: 1px solid #ddd !important;
            margin: 0 2px;
            transition: all 0.2s ease-in-out;
        }

        button.dt-paging-button:hover {
            background-color: #f0f0f0 !important;
        }

        button.dt-paging-button.current {
            background-color: #FCB900 !important;
            color: #fff !important;
            border-color: #FCB900 !important;
        }

        .dt-button {
            background-color: #FCB900 !important;
            color: #ffffff !important;
            padding: 8px 16px;
            border-radius: 8px !important;
            border: none !important;
            transition: all 0.2s ease-in-out;
        }

        .dt-button:hover {
            opacity: 0.9;
        }

        .dt-search input {
            height: 40px;
            border-radius: 8px;
            margin-left: 10px;
            border: 1px solid #ccc;
            padding: 0 10px;
        }

        .content-custom {
            padding: 20px !important;
            background-color: #ffffff !important;
            border-radius: 10px !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            color: #000000 !important;
        }

        /* Spinner Styles */
        .spinner-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px;
            gap: 10px;
        }

        .lds-ring {
            display: inline-block;
            position: relative;
            width: 80px;
            height: 80px;
        }

        .lds-ring div {
            box-sizing: border-box;
            display: block;
            position: absolute;
            width: 64px;
            height: 64px;
            margin: 8px;
            border: 8px solid #FCB900;
            border-radius: 50%;
            animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
            border-color: #FCB900 transparent transparent transparent;
        }

        .lds-ring div:nth-child(1) {
            animation-delay: -0.45s;
        }

        .lds-ring div:nth-child(2) {
            animation-delay: -0.3s;
        }

        .lds-ring div:nth-child(3) {
            animation-delay: -0.15s;
        }

        @keyframes lds-ring {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Datepicker z-index fix */
        .easepick-wrapper {
            z-index: 9999 !important;
        }

        /* Dark Mode Compatibility */
        .mode-gelap .content-custom {
            background-color: #1a202c !important;
            color: #ffffff !important;
        }

        .mode-gelap .dt-search,
        .mode-gelap .dt-info {
            color: #ffffff;
        }

        .mode-gelap table.dataTable thead th {
            border-bottom: 1px solid #4a5568;
        }

        .mode-gelap .card {
            background: #192e50;
        }

        .form-label,
        .form-select {
            color: #000000;
        }

        .mode-gelap .fw-medium,
        .mode-gelap .form-label,
        .mode-gelap .form-select {
            color: #ffffff;
        }
    </style>

    <div class="search-wrapper card shadow-sm p-4 border-0 rounded-3 mb-4">
        <h5 class="mb-3 fw-semibold">Summary Report Search</h5>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="datepicker" class="form-label fw-medium">Rentang Tanggal</label>
                <input id="datepicker" class="form-control" placeholder="Pilih rentang tanggal" />
            </div>

            <div class="col-md-6">
                <label for="vehicle-select" class="form-label fw-medium">Pilih Kendaraan</label>
                <select class="form-select w-100" id="vehicle-select" aria-label="Select vehicle">
                    <option selected disabled value="">--Pilih Kendaraan--</option>
                    <option value="1">Car</option>
                    <option value="2">Motorbike</option>
                    <option value="3">Box</option>
                    <option value="4">Valet</option>
                    <option value="5">Preffered</option>
                </select>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2 mt-3">
            <button type="button" class="btn btn-submit px-4" id="cari">
                <i class="bi bi-search me-1"></i> Cari
            </button>
            <div id="alertMessage" class="alert alert-danger py-2 px-3 mb-0 small flex-grow-1 d-none" role="alert">
                Silakan lengkapi semua kolom terlebih dahulu.
            </div>
        </div>
    </div>


    <div class="result mt-4">
        <div class="text-center">
            <h5 id="dataResults" class="fw-semibold">Data Report</h5>
        </div>
        <div class="content-custom mt-3">
            <div class="table-responsive">
                <table id="summaryReportTable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Date</th>
                            <th>Issued</th>
                            <th>Return</th>
                            <th>Member</th>
                            <th>Paid</th>
                            <th>Lost</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- CDN for easepick --}}
    <script src="https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.1/dist/index.umd.min.js"></script>

    {{-- CDN for DataTables Buttons and Responsive --}}
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize easepick
            const picker = new easepick.create({
                element: document.getElementById('datepicker'),
                css: [
                    'https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.1/dist/index.css',
                ],
                plugins: ['RangePlugin'],
                RangePlugin: {
                    tooltipNumber(num) {
                        return num - 1;
                    },
                    locale: {
                        one: 'hari',
                        other: 'hari',
                    },
                    format: 'YYYY-MM-DD',
                    delimiter: ' to '
                }
            });

            // Initialize DataTable
            let summaryReportTable = $('#summaryReportTable').DataTable({
                pageLength: 25,
                ordering: true,
                lengthChange: false,
                searching: true,
                responsive: true, // Enable responsive feature
                data: [], // Start with empty data
                columns: [{
                        data: 'no'
                    },
                    {
                        data: 'date'
                    },
                    {
                        data: 'issued'
                    },
                    {
                        data: 'return'
                    },
                    {
                        data: 'member'
                    },
                    {
                        data: 'paid'
                    },
                    {
                        data: 'lost'
                    },
                    {
                        data: 'revenue'
                    }
                ],
                language: {
                    emptyTable: "Silakan pilih rentang tanggal dan kendaraan, lalu klik 'Cari' untuk melihat data.",
                    zeroRecords: "Data tidak ditemukan untuk filter yang dipilih."
                },
                // Add DOM and Buttons for export functionality
                dom: 'Bfrtip',
                buttons: [
                    'excel', 'pdf'
                ]
            });

            $('#cari').click(function() {
                const $cariButton = $(this);
                const startDate = picker.getStartDate()?.format('YYYY-MM-DD');
                const endDate = picker.getEndDate()?.format('YYYY-MM-DD');
                const vehicleType = $('#vehicle-select').find(":selected").text();
                const vehicleValue = $('#vehicle-select').val();

                if (!startDate || !endDate || !vehicleValue) {
                    $('#alertMessage').text('Silakan pilih rentang tanggal dan kendaraan terlebih dahulu.')
                        .removeClass('d-none');
                    return;
                } else {
                    $('#alertMessage').addClass('d-none');
                }

                // Disable button and show spinner
                $cariButton.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mencari...'
                );
                summaryReportTable.clear().draw();
                const spinnerHtml = `
                    <tr>
                        <td colspan="8">
                            <div class="spinner-container">
                                <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                                <strong>Memuat data...</strong>
                            </div>
                        </td>
                    </tr>
                `;
                $('#summaryReportTable tbody').html(spinnerHtml);

                // Helper functions
                const formatDate = (dateString) => {
                    const options = {
                        weekday: 'short'
                    };
                    const dateObj = new Date(dateString);
                    const day = String(dateObj.getDate()).padStart(2, '0');
                    const weekday = dateObj.toLocaleDateString('en-US', options);
                    return `${day} - ${weekday}`;
                };
                const formatRupiah = (number) => {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(number);
                };

                // AJAX call to fetch data
                $.ajax({
                    url: '{{ route('summaryReportSearch') }}',
                    method: 'POST',
                    data: {
                        start1: startDate,
                        end1: endDate,
                        vehicleType: vehicleType,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        let formattedData = [];
                        $('#dataResults').text('Data Report: ' + vehicleType);

                        if (response.first_period && Array.isArray(response.first_period)) {
                            formattedData = response.first_period.map((item, index) => {
                                let issued = 0,
                                    returned = 0,
                                    member = 0,
                                    paid = 0,
                                    lost = 0,
                                    revenue = 0;

                                if (vehicleType === 'Car') {
                                    issued = item.car_in + item.carpass_in;
                                    returned = item.car_out + item.carpass_out;
                                    member = item.carpass_out;
                                    paid = item.car_out;
                                    lost = item.carlt;
                                    revenue = item.carincome + item.carltincome;
                                } else if (vehicleType === 'Motorbike') {
                                    issued = item.motorbike_in + item.motorbikepass_in;
                                    returned = item.motorbike_out + item
                                        .motorbikepass_out;
                                    member = item.motorbikepass_out;
                                    paid = item.motorbike_out;
                                    lost = item.motorbikelt;
                                    revenue = item.motorbikeincome + item
                                        .motorbikeltincome;
                                } else if (vehicleType === 'Box') {
                                    issued = item.truck_in + item.truckpass_in;
                                    returned = item.truck_out + item.truckpass_out;
                                    member = item.truckpass_out;
                                    paid = item.truck_out;
                                    lost = item.trucklt;
                                    revenue = item.truckincome;
                                } else if (vehicleType === 'Valet') {
                                    issued = item.valetqtylobby_in + item
                                        .valetqtynonlobby_in;
                                    returned = item.valetqtylobby_out + item
                                        .valetqtynonlobby_out;
                                    member = 0;
                                    paid = item.valetqtylobby_out + item
                                        .valetqtynonlobby_out + item.valetramayanaqty +
                                        item.valetnonramayanaqty + item.valetvoucherqty;
                                    lost = item.valetramayanalt + item
                                        .valetnonramayanalt + item.valetvoucherlt;
                                    revenue = item.valetincomelobby + item
                                        .valetincomenonlobby + item
                                        .valetramayanaincome + item
                                        .valetnonramayanaincome + item
                                        .valetvoucherincome + item.valetlobbyltincome +
                                        item.valetnonlobbyltincome + item
                                        .valetramayanaltincome + item
                                        .valetnonramayanaltincome + item
                                        .valetvoucherltincome;
                                } else if (vehicleType === 'Preffered') {
                                    issued = item.preferredqty_in;
                                    returned = item.preferredqty_out;
                                    member = 0;
                                    paid = item.preferredqty_out;
                                    lost = item.lostticketincome;
                                    revenue = item.preferredincome + item
                                        .preferredltincome;
                                }

                                return {
                                    no: index + 1,
                                    date: formatDate(item.dateTrx),
                                    issued: issued,
                                    return: returned,
                                    member: member,
                                    paid: paid,
                                    lost: lost,
                                    revenue: formatRupiah(revenue)
                                };
                            });
                        }
                        summaryReportTable.clear().rows.add(formattedData).draw();
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        const errorHtml = `
                            <tr>
                                <td colspan="8" class="text-center text-danger" style="padding: 20px;">
                                    Terjadi kesalahan saat mengambil data. Silakan coba lagi.
                                </td>
                            </tr>
                        `;
                        $('#summaryReportTable tbody').html(errorHtml);
                    },
                    complete: function() {
                        // Re-enable button
                        $cariButton.prop('disabled', false).html(
                            '<i class="bi bi-search me-1"></i> Cari');
                    }
                });
            });
        });
    </script>
@endsection
