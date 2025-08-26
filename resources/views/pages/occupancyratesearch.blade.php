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

    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">

    <style>
        /* General table and layout styles */
        tbody {
            white-space: normal;
            word-break: break-all;
        }

        .dt-buttons {
            display: inline-flex;
            gap: 10px;
            margin-bottom: 1rem;
        }

        .dt-search {
            float: right !important;
            margin-bottom: 5px;
        }

        .dt-button {
            background-color: #FCB900 !important;
            padding: 10px;
            border-radius: 10px;
            border: none !important;
        }

        .content-custom {
            padding: 20px !important;
            background-color: #ffffff !important;
            border-radius: 10px !important;
            box-shadow: 1px -2px 15px -1px rgba(0, 0, 0, 0.28);
            color: #000000 !important;
        }

        /* Metrics Cards */
        .metrics-container {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .metric-card {
            flex: 1;
            border-radius: 0.5rem;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .metric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .metric-title {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .metric-value {
            font-size: 2.5rem;
            font-weight: 700;
        }

        .parkinglot {
            background-color: #ffe47a;
            color: #78350f;
        }

        .occupancy-rate {
            background-color: #ffa7a7;
            color: #7f1d1d;
        }

        .available-space {
            background-color: #a7ffea;
            color: #065f46;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .metrics-container {
                flex-direction: column;
            }
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

        /* Dark Mode Styles */
        .dt-search,
        .dt-info,
        p,
        .today-date,
        .current-time,
        .tgl-selected {
            color: #000000;
        }

        .mode-gelap .dt-search,
        .mode-gelap .dt-info,
        .mode-gelap p,
        .mode-gelap .today-date,
        .mode-gelap .current-time,
        .mode-gelap .tgl-selected {
            color: #ffffff;
        }

        .card {
            background: #fff;
        }

        .mode-gelap .card {
            background: #192e50;
        }

        .fw-medium,
        .form-label,
        h5 {
            color: #000000;
        }

        .mode-gelap .fw-medium,
        .mode-gelap .form-label,
        .mode-gelap h5 {
            color: #ffffff;
        }
    </style>

    <div class="search-wrapper card shadow-sm p-4 border-0 rounded-3 mb-4">
        <h5 class="mb-3 fw-semibold">Occupancy Rate Search</h5>
        <div class="row g-3">
            <div class="col-md-4">
                <label for="datepicker" class="form-label fw-medium">Pilih Tanggal</label>
                <input id="datepicker" class="form-control" placeholder="Pilih tanggal" />
            </div>
        </div>
        <div class="d-flex align-items-center gap-2 mt-3">
            <button type="button" class="btn btn-submit px-4" id="cari">
                <i class="bi bi-search me-1"></i> Cari
            </button>
            <div id="alertMessage" class="alert alert-danger py-2 px-3 mb-0 small flex-grow-1 d-none" role="alert">
                Silakan pilih tanggal terlebih dahulu.
            </div>
        </div>
    </div>

    <div class="content-custom">
        <h5 class="fw-semibold">Current Occupancy Rate</h5>
        <hr>
        <div class="d-flex justify-content-between flex-wrap">
            <p>Date: <span class="today-date"></span></p>
            <p id="current-date">Last updated: <span class="current-time"></span> (Updates every 10 minutes)</p>
        </div>
        <div class="metrics-container">
            <div class="metric-card parkinglot">
                <div class="metric-title">Total Parking Lot</div>
                <div class="metric-value">2022</div>
            </div>
            <div class="metric-card occupancy-rate">
                <div class="metric-title">Occupancy Rate</div>
                <div class="metric-value occupancy">0%</div>
            </div>
            <div class="metric-card available-space">
                <div class="metric-title">Available Space</div>
                <div class="metric-value available">0</div>
            </div>
        </div>
    </div>

    <div class="content-custom mt-4 result" style="display: none;">
        <h5 class="fw-semibold">Occupancy Rate On: <span class="tgl-selected"></span></h5>
        <div class="table-responsive">
            <table class="table table-striped table-bordered mt-3" id="occupancyRateTable" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Time</th>
                        <th>Quantity</th>
                        <th>Occupancy Rate</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>

    {{-- CDN for easepick and DataTables Buttons --}}
    <script src="https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.1/dist/index.umd.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize easepick
            const picker = new easepick.create({
                element: document.getElementById('datepicker'),
                css: ['https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.1/dist/index.css'],
                format: 'YYYY-MM-DD',
            });

            function formatDate(dateString) {
                const date = new Date(dateString);
                return date.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
            }

            const today = new Date().toISOString().split('T')[0];
            $('.today-date').text(formatDate(today));

            function updateJakartaTime() {
                const now = new Date();
                const formatter = new Intl.DateTimeFormat('en-GB', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false,
                    timeZone: 'Asia/Jakarta'
                });
                $('.current-time').text(formatter.format(now));
            }
            updateJakartaTime();
            setInterval(updateJakartaTime, 60000);

            let occupancyRateTable;

            $('#cari').click(function() {
                const startDate = picker.getDate()?.format('YYYY-MM-DD');
                const $cariButton = $(this);

                if (!startDate) {
                    $('#alertMessage').show();
                    return;
                } else {
                    $('#alertMessage').hide();
                }

                $cariButton.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
                    );
                if (occupancyRateTable) {
                    occupancyRateTable.clear().draw();
                }
                $('.result').show();

                const spinnerHtml =
                    `<tr><td colspan="4"><div class="spinner-container"><div class="lds-ring"><div></div><div></div><div></div><div></div></div><strong>Memuat data...</strong></div></td></tr>`;
                $('#occupancyRateTable tbody').html(spinnerHtml);

                $.ajax({
                    url: '{{ route('occupancyRateSearchAPI') }}',
                    method: 'POST',
                    data: {
                        start1: startDate,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#occupancyRateTable tbody').empty();
                        $('.tgl-selected').text(formatDate(startDate));
                        $('.occupancy').text(response.data_realtime[0].occupancyrate);
                        $('.available').text(response.data_realtime[0].quantity);

                        const formattedData = response.data_unpaid.map((item, index) => ({
                            no: index + 1,
                            time: item.time,
                            quantity: item.quantity,
                            occupancy_rate: item.occupancyrate,
                        }));

                        if (occupancyRateTable) {
                            occupancyRateTable.destroy();
                        }

                        occupancyRateTable = $('#occupancyRateTable').DataTable({
                            destroy: true,
                            lengthChange: false,
                            searching: false,
                            paging: false,
                            ordering: false,
                            info: false,
                            data: formattedData,
                            columns: [{
                                    data: 'no'
                                },
                                {
                                    data: 'time'
                                },
                                {
                                    data: 'quantity'
                                },
                                {
                                    data: 'occupancy_rate'
                                }
                            ],
                            dom: 'Bfrtip',
                            buttons: [{
                                    extend: 'excelHtml5',
                                    title: `Occupancy Rate ${startDate}`
                                },
                                {
                                    extend: 'pdfHtml5',
                                    title: `Occupancy Rate ${startDate}`
                                }
                            ],
                            language: {
                                emptyTable: "Tidak ada data untuk tanggal yang dipilih.",
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        const errorHtml =
                            `<tr><td colspan="4" class="text-center text-danger" style="padding: 20px;">Terjadi kesalahan saat mengambil data.</td></tr>`;
                        $('#occupancyRateTable tbody').html(errorHtml);
                    },
                    complete: function() {
                        $cariButton.prop('disabled', false).html(
                            '<i class="bi bi-search me-1"></i> Cari');
                    }
                });
            });
        });
    </script>
@endsection
