@extends('layout.nav')
@section('content')
    @php
        $lokasiName = session('selected_location_name', 'Lokasi Default');
        $ipLokasi = session('selected_location_ip_lokasi', 'IP Tidak Diketahui');
        $lokasiId = session('selected_location_id', 0);
        $lokasiGrup = session('selected_location_id_grup', 'Group Tidak Diketahui');
        $kodeLokasi = session('selected_location_kode_lokasi', 'Kode Tidak Diketahui');
        $navbarTitle = $lokasiName;
    @endphp



    <link rel="stylesheet" href="css/main.css">
    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://code.highcharts.com/stock/highstock.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script>
        const kodeLokasi = @json($kodeLokasi);
    </script>

    <style>
        .nav-custom-tab {
            background-color: #185BB4;
            padding: 10px;
            border-radius: 10px;
        }
    </style>

    <style>
        .custom-nav {
            background-color: #185BB4 !important;
            padding: 10px;
            border-radius: 10px;
            width: 40% !important;
        }

        .nav-pills~.tab-content {
            box-shadow: none !important;
        }

        .tab-content {
            background-color: transparent !important;
            width: 100% !important;
        }

        .dashboard-card {
            background-color: #2A3A5A;
            border-radius: 10px;
            border: 2px solid #D9D9D9;
            padding: 13px;
            margin-bottom: 15px;
        }

        .card-title {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 5px;
            color: #FFFFFF !important;
        }

        .card-value {
            font-size: 25px;
            font-weight: 700;
            margin-bottom: 0;
            color: #FFFFFF;
        }

        .percentage {
            color: #ff4d4d;
            font-size: 14px;
            font-weight: bold;
        }

        .yesterday {
            font-size: 14px;
            opacity: 0.8;
            margin-top: 5px;
        }

        .tab-content:not(.doc-example-content) {
            padding: 0.5rem;
        }

        .nav-tabs {
            padding: 10px;
            background-color: #003b86;
            border-radius: 10px;
        }

        .content-custom {
            padding: 10px !important;
            background-color: #092953 !important;
            border-radius: 10px !important;
        }
    </style>


    <ul class="nav nav-custom-tab nav-pills mb-3 w-100" id="pills-tab" role="tablist">
        @if (isset($tabMenus['Transaction']))
            <li class="nav-item flex-fill text-center" role="presentation">
                <button class="nav-link active w-100" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home"
                    type="button" role="tab" aria-controls="pills-home" aria-selected="true">
                    Transaction
                </button>
            </li>
        @endif

        @if (isset($tabMenus['Income']))
            <li class="nav-item flex-fill text-center" role="presentation">
                <button class="nav-link {{ !isset($tabMenus['Transaction']) ? 'active' : '' }} w-100" id="pills-profile-tab"
                    data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab"
                    aria-controls="pills-profile" aria-selected="false">
                    Income
                </button>
            </li>
        @endif

        @if (isset($tabMenus['E-Payment']))
            <li class="nav-item flex-fill text-center" role="presentation">
                <button
                    class="nav-link {{ !isset($tabMenus['Transaction']) && !isset($tabMenus['Income']) ? 'active' : '' }} w-100"
                    id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button"
                    role="tab" aria-controls="pills-contact" aria-selected="false">
                    E-Payment
                </button>
            </li>
        @endif

        @if (isset($tabMenus['Traffic Management']))
            <li class="nav-item flex-fill text-center" role="presentation">
                <button
                    class="nav-link {{ !isset($tabMenus['Transaction']) && !isset($tabMenus['Income']) && !isset($tabMenus['E-Payment']) ? 'active' : '' }} w-100"
                    id="pills-traffic-tab" data-bs-toggle="pill" data-bs-target="#pills-traffic" type="button"
                    role="tab" aria-controls="pills-traffic" aria-selected="false">
                    Traffic Management
                </button>
            </li>
        @endif
    </ul>

    <div class="tab-content" id="pills-tabContent">
        @if (isset($tabMenus['Transaction']))
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                {{-- @require('tab-content.transaction') --}}
                @include('tab-content.transaction')
                {{-- @cachedInclude('tab-content.transaction', 'cached.transaction') --}}
            </div>
        @endif

        @if (isset($tabMenus['Income']))
            <div class="tab-pane fade {{ !isset($tabMenus['Transaction']) ? 'show active' : '' }}" id="pills-profile"
                role="tabpanel" aria-labelledby="pills-profile-tab">
                {{-- @require('tab-content.income') --}}
                @include('tab-content.income')
                {{-- @cachedInclude('tab-content.income', 'cached.income') --}}
            </div>
        @endif

        @if (isset($tabMenus['E-Payment']))
            <div class="tab-pane fade {{ !isset($tabMenus['Transaction']) && !isset($tabMenus['Income']) ? 'show active' : '' }}"
                id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                {{-- @require('tab-content.epayment') --}}
                @include('tab-content.epayment')
                {{-- @cachedInclude('tab-content.epayment', 'cached.epayment') --}}
            </div>
        @endif

        @if (isset($tabMenus['Traffic Management']))
            <div class="tab-pane fade {{ !isset($tabMenus['Transaction']) && !isset($tabMenus['Income']) && !isset($tabMenus['E-Payment']) ? 'show active' : '' }}"
                id="pills-traffic" role="tabpanel" aria-labelledby="pills-traffic-tab">
                {{-- @require('tab-content.traffic') --}}
                @include('tab-content.traffic')
                {{-- @cachedInclude('tab-content.traffic', 'cached.traffic') --}}
            </div>
        @endif
    </div>
@endsection
