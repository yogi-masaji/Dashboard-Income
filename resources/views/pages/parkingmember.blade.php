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

    <p>Parking Member Search</p>
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
    <div class="row gap-5 mt-5">
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 style="color: #000000 !important;" class="text-dark mb-1">Total Motorbike</h6>
                            <h4 style="color: #000000 !important;" class="fw-bold mb-0 totalMotorbike"></h4>
                            <small style="color: #000000 !important;" class="text-dark countMotorbike"></small>
                        </div>
                        <div class="text-success fs-4"><i class="bi bi-scooter"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 style="color: #000000 !important;" class="text-dark mb-1">Total Car</h6>
                            <h4 style="color: #000000 !important;" class="fw-bold mb-0 totalCar"></h4>
                            <small style="color: #000000 !important;" class="text-dark countCar"></small>
                        </div>
                        <div class="text-success fs-4"><i class="bi bi-car-front-fill"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 style="color: #000000 !important;" class="text-dark mb-1">Total Box</h6>
                            <h4 style="color: #000000 !important;" class="fw-bold mb-0 totalBox"></h4>
                            <small style="color: #000000 !important;" class="text-dark countBox"></small>
                        </div>
                        <div class="text-success fs-4"><i class="bi bi-truck"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="result mt-5">
        <table id="ParkirMemberTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Periode</th>
                    <th>Vehicle Type</th>
                    <th>Tenant Name</th>
                    <th>Plat Number</th>
                    <th>Total Amount</th>
                    <th>Start Date</th>
                    <th>Finish Date</th>
                    <th>Description</th>
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
            const table = $('#ParkirMemberTable').DataTable({
                dom: "Bfltip",
                pageLength: 100,
                ordering: true,
                lengthChange: false,
                bDestroy: true,
                layout: {
                    topEnd: {
                        buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5'],
                    },
                },
                columns: [{
                        data: 'periode'
                    },
                    {
                        data: 'vehicleType'
                    },
                    {
                        data: 'nameTenant'
                    },
                    {
                        data: 'vehiclePlateNumber'
                    },
                    {
                        data: 'grandTotalAmount'
                    },
                    {
                        data: 'startDate'
                    },
                    {
                        data: 'endDate'
                    },
                    {
                        data: 'description'
                    }
                ],
            });

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
                url: '{{ route('parkingMemberSearch') }}',
                method: 'POST',
                data: {
                    start1: startDate,
                    end1: endDate,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // Here you can also call a function to render the table
                        console.log(response);
                        const totalMotorbike = formatRupiah(response.summary.motor.total_income);
                        const totalCar = formatRupiah(response.summary.mobil.total_income);
                        const totalBox = formatRupiah(response.summary.box.total_income);
                        const countMotorbike = formatQuantity(response.summary.motor.count);
                        const countCar = formatQuantity(response.summary.mobil.count);
                        const countBox = formatQuantity(response.summary.box.count);
                        $('.countMotorbike').text(countMotorbike + ' Motorbike');
                        $('.totalMotorbike').text(totalMotorbike);
                        $('.totalCar').text(totalCar);
                        $('.countCar').text(countCar + ' Car');
                        $('.totalBox').text(totalBox);
                        $('.countBox').text(countBox + ' Box');
                        const parkingMemberData = response.data;
                        console.log(parkingMemberData);
                        const formattedData = parkingMemberData.map((item, index) => ({
                            periode: item.transactionDate,
                            vehicleType: item.vehicleType,
                            nameTenant: item.nameTenant,
                            vehiclePlateNumber: item.vehiclePlateNumber,
                            grandTotalAmount: item.grandTotalAmount,
                            startDate: item.startDate,
                            endDate: item.endDate,
                            description: item.description
                        }));
                        table.clear().rows.add(formattedData).draw();


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
