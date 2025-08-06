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
    </style>

    <div class="search-wrapper content-custom">
        <div class="row g-3 mb-3 align-items-end">
            <div class="col-md-3">
                <p>Income Gate Search</p>
                <label for="start-date-1" class="form-label text-dark">Start Date</label>
                <input type="text" name="start1" id="start-date-1" class="form-control" placeholder="Select start date" />
            </div>

            <div class="col-auto d-flex align-items-end">
                <div class="fw-semibold pb-2 text-dark">to</div>
            </div>

            <div class="col-md-3">
                <label for="end-date-1" class="form-label text-dark">End Date</label>
                <input type="text" name="end1" id="end-date-1" class="form-control" placeholder="Select end date" />
            </div>
        </div>
        <div class="mt-3">
            <button type="button" class="btn btn-submit" id="cari">Cari</button>
        </div>

    </div>

    <div class="content-custom mt-3">
        <canvas id="incomeGateBar" height="100" style="display: none;"> </canvas>
        <table id="incomeGateSearch" class="table table-striped table-bordered mt-5">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Pos Out</th>
                    <th>Income</th>
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
        const formatRupiah = (number) => {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);
        };
        const incomeGateSearchTable = $('#incomeGateSearch').DataTable({
            pageLength: 100,
            ordering: true,
            lengthChange: false,
            searching: false,
            layout: {
                topEnd: {
                    buttons: true
                }
            },
            columns: [{
                    data: 'no',
                },
                {
                    data: 'pos_out',
                },
                {
                    data: 'income',
                }
            ]
        });
        $('#cari').click(function() {
            const startDate = $('#start-date-1').val();
            const endDate = $('#end-date-1').val();

            $.ajax({
                url: '{{ route('incomeGateSearchApi') }}',
                method: 'POST',
                data: {
                    start1: startDate,
                    end1: endDate,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $("canvas").show();

                    // const resultIncomeGate = response.data.income_gate_periode[0];
                    // console.log(resultIncomeGate);
                    let res = response;

                    // Jika response berupa string, parse dulu
                    if (typeof response === 'string') {
                        res = JSON.parse(response);
                    }

                    // console.log('Parsed response:', res);
                    const incomeGate = res.data[0]?.income_gate_periode;
                    const formattedIncomeGate = incomeGate.map((item, index) => ({
                        no: index + 1,
                        pos_out: item.poscode,
                        income: formatRupiah(item.totalincome)
                    }));

                    incomeGateSearchTable.clear().rows.add(formattedIncomeGate).draw();




                    const colors = [
                        '#D10300', '#9966FF', '#7E00F5', '#36A2EB', '#00FFFF', '#FF6347',
                        '#E67E00', '#F4A460', '#FFCE56', '#90EE90', '#148F49', '#708090',
                        '#DAB370', '#F8AD2B', '#DFC639', '#E3E32A', '#00943E', '#0E17C4',
                        '#057385', '#101A9F', '#4F236E', '#634E32', '#C233EE', '#BC8F8F'
                    ];
                    // Gabungkan labels dan dataIncome ke dalam satu array objek
                    const combined = incomeGate.map(item => ({
                        poscode: item.poscode,
                        totalincome: item.totalincome
                    }));

                    // Urutkan berdasarkan totalincome (dari besar ke kecil)
                    combined.sort((a, b) => b.totalincome - a.totalincome);

                    // Ambil kembali labels dan data setelah diurutkan
                    const labels = combined.map(item => item.poscode);
                    const dataIncome = combined.map(item => item.totalincome);

                    const backgroundColors = labels.map((_, index) => colors[index % colors.length]);
                    // Buat chart data-nya
                    const incomeBarData = {
                        labels: labels,
                        datasets: [{
                            label: 'Statistik Income Gate',
                            data: dataIncome,
                            backgroundColor: backgroundColors,
                            borderWidth: 1
                        }]
                    };


                    const incomeBarConfig = {
                        type: 'bar',
                        data: incomeBarData,
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    labels: {
                                        color: '#fff'
                                    }
                                },
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        color: '#fff'
                                    }
                                },
                                x: {
                                    ticks: {
                                        color: '#fff'
                                    }
                                }
                            }
                        }
                    };
                    const incomeBarCtx = document.getElementById('incomeGateBar').getContext('2d');
                    new Chart(incomeBarCtx, incomeBarConfig);

                }

            })
        })
    </script>
@endsection
