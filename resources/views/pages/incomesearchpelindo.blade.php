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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* General Styling */
        .card {
            background: #fff;
            border-color: #e5e7eb;
        }

        .form-control {
            background: #fff;
        }

        /* Dark Mode Compatibility */
        .mode-gelap .card {
            background: #192e50;
            border-color: #2c4060;
        }

        .mode-gelap .form-label,
        .mode-gelap .fw-semibold,
        .mode-gelap h5 {
            color: #ffffff !important;
        }

        .mode-gelap .form-control {
            background-color: #2a3a5a;
            border-color: #405680;
            color: #ffffff;
        }

        .mode-gelap .table {
            --bs-table-color: #e9ecef;
            --bs-table-bg: #192e50;
            --bs-table-border-color: #2c4060;
            --bs-table-striped-color: #e9ecef;
            --bs-table-striped-bg: #21375a;
            --bs-table-hover-color: #e9ecef;
            --bs-table-hover-bg: #2c4060;
        }

        .mode-gelap .table th,
        .mode-gelap .table td,
        .mode-gelap .table thead th {
            border-color: #2c4060 !important;
            color: #e9ecef;
        }

        .mode-gelap .sub-total-row {
            --bs-table-accent-bg: #0e213f !important;
            color: #fff;
            font-weight: bold;
        }

        .mode-gelap .grand-total-row {
            --bs-table-accent-bg: #050e1d !important;
            color: #FCB900;
            font-weight: bold;
        }


        /* Table Specific Styles */
        .table> :not(caption)>*>* {
            padding: 10px 4px;
        }

        .sub-total-row {
            --bs-table-accent-bg: #e9ecef !important;
            font-weight: bold;
        }

        .grand-total-row {
            --bs-table-accent-bg: #d1d5db !important;
            font-weight: bold;
            color: #000;
        }

        table.dataTable tbody th,
        table.dataTable tbody td {
            padding: 8px 4px;
        }

        tbody {
            white-space: normal;
            word-break: break-all;
        }

        /* Loading Spinner Styles */
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

        /* Easepick z-index fix */
        .easepick-wrapper {
            z-index: 9999 !important;
        }
    </style>

    <div class="search-wrapper card shadow-sm p-4 border-0 rounded-3">
        <h5 class="mb-3 fw-semibold">Income Search</h5>
        <div class="mb-3">
            <label for="datepicker" class="form-label">Rentang Tanggal</label>
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

    <div class="table-responsive mt-4 card shadow-sm p-4 border-0 rounded-3">
        <table class="table table-striped table-bordered" id="table-custom" style="width:100%">
            <thead class="text-center" style="vertical-align: middle;">
                <tr>
                    <th scope="col" rowspan="2" style="width:13%;">POS</th>
                    <th scope="col" rowspan="2" style="width:6%;">LANE</th>
                    <th scope="col" colspan="3">MOTOR</th>
                    <th scope="col" colspan="3">MOBIL</th>
                    <th scope="col" colspan="3">TRUK</th>
                    <th scope="col" rowspan="2" style="width:10%;">TOTAL PENDAPATAN</th>
                </tr>
                <tr>
                    <th>PRODUKSI</th>
                    <th>TARIF</th>
                    <th>PENDAPATAN</th>
                    <th>PRODUKSI</th>
                    <th>TARIF</th>
                    <th>PENDAPATAN</th>
                    <th>PRODUKSI</th>
                    <th>TARIF</th>
                    <th>PENDAPATAN</th>
                </tr>
            </thead>
            <tbody id="tbody-custom">
                <tr>
                    <td colspan="12" class="text-center p-5">
                        <p class="mt-2 text-muted">Silakan pilih rentang tanggal dan klik 'Cari' untuk melihat data.</p>
                    </td>
                </tr>
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
                    format: 'YYYY-MM-DD',
                    delimiter: ' to '
                }
            });

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

                // Disable button and show loading spinner
                $cariButton.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
                );

                const spinnerHtml = `
                    <tr>
                        <td colspan="12">
                            <div class="spinner-container">
                                <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                                <strong>Loading...</strong>
                            </div>
                        </td>
                    </tr>
                `;
                $('#tbody-custom').html(spinnerHtml);

                // Helper functions for formatting
                const formatQuantity = (quantity) => new Intl.NumberFormat('id-ID').format(quantity);
                const formatRupiah = (number) => new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(number);

                $.ajax({
                    url: '{{ route('incomePelindoSearchAPI') }}',
                    method: 'POST',
                    data: {
                        start1: startDate,
                        end1: endDate,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#tbody-custom').empty(); // Clear spinner/old data

                        if (response.success && response.data) {
                            const data = response.data;
                            const buffer = data.buffer.buffer_line[0];
                            const pos1 = data.pos1;
                            const pos8 = data.pos8;
                            const pos9 = data.pos9;

                            // --- RENDER BUFFER AREA ---
                            $('#tbody-custom').append(`
                                <tr>
                                    <td class="text-center">Buffer Area</td>
                                    <td class="text-center">1</td>
                                    <td class="text-center">${formatQuantity(buffer.prod_motor)}</td>
                                    <td class="text-center">${formatRupiah(buffer.tarif_motor)}</td>
                                    <td class="text-center">${formatRupiah(buffer.pend_motor)}</td>
                                    <td class="text-center">${formatQuantity(buffer.prod_mobil)}</td>
                                    <td class="text-center">${formatRupiah(buffer.tarif_mobil)}</td>
                                    <td class="text-center">${formatRupiah(buffer.pend_mobil)}</td>
                                    <td class="text-center">${formatQuantity(buffer.prod_truck)}</td>
                                    <td class="text-center">${formatRupiah(buffer.tarif_truck)}</td>
                                    <td class="text-center">${formatRupiah(buffer.pend_truck)}</td>
                                    <td class="text-center">${formatRupiah(buffer.total)}</td>
                                </tr>
                                <tr class="sub-total-row">
                                    <td class="text-center" colspan="2">SUB TOTAL BUFFER AREA</td>
                                    <td class="text-center">${formatQuantity(buffer.prod_motor)}</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">${formatRupiah(buffer.pend_motor)}</td>
                                    <td class="text-center">${formatQuantity(buffer.prod_mobil)}</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">${formatRupiah(buffer.pend_mobil)}</td>
                                    <td class="text-center">${formatQuantity(buffer.prod_truck)}</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">${formatRupiah(buffer.pend_truck)}</td>
                                    <td class="text-center">${formatRupiah(buffer.total)}</td>
                                </tr>
                            `);

                            // --- RENDER POS 1 ---
                            let subTotalPos1 = {
                                prod_motor: 0,
                                pend_motor: 0,
                                prod_mobil: 0,
                                pend_mobil: 0,
                                prod_truck: 0,
                                pend_truck: 0,
                                total: 0
                            };
                            ['pos1_line1', 'pos1_line2', 'pos1_line3'].forEach((laneKey,
                                index) => {
                                const lane = pos1[laneKey][0];
                                subTotalPos1.prod_motor += lane.prod_motor;
                                subTotalPos1.pend_motor += lane.pend_motor;
                                subTotalPos1.prod_mobil += lane.prod_mobil;
                                subTotalPos1.pend_mobil += lane.pend_mobil;
                                subTotalPos1.prod_truck += lane.prod_truck;
                                subTotalPos1.pend_truck += lane.pend_truck;
                                subTotalPos1.total += lane.total;
                                $('#tbody-custom').append(`
                                    <tr>
                                        <td class="text-center">POS 1</td>
                                        <td class="text-center">${index + 1}</td>
                                        <td class="text-center">${formatQuantity(lane.prod_motor)}</td>
                                        <td class="text-center">${formatRupiah(lane.tarif_motor)}</td>
                                        <td class="text-center">${formatRupiah(lane.pend_motor)}</td>
                                        <td class="text-center">${formatQuantity(lane.prod_mobil)}</td>
                                        <td class="text-center">${formatRupiah(lane.tarif_mobil)}</td>
                                        <td class="text-center">${formatRupiah(lane.pend_mobil)}</td>
                                        <td class="text-center">${formatQuantity(lane.prod_truck)}</td>
                                        <td class="text-center">${formatRupiah(lane.tarif_truck)}</td>
                                        <td class="text-center">${formatRupiah(lane.pend_truck)}</td>
                                        <td class="text-center">${formatRupiah(lane.total)}</td>
                                    </tr>
                                `);
                            });
                            $('#tbody-custom').append(`
                                <tr class="sub-total-row">
                                    <td class="text-center" colspan="2">SUB TOTAL POS 1</td>
                                    <td class="text-center">${formatQuantity(subTotalPos1.prod_motor)}</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">${formatRupiah(subTotalPos1.pend_motor)}</td>
                                    <td class="text-center">${formatQuantity(subTotalPos1.prod_mobil)}</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">${formatRupiah(subTotalPos1.pend_mobil)}</td>
                                    <td class="text-center">${formatQuantity(subTotalPos1.prod_truck)}</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">${formatRupiah(subTotalPos1.pend_truck)}</td>
                                    <td class="text-center">${formatRupiah(subTotalPos1.total)}</td>
                                </tr>
                            `);

                            // --- RENDER POS 8 ---
                            let subTotalPos8 = {
                                prod_motor: 0,
                                pend_motor: 0,
                                prod_mobil: 0,
                                pend_mobil: 0,
                                prod_truck: 0,
                                pend_truck: 0,
                                total: 0
                            };
                            ['pos8_line1', 'pos8_line2', 'pos8_line3', 'pos8_line4',
                                'pos8_line5', 'pos8_line6'
                            ].forEach((laneKey, index) => {
                                const lane = pos8[laneKey][0];
                                subTotalPos8.prod_motor += lane.prod_motor;
                                subTotalPos8.pend_motor += lane.pend_motor;
                                subTotalPos8.prod_mobil += lane.prod_mobil;
                                subTotalPos8.pend_mobil += lane.pend_mobil;
                                subTotalPos8.prod_truck += lane.prod_truck;
                                subTotalPos8.pend_truck += lane.pend_truck;
                                subTotalPos8.total += lane.total;
                                $('#tbody-custom').append(`
                                    <tr>
                                        <td class="text-center">POS 8</td>
                                        <td class="text-center">${index + 1}</td>
                                        <td class="text-center">${formatQuantity(lane.prod_motor)}</td>
                                        <td class="text-center">${formatRupiah(lane.tarif_motor)}</td>
                                        <td class="text-center">${formatRupiah(lane.pend_motor)}</td>
                                        <td class="text-center">${formatQuantity(lane.prod_mobil)}</td>
                                        <td class="text-center">${formatRupiah(lane.tarif_mobil)}</td>
                                        <td class="text-center">${formatRupiah(lane.pend_mobil)}</td>
                                        <td class="text-center">${formatQuantity(lane.prod_truck)}</td>
                                        <td class="text-center">${formatRupiah(lane.tarif_truck)}</td>
                                        <td class="text-center">${formatRupiah(lane.pend_truck)}</td>
                                        <td class="text-center">${formatRupiah(lane.total)}</td>
                                    </tr>
                                `);
                            });
                            $('#tbody-custom').append(`
                                <tr class="sub-total-row">
                                    <td class="text-center" colspan="2">SUB TOTAL POS 8</td>
                                    <td class="text-center">${formatQuantity(subTotalPos8.prod_motor)}</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">${formatRupiah(subTotalPos8.pend_motor)}</td>
                                    <td class="text-center">${formatQuantity(subTotalPos8.prod_mobil)}</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">${formatRupiah(subTotalPos8.pend_mobil)}</td>
                                    <td class="text-center">${formatQuantity(subTotalPos8.prod_truck)}</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">${formatRupiah(subTotalPos8.pend_truck)}</td>
                                    <td class="text-center">${formatRupiah(subTotalPos8.total)}</td>
                                </tr>
                            `);

                            // --- RENDER POS 9 ---
                            let subTotalPos9 = {
                                prod_motor: 0,
                                pend_motor: 0,
                                prod_mobil: 0,
                                pend_mobil: 0,
                                prod_truck: 0,
                                pend_truck: 0,
                                total: 0
                            };
                            ['pos9_line1', 'pos9_line2', 'pos9_line3', 'pos9_line4'].forEach((
                                laneKey, index) => {
                                const lane = pos9[laneKey][0];
                                subTotalPos9.prod_motor += lane.prod_motor;
                                subTotalPos9.pend_motor += lane.pend_motor;
                                subTotalPos9.prod_mobil += lane.prod_mobil;
                                subTotalPos9.pend_mobil += lane.pend_mobil;
                                subTotalPos9.prod_truck += lane.prod_truck;
                                subTotalPos9.pend_truck += lane.pend_truck;
                                subTotalPos9.total += lane.total;
                                $('#tbody-custom').append(`
                                    <tr>
                                        <td class="text-center">POS 9</td>
                                        <td class="text-center">${index + 1}</td>
                                        <td class="text-center">${formatQuantity(lane.prod_motor)}</td>
                                        <td class="text-center">${formatRupiah(lane.tarif_motor)}</td>
                                        <td class="text-center">${formatRupiah(lane.pend_motor)}</td>
                                        <td class="text-center">${formatQuantity(lane.prod_mobil)}</td>
                                        <td class="text-center">${formatRupiah(lane.tarif_mobil)}</td>
                                        <td class="text-center">${formatRupiah(lane.pend_mobil)}</td>
                                        <td class="text-center">${formatQuantity(lane.prod_truck)}</td>
                                        <td class="text-center">${formatRupiah(lane.tarif_truck)}</td>
                                        <td class="text-center">${formatRupiah(lane.pend_truck)}</td>
                                        <td class="text-center">${formatRupiah(lane.total)}</td>
                                    </tr>
                                `);
                            });
                            $('#tbody-custom').append(`
                                <tr class="sub-total-row">
                                    <td class="text-center" colspan="2">SUB TOTAL POS 9</td>
                                    <td class="text-center">${formatQuantity(subTotalPos9.prod_motor)}</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">${formatRupiah(subTotalPos9.pend_motor)}</td>
                                    <td class="text-center">${formatQuantity(subTotalPos9.prod_mobil)}</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">${formatRupiah(subTotalPos9.pend_mobil)}</td>
                                    <td class="text-center">${formatQuantity(subTotalPos9.prod_truck)}</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">${formatRupiah(subTotalPos9.pend_truck)}</td>
                                    <td class="text-center">${formatRupiah(subTotalPos9.total)}</td>
                                </tr>
                            `);

                            // --- RENDER GRAND TOTAL ---
                            const grandTotal = {
                                prod_motor: buffer.prod_motor + subTotalPos1.prod_motor +
                                    subTotalPos8.prod_motor + subTotalPos9.prod_motor,
                                pend_motor: buffer.pend_motor + subTotalPos1.pend_motor +
                                    subTotalPos8.pend_motor + subTotalPos9.pend_motor,
                                prod_mobil: buffer.prod_mobil + subTotalPos1.prod_mobil +
                                    subTotalPos8.prod_mobil + subTotalPos9.prod_mobil,
                                pend_mobil: buffer.pend_mobil + subTotalPos1.pend_mobil +
                                    subTotalPos8.pend_mobil + subTotalPos9.pend_mobil,
                                prod_truck: buffer.prod_truck + subTotalPos1.prod_truck +
                                    subTotalPos8.prod_truck + subTotalPos9.prod_truck,
                                pend_truck: buffer.pend_truck + subTotalPos1.pend_truck +
                                    subTotalPos8.pend_truck + subTotalPos9.pend_truck,
                                total: buffer.total + subTotalPos1.total + subTotalPos8
                                    .total + subTotalPos9.total
                            };

                            $('#tbody-custom').append(`
                                <tr class="grand-total-row">
                                    <td class="text-center" colspan="2">GRAND TOTAL</td>
                                    <td class="text-center">${formatQuantity(grandTotal.prod_motor)}</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">${formatRupiah(grandTotal.pend_motor)}</td>
                                    <td class="text-center">${formatQuantity(grandTotal.prod_mobil)}</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">${formatRupiah(grandTotal.pend_mobil)}</td>
                                    <td class="text-center">${formatQuantity(grandTotal.prod_truck)}</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">${formatRupiah(grandTotal.pend_truck)}</td>
                                    <td class="text-center">${formatRupiah(grandTotal.total)}</td>
                                </tr>
                            `);

                        } else {
                            $('#tbody-custom').html(
                                '<tr><td colspan="12" class="text-center p-5"><i class="bi bi-x-circle fs-1 text-danger"></i><p class="mt-2 text-danger">Data tidak ditemukan untuk rentang tanggal yang dipilih.</p></td></tr>'
                            );
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        const errorHtml = `
                            <tr>
                                <td colspan="12" class="text-center text-danger p-5">
                                     <i class="bi bi-exclamation-triangle fs-1"></i>
                                     <p class="mt-2">Terjadi kesalahan saat mengambil data. Silakan coba lagi.</p>
                                </td>
                            </tr>
                        `;
                        $('#tbody-custom').html(errorHtml);
                    },
                    complete: function() {
                        // Re-enable button and restore text
                        $cariButton.prop('disabled', false).html(
                            '<i class="bi bi-search me-1"></i> Cari');
                    }
                });
            });
        });
    </script>
@endsection
