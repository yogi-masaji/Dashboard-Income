@extends('layout.nav')
@section('content')
    {{-- Variabel-variabel ini diambil dari session --}}
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
    {{-- CDN for easepick --}}
    <script src="https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.1/dist/index.umd.min.js"></script>

    <style>
        .dt-button {
            background-color: #FCB900 !important;
            padding: 10px;
            border-radius: 10px;
            border: none !important;
        }

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

        .form-label {
            color: #000;
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

        .easepick-wrapper {
            z-index: 9999 !important;
        }
    </style>

    {{-- ================================================================= --}}
    {{-- KONDISIONAL BERDASARKAN LOKASI (menggunakan variabel $kodeLokasi dari session) --}}
    {{-- ================================================================= --}}

    @if (isset($kodeLokasi) && in_array($kodeLokasi, ['PMBE', 'GACI', 'BMP']))
        {{-- ================================================================= --}}
        {{-- TAMPILAN KHUSUS UNTUK PMBE, GACI, BMP                           --}}
        {{-- ================================================================= --}}
        <div class="search-wrapper card shadow-sm p-4 border-0 rounded-3">
            <h5 class="mb-3 fw-semibold">Quantity Per Gate Search ({{ $kodeLokasi }})</h5>
            <form id="formPMBE">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="tgl_awal" class="form-label">Tanggal Awal</label>
                        <input id="tgl_awal" type="date" class="form-control" name="tgl_awal">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="tgl_akhir" class="form-label">Tanggal Akhir</label>
                        <input id="tgl_akhir" type="date" class="form-control" name="tgl_akhir">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="gate_option" class="form-label">Gate</label>
                        <select id="gate_option" name="gate_option" class="form-select">
                            <option value="">Pilih Gate</option>
                            <option value="IN">IN</option>
                            <option value="OUT">OUT</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="submit" class="btn btn-submit px-4" id="cariPMBE">
                        <i class="bi bi-search me-1"></i> Cari
                    </button>
                    <div id="alertMessage" class="alert alert-danger py-2 px-3 mb-0 small flex-grow-1 d-none"
                        role="alert"></div>
                </div>
            </form>
        </div>

        <div class="card shadow-sm p-4 border-0 rounded-3 mt-4">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="table-perGate" style="width:100%">
                    <thead>
                        <tr>
                            <th>Vehicle</th>
                            <th>Gate</th>
                            <th>00-01</th>
                            <th>01-02</th>
                            <th>02-03</th>
                            <th>03-04</th>
                            <th>04-05</th>
                            <th>05-06</th>
                            <th>06-07</th>
                            <th>07-08</th>
                            <th>08-09</th>
                            <th>09-10</th>
                            <th>10-11</th>
                            <th>11-12</th>
                            <th>12-13</th>
                            <th>13-14</th>
                            <th>14-15</th>
                            <th>15-16</th>
                            <th>16-17</th>
                            <th>17-18</th>
                            <th>18-19</th>
                            <th>19-20</th>
                            <th>20-21</th>
                            <th>21-22</th>
                            <th>22-23</th>
                            <th>23-00</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="26" class="text-center p-5">
                                <i class="bi bi-calendar-week fs-1 text-muted"></i>
                                <p class="mt-2 text-muted">Pilih tanggal dan gate untuk melihat data.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @else
        {{-- ================================================================= --}}
        {{-- TAMPILAN STANDAR UNTUK LOKASI LAIN                              --}}
        {{-- ================================================================= --}}
        <div class="search-wrapper card shadow-sm p-4 border-0 rounded-3">
            <h5 class="mb-3 fw-semibold">Quantity Per Gate Search</h5>
            <div class="mb-3">
                <label for="datepicker" class="form-label">Rentang Tanggal</label>
                <input id="datepicker" class="form-control" placeholder="Pilih rentang tanggal" />
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-submit px-4" id="cari">
                    <i class="bi bi-search me-1"></i> Cari
                </button>
                <div id="alertMessage" class="alert alert-danger py-2 px-3 mb-0 small flex-grow-1 d-none" role="alert">
                    Silakan pilih rentang tanggal terlebih dahulu.
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm p-4 border-0 rounded-3 h-100">
                    <h6 class="fw-semibold mb-3">Berdasarkan Pos</h6>
                    <div class="table-responsive">
                        <table id="tablePos" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Jam</th>
                                    <th>Pos 1</th>
                                    <th>Pos 8</th>
                                    <th>Pos 9</th>
                                    <th>Buffer Area</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6" class="text-center p-5">
                                        <i class="bi bi-calendar-week fs-1 text-muted"></i>
                                        <p class="mt-2 text-muted">Pilih tanggal untuk melihat data.</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm p-4 border-0 rounded-3 h-100">
                    <h6 class="fw-semibold mb-3">Berdasarkan Golongan</h6>
                    <div class="table-responsive">
                        <table id="tableGolongan" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Jam</th>
                                    <th>Mobil</th>
                                    <th>Motor</th>
                                    <th>Truk</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5" class="text-center p-5">
                                        <i class="bi bi-calendar-week fs-1 text-muted"></i>
                                        <p class="mt-2 text-muted">Pilih tanggal untuk melihat data.</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        $(document).ready(function() {
            // Mengambil variabel dari PHP (Blade) ke JavaScript
            const locationCode = @json($kodeLokasi); // Langsung menggunakan $kodeLokasi dari session
            const isPmbeLocation = ['PMBE', 'GACI', 'BMP'].includes(locationCode);

            // =================================================================
            // SCRIPT UNTUK LOKASI PMBE, GACI, BMP
            // =================================================================
            if (isPmbeLocation) {
                let dataTable = $('#table-perGate').DataTable({
                    paging: false,
                    searching: false,
                    ordering: false,
                    info: false,
                    dom: 'Bfrtip',
                    buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                    language: {
                        emptyTable: "Tidak ada data untuk ditampilkan"
                    },
                    createdRow: function(row, data, dataIndex) {
                        const isVehicleHeaderRow = data[0] !== '' && data[1] === '' && data.slice(2)
                            .every(item => item === '');
                        if (isVehicleHeaderRow) {
                            const $firstCell = $(row).find('td:first-child');
                            $firstCell.attr('colspan', 26);
                            $firstCell.css({
                                'background-color': '#083a62',
                                'color': '#fff',
                                'font-weight': 'bold',
                                'text-align': 'center',
                                'font-size': '15px',
                            });
                            $(row).find('td:gt(0)').hide();
                        }
                    }
                });

                $('#formPMBE').submit(function(e) {
                    e.preventDefault();
                    const $cariButton = $('#cariPMBE');
                    const $alert = $('#alertMessage');

                    const tgl_awal = $('#tgl_awal').val();
                    const tgl_akhir = $('#tgl_akhir').val();
                    const gate_option = $('#gate_option').val();

                    if (!tgl_awal || !tgl_akhir || !gate_option) {
                        $alert.text('Silakan lengkapi semua field.').removeClass('d-none');
                        return;
                    }
                    $alert.addClass('d-none');
                    $cariButton.prop('disabled', true).html(
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mencari...'
                    );

                    const spinner =
                        '<tr><td colspan="26"><div class="spinner-container"><div class="lds-ring"><div></div><div></div><div></div><div></div></div><strong>Memuat data...</strong></div></td></tr>';
                    dataTable.clear().draw();
                    $('#table-perGate tbody').html(spinner);

                    $.ajax({
                        url: '{{ route('quantitypergatePmbeAPI') }}',
                        method: 'POST',
                        data: {
                            tgl_awal: tgl_awal,
                            tgl_akhir: tgl_akhir,
                            gate_option: gate_option,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            $('#table-perGate tbody').empty();
                            if (response && response.data) {
                                const apiData = response.data;
                                let newData = [];
                                const VEHICLE_TYPES_CONFIG = [{
                                        key: 'car',
                                        displayName: 'Car'
                                    },
                                    {
                                        key: 'motorcycle',
                                        displayName: 'Motorcycle'
                                    },
                                    {
                                        key: 'truck',
                                        displayName: 'TRUCK'
                                    },
                                    {
                                        key: 'other',
                                        displayName: 'Other'
                                    }
                                ];
                                const TIME_SLOTS_CONFIG = [
                                    "00-01", "01-02", "02-03", "03-04", "04-05", "05-06",
                                    "06-07", "07-08",
                                    "08-09", "09-10", "10-11", "11-12", "12-13", "13-14",
                                    "14-15", "15-16",
                                    "16-17", "17-18", "18-19", "19-20", "20-21", "21-22",
                                    "22-23", "23-00"
                                ];

                                VEHICLE_TYPES_CONFIG.forEach(function(vehicleType) {
                                    const vehicleDataArray = apiData[vehicleType.key];
                                    if (vehicleDataArray && Array.isArray(
                                            vehicleDataArray) && vehicleDataArray
                                        .length > 0) {
                                        let vehicleHeaderRow = [vehicleType.displayName,
                                            ...Array(25).fill('')
                                        ];
                                        newData.push(vehicleHeaderRow);

                                        vehicleDataArray.forEach(function(gateEntry) {
                                            const gateName = Object.keys(
                                                gateEntry)[0];
                                            const gateHourlyData = gateEntry[
                                                gateName];
                                            let rowData = ['', gateName === "" ?
                                                "Other" : gateName
                                            ];
                                            TIME_SLOTS_CONFIG.forEach(slot =>
                                                rowData.push(gateHourlyData[
                                                    slot] || 0));
                                            newData.push(rowData);
                                        });
                                    }
                                });

                                if (newData.length > 0) {
                                    dataTable.rows.add(newData).draw();
                                } else {
                                    const noData =
                                        '<tr><td colspan="26" class="text-center p-5"><i class="bi bi-x-circle fs-1 text-danger"></i><p class="mt-2 text-danger">Data tidak ditemukan.</p></td></tr>';
                                    $('#table-perGate tbody').html(noData);
                                }

                            } else {
                                const noData =
                                    '<tr><td colspan="26" class="text-center p-5"><i class="bi bi-x-circle fs-1 text-danger"></i><p class="mt-2 text-danger">Data tidak ditemukan atau format respons salah.</p></td></tr>';
                                $('#table-perGate tbody').html(noData);
                            }
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                            const errorMsg =
                                '<tr><td colspan="26" class="text-center text-danger p-5"><i class="bi bi-exclamation-triangle fs-1"></i><p class="mt-2">Gagal memuat data.</p></td></tr>';
                            $('#table-perGate tbody').html(errorMsg);
                        },
                        complete: function() {
                            $cariButton.prop('disabled', false).html(
                                '<i class="bi bi-search me-1"></i> Cari');
                        }
                    });
                });

                // =================================================================
                // SCRIPT UNTUK LOKASI STANDAR (SELAIN PMBE, GACI, BMP)
                // =================================================================
            } else {
                const picker = new easepick.create({
                    element: document.getElementById('datepicker'),
                    css: ['https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.1/dist/index.css'],
                    plugins: ['RangePlugin'],
                    RangePlugin: {
                        format: 'YYYY-MM-DD',
                        delimiter: ' to '
                    }
                });

                const tablePos = $('#tablePos').DataTable({
                    paging: false,
                    searching: false,
                    ordering: false,
                    info: false,
                    autoWidth: false,
                    columns: [{
                            data: 'jam'
                        }, {
                            data: 'pos1'
                        }, {
                            data: 'pos8'
                        }, {
                            data: 'pos9'
                        },
                        {
                            data: 'buffer_area'
                        }, {
                            data: 'total'
                        }
                    ]
                });

                const tableGolongan = $('#tableGolongan').DataTable({
                    paging: false,
                    searching: false,
                    ordering: false,
                    info: false,
                    autoWidth: false,
                    columns: [{
                            data: 'jam'
                        }, {
                            data: 'mobil'
                        }, {
                            data: 'motor'
                        },
                        {
                            data: 'truk'
                        }, {
                            data: 'total'
                        }
                    ]
                });

                $('#cari').click(function() {
                    const $cariButton = $(this);
                    const startDate = picker.getStartDate()?.format('YYYY-MM-DD');
                    const endDate = picker.getEndDate()?.format('YYYY-MM-DD');

                    if (!startDate || !endDate) {
                        $('#alertMessage').text('Silakan pilih rentang tanggal terlebih dahulu.')
                            .removeClass('d-none');
                        return;
                    }
                    $('#alertMessage').addClass('d-none');

                    $cariButton.prop('disabled', true).html(
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mencari...'
                    );
                    tablePos.clear().draw();
                    tableGolongan.clear().draw();

                    const spinnerPos =
                        '<tr><td colspan="6"><div class="spinner-container"><div class="lds-ring"><div></div><div></div><div></div><div></div></div><strong>Memuat data...</strong></div></td></tr>';
                    const spinnerGolongan =
                        '<tr><td colspan="5"><div class="spinner-container"><div class="lds-ring"><div></div><div></div><div></div><div></div></div><strong>Memuat data...</strong></div></td></tr>';
                    $('#tablePos tbody').html(spinnerPos);
                    $('#tableGolongan tbody').html(spinnerGolongan);

                    $.ajax({
                        url: '{{ route('quantitypergateAPI') }}',
                        method: 'POST',
                        data: {
                            start1: startDate,
                            end1: endDate,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            $('#tablePos tbody').empty();
                            $('#tableGolongan tbody').empty();

                            if (response.success && response.per_pos && response.per_golongan) {
                                const {
                                    pos1,
                                    pos8,
                                    pos9,
                                    buffer_area
                                } = response.per_pos;
                                const {
                                    mobil,
                                    motor,
                                    truk
                                } = response.per_golongan;
                                const formatTimeRange = (timeRange) => timeRange ?
                                    `${timeRange.slice(0, 5)}-${timeRange.slice(6, 11)}` : '-';
                                const formatQuantity = (quantity) => new Intl.NumberFormat(
                                    'id-ID').format(quantity || 0);

                                const dataPos = pos1.map((item, index) => ({
                                    jam: formatTimeRange(item.time),
                                    pos1: formatQuantity(pos1[index]?.qty),
                                    pos8: formatQuantity(pos8[index]?.qty),
                                    pos9: formatQuantity(pos9[index]?.qty),
                                    buffer_area: formatQuantity(buffer_area[index]
                                        ?.qty),
                                    total: formatQuantity((pos1[index]?.qty || 0) +
                                        (pos8[index]?.qty || 0) + (pos9[index]
                                            ?.qty || 0) + (buffer_area[index]
                                            ?.qty || 0))
                                }));
                                tablePos.rows.add(dataPos).draw();

                                const dataGolongan = mobil.map((item, index) => ({
                                    jam: formatTimeRange(item.time),
                                    mobil: formatQuantity(mobil[index]?.qty),
                                    motor: formatQuantity(motor[index]?.qty),
                                    truk: formatQuantity(truk[index]?.qty),
                                    total: formatQuantity((mobil[index]?.qty || 0) +
                                        (motor[index]?.qty || 0) + (truk[index]
                                            ?.qty || 0))
                                }));
                                tableGolongan.rows.add(dataGolongan).draw();
                            } else {
                                const noDataPos =
                                    '<tr><td colspan="6" class="text-center p-5"><i class="bi bi-x-circle fs-1 text-danger"></i><p class="mt-2 text-danger">Data tidak ditemukan.</p></td></tr>';
                                const noDataGolongan =
                                    '<tr><td colspan="5" class="text-center p-5"><i class="bi bi-x-circle fs-1 text-danger"></i><p class="mt-2 text-danger">Data tidak ditemukan.</p></td></tr>';
                                $('#tablePos tbody').html(noDataPos);
                                $('#tableGolongan tbody').html(noDataGolongan);
                            }
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                            const errorPos =
                                '<tr><td colspan="6" class="text-center text-danger p-5"><i class="bi bi-exclamation-triangle fs-1"></i><p class="mt-2">Gagal memuat data.</p></td></tr>';
                            const errorGolongan =
                                '<tr><td colspan="5" class="text-center text-danger p-5"><i class="bi bi-exclamation-triangle fs-1"></i><p class="mt-2">Gagal memuat data.</p></td></tr>';
                            $('#tablePos tbody').html(errorPos);
                            $('#tableGolongan tbody').html(errorGolongan);
                        },
                        complete: function() {
                            $cariButton.prop('disabled', false).html(
                                '<i class="bi bi-search me-1"></i> Cari');
                        }
                    });
                });
            }
        });
    </script>
@endsection
