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
        /* General table and layout styles */
        #membershipTable_wrapper .dt-top {
            display: flex;
            justify-content: flex-start;
            gap: 20px;
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

        .dt-buttons {
            display: inline-flex;
            gap: 10px;
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
        }



        /* --- START: New Spinner Styles --- */
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

        /* --- END: New Spinner Styles --- */

        /* --- START: Datepicker z-index fix --- */
        .easepick-wrapper {
            z-index: 9999 !important;
        }

        /* --- END: Datepicker z-index fix --- */

        .dt-search {
            color: #000000;
        }

        .dt-info {
            color: #000000;
        }

        .mode-gelap .dt-search {
            color: #ffffff;
        }

        .mode-gelap .dt-info {
            color: #ffffff;
        }

        .card {
            background: #fff;
        }

        .mode-gelap .card {
            background: #192e50;
        }

        .fw-medium {
            color: #000000
        }

        .mode-gelap .fw-medium {
            color: #ffffff;
        }
    </style>

    <div class="search-wrapper card shadow-sm p-4 border-0 rounded-3">
        <h5 class="mb-3 text-dark fw-semibold">Detail Parkir Search</h5>

        {{-- Input for date range using easepick --}}
        <div class="mb-3">
            <label for="datepicker" class="fw-medium">Rentang Tanggal</label>
            <input id="datepicker" class="form-control" placeholder="Pilih rentang tanggal" />
        </div>

        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-primary px-4" id="cari">
                <i class="bi bi-search me-1"></i> Cari
            </button>
            <div id="alertMessage" class="alert alert-danger py-2 px-3 mb-0 small flex-grow-1 d-none" role="alert">
                Silakan pilih rentang tanggal terlebih dahulu.
            </div>
        </div>
    </div>


    <div class="result mt-5">
        <table id="detailParkirTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal Masuk</th>
                    <th>Tanggal Keluar</th>
                    <th>Nopol</th>
                    <th>Barcode</th>
                    <th>Kendaraan</th>
                    <th>Tarif Parkir</th>
                    <th>Denda</th>
                    <th>Post Masuk</th>
                    <th>Post Keluar</th>
                    <th>Bank</th>
                    <th>Shift</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data will be loaded here -->
            </tbody>
        </table>
    </div>

    {{-- CDN for easepick --}}
    <script src="https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.1/dist/index.umd.min.js"></script>

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
            let detailParkirTable = $('#detailParkirTable').DataTable({
                dom: "Bfltip",
                pageLength: 25,
                ordering: true,
                lengthChange: false,
                paging: true,
                data: [], // Start with empty data
                layout: {
                    topEnd: {
                        buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5'],
                    },
                },
                columns: [{
                        data: 'no'
                    },
                    {
                        data: 'tanggal_masuk'
                    },
                    {
                        data: 'tanggal_keluar'
                    },
                    {
                        data: 'nopol'
                    },
                    {
                        data: 'barcode'
                    },
                    {
                        data: 'kendaraan'
                    },
                    {
                        data: 'tarif_parkir'
                    },
                    {
                        data: 'dendalt'
                    },
                    {
                        data: 'post_masuk'
                    },
                    {
                        data: 'post_keluar'
                    },
                    {
                        data: 'bank'
                    },
                    {
                        data: 'shift'
                    },
                    {
                        data: 'status'
                    }
                ],
                language: {
                    emptyTable: "Silakan pilih rentang tanggal dan klik 'Cari' untuk melihat data.",
                    zeroRecords: "Data tidak ditemukan untuk rentang tanggal yang dipilih."
                }
            });

            $('#cari').click(function() {
                const $cariButton = $(this);
                const startDate = picker.getStartDate()?.format('YYYY-MM-DD');
                const endDate = picker.getEndDate()?.format('YYYY-MM-DD');

                if (!startDate || !endDate) {
                    $('#alertMessage').text('Silakan pilih rentang tanggal terlebih dahulu.').show();
                    return;
                } else {
                    $('#alertMessage').hide();
                }

                // Disable button
                $cariButton.prop('disabled', true);

                // --- START: Show loading spinner in table ---
                // Clear the table and show the spinner
                detailParkirTable.clear().draw();
                const spinnerHtml = `
                    <tr>
                        <td colspan="13">
                            <div class="spinner-container">
                                <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                                <strong>Loading...</strong>
                            </div>
                        </td>
                    </tr>
                `;
                $('#detailParkirTable tbody').html(spinnerHtml);
                // --- END: Show loading spinner in table ---

                $.ajax({
                    url: '{{ route('parkingDetailSearch') }}',
                    method: 'POST',
                    data: {
                        start1: startDate,
                        end1: endDate,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        let formattedDetailParkir = [];
                        if (response.success && Array.isArray(response.data.data) && response
                            .data.data.length > 0) {
                            formattedDetailParkir = response.data.data.map((item, index) => ({
                                no: index + 1,
                                tanggal_masuk: item.tglmasuk,
                                tanggal_keluar: item.tglkeluar,
                                nopol: item.noplat,
                                barcode: item.nobarcode,
                                kendaraan: item.namavehicle,
                                tarif_parkir: item.tarif,
                                dendalt: item.dendalt,
                                post_masuk: item.kodeposin,
                                post_keluar: item.kodeposout,
                                bank: item.bank,
                                shift: item.kodeshiftin,
                                status: item.statustransaction
                            }));
                        }
                        // Update table data. This will automatically remove the spinner.
                        detailParkirTable.clear().rows.add(formattedDetailParkir).draw();
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        // Show an error message in the table
                        const errorHtml = `
                            <tr>
                                <td colspan="13" class="text-center text-danger" style="padding: 20px;">
                                    Terjadi kesalahan saat mengambil data. Silakan coba lagi.
                                </td>
                            </tr>
                        `;
                        $('#detailParkirTable tbody').html(errorHtml);
                    },
                    complete: function() {
                        // Re-enable button
                        $cariButton.prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endsection
