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
            margin-bottom: 5px;
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
            width: 40%;
        }
    </style>

    <div class="search-wrapper">
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
        </div>



        <div class="mt-3">
            <button type="button" class="btn btn-submit" id="cari">Cari</button>
        </div>

        <!-- Alert Message -->
        <div id="alertMessage" class="alert alert-danger mt-3" role="alert" style="display: none;">
            Please fill in all the date fields before submitting.
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-6">
            <div class="content-custom mb-2">
                <table id="tablePos" class="table table-striped table-bordered">
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
                </table>
            </div>
        </div>
        <div class="col-md-6">
            <div class="content-custom mb-2">
                <table id="tableGolongan" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Jam</th>
                            <th>Mobil</th>
                            <th>Motor</th>
                            <th>Truk</th>
                            <th>Total</th>
                        </tr>
                    </thead>
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
        const tablePos = $('#tablePos').DataTable({
            paging: false,
            searching: false,
            ordering: false,
            info: false,
            autoWidth: false,
            layout: {
                topEnd: {
                    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5', 'print'],
                },
            },
            columns: [{
                    data: 'jam',
                },
                {
                    data: 'pos1'
                },
                {
                    data: 'pos8'
                },
                {
                    data: 'pos9'
                },
                {
                    data: 'buffer_area'
                },
                {
                    data: 'total'
                }
            ]
        })

        const tableGolongan = $('#tableGolongan').DataTable({
            paging: false,
            searching: false,
            ordering: false,
            info: false,
            autoWidth: false,
            layout: {
                topEnd: {
                    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5', 'print'],
                },
            },
            columns: [{
                    data: 'jam',
                },
                {
                    data: 'mobil'
                },
                {
                    data: 'motor'
                },
                {
                    data: 'truk'
                },
                {
                    data: 'total'
                }
            ]
        })
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

            function formatQuantity(quantity) {
                return new Intl.NumberFormat().format(quantity);
            }

            const formatRupiah = (number) => {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(number);
            };
            $.ajax({
                url: '{{ route('quantitypergateAPI') }}',
                method: 'POST',
                data: {
                    start1: startDate,
                    end1: endDate,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // Here you can also call a function to render the table

                        const {
                            pos1,
                            pos8,
                            pos9,
                            buffer_area,
                        } = response.per_pos;

                        const {
                            mobil,
                            motor,
                            truk,
                            totalGolongan,
                        } = response.per_golongan;

                        const formatTimeRange = (timeRange) => {
                            const [start, end] = timeRange.split("-");
                            return `${start.slice(0, 5)}-${end.slice(0, 5)}`;
                        };

                        const dataPos = pos1.map((item, index) => {
                            return {
                                jam: formatTimeRange(item.time),
                                pos1: formatQuantity(pos1[index]?.qty),
                                pos8: formatQuantity(pos8[index]?.qty),
                                pos9: formatQuantity(pos9[index]?.qty),
                                buffer_area: formatQuantity(buffer_area[index]?.qty),
                                total: formatQuantity(
                                    pos1[index]?.qty +
                                    pos8[index]?.qty +
                                    pos9[index]?.qty +
                                    buffer_area[index]?.qty
                                )
                            };
                        });

                        tablePos.clear().rows.add(dataPos).draw();

                        const dataGolongan = mobil.map((item, index) => {
                            return {
                                jam: formatTimeRange(item.time),
                                mobil: formatQuantity(mobil[index]?.qty),
                                motor: formatQuantity(motor[index]?.qty),
                                truk: formatQuantity(truk[index]?.qty),
                                total: formatQuantity(
                                    mobil[index]?.qty +
                                    motor[index]?.qty +
                                    truk[index]?.qty
                                )
                            };
                        });

                        tableGolongan.clear().rows.add(dataGolongan).draw();




                    } else {
                        alert('No data found!');
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('An error occurred while fetching data.');
                },
                complete: function() {
                    // Re-enable button and reset text after AJAX (success or error)
                    $cariButton.prop('disabled', false).html('Cari');
                }
            });
        });
    </script>
@endsection
