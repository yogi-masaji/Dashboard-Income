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

    <style>
        /* General table and layout styles from summaryreportsearch */
        #incomeGateSearch_wrapper .dt-top {
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
            padding: 20px !important;
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

        /* --- START: Dark Mode Styles --- */
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
        h5 {
            color: #000000
        }

        .mode-gelap .fw-medium,
        .mode-gelap .form-label,
        .mode-gelap h5 {
            color: #ffffff;
        }

        /* --- END: Dark Mode Styles --- */
    </style>

    <div class="search-wrapper card shadow-sm p-4 border-0 rounded-3">
        <h5 class="mb-3 fw-semibold">Income Gate Search</h5>
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

    <div class="content-custom mt-4">
        <div id="chartContainer" style="position: relative; height: 350px; display: none;" class="mb-4">
            <canvas id="incomeGateBar"></canvas>
        </div>
        <table id="incomeGateSearch" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Pos Out</th>
                    <th>Income</th>
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
            const incomeGateSearchTable = $('#incomeGateSearch').DataTable({
                pageLength: 25,
                ordering: true,
                lengthChange: false,
                searching: false, // You can enable this if you want a search box
                data: [], // Start with empty data
                columns: [{
                        data: 'no',
                    },
                    {
                        data: 'pos_out',
                    },
                    {
                        data: 'income',
                    }
                ],
                language: {
                    emptyTable: "Silakan pilih rentang tanggal, lalu klik 'Cari' untuk melihat data.",
                    zeroRecords: "Data tidak ditemukan untuk tanggal yang dipilih."
                }
            });

            let incomeChart = null; // Variable to hold the chart instance

            $('#cari').click(function() {
                const $cariButton = $(this);
                const startDate = picker.getStartDate()?.format('YYYY-MM-DD');
                const endDate = picker.getEndDate()?.format('YYYY-MM-DD');

                // Validation
                if (!startDate || !endDate) {
                    $('#alertMessage').text('Silakan pilih rentang tanggal terlebih dahulu.').show();
                    return;
                } else {
                    $('#alertMessage').hide();
                }

                // Disable button and show spinner
                $cariButton.prop('disabled', true);
                incomeGateSearchTable.clear().draw();
                const spinnerHtml = `
                    <tr>
                        <td colspan="3">
                            <div class="spinner-container">
                                <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                                <strong>Loading...</strong>
                            </div>
                        </td>
                    </tr>
                `;
                $('#incomeGateSearch tbody').html(spinnerHtml);
                $('#chartContainer').hide(); // Hide chart container

                // Helper function
                const formatRupiah = (number) => {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(number);
                };

                // AJAX call
                $.ajax({
                    url: '{{ route('incomeGateSearchApi') }}',
                    method: 'POST',
                    data: {
                        start1: startDate,
                        end1: endDate,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        let res = (typeof response === 'string') ? JSON.parse(response) :
                            response;
                        const incomeGate = res.data[0]?.income_gate_periode || [];

                        const formattedIncomeGate = incomeGate.map((item, index) => ({
                            no: index + 1,
                            pos_out: item.poscode,
                            income: formatRupiah(item.totalincome)
                        }));

                        incomeGateSearchTable.clear().rows.add(formattedIncomeGate).draw();

                        if (incomeGate.length > 0) {
                            $('#chartContainer').show();

                            // Chart logic
                            const colors = [
                                '#D10300', '#9966FF', '#7E00F5', '#36A2EB', '#00FFFF',
                                '#FF6347',
                                '#E67E00', '#F4A460', '#FFCE56', '#90EE90', '#148F49',
                                '#708090',
                                '#DAB370', '#F8AD2B', '#DFC639', '#E3E32A', '#00943E',
                                '#0E17C4',
                                '#057385', '#101A9F', '#4F236E', '#634E32', '#C233EE',
                                '#BC8F8F'
                            ];

                            const combined = incomeGate.map(item => ({
                                poscode: item.poscode,
                                totalincome: item.totalincome
                            })).sort((a, b) => b.totalincome - a.totalincome);

                            const labels = combined.map(item => item.poscode);
                            const dataIncome = combined.map(item => item.totalincome);
                            const backgroundColors = labels.map((_, index) => colors[index %
                                colors.length]);

                            const incomeBarData = {
                                labels: labels,
                                datasets: [{
                                    label: 'Statistik Income Gate',
                                    data: dataIncome,
                                    backgroundColor: backgroundColors,
                                    borderWidth: 1
                                }]
                            };

                            const textColor = $('body').hasClass('mode-gelap') ? '#fff' :
                                '#666';

                            const incomeBarConfig = {
                                type: 'bar',
                                data: incomeBarData,
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            labels: {
                                                color: textColor
                                            }
                                        },
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            ticks: {
                                                color: textColor
                                            }
                                        },
                                        x: {
                                            ticks: {
                                                color: textColor
                                            }
                                        }
                                    }
                                }
                            };

                            const ctx = document.getElementById('incomeGateBar').getContext(
                                '2d');
                            if (incomeChart) {
                                incomeChart.destroy(); // Destroy previous chart instance
                            }
                            incomeChart = new Chart(ctx, incomeBarConfig);
                        }
                    },
                    error: function(xhr) {
                        console.error("AJAX Error:", xhr.responseText);
                        const errorHtml = `
                            <tr>
                                <td colspan="3" class="text-center text-danger" style="padding: 20px;">
                                    Terjadi kesalahan saat mengambil data. Silakan coba lagi.
                                </td>
                            </tr>
                        `;
                        $('#incomeGateSearch tbody').html(errorHtml);
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
