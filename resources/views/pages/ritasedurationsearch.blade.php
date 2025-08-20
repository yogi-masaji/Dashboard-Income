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

    <!-- Easepick Stylesheet -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.1/dist/index.css" />

    <style>
        /* Custom styles for content blocks */
        .content-custom {
            padding: 20px !important;
            background-color: #ffffff !important;
            border-radius: 10px !important;
            box-shadow: 1px -2px 15px -1px rgba(0, 0, 0, 0.28);
            color: #000000 !important;
        }

        /* Styling for DataTables buttons and search */
        .dt-buttons {
            display: inline-flex;
            gap: 10px;
        }

        .dt-button {
            background-color: #FCB900 !important;
            padding: 10px;
            border-radius: 10px;
            border: none !important;
            margin-bottom: 5px;
        }

        .dt-search input {
            height: 40px;
            border-radius: 10px;
            margin-left: 10px;
        }

        /* Wide table styles */
        .wide-data-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            font-size: 12px;
            text-align: center;
        }

        .wide-data-table th,
        .wide-data-table td {
            border: 1px solid #ddd;
            word-wrap: break-word;
        }

        .wide-data-table thead th {
            position: sticky;
            top: 0;
            z-index: 1;
        }

        table.dataTable thead th,
        table.dataTable tbody td,
        table.dataTable tfoot th {
            padding: 10px 0 !important;
        }

        /* Light Mode Text Color */
        .search-wrapper label,
        .result h5 {
            color: #000000 !important;
        }

        .form-select {
            color: #000000;
        }

        .mode-gelap .form-select {
            color: #ffffff;
        }

        /* Dark Mode Compatibility */
        .mode-gelap .content-custom {
            background-color: #1e293b !important;
            color: #e2e8f0 !important;
        }

        .mode-gelap .form-control,
        .mode-gelap .form-select {
            background-color: #334155;
            color: #e2e8f0;
            border-color: #475569;
        }

        .mode-gelap .search-wrapper label,
        .mode-gelap .result h5 {
            color: #e2e8f0 !important;
        }

        .mode-gelap .wide-data-table th,
        .mode-gelap .wide-data-table td {
            border-color: #475569;
        }

        .mode-gelap .wide-data-table thead th {
            background-color: #1e293b;
        }

        .mode-gelap .wide-data-table tbody td {
            background-color: #283447;
            /* Darker background for table body cells */
            color: #e2e8f0;
            /* White text for table body */
        }

        .mode-gelap .wide-data-table tfoot th {
            background-color: #1e293b;
        }


        /* Ensure easepick calendar appears above other elements */
        .easepick-wrapper {
            z-index: 1060;
        }
    </style>

    <div class="search-wrapper content-custom mb-4">
        <div class="row g-3 align-items-end">
            <!-- Date Range Picker -->
            <div class="col-md-4">
                <label for="datepicker" class="form-label">Date Range</label>
                <input id="datepicker" class="form-control" placeholder="Select date range..." />
            </div>
            <!-- Vehicle Type Dropdown -->
            <div class="col-md-3">
                <label for="jenis_kendaraan" class="form-label">Vehicle Type</label>
                <select name="selection" id="jenis_kendaraan" class="form-select">
                    <option value="ALL">ALL</option>
                    <option value="CAR">CAR</option>
                    <option value="MOTORCYCLE">MOTORCYCLE</option>
                    <option value="TRUCK">TRUCK</option>
                </select>
            </div>
            <!-- Vehicle Status Dropdown -->
            <div class="col-md-3">
                <label for="status_kendaraan" class="form-label">Vehicle Status</label>
                <select name="selection" id="status_kendaraan" class="form-select">
                    <option value="ALL">ALL</option>
                    <option value="CASUAL">CASUAL</option>
                    <option value="MEMBER">MEMBER</option>
                </select>
            </div>
            <!-- Search Button -->
            <div class="col-md-2">
                <button type="button" class="btn btn-submit w-100" id="cari">Cari</button>
            </div>
        </div>

        <!-- Alert Message -->
        <div id="alertMessage" class="alert alert-danger mt-3" role="alert" style="display: none;">
            Please select a valid date range before submitting.
        </div>
    </div>

    <div class="result mt-4">
        <div class="content-custom mb-5">
            <h5>Data Ritase By Duration</h5>
            <div class="chartRitase" style="height: 350px; display: none;">
                <canvas id="RitaseDurationChart"></canvas>
            </div>
        </div>
        <div class="content-custom">
            <div class="table-responsive">
                <table class="wide-data-table" id="ritaseDurationTable">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>01</th>
                            <th>02</th>
                            <th>03</th>
                            <th>04</th>
                            <th>05</th>
                            <th>06</th>
                            <th>07</th>
                            <th>08</th>
                            <th>09</th>
                            <th>10</th>
                            <th>11</th>
                            <th>12</th>
                            <th>13</th>
                            <th>14</th>
                            <th>15</th>
                            <th>16</th>
                            <th>17</th>
                            <th>18</th>
                            <th>19</th>
                            <th>20</th>
                            <th>21</th>
                            <th>22</th>
                            <th>23</th>
                            <th>24</th>
                            <th> > 24</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <th>Grand Total</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Easepick and Chart.js Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.1/dist/index.umd.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Easepick Date Range Picker
            const picker = new easepick.create({
                element: document.getElementById('datepicker'),
                css: [
                    'https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.1/dist/index.css',
                ],
                plugins: ['RangePlugin'],
                RangePlugin: {
                    delimiter: ' to ',
                },
                format: 'YYYY-MM-DD'
            });

            // Initialize DataTable
            const ritaseDurationTable = $('#ritaseDurationTable').DataTable({
                lengthChange: false,
                searching: false,
                paging: false,
                ordering: false,
                info: false,
                layout: {
                    topEnd: {
                        buttons: ['excelHtml5', {
                            extend: 'pdfHtml5',
                            orientation: 'landscape',
                            pageSize: 'LEGAL'
                        }],
                    },
                },
                columns: [{
                        data: 'tanggal'
                    }, {
                        data: '01'
                    }, {
                        data: '02'
                    }, {
                        data: '03'
                    },
                    {
                        data: '04'
                    }, {
                        data: '05'
                    }, {
                        data: '06'
                    }, {
                        data: '07'
                    },
                    {
                        data: '08'
                    }, {
                        data: '09'
                    }, {
                        data: '10'
                    }, {
                        data: '11'
                    },
                    {
                        data: '12'
                    }, {
                        data: '13'
                    }, {
                        data: '14'
                    }, {
                        data: '15'
                    },
                    {
                        data: '16'
                    }, {
                        data: '17'
                    }, {
                        data: '18'
                    }, {
                        data: '19'
                    },
                    {
                        data: '20'
                    }, {
                        data: '21'
                    }, {
                        data: '22'
                    }, {
                        data: '23'
                    },
                    {
                        data: '24'
                    }, {
                        data: '>24'
                    }, {
                        data: 'total'
                    }
                ],
                footerCallback: function(row, data, start, end, display) {
                    const api = this.api();
                    for (let i = 1; i < api.columns().count(); i++) {
                        const total = api.column(i).data().reduce((a, b) => {
                            const val = typeof b === 'string' ? parseFloat(b.replace(/,/g,
                                '')) || 0 : b || 0;
                            return a + val;
                        }, 0);
                        $(api.column(i).footer()).html(new Intl.NumberFormat().format(total));
                    }
                }
            });

            // Search Button Click Event
            $('#cari').click(function() {
                const startDate = picker.getStartDate()?.format('YYYY-MM-DD');
                const endDate = picker.getEndDate()?.format('YYYY-MM-DD');
                const $cariButton = $(this);

                if (!startDate || !endDate) {
                    $('#alertMessage').show();
                    return;
                }
                $('#alertMessage').hide();

                $cariButton.prop('disabled', true).html('Loading...');

                $.ajax({
                    url: '{{ route('ritaseDurationSearchAPI') }}',
                    method: 'POST',
                    data: {
                        start1: startDate,
                        end1: endDate,
                        vehicle_type: $('#jenis_kendaraan').val(),
                        status_vehicle: $('#status_kendaraan').val(),
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success && response.data.length > 0) {
                            $('.chartRitase').show();

                            function formatQuantity(quantity) {
                                return new Intl.NumberFormat().format(quantity);
                            }

                            const ritaseDurationData = response.data.map(item => ({
                                tanggal: item.tanggal,
                                '01': formatQuantity(item.jam1),
                                '02': formatQuantity(item.jam2),
                                '03': formatQuantity(item.jam3),
                                '04': formatQuantity(item.jam4),
                                '05': formatQuantity(item.jam5),
                                '06': formatQuantity(item.jam6),
                                '07': formatQuantity(item.jam7),
                                '08': formatQuantity(item.jam8),
                                '09': formatQuantity(item.jam9),
                                '10': formatQuantity(item.jam10),
                                '11': formatQuantity(item.jam11),
                                '12': formatQuantity(item.jam12),
                                '13': formatQuantity(item.jam13),
                                '14': formatQuantity(item.jam14),
                                '15': formatQuantity(item.jam15),
                                '16': formatQuantity(item.jam16),
                                '17': formatQuantity(item.jam17),
                                '18': formatQuantity(item.jam18),
                                '19': formatQuantity(item.jam19),
                                '20': formatQuantity(item.jam20),
                                '21': formatQuantity(item.jam21),
                                '22': formatQuantity(item.jam22),
                                '23': formatQuantity(item.jam23),
                                '24': formatQuantity(item.jam24),
                                '>24': formatQuantity(item.over24jam),
                                total: formatQuantity(item.total),
                            }));

                            ritaseDurationTable.clear().rows.add(ritaseDurationData).draw();

                            // Chart Update Logic
                            const labels = response.data.map(item => item.tanggal);
                            const dataRitaseDuration = response.data.map(item => item.total);
                            const ctx = document.getElementById('RitaseDurationChart')
                                .getContext('2d');

                            if (window.ritaseDurationChart instanceof Chart) {
                                window.ritaseDurationChart.destroy();
                            }

                            const isDarkMode = document.body.classList.contains('mode-gelap');
                            const textColor = isDarkMode ? '#e2e8f0' : '#333';
                            const gridColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' :
                                'rgba(0, 0, 0, 0.1)';


                            window.ritaseDurationChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                        label: 'Total per tanggal',
                                        data: dataRitaseDuration,
                                        backgroundColor: 'rgba(252, 185, 0, 0.8)',
                                        borderColor: 'rgba(252, 185, 0, 1)',
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            ticks: {
                                                color: textColor
                                            },
                                            grid: {
                                                color: gridColor
                                            }
                                        },
                                        x: {
                                            ticks: {
                                                color: textColor
                                            },
                                            grid: {
                                                display: false
                                            }
                                        }
                                    },
                                    plugins: {
                                        legend: {
                                            labels: {
                                                color: textColor
                                            }
                                        },
                                        tooltip: {
                                            callbacks: {
                                                label: (tooltipItem) => formatQuantity(
                                                    tooltipItem.raw)
                                            }
                                        },
                                        datalabels: {
                                            anchor: 'end',
                                            align: 'end',
                                            color: textColor,
                                            font: {
                                                weight: 'bold'
                                            },
                                            formatter: (value) => formatQuantity(value)
                                        }
                                    }
                                },
                                plugins: [ChartDataLabels]
                            });
                        } else {
                            // Handle no data found
                            ritaseDurationTable.clear().draw();
                            $('.chartRitase').hide();
                            alert('No data found for the selected criteria.');
                        }
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr.responseText);
                        alert('An error occurred while fetching data.');
                    },
                    complete: function() {
                        $cariButton.prop('disabled', false).html('Cari');
                    }
                });
            });
        });
    </script>
@endsection
