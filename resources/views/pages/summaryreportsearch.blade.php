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

    <p>Summary Report Search</p>
    <div class="search-wrapper">
        <div class="row g-3 mb-3 align-items-end">
            <div class="col-md-3">
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

            <div class="col-md-3">
                <label for="vehicle-select" class="form-label text-dark">Select Vehicle</label>
                <select class="form-select w-100 text-dark" id="vehicle-select" aria-label="Select vehicle">
                    <option selected disabled>--Vehicle--</option>
                    <option value="1">Car</option>
                    <option value="2">Motorbike</option>
                    <option value="3">Box</option>
                    <option value="4">Valet</option>
                    <option value="5">Preffered</option>
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

    <div class="result">
        <div class="text-center">
            <h5 id="dataResults">Data Report: Car</h5>
        </div>

        <div class="content-custom mt-3">
            <table id="summaryReportTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Date</th>
                        <th>Issued</th>
                        <th>Return</th>
                        <th>Member</th>
                        <th>Paid</th>
                        <th>Lost</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody id="summaryReportBody">
                    <!-- Data will be populated here -->
                </tbody>
            </table>
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
        const summaryReportTable = $('#summaryReportTable').DataTable({
            // dom: "Bfltip",
            pageLength: 100,
            ordering: true,
            lengthChange: false,
            layout: {
                topEnd: {
                    // buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5'],
                },
            },
            columns: [{
                    data: 'no'
                },
                {
                    data: 'date'
                },
                {
                    data: 'issued'
                },
                {
                    data: 'return'
                },
                {
                    data: 'member'
                },
                {
                    data: 'paid'
                },
                {
                    data: 'lost'
                },
                {
                    data: 'revenue'
                }
            ]
        });

        $('#cari').click(function() {
            const startDate = $('#start-date-1').val();
            const endDate = $('#end-date-1').val();
            const vehicleType = $('#vehicle-select').find(":selected").text(); // Ambil teks pilihan dari dropdown


            function formatDate(dateString) {
                const options = {
                    weekday: 'short'
                };
                const dateObj = new Date(dateString);

                const day = String(dateObj.getDate()).padStart(2, '0'); // 01, 02, dst
                const weekday = dateObj.toLocaleDateString('en-US',
                    options); // Mon, Tue, Wed, etc.

                return `${day} - ${weekday}`;
            }
            const formatRupiah = (number) => {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(number);
            };
            if (!startDate || !endDate || !vehicleType) {
                $('#alertMessage').show();
                return;
            } else {
                $('#alertMessage').hide();
            }

            // Kirim data ke server menggunakan AJAX
            $.ajax({
                url: '{{ route('summaryReportSearch') }}',
                method: 'POST',
                data: {
                    start1: startDate,
                    end1: endDate,
                    vehicleType: vehicleType, // Kirim tipe kendaraan
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log(response);



                    $('#dataResults').text('Data Results : ' + vehicleType);
                    const summary = response.first_period;
                    const formattedData = summary.map((item, index) => {
                        let issued = 0;
                        let returned = 0;
                        let member = 0;
                        let paid = 0;
                        let lost = 0;
                        let revenue = 0;

                        if (vehicleType === 'Car') {
                            issued = item.car_in + item.carpass_in;
                            returned = item.car_out + item.carpass_out;
                            member = item.carpass_out;
                            paid = item.car_out;
                            lost = item.carlt;
                            revenue = item.carincome + item.carltincome;
                        } else if (vehicleType === 'Motorbike') {
                            issued = item.motorbike_in + item.motorbikepass_in;
                            returned = item.motorbike_out + item.motorbikepass_out;
                            member = item.motorbikepass_out;
                            paid = item.motorbike_out;
                            lost = item.motorbikelt;
                            revenue = item.motorbikeincome + item.motorbikeltincome;
                        } else if (vehicleType === 'Box') {
                            issued = item.truck_in + item.truckpass_in;
                            returned = item.truck_out + item.truckpass_out;
                            member = item.truckpass_out;
                            paid = item.truck_out;
                            lost = item.trucklt;
                            revenue = item.truckincome;
                        } else if (vehicleType === 'Valet') {
                            issued = item.valetqtylobby_in + item.valetqtynonlobby_in;
                            returned = item.valetqtylobby_out + item.valetqtynonlobby_out;
                            member = 0;
                            paid = item.valetqtylobby_out + item.valetqtynonlobby_out + item
                                .valetramayanaqty + item.valetnonramayanaqty + item
                                .valetvoucherqty;
                            lost = item.valetramayanalt + item.valetnonramayanalt + item
                                .valetvoucherlt;
                            revenue = item.valetincomelobby + item.valetincomenonlobby + item
                                .valetramayanaincome + item.valetnonramayanaincome + item
                                .valetvoucherincome + item.valetlobbyltincome + item
                                .valetnonlobbyltincome + item.valetramayanaltincome + item
                                .valetnonramayanaltincome + item.valetvoucherltincome;;
                        } else if (vehicleType === 'Preffered') {
                            issued = item.preferredqty_in;
                            returned = item.preferredqty_out;
                            member = 0;
                            paid = item.preferredqty_out;
                            lost = item.lostticketincome;
                            revenue = item.preferredincome + item.preferredltincome;
                        }

                        return {
                            no: index + 1,
                            date: formatDate(item.dateTrx),
                            issued: issued,
                            return: returned,
                            member: member,
                            paid: paid,
                            lost: lost,
                            revenue: formatRupiah(revenue)
                        };
                    });


                    summaryReportTable.clear().rows.add(formattedData).draw();

                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('Error while fetching data.');
                }
            });
        });
    </script>
@endsection
