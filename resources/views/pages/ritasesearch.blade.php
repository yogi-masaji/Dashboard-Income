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

    {{-- CDN for DataTables Responsive CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">


    <style>
        /* General table and layout styles */
        #RitaseMember_wrapper .dt-top,
        #RitaseCasual_wrapper .dt-top {
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

        .mode-gelap .fw-medium,
        .mode-gelap .form-label,
        .mode-gelap h5 {
            color: #ffffff;
        }
    </style>

    <div class="search-wrapper card shadow-sm p-4 border-0 rounded-3 mb-4">
        <h5 class="mb-3 fw-semibold">Ritase Search</h5>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="datepicker" class="form-label fw-medium">Rentang Tanggal</label>
                <input id="datepicker" class="form-control" placeholder="Pilih rentang tanggal" />
            </div>
        </div>

        <div class="d-flex align-items-center gap-2 mt-3">
            <button type="button" class="btn btn-submit px-4" id="cari">
                <i class="bi bi-search me-1"></i> Cari
            </button>
            <div id="alertMessage" class="alert alert-danger py-2 px-3 mb-0 small flex-grow-1 d-none" role="alert">
                Silakan pilih rentang tanggal terlebih dahulu.
            </div>
        </div>
    </div>

    <div class="content-custom">
        <div class="row">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h5 class="text-center mb-3">Member</h5>
                <div class="table-responsive">
                    <table id="RitaseMember" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Nomor Plat</th>
                                <th>Quantity Keluar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-6">
                <h5 class="text-center mb-3">Casual</h5>
                <div class="table-responsive">
                    <table id="RitaseCasual" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Nomor Plat</th>
                                <th>Quantity Keluar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded here -->
                        </tbody>
                    </table>
                </div>
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

            // Common DataTables options
            const commonTableOptions = {
                pageLength: 10,
                ordering: true,
                lengthChange: false,
                searching: true,
                responsive: true, // Enable responsive feature
                data: [],
                language: {
                    emptyTable: "Silakan pilih rentang tanggal, lalu klik 'Cari' untuk melihat data.",
                    zeroRecords: "Data tidak ditemukan untuk tanggal yang dipilih."
                },
                // Add DOM and Buttons for export
                dom: 'Bfrtip',
                buttons: [
                    'excel', 'pdf'
                ]
            };

            // Initialize Member Table
            const memberTable = $('#RitaseMember').DataTable({
                ...commonTableOptions,
                columns: [{
                    data: 'no'
                }, {
                    data: 'nama'
                }, {
                    data: 'nomor_plat'
                }, {
                    data: 'quantity_keluar'
                }]
            });

            // Initialize Casual Table
            const casualTable = $('#RitaseCasual').DataTable({
                ...commonTableOptions,
                columns: [{
                    data: 'no'
                }, {
                    data: 'nama'
                }, {
                    data: 'nomor_plat'
                }, {
                    data: 'quantity_keluar'
                }]
            });

            // Search button click event
            $('#cari').click(function() {
                const $cariButton = $(this);
                const startDate = picker.getStartDate()?.format('YYYY-MM-DD');
                const endDate = picker.getEndDate()?.format('YYYY-MM-DD');

                if (!startDate || !endDate) {
                    $('#alertMessage').text('Silakan pilih rentang tanggal terlebih dahulu.').removeClass(
                        'd-none');
                    return;
                } else {
                    $('#alertMessage').addClass('d-none');
                }

                $cariButton.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mencari...'
                );
                memberTable.clear().draw();
                casualTable.clear().draw();

                const spinnerHtml = `
                    <tr>
                        <td colspan="4">
                            <div class="spinner-container">
                                <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                                <strong>Memuat data...</strong>
                            </div>
                        </td>
                    </tr>
                `;
                $('#RitaseMember tbody').html(spinnerHtml);
                $('#RitaseCasual tbody').html(spinnerHtml);

                $.ajax({
                    url: '{{ route('ritaseSearchApi') }}',
                    method: 'POST',
                    data: {
                        start1: startDate,
                        end1: endDate,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        const memberData = (response.data[0]?.member || []).map((item, index) =>
                            ({
                                no: index + 1,
                                nama: item.nama,
                                nomor_plat: item.kodeproduk,
                                quantity_keluar: item.quantity
                            }));

                        const casualData = (response.data[0]?.casual || []).map((item, index) =>
                            ({
                                no: index + 1,
                                nama: item.nama,
                                nomor_plat: item.kodeproduk,
                                quantity_keluar: item.quantity
                            }));

                        memberTable.clear().rows.add(memberData).draw();
                        casualTable.clear().rows.add(casualData).draw();
                    },
                    error: function(xhr) {
                        console.error("AJAX Error:", xhr.responseText);
                        const errorHtml = `
                            <tr>
                                <td colspan="4" class="text-center text-danger" style="padding: 20px;">
                                    Terjadi kesalahan saat mengambil data. Silakan coba lagi.
                                </td>
                            </tr>
                        `;
                        $('#RitaseMember tbody').html(errorHtml);
                        $('#RitaseCasual tbody').html(errorHtml);
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
