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
            padding: 5px;
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

        .table thead tr th {
            padding-block: 1.161rem;
        }

        table.dataTable tbody th,
        table.dataTable tbody td {
            padding: 8px 5px;
            font-size: 11px;
        }

        table.dataTable tfoot th,
        table.dataTable tfoot td {
            padding: 8px 5px;
            font-size: 11px;
        }
    </style>


    <div class="search-wrapper mb-3">
        <div class="row g-3 mb-3 align-items-end">

            <div class="col-md-3">
                <label for="vehicle-select" class="form-label">Pilih tahun</label>
                <select class="form-select w-100" id="year-select" aria-label="Select year">
                    @php
                        $currentYear = date('Y');
                    @endphp
                    <option value="{{ $currentYear }}">{{ $currentYear }}</option>
                    <option value="{{ $currentYear - 1 }}">{{ $currentYear - 1 }}</option>
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


    <div class="content-custom">
        <table class="table table-striped table-bordered" id="table-custom">

            <thead>

                <tr>
                    <th scope="col" rowspan="4" class="text-center"
                        style="border-left: 1px solid white; border-top: 1px solid white; border-right: 1px solid white; vertical-align: middle; width:6%;">
                        BULAN</th>
                    <th scope="col" rowspan="1" colspan="13" class="text-center"
                        style="border-left: 1px solid white; border-top: 1px solid white; border-right: 1px solid white;">
                        PARKIR REGULER </th>
                </tr>

                <tr>
                    <th scope="col" colspan="4" rowspan="1" class="text-center"
                        style="border-top: 1px solid white; border-right: 1px solid white;">CARGO</th>
                    <th scope="col" colspan="4" rowspan="1" class="text-center"
                        style="border-top: 1px solid white; border-right: 1px solid white;">TERMINAL</th>
                    <th scope="col" colspan="4" rowspan="1" class="text-center"
                        style="border-top: 1px solid white; border-right: 1px solid white;">TOTAL PRODUKSI</th>
                    <th scope="col" colspan="1" rowspan="3" class="text-center"
                        style="border-top: 1px solid white; border-right: 1px solid white; width:7%; vertical-align: middle;">
                        GRAND TOTAL PENDAPATAN</th>
                </tr>


                <tr>
                    <th scope="col" colspan="2" class="text-center" style="border-right: 1px solid white;">RODA 2</th>
                    <th scope="col" colspan="2" class="text-center" style="border-right: 1px solid white;">RODA 4 & 6
                    </th>
                    <th scope="col" colspan="2" class="text-center" style="border-right: 1px solid white;">RODA 2</th>
                    <th scope="col" colspan="2" class="text-center" style="border-right: 1px solid white;">RODA 4 & 6
                    </th>
                    <th scope="col" colspan="2" class="text-center" style="border-right: 1px solid white;">RODA 2</th>
                    <th scope="col" colspan="2" class="text-center" style="border-right: 1px solid white;">RODA 4 & 6
                    </th>
                </tr>

                <tr>
                    <th scope="col" class="text-center" style="border-right: 1px solid white;">PROD</th>
                    <th scope="col" class="text-center" style="border-right: 1px solid white;">PEND</th>
                    <th scope="col" class="text-center" style="border-right: 1px solid white;">PROD</th>
                    <th scope="col" class="text-center" style="border-right: 1px solid white;">PEND</th>
                    <th scope="col" class="text-center" style="border-right: 1px solid white;">PROD</th>
                    <th scope="col" class="text-center" style="border-right: 1px solid white;">PEND</th>
                    <th scope="col" class="text-center" style="border-right: 1px solid white;">PROD</th>
                    <th scope="col" class="text-center" style="border-right: 1px solid white;">PEND</th>
                    <th scope="col" class="text-center" style="border-right: 1px solid white;">PROD</th>
                    <th scope="col" class="text-center" style="border-right: 1px solid white;">PEND</th>
                    <th scope="col" class="text-center" style="border-right: 1px solid white;">PROD</th>
                    <th scope="col" class="text-center" style="border-right: 1px solid white;">PEND</th>
                </tr>

            </thead>
            <tfoot></tfoot>
        </table>
    </div>

    <script>
        const tableCustom = $('#table-custom').DataTable({
            paging: false,
            searching: false,
            ordering: false,
            info: false,
            autoWidth: false,
            columns: [{
                    data: 'bulan',
                },
                {
                    data: 'cargo_rodadua_prod',
                },
                {
                    data: 'cargo_rodadua_pend',
                },
                {
                    data: 'cargo_rodaempat_prod',
                },
                {
                    data: 'cargo_rodaempat_pend',
                },
                {
                    data: 'terminal_rodadua_prod',
                },
                {
                    data: 'terminal_rodadua_pend',
                },
                {
                    data: 'terminal_rodaempat_prod',
                },
                {
                    data: 'terminal_rodaempat_pend',
                },
                {
                    data: 'totalproduksi_rodadua_prod',
                },
                {
                    data: 'totalproduksi_rodadua_pend',
                },
                {
                    data: 'totalproduksi_rodaempat_prod',
                },
                {
                    data: 'totalproduksi_rodaempat_pend',
                },
                {
                    data: 'grandtotal',
                }
            ]
        });

        function formatQuantity(quantity) {
            return new Intl.NumberFormat().format(quantity);
        }



        $('#cari').click(function() {
            const year = $('#year-select').val();
            const $btn = $(this);

            if (!year) {
                $('#alertMessage').show();
                return;
            } else {
                $('#alertMessage').hide();
            }

            // Ubah tombol jadi loading
            $btn.prop('disabled', true).text('Loading...');

            $.ajax({
                url: '{{ route('prodpendapatansearchAPI') }}',
                method: 'POST',
                data: {
                    year: year,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log(response);
                    const cargo = response.data[0].cargo;
                    const terminal = response.data[0].terminal;
                    const grandtotal = response.data[0].grandtotal;

                    console.log('hasil cargo', cargo);
                    console.log('hasil terminal', terminal);
                    console.log('hasil grandtotal', grandtotal);
                    const listBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli',
                        'Agustus', 'September', 'Oktober', 'November', 'Desember'
                    ];

                    const data = response.data.map((item) => ({
                        bulan: item.bulan,
                        cargo_rodadua_prod: formatQuantity(item.cargo[0].produksi_r2),
                        cargo_rodadua_pend: formatQuantity(item.cargo[0].pendapatan_r2),
                        cargo_rodaempat_prod: formatQuantity(item.cargo[0].produksi_r4_r6),
                        cargo_rodaempat_pend: formatQuantity(item.cargo[0]
                            .pendapatan_r4_r6),
                        terminal_rodadua_prod: formatQuantity(item.terminal[0].produksi_r2),
                        terminal_rodadua_pend: formatQuantity(item.terminal[0]
                            .pendapatan_r2),
                        terminal_rodaempat_prod: formatQuantity(item.terminal[0]
                            .produksi_r4_r6),
                        terminal_rodaempat_pend: formatQuantity(item.terminal[0]
                            .pendapatan_r4_r6),
                        totalproduksi_rodadua_prod: formatQuantity(item.grandtotal[0]
                            .produksi_r2),
                        totalproduksi_rodadua_pend: formatQuantity(item.grandtotal[0]
                            .pendapatan_r2),
                        totalproduksi_rodaempat_prod: formatQuantity(item.grandtotal[0]
                            .produksi_r4_r6),
                        totalproduksi_rodaempat_pend: formatQuantity(item.grandtotal[0]
                            .pendapatan_r4_r6),
                        grandtotal: formatQuantity(item.grandtotal[0]
                            .grandtotal_pendapatan),
                    }));

                    const totalAll = {
                        cargo_rodadua_prod: 0,
                        cargo_rodadua_pend: 0,
                        cargo_rodaempat_prod: 0,
                        cargo_rodaempat_pend: 0,
                        terminal_rodadua_prod: 0,
                        terminal_rodadua_pend: 0,
                        terminal_rodaempat_prod: 0,
                        terminal_rodaempat_pend: 0,
                        totalproduksi_rodadua_prod: 0,
                        totalproduksi_rodadua_pend: 0,
                        totalproduksi_rodaempat_prod: 0,
                        totalproduksi_rodaempat_pend: 0,
                        grandtotal: 0
                    };

                    response.data.forEach(item => {
                        totalAll.cargo_rodadua_prod += parseFloat(item.cargo[0].produksi_r2) ||
                            0;
                        totalAll.cargo_rodadua_pend += parseFloat(item.cargo[0]
                            .pendapatan_r2) || 0;
                        totalAll.cargo_rodaempat_prod += parseFloat(item.cargo[0]
                            .produksi_r4_r6) || 0;
                        totalAll.cargo_rodaempat_pend += parseFloat(item.cargo[0]
                            .pendapatan_r4_r6) || 0;

                        totalAll.terminal_rodadua_prod += parseFloat(item.terminal[0]
                            .produksi_r2) || 0;
                        totalAll.terminal_rodadua_pend += parseFloat(item.terminal[0]
                            .pendapatan_r2) || 0;
                        totalAll.terminal_rodaempat_prod += parseFloat(item.terminal[0]
                            .produksi_r4_r6) || 0;
                        totalAll.terminal_rodaempat_pend += parseFloat(item.terminal[0]
                            .pendapatan_r4_r6) || 0;

                        totalAll.totalproduksi_rodadua_prod += parseFloat(item.grandtotal[0]
                            .produksi_r2) || 0;
                        totalAll.totalproduksi_rodadua_pend += parseFloat(item.grandtotal[0]
                            .pendapatan_r2) || 0;
                        totalAll.totalproduksi_rodaempat_prod += parseFloat(item.grandtotal[0]
                            .produksi_r4_r6) || 0;
                        totalAll.totalproduksi_rodaempat_pend += parseFloat(item.grandtotal[0]
                            .pendapatan_r4_r6) || 0;
                        totalAll.grandtotal += parseFloat(item.grandtotal[0]
                            .grandtotal_pendapatan) || 0;
                    });


                    $('#table-custom tfoot').html(`
                        <tr>
                            <th>Total</th>
                            <th>${formatQuantity(totalAll.cargo_rodadua_prod)}</th>
                            <th>${formatQuantity(totalAll.cargo_rodadua_pend)}</th>
                            <th>${formatQuantity(totalAll.cargo_rodaempat_prod)}</th>
                            <th>${formatQuantity(totalAll.cargo_rodaempat_pend)}</th>
                            <th>${formatQuantity(totalAll.terminal_rodadua_prod)}</th>
                            <th>${formatQuantity(totalAll.terminal_rodadua_pend)}</th>
                            <th>${formatQuantity(totalAll.terminal_rodaempat_prod)}</th>
                            <th>${formatQuantity(totalAll.terminal_rodaempat_pend)}</th>
                            <th>${formatQuantity(totalAll.totalproduksi_rodadua_prod)}</th>
                            <th>${formatQuantity(totalAll.totalproduksi_rodadua_pend)}</th>
                            <th>${formatQuantity(totalAll.totalproduksi_rodaempat_prod)}</th>
                            <th>${formatQuantity(totalAll.totalproduksi_rodaempat_pend)}</th>
                            <th>${formatQuantity(totalAll.grandtotal)}</th>
                        </tr>
                    `);


                    tableCustom.clear().rows.add(data).draw();
                    console.log('data mapping', data);


                    console.log('list bulan', listBulan);
                    $btn.prop('disabled', false).text('Cari');
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('Terjadi kesalahan saat mengambil data.');
                    // Kembalikan tombol seperti semula
                    $btn.prop('disabled', false).text('Cari');
                }
            });
        });
    </script>
@endsection
