@extends('layout.nav')
@section('content')
    @php
        $lokasiName = session('selected_location_name', 'Lokasi Default');
        $ipLokasi = session('selected_location_ip_lokasi', 'IP Tidak Diketahui');
        $lokasiId = session('selected_location_id', 0);
        $lokasiGrup = session('selected_location_id_grup', 'Group Tidak Diketahui');
        $navbarTitle = $lokasiName;
    @endphp



    <link rel="stylesheet" href="css/main.css">
    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://code.highcharts.com/stock/highstock.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>


    <h2>{{ $navbarTitle }} - {{ $ipLokasi }} - {{ $lokasiId }} - {{ $lokasiGrup }}</h2>
    <div class="p-1 p-sm-5">
        <div class="col-12">
            <div class="row g-4">
                <div class="col-8">
                    <div class="p-3 rounded shadow border" style="height: 500px;">
                        <h5>Daily Quantity</h5>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-3 rounded shadow border" style="height: 200px;">
                        <h5>Casual Weekly Quantity</h5>
                    </div>
                    <div class="p-3 rounded shadow border mt-5" style="height: 200px;">
                        <h5>Casual Monthly Quantity</h5>
                    </div>
                </div>



            </div>


        </div>
    </div>
@endsection
