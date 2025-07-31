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
    <!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Income | Dashboard</title>

        <!-- Favicon -->
        <link rel="icon" href="{{ asset('cp.ico') }}">


        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
        <!-- Easepick -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.1/dist/index.css" />

        <style>
            body {
                font-family: 'Nunito', sans-serif;
                background-color: #f4f6f9;
            }

            #card_header {
                background-color: #3c4b64;
                color: white;
            }

            .calendar>.days-grid>.day.today {
                color: #000000 !important;
            }

            .card {
                background-color: #ffffff;
                border: none;
                box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
            }


            .text-white {
                color: white !important;
            }

            .loading {
                position: fixed;
                z-index: 9999;
                height: 2em;
                width: 2em;
                overflow: visible;
                margin: auto;
                top: 0;
                left: 0;
                bottom: 0;
                right: 0;
                border: 4px solid rgba(0, 0, 0, 0.2);
                border-top-color: #000000;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }

            .btn {
                padding: 0.375rem 0.75rem !important;
                font-size: 1rem !important;
                border-radius: 0.25rem !important;
                border: none !important;
                cursor: pointer !important;
                transition: background-color 0.2s ease !important;
            }

            /* Tombol Cari */
            .btn-cari {
                background-color: #0066cc !important;
                /* Biru tua */
                color: #ffffff !important;
            }

            .btn-cari:hover {
                background-color: #004c99 !important;
            }

            /* Tombol Export */
            .btn-export {
                background-color: #28a745 !important;
                /* Hijau */
                color: #ffffff !important;
            }

            .btn-export:hover {
                background-color: #1e7e34 !important;
            }

            /* Disabled state */
            .btn:disabled,
            .btn[disabled] {
                opacity: 0.6 !important;
                cursor: not-allowed !important;
                pointer-events: none !important;
            }


            @keyframes spin {
                to {
                    transform: rotate(360deg);
                }
            }

            #json_output {
                background-color: #1e2732;
                color: #a9c5e8;
                padding: 15px;
                border-radius: 5px;
                white-space: pre-wrap;
                word-wrap: break-word;
            }

            .easepick-wrapper {
                z-index: 1060;
            }
        </style>
    </head>

    <body>
        <div class="loading" style="display:none;"></div>
        <div class="container-fluid px-4 py-4">
            <div class="card h-100">
                <div class="d-flex justify-content-between px-3 py-2 mb-1 align-items-center" id="card_header">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-money-bill-wave-alt fa-2x"></i>
                        <h4 class="header text-light ml-3 mb-0">Income</h4>
                    </div>
                    <p class="card-text font-weight-light font-italic mb-0">{{ now()->format('d F Y') }}</p>
                </div>
                <div class="card-body">
                    <div class="container-fluid">
                        <form id="formPicker">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label>Select Report</label>
                                    <select id="income_type" name="income_type" class="form-control">
                                        <option value="" disabled selected>-- Choose Report --</option>
                                        <option value="rekap_income">Rekap Income</option>
                                        {{-- <option value="igh">IGH</option>
                                        <option value="income">Income</option> --}}
                                        <option value="income_payment">Income Payment</option>
                                        <option value="income_lost_ticket">Income Lost Ticket</option>
                                    </select>
                                </div>
                                <div class="col-md-4 form-group" id="datepicker_div" style="display: none;">
                                    <label>Date </label>
                                    <input id="datepicker" class="form-control" />
                                </div>
                                <div class="col-md-3 form-group" id="vehicle_type_div" style="display: none;">
                                    <label>Select Vehicle</label>
                                    <select id="vehicle_type" name="vehicle_type" class="form-control">

                                        @foreach ($vehicleTypes as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 form-group" id="payment_method_div" style="display: none;">
                                    <label>Select Payment</label>
                                    <select id="payment_method" name="payment_method" class="form-control">
                                        @foreach ($paymentMethods as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-6">
                                    <button type="submit" id="btn_submit" class="btn btn-cari">Cari</button>
                                    <button type="button" id="btn_export" class="btn btn-export" disabled>Export to
                                        Excel</button>

                                </div>
                            </div>
                        </form>
                    </div>
                    <hr>

                    <!-- Result area -->
                    <div id="result_area" class="mt-3" style="display: none;">
                        <h3 id="report_title" class="text-center" style="color: black;"></h3>
                        <h4 id="report_period" class="text-center mb-3" style="color: black;"></h4>

                        <div class="table-responsive" id="table_income_payment_wrapper">
                            <table class="table table-striped table-bordered " id="table-member">
                                <thead>
                                    <tr>
                                        <th rowspan="2">Tgl</th>
                                        <th rowspan="2">Hari</th>
                                        <th colspan="2" class="text-center">Mobil</th>
                                        <th colspan="2" class="text-center">Motor</th>
                                        <th colspan="2" class="text-center">Box</th>
                                        <th colspan="2" class="text-center">Total</th>
                                        <th rowspan="2" id="th-mobil-box" class="text-center">Payment Mobil + Box</th>
                                        <th rowspan="2" id="th-motor" class="text-center">Payment Motor</th>
                                    </tr>
                                    <tr>
                                        <th>Qty</th>
                                        <th>Inc</th>
                                        <th>Qty</th>
                                        <th>Inc</th>
                                        <th>Qty</th>
                                        <th>Inc</th>
                                        <th>Qty</th>
                                        <th>Inc</th>
                                    </tr>
                                </thead>
                                <tbody id="table-body-member"></tbody>
                                <tfoot id="table-foot-member"></tfoot>
                            </table>
                        </div>

                        <!-- NEW: Table for Lost Ticket -->
                        <div class="table-responsive" id="table_lost_ticket_wrapper" style="display: none;">
                            <table class="table table-striped table-bordered" id="table-lost-ticket">
                                <thead>
                                    <tr>
                                        <th rowspan="3">Tgl</th>
                                        <th rowspan="3">Hari</th>
                                        <th colspan="10" class="text-center">Mobil</th>
                                        <th colspan="10" class="text-center">Motor</th>
                                        <th colspan="10" class="text-center">Box</th>
                                        <th colspan="10" class="text-center fw-bold">Total</th>
                                        <th rowspan="2" colspan="2" class="text-center fw-bold">Grand Total</th>
                                    </tr>
                                    <tr>
                                        <!-- Mobil -->
                                        <th colspan="2">QRIS</th>
                                        <th colspan="2">Flazz</th>
                                        <th colspan="2">Emoney</th>
                                        <th colspan="2">Brizzi</th>
                                        <th colspan="2">Tapcash</th>
                                        <!-- Motor -->
                                        <th colspan="2">QRIS</th>
                                        <th colspan="2">Flazz</th>
                                        <th colspan="2">Emoney</th>
                                        <th colspan="2">Brizzi</th>
                                        <th colspan="2">Tapcash</th>
                                        <!-- Box -->
                                        <th colspan="2">QRIS</th>
                                        <th colspan="2">Flazz</th>
                                        <th colspan="2">Emoney</th>
                                        <th colspan="2">Brizzi</th>
                                        <th colspan="2">Tapcash</th>
                                        <!-- Total -->
                                        <th class="fw-bold" colspan="2">QRIS</th>
                                        <th class="fw-bold" colspan="2">Flazz</th>
                                        <th class="fw-bold" colspan="2">Emoney</th>
                                        <th class="fw-bold" colspan="2">Brizzi</th>
                                        <th class="fw-bold" colspan="2">Tapcash</th>

                                    </tr>
                                    <tr>
                                        <!-- Mobil -->
                                        <th>Qty</th>
                                        <th>Inc</th>
                                        <th>Qty</th>
                                        <th>Inc</th>
                                        <th>Qty</th>
                                        <th>Inc</th>
                                        <th>Qty</th>
                                        <th>Inc</th>
                                        <th>Qty</th>
                                        <th>Inc</th>
                                        <!-- Motor -->
                                        <th>Qty</th>
                                        <th>Inc</th>
                                        <th>Qty</th>
                                        <th>Inc</th>
                                        <th>Qty</th>
                                        <th>Inc</th>
                                        <th>Qty</th>
                                        <th>Inc</th>
                                        <th>Qty</th>
                                        <th>Inc</th>
                                        <!-- Box -->
                                        <th>Qty</th>
                                        <th>Inc</th>
                                        <th>Qty</th>
                                        <th>Inc</th>
                                        <th>Qty</th>
                                        <th>Inc</th>
                                        <th>Qty</th>
                                        <th>Inc</th>
                                        <th>Qty</th>
                                        <th>Inc</th>
                                        <!-- Total -->
                                        <th class="fw-bold">Qty</th>
                                        <th class="fw-bold">Inc</th>
                                        <th class="fw-bold">Qty</th>
                                        <th class="fw-bold">Inc</th>
                                        <th class="fw-bold">Qty</th>
                                        <th class="fw-bold">Inc</th>
                                        <th class="fw-bold">Qty</th>
                                        <th class="fw-bold">Inc</th>
                                        <th class="fw-bold">Qty</th>
                                        <th class="fw-bold">Inc</th>
                                        <!-- Grand Total -->
                                        <th class="fw-bold">Qty</th>
                                        <th class="fw-bold">Inc</th>
                                    </tr>
                                </thead>
                                <tbody id="table-body-lost-ticket"></tbody>
                                <tfoot id="table-foot-lost-ticket"></tfoot>
                            </table>
                        </div>

                        <!-- START: CONTAINER FOR REKAP INCOME TABLES -->
                        <div id="table_rekap_income_wrapper" style="display: none;">
                            <!-- Tables will be generated by JavaScript and injected here -->
                        </div>
                        <div id="json_output_wrapper" style="display: none;">
                            <pre id="json_output"></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.1/dist/index.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

        <script>
            $(document).ready(function() {
                let rawDataForExport = null;
                let startDate, endDate;
                let currentReportType = '';

                // Initialize easepick
                const picker = new easepick.create({
                    element: document.getElementById('datepicker'),
                    css: [
                        'https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.1/dist/index.css',
                    ],
                    plugins: ['RangePlugin'],
                    RangePlugin: {
                        autoApply: true
                    },
                    setup(picker) {
                        picker.on('select', (e) => {
                            const {
                                start,
                                end
                            } = e.detail;
                            const formatDate = (date) => {
                                if (!date) return null;
                                const d = new Date(date),
                                    month = '' + (d.getMonth() + 1),
                                    day = '' + d.getDate(),
                                    year = d.getFullYear();
                                return [year, month.padStart(2, '0'), day.padStart(2, '0')].join(
                                    '-');
                            }
                            startDate = formatDate(start);
                            endDate = formatDate(end);
                        });
                    }
                });

                $('#income_type').on('change', function() {
                    const selectedReport = $(this).val();
                    currentReportType = selectedReport;
                    $('#result_area').hide();
                    $('#btn_export').prop('disabled', true);

                    // Sembunyikan semua div opsi secara default
                    $('#datepicker_div, #payment_method_div, #vehicle_type_div').hide();

                    if (selectedReport) {
                        $('#datepicker_div').show();
                        if (selectedReport === 'income_payment') {
                            $('#payment_method_div').show();
                        } else if (selectedReport === 'rekap_income') {
                            $('#vehicle_type_div').show(); // Tampilkan pilihan kendaraan untuk Rekap Income
                        }
                    }
                });


                $('#formPicker').on('submit', function(e) {
                    e.preventDefault();
                    if (!currentReportType) {
                        alert('Mohon pilih jenis laporan terlebih dahulu.');
                        return;
                    }
                    if (!startDate || !endDate) {
                        alert('Mohon pilih rentang tanggal terlebih dahulu.');
                        return;
                    }

                    $('.loading').css("display", "block");
                    $('#btn_export').prop('disabled', true);
                    $('#result_area').hide();
                    rawDataForExport = null;

                    let url = '';
                    let data = {
                        _token: "{{ csrf_token() }}",
                        tgl_awal: startDate,
                        tgl_akhir: endDate
                    };

                    switch (currentReportType) {
                        case 'income_payment':
                            url = "{{ route('income.get') }}";
                            data.payment_method = $('#payment_method').val();
                            const paymentText = $('#payment_method').find('option:selected').text();
                            $('#report_title').text('Income Penggunaan ' + paymentText);
                            break;
                        case 'income_lost_ticket':
                            url = "{{ route('income.lost.get') }}";
                            $('#report_title').text('Income Lost Ticket');
                            break;
                        case 'rekap_income':
                            url = "{{ route('income.recap.get') }}"; // URL baru untuk rekap income
                            data.vehicle_type = $('#vehicle_type').val();
                            const vehicleText = $('#vehicle_type').find('option:selected').text();
                            $('#report_title').text('Rekap Income ' + vehicleText);
                            break;
                        case 'igh':
                        case 'income':
                            url = ""; // Placeholder for future reports
                            $('#report_title').text($(this).find('option:selected').text());
                            break;
                        default:
                            $('.loading').css("display", "none");
                            alert('Jenis laporan tidak valid.');
                            return;
                    }

                    const formatted_tgl_awal = new Date(startDate).toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    });
                    const formatted_tgl_akhir = new Date(endDate).toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    });
                    $('#report_period').text('Periode: ' + formatted_tgl_awal + ' - ' + formatted_tgl_akhir);

                    if (!url) {
                        $('.loading').css("display", "none");
                        $('#result_area').show();
                        displayComingSoon(currentReportType);
                        return;
                    }

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: data,
                        dataType: 'json',
                        success: function(response) {
                            $('#result_area').show();
                            rawDataForExport = response; // Store full response for export
                            $('#table_rekap_income_wrapper, #table_income_payment_wrapper, #table_lost_ticket_wrapper, #json_output_wrapper')
                                .hide();
                            switch (currentReportType) {
                                case 'rekap_income':
                                    displayRekapIncome(response);
                                    break;
                                case 'income_payment':
                                    displayIncomePayment(response);
                                    break;
                                case 'income_lost_ticket':
                                    displayLostTicketTable(response);
                                    break;
                                default:
                                    displayComingSoon(currentReportType);
                                    break;
                            }

                            // Enable export button if data is valid
                            if (rawDataForExport && rawDataForExport.data && Array.isArray(
                                    rawDataForExport.data) && rawDataForExport.data.length > 0) {
                                $('#btn_export').prop('disabled', false);
                            }
                        },
                        error: function(xhr) {
                            alert('Gagal memuat data. Silakan cek konsol untuk detailnya.');
                            console.error("Error:", xhr.responseText);
                        },
                        complete: function() {
                            $('.loading').css("display", "none");
                        }
                    });
                });

                function formatNumber(num) {
                    return Number(num || 0).toLocaleString('id-ID');
                }

                function createVehicleTable(title, summary, details) {
                    // Static headers definition based on user image
                    const headerMapping = [{
                            display: 'Income Casual Bersih',
                            key: 'IncomeCasualBersih'
                        },
                        {
                            display: 'Lost Ticket',
                            key: 'LostTicket'
                        },
                        {
                            display: 'Flazz BCA',
                            key: 'Flazz'
                        },
                        {
                            display: 'E Money',
                            key: 'Emoney'
                        },
                        {
                            display: 'Go Gpay',
                            key: 'Gopay'
                        },
                        {
                            display: 'Tap Cash',
                            key: 'Tapcash'
                        },
                        {
                            display: 'Brizzi',
                            key: 'Brizzi'
                        },
                        {
                            display: 'Shopee Pay',
                            key: 'Shopee'
                        },
                        {
                            display: 'Dana',
                            key: 'Dana'
                        },
                        {
                            display: 'Ovo',
                            key: 'Ovo'
                        },
                        {
                            display: 'Qris',
                            key: 'Qris'
                        },
                        {
                            display: 'Link Aja',
                            key: 'Linkaja'
                        },
                        {
                            display: 'ON',
                            key: 'Overnight',
                            detailKey: 'On'
                        }, // 'On' in detail, 'Overnight' in summary
                        {
                            display: 'Manual',
                            key: 'Manual'
                        }
                    ];

                    let tableHtml =
                        `<h4 class="mt-4" style="color: black;">Rekap Income ${title.replace(/_/g, ' ')}</h4>`;
                    tableHtml +=
                        `<div class="table-responsive"><table class="table table-striped table-bordered" style="width:100%;">`;

                    // Build Header from static mapping
                    let header = '<thead><tr><th>Tanggal</th><th>Hari</th>';
                    headerMapping.forEach(h => {
                        header += `<th>${h.display}</th>`;
                    });
                    header += '<th>Total</th></tr></thead>';
                    tableHtml += header;

                    // Build Body
                    let body = '<tbody>';
                    if (details && details.length > 0) {
                        details.forEach(item => {
                            let row = `<tr>
                                <td>${item.Tanggal ? new Date(item.Tanggal).toLocaleDateString('id-ID', { day:'numeric', month:'short', year:'numeric'}) : '-'}</td>
                                <td>${item.Hari || '-'}</td>`;
                            headerMapping.forEach(h => {
                                // Use detailKey if it exists (for 'ON'), otherwise use the standard key
                                const detailKey = h.detailKey || h.key;
                                row += `<td>${formatNumber(item[detailKey] || 0)}</td>`;
                            });
                            row += `<td style="font-weight:bold;">${formatNumber(item.Total || 0)}</td>`;
                            row += '</tr>';
                            body += row;
                        });
                    } else {
                        // Show a message if there are no transaction details
                        body +=
                            `<tr><td colspan="${headerMapping.length + 3}" class="text-center">Tidak ada data transaksi untuk ditampilkan.</td></tr>`;
                    }
                    body += '</tbody>';
                    tableHtml += body;

                    // Build Footer
                    let footer = '<tfoot style="font-weight: bold;"><tr><td colspan="2">Total</td>';
                    headerMapping.forEach(h => {
                        const summaryKey = 'Total' + h.key;
                        footer += `<td>${formatNumber(summary[summaryKey] || 0)}</td>`;
                    });
                    footer += `<td>${formatNumber(summary.Total || 0)}</td>`;
                    footer += '</tr></tfoot>';
                    tableHtml += footer;

                    tableHtml += '</table></div>';
                    return tableHtml;
                }

                function createSummaryTable(title, summary) {
                    const headerMapping = [{
                            display: 'Income Casual Bersih',
                            key: 'IncomeCasualBersih'
                        },
                        {
                            display: 'Lost Ticket',
                            key: 'LostTicket'
                        },
                        {
                            display: 'Flazz BCA',
                            key: 'Flazz'
                        },
                        {
                            display: 'E Money',
                            key: 'Emoney'
                        },
                        {
                            display: 'Go Gpay',
                            key: 'Gopay'
                        },
                        {
                            display: 'Tap Cash',
                            key: 'Tapcash'
                        },
                        {
                            display: 'Brizzi',
                            key: 'Brizzi'
                        },
                        {
                            display: 'Shopee Pay',
                            key: 'Shopee'
                        },
                        {
                            display: 'Dana',
                            key: 'Dana'
                        },
                        {
                            display: 'Ovo',
                            key: 'Ovo'
                        },
                        {
                            display: 'Qris',
                            key: 'Qris'
                        },
                        {
                            display: 'Link Aja',
                            key: 'Linkaja'
                        },
                        {
                            display: 'ON',
                            key: 'Overnight'
                        },
                        {
                            display: 'Manual',
                            key: 'Manual'
                        }
                    ];

                    let tableHtml = `<h4 class="mt-4" style="color: black;">${title}</h4>`;
                    tableHtml +=
                        `<div class="table-responsive"><table class="table table-striped table-bordered" style="width:100%;">`;

                    // Build Header
                    let header = '<thead><tr>';
                    headerMapping.forEach(h => {
                        header += `<th>${h.display}</th>`;
                    });
                    header += '<th>Total</th></tr></thead>';
                    tableHtml += header;

                    // Build Body (a single row with the totals)
                    let body = '<tbody><tr style="font-weight: bold;">';
                    headerMapping.forEach(h => {
                        const summaryKey = 'Total' + h.key;
                        body += `<td>${formatNumber(summary[summaryKey] || 0)}</td>`;
                    });
                    body += `<td>${formatNumber(summary.Total || 0)}</td>`;
                    body += '</tr></tbody>';
                    tableHtml += body;

                    tableHtml += '</table></div>';
                    return tableHtml;
                }

                function displayRekapIncome(response) {
                    const resultWrapper = $('#table_rekap_income_wrapper');
                    resultWrapper.empty().show();

                    if (!response || !response.data || !Array.isArray(response.data) || response.data.length === 0) {
                        resultWrapper.append(
                            '<p class="text-center">Format data dari server tidak sesuai atau data kosong.</p>');
                        return;
                    }

                    const dataObject = response.data[0];
                    const selectedVehicle = $('#vehicle_type').val();

                    const vehicleTypesToProcess = selectedVehicle === 'ALL' ?
                        Object.keys(dataObject) : [selectedVehicle];

                    vehicleTypesToProcess.forEach(vehicleType => {
                        const vehicleData = dataObject[vehicleType];

                        if (!vehicleData || !Array.isArray(vehicleData) || vehicleData.length === 0) {
                            console.warn(`No data or empty data array for vehicle type: ${vehicleType}`);
                            return;
                        }

                        const summaryObject = vehicleData.find(item => item.summaryTotal);
                        const detailObject = vehicleData.find(item => item.detailTransaction);

                        if (!summaryObject || !summaryObject.summaryTotal) {
                            console.warn(`Summary data missing for ${vehicleType}`);
                            resultWrapper.append(
                                `<p class="text-center">Data summary tidak ditemukan untuk ${vehicleType}.</p>`
                            );
                            return;
                        }

                        // Use createSummaryTable for TOTAL, and createVehicleTable for others
                        if (vehicleType === 'TOTAL') {
                            const summary = summaryObject.summaryTotal;
                            const tableHtml = createSummaryTable('TOTAL', summary);
                            resultWrapper.append(tableHtml);
                        } else {
                            const details = detailObject ? detailObject.detailTransaction : [];
                            const summary = summaryObject.summaryTotal;
                            const tableHtml = createVehicleTable(vehicleType, summary, details);
                            resultWrapper.append(tableHtml);
                        }
                    });
                }

                function displayIncomePayment(response) {
                    $('#table_income_payment_wrapper').show();
                    $('#table_lost_ticket_wrapper').hide();
                    $('#json_output_wrapper').hide();

                    const tableBody = $('#table-body-member');
                    const tableFoot = $('#table-foot-member');
                    tableBody.empty();
                    tableFoot.empty();

                    if (response && response.data && Array.isArray(response.data) && response.data.length > 0) {

                        const summaryObject = response.data.find(item => item.summaryTotal);
                        const detailObject = response.data.find(item => item.DetailTransaction);
                        const summaryLastMonthObject = response.data.find(item => item
                            .summaryTotalLast); // Find last month data

                        if (summaryObject && summaryObject.summaryTotal && detailObject && Array.isArray(detailObject
                                .DetailTransaction)) {

                            const detailTransactions = detailObject.DetailTransaction;
                            const summaryTotal = summaryObject.summaryTotal;

                            if (detailTransactions.length === 0) {
                                tableBody.append(
                                    '<tr><td colspan="12" class="text-center">Tidak ada data transaksi untuk periode yang dipilih.</td></tr>'
                                );
                                return;
                            }

                            detailTransactions.forEach(item => {
                                const row = `<tr>
                                    <td>${item.Tanggal ? new Date(item.Tanggal).toLocaleDateString('id-ID', { day:'numeric', month:'short', year:'2-digit'}).replace(/\./g, '') : '-'}</td>
                                    <td>${item.Hari || '-'}</td>
                                    <td>${formatNumber(item.QtyMobil)}</td>
                                    <td>${formatNumber(item.IncMobil)}</td>
                                    <td>${formatNumber(item.QtyMotor)}</td>
                                    <td>${formatNumber(item.IncMotor)}</td>
                                    <td>${formatNumber(item.QtyBox)}</td>
                                    <td>${formatNumber(item.IncBox)}</td>
                                    <td>${formatNumber(item.TotalQty)}</td>
                                    <td>${formatNumber(item.TotalIncome)}</td>
                                    <td>${formatNumber(item.Mobil_Box)}</td>
                                    <td>${formatNumber(item.Motor)}</td>
                                </tr>`;
                                tableBody.append(row);
                            });

                            // Current month total
                            const summaryMobilBoxKey = Object.keys(summaryTotal).find(k => k.startsWith('Total') && k
                                .endsWith('Mobil_Box')) || '';
                            const summaryMotorKey = Object.keys(summaryTotal).find(k => k.startsWith('Total') && k
                                .endsWith('Motor') && k !== 'TotalIncMotor' && k !== 'TotalQtyMotor') || '';
                            const totalQty = summaryTotal.TotalQty || 0;
                            const totalIncome = summaryTotal.TotalInc || 0;

                            const footerRowTotal = `<tr style="font-weight: bold;">
                                <td colspan="2">Total</td>
                                <td>${formatNumber(summaryTotal.TotalQtyMobil)}</td>
                                <td>${formatNumber(summaryTotal.TotalIncMobil)}</td>
                                <td>${formatNumber(summaryTotal.TotalQtyMotor)}</td>
                                <td>${formatNumber(summaryTotal.TotalIncMotor)}</td>
                                <td>${formatNumber(summaryTotal.TotalQtyTruck)}</td>
                                <td>${formatNumber(summaryTotal.TotalIncTruck)}</td>
                                <td>${formatNumber(totalQty)}</td>
                                <td>${formatNumber(totalIncome)}</td>
                                <td>${formatNumber(summaryTotal[summaryMobilBoxKey])}</td>
                                <td>${formatNumber(summaryTotal[summaryMotorKey])}</td>
                            </tr>`;
                            tableFoot.append(footerRowTotal);

                            // Last month total
                            if (summaryLastMonthObject && summaryLastMonthObject.summaryTotalLast) {
                                const summaryTotalLast = summaryLastMonthObject.summaryTotalLast;
                                const footerRowLastMonth = `<tr style="font-weight: bold;">
                                    <td colspan="2">Bulan Lalu</td>
                                    <td>${formatNumber(summaryTotalLast.TotalQtyMobil)}</td>
                                    <td>${formatNumber(summaryTotalLast.TotalIncMobil)}</td>
                                    <td>${formatNumber(summaryTotalLast.TotalQtyMotor)}</td>
                                    <td>${formatNumber(summaryTotalLast.TotalIncMotor)}</td>
                                    <td>${formatNumber(summaryTotalLast.TotalQtyTruck)}</td>
                                    <td>${formatNumber(summaryTotalLast.TotalIncTruck)}</td>
                                    <td>${formatNumber(summaryTotalLast.TotalQty)}</td>
                                    <td>${formatNumber(summaryTotalLast.TotalInc)}</td>
                                    <td>${formatNumber(summaryTotalLast.TotalMobil_Box)}</td>
                                    <td>${formatNumber(summaryTotalLast.TotalMotor)}</td>
                                </tr>`;
                                tableFoot.append(footerRowLastMonth);
                            }

                        } else {
                            tableBody.append(
                                '<tr><td colspan="12" class="text-center">Struktur data dari API tidak sesuai.</td></tr>'
                            );
                        }
                    } else {
                        tableBody.append(
                            '<tr><td colspan="12" class="text-center">Data tidak ditemukan atau respons API kosong.</td></tr>'
                        );
                    }
                }


                function displayLostTicketTable(response) {
                    // Show the correct table wrapper and hide others
                    $('#table_lost_ticket_wrapper').show();
                    $('#table_income_payment_wrapper').hide();
                    $('#json_output_wrapper').hide();

                    const tableBody = $('#table-body-lost-ticket');
                    const tableFoot = $('#table-foot-lost-ticket');
                    // Clear previous table data
                    tableBody.empty();
                    tableFoot.empty();

                    // Check if the response contains the necessary data structure.
                    // The details are at index 2.
                    if (response && response.data && response.data.length > 2 && response.data[2].DetailTransaction) {
                        const details = response.data[2].DetailTransaction;
                        // The summary for the current period is at index 0.
                        const summary = response.data[0].summaryTotal;
                        // The summary for the last period is at index 1.
                        const summaryLast = response.data[1].summaryTotalLast;

                        // Iterate over each daily transaction detail
                        details.forEach(item => {
                            // --- Per-Row Calculations ---

                            // Calculate Grand Total Quantity for the current row (Mobil + Motor + Box)
                            const grandTotalQty = (item.QtyMobilQris || 0) + (item.QtyMobilFlazz || 0) + (item
                                    .QtyMobilEmoney || 0) + (item.QtyMobilBrizzi || 0) + (item
                                    .QtyMobilTapcash || 0) +
                                (item.QtyMotorQris || 0) + (item.QtyMotorFlazz || 0) + (item.QtyMotorEmoney ||
                                    0) + (item.QtyMotorBrizzi || 0) + (item.QtyMotorTapcash || 0) +
                                (item.QtyBoxQris || 0) + (item.QtyBoxFlazz || 0) + (item.QtyBoxEmoney || 0) + (
                                    item.QtyBoxBrizzi || 0) + (item.QtyBoxTapcash || 0);

                            // Calculate Grand Total Income for the current row (Mobil + Motor + Box)
                            const grandTotalInc = (item.IncMobilQris || 0) + (item.IncMobilFlazz || 0) + (item
                                    .IncMobilEmoney || 0) + (item.IncMobilBrizzi || 0) + (item
                                    .IncMobilTapcash || 0) +
                                (item.IncMotorQris || 0) + (item.IncMotorFlazz || 0) + (item.IncMotorEmoney ||
                                    0) + (item.IncMotorBrizzi || 0) + (item.IncMotorTapcash || 0) +
                                (item.IncBoxQris || 0) + (item.IncBoxFlazz || 0) + (item.IncBoxEmoney || 0) + (
                                    item.IncBoxBrizzi || 0) + (item.IncBoxTapcash || 0);

                            // Calculate total by payment type for the current row
                            const totalQrisQty = (item.QtyMobilQris || 0) + (item.QtyMotorQris || 0) + (item
                                .QtyBoxQris || 0);
                            const totalQrisInc = (item.IncMobilQris || 0) + (item.IncMotorQris || 0) + (item
                                .IncBoxQris || 0);
                            const totalFlazzQty = (item.QtyMobilFlazz || 0) + (item.QtyMotorFlazz || 0) + (item
                                .QtyBoxFlazz || 0);
                            const totalFlazzInc = (item.IncMobilFlazz || 0) + (item.IncMotorFlazz || 0) + (item
                                .IncBoxFlazz || 0);
                            const totalEmoneyQty = (item.QtyMobilEmoney || 0) + (item.QtyMotorEmoney || 0) + (
                                item.QtyBoxEmoney || 0);
                            const totalEmoneyInc = (item.IncMobilEmoney || 0) + (item.IncMotorEmoney || 0) + (
                                item.IncBoxEmoney || 0);
                            const totalBrizziQty = (item.QtyMobilBrizzi || 0) + (item.QtyMotorBrizzi || 0) + (
                                item.QtyBoxBrizzi || 0);
                            const totalBrizziInc = (item.IncMobilBrizzi || 0) + (item.IncMotorBrizzi || 0) + (
                                item.IncBoxBrizzi || 0);
                            const totalTapcashQty = (item.QtyMobilTapcash || 0) + (item.QtyMotorTapcash || 0) +
                                (item.QtyBoxTapcash || 0);
                            const totalTapcashInc = (item.IncMobilTapcash || 0) + (item.IncMotorTapcash || 0) +
                                (item.IncBoxTapcash || 0);


                            // --- Create Table Row HTML ---
                            const row = `<tr>
                <!-- Date and Day -->
                <td>${item.Tanggal ? new Date(item.Tanggal).toLocaleDateString('id-ID', { day:'numeric', month:'short', year:'2-digit'}).replace(/\./g, '') : '-'}</td>
                <td>${item.Hari || '-'}</td>
                
                <!-- Mobil Data -->
                <td>${formatNumber(item.QtyMobilQris)}</td><td>${formatNumber(item.IncMobilQris)}</td>
                <td>${formatNumber(item.QtyMobilFlazz)}</td><td>${formatNumber(item.IncMobilFlazz)}</td>
                <td>${formatNumber(item.QtyMobilEmoney)}</td><td>${formatNumber(item.IncMobilEmoney)}</td>
                <td>${formatNumber(item.QtyMobilBrizzi)}</td><td>${formatNumber(item.IncMobilBrizzi)}</td>
                <td>${formatNumber(item.QtyMobilTapcash)}</td><td>${formatNumber(item.IncMobilTapcash)}</td>
                
                <!-- Motor Data -->
                <td>${formatNumber(item.QtyMotorQris)}</td><td>${formatNumber(item.IncMotorQris)}</td>
                <td>${formatNumber(item.QtyMotorFlazz)}</td><td>${formatNumber(item.IncMotorFlazz)}</td>
                <td>${formatNumber(item.QtyMotorEmoney)}</td><td>${formatNumber(item.IncMotorEmoney)}</td>
                <td>${formatNumber(item.QtyMotorBrizzi)}</td><td>${formatNumber(item.IncMotorBrizzi)}</td>
                <td>${formatNumber(item.QtyMotorTapcash)}</td><td>${formatNumber(item.IncMotorTapcash)}</td>
                
                <!-- Box Data (New) -->
                <td>${formatNumber(item.QtyBoxQris)}</td><td>${formatNumber(item.IncBoxQris)}</td>
                <td>${formatNumber(item.QtyBoxFlazz)}</td><td>${formatNumber(item.IncBoxFlazz)}</td>
                <td>${formatNumber(item.QtyBoxEmoney)}</td><td>${formatNumber(item.IncBoxEmoney)}</td>
                <td>${formatNumber(item.QtyBoxBrizzi)}</td><td>${formatNumber(item.IncBoxBrizzi)}</td>
                <td>${formatNumber(item.QtyBoxTapcash)}</td><td>${formatNumber(item.IncBoxTapcash)}</td>
                
                <!-- Total by Payment Type -->
                <td>${formatNumber(totalQrisQty)}</td><td>${formatNumber(totalQrisInc)}</td>
                <td>${formatNumber(totalFlazzQty)}</td><td>${formatNumber(totalFlazzInc)}</td>
                <td>${formatNumber(totalEmoneyQty)}</td><td>${formatNumber(totalEmoneyInc)}</td>
                <td>${formatNumber(totalBrizziQty)}</td><td>${formatNumber(totalBrizziInc)}</td>
                <td>${formatNumber(totalTapcashQty)}</td><td>${formatNumber(totalTapcashInc)}</td>
                
                <!-- Grand Total for the Row -->
                <td style="font-weight:bold;">${formatNumber(grandTotalQty)}</td>
                <td style="font-weight:bold;">${formatNumber(grandTotalInc)}</td>
            </tr>`;
                            tableBody.append(row);
                        });

                        // --- Populate Table Footer with Summary Data ---
                        if (summary) {
                            // The API response already provides all necessary totals in the summary object.
                            // We can use them directly instead of recalculating.
                            const footerRow = `<tr style="font-weight: bold;">
                <td colspan="2">Total</td>
                
                <!-- Mobil Summary -->
                <td>${formatNumber(summary.TotalQtyMobilQris)}</td><td>${formatNumber(summary.TotalIncMobilQris)}</td>
                <td>${formatNumber(summary.TotalQtyMobilFlazz)}</td><td>${formatNumber(summary.TotalIncMobilFlazz)}</td>
                <td>${formatNumber(summary.TotalQtyMobilEmoney)}</td><td>${formatNumber(summary.TotalIncMobilEmoney)}</td>
                <td>${formatNumber(summary.TotalQtyMobilBrizzi)}</td><td>${formatNumber(summary.TotalIncMobilBrizzi)}</td>
                <td>${formatNumber(summary.TotalQtyMobilTapcash)}</td><td>${formatNumber(summary.TotalIncMobilTapcash)}</td>
                
                <!-- Motor Summary -->
                <td>${formatNumber(summary.TotalQtyMotorQris)}</td><td>${formatNumber(summary.TotalIncMotorQris)}</td>
                <td>${formatNumber(summary.TotalQtyMotorFlazz)}</td><td>${formatNumber(summary.TotalIncMotorFlazz)}</td>
                <td>${formatNumber(summary.TotalQtyMotorEmoney)}</td><td>${formatNumber(summary.TotalIncMotorEmoney)}</td>
                <td>${formatNumber(summary.TotalQtyMotorBrizzi)}</td><td>${formatNumber(summary.TotalIncMotorBrizzi)}</td>
                <td>${formatNumber(summary.TotalQtyMotorTapcash)}</td><td>${formatNumber(summary.TotalIncMotorTapcash)}</td>
                
                <!-- Box Summary (New) -->
                <td>${formatNumber(summary.TotalQtyBoxQris)}</td><td>${formatNumber(summary.TotalIncBoxQris)}</td>
                <td>${formatNumber(summary.TotalQtyBoxFlazz)}</td><td>${formatNumber(summary.TotalIncBoxFlazz)}</td>
                <td>${formatNumber(summary.TotalQtyBoxEmoney)}</td><td>${formatNumber(summary.TotalIncBoxEmoney)}</td>
                <td>${formatNumber(summary.TotalQtyBoxBrizzi)}</td><td>${formatNumber(summary.TotalIncBoxBrizzi)}</td>
                <td>${formatNumber(summary.TotalQtyBoxTapcash)}</td><td>${formatNumber(summary.TotalIncBoxTapcash)}</td>
                
                <!-- Total by Payment Type Summary -->
                <td>${formatNumber(summary.TotalQtyQris)}</td><td>${formatNumber(summary.TotalIncQris)}</td>
                <td>${formatNumber(summary.TotalQtyFlazz)}</td><td>${formatNumber(summary.TotalIncFlazz)}</td>
                <td>${formatNumber(summary.TotalQtyEmoney)}</td><td>${formatNumber(summary.TotalIncEmoney)}</td>
                <td>${formatNumber(summary.TotalQtyBrizzi)}</td><td>${formatNumber(summary.TotalIncBrizzi)}</td>
                <td>${formatNumber(summary.TotalQtyTapcash)}</td><td>${formatNumber(summary.TotalIncTapcash)}</td>
                
                <!-- Grand Total Summary -->
                <td>${formatNumber(summary.GrandTotalQty)}</td>
                <td>${formatNumber(summary.GrandTotalInc)}</td>
            </tr>`;
                            tableFoot.append(footerRow);
                        }

                        // --- Populate Table Footer with Last Month's Summary Data ---
                        if (summaryLast) {
                            const lastMonthFooterRow = `<tr style="font-weight: bold;">
                <td colspan="2">Bulan lalu</td>
                
                <!-- Mobil Summary Last Month -->
                <td>${formatNumber(summaryLast.TotalQtyMobilQris)}</td><td>${formatNumber(summaryLast.TotalIncMobilQris)}</td>
                <td>${formatNumber(summaryLast.TotalQtyMobilFlazz)}</td><td>${formatNumber(summaryLast.TotalIncMobilFlazz)}</td>
                <td>${formatNumber(summaryLast.TotalQtyMobilEmoney)}</td><td>${formatNumber(summaryLast.TotalIncMobilEmoney)}</td>
                <td>${formatNumber(summaryLast.TotalQtyMobilBrizzi)}</td><td>${formatNumber(summaryLast.TotalIncMobilBrizzi)}</td>
                <td>${formatNumber(summaryLast.TotalQtyMobilTapcash)}</td><td>${formatNumber(summaryLast.TotalIncMobilTapcash)}</td>
                
                <!-- Motor Summary Last Month -->
                <td>${formatNumber(summaryLast.TotalQtyMotorQris)}</td><td>${formatNumber(summaryLast.TotalIncMotorQris)}</td>
                <td>${formatNumber(summaryLast.TotalQtyMotorFlazz)}</td><td>${formatNumber(summaryLast.TotalIncMotorFlazz)}</td>
                <td>${formatNumber(summaryLast.TotalQtyMotorEmoney)}</td><td>${formatNumber(summaryLast.TotalIncMotorEmoney)}</td>
                <td>${formatNumber(summaryLast.TotalQtyMotorBrizzi)}</td><td>${formatNumber(summaryLast.TotalIncMotorBrizzi)}</td>
                <td>${formatNumber(summaryLast.TotalQtyMotorTapcash)}</td><td>${formatNumber(summaryLast.TotalIncMotorTapcash)}</td>
                
                <!-- Box Summary Last Month -->
                <td>${formatNumber(summaryLast.TotalQtyBoxQris)}</td><td>${formatNumber(summaryLast.TotalIncBoxQris)}</td>
                <td>${formatNumber(summaryLast.TotalQtyBoxFlazz)}</td><td>${formatNumber(summaryLast.TotalIncBoxFlazz)}</td>
                <td>${formatNumber(summaryLast.TotalQtyBoxEmoney)}</td><td>${formatNumber(summaryLast.TotalIncBoxEmoney)}</td>
                <td>${formatNumber(summaryLast.TotalQtyBoxBrizzi)}</td><td>${formatNumber(summaryLast.TotalIncBoxBrizzi)}</td>
                <td>${formatNumber(summaryLast.TotalQtyBoxTapcash)}</td><td>${formatNumber(summaryLast.TotalIncBoxTapcash)}</td>
                
                <!-- Total by Payment Type Summary Last Month -->
                <td>${formatNumber(summaryLast.TotalQtyQris)}</td><td>${formatNumber(summaryLast.TotalIncQris)}</td>
                <td>${formatNumber(summaryLast.TotalQtyFlazz)}</td><td>${formatNumber(summaryLast.TotalIncFlazz)}</td>
                <td>${formatNumber(summaryLast.TotalQtyEmoney)}</td><td>${formatNumber(summaryLast.TotalIncEmoney)}</td>
                <td>${formatNumber(summaryLast.TotalQtyBrizzi)}</td><td>${formatNumber(summaryLast.TotalIncBrizzi)}</td>
                <td>${formatNumber(summaryLast.TotalQtyTapcash)}</td><td>${formatNumber(summaryLast.TotalIncTapcash)}</td>
                
                <!-- Grand Total Summary Last Month -->
                <td>${formatNumber(summaryLast.GrandTotalQty)}</td>
                <td>${formatNumber(summaryLast.GrandTotalInc)}</td>
            </tr>`;
                            tableFoot.append(lastMonthFooterRow);
                        }

                    } else {
                        // Display a message if no data is found
                        tableBody.append('<tr><td colspan="44" class="text-center">Data tidak ditemukan.</td></tr>');
                    }
                }


                function displayComingSoon(reportType) {
                    $('#json_output_wrapper').show();
                    $('#table_income_payment_wrapper').hide();
                    $('#table_lost_ticket_wrapper').hide();
                    $('#json_output').text(`Report type "${reportType}" is not yet implemented.`);
                }

                // =================================================================
                // START: FUNGSI EKSPOR EXCEL YANG DIPERBARUI
                // =================================================================
                $('#btn_export').on('click', function() {
                    if (!rawDataForExport || !rawDataForExport.data) {
                        alert("Tidak ada data untuk diekspor.");
                        return;
                    }

                    const reportTitle = $('#report_title').text();
                    const reportPeriod = $('#report_period').text();
                    const wb = XLSX.utils.book_new();

                    if (currentReportType === 'rekap_income') {
                        const dataObject = rawDataForExport.data[0];
                        const selectedVehicle = $('#vehicle_type').val();
                        const vehicle_type_text = $('#vehicle_type').find('option:selected').text().replace(
                            /\s/g, '_');
                        const fileName = `Rekap_Income_${vehicle_type_text}_${startDate}_to_${endDate}.xlsx`;

                        const headerMapping = [{
                                display: 'Income Casual Bersih',
                                key: 'IncomeCasualBersih'
                            },
                            {
                                display: 'Lost Ticket',
                                key: 'LostTicket'
                            },
                            {
                                display: 'Flazz BCA',
                                key: 'Flazz'
                            },
                            {
                                display: 'E Money',
                                key: 'Emoney'
                            },
                            {
                                display: 'Go Gpay',
                                key: 'Gopay'
                            },
                            {
                                display: 'Tap Cash',
                                key: 'Tapcash'
                            },
                            {
                                display: 'Brizzi',
                                key: 'Brizzi'
                            },
                            {
                                display: 'Shopee Pay',
                                key: 'Shopee'
                            },
                            {
                                display: 'Dana',
                                key: 'Dana'
                            },
                            {
                                display: 'Ovo',
                                key: 'Ovo'
                            },
                            {
                                display: 'Qris',
                                key: 'Qris'
                            },
                            {
                                display: 'Link Aja',
                                key: 'Linkaja'
                            },
                            {
                                display: 'ON',
                                key: 'Overnight',
                                detailKey: 'On'
                            },
                            {
                                display: 'Manual',
                                key: 'Manual'
                            }
                        ];

                        const createSheetFromData = (data, title) => {
                            const summaryObject = data.find(item => item.summaryTotal);
                            const detailObject = data.find(item => item.detailTransaction);
                            if (!summaryObject || !summaryObject.summaryTotal) return null;

                            const summary = summaryObject.summaryTotal;
                            const details = detailObject ? detailObject.detailTransaction : [];

                            let exportData = [];
                            exportData.push([`Rekap Income ${title.replace(/_/g, ' ')}`]);
                            exportData.push([reportPeriod]);
                            exportData.push([]);

                            const headerRow = ['Tanggal', 'Hari', ...headerMapping.map(h => h.display),
                                'Total'
                            ];
                            const bodyData = details.map(item => {
                                const row = [
                                    item.Tanggal ? new Date(item.Tanggal) : null,
                                    item.Hari || null
                                ];
                                headerMapping.forEach(h => {
                                    const detailKey = h.detailKey || h.key;
                                    row.push(Number(item[detailKey] || 0));
                                });
                                row.push(Number(item.Total || 0));
                                return row;
                            });
                            const footerRow = ['Total', null];
                            headerMapping.forEach(h => {
                                footerRow.push(Number(summary['Total' + h.key] || 0));
                            });
                            footerRow.push(Number(summary.Total || 0));

                            exportData = exportData.concat([headerRow], bodyData, [footerRow]);
                            const ws = XLSX.utils.aoa_to_sheet(exportData);

                            const colWidths = [{
                                wch: 15
                            }, {
                                wch: 15
                            }, ...headerMapping.map(() => ({
                                wch: 20
                            })), {
                                wch: 22
                            }];
                            ws['!cols'] = colWidths;

                            const range = XLSX.utils.decode_range(ws['!ref']);
                            for (let R = range.s.r; R <= range.e.r; ++R) {
                                for (let C = range.s.c; C <= range.e.c; ++C) {
                                    const cell_ref = XLSX.utils.encode_cell({
                                        c: C,
                                        r: R
                                    });
                                    const cell = ws[cell_ref];
                                    if (!cell) continue;
                                    if (typeof cell.v === 'number') {
                                        cell.t = 'n';
                                        cell.s = {
                                            numFmt: '#,##0'
                                        };
                                    }
                                    if (cell.v instanceof Date) {
                                        cell.t = 'd';
                                        cell.s = {
                                            numFmt: 'dd-mmm-yyyy'
                                        };
                                    }
                                }
                            }
                            return ws;
                        };

                        if (selectedVehicle === 'ALL') {
                            // Create individual sheets for CAR, MOTORCYCLE, TRUCK_BOX
                            ['CAR', 'MOTORCYCLE', 'TRUCK_BOX'].forEach(vehicleType => {
                                const vehicleData = dataObject[vehicleType];
                                if (vehicleData) {
                                    const ws = createSheetFromData(vehicleData, vehicleType);
                                    if (ws) XLSX.utils.book_append_sheet(wb, ws, vehicleType);
                                }
                            });

                            // Create the master "TOTAL" sheet with all tables
                            let totalSheetData = [];
                            totalSheetData.push([`Rekap Income Gabungan`]);
                            totalSheetData.push([reportPeriod]);
                            totalSheetData.push([]);

                            ['CAR', 'MOTORCYCLE', 'TRUCK_BOX'].forEach(vehicleType => {
                                const vehicleData = dataObject[vehicleType];
                                if (!vehicleData) return;

                                const summaryObject = vehicleData.find(item => item.summaryTotal);
                                const detailObject = vehicleData.find(item => item.detailTransaction);
                                const summary = summaryObject ? summaryObject.summaryTotal : {};
                                const details = detailObject ? detailObject.detailTransaction : [];

                                totalSheetData.push([`Rekap Income ${vehicleType.replace(/_/g, ' ')}`]);
                                const headerRow = ['Tanggal', 'Hari', ...headerMapping.map(h => h
                                    .display), 'Total'];
                                totalSheetData.push(headerRow);

                                details.forEach(item => {
                                    const row = [item.Tanggal ? new Date(item.Tanggal) : null,
                                        item.Hari || null
                                    ];
                                    headerMapping.forEach(h => {
                                        row.push(Number(item[h.detailKey || h.key] ||
                                            0));
                                    });
                                    row.push(Number(item.Total || 0));
                                    totalSheetData.push(row);
                                });

                                const footerRow = ['Total', null];
                                headerMapping.forEach(h => {
                                    footerRow.push(Number(summary['Total' + h.key] || 0));
                                });
                                footerRow.push(Number(summary.Total || 0));
                                totalSheetData.push(footerRow);
                                totalSheetData.push([]);
                                totalSheetData.push([]);
                            });

                            // Final Grand Total Summary
                            const grandTotalData = dataObject['TOTAL'];
                            if (grandTotalData) {
                                const summaryObject = grandTotalData.find(item => item.summaryTotal);
                                if (summaryObject && summaryObject.summaryTotal) {
                                    const summary = summaryObject.summaryTotal;
                                    totalSheetData.push([`Rekap Income TOTAL`]);

                                    // Header without Tanggal and Hari
                                    const summaryHeaderRow = ['', ...headerMapping.map(h => h.display),
                                        'Total'
                                    ];

                                    // Data row starting with "Total"
                                    const summaryDataRow = ['Total'];
                                    headerMapping.forEach(h => {
                                        summaryDataRow.push(Number(summary['Total' + h.key] || 0));
                                    });
                                    summaryDataRow.push(Number(summary.Total || 0));

                                    totalSheetData.push(summaryHeaderRow);
                                    totalSheetData.push(summaryDataRow);
                                }
                            }

                            const totalWS = XLSX.utils.aoa_to_sheet(totalSheetData);
                            const colWidths = [{
                                wch: 15
                            }, {
                                wch: 15
                            }, ...headerMapping.map(() => ({
                                wch: 20
                            })), {
                                wch: 22
                            }];
                            totalWS['!cols'] = colWidths;

                            const range = XLSX.utils.decode_range(totalWS['!ref']);
                            for (let R = range.s.r; R <= range.e.r; ++R) {
                                for (let C = range.s.c; C <= range.e.c; ++C) {
                                    const cell_ref = XLSX.utils.encode_cell({
                                        c: C,
                                        r: R
                                    });
                                    const cell = totalWS[cell_ref];
                                    if (!cell || !cell.v) continue;
                                    if (typeof cell.v === 'number') {
                                        cell.t = 'n';
                                        cell.s = {
                                            numFmt: '#,##0'
                                        };
                                    }
                                    if (cell.v instanceof Date) {
                                        cell.t = 'd';
                                        cell.s = {
                                            numFmt: 'dd-mmm-yyyy'
                                        };
                                    }
                                }
                            }
                            XLSX.utils.book_append_sheet(wb, totalWS, 'TOTAL');

                        } else {
                            // Case for single vehicle selection
                            const vehicleData = dataObject[selectedVehicle];
                            if (vehicleData) {
                                const ws = createSheetFromData(vehicleData, selectedVehicle);
                                if (ws) XLSX.utils.book_append_sheet(wb, ws, selectedVehicle.substring(0, 31));
                            }
                        }

                        XLSX.writeFile(wb, fileName);

                    } else if (currentReportType === 'income_payment') {
                        const summaryObject = rawDataForExport.data.find(item => item.summaryTotal);
                        const detailObject = rawDataForExport.data.find(item => item.DetailTransaction);
                        const summaryLastMonthObject = rawDataForExport.data.find(item => item
                            .summaryTotalLast);

                        if (!summaryObject || !detailObject || !Array.isArray(detailObject.DetailTransaction)) {
                            alert("Struktur data untuk ekspor tidak valid atau tidak lengkap.");
                            return;
                        }

                        const detailTransactions = detailObject.DetailTransaction;
                        const summaryTotal = summaryObject.summaryTotal;
                        const payment_method_text = $('#payment_method').find('option:selected').text().replace(
                            /\s/g, '_');
                        const fileName =
                            `Income_Payment_${payment_method_text}_${startDate}_to_${endDate}.xlsx`;

                        let exportData = [];
                        exportData.push([reportTitle]);
                        exportData.push([reportPeriod]);
                        exportData.push([]);

                        const headers = [
                            ["Tgl", "Hari", "Mobil", null, "Motor", null, "Box", null, "Total", null,
                                "Payment Mobil + Box", "Payment Motor"
                            ],
                            [null, null, "Qty", "Inc", "Qty", "Inc", "Qty", "Inc", "Qty", "Inc", null, null]
                        ];

                        const bodyData = detailTransactions.map(item => [
                            item.Tanggal ? new Date(item.Tanggal) : null, item.Hari || null,
                            Number(item.QtyMobil || 0), Number(item.IncMobil || 0),
                            Number(item.QtyMotor || 0), Number(item.IncMotor || 0),
                            Number(item.QtyBox || 0), Number(item.IncBox || 0),
                            Number(item.TotalQty || 0), Number(item.TotalIncome || 0),
                            Number(item.Mobil_Box || 0), Number(item.Motor || 0)
                        ]);

                        const summaryMobilBoxKey = Object.keys(summaryTotal).find(k => k.startsWith('Total') &&
                            k.endsWith('Mobil_Box')) || '';
                        const summaryMotorKey = Object.keys(summaryTotal).find(k => k.startsWith('Total') && k
                            .endsWith('Motor') && k !== 'TotalIncMotor' && k !== 'TotalQtyMotor') || '';
                        const footerData = [
                            ["Total", null,
                                Number(summaryTotal.TotalQtyMobil || 0), Number(summaryTotal
                                    .TotalIncMobil || 0),
                                Number(summaryTotal.TotalQtyMotor || 0), Number(summaryTotal
                                    .TotalIncMotor || 0),
                                Number(summaryTotal.TotalQtyTruck || 0), Number(summaryTotal
                                    .TotalIncTruck || 0),
                                Number(summaryTotal.TotalQty || 0), Number(summaryTotal.TotalInc || 0),
                                Number(summaryTotal[summaryMobilBoxKey] || 0), Number(summaryTotal[
                                    summaryMotorKey] || 0)
                            ]
                        ];

                        if (summaryLastMonthObject && summaryLastMonthObject.summaryTotalLast) {
                            const summaryTotalLast = summaryLastMonthObject.summaryTotalLast;
                            footerData.push([
                                `Bulan Lalu`, null,
                                Number(summaryTotalLast.TotalQtyMobil || 0), Number(summaryTotalLast
                                    .TotalIncMobil || 0),
                                Number(summaryTotalLast.TotalQtyMotor || 0), Number(summaryTotalLast
                                    .TotalIncMotor || 0),
                                Number(summaryTotalLast.TotalQtyTruck || 0), Number(summaryTotalLast
                                    .TotalIncTruck || 0),
                                Number(summaryTotalLast.TotalQty || 0), Number(summaryTotalLast
                                    .TotalInc || 0),
                                Number(summaryTotalLast.TotalMobil_Box || 0), Number(summaryTotalLast
                                    .TotalMotor || 0)
                            ]);
                        }

                        exportData = exportData.concat(headers, bodyData, footerData);
                        const ws = XLSX.utils.aoa_to_sheet(exportData);

                        const merges = [{
                            s: {
                                r: 0,
                                c: 0
                            },
                            e: {
                                r: 0,
                                c: 11
                            }
                        }, {
                            s: {
                                r: 1,
                                c: 0
                            },
                            e: {
                                r: 1,
                                c: 11
                            }
                        }, {
                            s: {
                                r: 3,
                                c: 0
                            },
                            e: {
                                r: 4,
                                c: 0
                            }
                        }, {
                            s: {
                                r: 3,
                                c: 1
                            },
                            e: {
                                r: 4,
                                c: 1
                            }
                        }, {
                            s: {
                                r: 3,
                                c: 2
                            },
                            e: {
                                r: 3,
                                c: 3
                            }
                        }, {
                            s: {
                                r: 3,
                                c: 4
                            },
                            e: {
                                r: 3,
                                c: 5
                            }
                        }, {
                            s: {
                                r: 3,
                                c: 6
                            },
                            e: {
                                r: 3,
                                c: 7
                            }
                        }, {
                            s: {
                                r: 3,
                                c: 8
                            },
                            e: {
                                r: 3,
                                c: 9
                            }
                        }, {
                            s: {
                                r: 3,
                                c: 10
                            },
                            e: {
                                r: 4,
                                c: 10
                            }
                        }, {
                            s: {
                                r: 3,
                                c: 11
                            },
                            e: {
                                r: 4,
                                c: 11
                            }
                        }, {
                            s: {
                                r: 5 + bodyData.length,
                                c: 0
                            },
                            e: {
                                r: 5 + bodyData.length,
                                c: 1
                            }
                        }];
                        if (summaryLastMonthObject) {
                            merges.push({
                                s: {
                                    r: 5 + bodyData.length + 1,
                                    c: 0
                                },
                                e: {
                                    r: 5 + bodyData.length + 1,
                                    c: 1
                                }
                            });
                        }
                        ws['!merges'] = merges;

                        const borderStyle = {
                            top: {
                                style: "thin"
                            },
                            bottom: {
                                style: "thin"
                            },
                            left: {
                                style: "thin"
                            },
                            right: {
                                style: "thin"
                            }
                        };
                        const range = XLSX.utils.decode_range(ws['!ref']);
                        for (let R = range.s.r; R <= range.e.r; ++R) {
                            for (let C = range.s.c; C <= range.e.c; ++C) {
                                const cell_ref = XLSX.utils.encode_cell({
                                    c: C,
                                    r: R
                                });
                                if (!ws[cell_ref]) continue;

                                let style = {};
                                if (R === 0) {
                                    style.font = {
                                        bold: true,
                                        sz: 16
                                    };
                                    style.alignment = {
                                        horizontal: 'center'
                                    };
                                } else if (R === 1) {
                                    style.font = {
                                        italic: true,
                                        sz: 12
                                    };
                                    style.alignment = {
                                        horizontal: 'center'
                                    };
                                }
                                if (R >= 3 && R < exportData.length) {
                                    style.border = borderStyle;
                                }
                                if (R >= 3 && R <= 4) {
                                    style.alignment = {
                                        ...(style.alignment || {}),
                                        vertical: "center",
                                        horizontal: "center",
                                        wrapText: true
                                    };
                                }
                                if (R >= (5 + bodyData.length)) {
                                    style.font = {
                                        ...(style.font || {}),
                                        bold: true
                                    };
                                }
                                if (R >= 5) {
                                    if (C === 0 && ws[cell_ref].v instanceof Date) {
                                        ws[cell_ref].t = 'd';
                                        style.numFmt = 'dd/mm/yyyy';
                                    } else if (C >= 2 && typeof ws[cell_ref].v === 'number') {
                                        ws[cell_ref].t = 'n';
                                        style.numFmt = '#,##0';
                                    }
                                }
                                ws[cell_ref].s = style;
                            }
                        }

                        ws['!cols'] = [{
                            wch: 12
                        }, {
                            wch: 15
                        }, {
                            wch: 12
                        }, {
                            wch: 15
                        }, {
                            wch: 12
                        }, {
                            wch: 15
                        }, {
                            wch: 12
                        }, {
                            wch: 15
                        }, {
                            wch: 12
                        }, {
                            wch: 15
                        }, {
                            wch: 20
                        }, {
                            wch: 20
                        }];

                        XLSX.utils.book_append_sheet(wb, ws, "Income Payment Report");
                        XLSX.writeFile(wb, fileName);

                    } else if (currentReportType === 'income_lost_ticket') {
                        const fileName = `Income_Lost_Ticket_${startDate}_to_${endDate}.xlsx`;
                        const ws_name = "Income Lost Ticket";
                        const colCount = 44;

                        let exportData = [];
                        exportData.push([reportTitle]);
                        exportData.push([reportPeriod]);
                        exportData.push([]);

                        const headers = [
                            ["Tgl", "Hari", "Mobil", ...Array(9).fill(null), "Motor", ...Array(9).fill(
                                    null), "Box", ...Array(9).fill(null), "Total", ...Array(9).fill(null),
                                "Grand Total", null
                            ],
                            [null, null, "QRIS", null, "Flazz", null, "Emoney", null, "Brizzi", null,
                                "Tapcash", null, "QRIS", null, "Flazz", null, "Emoney", null, "Brizzi",
                                null, "Tapcash", null, "QRIS", null, "Flazz", null, "Emoney", null,
                                "Brizzi", null, "Tapcash", null, "QRIS", null, "Flazz", null, "Emoney",
                                null, "Brizzi", null, "Tapcash", null, null, null
                            ],
                            [null, null, "Qty", "Inc", "Qty", "Inc", "Qty", "Inc", "Qty", "Inc", "Qty",
                                "Inc", "Qty", "Inc", "Qty", "Inc", "Qty", "Inc", "Qty", "Inc", "Qty", "Inc",
                                "Qty", "Inc", "Qty", "Inc", "Qty", "Inc", "Qty", "Inc", "Qty", "Inc", "Qty",
                                "Inc", "Qty", "Inc", "Qty", "Inc", "Qty", "Inc", "Qty", "Inc", "Qty", "Inc"
                            ]
                        ];

                        const details = rawDataForExport.data[1].DetailTransaction;
                        const summary = rawDataForExport.data[0].summaryTotal;

                        const bodyData = details.map(item => {
                            const rowData = [item.Tanggal ? new Date(item.Tanggal) : null, item.Hari];
                            ['Mobil', 'Motor', 'Box'].forEach(vType => {
                                ['Qris', 'Flazz', 'Emoney', 'Brizzi', 'Tapcash'].forEach(
                                    pType => {
                                        rowData.push(Number(item[`Qty${vType}${pType}`] ||
                                            0));
                                        rowData.push(Number(item[`Inc${vType}${pType}`] ||
                                            0));
                                    });
                            });
                            let totalRowQty = 0,
                                totalRowInc = 0;
                            ['Qris', 'Flazz', 'Emoney', 'Brizzi', 'Tapcash'].forEach(pType => {
                                const qty = (item[`QtyMobil${pType}`] || 0) + (item[
                                    `QtyMotor${pType}`] || 0) + (item[`QtyBox${pType}`] ||
                                    0);
                                const inc = (item[`IncMobil${pType}`] || 0) + (item[
                                    `IncMotor${pType}`] || 0) + (item[`IncBox${pType}`] ||
                                    0);
                                rowData.push(qty, inc);
                                totalRowQty += qty;
                                totalRowInc += inc;
                            });
                            rowData.push(totalRowQty, totalRowInc);
                            return rowData;
                        });

                        const footerData = [
                            []
                        ];
                        footerData[0][0] = "Total";
                        footerData[0][1] = null;
                        let totalGrandQty = 0,
                            totalGrandInc = 0;
                        ['Mobil', 'Motor', 'Box'].forEach(vType => {
                            ['Qris', 'Flazz', 'Emoney', 'Brizzi', 'Tapcash'].forEach(pType => {
                                footerData[0].push(Number(summary[`TotalQty${vType}${pType}`] ||
                                    0));
                                footerData[0].push(Number(summary[`TotalInc${vType}${pType}`] ||
                                    0));
                            });
                        });
                        ['Qris', 'Flazz', 'Emoney', 'Brizzi', 'Tapcash'].forEach(pType => {
                            const qty = (summary[`TotalQtyMobil${pType}`] || 0) + (summary[
                                `TotalQtyMotor${pType}`] || 0) + (summary[`TotalQtyBox${pType}`] ||
                                0);
                            const inc = (summary[`TotalIncMobil${pType}`] || 0) + (summary[
                                `TotalIncMotor${pType}`] || 0) + (summary[`TotalIncBox${pType}`] ||
                                0);
                            footerData[0].push(qty, inc);
                            totalGrandQty += qty;
                            totalGrandInc += inc;
                        });
                        footerData[0].push(totalGrandQty, totalGrandInc);

                        exportData = exportData.concat(headers, bodyData, footerData);
                        const ws = XLSX.utils.aoa_to_sheet(exportData);

                        const merges = [{
                            s: {
                                r: 0,
                                c: 0
                            },
                            e: {
                                r: 0,
                                c: colCount - 1
                            }
                        }, {
                            s: {
                                r: 1,
                                c: 0
                            },
                            e: {
                                r: 1,
                                c: colCount - 1
                            }
                        }, {
                            s: {
                                r: 3,
                                c: 0
                            },
                            e: {
                                r: 5,
                                c: 0
                            }
                        }, {
                            s: {
                                r: 3,
                                c: 1
                            },
                            e: {
                                r: 5,
                                c: 1
                            }
                        }, {
                            s: {
                                r: 3,
                                c: 2
                            },
                            e: {
                                r: 3,
                                c: 11
                            }
                        }, {
                            s: {
                                r: 3,
                                c: 12
                            },
                            e: {
                                r: 3,
                                c: 21
                            }
                        }, {
                            s: {
                                r: 3,
                                c: 22
                            },
                            e: {
                                r: 3,
                                c: 31
                            }
                        }, {
                            s: {
                                r: 3,
                                c: 32
                            },
                            e: {
                                r: 3,
                                c: 41
                            }
                        }, {
                            s: {
                                r: 3,
                                c: 42
                            },
                            e: {
                                r: 4,
                                c: 43
                            }
                        }, ];
                        for (let i = 2; i < 42; i += 2) {
                            merges.push({
                                s: {
                                    r: 4,
                                    c: i
                                },
                                e: {
                                    r: 4,
                                    c: i + 1
                                }
                            });
                        }
                        merges.push({
                            s: {
                                r: 6 + bodyData.length,
                                c: 0
                            },
                            e: {
                                r: 6 + bodyData.length,
                                c: 1
                            }
                        });
                        ws['!merges'] = merges;

                        const borderStyle = {
                            top: {
                                style: "thin"
                            },
                            bottom: {
                                style: "thin"
                            },
                            left: {
                                style: "thin"
                            },
                            right: {
                                style: "thin"
                            }
                        };
                        const range = XLSX.utils.decode_range(ws['!ref']);
                        for (let R = range.s.r; R <= range.e.r; ++R) {
                            for (let C = range.s.c; C <= range.e.c; ++C) {
                                const cell_ref = XLSX.utils.encode_cell({
                                    c: C,
                                    r: R
                                });
                                if (!ws[cell_ref]) continue;

                                let style = {};
                                if (R === 0) {
                                    style.font = {
                                        bold: true,
                                        sz: 16
                                    };
                                    style.alignment = {
                                        horizontal: 'center'
                                    };
                                } else if (R === 1) {
                                    style.font = {
                                        italic: true,
                                        sz: 12
                                    };
                                    style.alignment = {
                                        horizontal: 'center'
                                    };
                                }
                                if (R >= 3 && R < exportData.length) {
                                    style.border = borderStyle;
                                }
                                if (R >= 3 && R <= 5) {
                                    style.alignment = {
                                        ...(style.alignment || {}),
                                        vertical: "center",
                                        horizontal: "center",
                                        wrapText: true
                                    };
                                }
                                const footerRowIndex = 6 + bodyData.length;
                                if (R === footerRowIndex) {
                                    style.font = {
                                        ...(style.font || {}),
                                        bold: true
                                    };
                                }
                                if (R >= 6) {
                                    if (C === 0 && ws[cell_ref].v instanceof Date) {
                                        ws[cell_ref].t = 'd';
                                        style.numFmt = 'dd/mm/yyyy';
                                    } else if (C >= 2 && typeof ws[cell_ref].v === 'number') {
                                        ws[cell_ref].t = 'n';
                                        style.numFmt = '#,##0';
                                    }
                                }
                                ws[cell_ref].s = style;
                            }
                        }

                        ws['!cols'] = Array(colCount).fill({
                            wch: 12
                        });

                        XLSX.utils.book_append_sheet(wb, ws, ws_name);
                        XLSX.writeFile(wb, fileName);
                    } else {
                        alert("Tipe laporan ini tidak didukung untuk ekspor.");
                    }
                });
                // =================================================================
                // END: FUNGSI EKSPOR EXCEL YANG DIPERBARUI
                // =================================================================
            });
        </script>
    </body>

    </html>
@endsection
