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

    {{-- CSS untuk Date Range Picker --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.1/dist/index.css" />
    <style>
        /* Menyesuaikan style card agar konsisten */
        .card-header {
            font-weight: bold;
        }

        .content-custom {
            padding: 1rem;
            border-radius: 0.5rem;
            background-color: var(--bs-card-bg, #fff);
            border: 1px solid var(--bs-card-border-color, #dee2e6);
        }

        .form-label {
            margin-bottom: 0.5rem;
            color: #212529;
        }

        .mode-gelap .form-label {
            color: #fff;
        }

        b {
            color: #000000;
        }

        .mode-gelap b {
            color: #fff;
        }

        .easepick-wrapper {
            z-index: 9999 !important;
        }
    </style>


    <div class="container-fluid mt-4">
        {{-- Card untuk header pencarian --}}
        <div class="card mb-4 content-custom">
            <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-bottom-0">
                <div class="d-flex align-items-center">
                    <i class="fas fa-search me-2"></i>
                    <h5 class="mb-0"><b>Long Stay Search</b></h5>
                </div>

            </div>
            <div class="card-body">
                {{-- Form untuk input tanggal --}}
                <form id="formPicker">
                    @csrf {{-- Token CSRF untuk keamanan Laravel --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="datepicker1" class="form-label">Periode Pertama</label>
                            <input id="datepicker1" class="form-control" placeholder="Pilih rentang tanggal..." />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="datepicker2" class="form-label">Periode Kedua</label>
                            <input id="datepicker2" class="form-control" placeholder="Pilih rentang tanggal..." />
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <button type="submit" id="btn_submit" class="btn btn-submit">
                                <span id="btn-text">Cari</span>
                                <span id="btn-loader" class="spinner-border spinner-border-sm" role="status"
                                    aria-hidden="true" style="display: none;"></span>
                            </button>
                        </div>
                    </div>
                    <!-- Alert Message -->
                    <div id="alertMessage" class="alert alert-danger mt-3" role="alert" style="display: none;">
                        Silakan isi semua kolom tanggal sebelum mengirim.
                    </div>
                </form>
            </div>
        </div>

        {{-- Container untuk menampilkan hasil data (chart dan tabel) --}}
        <div id="results-container" style="display: none;">
            <div class="card mb-4 content-custom">
                <div class="card-header bg-transparent">
                    <i class="fas fa-chart-bar me-2"></i>
                    <b>Data Hasil Pencarian</b>
                </div>
                <div class="card-body">
                    {{-- Mobil --}}
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <h5 class="text-center">Mobil Casual</h5>
                            <canvas id="barCarCas"></canvas>
                            <div class="table-responsive mt-3">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kondisi</th>
                                            <th class="text-center first_period_header">Periode 1</th>
                                            <th class="text-center second_period_header">Periode 2</th>
                                            <th class="text-center">Persentase</th>
                                        </tr>
                                    </thead>
                                    <tbody id="car_casual"></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <h5 class="text-center">Mobil Pass</h5>
                            <canvas id="barCarPas"></canvas>
                            <div class="table-responsive mt-3">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kondisi</th>
                                            <th class="text-center first_period_header">Periode 1</th>
                                            <th class="text-center second_period_header">Periode 2</th>
                                            <th class="text-center">Persentase</th>
                                        </tr>
                                    </thead>
                                    <tbody id="car_pass"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <hr>
                    {{-- Motor --}}
                    <div class="row mt-4">
                        <div class="col-md-6 mb-4">
                            <h5 class="text-center">Motor Casual</h5>
                            <canvas id="barBikeCas"></canvas>
                            <div class="table-responsive mt-3">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kondisi</th>
                                            <th class="text-center first_period_header">Periode 1</th>
                                            <th class="text-center second_period_header">Periode 2</th>
                                            <th class="text-center">Persentase</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bike_casual"></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <h5 class="text-center">Motor Pass</h5>
                            <canvas id="barBikePas"></canvas>
                            <div class="table-responsive mt-3">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kondisi</th>
                                            <th class="text-center first_period_header">Periode 1</th>
                                            <th class="text-center second_period_header">Periode 2</th>
                                            <th class="text-center">Persentase</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bike_pass"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <hr>
                    {{-- Truk --}}
                    <div class="row mt-4">
                        <div class="col-md-6 mb-4">
                            <h5 class="text-center">Truck Casual</h5>
                            <canvas id="barTruckCas"></canvas>
                            <div class="table-responsive mt-3">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kondisi</th>
                                            <th class="text-center first_period_header">Periode 1</th>
                                            <th class="text-center second_period_header">Periode 2</th>
                                            <th class="text-center">Persentase</th>
                                        </tr>
                                    </thead>
                                    <tbody id="truck_casual"></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <h5 class="text-center">Truck Pass</h5>
                            <canvas id="barTruckPas"></canvas>
                            <div class="table-responsive mt-3">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kondisi</th>
                                            <th class="text-center first_period_header">Periode 1</th>
                                            <th class="text-center second_period_header">Periode 2</th>
                                            <th class="text-center">Persentase</th>
                                        </tr>
                                    </thead>
                                    <tbody id="truck_pass"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <hr>
                    {{-- Taxi --}}
                    <div class="row mt-4">
                        <div class="col-md-6 mb-4">
                            <h5 class="text-center">Taxi Casual</h5>
                            <canvas id="barTaxiCas"></canvas>
                            <div class="table-responsive mt-3">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kondisi</th>
                                            <th class="text-center first_period_header">Periode 1</th>
                                            <th class="text-center second_period_header">Periode 2</th>
                                            <th class="text-center">Persentase</th>
                                        </tr>
                                    </thead>
                                    <tbody id="taxi_casual"></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <h5 class="text-center">Taxi Pass</h5>
                            <canvas id="barTaxiPas"></canvas>
                            <div class="table-responsive mt-3">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kondisi</th>
                                            <th class="text-center first_period_header">Periode 1</th>
                                            <th class="text-center second_period_header">Periode 2</th>
                                            <th class="text-center">Persentase</th>
                                        </tr>
                                    </thead>
                                    <tbody id="taxi_pass"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- JS untuk Date Range Picker --}}
    <script src="https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.1/dist/index.umd.min.js"></script>

    <script>
        // Inisialisasi objek untuk menyimpan chart instances
        let charts = {};

        // Fungsi untuk menghancurkan chart yang ada sebelum membuat yang baru
        function destroyChart(chartId) {
            if (charts[chartId]) {
                charts[chartId].destroy();
            }
        }

        // Fungsi untuk membuat atau mengupdate chart
        function createOrUpdateChart(chartId, labels, datasets) {
            destroyChart(chartId); // Hancurkan chart lama jika ada
            const ctx = document.getElementById(chartId).getContext('2d');
            charts[chartId] = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        }
                    },
                    scales: {
                        x: {
                            stacked: true,
                        },
                        y: {
                            stacked: true,
                            beginAtZero: true,
                            // Menambahkan 'grace' untuk memberi ruang ekstra di atas bar tertinggi
                            grace: '10%'
                        }
                    }
                }
            });
        }

        $(document).ready(function() {
            // Inisialisasi date range picker
            const picker1 = new easepick.create({
                element: document.getElementById('datepicker1'),
                css: [
                    'https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.1/dist/index.css',
                ],
                plugins: ['RangePlugin'],
                RangePlugin: {
                    delimiter: ' to ',
                },
                format: 'YYYY-MM-DD'
            });

            const picker2 = new easepick.create({
                element: document.getElementById('datepicker2'),
                css: [
                    'https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.1/dist/index.css',
                ],
                plugins: ['RangePlugin'],
                RangePlugin: {
                    delimiter: ' to ',
                },
                format: 'YYYY-MM-DD'
            });

            $('#formPicker').submit(function(e) {
                e.preventDefault(); // Mencegah form submit default

                // Mengambil nilai dari date picker
                const startDate1 = picker1.getStartDate() ? picker1.getStartDate().format('YYYY-MM-DD') :
                    null;
                const endDate1 = picker1.getEndDate() ? picker1.getEndDate().format('YYYY-MM-DD') : null;
                const startDate2 = picker2.getStartDate() ? picker2.getStartDate().format('YYYY-MM-DD') :
                    null;
                const endDate2 = picker2.getEndDate() ? picker2.getEndDate().format('YYYY-MM-DD') : null;

                // Validasi input
                if (!startDate1 || !endDate1 || !startDate2 || !endDate2) {
                    $('#alertMessage').text('Silakan pilih rentang tanggal yang valid untuk kedua periode.')
                        .show();
                    return;
                } else {
                    $('#alertMessage').hide();
                }

                // Menampilkan loader dan menonaktifkan tombol
                const btn = $('#btn_submit');
                const btnText = $('#btn-text');
                const btnLoader = $('#btn-loader');

                btnText.hide();
                btnLoader.show();
                btn.prop('disabled', true);

                // Menyiapkan data untuk AJAX
                const ajaxData = {
                    tgl_awal: startDate1,
                    tgl_akhir: endDate1,
                    tgl_awal_row2: startDate2,
                    tgl_akhir_row2: endDate2,
                    _token: '{{ csrf_token() }}'
                };

                // AJAX call ke controller Laravel
                $.ajax({
                    type: "POST",
                    url: "{{ route('longstay.search') }}",
                    data: ajaxData,
                    dataType: "JSON",
                    success: function(response) {
                        $('#results-container').show();

                        var first_period = response[0];
                        var second_period = response[1];

                        const processedData = {
                            car: {
                                casual: {},
                                pass: {}
                            },
                            bike: {
                                casual: {},
                                pass: {}
                            },
                            truck: {
                                casual: {},
                                pass: {}
                            },
                            taxi: {
                                casual: {},
                                pass: {}
                            },
                        };

                        const populateData = (periodData, periodKey) => {
                            periodData.forEach(item => {
                                let vehicle, type;
                                switch (item.vehicle_type) {
                                    case 'Mobil':
                                        vehicle = 'car';
                                        break;
                                    case 'Motorbike':
                                        vehicle = 'bike';
                                        break;
                                    case 'Truck':
                                        vehicle = 'truck';
                                        break;
                                    case 'Taxi':
                                        vehicle = 'taxi';
                                        break;
                                    default:
                                        return;
                                }
                                type = item.transaction_Type.toLowerCase();

                                if (processedData[vehicle] && processedData[vehicle]
                                    [type]) {
                                    processedData[vehicle][type][item
                                        .long_stay_period
                                    ] = {
                                        ...processedData[vehicle][type][item
                                            .long_stay_period
                                        ],
                                        [periodKey]: parseInt(item.quantity)
                                    };
                                }
                            });
                        };

                        populateData(first_period, 'fp');
                        populateData(second_period, 'sp');

                        function calculatePercentage(fp, sp) {
                            fp = fp || 0;
                            sp = sp || 0;
                            if (fp === 0 && sp !== 0) return 100;
                            if (fp === 0 && sp === 0) return 0;
                            return ((sp - fp) / fp) * 100;
                        }

                        const conditions = [{
                                key: 'CASUAL_LESS_THAN_TEN_MINUTE',
                                label: '< 10 Menit'
                            },
                            {
                                key: 'CASUAL_LESS_THAN_ONE_HOUR',
                                label: '< 1 Jam'
                            },
                            {
                                key: 'CASUAL_BETWEEN_ONE_AND_TWO_HOUR',
                                label: '1 - 2 Jam'
                            },
                            {
                                key: 'CASUAL_BETWEEN_TWO_AND_THREE_HOUR',
                                label: '2 - 3 Jam'
                            },
                            {
                                key: 'CASUAL_BETWEEN_THREE_AND_FOUR_HOUR',
                                label: '3 - 4 Jam'
                            },
                            {
                                key: 'CASUAL_BETWEEN_FOUR_AND_FIVE_HOUR',
                                label: '4 - 5 Jam'
                            },
                            {
                                key: 'CASUAL_MORE_THAN_FIVE_HOUR',
                                label: '> 5 Jam'
                            },
                        ];

                        const generateTableRows = (vehicle, type) => {
                            let html = '';
                            let total_fp = 0;
                            let total_sp = 0;

                            conditions.forEach((cond, index) => {
                                const data = processedData[vehicle][type][cond
                                    .key
                                ] || {};
                                const fp = data.fp || 0;
                                const sp = data.sp || 0;
                                const percent = calculatePercentage(fp, sp);
                                total_fp += fp;
                                total_sp += sp;

                                html += `<tr>
                                    <td>${index + 1}</td>
                                    <td>${cond.label}</td>
                                    <td class="text-center">${fp.toLocaleString()}</td>
                                    <td class="text-center">${sp.toLocaleString()}</td>
                                    <td class="text-center ${percent >= 0 ? 'text-success' : 'text-danger'}">${percent.toFixed(2)}%</td>
                                </tr>`;
                            });

                            const total_percent = calculatePercentage(total_fp, total_sp);
                            html += `<tr class="font-weight-bold">
                                <td colspan="2"><b>Grand Total</b></td>
                                <td class="text-center"><b>${total_fp.toLocaleString()}</b></td>
                                <td class="text-center"><b>${total_sp.toLocaleString()}</b></td>
                                <td class="text-center ${total_percent >= 0 ? 'text-success' : 'text-danger'}"><b>${total_percent.toFixed(2)}%</b></td>
                            </tr>`;

                            return html;
                        };

                        $('#car_casual').html(generateTableRows('car', 'casual'));
                        $('#car_pass').html(generateTableRows('car', 'pass'));
                        $('#bike_casual').html(generateTableRows('bike', 'casual'));
                        $('#bike_pass').html(generateTableRows('bike', 'pass'));
                        $('#truck_casual').html(generateTableRows('truck', 'casual'));
                        $('#truck_pass').html(generateTableRows('truck', 'pass'));
                        $('#taxi_casual').html(generateTableRows('taxi', 'casual'));
                        $('#taxi_pass').html(generateTableRows('taxi', 'pass'));

                        $('.first_period_header').text(`${startDate1} to ${endDate1}`);
                        $('.second_period_header').text(`${startDate2} to ${endDate2}`);

                        const generateChartDatasets = (vehicle, type) => {
                            const colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                                '#9966FF', '#FF9F40', '#C9CBCF'
                            ];
                            return conditions.map((cond, index) => {
                                const data = processedData[vehicle][type][cond
                                    .key
                                ] || {};
                                return {
                                    label: cond.label,
                                    data: [data.fp || 0, data.sp || 0],
                                    backgroundColor: colors[index % colors.length],
                                }
                            });
                        };

                        const chartLabels = [`Periode 1`, `Periode 2`];

                        createOrUpdateChart('barCarCas', chartLabels, generateChartDatasets(
                            'car', 'casual'));
                        createOrUpdateChart('barCarPas', chartLabels, generateChartDatasets(
                            'car', 'pass'));
                        createOrUpdateChart('barBikeCas', chartLabels, generateChartDatasets(
                            'bike', 'casual'));
                        createOrUpdateChart('barBikePas', chartLabels, generateChartDatasets(
                            'bike', 'pass'));
                        createOrUpdateChart('barTruckCas', chartLabels, generateChartDatasets(
                            'truck', 'casual'));
                        createOrUpdateChart('barTruckPas', chartLabels, generateChartDatasets(
                            'truck', 'pass'));
                        createOrUpdateChart('barTaxiCas', chartLabels, generateChartDatasets(
                            'taxi', 'casual'));
                        createOrUpdateChart('barTaxiPas', chartLabels, generateChartDatasets(
                            'taxi', 'pass'));

                        // Memastikan chart diperbarui warnanya sesuai tema saat ini
                        if (window.updateChartColors) {
                            const isDarkMode = document.body.classList.contains('mode-gelap');
                            window.updateChartColors(isDarkMode);
                        }

                    },
                    error: function(xhr, status, error) {
                        const err = JSON.parse(xhr.responseText);
                        alert("Error: " + err.message);
                        console.error("AJAX Error:", xhr.responseText);
                    },
                    complete: function() {
                        btnText.show();
                        btnLoader.hide();
                        btn.prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endsection
