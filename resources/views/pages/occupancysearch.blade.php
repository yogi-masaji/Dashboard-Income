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

        table.dataTable thead th,
        table.dataTable thead td {
            padding: 16px;
            border-bottom: 1px solid #111
        }

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
            padding: 10px !important;
            background-color: #ffffff !important;
            border-radius: 10px !important;
            box-shadow: 1px -2px 15px -1px rgba(0, 0, 0, 0.28);
            color: #000000 !important;
        }

        .search-wrapper {
            width: 100%;
        }
    </style>
    <style>
        .content-custom {
            width: 100%;
            overflow-x: hidden;
            padding: 20px;
            box-sizing: border-box;
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
            background-color: #ffffff;
            z-index: 1;
        }

        .mode-gelap .wide-data-table thead th {
            background-color: #192e50;
        }

        .mode-gelap .wide-data-table td {
            background-color: #192e50;
            color: #ffffff;
        }

        .wide-data-table td {
            position: sticky;
            top: 0;
            background-color: #ffffff;
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

        table.dataTable thead th,
        table.dataTable thead td {
            padding: 2px;
            border-bottom: 1px solid #111;
        }
    </style>
    <div class="search-wrapper">
        <div class="row g-3 mb-3 align-items-end">
            <div class="col-md-3">
                <label for="start-date-1" class="form-label text-dark">Start Date</label>
                <input type="text" name="start1" id="start-date-1" class="form-control" placeholder="Select start date" />
            </div>
        </div>
        <div class="mt-3">
            <button type="button" class="btn btn-submit" id="cari">Cari</button>
        </div>

    </div>

    <div class="content-custom">
        <div class="table-scroll-wrapper">
            <table class="wide-data-table" id="occupancyTable">
                <thead>
                    <tr>
                        <th>Floor Name</th>
                        <th>00:00</th>
                        <th>01:00</th>
                        <th>02:00</th>
                        <th>03:00</th>
                        <th>04:00</th>
                        <th>05:00</th>
                        <th>06:00</th>
                        <th>07:00</th>
                        <th>08:00</th>
                        <th>09:00</th>
                        <th>10:00</th>
                        <th>11:00</th>
                        <th>12:00</th>
                        <th>13:00</th>
                        <th>14:00</th>
                        <th>15:00</th>
                        <th>16:00</th>
                        <th>17:00</th>
                        <th>18:00</th>
                        <th>19:00</th>
                        <th>20:00</th>
                        <th>21:00</th>
                        <th>22:00</th>
                        <th>23:00</th>

                        <th>Total</th>
                        <th>Average</th>
                    </tr>
                </thead>

            </table>
        </div>
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
        const occupancyTable = $('#occupancyTable').DataTable({
            // dom: "Bfltip",
            searching: false,
            paging: false,
            pageLength: false,
            lengthChange: false,

            // layout: {
            //     topEnd: {
            //         buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5'],
            //     },
            // },
            columns: [{
                    data: 'floor_name'
                },
                {
                    data: '00:00'
                },
                {
                    data: '01:00'
                },
                {
                    data: '02:00'
                },
                {
                    data: '03:00'
                },
                {
                    data: '04:00'
                },
                {
                    data: '05:00'
                },
                {
                    data: '06:00'
                },
                {
                    data: '07:00'
                },
                {
                    data: '08:00'
                },
                {
                    data: '09:00'
                },
                {
                    data: '10:00'
                },
                {
                    data: '11:00'
                },
                {
                    data: '12:00'
                },
                {
                    data: '13:00'
                },
                {
                    data: '14:00'
                },
                {
                    data: '15:00'
                },
                {
                    data: '16:00'
                },
                {
                    data: '17:00'
                },
                {
                    data: '18:00'
                },
                {
                    data: '19:00'
                },
                {
                    data: '20:00'
                },
                {
                    data: '21:00'
                },
                {
                    data: '22:00'
                },
                {
                    data: '23:00'
                },

                {
                    data: 'total'
                },
                {
                    data: 'average'
                }
            ]
        });
        $(document).ready(function() {
            $('#cari').click(function() {
                const startDate = $('#start-date-1').val();

                if (!startDate) {
                    alert('Please select a start date.');
                    return;
                }

                $.ajax({
                    url: '{{ route('occupancySearchAPI') }}',
                    method: 'POST',
                    data: {
                        start1: startDate,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {

                        // You can update your HTML here to show the results
                        const occupancyData = response.data;
                        console.log(occupancyData)

                        const formattedOccupancyData = occupancyData.map((item, index) => ({
                            floor_name: item.floor_name,
                            '00:00': item.jam1,
                            '01:00': item.jam2,
                            '02:00': item.jam3,
                            '03:00': item.jam4,
                            '04:00': item.jam5,
                            '05:00': item.jam6,
                            '06:00': item.jam7,

                            '07:00': item.jam8,
                            '08:00': item.jam9,
                            '09:00': item.jam10,
                            '10:00': item.jam11,
                            '11:00': item.jam12,
                            '12:00': item.jam13,
                            '13:00': item.jam14,
                            '14:00': item.jam15,
                            '15:00': item.jam16,
                            '16:00': item.jam17,
                            '17:00': item.jam18,
                            '18:00': item.jam19,
                            '19:00': item.jam20,
                            '20:00': item.jam21,
                            '21:00': item.jam22,
                            '22:00': item.jam23,
                            '23:00': item.jam24,

                            total: item.total,
                            average: item.average
                        }));

                        occupancyTable.clear().rows.add(formattedOccupancyData).draw();
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                    }
                });
            });
        });
    </script>
@endsection
