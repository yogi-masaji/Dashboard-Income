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

    {{-- CDN for Datepicker --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.min.css" rel="stylesheet">

    {{-- CDN for DataTables Responsive and Buttons CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">

    <style>
        /* General table and layout styles */
        #membershipTable_wrapper .dt-top {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
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

        /* Loading Overlay & Spinner */
        #table-container {
            position: relative;
        }

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

        .mode-gelap .form-label,
        .mode-gelap .form-select {
            color: #ffffff;
        }

        .easepick-wrapper {
            z-index: 9999 !important;
        }
    </style>

    <div class="search-wrapper card shadow-sm p-4 border-0 rounded-3 mb-4">
        <h5 class="mb-3 fw-semibold">Membership Search</h5>
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
    </div>


    <div class="content-custom mt-4">
        <div class="text-center mb-3">
            <h5 class="fw-semibold">BCA Membership</h5>
        </div>
        <div id="table-container">
            <div id="loading-overlay" style="display: none;">
                <div class="spinner">
                    <div class="dot1"></div>
                    <div class="dot2"></div>
                    <div class="dot3"></div>
                </div>
                <p>Loading...</p>
            </div>
            <div class="table-responsive">
                <table id="membershipTable" class="table table-striped table-bordered" style="width:100%">
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
                    <tbody>
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- CDN for Datepicker JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>

    {{-- CDN for DataTables Buttons and Responsive JS --}}
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>

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
                pageLength: 25,
                ordering: true,
                lengthChange: false,
                searching: true,
                responsive: true, // Enable responsive feature
                data: [],
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
                ],
                language: {
                    emptyTable: "Silakan pilih status member dan klik 'Cari' untuk melihat data.",
                    zeroRecords: "Data tidak ditemukan untuk filter yang dipilih."
                },
                dom: 'Bfrtip',
                buttons: [
                    'excel', 'pdf', 'print'
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
                const $cariButton = $(this);

                if (!selection) {
                    alert('Please select a member status.');
                    return;
                }

                $('#loading-overlay').show();
                $cariButton.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mencari...'
                );

                if (aktifData.length > 0 && nonaktifData.length > 0 && period === lastPeriod) {
                    updateTableBySelection(selection);
                    $('#loading-overlay').hide();
                    $cariButton.prop('disabled', false).text('Cari');
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
                            lastPeriod = period;
                            updateTableBySelection(selection);
                        },
                        error: function(xhr) {
                            alert('Failed to fetch data.');
                            const errorHtml = `
                                <tr>
                                    <td colspan="7" class="text-center text-danger" style="padding: 20px;">
                                        Terjadi kesalahan saat mengambil data.
                                    </td>
                                </tr>
                            `;
                            $('#membershipTable tbody').html(errorHtml);
                        },
                        complete: function() {
                            $('#loading-overlay').hide();
                            $cariButton.prop('disabled', false).text('Cari');
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
                    resultTable = [...aktifData, ...nonaktifData];
                }

                const formattedResult = formatDataForTable(resultTable);
                TableMembership.clear().rows.add(formattedResult).draw();
            }
        });
    </script>
@endsection
