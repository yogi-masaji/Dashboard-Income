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
        /* Apply flexbox to the wrapper that contains the search and buttons */
        #membershipTable_wrapper .dt-top {
            display: flex;
            justify-content: flex-start;
            /* Align buttons and search to the left */
            gap: 20px;
            /* Add space between the buttons and search */
            align-items: center;
        }

        /* table.dataTable thead th,
                                                                                                                                                                                                                                                                                                                            table.dataTable thead td {
                                                                                                                                                                                                                                                                                                                                padding: 16px;
                                                                                                                                                                                                                                                                                                                                border-bottom: 1px solid #111
                                                                                                                                                                                                                                                                                                                            } */

        tbody {
            white-space: normal;
            word-break: break-all;
        }

        /* Ensure the buttons are inline and spaced correctly */
        .dt-buttons {
            display: inline-flex;
            gap: 10px;
            /* Space between individual buttons */
        }

        /* Make sure the search input aligns properly */
        .dt-search input {
            display: inline-block;
            margin-right: 10px;
            /* Space between the search input and buttons */
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
            margin-top: 5px;
        }

        .dt-button {
            background-color: #FCB900 !important;
            padding: 10px;
            border-radius: 10px;
            border: none !important;
            margin-bottom: 5px;
        }

        #dt-search-0 {
            height: 40px;
            border-radius: 10px;
            margin-left: 10px;
        }

        .wide-data-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            font-size: 12px;
            text-align: center;
        }

        .wide-data-table th,
        .wide-data-table td {
            /* padding: 4px 6px; */
            border: 1px solid #ddd;
            word-wrap: break-word;
        }

        /* Optional: sticky header */
        .wide-data-table thead th {
            position: sticky;
            top: 0;
            /* background-color: #00356b; */
            z-index: 1;
        }

        .wide-data-table td {
            position: sticky;
            top: 0;
            background-color: #00356b;
            z-index: 1;
        }

        /* Adjust column widths */
        .wide-data-table th:first-child,
        .wide-data-table td:first-child {
            width: 75px;
            /* Adjust this as needed */
            font-size: 12px;
        }

        /* Rest of the columns distributed equally */
        .wide-data-table th:not(:first-child),
        .wide-data-table td:not(:first-child) {
            width: calc((100% - 80px) / 26);
        }

        table.dataTable thead th,
        table.dataTable thead td {
            padding: 10px 0 !important;

        }

        table.dataTable tbody th,
        table.dataTable tbody td {
            padding: 10px 0 !important;

        }

        table.dataTable tfoot th,
        table.dataTable tfoot td {
            padding: 10px 0 !important;
            font-size: 10px;
        }
    </style>
    <style>
        .content-custom {
            padding: 10px !important;
            background-color: #ffffff !important;
            border-radius: 10px !important;
            box-shadow: 1px -2px 15px -1px rgba(0, 0, 0, 0.28);
            color: #000000 !important;
        }
    </style>


    <div class="search-wrapper content-custom">
        <div class="d-flex align-items-end gap-3 mb-3">
            <div>
                <label for="start-date-1" class="form-label text-dark">Start Date</label>
                <input type="text" name="start1" id="start-date-1" class="form-control" placeholder="Select start date" />
            </div>
            <div class="pb-3 fw-semibold">to</div>
            <div>
                <label for="end-date-1" class="form-label text-dark">End Date</label>
                <input type="text" name="end1" id="end-date-1" class="form-control" placeholder="Select end date" />
            </div>
            <div>
                <label for="end-date-1" class="form-label text-dark">Vehicle Type</label>
                <select name="selection" id="jenis_kendaraan" class="form-select">
                    <option value="ALL">ALL</option>
                    <option value="CAR">CAR</option>
                    <option value="MOTORCYCLE">MOTORCYCLE</option>
                    <option value="TRUCK">TRUCK</option>
                </select>
            </div>
            <div>
                <label for="end-date-1" class="form-label text-dark">Vehicle Status</label>
                <select name="selection" id="status_kendaraan" class="form-select">
                    <option value="ALL">ALL</option>
                    <option value="CASUAL">CASUAL</option>
                    <option value="MEMBER">MEMBER</option>
                </select>
            </div>
        </div>



        <div class="mt-3">
            <button type="button" class="btn btn-submit" id="cari">Cari</button>
        </div>

        <!-- Alert Message -->
        <div id="alertMessage" class="alert alert-danger mt-3" role="alert" style="display: none;">
            Please fill in all the date fields before submitting.
        </div>
    </div>
    <div class="result mt-5">
        <div class="content-custom mb-5">
            <h5>Data Ritase By Duration</h5>
            <div class="chartRitase" style="height: 350px;display: none;">
                <canvas id="RitaseDurationChart" height="350px"></canvas>
            </div>
        </div>
        <div class="content-custom">
            <div class="table-scroll-wrapper">
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

    <script>
        const dateInputs = [{
            start: '#start-date-1',
            end: '#end-date-1'
        }];

        dateInputs.forEach(pair => {
            $(pair.start).daterangepicker({
                singleDatePicker: true,
                autoApply: true,
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            }).on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD'));
            });

            $(pair.end).daterangepicker({
                singleDatePicker: true,
                autoApply: true,
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            }).on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD'));
            });
        });
    </script>


    <script>
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
                }, {
                    data: '04'
                },
                {
                    data: '05'
                }, {
                    data: '06'
                }, {
                    data: '07'
                }, {
                    data: '08'
                }, {
                    data: '09'
                },
                {
                    data: '10'
                }, {
                    data: '11'
                }, {
                    data: '12'
                }, {
                    data: '13'
                }, {
                    data: '14'
                },
                {
                    data: '15'
                }, {
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
                }, {
                    data: '24'
                },
                {
                    data: '>24'
                }, {
                    data: 'total'
                }
            ],
            footerCallback: function(row, data, start, end, display) {
                const api = this.api();

                for (let i = 1; i < api.columns().count(); i++) {
                    let total = 0;
                    api.column(i).data().each(function(value) {
                        const number = typeof value === 'string' ? parseFloat(value.replace(/,/g,
                            '')) || 0 : value;
                        total += number;
                    });
                    $(api.column(i).footer()).html(new Intl.NumberFormat().format(total));
                }
            }
        });

        $('#cari').click(function() {


            const startDate = $('#start-date-1').val();
            const endDate = $('#end-date-1').val();
            const $cariButton = $(this);

            if (!startDate || !endDate) {
                $('#alertMessage').show();
                return;
            } else {
                $('#alertMessage').hide();
            }

            // Disable button and show loading text
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
                    if (response.success) {
                        // Here you can also call a function to render the table
                        console.log(response.data);
                        console.log(response.summary);
                        $('.chartRitase').show();
                        //01 = jam1 
                        // >24 = over24jam
                        function formatQuantity(quantity) {
                            return new Intl.NumberFormat().format(quantity);
                        }

                        function formatDate(dateString) {
                            const date = new Date(dateString);
                            const options = {
                                day: 'numeric',
                                month: 'long',
                                year: 'numeric'
                            };
                            return date.toLocaleDateString('id-ID', options);
                        }
                        const labels = response.data.map(item => formatDate(item.tanggal));
                        const dataRitaseDuration = response.data.map(item => item
                            .total);

                        console.log(dataRitaseDuration);

                        const ritaseDurationData = response.data.map((item, index) => ({
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

                        // Clear the table before adding new data
                        ritaseDurationTable.clear().rows.add(ritaseDurationData).draw();


                        // Create the chart
                        const ctx = document.getElementById('RitaseDurationChart').getContext('2d');

                        // Hancurkan chart lama dulu (jika ada)
                        if (window.ritaseDurationChart) {
                            window.ritaseDurationChart.destroy();
                        }

                        const ritaseData = {
                            labels: labels,
                            datasets: [{
                                label: 'Total per tanggal',
                                data: dataRitaseDuration,
                                backgroundColor: 'rgba(255, 99, 132, 1)',
                                datalabels: {
                                    anchor: 'end',
                                    align: 'end'
                                }
                            }]
                        };

                        const ritaseDataOptions = {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return formatQuantity(value);
                                        },
                                        color: '#fff',
                                    },
                                    grace: '10%'
                                },
                                x: {
                                    ticks: {
                                        color: '#fff',
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top',
                                    labels: {
                                        color: '#fff',
                                        font: {
                                            size: 14,
                                            family: 'Arial',
                                            weight: 'bold'
                                        }
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(tooltipItem) {
                                            return formatQuantity(tooltipItem.raw);
                                        }
                                    }
                                },
                                datalabels: {
                                    backgroundColor: (context) => context.dataset
                                        .backgroundColor,
                                    borderRadius: 4,
                                    color: 'white',
                                    font: {
                                        weight: 'bold'
                                    },
                                    formatter: Math.round,
                                    padding: 6,
                                    offset: 8
                                }
                            }
                        };

                        // Buat chart baru setelah memastikan yang lama sudah dihancurkan
                        window.ritaseDurationChart = new Chart(ctx, {
                            type: 'bar',
                            data: ritaseData,
                            options: ritaseDataOptions,
                            plugins: [
                                ChartDataLabels
                            ]
                        });







                    } else {
                        // alert('No data found!');
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    // alert('An error occurred while fetching data.');
                },
                complete: function() {
                    // Re-enable button and reset text after AJAX (success or error)
                    $cariButton.prop('disabled', false).html('Cari');
                }
            });
        });
    </script>
@endsection
