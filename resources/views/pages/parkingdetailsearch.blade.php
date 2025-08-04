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
            background-color: #092953 !important;
            border-radius: 10px !important;
        }

        .search-wrapper {
            width: 40%;
        }
    </style>

    <p class="text-dark"">detail parkir search</p>
    <div class="search-wrapper">
        <div class="d-flex align-items-end gap-3 mb-3">
            <div>
                <label for="start-date-1" class="form-label text-dark">Start Date</label>
                <input type="text" name="start1" id="start-date-1" class="form-control" placeholder="Select start date" />
            </div>
            <div class="pb-3 fw-semibold text-dark">to</div>
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
    <div class="result mt-5">

        <table id="detailParkirTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal Masuk</th>
                    <th>Tanggal Keluar</th>
                    <th>Nopol</th>
                    <th>Barcode</th>
                    <th>Kendaraan</th>
                    <th>Tarif Parkir</th>
                    <th>Dendalt</th>
                    <th>Post Masuk</th>
                    <th>Post Keluar</th>
                    <th>Bank</th>
                    <th>Shift</th>
                    <th>Status</th>
                </tr>
            </thead>
        </table>
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
        let detailParkirTable = null; // buat variabel di luar scope fungsi

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
                url: '{{ route('parkingDetailSearch') }}',
                method: 'POST',
                data: {
                    start1: startDate,
                    end1: endDate,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        const detailParkirData = response.data.data;

                        const formattedDetailParkir = detailParkirData.map((item, index) => ({
                            no: index + 1,
                            tanggal_masuk: item.tglmasuk,
                            tanggal_keluar: item.tglkeluar,
                            nopol: item.noplat,
                            barcode: item.nobarcode,
                            kendaraan: item.namavehicle,
                            tarif_parkir: item.tarif,
                            dendalt: item.dendalt,
                            post_masuk: item.kodeposin,
                            post_keluar: item.kodeposout,
                            bank: item.bank,
                            shift: item.kodeshiftin,
                            status: item.statustransaction
                        }));

                        // Inisialisasi hanya sekali
                        if (!$.fn.DataTable.isDataTable('#detailParkirTable')) {
                            detailParkirTable = $('#detailParkirTable').DataTable({
                                dom: "Bfltip",
                                pageLength: 100,
                                ordering: true,
                                lengthChange: false,
                                paging: true,
                                layout: {
                                    topEnd: {
                                        buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5',
                                            'pdfHtml5'
                                        ],
                                    },
                                },
                                columns: [{
                                        data: 'no'
                                    },
                                    {
                                        data: 'tanggal_masuk'
                                    },
                                    {
                                        data: 'tanggal_keluar'
                                    },
                                    {
                                        data: 'nopol'
                                    },
                                    {
                                        data: 'barcode'
                                    },
                                    {
                                        data: 'kendaraan'
                                    },
                                    {
                                        data: 'tarif_parkir'
                                    },
                                    {
                                        data: 'dendalt'
                                    },
                                    {
                                        data: 'post_masuk'
                                    },
                                    {
                                        data: 'post_keluar'
                                    },
                                    {
                                        data: 'bank'
                                    },
                                    {
                                        data: 'shift'
                                    },
                                    {
                                        data: 'status'
                                    }
                                ]
                            });
                        }

                        // Update data
                        detailParkirTable.clear().rows.add(formattedDetailParkir).draw();
                    } else {
                        alert('No data found!');
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('An error occurred while fetching data.');
                },
                complete: function() {
                    $cariButton.prop('disabled', false).html('Cari');
                }
            });
        });
    </script>
@endsection
