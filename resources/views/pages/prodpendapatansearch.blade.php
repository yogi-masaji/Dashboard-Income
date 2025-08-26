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
        table.dataTable thead th,
        table.dataTable thead td {
            padding: 5px;
            border-bottom: 1px solid #111
        }

        tbody {
            white-space: normal;
            word-break: break-all;
        }

        .dt-buttons {
            display: inline-flex;
            gap: 10px;
            margin-bottom: 1rem;
            /* Added margin for spacing */
        }

        .dt-button {
            background-color: #FCB900 !important;
            padding: 10px;
            border-radius: 10px;
            border: none !important;
        }

        .dt-search {
            float: right !important;
            margin-bottom: 5px;
        }

        .dt-search input {
            height: 40px;
            border-radius: 10px;
            margin-left: 10px;
        }

        .content-custom {
            padding: 20px !important;
            background-color: #ffffff !important;
            border-radius: 10px !important;
            box-shadow: 1px -2px 15px -1px rgba(0, 0, 0, 0.28);
            color: #000000 !important;
        }

        .table thead tr th {
            padding-block: 1.161rem;
            text-align: center;
            vertical-align: middle;
        }

        table.dataTable tbody th,
        table.dataTable tbody td {
            padding: 8px 5px;
            font-size: 11px;
        }

        table.dataTable tfoot th,
        table.dataTable tfoot td {
            padding: 8px 5px;
            font-size: 11px;
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

        /* Dark Mode Styles */
        .dt-search,
        .dt-info {
            color: #000000;
        }

        .mode-gelap .dt-search,
        .mode-gelap .dt-info {
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
        h5,
        .form-select {
            color: #000000;
        }

        .mode-gelap .fw-medium,
        .mode-gelap .form-label,
        .mode-gelap h5,
        .mode-gelap .form-select {
            color: #ffffff;
        }
    </style>


    <div class="search-wrapper card shadow-sm p-4 border-0 rounded-3 mb-4">
        <h5 class="mb-3 fw-semibold">Produksi dan Pendapatan Search</h5>
        <div class="row g-3">
            <div class="col-md-4">
                <label for="year-select" class="form-label fw-medium">Pilih Tahun</label>
                <select class="form-select w-100" id="year-select" aria-label="Select year">
                    @php
                        $currentYear = date('Y');
                    @endphp
                    <option value="{{ $currentYear }}">{{ $currentYear }}</option>
                    <option value="{{ $currentYear - 1 }}">{{ $currentYear - 1 }}</option>
                    <option value="{{ $currentYear - 2 }}">{{ $currentYear - 2 }}</option>
                </select>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2 mt-3">
            <button type="button" class="btn btn-submit px-4" id="cari">
                <i class="bi bi-search me-1"></i> Cari
            </button>
            <div id="alertMessage" class="alert alert-danger py-2 px-3 mb-0 small flex-grow-1 d-none" role="alert">
                Silakan pilih tahun terlebih dahulu.
            </div>
        </div>
    </div>


    <div class="content-custom">
        <div class="table-responsive">
            <table class="table table-striped table-bordered" id="table-custom" style="width:100%">
                <thead>
                    <tr>
                        <th rowspan="4" style="vertical-align: middle; width:6%;">BULAN</th>
                        <th colspan="13">PARKIR REGULER</th>
                    </tr>
                    <tr>
                        <th colspan="4">CARGO</th>
                        <th colspan="4">TERMINAL</th>
                        <th colspan="4">TOTAL PRODUKSI</th>
                        <th rowspan="3" style="width:7%; vertical-align: middle;">GRAND TOTAL PENDAPATAN</th>
                    </tr>
                    <tr>
                        <th colspan="2">RODA 2</th>
                        <th colspan="2">RODA 4 & 6</th>
                        <th colspan="2">RODA 2</th>
                        <th colspan="2">RODA 4 & 6</th>
                        <th colspan="2">RODA 2</th>
                        <th colspan="2">RODA 4 & 6</th>
                    </tr>
                    <tr>
                        <th>PROD</th>
                        <th>PEND</th>
                        <th>PROD</th>
                        <th>PEND</th>
                        <th>PROD</th>
                        <th>PEND</th>
                        <th>PROD</th>
                        <th>PEND</th>
                        <th>PROD</th>
                        <th>PEND</th>
                        <th>PROD</th>
                        <th>PEND</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be loaded here -->
                </tbody>
                <tfoot></tfoot>
            </table>
        </div>
    </div>

    <!-- DataTables Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function() {
            let tableCustom;

            function initializeDataTable(data = []) {
                if (tableCustom) {
                    tableCustom.destroy();
                    $('#table-custom tfoot').empty();
                }

                tableCustom = $('#table-custom').DataTable({
                    paging: false,
                    searching: false,
                    ordering: false,
                    info: false,
                    autoWidth: false,
                    data: data,
                    columns: [{
                            data: 'bulan'
                        }, {
                            data: 'cargo_rodadua_prod'
                        },
                        {
                            data: 'cargo_rodadua_pend'
                        }, {
                            data: 'cargo_rodaempat_prod'
                        },
                        {
                            data: 'cargo_rodaempat_pend'
                        }, {
                            data: 'terminal_rodadua_prod'
                        },
                        {
                            data: 'terminal_rodadua_pend'
                        }, {
                            data: 'terminal_rodaempat_prod'
                        },
                        {
                            data: 'terminal_rodaempat_pend'
                        }, {
                            data: 'totalproduksi_rodadua_prod'
                        },
                        {
                            data: 'totalproduksi_rodadua_pend'
                        }, {
                            data: 'totalproduksi_rodaempat_prod'
                        },
                        {
                            data: 'totalproduksi_rodaempat_pend'
                        }, {
                            data: 'grandtotal'
                        }
                    ],
                    dom: 'Bfrtip',
                    buttons: [{
                            extend: 'excelHtml5',
                            title: `Produksi dan Pendapatan ${$('#year-select').val()}`
                        },
                        {
                            extend: 'pdfHtml5',
                            title: `Produksi dan Pendapatan ${$('#year-select').val()}`,
                            orientation: 'landscape'
                        }
                    ],
                    language: {
                        emptyTable: "Silakan pilih tahun, lalu klik 'Cari' untuk melihat data.",
                    }
                });
            }

            initializeDataTable(); // Initialize empty table on page load

            function formatQuantity(quantity) {
                return new Intl.NumberFormat('id-ID').format(quantity);
            }

            $('#cari').click(function() {
                const year = $('#year-select').val();
                const $cariButton = $(this);

                if (!year) {
                    $('#alertMessage').show();
                    return;
                } else {
                    $('#alertMessage').hide();
                }

                $cariButton.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
                    );

                const spinnerHtml =
                    `<tr><td colspan="14"><div class="spinner-container"><div class="lds-ring"><div></div><div></div><div></div><div></div></div><strong>Memuat data...</strong></div></td></tr>`;
                $('#table-custom tbody').html(spinnerHtml);

                $.ajax({
                    url: '{{ route('prodpendapatansearchAPI') }}',
                    method: 'POST',
                    data: {
                        year: year,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        const data = response.data.map((item) => ({
                            bulan: item.bulan,
                            cargo_rodadua_prod: formatQuantity(item.cargo[0]
                                .produksi_r2),
                            cargo_rodadua_pend: formatQuantity(item.cargo[0]
                                .pendapatan_r2),
                            cargo_rodaempat_prod: formatQuantity(item.cargo[0]
                                .produksi_r4_r6),
                            cargo_rodaempat_pend: formatQuantity(item.cargo[0]
                                .pendapatan_r4_r6),
                            terminal_rodadua_prod: formatQuantity(item.terminal[0]
                                .produksi_r2),
                            terminal_rodadua_pend: formatQuantity(item.terminal[0]
                                .pendapatan_r2),
                            terminal_rodaempat_prod: formatQuantity(item.terminal[0]
                                .produksi_r4_r6),
                            terminal_rodaempat_pend: formatQuantity(item.terminal[0]
                                .pendapatan_r4_r6),
                            totalproduksi_rodadua_prod: formatQuantity(item
                                .grandtotal[0].produksi_r2),
                            totalproduksi_rodadua_pend: formatQuantity(item
                                .grandtotal[0].pendapatan_r2),
                            totalproduksi_rodaempat_prod: formatQuantity(item
                                .grandtotal[0].produksi_r4_r6),
                            totalproduksi_rodaempat_pend: formatQuantity(item
                                .grandtotal[0].pendapatan_r4_r6),
                            grandtotal: formatQuantity(item.grandtotal[0]
                                .grandtotal_pendapatan),
                        }));

                        initializeDataTable(data); // Re-initialize with new data

                        const totalAll = {
                            cargo_rodadua_prod: 0,
                            cargo_rodadua_pend: 0,
                            cargo_rodaempat_prod: 0,
                            cargo_rodaempat_pend: 0,
                            terminal_rodadua_prod: 0,
                            terminal_rodadua_pend: 0,
                            terminal_rodaempat_prod: 0,
                            terminal_rodaempat_pend: 0,
                            totalproduksi_rodadua_prod: 0,
                            totalproduksi_rodadua_pend: 0,
                            totalproduksi_rodaempat_prod: 0,
                            totalproduksi_rodaempat_pend: 0,
                            grandtotal: 0
                        };

                        response.data.forEach(item => {
                            totalAll.cargo_rodadua_prod += parseFloat(item.cargo[0]
                                .produksi_r2) || 0;
                            totalAll.cargo_rodadua_pend += parseFloat(item.cargo[0]
                                .pendapatan_r2) || 0;
                            totalAll.cargo_rodaempat_prod += parseFloat(item.cargo[0]
                                .produksi_r4_r6) || 0;
                            totalAll.cargo_rodaempat_pend += parseFloat(item.cargo[0]
                                .pendapatan_r4_r6) || 0;
                            totalAll.terminal_rodadua_prod += parseFloat(item.terminal[
                                0].produksi_r2) || 0;
                            totalAll.terminal_rodadua_pend += parseFloat(item.terminal[
                                0].pendapatan_r2) || 0;
                            totalAll.terminal_rodaempat_prod += parseFloat(item
                                .terminal[0].produksi_r4_r6) || 0;
                            totalAll.terminal_rodaempat_pend += parseFloat(item
                                .terminal[0].pendapatan_r4_r6) || 0;
                            totalAll.totalproduksi_rodadua_prod += parseFloat(item
                                .grandtotal[0].produksi_r2) || 0;
                            totalAll.totalproduksi_rodadua_pend += parseFloat(item
                                .grandtotal[0].pendapatan_r2) || 0;
                            totalAll.totalproduksi_rodaempat_prod += parseFloat(item
                                .grandtotal[0].produksi_r4_r6) || 0;
                            totalAll.totalproduksi_rodaempat_pend += parseFloat(item
                                .grandtotal[0].pendapatan_r4_r6) || 0;
                            totalAll.grandtotal += parseFloat(item.grandtotal[0]
                                .grandtotal_pendapatan) || 0;
                        });

                        $('#table-custom tfoot').html(`
                        <tr>
                            <th>Total</th>
                            <th>${formatQuantity(totalAll.cargo_rodadua_prod)}</th>
                            <th>${formatQuantity(totalAll.cargo_rodadua_pend)}</th>
                            <th>${formatQuantity(totalAll.cargo_rodaempat_prod)}</th>
                            <th>${formatQuantity(totalAll.cargo_rodaempat_pend)}</th>
                            <th>${formatQuantity(totalAll.terminal_rodadua_prod)}</th>
                            <th>${formatQuantity(totalAll.terminal_rodadua_pend)}</th>
                            <th>${formatQuantity(totalAll.terminal_rodaempat_prod)}</th>
                            <th>${formatQuantity(totalAll.terminal_rodaempat_pend)}</th>
                            <th>${formatQuantity(totalAll.totalproduksi_rodadua_prod)}</th>
                            <th>${formatQuantity(totalAll.totalproduksi_rodadua_pend)}</th>
                            <th>${formatQuantity(totalAll.totalproduksi_rodaempat_prod)}</th>
                            <th>${formatQuantity(totalAll.totalproduksi_rodaempat_pend)}</th>
                            <th>${formatQuantity(totalAll.grandtotal)}</th>
                        </tr>
                    `);
                    },
                    error: function(xhr) {
                        console.error("AJAX Error:", xhr.responseText);
                        $('#table-custom tbody').html(
                            `<tr><td colspan="14" class="text-center text-danger p-3">Terjadi kesalahan.</td></tr>`
                            );
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
