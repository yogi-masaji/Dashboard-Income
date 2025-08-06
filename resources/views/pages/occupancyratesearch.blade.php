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
        /* Apply flextruck to the wrapper that contains the search and buttons */
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
    </style>
    <style>
        .content-custom {
            padding: 20px !important;
            background-color: #092953 !important;
            border-radius: 10px !important;
        }

        .search-wrapper {
            width: 100%;
        }
    </style>
    <style>
        /* .content-custom {
                                                                                                                                                                                                                                                            width: 100%;
                                                                                                                                                                                                                                                            overflow-x: hidden;
                                                                                                                                                                                                                                                            padding: 20px;
                                                                                                                                                                                                                                                            box-sizing: border-box;
                                                                                                                                                                                                                                                        } */

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
            background-color: #00356b;
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
            font-size: 11px;
        }

        /* Rest of the columns distributed equally */
        .wide-data-table th:not(:first-child),
        .wide-data-table td:not(:first-child) {
            width: calc((100% - 80px) / 26);
        }

        /* table.dataTable thead th,
                                                                                                                                                                                                                                                                                table.dataTable thead td {
                                                                                                                                                                                                                                                                                    padding: 2px;
                                                                                                                                                                                                                                                                                    border-bottom: 1px solid #111;
                                                                                                                                                                                                                                                                                } */

        .metrics-container {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .metric-card {
            flex: 1;
            border-radius: 0.5rem;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .metric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .metric-title {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .metric-value {
            font-size: 2.5rem;
            font-weight: 700;
        }

        .parkinglot {
            background-color: #ffe47a;
            color: #78350f;
        }

        .occupancy-rate {
            background-color: #ffa7a7;
            color: #7f1d1d;
        }

        .available-space {
            background-color: #a7ffea;
            color: #065f46;
        }
    </style>
    <div class="search-wrapper">
        <div class="row g-3 mb-3 align-items-end">
            <div class="col-md-3">
                <label for="start-date-1" class="form-label text-dark">Start Date</label>
                <input type="text" name="start1" id="start-date-1" class="form-control" placeholder="Select start date" />
            </div>
            <div class="col-md-2 mt-3">
                <button type="button" class="btn btn-submit" id="cari">Cari</button>
            </div>
        </div>

    </div>

    <div class="content-custom mt-5">
        <h5>
            Current Occupancy Rate
        </h5>
        <hr>
        <div class="d-flex justify-content-between">

            <p>Date: <span class="today-date"></span></p>
            <p id="current-date">Last updated: <span class="current-time"></span> (Updates every 10 minutes)</p>
        </div>

        <div class="metrics-container">
            <div class="metric-card parkinglot">
                <div class="metric-title">Total Parking Lot</div>
                <div class="metric-value">2022</div>
            </div>
            <div class="metric-card occupancy-rate">
                <div class="metric-title">Occupancy Rate</div>
                <div class="metric-value occupancy">0%</div>
            </div>
            <div class="metric-card available-space">
                <div class="metric-title">Available Space</div>
                <div class="metric-value available">0</div>
            </div>
        </div>
    </div>

    <div class="content-custom mt-5 result" style="display: none;">
        <h5>Occupancy Rate On: <span class="tgl-selected"></span></h5>
        <table class="table table-striped table-bordered mt-3" id="occupancyRateTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Time</th>
                    <th>Quantity</th>
                    <th>Occupancy Rate</th>
                </tr>
            </thead>
        </table>
    </div>
    <script>
        const dateInputs = [{
            start: '#start-date-1',
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
        });
    </script>

    <script>
        $(document).ready(function() {
            function formatDate(dateString) {
                const date = new Date(dateString);
                const options = {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                };
                return date.toLocaleDateString('id-ID', options);
            }
            const today = new Date().toISOString().split('T')[0];

            const formattedToday = formatDate(today);

            function updateJakartaTime() {
                const now = new Date();

                const formatter = new Intl.DateTimeFormat('en-GB', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false,
                    timeZone: 'Asia/Jakarta'
                });

                $('.current-time').text(formatter.format(now));
            }

            updateJakartaTime();
            setInterval(updateJakartaTime, 1000);


            $('.today-date').text(formattedToday);
            const occupancyRateTable = $('#occupancyRateTable').DataTable({
                lengthChange: false,
                searching: false,
                paging: false,
                ordering: false,
                info: false,
                layout: {
                    topEnd: {
                        buttons: ['excelHtml5', 'pdfHtml5'],
                    },
                },
                columns: [{
                        data: 'no',
                    },
                    {
                        data: 'time',
                    },
                    {
                        data: 'quantity',
                    },
                    {
                        data: 'occupancy_rate',
                    }
                ],
            });
            $('#cari').click(function() {
                const startDate = $('#start-date-1').val();

                if (!startDate) {
                    alert('Please select a start date.');
                    return;
                }



                $.ajax({
                    url: '{{ route('occupancyRateSearchAPI') }}',
                    method: 'POST',
                    data: {
                        start1: startDate,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log('Response data realtime ocupancy:', response.data_realtime[
                                0]
                            .occupancyrate);
                        $('.result').show();
                        $('.tgl-selected').text(formatDate(startDate));
                        $('.occupancy').text(response.data_realtime[
                                0]
                            .occupancyrate);
                        $('.available').text(response.data_realtime[
                                0]
                            .quantity);
                        // $('.available').text(response.available);
                        // You can update your HTML here to show the results
                        console.log(response);
                        const OccupancyTableData = response.data_unpaid;

                        console.log('ini data unpdaid', OccupancyTableData);

                        const formattedData = OccupancyTableData.map((item, index) => ({
                            no: index + 1,
                            time: item.time,
                            quantity: item.quantity,
                            occupancy_rate: item.occupancyrate,
                        }));

                        console.log('formatted data', formattedData);
                        // Clear the existing data and add the new data
                        occupancyRateTable.clear().rows.add(formattedData).draw();
                        // occupancyRateTable.clear().rows.add(formattedData).draw();
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                    }
                });
            });
        });
    </script>
@endsection
