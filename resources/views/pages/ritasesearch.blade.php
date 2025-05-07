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
            background-color: #092953 !important;
            border-radius: 10px !important;
        }

        .search-wrapper {
            width: 70%;
        }
    </style>

    <p>Ritase Search</p>
    <div class="search-wrapper">
        <div class="row g-3 mb-3 align-items-end">
            <div class="col-md-3">
                <label for="start-date-1" class="form-label">Start Date</label>
                <input type="text" name="start1" id="start-date-1" class="form-control" placeholder="Select start date" />
            </div>

            <div class="col-auto d-flex align-items-end">
                <div class="fw-semibold pb-2">to</div>
            </div>

            <div class="col-md-3">
                <label for="end-date-1" class="form-label">End Date</label>
                <input type="text" name="end1" id="end-date-1" class="form-control" placeholder="Select end date" />
            </div>
        </div>
        <div class="mt-3">
            <button type="button" class="btn btn-submit" id="cari">Cari</button>
        </div>
    </div>

    <div class="content-custom mt-5">
        <div class="row">
            <div class="col-md-6">
                <h5 class="text-center">Member</h5>
                <table id="RitaseMember" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Nomor Plat</th>
                            <th>Quantity Keluar</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="col-md-6">
                <h5 class="text-center">Casual</h5>
                <table id="RitaseCasual" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Nomor Plat</th>
                            <th>Quantity Keluar</th>
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
        const memberTable = $('#RitaseMember').DataTable({
            dom: "Bfltip",
            pageLength: 100,
            ordering: true,
            lengthChange: false,
            layout: {
                topEnd: {
                    search: true,
                    buttons: true
                }
            },
            columns: [{
                    data: 'no',
                },
                {
                    data: 'nama',
                },
                {
                    data: 'nomor_plat',
                },
                {
                    data: 'quantity_keluar',
                }
            ]
        });

        const casualTable = $('#RitaseCasual').DataTable({
            dom: "Bfltip",
            pageLength: 100,
            ordering: true,
            lengthChange: false,
            layout: {
                topEnd: {
                    search: true,
                    buttons: true
                }
            },
            columns: [{
                    data: 'no',
                },
                {
                    data: 'nama',
                },
                {
                    data: 'nomor_plat',
                },
                {
                    data: 'quantity_keluar',
                }
            ]
        });
        $('#cari').click(function() {
            const startDate = $('#start-date-1').val();
            const endDate = $('#end-date-1').val();

            $.ajax({
                url: '{{ route('ritaseSearchApi') }}',
                method: 'POST',
                data: {
                    start1: startDate,
                    end1: endDate,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log(response.data[0].member);
                    console.log(response.data[0].casual);
                    const memberData = response.data[0].member.map((item, index) => ({
                        no: index + 1,
                        nama: item.nama,
                        nomor_plat: item.kodeproduk,
                        quantity_keluar: item.quantity
                    }));

                    const casualData = response.data[0].casual.map((item, index) => ({
                        no: index + 1,
                        nama: item.nama,
                        nomor_plat: item.kodeproduk,
                        quantity_keluar: item.quantity
                    }));

                    memberTable.clear().rows.add(memberData).draw();
                    casualTable.clear().rows.add(casualData).draw();
                }
            })
        })
    </script>
@endsection
